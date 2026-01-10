<?php
include "conn.php";
include "drops_stage.php";
include "wilting.php";
session_start();

$test_user_id = $_SESSION['user_id'];

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

//latest plant to be displayed in the dashboard
$latestPlantQuery = "SELECT virtual_plant.virtual_plant_id, virtual_plant.current_stage, plant.plant_id, plant.plant_name, plant.plant_desc, virtual_plant.drops_used, plant.drops_required FROM virtual_plant INNER JOIN plant ON virtual_plant.plant_id = plant.plant_id WHERE virtual_plant.user_id = '$test_user_id' ORDER BY virtual_plant.date_planted DESC LIMIT 1
";

$latestPlantResult = mysqli_query($con, $latestPlantQuery);

if (!$latestPlantResult) {
    die("Virtual Plant SQL Error: ".mysqli_error($con));
}

$latestPlant = mysqli_fetch_assoc($latestPlantResult);

if ($latestPlant) {
    checkPlantWilting($con, $latestPlant['virtual_plant_id'], $test_user_id);

    // Refresh plant info after potential wilting
    $latestPlantResult = mysqli_query($con, $latestPlantQuery);
    $latestPlant = mysqli_fetch_assoc($latestPlantResult);

    // Then sync growth stage based on drops
    syncPlantGrowthStage($con, $latestPlant['virtual_plant_id']);
}

//daily quest display, changes everyday randomly
$dailyquestQuery = "SELECT * 
FROM quest 
WHERE TRIM(status) = 'Active'
ORDER BY CRC32(CONCAT(quest_id, CURDATE(), '$test_user_id'))
LIMIT 3";

$dailyquestResult = mysqli_query($con, $dailyquestQuery);

if (!$dailyquestResult) {
    die("Quest SQL Error: ".mysqli_error($con));
}

$dailyquests = [];
while ($row = mysqli_fetch_assoc($dailyquestResult)) {
    $dailyquests[] = $row;
}


// calculate remainign hours for quest refresh
$endOfDay = strtotime('tomorrow midnight') * 1000;

$quest_id = $dailyquest['quest_id'] ?? null; // make sure daily quest exists
$user_id = $test_user_id;

$alreadySubmitted = false;

if ($quest_id) {
    $submissionCheckQuery = "SELECT COUNT(*) AS submissions_today FROM quest_submission  WHERE user_id = '$user_id' AND quest_id = '$quest_id' AND DATE(submitted_at) = CURDATE()";
    $submissionResult = mysqli_query($con, $submissionCheckQuery);
    if ($submissionResult) {
        $row = mysqli_fetch_assoc($submissionResult);
        $alreadySubmitted = $row['submissions_today'] > 0;
    }
}

// Collect quest IDs shown today
$questIds = array_column($dailyquests, 'quest_id');

$submittedToday = [];
if (!empty($dailyquests)) {
    $questIds = array_column($dailyquests, 'quest_id');
    $questIdList = "'" . implode("','", $questIds) . "'";

    $submissionQuery = "
        SELECT quest_id 
        FROM quest_submission
        WHERE user_id = '$test_user_id'
        AND DATE(submitted_at) = CURDATE()
        AND quest_id IN ($questIdList)
    ";

    $submissionResult = mysqli_query($con, $submissionQuery);
    while ($row = mysqli_fetch_assoc($submissionResult)) {
        $submittedToday[$row['quest_id']] = true;
    }
}

//message for wilting
$wiltedQuery = "SELECT COUNT(*) AS wilted_count 
FROM virtual_plant 
WHERE user_id = '$test_user_id' 
AND current_stage = 0
";

$res = mysqli_query($con, $wiltedQuery);
$wilted = mysqli_fetch_assoc($res)['wilted_count'];



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

<style>
    html, body {
    margin: 0;
    padding: 0;
    height: 100%;
}

*, *::before, *::after {
    box-sizing: border-box;
}

html {
    scrollbar-gutter: stable;
}

    body {
        overflow-x: hidden;
    }


    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap'); 
    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap');

    .container-wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
        overflow: hidden;
    }


    .sidepanel {
        width: 260px;
        flex-shrink: 0;
    }

    .profilepanel {
        width: 400px;
        flex-shrink: 0;
    }


    .dashboard-content {
        flex: 1; /* take remaining space */
        padding: 20px 30px;
        background-color: #DAE5D7;
        margin-top: 30px;
        border-radius: 16px 16px 0 0;
        font-family: "Encode Sans Expanded";
        box-sizing: border-box;
        overflow-y: auto; /* scroll only content */
        min-width:0;
        margin-right:20px;
    }


    .header-row {
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap; /* optional: wrap if screen is too small */
        margin-bottom: 20px;
        margin-top:20px;
    }


    .header-row h1{
        flex-shrink:0;
        font-weight:600;
        margin-top:5px;
    }

    .bars {
        display: flex; /* horizontal layout for bars */
        gap: 10px; /* space between drop bar and eco coin bar */
        flex-direction: column;
        align-items:flex-end;
        margin-left: auto; /* pushes bars to the right edge */
    }

    .header-text {
        display: flex;
        flex-direction: column; /* stack h1 and tagline vertically */
    }

    .tagline {
        font-size: 18px;
        color: rgb(0,0,0,0.5);
        margin: 4px 0 0 0;
        font-weight:600;
    }

    .drop-bar{
        border-radius: 10px;
        background-color: white;
        font-family:"Encode Sans Expanded";
        font-weight:500;
        display:flex;
        align-items: center;  
        justify-content: space-between; 
        padding: 8px 10px; 
        width: 185px; 
        font-size: 20px;
        gap: 10px; 
    }

    .drop-bar button{
        background-color: #85C768;
        border-radius:10px;
        border:none;
        width: 29px;
        height: 29px;
        cursor:pointer;
    }

    .drop-content{
        display:flex;
        align-items:center;
        gap:5px; /* space between icon and number */
    }


    .quick-plant-view {
        background-color: white;
        border-radius: 15px;
        padding: 15px;
        margin-top: 20px;
        font-family: "Encode Sans Expanded";
        font-weight:600;
        display: flex;
        gap: 20px;   
        align-items:center;
        margin-left:auto;
        width:100%;
        max-width:1100px;
        margin-left:auto;
        margin-right:auto;
    }

    .plant-info{
        display:flex;
        flex-direction:column;
        gap:10px;
    }

    .pinfo-text{
        font-size: 30px;
        margin-top:-20px;
    }

    .p-desc{
        color:rgb(0,0,0,0.5);
        font-size:15px;
        font-weight:500;
    }

    .stage-image {
        display: flex;
        gap: 12px;
    }

    .stage {
        width: 73px;
        height: 90px;
        border-radius: 15px;
        background-color: rgba(233, 242, 230, 0.5); /* default (inactive) */
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s ease;
    }

    .stage.active {
        background-color: rgba(133, 199, 104, 0.5); /* green highlight */
    }

    .plant-visual img{
        border-radius:15px;
    }

    .drops-progress{
        display:flex;
        align-items:center;
        gap: 10px;
        font-size:20px;
    }

    .drops-progress p{
        display: flex;
    }

    .dropss{
        color:rgb(0,0,0,0.5);
        font-weight:400;
    }

    .progress-bar {
        width: 100%;
        height: 6px; /* thin bar */
        background-color: rgba(98, 175, 205, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background-color: #62AFCD;
        border-radius: 10px;
        transition: width 0.4s ease;
    }

    .drops-info{
        display: flex;
        flex-direction: column;
        gap:2px;
        width:320px;
    }

    .drops-text-row{
        display:flex;
        justify-content : space-between;
        align-items: center;
        width:100%;
    }

    .drops-label{
        font-weight:400;
    }

    .drop-icon{
        padding:15px 5px 0 5px;
    }

    .custom{
        color: rgb(0,0,0,0.5);
        background-color: #DBE5D7;
    }

    .custom, .quick{
        display:flex;
        align-items: center;
        gap :8px;
        padding: 8px 20px;
        border-radius: 15px;
        font-family: "Encode Sans Expanded";
        font-weight:500;
        border:none;
        font-size:14px;
        cursor:pointer;
    }

    .cdrop-btn-icon{
        height: 24;
        width:24;
        filter: brightness(0) opacity(0.5);
    }

    .quick{
        background-color: #62AFCD;
        color: white;
    }
    
    .qdrop-btn-icon{
        height: 24;
        width:24;
        filter: brightness(1000%) opacity(1);
    }

    .buttons{
        display:flex;
        gap:5px;
        padding:5px;
        width:400px;
        justify-content:space-between;
    }

    .quest-container{
        display:flex;
        justify-content:space-between;
        align-items: flex-start;
        gap:20px;
    }
    
    .daily-quests{
       background-color: white;
       border-radius: 15px;
       display : flex; 
       flex-direction: column;
       gap:10px;
       padding:10px 20px 10px 20px;  
    }

    .dq-title{
        font-weight:600;
        padding : 15px;
        font-size:25px;
        margin-top:19px;
    }

    .qtitle{
        font-weight:500;
        font-size:20px;
        margin-bottom:2px;
    }

    .qdesc{
        font-weight:500;
        color:rgb(0,0,0,0.5);
    }

    .quest-deets{
        display:flex;
        flex-direction:column;
        flex:1;
        gap:0;
    }

    .exp-sub{
        display:flex;
        flex-direction:column;
        gap :3px;
        align-items:flex-end;
    }

    .quest-countdown{
        color:rgb(0,0,0,0.5);
        font-weight:500;
    }

    .sub{
        border-radius: 15px;
        background-color: #85C768;
        color: white;
        border:none;
        font-family:"Encode Sans Expanded";
        font-weight: 500;
        padding: 10px;
        width: 180px;
        font-size:15px;
        cursor:pointer;
    }


    /* Gray overlay */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: none;
        z-index: 999;
    }

    /* Modal window */
    .modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px 30px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        display: none;
        z-index: 1000;
        width: 300px;
        font-family:"Encode Sans Expanded";
    }

    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 15px;
    }

    .modal-buttons button{
        border:none;
        border-radius:10px;
        background-color: #85C768;
        color: white;
        font-family:"Encode Sans Expanded";
        width:auto;
        padding:10px 15px;
        font-weight:500;
        cursor: pointer;
    }

    #modal-input{
        border:none;
        background-color: #D6DAD4;
        padding: 8px;
        border-radius: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    #upload-input {
        width: 100%;
        padding: 8px;
        border-radius: 8px;
        border: none;
        background-color: #D6DAD4;
        box-sizing: border-box;
    }

    #upload-trigger{
        border-radius:15px;
         cursor: pointer;
        transition: transform 0.2s;
}

    #upload-trigger:hover {
        transform: scale(1.05); /* little hover effect */
    }

    #upload-filename{
        font-size: 14px;
        color: rgba(0, 0, 0, 0.6);
        margin-top: 5px;
        white-space: nowrap;       /* keep on one line */
        overflow: hidden;          /* hide the overflowing text */
        text-overflow: ellipsis;   /* show ... for overflow */
    }

    .tooltip {
        position: absolute;
        background-color: #333;
        color: #fff;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 13px;
        display: none;
        white-space: nowrap;
        z-index: 1000;
    }

    .dynamic-tooltip {
        position: absolute;
        padding: 5px 10px;
        background-color: #333;
        color: #fff;
        font-family:"Encode Sans Expanded";
        border-radius: 5px;
        font-size: 12px;
        z-index: 1000;
        pointer-events: none; /* ensures tooltip doesn’t block mouse events */
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
    }

    .dynamic-tooltip.show {
        opacity: 1;
    }

    .sub.completed {
        background-color: #C9C9C9;
        cursor: not-allowed;
    }

    #upload-description {
        width: 100%;
        padding: 8px;
        border-radius: 8px;
        border: none;
        background-color: #D6DAD4;
        margin-bottom: 10px;
        box-sizing: border-box;
        font-family: "Encode Sans Expanded";
    }


    @media (max-width :768px) and (min-width:426px){

        .plant-visual img{
            width: 280px;
            height:320px;
        }

        .plant-info{
            width:600px;
        }

        .stage img{
            width:50px;
            height:62px;
        }

        .stage {
            width:60px;
            height:72px;
        }

        .drops-progress{
            width:350px;
        }

        .buttons{
            width:355px;
        }

        .custom, .quick{
            font-size:11px;
        }
    }

    @media (max-width: 425px){
        .quick-plant-view{
            flex-direction: column;
        }

        .plant-visual img{
            width: 300px;
            height:150px;
        }

        .plant-info{
            width:300px;
        }

        .stage img{
            width:50px;
            height:62px;
        }

        .drops-progress{
            width:300px;
        }

        .buttons{
            width:310px;
            gap:5px;
        }

        .custom, .quick{
            font-size:10.5px;
            padding:10px;
        }

        .quest-container{
            flex-direction: column;
            align-items: center;
        }
    }

    @media (max-width: 375px) and (min-width:321px){
        .quick-plant-view{
            flex-direction: column;
        }

        .plant-visual img{
            width: 270px;
            height:150px;
        }

        .plant-info{
            width:250px;
        }

        .stage{
            width: 43px;
            height:47px; 
        }

        .stage img{
            width: 37px;
            height:40px;
        }

        .drops-progress{
            width:250px;
        }

        .buttons{
            width:270px;
            gap:5px;
        }

        .custom, .quick{
            font-size:10.5px;
            padding:10px;
        }
    }

    @media (max-width: 320px){
        .quick-plant-view{
            flex-direction: column;
        }

        .plant-visual img{
            width: 220px;
            height:110px;
        }

        .plant-info{
            width:200px;
        }

        .stage{
            width: 43px;
            height:47px; 
        }

        .stage img{
            width: 32px;
            height:40px;
        }

        .drops-progress{
            width:200px;
            font-size:15px;
        }

        .drop-icon img{
            height: 30px;
            width:30px;
        }

        .buttons{
            width:200px;
            gap:5px;
        }

        .custom img,.quick img{
            height: 20px;
            width:20px;
        }

        .custom, .quick{
            font-size:10.5px;
            padding:10px;
        }
    }

    .alert-box{
        background-color: rgba(227, 107, 107, 0.5);
        font-size: 12px;
        padding:5px;
        border-radius:10px;
    }



</style>

</head>
<body>
    <div class = "container-wrapper">
        <?php include "sidepanel.php"; ?>

        <!--main dashboard-->
        <div class="dashboard-content">
            <div class="header-row">
                <div class="header-text">
                    <h1>Welcome back, <?php echo htmlspecialchars($user['name']); ?></h1>
                    <p class="tagline">Keep growing your plants and eco coins!</p>
                </div>

                <div class="bars">
                    <div class="drop-bar">
                        <div class="drop-content">
                            <img src="assets/drop.png" width="27px" height="27px">
                            <span><?php echo htmlspecialchars($user_player['drops_progress']); ?></span>
                        </div>
                        <button class="plus" onclick="window.location.href='shop.php'"><img src="assets/plus.png" width="12px" height="12px"></button>
                    </div>

                    <div class="drop-bar">
                        <div class="drop-content">
                            <img src="assets/ecocoin.png" width="27px" height="27px">
                            <span><?php echo htmlspecialchars($user_player['eco_coins']); ?></span>
                        </div>
                        <button class="plus" onclick="window.location.href='shop.php'"><img src="assets/plus.png" width="12px" height="12px"></button>
                    </div>
                </div>
            </div>

            <!--quick plant view-->
           <div class="quick-plant-view">
                <?php if ($latestPlant): ?>

                    <!--image-->
                    <div class ="plant-visual">
                        <?php if ($latestPlant['current_stage'] == 1): ?>
                            <img src="assets/stage-1-display.png"  width="520px" height="370px">
                        <?php elseif ($latestPlant['current_stage'] == 2): ?>
                            <img src="assets/stage-2-display.png"  width="520px" height="370px">
                        <?php elseif ($latestPlant['current_stage'] == 3): ?>
                            <img src="assets/stage-3-display.png"  width="520px" height="370px">
                        <?php elseif ($latestPlant['current_stage'] == 4): ?>
                            <img src="assets/stage-4-display.png" width="520px" height="370px">
                        <?php elseif ($latestPlant['current_stage'] == 5): ?>
                            <img src="assets/stage-5-display.png"  width="520px" height="370px">
                        <?php elseif ($latestPlant['current_stage'] == 0): ?>
                            <img src="assets/stage-0-display.png"  width="520px" height="370px">
                        <?php endif; ?>
                    </div>

                    <div class="plant-info">
                        <!--text-->
                        <div class ="pinfo-text">
                            <p><?php echo htmlspecialchars($latestPlant['plant_name']); ?></p>
                            <p class="p-desc"><?php echo htmlspecialchars($latestPlant['plant_desc']); ?></p>
                            <?php if ($wilted > 0): ?>
                            <div class="alert-box">
                                Plant has wilted. Complete a quest and water the plant to recover!
                            </div>
                            <?php endif; ?>
                        </div>

                        <!--stage image-->
                        <div class="stage-image">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="stage <?= ($latestPlant['current_stage'] == $i) ? 'active' : '' ?>">
                                    <img src="assets/<?= 
                                        ['first','second','third','fourth','fifth'][$i-1] 
                                    ?>stage.png" width="68px" height="80px">
                                </div>
                            <?php endfor; ?>
                        </div>

                        
                        <!--drop progress-->
                        <div class ="drops-progress">
                            <div class="drop-icon">
                                <img src ="assets/drop.png" width = "60px" height= "60px">                               
                            </div>
                            <div class ="drops-info">
                                <div class ="drops-text-row">
                                    <p class ="drops-label">Drops</p>
                                    <p class = "dropss"><?php echo htmlspecialchars($latestPlant['drops_used']); ?> / <?php echo htmlspecialchars($latestPlant['drops_required']); ?></p>   
                                </div>
                                

                                <div class ="progress-bar">
                                    <?php 
                                        $progressPercent = 0;
                                        if ($latestPlant['drops_required'] >0){
                                            $progressPercent = min(100, ($latestPlant['drops_used']/$latestPlant['drops_required'])*100);
                                        }
                                    ?>
                                    <div class ="progress-fill" style ="width: <?=$progressPercent?>%"></div>
                                </div>
                            </div>
                            
                        </div>

                        <!--buttons-->
                        <div class ="buttons">
                            <button class = "custom" data-type="custom">
                                <img src="assets/custom-drop.png" class="cdrop-btn-icon" width=25px height =25px>
                                Custom Water
                            </button>
                            <button class ="quick" data-type="quick" data-amount="100" >
                                <img src="assets/custom-drop.png" class="qdrop-btn-icon" width=25px height =25px>
                                Quick Water(100)
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <p>No plants planted yet.</p>
                <?php endif; ?>
            </div>
        
            <div class ="dq-title">Daily Quests</div>
            <div class="daily-quests">
            <?php foreach ($dailyquests as $dailyquest): ?>
                <div class="quest-container">
                    <div class="quest-deets">
                        <p class="qtitle">
                            <?php echo htmlspecialchars($dailyquest['quest_emoji']); ?>
                            <?= htmlspecialchars($dailyquest['quest_title']); ?>
                        </p>
                        <p class="qdesc">
                            <?= htmlspecialchars($dailyquest['quest_description']); ?>
                        </p>
                    </div>
                

                    <div class="exp-sub">
                        <p class="quest-countdown">
                            <span class="hoursRemaining"></span>
                        </p>

                       <?php $isSubmitted = isset($submittedToday[$dailyquest['quest_id']]);?>

                        <button class="sub <?= $isSubmitted ? 'completed' : ''; ?>"
                            data-quest-id="<?= $dailyquest['quest_id']; ?>"
                            data-status="<?= $isSubmitted ? 'completed' : 'pending'; ?>"
                            <?= $isSubmitted ? 'disabled="disabled"' : ''; ?>
                            data-tooltip="<?= $isSubmitted ? 'You have already completed this quest today!' : ''; ?>">
                            <?= $isSubmitted ? 'Completed' : 'Upload'; ?>
                        </button>


                    </div>
                </div>
            <?php endforeach; ?>
        </div>


          
                </div>
        <?php include "profilepanel.php";?>
           
</div>


<!-- Overlay -->
<div id="modal-overlay" class="modal-overlay"></div>

<!-- Modal popup -->
<div id="modal" class="modal">
    <h2>Custom Water</h2>
    <p>Enter the amount of drops to water:</p>
    <input type="number" id="modal-input" min="1">
    <div class="modal-buttons">
        <button id="modal-submit">Submit</button>
        <button id="modal-cancel">Cancel</button>
    </div>
</div>

<!-- Upload Modal Overlay -->
<div id="upload-overlay" class="modal-overlay"></div>

<!-- Upload Modal -->
<div id="upload-modal" class="modal">
    <h2>Upload Image</h2>
    <p>Select an image to upload:</p>
    <p>Description:</p>
    <input type="text" id="upload-description" placeholder="Describe what you did for this quest" required>
    <input type="file" id="upload-input" accept="image/*" style="display:none">
    <img src="assets/upload-btn.png" id="upload-trigger" style="cursor:pointer; width:100%; height:auto;">
    <p id="upload-filename" style="font-size:14px; color:rgb(0,0,0,0.6); margin-top:5px;"></p>
    <div class="modal-buttons">
        <button id="upload-submit">Upload</button>
        <button id="upload-cancel">Cancel</button>
    </div>
</div>

<div id="alert-box" style="position: fixed; top: 20px; right: 20px; padding: 15px 25px; background-color: #DAE5D7; color: white; border-radius: 12px; font-weight: 600; font-family: 'Encode Sans Expanded'; box-shadow: 0 5px 15px rgba(0,0,0,0.2); display: none; z-index: 9999;"></div>



<script>
    //remianign hours
    function updateHours() {
        const now = new Date();
        const midnight = new Date();
        midnight.setHours(24, 0, 0, 0);

        const diff = midnight - now;
        const hours = Math.max(0, Math.ceil(diff / (1000 * 60 * 60)));

        document.querySelectorAll(".hoursRemaining").forEach(el => {
            el.innerText = hours + " hours remaining";
        });
    }


    document.addEventListener('DOMContentLoaded', () => {
    updateHours();
    setInterval(updateHours, 60000);
});

    //water btn
    document.querySelectorAll('.quick').forEach(btn => {
    btn.addEventListener('click', () => {
        const amount = parseInt(btn.dataset.amount);

        fetch('./water_plant.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'amount=' + encodeURIComponent(amount)
        })
        .then(res => res.text())
        .then(data => {
             data = data.trim();
            if (data === 'success') {
                showAlert("Plant watered!", 'success');
                setTimeout(() => location.reload(), 1800);
            } else if (data === 'Not enough drops!') {
                showAlert("You don’t have enough drops!", 'error'); // alert for insufficient drops
            } else if (data === 'Invalid amount') {
                showAlert("Please enter a valid amount!", 'error'); // alert for invalid input
            } else {
                showAlert(data, 'error'); // any other errors from PHP
            }
        })
        .catch(err => {
            showAlert("Something went wrong!", 'error');
            console.error(err);
        });
    });
});

//custome watering
const modal = document.getElementById('modal');
const overlay = document.getElementById('modal-overlay');
const modalInput = document.getElementById('modal-input');
const modalSubmit = document.getElementById('modal-submit');
const modalCancel = document.getElementById('modal-cancel');

document.querySelectorAll('.custom').forEach(btn => {
    btn.addEventListener('click', () => {
        overlay.style.display = 'block';
        modal.style.display = 'block';
        modalInput.value = '';
    });
});

modalCancel.addEventListener('click', () => {
    overlay.style.display = 'none';
    modal.style.display = 'none';
});

modalSubmit.addEventListener('click', () => {
    const amount = parseInt(modalInput.value);
    if(!amount || amount <= 0){
        showAlert('Not enough drops!',error);
        return;
    }

    fetch('./water_plant.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'amount=' + encodeURIComponent(amount)
    })
    .then(res => res.text())
    .then(data => {
            data = data.trim();
            if (data === 'success') {
                showAlert("Plant watered!" , 'success');
                setTimeout(() => location.reload(), 1800);
            } else if (data === 'Not enough drops!') {
                showAlert("You don’t have enough drops!", 'error'); // alert for insufficient drops
            } else if (data === 'Invalid amount') {
                showAlert("Please enter a valid amount!", 'error'); // alert for invalid input
            } else {
                showAlert(data, 'error'); // any other errors from PHP
            }
        })
        .catch(err => {
            showAlert("Something went wrong!", 'error');
            console.error(err);
        });

    overlay.style.display = 'none';
    modal.style.display = 'none';
});

//upload prroof
const uploadModal = document.getElementById('upload-modal');
const uploadOverlay = document.getElementById('upload-overlay');
const uploadDescription = document.getElementById('upload-description');
const uploadInput = document.getElementById('upload-input');
const uploadSubmit = document.getElementById('upload-submit');
const uploadCancel = document.getElementById('upload-cancel');

uploadDescription.value = '';

document.querySelectorAll('.sub').forEach(btn => {
    btn.addEventListener('click', () => {
        uploadOverlay.style.display = 'block';
        uploadModal.style.display = 'block';
        uploadInput.value = '';
        uploadSubmit.dataset.questId = btn.dataset.questId; // store questId
    });
});

uploadCancel.addEventListener('click', () => {
    uploadOverlay.style.display = 'none';
    uploadModal.style.display = 'none';
    uploadInput.value = '';
    uploadDescription.value = '';
});

uploadSubmit.addEventListener('click', () => {
    const file = uploadInput.files[0];
    const description = uploadDescription.value.trim();

    if (!file) {
        alert("Please select a file!");
        return;
    }

    if (description === '') {
        alert("Please enter a description!");
        return;
    }

    const questId = uploadSubmit.dataset.questId;

    const formData = new FormData();
    formData.append('image', file);
    formData.append('quest_id', questId);
    formData.append('description', description);

    fetch('./upload_image.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data.toLowerCase().includes('successful')) {

            const btn = document.querySelector(`.sub[data-quest-id="${questId}"]`);
            btn.disabled = true;
            btn.textContent = 'Completed';
            btn.classList.add('completed');
            btn.dataset.tooltip = 'You have already completed this quest today!';
            btn.dataset.status = 'completed';

            uploadOverlay.style.display = 'none';
            uploadModal.style.display = 'none';
            uploadInput.value = '';
            uploadDescription.value = '';
            uploadFilename.innerText = '';

        } else {
            alert(data);
        }
    });
});



const uploadTrigger = document.getElementById('upload-trigger');

uploadTrigger.addEventListener('click', () => {
    uploadInput.click();
});

const uploadFilename = document.getElementById('upload-filename');

uploadInput.addEventListener('change', () => {
    if (uploadInput.files.length > 0) {
        uploadFilename.innerText = uploadInput.files[0].name; // show filename
    } else {
        uploadFilename.innerText = ''; // reset if no file
    }
});

//show tooltip for submitted quest
document.querySelectorAll('.sub').forEach(btn => {
    const tooltipText = btn.dataset.tooltip;

    btn.addEventListener('mouseenter', () => {
        if (btn.disabled && tooltipText) {
            let tooltip = document.createElement('div');
            tooltip.className = 'dynamic-tooltip';
            tooltip.textContent = tooltipText;
            document.body.appendChild(tooltip);

            const rect = btn.getBoundingClientRect();
            tooltip.style.top = rect.top - 30 + window.scrollY + 'px';
            tooltip.style.left = rect.left + window.scrollX + 'px';

            // fade in
            setTimeout(() => tooltip.classList.add('show'), 10);

            btn._tooltip = tooltip;
        }
    });

    btn.addEventListener('mouseleave', () => {
        if (btn._tooltip) {
            btn._tooltip.remove();
            btn._tooltip = null;
        }
    });
});

function showAlert(message, type = 'success') {
    const alertBox = document.getElementById('alert-box');
    alertBox.textContent = message;

    // Set color based on type
    if(type === 'error') {
        alertBox.style.backgroundColor = '#E36B6B';
    } else {
        alertBox.style.backgroundColor = '#85C768';
    }

    // Show the box
    alertBox.style.display = 'block';
    alertBox.style.opacity = '0';
    alertBox.style.transform = 'translateY(-20px)';
    
    // Animate in
    setTimeout(() => {
        alertBox.style.transition = 'all 0.3s ease';
        alertBox.style.opacity = '1';
        alertBox.style.transform = 'translateY(0)';
    }, 10);

    // Hide after 2 seconds
    setTimeout(() => {
        alertBox.style.opacity = '0';
        alertBox.style.transform = 'translateY(-20px)';
        setTimeout(() => alertBox.style.display = 'none', 300);
    }, 2000);
}


</script>
</body>
</html>