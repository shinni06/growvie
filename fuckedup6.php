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

        h1 {
            font-size: 32px;
            font-weight: 700;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            color: #000;
        }

        h3 {
            margin: 0 0 20px 0;
            font-size: 18px;
            font-weight: 700;
            color: #000;
        }
        
        .subtitle {
            margin-top: 6px;
            color: #6f6f6f;
            margin-bottom: 25px;
        }
        
        .subtext {
            color: #888;
            margin-bottom: 30px;
            margin-top: 8px;
            font-size: 14px;
        }

    /*Dashboard*/
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

    /*Shopping management*/
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

    /*User management*/
        /* Tabs */
        .tabs {
            display: flex;
            gap: 15px;
        }

        .tab {
            padding: 12px 26px;
            border: none;
            border-radius: 8px;
            background: #d0d8c7;
            font-weight: 600;
            cursor: pointer;
        }

        .tab.active {
            background: #8ccf73;
            color: white;
        }

        /* Top Row */
        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-filter {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-filter input {
            width: 260px;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
        }

        .filter-btn {
            padding: 10px 20px;
            background: #d0d8c7;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        /* User Card */
        .user-card {
            background: white;
            padding: 20px;
            border-radius: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
        }

        .left-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .left-section img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 4px solid #f3df5b;
        }

        .info h3 {
            font-size: 18px;
            font-weight: 700;
        }

        .info h3 span {
            font-size: 14px;
            font-weight: 500;
            color: #666;
        }

        .info p {
            font-size: 14px;
            color: #444;
            margin-top: 2px;
        }

        .right-section {
            text-align: right;
        }

        .last-active {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        /* Buttons */
        .details-btn {
            padding: 8px 18px;
            background: #d9e4d3;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .suspend-btn {
            padding: 8px 18px;
            background: #f7c8c4;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .delete-btn {
            padding: 8px 18px;
            background: #d95757;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
        }

    /*Partener Orgainzer tab*/
        .partner-wrapper {
            background: #dfe9d8;
            margin: 20px;
            padding: 40px;
            border-radius: 16px;
        }

        /* Request Card */
        .request-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 18px;
        }

        /* Image */
        .request-card img {
            width: 140px;
            height: 110px;
            border-radius: 10px;
            object-fit: cover;
        }

        /* Info Section */
        .request-info {
            flex: 1;
        }

        .request-info h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            row-gap: 8px;
            column-gap: 20px;
            font-size: 14px;
        }

        .info-grid span:nth-child(odd) {
            color: #6b6b6b;
            font-weight: 500;
        }

        .info-grid span:nth-child(even) {
            color: #333;
            font-weight: 600;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 12px;
        }

        .actions button {
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        /* Buttons */
        .reject {
            background: #d86a6a;
            color: white;
        }

        .approve {
            background: #8ecf73;
            color: white;
        }

    /* Analysis tab */    
        /* Dashboard wrapper */
        .dashboard {
            background: #dfe9d8;
            margin: 20px;
            padding: 40px;
            border-radius: 16px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 700;
        }

        .header p {
            margin-top: 5px;
            color: #6f6f6f;
        }

        .generate-btn {
            background: #8ecf73;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 14px;
            padding: 20px;
        }

        .small {
            height: 140px;
        }

        .medium {
            height: 220px;
        }

        .large {
            grid-column: span 2;
            height: 260px;
        }

        .label {
            color: #777;
            font-size: 14px;
        }

        .card h2 {
            font-size: 30px;
            margin: 10px 0;
        }

        .card h2 small {
            font-size: 14px;
            font-weight: 500;
            margin-left: 6px;
        }

        .trend {
            font-size: 13px;
        }

        .trend.up {
            color: #5fb85f;
        }

        .trend.down {
            color: #d85c5c;
        }

        /* Chart placeholder */
        .chart {
            margin-top: 15px;
            height: 120px;
            background: linear-gradient(135deg, #bfe6b3, #eaf5e6);
            border-radius: 10px;
        }

        .months {
            font-size: 12px;
            color: #777;
            margin-top: 8px;
        }

        /* Progress bar */
        .progress-bar {
            background: #e3e3e3;
            height: 28px;
            border-radius: 14px;
            overflow: hidden;
            margin: 15px 0;
        }

        .progress {
            background: #8ecf73;
            height: 100%;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .progress-info .total {
            color: #8ecf73;
        }

        /* Donut chart */
        .donut-row {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .donut {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: conic-gradient(
                #8ecf73 0% 45%,
                #a8d99b 45% 75%,
                #cfe8c4 75% 100%
            );
        }

        .legend {
            list-style: none;
            font-size: 14px;
        }

        .legend li {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .legend span {
            width: 10px;
            height: 10px;
            background: #8ecf73;
            border-radius: 50%;
        }

    </style>
    <script>
        function tab(t){
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });

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
</body>
</html>