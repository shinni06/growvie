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
            background: #eaf2e8;
        }

        .layout {
        display: grid;
        grid-template-columns: 220px 1fr 260px;
        }

        /*sidebar*/
        .sidebar {
            width: 220px;
            background: #DAE5D7;
            height: 100vh;
            border-right: 1px solid #e3e3e3;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 35px;
        }

        .logo img {
            width: 28px;
            height: auto;
        }
        
        /*sidebar-menu*/
        .menu {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .menu-item {
            padding: 10px 12px;
            font-size: 15px;
            color: #444;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;           
            transition: 0.2s ease;
            cursor: pointer;
        }

        .menu-item img.menu-icon {
            width: 30px;        
            height: 30px;
            object-fit: contain;
        }

        .hidden {
            display: none;
        }

        .menu-item:hover {
            background: #e3f1df;
            color: #000;
        }

        .logout {
            margin-top: auto;
            padding: 10px 12px;
            font-size: 15px;
            color: #777;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .logout:hover {
            background: #e3f1df;
            color: #000;
        }

        .logout-icon {
            width: 30px;
            height: 30px;
            object-fit: contain;
            opacity: 0.75;
        }

        .logout:hover .logout-icon {
            opacity: 1;
        }

        /*main content*/
        .main {
            padding: 35px;
            overflow-y: auto;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
        }

        .subtext {
            color: #777;
            margin-bottom: 25px;
            margin-top: 5px;
        }

        h3 {
            margin: 15px 0;
            font-size: 20px;
        }

        /*dashboard*/
        /*quest*/
        .create-btn {
            background: #85C668;
            color: white;
            padding: 12px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 25px;
            width: 250px;
            font-size: 15px;
        }

        .quests {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 700px;
        }

        .quest-card {
            background: #ffffff;
            padding: 18px 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e3e3e3;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .quest-left {
            display: flex;
            gap: 12px;
            align-items: center;
            flex: 1;
        }

        .icon {
            font-size: 22px;
        }

        .quest-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .quest-sub {
            font-size: 13px;
            color: #777;
        }

        .quest-right {
            text-align: right;
            min-width: 120px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: flex-end;
        }

        .time {
            font-size: 13px;
            color: #777;
        }

        .edit-btn {
            background: #d6e6d5;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .edit-btn:hover {
            background: #b0c9a8;
        }
        
        /*leaderboard*/
        .leaderboard {
            background: #f5f9f5;
            padding: 30px;
            border-left: 1px solid #dcdcdc;
        }

        .leader-list {
            margin-top: 15px;
        }

        .leader-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #eee;
            margin-bottom: 10px;
        }

        .rank {
            font-weight: 700;
            width: 30px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
        }

        .name {
            font-size: 14px;
            font-weight: 600;
        }

        .score {
            font-size: 12px;
            color: #777;
        }
    </style>
    <script>
        function tab(t){
            if(t == 1){
                document.getElementById('content1').style.display = 'block';
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
            
            <!-- QUEST CARD -->
            <div class="content" id="content1">
                <h2>Welcome back, Jamal</h2>
                <p class="subtext">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                <h3>Daily Quests</h3>

                <button class="create-btn">+ Create a new quest</button>

                <div class="quests" id="quests-container">
                    <!-- Quest cards will be dynamically inserted here -->
                </div>

                <aside class="leaderboard">
                <h3>Daily Leaderboard</h3>
                    <div class="leader-list">
                        <!-- 10 repeated leaderboard items -->
                        <div class="leader-item">
                            <span class="rank">#1</span>
                            <img src="https://i.pravatar.cc/40?img=4" class="avatar">
                            <div>
                                <p class="name">Jamal Chong</p>
                                <p class="score">20 quests completed</p>
                            </div>
                        </div>

                        <!-- Repeat items as needed -->
                        <!-- You can duplicate for all 10 positions -->
                    </div>

                </aside>
            </div>

            <div class="content hidden" id="content2">Announcements content...

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
