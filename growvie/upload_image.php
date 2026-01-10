<?php
include "conn.php";
session_start();

$test_user_id = "USR002"; 

// Basic validation
if (!isset($_POST['quest_id']) || !isset($_FILES['image'])) {
    echo "Missing quest ID or image!";
    exit;
}

$quest_id = $_POST['quest_id'];
$file = $_FILES['image'];

$description = trim($_POST['description'] ?? '');

if ($description === '') {
    echo "Description is required";
    exit;
}


// Upload error check
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo "File upload error!";
    exit;
}

/* genrate NEW submission_id */
$lastQuery = "SELECT submission_id FROM quest_submission 
              ORDER BY submission_id DESC LIMIT 1";

$result = mysqli_query($con, $lastQuery);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $lastNum = intval(substr($row['submission_id'], 2)); // QS###
    $newNum = $lastNum + 1;
} else {
    $newNum = 1;
}

$newSubmissionId = 'QS' . str_pad($newNum, 3, '0', STR_PAD_LEFT);


// Force PNG naming 
$filename =  $newSubmissionId . ".png";

$uploadDir = __DIR__ . "/uploads/";

// safety check
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$destination = $uploadDir . $filename;


// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    echo "Permission denied or failed to save file!";
    exit;
}

/* insert into database*/
$insertQuery = "INSERT INTO quest_submission
(submission_id, quest_id, user_id, proof_code, quest_submission_description, approval_status, submitted_at)
VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";

$stmt = mysqli_prepare($con, $insertQuery);

if (!$stmt) {
    echo "Database error!";
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    "sssss",
    $newSubmissionId,
    $quest_id,
    $test_user_id,
    $filename,
    $description
);


if (mysqli_stmt_execute($stmt)) {
    echo "Upload successful!";
} else {
    echo "Database insert failed!";
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
