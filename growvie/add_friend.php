<?php
include "conn.php";
session_start();

$user_id = $_SESSION['user_id'];

// Get friend code from POST
if (!isset($_POST['friend_code']) || empty(trim($_POST['friend_code']))) {
    echo "Friend code is required";
    exit;
}

$friend_code = trim($_POST['friend_code']);

// Prevent adding self
if ($friend_code === $user_id) {
    echo "You cannot add yourself as a friend!";
    exit;
}

// Check if friend exists
$friendQuery = "SELECT * FROM user WHERE user_id = '$friend_code'";
$friendResult = mysqli_query($con, $friendQuery);
if (!$friendResult || mysqli_num_rows($friendResult) == 0) {
    echo "User not found";
    exit;
}

// Check if friendship already exists
$checkFriendship = "SELECT * FROM friend 
                    WHERE (user_id='$user_id' AND friend_id='$friend_code') 
                       OR (user_id='$friend_code' AND friend_id='$user_id')";
$checkResult = mysqli_query($con, $checkFriendship);
if ($checkResult && mysqli_num_rows($checkResult) > 0) {
    echo "Friend request already exists or you are already friends";
    exit;
}

// Generate new friendship_id in FS001 format
$idResult = mysqli_query($con, "SELECT MAX(friendship_id) AS max_id FROM friend");
$row = mysqli_fetch_assoc($idResult);
$maxId = $row['max_id'] ?? 'FS000';
$number = (int)substr($maxId, 2) + 1;
$newFriendshipId = 'FS' . str_pad($number, 3, '0', STR_PAD_LEFT);

// Insert friend request as Pending
$insert = "INSERT INTO friend (friendship_id, user_id, friend_id, friendship_status, date_added)
           VALUES ('$newFriendshipId', '$user_id', '$friend_code', 'Pending', NOW())";

if (mysqli_query($con, $insert)) {
    echo "success"; // frontend will show "Friend Request Sent!"
} else {
    echo "Database error: " . mysqli_error($con);
}
?>
