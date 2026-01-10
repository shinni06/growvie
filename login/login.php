<?php

session_start();
require_once __DIR__ . "/../backend/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $identifier = mysqli_real_escape_string($con, $_POST['identifier']);
    $password = $_POST['password']; // Get password entered by user
    $requestedRole = $_POST['role']; // Get role that the user is trying to login by

    // Fetch user by email or username
    $query = "SELECT * FROM user WHERE email = '$identifier' OR username = '$identifier'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify Password
        $passwordMatch = false;
        
        // Unhash password for checking if it was hashed
        if (password_verify($password, $user['password'])) {
            $passwordMatch = true;
        } elseif ($password === $user['password']) {
            $passwordMatch = true;
        }

        if ($passwordMatch) {
            
            // Make sure user is logging in as the correct role
            $dbRole = strtolower($user['role']);
            $formRole = strtolower($requestedRole); 

            if ($dbRole === $formRole) {
                // Successful login
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];

                // Redirect to corresponding dashboard
                if ($formRole === 'admin') {
                    header("Location: ../admin.php");
                } elseif ($formRole === 'partner') {
                    // Replace with partner file
                    header("Location: ../Interface/dashboard.php");
                } else {
                    // Replace with user file
                    header("Location: ../growvie/dashboard.php");
                }
                exit();

            } else {
                echo "<script>alert('Access Denied. You are trying to login as a " . ucfirst($requestedRole) . " but your account is a " . ucfirst($user['role']) . ".'); window.location.href='login.html';</script>";
            }

        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location.href='login.html';</script>";
    }
}
?>