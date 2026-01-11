<?php
include "conn.php";
session_start();

$test_user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $friendship_id = $_POST['friendship_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$friendship_id || !$action) {
        echo "Invalid request";
        exit;
    }

    if ($action === 'accept') {
        $sql = "UPDATE friend 
                SET friendship_status='Accepted' 
                WHERE friendship_id='$friendship_id'";
        echo mysqli_query($con, $sql) ? 'accepted' : 'error: '.mysqli_error($con);

    } elseif ($action === 'reject') {
        $sql = "DELETE FROM friend 
                WHERE friendship_id='$friendship_id'";
        echo mysqli_query($con, $sql) ? 'rejected' : 'error: '.mysqli_error($con);

    } else {
        echo "Invalid action";
    }
}
?>
