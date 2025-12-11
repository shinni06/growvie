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
            display: none !important;
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
                <h2>Announcement</h2>
            </div>

            <div class="content hidden" id="content3">Shop Management content...

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