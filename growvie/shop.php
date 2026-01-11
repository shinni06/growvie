<?php
include "conn.php";
session_start();

$test_user_id = $_SESSION['user_id'];

//user table
$userQuery = "SELECT * FROM `user` /*sql reserved keyword, need escape char*/ WHERE user_id = '$test_user_id'";
$userResult = mysqli_query($con, $userQuery);

if (!$userResult) {
    die("User SQL Error: ".mysqli_error($con));
}
$user = mysqli_fetch_assoc($userResult);

//user player table
$user_playerQuery = "SELECT * FROM user_player WHERE user_id = '$test_user_id'";
$user_playerResult = mysqli_query($con, $user_playerQuery);

if (!$user_playerResult) {
    die("User Player SQL Error: ".mysqli_error($con));
}
$user_player = mysqli_fetch_assoc($user_playerResult);

// getting categpry for item dislay
$category = $_GET['category'] ?? '';
$query = "SELECT * FROM shop_item WHERE 1=1";
if($category){
    $category = mysqli_real_escape_string($con, $category);
    $query .= " AND item_category = '$category'";
}

$productResult = mysqli_query($con, $query);

if(!$productResult){
    die("Shop Item SQL Error: " . mysqli_error($con));
}

$products = [];
while($row = mysqli_fetch_assoc($productResult)){
    $products[] = $row;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

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
    }


    .sidepanel {
        width: 260px;
        flex-shrink: 0;
    }


    .shop-content {
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
        gap:20px;
    }


    .header-row h1{
        flex-shrink:0;
        font-weight:600;
        margin-top:5px;
    }

    .bars {
        display: flex; /* horizontal layout for bars */
        gap: 10px; /* space between drop bar and eco coin bar */
        align-items:flex-end;
        margin-left: auto; /* pushes bars to the right edge */
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

    .drop-bar{
        border-radius: 10px;
        background-color: white;
        font-family:"Encode Sans Expanded";
        font-weight:500;
        display:flex;
        align-items: center;  
        justify-content: space-between; 
        padding: 8px 10px; 
        width: 185px; 
        font-size: 20px;
        gap: 10px; 
    }

    .drop-bar button{
        background-color: #85C768;
        border-radius:10px;
        border:none;
        width: 29px;
        height: 29px;
        cursor:pointer;
    }

    .drop-content{
        display:flex;
        align-items:center;
        gap:5px; /* space between icon and number */
    }

    .filter-row{
        display:flex;
        gap:10px;
    }



    .fr-item {
        text-decoration:none;
        color: inherit;
        font-weight:550;
        background-color:#BEC8BA;
        border-radius:15px;
        color: rgb(0,0,0,0.5);
        padding: 15px 30px;
        display:flex;
        align-items:center;
        justify-content:center;
    }
   
    .fr-item.active{
        background-color:#85C768;
        color:white;
    }

    .items-display{
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 columns */
        gap: 20px; 
        padding: 20px 0;
    }

    .item-card{
        display:flex;
        flex-direction:column;
        gap:10px;
        background-color: white;
        padding:20px;
        border-radius:20px;
        font-weight: 500;
    }

    .item-img{
        background-color: rgba(218, 229, 215, 0.5);
        border-radius: 20px;
        object-fit:contain;
        padding:20px;
    }

    .item-text{
        gap:10px;
        display:flex;
        flex-direction:column;
        padding:10px;
    }

    .item-desc{
        color: rgba(0, 0, 0, 0.5);
    }

    .buy-btn{
        background-color: #85C768;
        border:none;
        border-radius:20px;
        align-items:center;
        display:flex;
        gap:10px;
        font-family: "Encode Sans Expanded";
        padding:10px;
        font-weight:600;
        justify-content:center;
        font-size:20px;
        color:white;
        cursor:pointer;
    }

    .item-card.inactive {
        opacity: 0.45;
        filter: grayscale(100%);
    }

    .buy-btn:disabled {
        background-color: #B5B5B5;
        cursor: not-allowed;
    }

    @media (max-width : 1023px) and (min-width :652px){
        .items-display{
            grid-template-columns: repeat(2, 1fr); /* 2 columns */
        }
    }

    @media (max-width : 651px){
        .items-display{
            grid-template-columns: repeat(1, 1fr); /* 1 column */
        }

        .fr-item{
            font-size: 10px;
            padding: 11px;
        }

        .bars{
            width:100%;
        }

        .drop-content{
            font-size:15px;
        }
        
    }

</style>
</head>
<body>
    <div class = "container-wrapper">
        <?php include "sidepanel.php"; ?>

        <!--main shop-->
        <div class="shop-content">
            <div class="header-row">
                <div class="header-text">
                    <h1>Shop</h1>
                    <p class="tagline">Grow, customize, conquer - your eco-adventure starts here!</p>
                </div>

                <div class="bars">
                    <div class="drop-bar">
                        <div class="drop-content">
                            <img src="assets/drop.png" width="27px" height="27px">
                            <span><?php echo htmlspecialchars($user_player['drops_progress']); ?></span>
                        </div>
                        <button class="plus" onclick="window.location.href='shop.php'"><img src="assets/plus.png" width="12px" height="12px"></button>
                    </div>

                    <div class="drop-bar">
                        <div class="drop-content">
                            <img src="assets/ecocoin.png" width="27px" height="27px">
                            <span><?php echo htmlspecialchars($user_player['eco_coins']); ?></span>
                        </div>
                        <button class="plus" onclick="window.location.href='shop.php'"><img src="assets/plus.png" width="12px" height="12px"></button>
                    </div>
                </div>
            </div>
            <div class = "filter-row">
                <a href="shop.php" class="fr-item <?=   empty($_GET['category'] ?? '')  ? 'active' : '' ?>"> All</a>
                <a href="shop.php?category=Plant%20Seeds" class="fr-item <?= (($_GET['category'] ?? '') === 'Plant Seeds') ? 'active' : '' ?>"> Plant Seeds</a>
                <a href="shop.php?category=Power%20Ups" class="fr-item <?php echo (($_GET['category'] ?? '') == 'Power Ups') ? 'active' : ''; ?>">Power-Ups</a>
                <a href="shop.php?category=In%20App%20Purchases" class="fr-item <?php echo (($_GET['category'] ?? '') == 'In App Purchases') ? 'active' : ''; ?>">In-App Purchases</a>
            </div>
            <div class="items-display">
                
                <?php foreach($products as $product): ?>
                    <div class="item-card <?= $product['item_availability'] == 0 ? 'inactive' : '' ?>">
                        <div class="item-img">
                            <img src="assets/<?php echo htmlspecialchars($product['item_image_code']); ?>" alt="<?php echo htmlspecialchars($product['item_name']); ?>" style="width:210px; height: 210px; border-radius:10px;">
                        </div>
                        <div class="item-text">
                            <div class="item-title"><?php echo htmlspecialchars($product['item_name']); ?></div>
                            <div class="item-desc"><?php echo htmlspecialchars($product['item_desc']); ?></div>
                        </div>
                        <button class="buy-btn" data-product-id="<?= $product['item_id']; ?>" <?= $product['item_availability'] == 0 ? 'disabled' : '' ?>><img src ="assets/ecocoin.png" width = 35px height = 35px><?php echo htmlspecialchars($product['item_price']); ?></button>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>

    <div id="alert-box" style="position: fixed; top: 20px; right: 20px; padding: 15px 25px; background-color: #DAE5D7; color: white; border-radius: 12px; font-weight: 600; font-family: 'Encode Sans Expanded'; box-shadow: 0 5px 15px rgba(0,0,0,0.2); display: none; z-index: 9999;"></div>


<script>
// buyitng items 
document.querySelectorAll('.buy-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const itemId = btn.dataset.productId;

        fetch('buy_item.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'item_id=' + encodeURIComponent(itemId)
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === 'success') {
            showAlert('Item purchased!', 'success');

            setTimeout(() => {
                location.reload();
            }, 1200); // wait 1.2s
        }else {
                showAlert(data, 'error');
            }
        });
    });
});

function showAlert(message, type = 'success') {
    const alertBox = document.getElementById('alert-box');
    alertBox.textContent = message;

    // Set color based on type
    if(type === 'error') {
        alertBox.style.backgroundColor = '#E36B6B';
    } else {
        alertBox.style.backgroundColor = '#85C768';
    }

    // Show the box
    alertBox.style.display = 'block';
    alertBox.style.opacity = '0';
    alertBox.style.transform = 'translateY(-20px)';
    
    // Animate in
    setTimeout(() => {
        alertBox.style.transition = 'all 0.3s ease';
        alertBox.style.opacity = '1';
        alertBox.style.transform = 'translateY(0)';
    }, 10);

    // Hide after 2 seconds
    setTimeout(() => {
        alertBox.style.opacity = '0';
        alertBox.style.transform = 'translateY(-20px)';
        setTimeout(() => alertBox.style.display = 'none', 300);
    }, 2000);
}
</script>

</body>
</html>
