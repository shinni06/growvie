<?php
require_once __DIR__ . "/backend/db.php";
require_once __DIR__ . "/backend/modal.php";
require_once __DIR__ . "/backend/dashboard.php";
require_once __DIR__ . "/backend/questsubmission.php";
require_once __DIR__ . "/backend/usermanagement.php";
require_once __DIR__ . "/backend/shopmanagement.php";
require_once __DIR__ . "/backend/partnermanagement.php"; // Add this line
require_once __DIR__ . "/backend/announcement/announcement.php";

handleCreateQuest($con);
handleReviewAction($con);
handleUserActions($con);
handleShopActions($con);
handlePartnerActions($con);
handleAnnouncementActions($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growvie Dashboard</title>

    <?php
        renderDashboardScripts();
        renderUserManagementScripts();
        renderShopScripts();
    ?>

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
    <script src="backend/announcement/announcement.js"></script>

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
                'user_deleted':   ['User Deleted', 'Account permanently removed.'],
                'shop_item_added': ['Item Added', 'New shop item created successfully.'],
                'shop_item_updated': ['Item Updated', 'Shop item details updated.'],
                'shop_item_deleted': ['Item Deleted', 'Shop item removed permanently.'],
                'partner_updated': ['Request Processed', 'The tree planting verification has been updated.'],
                'announcement_created': ['Announcement Posted', 'The announcement has been successfully created.'],
                'announcement_updated': ['Announcement Updated', 'The announcement has been successfully updated.'],
                'announcement_deleted': ['Announcement Deleted', 'The announcement has been removed.']
            };

            // Check if any param matches our keys
            let action = params.get('action');
            if (params.get('review_success')) action = 'quest_success';
            if (params.get('quest_success')) action = 'quest_success';
            if (params.get('announcement_success') === 'true') action = 'announcement_created';
            if (params.get('announcement_success') === 'deleted') action = 'announcement_deleted';

            const match = messages[action] || messages[params.get('review_success')]; // specific check for review values

            if (match) {
                modal.querySelector('h3').innerText = match[0];
                modal.querySelector('p').innerText = match[1];
                modal.style.display = 'block';
                
                // Clean URL but preserve important params
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.delete('action');
                newUrl.searchParams.delete('review_success');
                newUrl.searchParams.delete('quest_success');
                newUrl.searchParams.delete('announcement_success');
                // We keep 'shop_category', 'role', 'search', 'tab' etc.
                
                window.history.replaceState({}, document.title, newUrl.toString());
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
                <div class="main-content-container">
                    <h2>Welcome back, Jamal</h2>
                    <p class="subtext">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                    <h3>Active Quests</h3>

                    <button class="action-btn create" onclick="openCreateModal()">+ Create a new quest</button>

                    <div class="list-container" id="quests-container">
                        <?php renderQuestCards($con); ?>
                    </div>

                    <h3 id="inactive-section-title">Inactive Quests</h3>

                    <div class="list-container">
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
                <div class="main-content-container">
                    <h2>Quest Submissions</h2>
                    <p class="subtext">Review community tasks and approve/reject pending evidence.</p>
                    
                    <div class="review-container">
                        <?php renderReviewTab($con); ?>
                    </div>
                </div>

                <aside class="leaderboard">
                    <h3>Approval Log</h3>
                    <p class="subtext">Track recent community quest approvals.</p>
                    <?php renderSubmissionHistory($con); ?>
                </aside>
            </div>

            <!-- ANNOUNCEMENT TAB -->
            <div class="content hidden" id="content3">
                <div class="main-content-container">
                    <h2>Announcements</h2>
                    <p class="subtext">Manage system-wide announcements.</p>
    
                    <button type="button" class="action-btn create" onclick="openPopup()">+ Create a new announcement</button>
    
                    <div id="announcementPopup" class="modal">
                        <div class="modal-content">
                            <h3>Scheduled Date</h3>
                            
                            <div class="announcement-date-container">
                                <div class="announcement-date-input">
                                    <input type="number" id="day" min="1" max="31" placeholder="Day">
                                </div>

                                <div class="announcement-date-input">
                                    <select id="month">
                                        <option value="" disabled selected hidden>Month</option>
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                </div>

                                <div class="announcement-date-input">
                                    <input type="number" id="year" min="2024" max="2030" placeholder="Year">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="announcementTitle">Announcement Title</label>
                                <input type="text" id="announcementTitle" class="announcement-form-input" placeholder="Enter announcement title">
                            </div>
                            
                            <div class="form-group">
                                <label for="announcementContent">Announcement Content</label>
                                <textarea id="announcementContent" class="announcement-textarea" placeholder="Enter announcement content"></textarea>
                            </div>

                            <div class="modal-footer announcement-modal-footer">
                                <button type="button" class="action-btn gray" onclick="closePopup()">Close</button>
                                <button type="button" class="action-btn green" onclick="postAnnouncement()">Post</button>
                            </div>
                        </div>
                    </div>

                    <div class="list-container">
                        <?php renderAnnouncements($con); ?>
                    </div>
                </div>
            </div>

            <!--Shop management tab-->
            <div class="content hidden" id="content4">
                <div class="main-content-container">
                    <h2>Shop Management</h2>
                    <p class="subtext">Manage shop items, power-ups, and in-app purchases.</p>
                
                    <div class="top-row">
                        <div class="tabs">
                            <?php $currentShopTab = $_GET['shop_category'] ?? 'seeds'; ?>
                            <button class="tab <?php echo ($currentShopTab == 'seeds') ? 'active' : ''; ?>" 
                                    onclick="shopTab('seeds')">Plant Seeds</button>
                            <button class="tab <?php echo ($currentShopTab == 'powerups') ? 'active' : ''; ?>" 
                                    onclick="shopTab('powerups')">Power-Ups</button>
                            <button class="tab <?php echo ($currentShopTab == 'iap') ? 'active' : ''; ?>" 
                                    onclick="shopTab('iap')">In-App Purchases</button>
                        </div>

                        <div class="top-actions">
                            <button class="action-btn green" onclick="openShopItemModal('add')">
                                + Add Item
                            </button>
                        </div>
                    </div>

                    <?php renderShopManagement($con, $currentShopTab); ?>
                </div>
            </div>

            <!--User management tab-->
            <div class="content hidden" id="content5">
                <div class="main-content-container">
                    <h2>User Management</h2>
                    <p class="subtext">Manage and monitor Growvie users, partners, and administrators.</p>

                    <div class="top-row">
                        <div class="tabs">
                            <?php $currentRole = $_GET['role'] ?? 'Player'; ?>
                            <button class="tab <?php echo ($currentRole == 'Player') ? 'active' : ''; ?>" onclick="userTab('Player')">Players</button>
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
            </div>
    
            <!--Partner organizer tab-->
            <div class="content hidden" id="content6">
                <div class="main-content-container">
                    <h2>Partner Organization</h2>
                    <p class="subtext">Manage real-world tree planting verification requests.</p>

                    <div class="list-container">
                        <?php renderPartnerRequests($con); ?>
                    </div>
                </div>

                <aside class="leaderboard">
                    <h3>Verification History</h3>
                    <p class="subtext">Review recently verified tree planting records.</p>
                    <?php renderPartnerHistory($con); ?>
                </aside>
            </div>  

            <div class="content hidden" id="content7">
                <div class="main-content-container">
                    <h2>App analytics</h2>
                    <p class="subtext">Monitor your application performance and user growth.</p>

                    <div class="top-row">
                        <div class="tabs"></div>
                        <div class="top-actions">
                            <button class="action-btn green">+ Generate new report</button>
                        </div>
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

            </div>

        </main>
    </div>

    <?php 
        renderQuestModal(); 
        renderSuccessModal();
        renderDeactivateModal();
        renderDeleteModal();
        renderAddPartnerModal();
        renderShopModals();
    ?>  

</body>
</html>
