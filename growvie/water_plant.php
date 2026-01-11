<?php
include "conn.php";
include "drops_stage.php";
include "wilting.php";
session_start();

$test_user_id = $_SESSION['user_id'];

// Check wilting for all user's plants first
$result = mysqli_query($con, "SELECT virtual_plant_id FROM virtual_plant WHERE user_id = '$test_user_id'");
while ($row = mysqli_fetch_assoc($result)) {
    checkPlantWilting($con, $row['virtual_plant_id'], $test_user_id);
}

// Fetch user drops
$user_playerQuery = "SELECT * FROM user_player WHERE user_id = '$test_user_id'";
$user_playerResult = mysqli_query($con, $user_playerQuery);
$user_player = mysqli_fetch_assoc($user_playerResult);

// Validate amount
if(!isset($_POST['amount']) || intval($_POST['amount']) <= 0){
    echo "Invalid amount";
    exit;
}
$amount = intval($_POST['amount']);

// Check if user has enough drops
if($user_player['drops_progress'] < $amount){
    echo "Not enough drops!";
    exit;
}

// Get latest plant
$latestPlantQuery = "SELECT virtual_plant.virtual_plant_id, virtual_plant.drops_used, plant.drops_required 
                     FROM virtual_plant 
                     INNER JOIN plant ON virtual_plant.plant_id = plant.plant_id
                     WHERE virtual_plant.user_id='$test_user_id' 
                     ORDER BY virtual_plant.date_planted DESC LIMIT 1";

$result = mysqli_query($con, $latestPlantQuery);
$plant = mysqli_fetch_assoc($result);

if(!$plant){
    echo "No plant found!";
    exit;
}

// Calculate new drops
$new_drops = min($plant['drops_required'], $plant['drops_used'] + $amount);

// Update plant first
$updatePlant = "UPDATE virtual_plant SET drops_used='$new_drops' WHERE virtual_plant_id='".$plant['virtual_plant_id']."'";

if(!mysqli_query($con, $updatePlant)){
    echo "Error updating plant: " . mysqli_error($con);
    exit;
} 


// Deduct drops from user
$new_user_drops = $user_player['drops_progress'] - $amount;
$updateUser = "UPDATE user_player SET drops_progress='$new_user_drops' WHERE user_id='$test_user_id'";
if(!mysqli_query($con, $updateUser)){
    echo "Error updating user drops: " . mysqli_error($con);
    exit;
}

// sync the stage 
syncPlantGrowthStage($con, $plant['virtual_plant_id']);

echo "success";
exit;
?>
