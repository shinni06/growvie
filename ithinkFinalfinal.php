<?php
require_once 'dbdummy.php';         //replace with database or maybe main       
require_once 'announcement.php';      

handleAnnouncementActions($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growvie Dashboard</title>
    <script src="sidebartab.js"></script>
    <link rel="stylesheet" href="maincontentcss.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="announcement.css">
    <link rel="stylesheet" href="shopmanagement.css">
    <link rel="stylesheet" href="usermanagement.css">
    <link rel="stylesheet" href="partnermanagement.css">
    <link rel="stylesheet" href="analytics.css">



</head>

<body>
    <div class = "layout"> 
        <!--sidebar-->
        <nav class="sidebar">
            <div class="logo">
                <img src="Logo.png" alt="Growvie">
                <span>Growvie</span>
            </div>

        <!--sidebar menu-->
        <div class="menu">
            <div class="menu-item" onclick="tab(1)" id="tab1">
                <img src="dashboard.png" class="menu-icon">
                Dashboard
            </div>

            <div class="menu-item" onclick="tab(2)" id="tab2">
                <img src="announcement.png" class="menu-icon">
                Announcements
            </div>

            <div class="menu-item" onclick="tab(3)" id="tab3">
                <img src="shop.png" class="menu-icon">
                Shop Management
            </div>

            <div class="menu-item" onclick="tab(4)" id="tab4">
                <img src="user.png" class="menu-icon">
                User Management
            </div>

            <div class="menu-item" onclick="tab(5)" id="tab5">
                <img src="partner.png" class="menu-icon">
                Partner Organization
            </div>

            <div class="menu-item" onclick="tab(6)" id="tab6">
                <img src="appanalytics.png" class="menu-icon">
                App Analytics
            </div>
        </div>

        <!--logout-->
        <div class="logout">
            <img src="logout.png" class="logout-icon">
            Logout
        </div>
        </nav>

        <!--main content-->
        <main class="main">
            
            <!-- DASHBOARD TAB -->
            <div class="content" id="content1">
                <h1>Welcome back, Jamal</h1>
                <p class="subtext">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                <h3>Daily Quests</h3>

                <button class="create-btn">+ Create a new quest</button>
                    <!-- create questcard tab -->

                <div class="quests" id="quests-container">
                    <!-- replace with actual questcard data -->
                </div>

                <aside class="leaderboard">
                <h3>Daily Leaderboard</h3>
                    <div class="leader-list">
                        <!-- replace actual leaderboard data -->
                        <div class="leader-item">
                            <span class="rank">#1</span>
                            <img src="https://i.pravatar.cc/40?img=4" class="avatar">
                            <div>
                                <p class="name">Jamal Chong</p>
                                <p class="score">20 quests completed</p>
                            </div>
                        </div>
                    </div>

                </aside>
            </div>

        <!--Announcement tab-->
        <div class="content" id="content2">
            <h1>Announcements</h1>
            <p class="subtext">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

            <button type="button" class="create-announcement-btn" onclick="openPopup()">Create new announcement</button>

            <div id="announcementPopup" class="popup-overlay">
                <div class="popup-content">
                    <h3 class="popup-title">Scheduled Date</h3>
                    
                    <div class="date-inputs">
                        <div class="date-input">
                            <input type="number" id="day" min="1" max="31" placeholder="DD">
                        </div>

                        <div class="date-input">
                            <select id="month">
                                <option value="" disabled selected hidden>Select month</option>
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

                        <div class="date-input">
                            <input type="number" id="year" min="2024" max="2030" placeholder="YYYY">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="announcementTitle">Announcement Title</label>
                        <input type="text" id="announcementTitle" class="form-input" placeholder="Enter announcement title">
                    </div>
                    
                    <div class="form-group">
                        <label for="announcementContent">Announcement Content</label>
                        <textarea id="announcementContent" class="form-textarea" placeholder="Enter announcement content"></textarea>
                    </div>

                    <div class="popup-buttons">
                        <button type="button" class="btn-announcement-close" onclick="closePopup()">Close</button>
                        <button type="button" class="btn-announcement-post" onclick="postAnnouncement()">Post</button>
                    </div>
                </div>
            </div>

            <div class="announcement-history">
                <?php renderAnnouncements($con); ?>
            </div>
            <script src="announcement.js"></script>
        </div>

            <!--Shop management tab-->
            <div class="content hidden" id="content3"> 
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
            <div class="content hidden" id="content4">
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
            <div class="content hidden" id="content5">
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

            <div class="content hidden" id="content6">
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

        </main>
    </div>

</body>
</html>