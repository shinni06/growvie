<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growvie Dashboard</title>
    <link rel="stylesheet" href="style.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Encode Sans Expanded", sans-serif;
        }

        body {
            background: #d4e3d0;
        }

        .layout {
        display: grid;
        grid-template-columns: 220px 1fr;
        }

        /*sidebar*/
        .sidebar {
            width: 220px;
            background: #ffffff;
            height: 100vh;
            border-right: 1px solid #e3e3e3;
            padding: 25px 15px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 35px;
            color: #000;
        }

        .logo img {
            width: 40px;
            height: auto;
        }
        
        /*sidebar-menu*/
        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            padding: 10px 12px;
            font-size: 13px;
            color: #000000;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;           
            transition: 0.2s ease;
            cursor: pointer;
            font-weight: 500;
        }

        .menu-item img.menu-icon {
            width: 30px;        
            height: 30px;
            object-fit: contain;
            opacity: 0.7;
        }

        .menu-item.active {
            background: #A7EF89;
            color: #000000;
            font-weight: 700;
        }

        .menu-item.active img.menu-icon {
            filter: brightness(1) invert(0);
            opacity: 1;
        }

        .hidden {
            display: none;
        }

        .menu-item:hover {
            background: #f0f0f0;
            color: #000;
        }

        .logout {
            margin-top: auto;
            padding: 10px 12px;
            font-size: 13px;
            color: #999;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.2s ease;
            font-weight: 500;
        }

        .logout:hover {
            background: #f0f0f0;
            color: #666;
        }

        .logout-icon {
            width: 30px;
            height: 30px;
            object-fit: contain;
            opacity: 0.6;
        }

        .logout:hover .logout-icon {
            opacity: 0.9;
        }

        /*main content*/
        .main {
            padding: 35px 40px;
            overflow-y: auto;
            background: #d4e3d0;
        }

        .hidden {
            display: none;
        }

        #content1 {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 25px;
            grid-template-rows: auto auto auto auto;
        }

        #content1 h2 {
            grid-column: 1 / 2;
        }

        #content1 .subtext {
            grid-column: 1 / 2;
        }

        #content1 h3 {
            grid-column: 1 / 2;
        }

        #content1 .create-btn {
            grid-column: 1 / 2;
        }

        #content1 .quests {
            grid-column: 1 / 2;
        }

        #content1 .leaderboard {
            grid-column: 2 / 3;
            grid-row: 1 / -1;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            color: #000;
        }

        .subtext {
            color: #888;
            margin-bottom: 30px;
            margin-top: 8px;
            font-size: 14px;
        }

        h3 {
            margin: 0 0 20px 0;
            font-size: 18px;
            font-weight: 700;
            color: #000;
        }

        /*dashboard*/
        /*quest*/
        .create-btn {
            background: #85C668;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 20px;
            width: 100%;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .quests {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .quest-card {
            background: #ffffff;
            padding: 16px 18px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #d8e3d5;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .quest-left {
            display: flex;
            gap: 12px;
            align-items: center;
            flex: 1;
        }

        .icon {
            font-size: 20px;
        }

        .quest-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #000;
        }

        .quest-sub {
            font-size: 12px;
            color: #999;
        }

        .quest-right {
            text-align: right;
            min-width: 140px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .time {
            font-size: 12px;
            color: #999;
        }

        .edit-btn {
            background: #e8ede7;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
            font-size: 12px;
            font-weight: 500;
            color: #666;
        }

        .edit-btn:hover {
            background: #d0dccf;
        }
        
        /*leaderboard*/
        .leaderboard {
            background: #85C668;
            padding: 20px;
            border-radius: 12px;
            height: fit-content;
        }

        .leaderboard h3 {
            color: #ffffff;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .leader-list {
            margin-top: 0;
        }

        .leader-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 10px 12px;
            border-radius: 8px;
            border: none;
            margin-bottom: 8px;
        }

        .rank {
            font-weight: 700;
            width: 30px;
            color: #999;
            font-size: 14px;
        }

        .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .name {
            font-size: 13px;
            font-weight: 600;
            color: #000;
        }

        .score {
            font-size: 11px;
            color: #999;
        }

        /* Announcement Tab Styles */
        #content2 {
            max-width: 900px;
        }

        #content2 h2 {
            font-size: 28px;
            font-weight: 700;
            color: #000;
            margin-bottom: 8px;
        }

        #content2 .subtext {
            color: #888;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .create-announcement-btn {
            background: #85C668;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 25px;
            width: 100%;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.2s ease;
        }

        .create-announcement-btn:hover {
            background: #c5d4c1;
        }

        .date-header {
            font-size: 14px;
            font-weight: 700;
            color: #000;
            margin-bottom: 15px;
            margin-top: 10px;
        }

        .announcements-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .announcement-card {
            background: #ffffff;
            padding: 20px 24px;
            border-radius: 10px;
            border: 1px solid #d8e3d5;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .announcement-title {
            font-size: 15px;
            font-weight: 700;
            color: #000;
        }

        .announcement-time {
            font-size: 13px;
            color: #999;
        }

        .announcement-content {
            font-size: 13px;
            line-height: 1.6;
            color: #444;
            margin-bottom: 15px;
        }

        .announcement-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .action-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .edit-action-btn {
            background: #e8ede7;
            color: #666;
        }

        .edit-action-btn:hover {
            background: #d0dccf;
        }

        .delete-action-btn {
            background: #e88b8b;
            color: #ffffff;
        }

        .delete-action-btn:hover {
            background: #d67676;
        }

        /*shopping management*/
        .tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .tab {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            background: #85C668;
            cursor: pointer;
            font-weight: 600;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        /* Add New Card */
        .add-card {
            background: #e4eadf;
            height: 300px;
            border-radius: 12px;
            border: 2px dashed #adb7a2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #7f8b74;
        }

        .add-card .plus {
            font-size: 40px;
            margin-bottom: 10px;
        }

        /* Product Cards */
        .card {
            background: white;
            border-radius: 12px;
            padding: 18px;
            height: 300px;
            display: flex;
            flex-direction: column;
        }

        .card img {
            width: 120px;
            margin: 0 auto;
        }

        .card h3 {
            font-size: 16px;
            font-weight: 700;
            margin-top: 10px;
        }

        .card p {
            font-size: 13px;
            color: #666;
            margin: 5px 0 10px;
        }

        .bottom-row {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-weight: 700;
        }

        .edit-btn {
            padding: 6px 16px;
            background: #d7dfd1;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

    </style>
    <script>
        function tab(t){
            // Remove active class from all menu items
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to clicked tab
            document.getElementById('tab' + t).classList.add('active');
            
            if(t == 1){
                document.getElementById('content1').style.display = 'grid';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'none';
            }else if(t == 2){
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'block';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'none';
            }else if(t == 3){
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'block';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'none';
            }else if (t == 4){
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'block';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'none';
            }else if (t == 5){
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'block';
                document.getElementById('content6').style.display = 'none';
            }else{
                document.getElementById('content1').style.display = 'none';
                document.getElementById('content2').style.display = 'none';
                document.getElementById('content3').style.display = 'none';
                document.getElementById('content4').style.display = 'none';
                document.getElementById('content5').style.display = 'none';
                document.getElementById('content6').style.display = 'block';
            }
        }
        
        // Set initial active tab on page load
        window.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tab1').classList.add('active');
        });
    </script>
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
                <h2>Welcome back, Jamal</h2>
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

            <!-- ANNOUNCEMENT TAB -->
            <div class="content hidden" id="content2">
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
                            <button class="action-btn edit-action-btn">Edit</button>
                            <button class="action-btn delete-action-btn">Delete</button>
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
                            <button class="action-btn edit-action-btn">Edit</button>
                            <button class="action-btn delete-action-btn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

    <!--Shop management tab-->
            <div class="content hidden" id="content3"> 
                <h1>Shop Management</h1>
                <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                    <!-- Top Tabs -->
                    <div class="tabs">
                        <button class="tab">Plant Seeds</button>
                        <button class="tab">Power-Ups</button>
                        <button class="tab">Profile Customization</button>
                        <button class="tab">In-App Purchases</button>
                    </div>

                    <!-- Grid Section -->
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

            <div class="content hidden" id="content4">User Management content...

            </div>

            <div class="content hidden" id="content5">Partner Organization content...

            </div>

            <div class="content hidden" id="content6">App Analytics content...

            </div>

        </main>
    </div>
</body>
</html>