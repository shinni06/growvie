<?php
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['friendship_id'])) {
    $friendship_id = mysqli_real_escape_string($con, $_POST['friendship_id']);

    $sql = "DELETE FROM friend WHERE friendship_id='$friendship_id'";

    if (mysqli_query($con, $sql)) {
        echo 'success';
    } else {
        echo 'error: ' . mysqli_error($con);
    }
} else {
    echo 'invalid request';
}
?>
