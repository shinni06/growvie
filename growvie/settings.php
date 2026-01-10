<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "growvie";

$conn = new mysqli($servername, $username, $password, $database);

session_start();
include 'doubleecocoins.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

    <style>
    html, body {
    margin: 0;
    padding: 0;
    height: 100%;
}

*, *::before, *::after {
    box-sizing: border-box;
}

html {
    scrollbar-gutter: stable;
}

    body {
        overflow-x: hidden;
    }


    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap'); 
    @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Semi+Expanded:wght@100;200;300;400;500;600;700;800;900&display=swap');

    .container-wrapper {
        display: flex;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        gap:20px;
    }


    .sidepanel {
        width: 260px;
        flex-shrink: 0;
    }


    .settings-content {
        flex: 1; /* take remaining space */
        padding: 20px 30px;
        background-color: #DAE5D7;
        margin: 30px 10px 0 0;
        border-radius: 16px 16px 0 0;
        font-family: "Encode Sans Expanded";
        box-sizing: border-box;
        overflow-y: auto; /* scroll only content */
        min-width:0;
    }

    .header-row {
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap; /* optional: wrap if screen is too small */
        margin-bottom: 20px;
        margin-top:20px;
    }


    .header-row h1{
        flex-shrink:0;
        font-weight:600;
        margin-top:5px;
    }



    .header-text {
        display: flex;
        flex-direction: column; /* stack h1 and tagline vertically */
    }

    .tagline {
        font-size: 18px;
        color: rgb(0,0,0,0.5);
        margin: 4px 0 0 0;
        font-weight:600;
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



</style>
</head>
<body>
    <div class = "container-wrapper">
        <?php include "sidepanel.php"; ?>

        <div class="settings-content">
            <div class="header-row">
                <div class="header-text">
                    <h1>Settings</h1>
                    <p class="tagline">Make it Yours!</p>
                </div>
            </div>
            <?php

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
        </div>
    </div>
</body>
</html>
