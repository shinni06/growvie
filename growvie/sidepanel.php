<!--sidebar green color: #A7EF89
Background green color: #DAE5D7
Dark green for buttons and progress bar: #85C668
Gray color (word): #7E7E7E
Black color(word): #000200-->


    <?php
        $currentpage = basename($_SERVER['PHP_SELF']);
    ?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidepanel</title>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap'); 
    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap');

    .logo_container{
        color:black;
        font-family: "Encode Sans Semi Expanded";
        font-size: 24px;
        font-style:bold;
        font-weight:700;
        display:flex;
        margin-left:20px;
        top:30px;
        gap:10px;
        position:sticky;
    }

    .logo-text{
        margin-top:5px;
    }

    .sidepanel_container{
        flex-direction:column; /*stack vertically*/
        margin-left:30px;
        cursor:pointer;
        gap:5px;
        display: flex; /*flex container*/
        justify-content: space-between;  /*push last item to bottom*/
        height:calc(100vh - 80px);
        width:260px;
        position:sticky;
        top:90px;
        left:0;
    }

    .sidepanel-item{
        text-decoration:none;
        color: #7E7E7E;
        font-family: "Encode Sans Expanded";
        font-size: 18px;
        font-style:medium;
        font-weight:500;
        display:flex;
        gap:15px;
        align-items:center;
        width:239px;
        height:63px;
        border-radius:16px;
        padding-left:10px;
    }
    .sidepanel-item.active{
        background-color:#A7EF89;
        color: #000200;
        border-radius:16px;
    }

    .sidepanel-item.active svg path, .sidepanel_item.active svg circle{
        fill:currentColor; 
    }

    .sidepanel-item:hover{
        background-color: rgba(167, 239, 137, 0.5);
        cursor:pointer;
    }

    .sidepanel-item.active:hover {
        background-color: #a7ef89ff;
        color: inherit;
        cursor: default; /* optional */
    }

    .logout{
        margin-bottom: 30px;
    }

    .sidepanel-main{
        margin-top:20px;
    }

    /* Only target the quest review icon when its parent is NOT active */
    .quest-review-item:not(.active) img {
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }

    .quest-review-item{
        padding:15px;
    }

    /* default: hide toggle on large screens */
    #menu-toggle {
        display: none;
    }

    /* small screens */
    @media (max-width: 1023px) {
        #menu-toggle {
            display: block;
            position: fixed;
            top: 5px;
            left: 15px;
            z-index: 1001;
            background-color: #A7EF89;
            border: none;
            border-radius: 5px;
            padding: 8px;
            cursor: pointer;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: -290px; /* hidden */
            height: 100%;
            background-color: white;
            z-index: 1000;
            transition: left 0.3s ease;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
        }

        .sidebar.open {
            left: 0; /* slide in */
        }
    }




</style>

</head>
<body>
    <button id="menu-toggle">☰</button>
    <div class ="sidebar">
    <div class ="logo_container">
        <img src="assets/growvie_logo.png" alt="Growvie Logo" width="40" height="40">
        <div class = "logo-text">Growvie</div>
    </div>


    <div class="sidepanel_container">
        <div class ="sidepanel-main">
        <a href="dashboard.php" class="sidepanel-item <?= ($currentpage==='dashboard.php' ) ? 'active' : '' ?>">
            <img src="assets/<?= ($currentpage==='dashboard.php' ) ? 'dashboard-active.png' : 'dashboard.png' ?>" width="27" height="27">
            <div class="text-container">Dashboard</div>
        </a>


        <a href ="shop.php" class ="sidepanel-item <?=$currentpage==='shop.php' ? 'active' : ''?>">
            <img src = "assets/<?= $currentpage === 'shop.php' ? 'shop-active.png' : 'shop.png'?>" width = "27" height = "27">
            <div class ="text-container">Shop</div>
        </a>

        <a href ="notifications.php"  class ="sidepanel-item <?=$currentpage==='notifications.php' ? 'active' : ''?>">
            <img src = "assets/<?= $currentpage === 'notifications.php' ? 'notifs-active.png' : 'notifs.png' ?>" width="27" height="27">
            <div class ="text-container">Notifications</div>
        </a>

        <a href ="friends.php" class ="sidepanel-item <?=$currentpage==='friends.php' ? 'active' : ''?>">
            <img src = "assets/<?= $currentpage === 'friends.php' ? 'friends-active.png' : 'friends.png' ?>" width="27" height="27">
            <div class ="text-container">Friends</div>
        </a>

        <a href="quest_vote.php" class="sidepanel-item quest-review-item <?= $currentpage==='quest_vote.php' ? 'active' : '' ?>">
            <img src="assets/<?= $currentpage === 'quest_vote.php' ? 'quest_review.png' : 'quest_review.png' ?>" width="27" height="27">
            <div class="text-container">Quest Review</div>
        </a>


        <a href ="profile.php" class ="sidepanel-item <?=$currentpage==='profile.php' ? 'active' : ''?>">
            <img src = "assets/<?= $currentpage === 'profile.php' ? 'profile-active.png' : 'profile.png' ?>" width="27" height="27">
            <div class ="text-container">Profile</div>
        </a>

        <a href ="settings.php" class ="sidepanel-item <?=$currentpage==='settings.php' ? 'active' : ''?>">
            <svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M22.5 9.76499L23.9737 10.2487C24.432 10.4035 24.74 10.8338 24.7387 11.3175V15.6262C24.7636 16.1335 24.4455 16.5944 23.9625 16.7512L22.5 17.235L23.3212 18.5962C23.543 19.0293 23.4613 19.5558 23.1187 19.9012L20.025 22.995C19.6795 23.3376 19.153 23.4193 18.72 23.1975L17.3475 22.5L16.8637 23.9625C16.7069 24.4455 16.246 24.7636 15.7387 24.7387H11.3737C10.8665 24.7636 10.4056 24.4455 10.2487 23.9625L9.765 22.5L8.3925 23.2087C7.95947 23.4305 7.43295 23.3488 7.0875 23.0062L4.005 20.025C3.66242 19.6795 3.58072 19.153 3.8025 18.72L4.5 17.3475L3.0375 16.8637C2.55449 16.7069 2.23643 16.246 2.26125 15.7387V11.3737C2.23643 10.8665 2.55449 10.4056 3.0375 10.2487L4.5 9.76499L3.79125 8.39249C3.56947 7.95947 3.65117 7.43295 3.99375 7.08749L7.0875 3.99374C7.43295 3.65117 7.95947 3.56946 8.3925 3.79124L9.765 4.49999L10.1925 3.03749C10.3493 2.55449 10.8103 2.23642 11.3175 2.26124H15.6262C16.1335 2.23642 16.5944 2.55449 16.7512 3.03749L17.235 4.49999L18.6075 3.79124C19.0405 3.56946 19.567 3.65117 19.9125 3.99374L22.995 7.08749C23.3376 7.43295 23.4193 7.95947 23.1975 8.39249L22.5 9.76499ZM7.875 13.5C7.875 16.6066 10.3934 19.125 13.5 19.125C16.6066 19.125 19.125 16.6066 19.125 13.5C19.125 10.3934 16.6066 7.87499 13.5 7.87499C10.3934 7.87499 7.875 10.3934 7.875 13.5Z" fill="currentColor" fill-opacity="1"/>
            <circle cx="13.5" cy="13.5" r="3.375" fill="currentColor" fill-opacity="1"/>
            </svg>
            <div class ="text-container">Settings</div>
        </a>
</div>
        <div class ="logout">
        <a href="/growvie-1/logout.php" class ="sidepanel-item <?=$currentpage==='/growvie-1/logout.php' ? 'active' : ''?>">
            <img src = "assets/logout.png" width="27" height="27">
            <div class ="text-container">Log Out</div>
        </a>
        </div>
    </div>
    </div>  

<script>
const menuToggle = document.getElementById('menu-toggle');
const sidepanel = document.querySelector('.sidebar');

menuToggle.addEventListener('click', () => {
    sidepanel.classList.toggle('open');
});
</script>
</body>
</html>
