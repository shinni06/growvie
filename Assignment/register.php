<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Growvie";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $user_email = mysqli_real_escape_string($con, $_POST['user_email']);
    $user_password = mysqli_real_escape_string($con, $_POST['user_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if ($user_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $check_email = "SELECT * FROM user WHERE email='$user_email'";
        $result = mysqli_query($con, $check_email);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already registered!');</script>";
        } else {
            $check_username = "SELECT * FROM user WHERE username='$username'";
            $username_result = mysqli_query($con, $check_username);
            
            if (mysqli_num_rows($username_result) > 0) {
                echo "<script>alert('Username already taken!');</script>";
            } else {
                $query = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
                $result = mysqli_query($con, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_array($result);
                    $last_id = $row['user_id'];
                    $num = intval(substr($last_id, 3)) + 1;
                    $new_id = 'USR' . str_pad($num, 3, '0', STR_PAD_LEFT);
                } else {
                    $new_id = 'USR001';
                }
                
                $new_player_id = 'UP' . str_pad($num, 3, '0', STR_PAD_LEFT);
                
                $date_joined = date('Y-m-d');
                
                $sql = "INSERT INTO user (user_id, username, name, email, password, role, profile_picture, date_joined) 
                        VALUES ('$new_id', '$username', '$full_name', '$user_email', '$user_password', 'Player', 'default_profile_pic.jpeg', '$date_joined')";
                
                if (mysqli_query($con, $sql)) {
                    $player_sql = "INSERT INTO user_player (user_player_id, user_id, player_tier, eco_coins, drops_progress, total_quests_completed, tree_planted_irl, growvie_plants_planted) 
                                   VALUES ('$new_player_id', '$new_id', 1, 0, 0, 0, 0, 0)";
                    mysqli_query($con, $player_sql);
                    
                    echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
                } else {
                    echo "<script>alert('Registration failed: " . mysqli_error($con) . "');</script>";
                }
            }
        }
    }
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growvie - Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f5f5;
            padding: 20px 0;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            width: 400px;
            padding: 50px 40px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }

        .logo-text {
            font-size: 32px;
            font-weight: bold;
            color: #4caf50;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #9e9e9e;
            font-size: 14px;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 35px;
            text-align: center;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #555;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #66bb6a;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 187, 106, 0.1);
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        button {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button[type="submit"] {
            background: #4caf50;
            color: white;
        }

        button[type="submit"]:hover {
            background: #45a049;
        }

        button[type="reset"] {
            background: white;
            color: #4caf50;
            border: 2px solid #4caf50;
        }

        button[type="reset"]:hover {
            background: #fafafa;
        }

        .login-link {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #4caf50;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #388e3c;
        }

        @media (max-width: 480px) {
            .register-container {
                width: 95%;
                margin: 20px;
            }

            .register-container {
                padding: 40px 30px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo-container">
            <img src="logo.png" alt="Growvie Logo" class="logo">
            <div class="logo-text">Growvie</div>
            <div class="subtitle">Grow Green, Live Clean</div>
        </div>

        <h2>Register</h2>

        <form method="post">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Enter your full name" required>
            </div>

            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Choose a username" required>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="user_email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="user_password" placeholder="Create a password" required>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Re-enter your password" required>
            </div>

            <div class="button-group">
                <button type="submit">Register</button>
                <button type="reset">Reset</button>
            </div>

            <div class="login-link">
                Already Member? <a href="login.php">Login</a>
            </div>
        </form>
    </div>
</body>
</html>