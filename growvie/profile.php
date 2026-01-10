<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "Growvie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = 'USR001';

$user_query = "SELECT u.*, up.player_tier, up.eco_coins, up.drops_progress, up.total_quests_completed, 
               up.tree_planted_irl, up.growvie_plants_planted 
               FROM user u 
               LEFT JOIN user_player up ON u.user_id = up.user_id 
               WHERE u.user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

$friend_code = $user['user_id'];

$current_tier = $user['player_tier'];
$drops_progress = $user['drops_progress'];
$tier_thresholds = [0, 5000, 15000, 30000]; 
$next_tier_drops = isset($tier_thresholds[$current_tier]) ? $tier_thresholds[$current_tier] : 50000;
$prev_tier_drops = isset($tier_thresholds[$current_tier - 1]) ? $tier_thresholds[$current_tier - 1] : 0;
$progress_in_tier = $drops_progress - $prev_tier_drops;
$drops_needed = $next_tier_drops - $prev_tier_drops;
$progress_percentage = ($progress_in_tier / $drops_needed) * 100;

$history_query = "SELECT vp.*, p.plant_name, vp.date_planted, vp.is_completed, vp.current_stage
                  FROM virtual_plant vp 
                  JOIN plant p ON vp.plant_id = p.plant_id 
                  WHERE vp.user_id = ? 
                  ORDER BY vp.date_planted DESC 
                  LIMIT 5";
$stmt = $conn->prepare($history_query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$history_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

    <style>
    html, body {
    margin: 0;
    padding: 0;
    height: 100%;
}

*, *::before, *::after {
    box-sizing: border-box;
}

html {
    scrollbar-gutter: stable;
}

    body {
        overflow-x: hidden;
    }


    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap'); 
    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap');

    .container-wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        gap:20px;
    }


    .sidepanel {
        width: 260px;
        flex-shrink: 0;
    }


    .settings-content {
        flex: 1; /* take remaining space */
        padding: 20px 30px;
        background-color: #DAE5D7;
        margin: 30px 10px 0 0;
        border-radius: 16px 16px 0 0;
        font-family: "Encode Sans Expanded";
        box-sizing: border-box;
        overflow-y: auto; /* scroll only content */
        min-width:0;
    }

     .header {
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    }
    .header h1 {
    margin-bottom: 5px;
    }

    .date {
    margin: 20px 0 10px;
    font-size: 14px;
    color: #010101ff;
    }
    .card {
    background: #ffffffff;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .card-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    }
    .card-header span {
    font-size: 12px;
    color: #a1a1a1ff;
    }
    .card p {
    font-size: 14px;
    color: #555;
    line-height: 1.5;
    }
    .content {
    flex: 1;
    padding: 30px 40px 30px 20px;
    }
    
    .friend-box {
    background: #f1f7ee;
    padding: 6px 12px;
    border-radius: 10px;
    }

    .friend-box span {
    font-weight: bold;
    margin-right: 6px;
    }

    .friend-code-section {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 10px;
        background: #C8D9C2;
        padding: 12px 20px;
        border-radius: 12px;
    }

    .friend-code-label {
        font-size: 14px;
        color: #000000ff;
        font-weight: 500;
    }

    .friend-code-box {
        background: #ffffff;
        padding: 8px 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        border: none;
    }

    .friend-code-box span {
        font-weight: 600;
        color: #000;
    }
    
    .copy-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #888;
        font-size: 14px;
    }
    
    .copy-btn:hover {
        color: #555;
    }
    
    .profile-header {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }
    
    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #f0d45f;
        object-fit: cover;
    }
    
    .profile-info {
        flex: 1;
    }
    
    .profile-name {
        font-size: 22px;
        font-weight: 600;
        color: #333;
        margin-bottom: 3px;
    }
    
    .profile-handle {
        color: #888;
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .level-container {
        background: #f8f8f8;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
    }
    
    .level-badge {
        background: #99e78a;
        color: #2d5016;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 15px;
    }
    
    .progress-bar {
        position: relative;
        height: 12px;
        background: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #99e78a 0%, #7dd66f 100%);
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .progress-labels {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #888;
    }
    
    .history-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }
    
    .history-scroll {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    
    .history-scroll::-webkit-scrollbar {
        height: 6px;
    }
    
    .history-scroll::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 10px;
    }
    
    .history-scroll::-webkit-scrollbar-thumb {
        background: #99e78a;
        border-radius: 10px;
    }
    
    .plant-card {
        min-width: 140px;
        background: #f0f5ed;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
    }
    
    .plant-icon {
        font-size: 60px;
        margin-bottom: 10px;
    }
    
    .plant-status {
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 3px;
    }
    
    .plant-status.completed {
        color: #4caf50;
    }
    
    .plant-status.germinated {
        color: #ff9800;
    }
    
    .plant-status.planted {
        color: #2196f3;
    }
    
    .plant-type {
        font-size: 12px;
        color: #666;
        margin-bottom: 2px;
    }
    
    .plant-date {
        font-size: 10px;
        color: #999;
    }
    
    .nav-buttons-container {
        display: flex;
        gap: 15px;
        padding: 20px;
    }
    
    .nav-btn-link {
        flex: 1;
        text-decoration: none;
    }
    
    .nav-btn {
        width: 100%;
        padding: 15px 20px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #2d5016;
    }
    
    .nav-btn.history {
        background: #99e78a;
    }
    
    .nav-btn.quest {
        background: #99e78a;
    }
    
    .nav-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .nav-btn:active {
        transform: translateY(0);
    }


</style>
</head>
<body>
    <div class = "container-wrapper">
        <?php include "sidepanel.php"; ?>

        <!--main dashboard-->
        <div class="settings-content">
            <section class="header">
            <div>
                <h1>Profile</h1>
                <p>Bloom where you are planted!</p>
            </div>

            <div class="friend-code-section">
                <span class="friend-code-label">Friend Code</span>
                <div class="friend-code-box">
                    <span><?php echo $friend_code; ?></span>
                    <button class="copy-btn" onclick="copyFriendCode()" title="Copy to clipboard">📋</button>
                </div>
            </div>
        </section>

            <div class="card">
            <div class="profile-header">
                <img src="jamalchong.png" alt="<?php echo htmlspecialchars($user['name']); ?>" class="profile-image">
                
                <div class="profile-info">
                    <h2 class="profile-name"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p class="profile-handle">@<?php echo htmlspecialchars($user['username']); ?></p>
                    
                    <div class="level-container">
                        <div class="level-badge">Lv.<?php echo $current_tier; ?></div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?php echo min($progress_percentage, 100); ?>%;"></div>
                        </div>
                        <div class="progress-labels">
                            <span>0</span>
                            <span><?php echo number_format($prev_tier_drops + ($drops_needed * 0.25)); ?></span>
                            <span><?php echo number_format($prev_tier_drops + ($drops_needed * 0.5)); ?></span>
                            <span><?php echo number_format($prev_tier_drops + ($drops_needed * 0.75)); ?></span>
                            <span><?php echo number_format($next_tier_drops); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="history-title">Growvie Plants History</h3>
            <div class="history-scroll">

                <?php if ($history_result->num_rows > 0): ?>
                <?php while ($plant = $history_result->fetch_assoc()): ?>

                    <?php
                        if ($plant['is_completed'] == 1) {
                            $status = 'Completed';
                            $status_class = 'completed';
                            $plant_img = 'Full plant.png';
                        } elseif ($plant['current_stage'] >= 3) {
                            $status = 'Germinated';
                            $status_class = 'germinated';
                            $plant_img = 'Germinated.png';
                        } else {
                            $status = 'Planted';
                            $status_class = 'planted';
                            $plant_img = 'Wilted.png';
                        }
                    ?>

                    <div class="plant-card">
                        <img src="<?php echo $plant_img; ?>" class="plant-icon" alt="">
                        <div class="plant-status <?php echo $status_class; ?>">
                            <?php echo $status; ?>
                        </div>
                        <div class="plant-type"><?php echo htmlspecialchars($plant['plant_name']); ?></div>
                        <div class="plant-date"><?php echo date('m/d/Y', strtotime($plant['date_planted'])); ?></div>
                    </div>

                <?php endwhile; ?>
                <?php else: ?>

                    <p style="color: #888;">No plants yet. Start your journey!</p>

                <?php endif; ?>

            </div>
        </div>

        <div class="card nav-buttons-container">
            <a href="history.php" class="nav-btn-link">
                <button class="nav-btn history">
                    View Full Planting History
                </button>
            </a>
            <a href="questsubmit.php" class="nav-btn-link">
                <button class="nav-btn quest">
                    View Submitted Quests
                </button>
            </a>
        </div>

    </main>

</div>


<?php
$conn->close();
?>
        </div>
    </div>

    <script>
function copyFriendCode() {
    const friendCode = '<?php echo $friend_code; ?>';
    navigator.clipboard.writeText(friendCode).then(function() {
        alert('Friend code copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}

function logout() {
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'login.php';
    }
}
</script>
</body>
</html>
