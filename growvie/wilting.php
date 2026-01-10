<?php
function checkPlantWilting(mysqli $con, string $virtualPlantId, string $userId, int $thresholdDays = 3) {
    // Get plant info
    $plantQuery = "SELECT virtual_plant_id, drops_used, current_stage, is_completed 
                   FROM virtual_plant 
                   WHERE virtual_plant_id = '$virtualPlantId' 
                   LIMIT 1";
    $plantResult = mysqli_query($con, $plantQuery);
    if (!$plantResult || mysqli_num_rows($plantResult) === 0) return;

    $plant = mysqli_fetch_assoc($plantResult);

    // If the plant is completed, do nothing
    if (intval($plant['is_completed']) === 1) return;

    // Get the latest quest submission for the user
    $submissionQuery = "SELECT submitted_at 
                        FROM quest_submission 
                        WHERE user_id = '$userId' 
                        ORDER BY submitted_at DESC 
                        LIMIT 1";
    $submissionResult = mysqli_query($con, $submissionQuery);

    $lastSubmit = null;
    if ($submissionResult && mysqli_num_rows($submissionResult) > 0) {
        $row = mysqli_fetch_assoc($submissionResult);
        $lastSubmit = new DateTime($row['submitted_at']);
    }

    // Calculate inactivity in days
    $daysInactive = ($lastSubmit === null) ? PHP_INT_MAX : (new DateTime())->diff($lastSubmit)->days;

    if ($daysInactive >= $thresholdDays) {
        // Reset drops and stage
        $update = "UPDATE virtual_plant 
                   SET drops_used = 0, current_stage = 0 
                   WHERE virtual_plant_id = '$virtualPlantId'";
        mysqli_query($con, $update);
    }
}
?>
