<?php
require_once __DIR__ . '/db.php';

// Get data for analytics from database
function getAnalyticsData($con) {
    $data = [];

    // Get data for user count
    $sqlUsers = "SELECT COUNT(*) as total FROM user WHERE role='Player'";
    $resUsers = mysqli_query($con, $sqlUsers);
    $data['total_users'] = mysqli_fetch_assoc($resUsers)['total'];

    // Get data for virtual plants planted
    $sqlPlants = "SELECT COUNT(*) as total FROM virtual_plant";
    $resPlants = mysqli_query($con, $sqlPlants);
    $data['total_plants'] = mysqli_fetch_assoc($resPlants)['total'];

    // Get data for partner count
    $sqlActivePartners = "SELECT COUNT(*) as total FROM partner WHERE partner_status='Active'";
    $resActivePartners = mysqli_query($con, $sqlActivePartners);
    $data['active_partners'] = mysqli_fetch_assoc($resActivePartners)['total'];

    // Get data for real tree planting records
    $sqlRequests = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN request_status = 'Approved' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN request_status = 'Pending' THEN 1 ELSE 0 END) as pending
        FROM real_tree_record";
    $resReq = mysqli_query($con, $sqlRequests);
    $reqData = mysqli_fetch_assoc($resReq);
    
    $data['req_total'] = $reqData['total'] ?: 0;
    $data['req_completed'] = $reqData['completed'] ?: 0;
    $data['req_pending'] = $reqData['pending'] ?: 0;
    $data['req_percent'] = ($data['req_total'] > 0) 
        ? round(($data['req_completed'] / $data['req_total']) * 100) 
        : 0;

    // Get data for revenue amount
    $sqlRev = "SELECT SUM(si.item_price) as revenue 
               FROM user_purchase up 
               JOIN shop_item si ON up.item_id = si.item_id 
               WHERE si.item_category = 'In App Purchases'";
    $resRev = mysqli_query($con, $sqlRev);
    $revRaw = mysqli_fetch_assoc($resRev)['revenue'];
    $data['revenue_total'] = number_format(($revRaw ?: 0), 2);

    // Get data for revenue category breakdown
    $sqlCat = "SELECT si.item_category, COUNT(*) as count 
               FROM user_purchase up 
               JOIN shop_item si ON up.item_id = si.item_id 
               GROUP BY si.item_category";
    $resCat = mysqli_query($con, $sqlCat);
    
    $data['categories'] = ['Plant Seeds' => 0, 'Power Ups' => 0, 'In App Purchases' => 0];
    while($row = mysqli_fetch_assoc($resCat)) {
        $data['categories'][$row['item_category']] = $row['count'];
    }

    // Get data for chart
    $months = [];
    $userCounts = [];
    $questCounts = [];
    $plantCounts = [];
    $requestCounts = [];

    // Display data for 3 months before and 2 months after the current month
    for ($i = -3; $i <= 2; $i++) {
        $date = date('Y-m', strtotime("$i months"));
        $label = date('M', strtotime("$i months"));
        $months[] = $label;

        // Users Joined
        $sqlU = "SELECT COUNT(*) as c FROM user WHERE date_joined LIKE '$date%' AND role='Player'";
        $userCounts[] = mysqli_fetch_assoc(mysqli_query($con, $sqlU))['c'];

        // Quests Completed
        $sqlQ = "SELECT COUNT(*) as c FROM quest_submission WHERE submitted_at LIKE '$date%' AND approval_status='Approved'";
        $questCounts[] = mysqli_fetch_assoc(mysqli_query($con, $sqlQ))['c'];

        // Virtual Plants Planted
        $sqlP = "SELECT COUNT(*) as c FROM virtual_plant WHERE date_planted LIKE '$date%'";
        $plantCounts[] = mysqli_fetch_assoc(mysqli_query($con, $sqlP))['c'];

        // Real Trees Handled (Approved)
        $sqlR = "SELECT COUNT(*) as c FROM real_tree_record WHERE date_reported LIKE '$date%' AND request_status='Approved'";
        $requestCounts[] = mysqli_fetch_assoc(mysqli_query($con, $sqlR))['c'];
    }

    $data['chart_labels'] = json_encode($months);
    $data['chart_users'] = json_encode($userCounts);
    $data['chart_quests'] = json_encode($questCounts);
    $data['quests_this_month'] = $questCounts[3]; 
    $data['users_this_month'] = $userCounts[3];  

    // Calculate the growth percentage for user count, virtual planting and request handling
    function calcGrowth($current, $last) {
        if ($last > 0) return round((($current - $last) / $last) * 100);
        return ($current > 0) ? 100 : 0;
    }

    $data['user_growth_percent'] = calcGrowth($userCounts[3], $userCounts[2]);
    $data['plant_growth_percent'] = calcGrowth($plantCounts[3], $plantCounts[2]);
    $data['request_growth_percent'] = calcGrowth($requestCounts[3], $requestCounts[2]);

    // Define colours for pie chart
    $data['colors'] = ['#8ecf73', '#5fb85f', '#2d6a4f'];

    return $data;
}

// Render HTML content for App Analytics Tab
function renderAnalyticsTab($analytics) {
    ?>
    <div class="grid">
        <!-- First Row -->
        <!-- Total Active Users Card -->
        <div class="card short-card span-2">
            <span class="label">Total Active Users</span>
            <h2><?php echo $analytics['total_users']; ?></h2>
            <div class="trend-text">
                <span class="trend <?php echo ($analytics['user_growth_percent'] >= 0) ? 'up' : 'down'; ?> trend-icon">
                    <?php echo ($analytics['user_growth_percent'] >= 0) ? '▲' : '▼'; ?>
                </span>
                <span class="trend-content">
                    <span class="trend-percent"><?php echo abs($analytics['user_growth_percent']); ?>%</span>
                    <span class="trend-detail"> <?php echo ($analytics['user_growth_percent'] >= 0) ? 'increase' : 'decrease'; ?> in player count<span class="trend-period"> since last month</span></span>
                </span>
            </div>
        </div>

        <!-- Virtual Plants Planted Card -->
        <div class="card short-card span-2">
            <span class="label">Growvie Plants Planted</span>
            <h2><?php echo $analytics['total_plants']; ?></h2>
            <div class="trend-text">
                <span class="trend <?php echo ($analytics['plant_growth_percent'] >= 0) ? 'up' : 'down'; ?> trend-icon">
                    <?php echo ($analytics['plant_growth_percent'] >= 0) ? '▲' : '▼'; ?>
                </span>
                <span class="trend-content">
                    <span class="trend-percent"><?php echo abs($analytics['plant_growth_percent']); ?>%</span>
                    <span class="trend-detail"> <?php echo ($analytics['plant_growth_percent'] >= 0) ? 'increase' : 'decrease'; ?> in planting<span class="trend-period"> since last month</span></span>
                </span>
            </div>
        </div>

        <!-- Total Active Partners Card -->
        <div class="card short-card span-2">
            <span class="label">Total Active Partners</span>
            <h2><?php echo $analytics['active_partners']; ?></h2>
            <div class="trend-text">
                <span class="trend <?php echo ($analytics['request_growth_percent'] >= 0) ? 'up' : 'down'; ?> trend-icon">
                    <?php echo ($analytics['request_growth_percent'] >= 0) ? '▲' : '▼'; ?>
                </span>
                <span class="trend-content">
                    <span class="trend-percent"><?php echo abs($analytics['request_growth_percent']); ?>%</span>
                    <span class="trend-detail"> <?php echo ($analytics['request_growth_percent'] >= 0) ? 'increase' : 'decrease'; ?> in requests handled<span class="trend-period"> since last month</span></span>
                </span>
            </div>
        </div>

        <!-- Planting Requests Progress Bar Card -->
        <div class="card short-card span-5">
            <span class="label">Planting Requests</span>
            
            <div class="progress-wrapper">
                <div class="progress-bar analytics-progress-track">
                    <div class="progress" style="width: <?php echo $analytics['req_percent']; ?>%"></div>
                </div>
                <span class="info-label progress-text"><?php echo $analytics['req_percent']; ?>%</span>
            </div>
            
            <div class="analytics-stats-grid">
                <span class="info-label">Completed</span>
                <span class="info-value"><?php echo $analytics['req_completed']; ?></span>
                
                <span class="info-label">Pending</span>
                <span class="info-value"><?php echo $analytics['req_pending']; ?></span>
                
                <span class="info-label">Total Requests</span>
                <span class="info-value"><?php echo $analytics['req_total']; ?></span>
            </div>
        </div>

        <!-- Second Row -->
        <!-- Revenue Earned & Revenue Breakdown Pie Chart Card -->
        <div class="card span-3">
            <span class="label">Revenue Earned</span>
            <h2>RM<?php echo $analytics['revenue_total']; ?></h2>

            <div class="donut-row">
                <div class="donut-chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>

                <ul class="legend">
                    <li><span style="background: <?php echo $analytics['colors'][0]; ?>;"></span> Seeds</li>
                    <li><span style="background: <?php echo $analytics['colors'][1]; ?>;"></span> Power-ups</li>
                    <li><span style="background: <?php echo $analytics['colors'][2]; ?>;"></span> In-App</li>
                </ul>
            </div>
        </div>

        <!-- New User Registration Line Graph Card -->
        <div class="card span-4">
            <span class="label">New User Registration</span>
            <h2><?php echo $analytics['users_this_month']; ?> <small>new users this month</small></h2>
            <div class="line-chart-container">
                <canvas id="userChart"></canvas>
            </div>
        </div>

        <!-- Quests Completed Line Graph Card -->
        <div class="card span-4">
            <span class="label">Quests Completed</span>
            <h2><?php echo $analytics['quests_this_month']; ?> <small>quests completed this month</small></h2>
            <div class="line-chart-container">
                <canvas id="questChart"></canvas>
            </div>
        </div>

    </div>
    <?php
}

// Render JS for charts
function renderAnalyticsScripts($analytics) {
    // Pass in PHP colours
    $colorsJS = json_encode($analytics['colors']); 
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userChartCanvas = document.getElementById('userChart');
            if (!userChartCanvas) return;

            // Render Revenue Breakdown Pie Chart
            const ctxRev = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRev, {
                type: 'pie',
                data: {
                    labels: ['Seeds', 'Power Ups', 'In-App Purchases'],
                    datasets: [{
                        data: [
                            <?php echo $analytics['categories']['Plant Seeds']; ?>, 
                            <?php echo $analytics['categories']['Power Ups']; ?>, 
                            <?php echo $analytics['categories']['In App Purchases']; ?>
                        ],
                        // Use the System Colors we defined in PHP
                        backgroundColor: <?php echo $colorsJS; ?>,
                        borderWidth: 1, // Add slight border for definition
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
            
            // Render New User Registration Line Graph
            new Chart(userChartCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: <?php echo $analytics['chart_labels']; ?>, 
                    datasets: [{
                        label: 'New Users',
                        data: <?php echo $analytics['chart_users']; ?>,
                        borderColor: '#8ecf73',
                        backgroundColor: 'rgba(142, 207, 115, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
                }
            });

            // Render Quests Completed Line Graph
            new Chart(document.getElementById('questChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: <?php echo $analytics['chart_labels']; ?>,
                    datasets: [{
                        label: 'Quests',
                        data: <?php echo $analytics['chart_quests']; ?>,
                        borderColor: '#5fb85f',
                        backgroundColor: 'rgba(95, 184, 95, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
                }
            });
        });
    </script>
    <?php
}
?>