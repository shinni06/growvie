<?php
session_start();
include '../backend/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get data entered in the form
    $name = mysqli_real_escape_string($con, $_POST['fullname']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password']; 
    $role = 'Player'; 

    // Check if username or email already exists in the database
    $checkQuery = "SELECT * FROM user WHERE email = '$email' OR username = '$username'";
    $result = mysqli_query($con, $checkQuery);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Print appropriate error message
        if ($row['email'] === $email) {
            echo "<script>alert('Email already registered!'); window.location.href='register.html';</script>";
        } else {
            echo "<script>alert('Username already taken!'); window.location.href='register.html';</script>";
        }
        // Stop the remaining code from running
        exit();
    }

    // Generate new user ID (USRXXX)
    $idQuery = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
    $idResult = mysqli_query($con, $idQuery);
    
    if (mysqli_num_rows($idResult) > 0) {
        $row = mysqli_fetch_assoc($idResult);
        $lastId = $row['user_id'];
        // Get the number from the last ID and +1
        $number = intval(substr($lastId, 3)) + 1;
        // Get the number and fill in the rest with 0s to ensure number is 3 characters
        $newUserId = "USR" . str_pad($number, 3, "0", STR_PAD_LEFT);
    } else {
        // Default user ID is USR001
        $newUserId = "USR001";
    }

    // Generate new player ID (UPXXX)
    $pidQuery = "SELECT user_player_id FROM user_player ORDER BY user_player_id DESC LIMIT 1";
    $pidResult = mysqli_query($con, $pidQuery);
    
    if (mysqli_num_rows($pidResult) > 0) {
        $pRow = mysqli_fetch_assoc($pidResult);
        // Get the number from the last ID and +1
        $lastPid = $pRow['user_player_id'];
        // Get the number and fill in the rest with 0s to ensure number is 3 characters
        $pNumber = intval(substr($lastPid, 2)) + 1;
        $newUserPlayerId = "UP" . str_pad($pNumber, 3, "0", STR_PAD_LEFT);
    } else {
        // Default user ID is UP001
        $newUserPlayerId = "UP001";
    }

    // Insert new data row into user table
    $insertUser = "INSERT INTO user (user_id, username, name, email, password, role, date_joined) 
                   VALUES ('$newUserId', '$username', '$name', '$email', '$password', '$role', NOW())";

    // Insert new data row into user_player table
    $insertPlayer = "INSERT INTO user_player (user_player_id, user_id, player_tier, eco_coins, drops_progress, total_quests_completed, tree_planted_irl, growvie_plants_planted, player_status) 
                     VALUES ('$newUserPlayerId', '$newUserId', 1, 0, 0, 0, 0, 0, 'Active')";

    // Print success message if the database is updated successfully
    if (mysqli_query($con, $insertUser) && mysqli_query($con, $insertPlayer)) {
        echo "<script>alert('Account created successfully! Please login.'); window.location.href='login.html';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>