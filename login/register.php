<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growvie - Sign Up</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <section class="hero-section">
        <div class="brand-content">
            <div class="logo-placeholder">
                <img src="assets/Logo.png" alt="Growvie Logo"> 
            </div>
            
            <h1 class="hero-title">Join Growvie</h1>
            <p class="hero-subtitle">
                Start your eco-journey today. Create an account to begin tracking habits and growing your garden.
            </p>
        </div>
    </section>

    <section class="login-section">
        <div class="login-card">
            
            <div class="welcome-text">
                <h2>Create Account</h2>
                <p>Fill in your details to get started.</p>
            </div>

            <div class="role-tabs">
                <button class="tab-btn active" onclick="switchTab('user')">User</button>
                <button class="tab-btn" onclick="switchTab('partner')">Partner</button>
            </div>

            <form action="backend/register.php" method="POST">
                <input type="hidden" name="role" id="role-input" value="user">

                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" class="input-box" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="input-box" placeholder="name@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="input-box" placeholder="Create a password" required>
                </div>

                <button type="submit" class="login-btn">Sign Up</button>

                <div class="switch-page">
                    Already have an account? <a href="index.php">Sign In</a>
                </div>
            </form>

        </div>
    </section>

    <script>
        function switchTab(role) {
            document.getElementById('role-input').value = role;
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            if(role === 'user') tabs[0].classList.add('active');
            if(role === 'partner') tabs[1].classList.add('active');
        }
    </script>

</body>
</html>