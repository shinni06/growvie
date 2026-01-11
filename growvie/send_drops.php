<?php
include "conn.php";
session_start();

$sender_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friend_id = $_POST['friend_id'] ?? '';

    if (!$friend_id) {
        echo 'No friend specified';
        exit;
    }

    // Check if sender has enough drops
    $check_sql = "SELECT drops_progress FROM user_player WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($stmt, "s", $sender_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $sender_drops);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($sender_drops < 100) {
        echo 'not_enough';
        exit;
    }

    // Start transaction
    mysqli_begin_transaction($con);

    $success = true;

    // Subtract 100 drops from sender
    $stmt1 = mysqli_prepare($con, "UPDATE user_player SET drops_progress = drops_progress - 100 WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt1, "s", $sender_id);
    if (!mysqli_stmt_execute($stmt1)) $success = false;
    mysqli_stmt_close($stmt1);

    // Add 100 drops to friend
    $stmt2 = mysqli_prepare($con, "UPDATE user_player SET drops_progress = drops_progress + 100 WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt2, "s", $friend_id);
    if (!mysqli_stmt_execute($stmt2)) $success = false;
    mysqli_stmt_close($stmt2);

    if ($success) {
        mysqli_commit($con);
        echo 'success';
    } else {
        mysqli_rollback($con);
        echo 'error';
    }

}
?>
