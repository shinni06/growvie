<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "growvie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Growvie Settings</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

    *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
    }
    body {
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;;
    background-color: #DAE4D6;
    min-height: 100vh;
    }
    .container {
    display: flex;
    min-height: 100vh;
    }
    .sidebar {
    width: 220px;
    background: #ffffff;
    padding: 20px;
    border-right: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    }
    .logo {
    margin-bottom: 30px;
    color: #0c0c0cff;
    }
    .menu {
    list-style: none;
    }
    .menu li {
    padding: 10px;
    margin-bottom: 8px;
    border-radius: 8px;
    cursor: pointer;
    }
    .menu li.active,
    .menu li:hover {
    background-color: #99e78aff;
    }
    .logout {
    margin-top: auto;
    color: #000000;
    cursor: pointer;
    background: #ffffff;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
    }
    
    .logout:hover {
    background: #e57373;
    transform: translateY(-2px);
    }
    .header {
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
    }
    .header h1 {
    margin-bottom: 5px;
    }
    .date {
    margin: 20px 0 10px;
    font-size: 14px;
    color: #010101ff;
    }
    .card {
    background: #ffffffff;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .card-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    }
    .card-header span {
    font-size: 12px;
    color: #a1a1a1ff;
    }
    .card p {
    font-size: 14px;
    color: #555;
    line-height: 1.5;
    }
    .content {
    flex: 1;
    padding: 30px 40px 30px 20px;
    }
</style>
</head>

<body>

<div class="container">

    <aside class="sidebar">
        <h2 class="logo">
        <img src="Logo.png" alt="Logo.png" style="height: 28px; vertical-align: middle; margin-right: 8px;">
        Growvie
        </h2>
        <ul class="menu">
            <li>Dashboard</li>
            <li>Shop</li>
            <li>Notifications</li>
            <li>Friends</li>
            <li>Profile</li>
            <li class="active">Settings</li>
        </ul>

        <div class="logout" onclick="logout()">Log Out</div>
    </aside>
    <main class="content">

        <section class="header">
            <h1>Settings</h1>
        </section>

        <?php
        session_start();
        include 'doubleecocoins.php';

        $current_stage = 5;

        $purchased_items = [
            ['id'=>1,'name'=>'Sunflower Seed','type'=>'seed'],
            ['id'=>2,'name'=>'Double Eco Coins','type'=>'powerup'],
            ['id'=>3,'name'=>'Water Boost','type'=>'powerup']
        ];
        ?>

        <div class="card">
            <h3>Account Settings</h3>
            <form action="update_account.php" method="POST">
                <input type="text" name="name" placeholder="New Name" required><br><br>
                <input type="password" name="password" placeholder="New Password" required><br><br>
                <button type="submit">Update Account</button>
            </form>
        </div>

        <div class="card">
            <h3>Purchased Items</h3>

            <?php foreach ($purchased_items as $item): ?>
                <p>
                    <strong><?= $item['name'] ?></strong>

                    <?php if ($item['type'] == 'seed'): ?>
                        <?php if ($current_stage == 5): ?>
                            <form action="use_item.php" method="POST" style="display:inline;">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <button>Plant</button>
                            </form>
                        <?php else: ?>
                            <span style="color:red;">Finish current plant first 🌱</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <form action="use_item.php" method="POST" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button>Use</button>
                        </form>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>
        </div>

    </main>

</div>

<script>
function logout() {
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'login.php';
    }
}
</script>

</body>
</html>