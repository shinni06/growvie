<?php 
    //user table
    $userQuery = "SELECT * FROM `user` /*sql reserved keyword, need escape char*/ WHERE user_id = '$test_user_id'";
    $userResult = mysqli_query($con, $userQuery);

    if (!$userResult) {
        die("User SQL Error: ".mysqli_error($con));
    }
    $user = mysqli_fetch_assoc($userResult);


    // user_player table
    $user_playerQuery = "SELECT * FROM user_player WHERE user_id = '$test_user_id'";
    $user_playerResult = mysqli_query($con, $user_playerQuery);

    if (!$user_playerResult) {
        die("User Player SQL Error: ".mysqli_error($con));
    }
    $user_player = mysqli_fetch_assoc($user_playerResult);

    //leaderboar display
    $leaderboardQuery = "SELECT user.username, user.name, up.total_quests_completed, user.profile_picture, user.user_id
FROM user_player up
INNER JOIN user ON up.user_id = user.user_id
ORDER BY up.total_quests_completed DESC";


    $leaderboardResult = mysqli_query($con, $leaderboardQuery);
    
    if (!$leaderboardResult) {
        die("Leaderboard SQL Error: " . mysqli_error($con));
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>

    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap'); 
    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap');
 
    .profile-view{
        padding: 40px 10px 0 10px;
        background-color: #85C668;
        display: flex;
        flex-direction: column;
        height: 100vh;
        justify-content:center;
        align-items:center;
        font-family:"Encode Sans Expanded";
        font-size:25px;
        top:0;
        overflow-y: auto; /* scroll if content exceeds viewport */
        width:360px;
    }


    .pfp{
        border-radius: 50%;
        margin-bottom:12px;
        border: 5px solid #FFF177;
    }

    .tier{
        border-radius:20px;
        font-family:"Encode Sans Expanded";
        color: rgb(0,0,0,0.5);
        background-color:#FFF177;
        font-size:15px;
        padding: 5px 30px;
        font-weight:500;
    }

    .profile-view h3, .profile-view h4, .tier{
        margin: 10px 0;
    }
    .profile-view h4{
        color: rgb(0,0,0,0.5);
    }

    .profile-view h3, .profile-view h4{
        font-weight:550;
    }

    .plants-container {
        display: flex;
        align-items: center;
        padding:5px;
        width:330px;
        height:120px;
        position:relative;
    }

    /* circular icon */
    .plants-icon {
        width: 100px;
        height: 100px;
        background-color: #E9F2E6;
        border-radius: 50%;
        display: flex;
        align-items: center;     /* vertical center */
        justify-content: center; /* horizontal center */
        flex-shrink: 0;          /* prevent squishing */
        z-index: 2;
    }

    .plants-icon img {
        width: 90px;   /* control icon size */
        height: 90px;
        border-radius:50%;
        /*object-fit: contain;*/
    }

    /* text side */
    .plants-text {
        background-color: white;
        padding:15px 25px 15px 55px;
        border-radius: 15px;
        display: flex;
        flex-direction: column;
        flex:1;
        position:absolute;
        left:50px;
        right:0;
        height:90px;
        z-index:1; 
        justify-content:center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08)
    }

    .plants-title {
        font-family: "Encode Sans Expanded";
        font-size: 14px;
        color: #7E7E7E;
        font-weight: 500;
        margin-left:20px;
    }

    .plants-count {
        font-family: "Encode Sans Expanded";
        font-size: 36px;
        font-weight: 600;
        color: #000200;
        margin-left:25px;
    }

    .leaderboard{
        padding:0 10px ;
        width : 330px; 
        font-family : "Encode Sans Expanded";
        font-weight: 600;
        background-color:white;
        font-size:15px;
        flex: 1; /*fill remianing height*/
        overflow-y : auto; /*scroll if too many users*/

    }

    .lb-title{
        margin-top:10px;
        background-color: #DBE5D7;
        border-radius: 15px 15px 0 0 ; 
        font-family:"Encode Sans Expanded";
        font-weight:600;
        width:330px;
        padding:10px;
        font-size:15px;
    }

    .pfp-label{
        display:flex;
        gap:20px;
        color:rgb(0,0,0,0.5);
        justify-content: flex-start;
        align-items:center;
    }

    .rank{
        color:rgb(0,0,0,0.5);
        align-items:center;
        font-size:30px;
    }

    .lb-text{
        display:flex;
        flex-direction: column;
        gap:5px;
    }

    .lb-card{
        display: flex;
        align-items: center;
        padding:8px;
        width:320px;
        gap:20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.15);

    }

    .quests-count{
        color:rgb(0,0,0,0.5);
    }

    .lb-pfp{
        border: 3px solid #FFF177;
        border-radius:50%;
    }

    .lb-card.current-user {
        background-color: #FFF9C4; /* light yellow background */
    }

    /* hide toggle on desktop */
.pfp-toggle {
    display: none;
}

/* mobile / tablet */
@media (max-width: 1023px) {

    body {
        position: relative;
    }

    .pfp-toggle {
        display: flex;
        align-items: center;
        justify-content: center;    


        position: fixed;
        top: 5px;
        right: 15px;

        background-color: #A7EF89;
        border: none;
        border-radius: 10px;
        padding: 10px;

        cursor: pointer;
        z-index: 2100;
    }

    .plant-container{
        width: 300px;
    }

    .plants-icon {
        width: 70px;
        height: 70px;
        margin-left:20px;
    }

    .plants-icon img {
        width: 60px;   /* control icon size */
        height: 60px;
    }

    .plants-text {
        background-color: white;
        padding:10px 10px 5px 30px;
        border-radius: 15px 0 0 15px;
        display: flex;
        flex-direction: column;
        flex:1;
        position:absolute;
        left:50px;
        right:0;
        height:60px;
        z-index:1; 
        justify-content:center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .plants-title {
        font-family: "Encode Sans Expanded";
        font-size: 15px;
        color: #7E7E7E;
        font-weight: 500;
        margin-left:20px;
    }

    .plants-count {
        font-family: "Encode Sans Expanded";
        font-size: 25px;
        font-weight: 600;
        color: #000200;
        margin-left:25px;
    }

    .profile-view {
        position: fixed;
        top: 0;
        right: -360px;      /* hidden */
        width: 330px;
        height: 100vh;

        background-color: #85C668;
        z-index: 2000;

        transition: right 0.3s ease;
        box-shadow: -4px 0 10px rgba(0,0,0,0.25);
    }

    .profile-view.open {
        right: 0;
    }

    .leaderboard{
        width: 300px;
    }

    .lb-title{
        width:300px;
    }

    .lb-text{
        font-size: 13px;
    }
}


    </style>
</head>
<body>
    <button class="pfp-toggle"><img src = "assets/profile-active.png" width = 15px height = 15px></button>
    <!--profile view-->
    <div class ="profile-view">
        <img src = "<?php echo htmlspecialchars($user['profile_picture']); ?>" class ="pfp" width= 150 height=150>
        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
        <h4><?php echo htmlspecialchars($user['username']); ?></h4>
        <span class ="tier">Tier <?php echo htmlspecialchars($user_player['player_tier']); ?></span>

        <div class ="plants-container">
            <div class ="plants-icon">
                <img src = "assets/growvie-plants-planted.png"  width = 90 height = 90>
            </div>

            <div class = "plants-text">
                <span class ="plants-title">Growvie Plants Planted</span>
                <span class ="plants-count"><?php echo htmlspecialchars($user_player['growvie_plants_planted']); ?></span>
            </div>
        </div>

        <div class ="plants-container">
            <div class ="plants-icon">
                <img src = "assets/real-trees-planted.png"  width = 90 height = 90>
            </div>

            <div class = "plants-text">
                <span class ="plants-title">Real Plants Planted</span>
                <span class ="plants-count"><?php echo htmlspecialchars($user_player['tree_planted_irl']); ?></span>
            </div>
        </div>


            <span class = "lb-title">Daily Leaderboard</span>
        <div class = "leaderboard">
            <?php
            $rank = 1;
            while ($lb = mysqli_fetch_assoc($leaderboardResult)) {
                $name = htmlspecialchars($lb['name']);
                $pfp = htmlspecialchars($lb['profile_picture']);
                $quests = htmlspecialchars($lb['total_quests_completed']);

                // check if this is the current logged-in user
                $class = ($lb['user_id'] === $test_user_id) ? 'current-user' : '';
            ?>
            <div class="lb-card <?= $class ?>">
                <div class="pfp-label">
                    <span class="rank">#<?= $rank ?></span>
                    <img src="<?= $pfp ?>" class="lb-pfp" width="50" height="50">
                </div>
                <div class="lb-text">
                    <span class="name"><?= $name ?></span>
                    <span class="quests-count"><?= $quests ?> quests completed</span>
                </div>
            </div>
            <?php
                $rank++;
            }
            ?>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.querySelector('.pfp-toggle');
    const profileView = document.querySelector('.profile-view');

    menuToggle.addEventListener('click', () => {
        profileView.classList.toggle('open');
    });
});



</script>

</body>
</html>

