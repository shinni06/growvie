<?php
function syncPlantGrowthStage(mysqli $con, $virtualPlantId) {

    $query = "SELECT vp.drops_used, p.drops_required, vp.current_stage
              FROM virtual_plant vp
              JOIN plant p ON vp.plant_id = p.plant_id
              WHERE vp.virtual_plant_id = '$virtualPlantId'";

    $result = mysqli_query($con, $query);
    if (!$result || mysqli_num_rows($result) === 0) return;

    $plant = mysqli_fetch_assoc($result);

    $ratio = $plant['drops_used'] / $plant['drops_required'];

    if ($ratio >= 1)      $newStage = 5;
    elseif ($ratio >= .75) $newStage = 4;
    elseif ($ratio >= .5)  $newStage = 3;
    elseif ($ratio >= .25) $newStage = 2;
    elseif ($ratio >=.01) $newStage = 1;
    else $newStage = 0;

    // Only update if stage changed
    if ($newStage != $plant['current_stage']) {
        $update = "UPDATE virtual_plant 
                   SET current_stage = $newStage 
                   WHERE virtual_plant_id = '$virtualPlantId'";
        mysqli_query($con, $update);
    }
}





