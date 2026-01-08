<?php
require_once __DIR__ . "/backend/db.php";
require_once __DIR__ . "/backend/modal.php";
require_once __DIR__ . "/backend/dashboard.php";
require_once __DIR__ . "/backend/questsubmission.php";
require_once __DIR__ . "/backend/usermanagement.php";

handleCreateQuest($con);
handleReviewAction($con);
handleUserActions($con);
renderDashboardScripts();
renderUserManagementScripts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growvie Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/maincontentcss.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/questsubmission.css">
    <link rel="stylesheet" href="css/announcement.css">
    <link rel="stylesheet" href="css/shopmanagement.css">
    <link rel="stylesheet" href="css/usermanagement.css">
    <link rel="stylesheet" href="css/partnermanagement.css">
    <link rel="stylesheet" href="css/analytics.css">    

    <script>
        // Simple Tab Switcher
        function tab(t) {
            localStorage.setItem('activeTab', t);
            
            // Toggle Active Menu Class
            document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));
            document.getElementById('tab' + t).classList.add('active');

            // Toggle Content Visibility
            document.querySelectorAll('.content').forEach(el => el.classList.add('hidden'));
            const activeContent = document.getElementById('content' + t);
            if(activeContent) activeContent.classList.remove('hidden');
        }

        // Auto-Run on Load
        window.addEventListener('DOMContentLoaded', () => {
            // 1. Restore Tab
            tab(localStorage.getItem('activeTab') || 1);

            // 2. Handle URL Success Messages (Simplified)
            const params = new URLSearchParams(window.location.search);
            const modal = document.getElementById('successModal');
            
            // Map URL actions to Title/Message
            const messages = {
                'approved':       ['Submission Approved!', 'Evidence verified. User rewarded.'],
                'rejected':       ['Submission Rejected', 'Submission declined and removed.'],
                'quest_success':  ['Success!', 'Operation completed successfully.'],
                'status_updated': ['Status Updated', 'User account status toggled.'],
                'user_deleted':   ['User Deleted', 'Account permanently removed.']
            };

            // Check if any param matches our keys
            const action = params.get('review_success') || params.get('quest_success') ? 'quest_success' : params.get('action');
            const match = messages[action] || messages[params.get('review_success')]; // specific check for review values

            if (match) {
                modal.querySelector('h3').innerText = match[0];
                modal.querySelector('p').innerText = match[1];
                modal.style.display = 'block';
                // Clean URL
                window.history.replaceState({}, document.title, "final.php");
            }
        });
    </script>
</head>

<body>
    <div class = "layout"> 
        <!--sidebar-->
        <nav class="sidebar">
            <div class="logo">
                <img src="assets/Logo.png" alt="Growvie">
                <span>Growvie</span>
            </div>

        <!--sidebar menu-->
        <div class="menu">
            <div class="menu-item" onclick="tab(1)" id="tab1">
                <img src="assets/dashboard.png" class="menu-icon">
                Dashboard
            </div>

            <div class="menu-item" onclick="tab(2)" id="tab2">
                <img src="assets/announcement.png" class="menu-icon">
                Quest Submissions
            </div>

            <div class="menu-item" onclick="tab(3)" id="tab3">
                <img src="assets/announcement.png" class="menu-icon">
                Announcements
            </div>

            <div class="menu-item" onclick="tab(4)" id="tab4">
                <img src="assets/shop.png" class="menu-icon">
                Shop Management
            </div>

            <div class="menu-item" onclick="tab(5)" id="tab5">
                <img src="assets/user.png" class="menu-icon">
                User Management
            </div>

            <div class="menu-item" onclick="tab(6)" id="tab6">
                <img src="assets/partner.png" class="menu-icon">
                Partner Organization
            </div>

            <div class="menu-item" onclick="tab(7)" id="tab7">
                <img src="assets/appanalytics.png" class="menu-icon">
                App Analytics
            </div>
        </div>

        <!--logout-->
        <div class="logout">
            <img src="assets/logout.png" class="logout-icon">
            Log Out
        </div>
        </nav>

        <!--main content-->
        <main class="main">
            
            <!-- DASHBOARD TAB -->
            <div class="content" id="content1">
                <div class="dashboard-left-column">
                    <h2>Welcome back, Jamal</h2>
                    <p class="subtext">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                    <h3>Active Quests</h3>

                    <button class="action-btn create" onclick="openCreateModal()">+ Create a new quest</button>

                    <div class="quests" id="quests-container">
                        <?php renderQuestCards($con); ?>
                    </div>

                    <h3 id="inactive-section-title">Inactive Quests</h3>

                    <div class="quests">
                        <?php renderInactiveQuestCards($con); ?>
                    </div>
                </div>

                <aside class="leaderboard">
                    <h3>Daily Leaderboard</h3>
                    <?php renderLeaderboard($con, 10); ?>
                </aside>
            </div>

            <!-- Quest submission tab -->
            <div class="content hidden" id="content2">
                <div class="dashboard-left-column">
                    <h2>Quest Submissions</h2>
                    <p class="subtext">Review community tasks and approve/reject pending evidence.</p>
                    
                    <div class="review-container">
                        <?php renderReviewTab($con); ?>
                    </div>
                </div>

                <aside class="leaderboard">
                    <h3>Approval Log</h3>
                    <?php renderSubmissionHistory($con); ?>
                </aside>
            </div>

            <!-- ANNOUNCEMENT TAB -->
            <div class="content hidden" id="content3">
                <h2>Announcements</h2>
                <p class="subtext">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                <button class="create-announcement-btn">+ Create a new announcement</button>

                <div class="date-header">27 November 2025</div>

                <div class="announcements-list">
                    <!-- Announcement Card 1 -->
                    <div class="announcement-card">
                        <div class="announcement-header">
                            <h3 class="announcement-title">Announcement Title</h3>
                            <span class="announcement-time">11:38PM</span>
                        </div>
                        <p class="announcement-content">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nisi leo, faucibus sed lobortis bibendum, vehicula eget ante. In finibus ligula sit amet arcu eleifend, a porta sem aliquam. Nunc laoreet scelerisque semper. Aenean venenatis felis nibh, et luctus nibh mollis quis. Vestibulum sit amet lobortis tellus. Praesent finibus pretium lectus, at imperdiet erat. Maecenas interdum lacus at eros lacinia, quis facilisis dolor faucibus. Duis iaculis congue lacus, at condimentum massa suscipit id. Nulla bibendum, odio at aliquet semper, felis risus tincidunt arcu, eget sodales mi ex et dolor. Aliquam mattis ultrices orci a cursus. Donec fermentum viverra leo.
                        </p>
                        <div class="announcement-actions">
                            <button class="action-btn edit">Edit</button>
                            <button class="action-btn delete">Delete</button>
                        </div>
                    </div>

                    <!-- Announcement Card 2 -->
                    <div class="announcement-card">
                        <div class="announcement-header">
                            <h3 class="announcement-title">Announcement Title</h3>
                            <span class="announcement-time">11:38PM</span>
                        </div>
                        <p class="announcement-content">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nisi leo, faucibus sed lobortis bibendum, vehicula eget ante. In finibus ligula sit amet arcu eleifend, a porta sem aliquam. Nunc laoreet scelerisque semper. Aenean venenatis felis nibh, et luctus nibh mollis quis. Vestibulum sit amet lobortis tellus. Praesent finibus pretium lectus, at imperdiet erat. Maecenas interdum lacus at eros lacinia, quis facilisis dolor faucibus. Duis iaculis congue lacus, at condimentum massa suscipit id. Nulla bibendum, odio at aliquet semper, felis risus tincidunt arcu, eget sodales mi ex et dolor. Aliquam mattis ultrices orci a cursus. Donec fermentum viverra leo.
                        </p>
                        <div class="announcement-actions">
                            <button class="action-btn edit">Edit</button>
                            <button class="action-btn delete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--Shop management tab-->
            <div class="content hidden" id="content4"> 
                <h1>Shop Management</h1>
                <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>


                    <div class="tabs">
                        <button class="tab">Plant Seeds</button>
                        <button class="tab">Power-Ups</button>
                        <button class="tab">Profile Customization</button>
                        <button class="tab">In-App Purchases</button>
                    </div>

                    <div class="grid">

                        <!-- Add New Seed -->
                        <div class="card add-card">
                            <span class="plus">+</span>
                            <p>Add new <br> plant seed</p>
                        </div>

                        <!-- Product Card (change to real data) -->
                        <div class="card">
                            <img src="https://i.imgur.com/3g7nmJC.png" alt="Seeds">
                            <h3>Plant Name A Seeds</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                            <div class="bottom-row">
                                <span class="price">🌕 1,000</span>
                                <button class="edit-btn">Edit</button>
                            </div>
                        </div>
                    </div>
            </div>

            <!--User management tab-->
            <div class="content hidden" id="content5">
                <h1>User Management</h1>
                <p class="subtitle">Manage and monitor Growvie users, partners, and administrators.</p>

                <div class="top-row">
                    <div class="tabs">
                        <?php $currentRole = $_GET['role'] ?? 'Player'; ?>
                        <button class="tab <?php echo ($currentRole == 'Player') ? 'active' : ''; ?>" onclick="userTab('Player')">Users</button>
                        <button class="tab <?php echo ($currentRole == 'Partner') ? 'active' : ''; ?>" onclick="userTab('Partner')">Partners</button>
                    </div>

                    <div class="top-actions">
                        <?php if ($currentRole === 'Partner'): ?>
                            <button class="action-btn green" onclick="openAddPartnerModal()">
                                + Add Partner
                            </button>
                        <?php endif; ?>

                        <div class="search-filter">
                            <input type="text" id="userSearchInput" placeholder="Search..." 
                                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                                onkeypress="if(event.key === 'Enter') handleUserSearch()">
                            <button class="filter-btn" onclick="handleUserSearch()">🔍 Search</button>
                        </div>
                    </div>
                    
                </div>

                <div id="user-list-container">
                    <?php 
                        // Call the render function we built earlier
                        renderUserManagement($con, $currentRole, $_GET['search'] ?? ''); 
                    ?>
                </div>
            </div>
    
            <!--Partner organizer tab-->
            <div class="content hidden" id="content6">
                <div class="partner-warpper">
                    <h1>Partner Organization</h1>
                    <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                    <!-- Request Card (change with real data) -->
                    <div class="request-card">

                        <img src="https://images.unsplash.com/photo-1587049352846-4a222e7840b1"
                            alt="Planting Image">

                        <div class="request-info">
                            <h3>REQ12345</h3>

                            <div class="info-grid">
                                <span>User</span>
                                <span>Jamal Chong @jamalchong_123</span>

                                <span>Request Date</span>
                                <span>15/10/2025, 3:17pm</span>

                                <span>Fulfillment Date</span>
                                <span>20/10/2025, 8:56am</span>

                                <span>Planting Site</span>
                                <span>Taman Tugu Forest Park, Kuala Lumpur</span>

                                <span>Location</span>
                                <span>FRIM - Reforestation Plot A5</span>

                                <span>Coordinates</span>
                                <span>3.1742, 101.6913</span>
                            </div>
                        </div>

                        <div class="actions">
                            <button class="reject">Reject</button>
                            <button class="approve">Approve</button>
                        </div>
                    </div>
                </div>
            </div>  

            <div class="content hidden" id="content7">
                <div class="dashboard">
                    <div class="header">
                        <div>
                            <h1>Partner Management</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>

                    <button class="generate-btn">+ Generate new report</button>
                    </div>

                <!--change with real data-->
                    <div class="grid">
                        <div class="card small">
                            <span class="label">Total users</span>
                            <h2>12,345</h2>
                            <span class="trend up">▲ 15% increase since last week</span>
                        </div>

                        <div class="card small">
                            <span class="label">Growvie plants planted</span>
                            <h2>23,456</h2>
                            <span class="trend down">▼ 8% decrease since last week</span>
                        </div>

                        <div class="card large">
                            <span class="label">New user registration</span>
                            <h2>1,234 <small>new users this month</small></h2>
                            <div class="chart"></div>
                            <div class="months">Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec</div>
                        </div>

                        <div class="card medium">
                            <span class="label">Planting requests</span>
                            <div class="progress-bar">
                                <div class="progress" style="width:86%">86%</div>
                            </div>

                            <div class="progress-info">
                                <div>
                                    <span>Completed</span>
                                    <strong>20,400</strong>
                                </div>
                                <div>
                                    <span>Pending</span>
                                    <strong>3,056</strong>
                                </div>
                                <div class="total">
                                    <span>Total</span>
                                    <strong>23,456</strong>
                                </div>
                            </div>
                        </div>

                        <div class="card medium">
                            <span class="label">Revenue earned</span>
                            <h2>RM12,345</h2>
                            <span class="trend up">▲ 15% increase since last week</span>

                            <div class="donut-row">
                                <div class="donut"></div>

                                <ul class="legend">
                                    <li><span></span> Plant Seeds</li>
                                    <li><span></span> Power-ups</li>
                                    <li><span></span> Profile Customization</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card large">
                            <span class="label">Quests completed</span>
                            <h2>12,345 <small>quests completed this month</small></h2>
                            <div class="chart"></div>
                            <div class="months">Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec</div>
                        </div>

                    </div>

                </div>
            </div>

        </main>
    </div>

    <?php 
        renderQuestModal(); 
        renderSuccessModal();
        renderDeactivateModal();
        renderDeleteModal();
        renderAddPartnerModal();
    ?>  

</body>
</html>
