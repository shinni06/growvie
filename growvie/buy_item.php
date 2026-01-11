<?php
include "conn.php";
session_start();

$test_user_id = $_SESSION['user_id'];

if (!isset($_POST['item_id'])) {
    echo "Invalid request";
    exit;
}

$item_id = mysqli_real_escape_string($con, $_POST['item_id']);


// retirive item id
$itemQuery = "SELECT * FROM shop_item WHERE item_id = '$item_id' AND item_availability = '1'";
$itemResult = mysqli_query($con, $itemQuery);

if (mysqli_num_rows($itemResult) === 0) {
    echo "Item not available";
    exit;
}

$item = mysqli_fetch_assoc($itemResult);


// retriev user eco coins num
$userQuery = "SELECT eco_coins FROM user_player WHERE user_id = '$test_user_id'";
$userResult = mysqli_query($con, $userQuery);
$user = mysqli_fetch_assoc($userResult);


// if not enough csnt proceed
if ($user['eco_coins'] < $item['item_price']) {
    echo "Not enough eco coins";
    exit;
}


//  minue eco coins
$newBalance = $user['eco_coins'] - $item['item_price'];

$updateUser = "UPDATE user_player 
    SET eco_coins = '$newBalance' 
    WHERE user_id = '$test_user_id'
";

if (!mysqli_query($con, $updateUser)) {
    echo "Failed to update balance";
    exit;
}


// insert purchase inot user_purchase table
$idQuery = "SELECT purchase_id
            FROM user_purchase 
            ORDER BY purchase_id DESC 
            LIMIT 1";

$idResult = mysqli_query($con, $idQuery);

if ($row = mysqli_fetch_assoc($idResult)) {
    $lastId = (int) substr($row['purchase_id'], 3);
    $vote_id = 'PUR' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
} else {
    $vote_id = 'PUR001';
}
$insertPurchase = "INSERT INTO user_purchase (purchase_id,user_id, item_id, purchase_at)
    VALUES ('$vote_id','$test_user_id', '$item_id', NOW())
";

if (!mysqli_query($con, $insertPurchase)) {
    echo "Purchase failed";
    exit;
}

echo "success";
