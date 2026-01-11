<?php
include "conn.php";
session_start();

$test_user_id = $_SESSION['user_id'];

// friend request query
$friendreqReceivedQuery = "SELECT f.friendship_id, f.user_id AS requester_id, u.username AS requester_username, u.name AS requester_name, u.profile_picture AS requester_pfp, f.date_added
FROM friend f
INNER JOIN user u ON f.user_id = u.user_id
WHERE f.friend_id = '$test_user_id'
AND f.friendship_status = 'Pending'
ORDER BY f.date_added DESC;
";

$friendreqReceivedResult = mysqli_query($con, $friendreqReceivedQuery);
if (!$friendreqReceivedResult) {
    die("Friend Request Received SQL Error: ".mysqli_error($con));
}

$friendreqSentQuery = "SELECT f.friendship_id, f.friend_id AS recipient_id, u.username AS recipient_username, u.name AS recipient_name, u.profile_picture AS recipient_pfp, f.date_added, f.friendship_status
FROM friend f
INNER JOIN user u ON f.friend_id = u.user_id
WHERE f.user_id = '$test_user_id'
AND f.friendship_status = 'Pending'
ORDER BY f.date_added DESC;
";

$friendreqSentResult = mysqli_query($con, $friendreqSentQuery);
if (!$friendreqSentResult) {
    die("Friend Request Sent SQL Error: ".mysqli_error($con));
}


//friend's latest plant to be displayed
$latestFriendPlantsQuery = "SELECT 
    f.friendship_id,
    CASE 
        WHEN f.user_id = '$test_user_id' THEN f.friend_id
        ELSE f.user_id
    END AS friend_user_id,

    f.date_added,
    u.username AS friend_un,
    u.name AS friend_name,
    u.profile_picture AS friend_pfp,

    vp.virtual_plant_id,
    vp.current_stage,
    p.plant_name,
    p.plant_desc,
    vp.drops_used,
    p.drops_required,
    vp.date_planted

FROM friend f

INNER JOIN user u 
    ON u.user_id = CASE 
        WHEN f.user_id = '$test_user_id' THEN f.friend_id
        ELSE f.user_id
    END

LEFT JOIN (
    SELECT vp1.*
    FROM virtual_plant vp1
    INNER JOIN (
        SELECT user_id, MAX(date_planted) AS latest_date
        FROM virtual_plant
        GROUP BY user_id
    ) latest
    ON vp1.user_id = latest.user_id
   AND vp1.date_planted = latest.latest_date
) vp ON vp.user_id = u.user_id

LEFT JOIN plant p ON vp.plant_id = p.plant_id

WHERE f.friendship_status = 'Accepted'
  AND (f.user_id = '$test_user_id' OR f.friend_id = '$test_user_id');
";

$latestFriendPlantResult = mysqli_query($con, $latestFriendPlantsQuery);

if (!$latestFriendPlantResult) {
    die("Virtual Plant SQL Error: " . mysqli_error($con));
}

$latestFriendPlantResult = mysqli_query($con, $latestFriendPlantsQuery);

if (!$latestFriendPlantResult) {
    die("Virtual Plant SQL Error: " . mysqli_error($con));
}


//number of quests completed today
$quest_todayQuery="SELECT friend.friend_id,COUNT(quest_submission.submission_id) AS quests_submitted_today FROM friend LEFT JOIN quest_submission ON quest_submission.user_id = friend.friend_id AND DATE(quest_submission.submitted_at) = CURDATE() WHERE friend.user_id = '$test_user_id' AND friend.friendship_status = 'Accepted' GROUP BY friend.friend_id";

$quest_todayResult = mysqli_query($con, $quest_todayQuery);

if (!$latestFriendPlantResult) {
    die(" SQL Error: " . mysqli_error($con));
}

$quest_today = mysqli_fetch_assoc($quest_todayResult);

//unfriend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $friend_id = $_POST['friend_id'];
    $user_id = $test_user_id; // your current user ID

    // Delete friend record (regardless of user_id/friend_id position)
    $sql = "DELETE FROM friend 
            WHERE (user_id='$user_id' AND friend_id='$friend_id') 
               OR (user_id='$friend_id' AND friend_id='$user_id')";

    if (mysqli_query($con, $sql)) {
        echo 'success';
    } else {
        echo 'error: '.mysqli_error($con);
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>

<style>

    *, *::before, *::after {
    box-sizing: border-box;
}

html {
    scrollbar-gutter: stable;
}

 html, body {
    margin: 0;
    padding: 0;
    height: 100%;
}

 body {
        overflow-x: hidden;
    }

    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap'); 
    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap');

    .sidepanel{
        width: 260px;
        flex-shrink:0;
    }

    .container-wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
    }

    .friend-container{
        flex: 1;
        min-width:0;
        padding: 20px 30px;
        background-color: #DAE5D7;
        margin: 30px 10px 0 0;  
        border-radius: 16px 16px 0 0;
        font-family: "Encode Sans Expanded";
        box-sizing: border-box;
        overflow-y: auto; /* scroll only content */

    }

    .header-row {
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap; /* optional: wrap if screen is too small */
        margin-bottom: 20px;
        margin-top:20px;
        justify-content:space-between;
        gap:20px;
    }


    .header-row h1{
        flex-shrink:0;
        font-weight:600;
        margin-top:5px;
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

	.friends-main-wrapper {
        display:flex;
        gap:10px;
    }
    
    .friend-cards-column {
        flex: 1;
        width:auto;
        display: flex;
        flex-direction: column;
        align-items:stretch;
        gap: 20px; /* space between friend cards */
    }

    .friend-requests-container{
        width: 380px;
        flex-shrink: 0;
        margin-left: auto;
        max-height: calc(100vh - 140px);
        overflow-y: auto;
        gap: 20px; 
    }

    .friend-cards-column:empty {
        flex: 1;
    }

    .empty-state {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(0,0,0,0.5);
    }


    .friend-info,
    .plant-stage-dis,
    .date-gift {
        height: 100%;
    }


    .friend-card{
        display:flex;
        gap:10px;
        align-items:stretch;
        gap:15px; 
        flex-wrap:nowrap;
        height:230px;
        width: 100%;
    }

    #friend-pfp{
        border-radius:50%;
        border: 5px solid #FFF177;
        flex-shrink:0;
    }

    .friend-info{
        background-color:white;
        font-weight: 600;
        padding: 15px 30px;
        border-radius: 15px;
    }

    .names{
        display:flex;
        gap :10px;
        width:100%;
        font-size:20px;
    }


    #friend-un{
        color:rgb(0,0,0,0.5);
    }

    .latestplant{
        display : flex;
        gap:10px;
        align-items: center;
    }

    .quest{
        display:flex;
        gap:10px;
        align-items:center;
    }


    .more{
        align-items: flex-end;
        border:none;
        background:none;
        cursor:pointer;
        margin-left:auto;
    }

    .plant-stage-dis{
        background-color:white;
        border-radius:15px;
        padding:15px;
        width: auto;
        flex-shrink: 0;
    }

    .date-gift{
        display:flex; 
        flex-direction:column;
        gap:10px;
        height:190px;
        min-width: 200px;
        flex:0;
    }



    .friend-date{
        font-weight:500;
        color:rgb(0,0,0,0.5);
        background-color:#C1D1BB;
        border-radius:15px;
        display: flex;
        flex-direction: column;
        gap: 10px; 
        align-items:flex-start;
        height :85%;
        padding:20px 25px;
    }

    .fd-title{
        display:flex;
        align-items:center;
        gap:15px;
        font-size:20px;
    }

    .gifting{
        background-color:#62AFCD;
        color:white;
        border-radius:15px; 
        font-weight:500;
        display:flex;   
        gap:5px;
        align-items:center;
        font-size:15px;
        cursor:pointer;
        padding:5px 15px;
    }

    .friend-code-box{
        display:flex;
        font-weight:550;
        color:rgb(0,0,0,0.5);
        border-radius:15px;
        background-color:#C1D1BB;
        gap:10px;
        align-items:center;
        height:fit-content;
        padding:7.5px 10px ;
    }

    .code{
        background-color:white;
        border-radius:15px;
        align-items:center;
        display:flex;
        padding: 1px 10px;
        gap: 10px;
    }

    .code img{
        background-color: #DBE5D7;
        border-radius:5px;
        padding:2px;
        cursor:pointer;
    }

    .addfriend{
        font-weight:600;
        border:none;
        background-color:#85C768;
        border-radius:15px;
        color:white;
        gap:15px;    
        display:flex;
        align-items:center;
        padding: 20px;
        font-size: 19px;
        cursor:pointer;
        margin-top:0;
    }

    .new-friend{
        display: flex;
        gap:15px;
    }

    .copied-tooltip {
        position: absolute; 
        top: -20px; 
        left: 50%; 
        transform: translateX(-50%); 
        background: #333; 
        color: #fff; 
        padding: 2px 6px; 
        border-radius: 4px; 
        font-size: 12px; 
        opacity: 0;
        transition: opacity 0.5s;
        display: none; /* hidden by default */
    }


    /* Gray overlay */
    .aFmodal-overlay {
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
    .aFmodal {
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

    .aFmodal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 15px;
    }

    .aFmodal-buttons button{
        border:none;
        border-radius:10px;
        background-color:#85C768;
        color: white;
        font-family:"Encode Sans Expanded";
        width:auto;
        padding:10px 15px;
        font-weight:500;
    }

    #aFmodal-input{
        border:none;
        background-color:#D6DAD4;
        padding: 8px;
        border-radius: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    #request-feedback {
        position: absolute; 
        top: -20px; 
        left: 50%; 
        transform: translateX(-50%); 
        opacity: 0;   
        transition: opacity 0.5s;
        pointer-events: none;  
    }


    .aFmodal-overlay, .aFmodal {
        display: none; /* start hidden */
    }

    .friend-request-card {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #fff;
        padding: 10px 15px;
        border-radius: 0 0 10px 10px;
        margin-bottom: 10px; /* gap between requests */
        justify-content:space-between;
    }

    .friend-request-card img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 1px solid #FFF177;
    }

    .accept-friend-container{
        display:flex;
        flex-direction:column;
        width:380px;
    }

    .add-friend-container{
        display:flex;
        flex-direction:column;
        width:380px;
    }

    .aFC-header{
        font-weight:500;
        background-color: #C1D1BB;
        color:rgb(0,0,0,0.5);
        border-radius:10px 10px 0 0 ;
        padding:10px;
    }

    .add-friend-container,.accept-friend-container {
        background-color: white;
        border-radius: 10px;
    }

#info {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.aFbuttons{
    display:flex;
    flex-direction:column;
    gap:7px;
}

.reject, .accept{
    border-radius: 10px;
    color:white;
    padding: 10px;
    border:none;
    font-family:"Encode Sans Expanded";
    font-weight:500;
    cursor:pointer;
}

.reject{
    background-color:#D87373;
}

.accept{
    background-color:#85C768
}

.more-wrapper {
    position: relative;
    display: inline-block;
}

.more-bubble {
    position: absolute;
    top: 30px; /* below the more button */
    right: 0;
    background: #fff;
    border-radius: 10px;
    padding: 5px 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    display: none; /* hidden by default */
    z-index: 10;
    white-space: nowrap;
}

.unfriend-btn {
    background-color: rgba(216, 115, 115, 0.8);
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.unfriend-btn:hover {
    background-color: #D87373;
}

.toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #85C768; /* your green */
    color: white;
    padding: 14px 22px;
    border-radius: 14px;
    font-family: "Encode Sans Expanded";
    font-weight: 600;
    font-size: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    opacity: 0;
    pointer-events: none;
    transform: translateY(10px);
    transition: all 0.35s ease;
    z-index: 9999;
}

/* visible state */
.toast.show {
    opacity: 1;
    transform: translateY(0);
}


.toast.error {
    background-color: #E36B6B;
}

@media (max-width:1023px) and (min-width:426px){
    .names, .latestplant, .quest{
        font-size:12px;
    }

    #friend-pfp{
        width:50px;
        height:50px;
    }

    .friend-card{
        height:170px;
    }

    .plant-stage-dis{
        height: 170px;
    }

    .plant-stage-dis img{
        height:150px;
        width:150px;
    }

    .date-gift{
        height:150px;
    }

    .fd-title, #date{
        font-size:10px;
    }
    .fd-title img{
        width:22px;
        height:20px;
    }

    .gifting{
        font-size: 10px;
    }

    .gifting img{
        width:20px;
        height:20px;
    }

    .friends-main-wrapper{
        flex-direction:column;
        width:300px;
    }

    .friend-requests-container {
        display: flex;
        flex-direction: row; 
        gap: 10px;
        overflow-x: auto;
        width: 720px;
    }

    .add-friend-container,
    .accept-friend-container {
        flex: 1; 
        min-width: 300px; 
    
}
}

@media (max-width:425px){
    .names, .latestplant, .quest{
        font-size:12px;
    }

    #friend-pfp{
        width:35px;
        height:35px;
    }

    .friend-card{
        height:150px;
    }

    .plant-stage-dis{
        height: 150px;
    }

    .plant-stage-dis img{
        height:120px;
        width:120px;
    }

    .date-gift{
        height:150px;
    }

    .fd-title, #date{
        font-size:10px;
    }
    .fd-title img{
        width:22px;
        height:20px;
    }

    .gifting{
        font-size: 10px;
    }

    .gifting img{
        width:20px;
        height:20px;
    }

    .friends-main-wrapper{
        flex-direction:column;
        width:300px;
    }

    .friend-requests-container {
        display: flex;
        flex-direction: row; 
        gap: 10px;
        overflow-x: auto;
        width: 720px;
    }

    .add-friend-container,
    .accept-friend-container {
        flex: 1; 
        min-width: 300px; 
    
    }

    .friend-date{
        height: 100px;
    }

    .addfriend{
        font-size:15px;
        width:150px;
        height: 85px;
        padding: 10px 15px;
    }

}


 

</style>

</head>
<body>
    <div class = "container-wrapper">
        <?php include "sidepanel.php"; ?>

        <!--main-->
        <div class="friend-container">
            <div class="header-row">
                <div class="header-text">
                    <h1>Friends</h1>
                    <p class="tagline">Your garden is better with friends!</p>
                </div>
                
                <div class ="new-friend">
                    <div class ="friend-code-box">
                        <p>Friend Code</p>
                        <div class="code" style="position: relative;">
                        <p id="friend-code"><?php echo htmlspecialchars($test_user_id); ?></p>
                        <span id="copied-tooltip" class="copied-tooltip">Copied!</span>
                        <img src="assets/copy.png" width="25" height="25" id="copy-code" title="Copy to clipboard">
                    </div>
                    </div>
                    <div class = "friend-request-block">
                        <p class ="addfriend"><img src ="assets/addFriend.png" width = 30px height =31px>Add New Friend</p>
                        <span id="request-feedback" >Friend Request Sent!</span>
                    </div>
                </div>
                
            </div>
                <div class ="friends-main-wrapper">
                    <!--friend cards-->
                    <div class ="friend-cards-column">
                        <?php if (mysqli_num_rows($latestFriendPlantResult) > 0): ?>
                        <?php while ($latestFriendPlant = mysqli_fetch_assoc($latestFriendPlantResult)): ?>
                        <div class = "friend-card">
                            <div class ="friend-info">
                                <div class ="names">
                                <img src = <?php echo htmlspecialchars($latestFriendPlant['friend_pfp']); ?> id = "friend-pfp" width = 80px height = 80px> 
                                    <p id = "friend-name"><?php echo htmlspecialchars($latestFriendPlant['friend_name']); ?> </p>
                                    <p id ="friend-un">@<?php echo htmlspecialchars($latestFriendPlant['friend_un']); ?> </p>
                                        <div class="more-wrapper">
                                            <button class="more" data-id="<?= $latestFriendPlant['friendship_id'] ?>">
                                                <img src="assets/more.png" width="12.5" height="3.75">
                                            </button>

                                            <!-- bubble -->
                                            <div class="more-bubble">
                                                <button class="unfriend-btn" data-id="<?= $latestFriendPlant['friendship_id'] ?>">Unfriend</button>
                                            </div>
                                        </div>

                                </div>

                                <div class = "latestplant">
                                    <img src ="assets/seed.png" width=30px height = 30px>
                                    <p id ="info"><?php echo htmlspecialchars($latestFriendPlant['plant_name']); ?> - Growth Stage <?php echo htmlspecialchars($latestFriendPlant['current_stage']); ?></p>
                                </div>
                                <div class = "quest">
                                    <img src = "assets/quest-complete.png" width = 30px height = 30px>
                                    <p id = "info">Completed <?php echo htmlspecialchars($quest_today['quests_submitted_today'] ?? 0); ?> quests today</p>
                                </div>
                            </div>
                            <div class ="plant-stage-dis">
                                <?php if ($latestFriendPlant['current_stage'] == 1): ?>
                                        <img src="assets/firststage.png"  width="190px" height="190px">
                                    <?php elseif ($latestFriendPlant['current_stage'] == 2): ?>
                                        <img src="assets/secondstage.png"  width="190px" height="190px">
                                    <?php elseif ($latestFriendPlant['current_stage'] == 3): ?>
                                        <img src="assets/thirdstage.png"  width="190px" height="190px">
                                    <?php elseif ($latestFriendPlant['current_stage'] == 4): ?>
                                        <img src="assets/fourthstage.png" width="190px" height="190px">
                                    <?php elseif ($latestFriendPlant['current_stage'] == 5): ?>
                                        <img src="assets/fifthstage.png"  width="190px" height="190px">
                                    <?php endif; ?>
                            </div>
                                                        <div class ="date-gift">
                                <div class ="friend-date">
                                    <div class ="fd-title">
                                        <img src ="assets/friend-icon.png" width = 28px height = 21.92px>
                                        <p>Friends</p>
                                    </div>
                                        <p id ="date">Since <?php echo htmlspecialchars($latestFriendPlant['date_added']); ?></p>
                                </div>

                                    <div class ="gifting" data-fid="<?= $latestFriendPlant['friend_user_id'] ?>">
                                        <img src = "assets/gift-drop.png" width = 38px height = 38px>
                                        <p class="send-drops">Send 100 Drops</p>
                                    </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div class="empty-state">
                            <p>No friends yet 🌱</p>
                        </div>
                    <?php endif; ?>
                    </div>
                    <!--requests container-->
                    <div class ="friend-requests-container">
                        <div class="add-friend-container">
                            <div class="aFC-header">Friend Requests Received</div>
                                <?php if (mysqli_num_rows($friendreqReceivedResult) > 0): ?>
                                    <?php while ($friendreq = mysqli_fetch_assoc($friendreqReceivedResult)): ?>
                                        <div class="friend-request-card">
                                            <img src="<?= htmlspecialchars($friendreq['requester_pfp']) ?>">
                                            <p style="font-weight:600;"><?= htmlspecialchars($friendreq['requester_name']) ?></p>
                                            <p>@<?= htmlspecialchars($friendreq['requester_username']) ?></p>
                                            <div class ="aFbuttons">
                                                <button class="accept" data-id="<?= $friendreq['friendship_id'] ?>">Accept</button>
                                                <button class="reject" data-id="<?= $friendreq['friendship_id'] ?>">Reject</button>
                                            </div>

                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p style="padding: 15px; color: rgba(0,0,0,0.5); text-align: center;">
                                        No incoming friend requests
                                    </p>
                                <?php endif; ?>

                        </div>
                        <div class = "accept-friend-container">
                            <div class="aFC-header">Friend Requests Sent</div>
                            <?php if (mysqli_num_rows($friendreqSentResult) > 0): ?>
                            <?php while ($friendreqSent = mysqli_fetch_assoc($friendreqSentResult)): ?>
                                <div class="friend-request-card">
                                    <img src="<?= htmlspecialchars($friendreqSent['recipient_pfp']) ?>">
                                    <p style="font-weight:600;"><?= htmlspecialchars($friendreqSent['recipient_name']) ?></p>
                                    <p>@<?= htmlspecialchars($friendreqSent['recipient_username']) ?></p>
                                    <p style="background-color: rgba(234, 220, 101, 0.5); font-weight:500; color: rgb(0,0,0,0.5); padding:10px; border-radius:10px;">
                                        <?= htmlspecialchars($friendreqSent['friendship_status']) ?>
                                    </p>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p style="padding: 15px; color: rgba(0,0,0,0.5); text-align: center;">
                                No sent friend requests
                            </p>
                        <?php endif; ?>
                        </div>
                    </div>
                    
                </div>
        </div>

<!-- Overlay -->
<div id="aFmodal-overlay" class="aFmodal-overlay"></div>

<!-- Modal popup -->
<div id="aFmodal" class="aFmodal">
    <h2>Add Friend</h2>
    <p>Enter friend code:</p>
    <input type="text" id="aFmodal-input" placeholder="Enter Friend Code" required>
    <div class="aFmodal-buttons">
        <button id="aFmodal-submit">Submit</button>
        <button id="aFmodal-cancel">Cancel</button>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
    //copy friend code to clipboard
// Copy Friend Code
const copyIcon = document.getElementById('copy-code');
const friendCodeText = document.getElementById('friend-code');
const tooltip = document.getElementById('copied-tooltip');

copyIcon.addEventListener('click', async () => {
    try {
        const codeText = friendCodeText.textContent.trim();
        await navigator.clipboard.writeText(codeText);

        // show tooltip
        tooltip.style.opacity = '1';
        tooltip.style.display = 'block';
        setTimeout(() => {
            tooltip.style.opacity = '0';
            setTimeout(() => { tooltip.style.display = 'none'; }, 500);
        }, 1000);
    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Copy failed. Make sure your browser allows clipboard access.');
    }
});

// Add Friend Modal
const addFriendBtn = document.querySelector('.addfriend');
const modal = document.getElementById('aFmodal');
const overlay = document.getElementById('aFmodal-overlay');
const modalInput = document.getElementById('aFmodal-input'); 
const modalSubmit = document.getElementById('aFmodal-submit');
const modalCancel = document.getElementById('aFmodal-cancel');
const requestFeedback = document.getElementById('request-feedback');

// Open modal
addFriendBtn.addEventListener('click', () => {
    overlay.style.display = 'block';
    modal.style.display = 'block';
    modalInput.value = '';
});

// Cancel button
modalCancel.addEventListener('click', () => {
    overlay.style.display = 'none';
    modal.style.display = 'none';
});

// Submit friend request
modalSubmit.addEventListener('click', () => {
    const code = modalInput.value.trim();

    if (!code) {
        alert('Please enter a Friend Code!');
        return;
    }

    if (code === '<?php echo $test_user_id; ?>') {
        alert('You cannot add yourself as a friend!');
        return;
    }

    fetch('./add_friend.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'friend_code=' + encodeURIComponent(code)
    })
    .then(res => res.text())
    .then(data => {
        if (data === 'success') {
            // show fading feedback text
            requestFeedback.style.opacity = '1';
            setTimeout(() => { requestFeedback.style.opacity = '0'; }, 1500);
        } else {
            // show alert for errors (like "User not found" or "Already friends")
            alert(data);
        }
    });

    overlay.style.display = 'none';
    modal.style.display = 'none';
});

// toggle bubble
document.querySelectorAll('.more').forEach(button => {
    button.addEventListener('click', (e) => {
        e.stopPropagation();
        const bubble = button.nextElementSibling;
        bubble.style.display = bubble.style.display === 'block' ? 'none' : 'block';
    });
});

// hide bubble when clicking outside
document.addEventListener('click', () => {
    document.querySelectorAll('.more-bubble').forEach(b => b.style.display = 'none');
});

// unfriend button
document.querySelectorAll('.unfriend-btn').forEach(button => {
    button.addEventListener('click', () => {
        const friendshipId = button.dataset.id; // now it exists

        if (!confirm("Are you sure you want to unfriend this user?")) return;

        fetch('unfriend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'friendship_id=' + encodeURIComponent(friendshipId)
        })
        .then(res => res.text())
        .then(data => {
            data = data.trim(); // remove extra spaces/newlines
            if (data === 'success') {
                button.closest('.friend-card').remove();
            } else {
                alert('Failed to unfriend: ' + data);
            }
        });
    });
});

// send 100 drops
document.querySelectorAll('.send-drops').forEach(button => {
    button.addEventListener('click', () => {
        const friendId = button.parentElement.dataset.fid;

        if (!confirm("Send 100 Drops to this friend?")) return;

        fetch('send_drops.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'friend_id=' + encodeURIComponent(friendId)
        })
        .then(res => res.text())
        .then(data => {
            data= data.trim();
            if (data === 'success') {
                button.textContent = 'Sent!';
                button.disabled = true;
                setTimeout(() => {
                    button.textContent = 'Send 100 Drops';
                    button.disabled = false;
                    button.style.backgroundColor = '';
                }, 2000);
            } else if (data === 'not_enough') {
                alert('You do not have enough drops to send!');
            } else {
                alert('Failed to send drops: ' + data);
            }
        });
    });
});

// Accept & Reject Friend Request
document.querySelectorAll('.accept, .reject').forEach(button => {
    button.addEventListener('click', () => {
        const friendshipId = button.dataset.id;
        const action = button.classList.contains('accept') ? 'accept' : 'reject';

        if (action === 'reject' && !confirm("Are you sure you want to reject this request?")) return;

        fetch('accept_reject.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `friendship_id=${encodeURIComponent(friendshipId)}&action=${encodeURIComponent(action)}`
        })
        .then(res => res.text())
        .then(data => {
            data = data.trim();

            if (['success', 'accepted', 'rejected'].includes(data)) {
                showToast("Request updated successfully");

                setTimeout(() => {
                    window.location.reload();
                }, 1200);
            } else {
                alert(data, error);
            }
        });
    });
});

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');

    toast.textContent = message;
    toast.className = 'toast show';

    if (type === 'error') {
        toast.classList.add('error');
    }

    setTimeout(() => {
        toast.classList.remove('show');
    }, 2000);
}

</script>

</body>
</html>