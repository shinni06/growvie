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
    $user_email = mysqli_real_escape_string($con, $_POST['user_email']);
    $user_password = mysqli_real_escape_string($con, $_POST['user_password']);

    $sql = "SELECT * FROM user WHERE email='$user_email' AND password='$user_password'";

    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);
    $rowcount = mysqli_num_rows($result);

    if ($rowcount == 1){
        $_SESSION['mySession'] = $row['user_id'];
        $_SESSION['user_name'] = $row['username'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];
        header("location: profile.php");
        exit();
    }
    else{
        echo "<script>alert('Email/Password is incorrect. Please relogin');</script>";
    }
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growvie - Login</title>
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
        }

        .login-container {
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

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #66bb6a;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 187, 106, 0.1);
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        .forgot-password a {
            color: #4caf50;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #388e3c;
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

        .register-link {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        .register-link a {
            color: #4caf50;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #388e3c;
        }

        @media (max-width: 480px) {
            .login-container {
                width: 95%;
                margin: 20px;
            }

            .content {
                padding: 40px 30px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="logo.png" alt="Growvie Logo" class="logo">
            <div class="logo-text">Growvie</div>
            <div class="subtitle">Grow Green, Live Clean</div>
        </div>

        <h2>Login</h2>

        <form method="post">
            <div class="input-group">
                <label>Email</label>
                <input type="email" name="user_email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="user_password" placeholder="Enter your password" required>
            </div>

            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

            <div class="button-group">
                <button type="submit">Login</button>
                <button type="reset">Reset</button>
            </div>

            <div class="register-link">
                New Here? <a href="register.php">Register</a>
            </div>
        </form>
    </div>
</body>
</html>