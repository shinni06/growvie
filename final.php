<?php
require_once __DIR__ . "/backend/db.php";
require_once __DIR__ . "/backend/modal.php";
require_once __DIR__ . "/backend/dashboard.php";
require_once __DIR__ . "/backend/questsubmission.php";

handleCreateQuest($con);
handleReviewAction($con);
renderDashboardScripts();
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
        /* final.php script replacement */
        /* final.php script replacement */
        function tab(t) {
            // 1. Save the current tab choice to the browser's memory
            localStorage.setItem('activeGrowvieTab', t);

            // 2. Update Menu Item Active States
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            document.getElementById('tab' + t).classList.add('active');

            // 3. Hide all content containers
            document.querySelectorAll('.content').forEach(content => {
                content.classList.add('hidden');
            });

            // 4. Show the selected tab
            const targetContent = document.getElementById('content' + t);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        }

        // Universal Auto-Loader
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // 1. Get the last open tab from memory, or default to 1
            const savedTab = localStorage.getItem('activeGrowvieTab') || 1;
            tab(savedTab);

            // 2. Automatically handle success modals based on ANY success parameter
            const reviewSuccess = urlParams.get('review_success');
            const questSuccess = urlParams.get('quest_success');

            if (reviewSuccess || questSuccess) {
                const modal = document.getElementById('successModal');
                
                if (reviewSuccess === 'approved') {
                    modal.querySelector('h3').innerText = "Submission Approved!";
                    modal.querySelector('p').innerHTML = "The evidence was verified and the user has been rewarded.";
                } else if (reviewSuccess === 'rejected') {
                    modal.querySelector('h3').innerText = "Submission Rejected";
                    modal.querySelector('p').innerHTML = "The submission has been declined and removed.";
                } else if (questSuccess) {
                    modal.querySelector('h3').innerText = "Success!";
                    modal.querySelector('p').innerHTML = "The operation was completed successfully.";
                }
                
                modal.style.display = 'block';

                // Clean up the URL so refresh doesn't trigger the modal again
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
            Logout
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
                <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                    <div class="top-row">

                        <div class="tabs">
                            <button class="tab active">Users</button>
                            <button class="tab">Partners</button>
                            <button class="tab">Admins</button>
                        </div>

                        <div class="search-filter">
                            <input type="text" placeholder="Search for user...">

                            <button class="filter-btn">🔍 Filter</button>
                        </div>

                    </div>

                    <!-- User Cards (change with real data) -->
                    <div class="user-card">
                        <div class="left-section">
                            <img src="https://i.imgur.com/9QFfF2G.png" alt="Profile">
                            <div class="info">
                                <h3>Jamal Chong <span>@jamalchong_123</span></h3>
                                <p>🌱 Plant Name A - Growth Stage 2</p>
                                <p>🏆 Completed <strong>3 quests</strong> today</p>
                            </div>
                        </div>

                        <div class="right-section">
                            <p class="last-active">Last active Saturday, 2:36PM</p>

                            <div class="actions">
                                <button class="details-btn">View details</button>
                                <button class="suspend-btn">Suspend</button>
                                <button class="delete-btn">Delete</button>
                            </div>
                        </div>
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
    ?>  

</body>
</html>
