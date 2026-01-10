
<?php
include "conn.php";
session_start();

header("Content-Type: text/plain");

$test_user_id = $_SESSION['user_id'];

$submission_id = $_POST['submission_id'];
$vote = $_POST['vote'];

// 1. Check if user already voted
$checkQuery = "SELECT 1 
               FROM quest_submission_vote 
               WHERE submission_id = ? AND user_id = ? 
               LIMIT 1";

$stmt = mysqli_prepare($con, $checkQuery);
mysqli_stmt_bind_param($stmt, "ss", $submission_id, $test_user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "already_voted";
    exit;
}
mysqli_stmt_close($stmt);

// 2. Generate vote_id
$idQuery = "SELECT vote_id 
            FROM quest_submission_vote 
            ORDER BY vote_id DESC 
            LIMIT 1";

$idResult = mysqli_query($con, $idQuery);

if ($row = mysqli_fetch_assoc($idResult)) {
    $lastId = (int) substr($row['vote_id'], 3);
    $vote_id = 'QSV' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
} else {
    $vote_id = 'QSV001';
}

// 3. Insert vote + reward drops (transaction)
mysqli_begin_transaction($con);

$upvote = ($vote === "upvote") ? 1 : 0;
$downvote = ($vote === "downvote") ? 1 : 0;

$insertQuery = "INSERT INTO quest_submission_vote 
(vote_id, submission_id, user_id, upvote_status, downvote_status, vote_timestamp) 
VALUES (?, ?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($con, $insertQuery);
mysqli_stmt_bind_param(
    $stmt,
    "sssii",
    $vote_id,
    $submission_id,
    $test_user_id,
    $upvote,
    $downvote
);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_rollback($con);
    echo "vote_failed";
    exit;
}
mysqli_stmt_close($stmt);

/* reward voter */
$rewardDrops = 100;
$rewardEcocoins = 5;

$updateDropsQuery = " UPDATE user_player
    SET drops_progress = drops_progress + ?
    WHERE user_id = ?
";

$updateCoinsQuery = "UPDATE user_player
    SET eco_coins = eco_coins + ?
    WHERE user_id = ?";

$dropStmt = mysqli_prepare($con, $updateDropsQuery);
mysqli_stmt_bind_param($dropStmt, "is", $rewardDrops, $test_user_id);

if (!mysqli_stmt_execute($dropStmt)) {
    mysqli_rollback($con);
    echo "reward_failed";
    exit;
}
mysqli_stmt_close($dropStmt);


$coinStmt = mysqli_prepare($con, $updateCoinsQuery);
mysqli_stmt_bind_param($coinStmt, "is", $rewardEcocoins, $test_user_id);

if (!mysqli_stmt_execute($coinStmt)) {
    mysqli_rollback($con);
    echo "reward_failed";
    exit;
}
mysqli_stmt_close($coinStmt);

mysqli_commit($con);
echo "success";
exit;

?>
