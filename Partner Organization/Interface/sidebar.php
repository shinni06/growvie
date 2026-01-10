<?php
    include('conn.php');
    $currentPage = basename($_SERVER['PHP_SELF']);
    session_start();
    $partner_login = $_SESSION['user_id'];
    $queryPartner = "SELECT * FROM `partner` WHERE user_id = '$partner_login'";
    $resultPartner = mysqli_query($con, $queryPartner);
    $row = mysqli_fetch_assoc($resultPartner);
    $partner_id = $row['partner_id'];
    $GLOBALS['partner_id'] = $partner_id;
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Growvie</title>
    <link rel="stylesheet"  href="style.css">
</head>
<body>
    <div class="sidebar" id="sidebar"> 
        <div class="logo">
            <img src="..\PNGS\Logo.png">
            <span> Growvie</span>
        </div>

        <!--This section defines the navigation links the user will be able to use-->
        <nav class="menu">
            <a href="dashboard.php" class="navBtn <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
                <img src="..\PNGS\<?php echo ($currentPage == 'dashboard.php') ? 'DashboardLogoActive.png' : 'DashboardLogo.png'; ?>">Dashboard
            </a>
            <a href="history.php" class="navBtn <?php echo ($currentPage == 'history.php') ? 'active' : ''; ?>">
                <img src="..\PNGS\<?php echo ($currentPage == 'history.php') ? 'HistoryLogoActive.png' : 'HistoryLogo.png'; ?>">History
            </a>
            <a href="announcement.php" class="navBtn <?php echo ($currentPage == 'announcement.php') ? 'active' : ''; ?>">
                <img src="..\PNGS\<?php echo ($currentPage == 'announcement.php') ? 'announcement-active.png' : 'AnnouncementLogo.png'; ?>">Announcement
            </a>
        </nav>

        <div class="logout"><img src="..\PNGS\LogoutLogo.PNG">Logout</div>         
    </div>

    <script>
        const currentPage = window.location.pathname.split('/').pop(); 
        const navBtns = document.querySelectorAll('.navBtn');

        navBtns.forEach(function(btn) {
            
            if (btn.getAttribute('href') === currentPage) {
                btn.classList.add('active');
            }
        });
    </script>
</body>
</html>