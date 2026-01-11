<?php
include "conn.php";
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


// display vote for quest submssion
$quest_voteQuery = "SELECT 
    qs.submission_id,
    u.user_id,
    u.username,
    u.name,
    u.profile_picture,
    qs.proof_code,
    q.quest_title,
    qs.quest_submission_description,

    SUM(CASE WHEN qsv.upvote_status = 1 THEN 1 ELSE 0 END) AS total_upvotes,
    SUM(CASE WHEN qsv.downvote_status = 1 THEN 1 ELSE 0 END) AS total_downvotes,

    MAX(CASE WHEN qsv.user_id = '$test_user_id' THEN 1 ELSE 0 END) AS has_current_user_voted

FROM quest_submission qs
INNER JOIN user u ON qs.user_id = u.user_id
INNER JOIN quest q ON qs.quest_id = q.quest_id
LEFT JOIN quest_submission_vote qsv ON qs.submission_id = qsv.submission_id

GROUP BY qs.submission_id, u.user_id, u.username, u.name, u.profile_picture, qs.proof_code, q.quest_title, qs.quest_submission_description

ORDER BY qs.submitted_at ASC";

$quest_voteResult = mysqli_query($con, $quest_voteQuery);

if (!$quest_voteResult) {
    die("Quest Submission Vote SQL Error: ".mysqli_error($con));
}

// fetching all 
$submissions = [];
while ($row = mysqli_fetch_assoc($quest_voteResult)) {
    $submissions[] = $row;
}

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

    body {
        overflow-x: hidden;
    }

    *, *::before, *::after {
    box-sizing: border-box;
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

    .quest-container{
        display:flex;
        justify-content:space-between;
        align-items: flex-start;
        gap:20px;
    }
    
    .quests-container{
       background-color: white;
       border-radius: 15px ;
       display : flex; 
       flex-direction: column;
       gap:30px;
       padding: 20px;  
        min-height: calc(100vh - 280px);
        box-sizing: border-box;
    }

   

    .quest-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .qtitle {
        font-weight: 600;
        font-size: 22px;
        margin-bottom: 5px;
    }

    .qdesc {
        font-weight: 500;
        color: rgba(0,0,0,0.6);
        font-size: 16px;
    }

    /* Submissions container */
    .submissions-container {
        display: flex;
        gap: 20px;
        flex-wrap:wrap; 
        align-items: stretch; /* this ensures all cards in a row match the tallest */
    }

    /* Submission card */
    .submission-card {
        display: inline-flex;
        flex-direction:column;
        align-items: center;
        gap: 7px;
        padding: 15px;
        background-color: #EDF2EB;
        border-radius:15px;
        width:auto;
        min-width:315px;
        max-width:315px;
        box-sizing: border-box;
        margin-bottom: 5px;
    }

    /* User info */
    .user-info {
        flex: 1;
        font-weight: 500;
        display:flex;
        gap:10px;
        align-items:center;
    }

    /* Proof image */
    .proof-image img {
        width: 200px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
    }

    /* Votes */
    .votes {
        text-align: center;
        min-width: 80px;
        font-weight:500;
    }

    /* Vote buttons */
    .vote-buttons form {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .vote-buttons button {
        background-color: #85C768;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        cursor: pointer;
        color: white;
        font-weight: 600;
        font-size:15px;
    }

    .vote-buttons p {
        color: gray;
        font-size: 14px;
        margin: 0;
    }

    /* No submissions message */
    .no-submissions-message {
        text-align: center;
        padding: 20px;
        font-weight: 500;
        color: gray;
    }

    .user-text{
        display:flex;
        flex-direction: column;
        gap:2px;
    }

    .user-text p{
        margin :0;
        line-height:2;
    }

    .user-text p:last-child {
        font-size: 13px;
        color: rgb(0,0,0,0.5);
    }


    .user-pfp{
        border-radius:50%;
        border :  5px solid #FFF177;
    }

    .vote-buttons.voted {
        opacity: 0.5;
        pointer-events: none;
    }

    .vote-buttons.voted .voted-msg {
        opacity: 1;
        pointer-events: auto;
        font-size: 13px;
        color: gray;
        height: 20px; /* reserve space for message */
        margin-top: 5px;
        text-align: center;
    }

    .drop-bar {
        position: relative; /* ← this is key */
    }

    .drop-tooltip {
        position: absolute; /* relative to .drop-bar */
        bottom: 100%;       /* place above the drop bar */
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
        background-color: #85C768;
        color: white;
        font-family: "Encode Sans Expanded";
        font-weight: 600;
        padding: 8px 14px;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        opacity: 0;
        transition: all 0.3s ease;
        pointer-events: none;
        white-space: nowrap;
        z-index: 100;
        font-size:12px;
    }

    .drop-tooltip.show {
        opacity: 1;
        transform: translateX(-50%) translateY(-5px);
    }

    .quest-deets{
        gap:10px;
        word-wrap: break-word; /* break long words */
        overflow-wrap: break-word; /* modern standard */
    }
    
    /* modla bg */
    .modal {
        display: none; 
        position: fixed;
        z-index: 1000;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.8); /* dark background */
    }

    .modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 80%;
        border-radius: 10px;
    }

    #caption {
        margin: 10px auto;
        text-align: center;
        color: #fff;
        font-size: 18px;
        font-family:"Encode Sans Expanded";
    }

    .close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    @media (max-width: 375px) {
        .submissions-container {
            flex-direction: column;
            gap: 10px; 
            max-width: 100%; /* prevent overflow */
        }

        .submission-card {
            width: 100%; /* take full width of container */
            min-width: 0; /* allow shrinking */
            max-width: 100%;
        }
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
                    <p class="tagline">Review other quest submissions to earn drops!</p>
                </div>

                <div class="bars">

                    <div class="drop-bar">
                        <div class="drop-tooltip" id="drop-tooltip">100 Drops and 5 Eco Coins added!</div>
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
                <div class ="quests-container">
               <div class="quest-submissions">
                <?php if (!empty($submissions)): ?>
                    <?php foreach ($submissions as $submission): 
                        $submission_id = $submission['submission_id'];
                        $username = htmlspecialchars($submission['username']);
                        $name = htmlspecialchars($submission['name']);
                        $proof_code = htmlspecialchars($submission['proof_code']);
                        $upvotes = $submission['total_upvotes'];
                        $downvotes = $submission['total_downvotes'];
                        $hasVoted = $submission['has_current_user_voted'];
                        $pfp = $submission['profile_picture'];
                        $title = $submission['quest_title'];
                        $sub_desc = $submission['quest_submission_description'];
                    ?>
                    <!-- each submission card -->
                    <div class="submission-card">
                        <div class="user-info">
                            <img src=" <?php echo htmlspecialchars($pfp); ?>" 
                                alt="Profile Picture" class="user-pfp" width="50" height="50">
                            <div class="user-text">
                                <p><strong><?php echo $name; ?></strong></p>
                                <p><?php echo $username; ?></p> 
                            </div>
                        </div> 
                        <div class="proof-image">
                            <img src="/growvie-1/uploads/<?php echo htmlspecialchars($proof_code); ?>"  alt="Proof Image" class="proof-zoom">
                        </div>
                        <div class ="quest-deets">
                            <p  ><strong><?php echo $title; ?></strong></p>
                            <p><?php echo $sub_desc; ?></p>
                        </div>
                        <div class="votes">
                            <p>Upvotes: <?php echo $upvotes; ?></p>
                            <p>Downvotes: <?php echo $downvotes; ?></p>
                        </div>

                        <div class="vote-buttons <?= $hasVoted ? 'voted' : '' ?>">
                            <form class="vote-form">
                                <input type="hidden" name="submission_id" value="<?= $submission_id ?>">
                                <button type="button" class="vote-btn upvote" data-vote="upvote">Upvote</button>
                                <button type="button" class="vote-btn downvote" data-vote="downvote">Downvote</button>
                            </form>

                            <?php if ($hasVoted): ?>
                                <p class="voted-msg">You already voted</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-submissions-message">
                        No submissions yet for this quest. Be the first to participate!
                    </p>
                <?php endif; ?>
            </div>

            </div>

            
        </div>
        <?php include "profilepanel.php";?>
    </div>

<!-- proof image zoom in-->
 <div id = "imgModal" class = "modal">
    <span class = "close">&times;</span>
    <img class ="modal-content" id = "modalImg">
    <div id ="caption"></div>
 </div>

<script>
document.querySelectorAll('.vote-btn').forEach(button => {
    button.addEventListener('click', function () {
        const form = this.closest('.vote-form');
        const voteButtons = this.closest('.vote-buttons');

        // Already voted check
        if (voteButtons.classList.contains('voted')) {
            alert("You have already placed a vote.");
            return;
        }

        const formData = new FormData(form);
        formData.append('vote', this.dataset.vote);

        fetch('submit_vote.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            response = response.trim(); // Trim spaces/newlines

            if (response === 'already_voted') {
                alert("You have already placed a vote.");
                voteButtons.classList.add('voted');
            } 
            else if (response === 'success') {
                voteButtons.classList.add('voted');
                showDropTooltip(); 

                // reload after tooltip is visible
                setTimeout(() => {
                    location.reload();
                }, 1800);
            } 
            else {
                alert("Something went wrong.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Something went wrong.");
        });
    });
});

// show tooltip when vote is placed indicating 100 drops are added
function showDropTooltip() {
    const tooltip = document.getElementById("drop-tooltip");
    if (!tooltip) return;

    tooltip.classList.add("show");

    setTimeout(() => {
        tooltip.classList.remove("show");
    }, 2000);
}

//modal for zooming in image
const modal = document.getElementById("imgModal");
const modalImg = document.getElementById("modalImg");
const captionText = document.getElementById("caption");

// select all thumbnails
document.querySelectorAll(".proof-zoom").forEach(img => {
    img.addEventListener("click", () => {
        modal.style.display = "block";
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
    });
});

// close button
document.querySelector(".close").onclick = function() {
    modal.style.display = "none";
}

// click outside image closes modal
modal.addEventListener("click", (e) => {
    if (e.target === modal) modal.style.display = "none";
});

</script>


</body>