<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "growvie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = 'USR001'; // You can make this dynamic based on logged-in user

// Fetch user's friend code
$user_query = "SELECT user_id FROM user WHERE user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$friend_code = $user['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Growvie Quest Submission</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
margin: 0;
padding: 0;
box-sizing: border-box;
font-family: Arial, sans-serif;
}

body {
font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
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
display: flex;
align-items: center;
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
display: flex;
justify-content: space-between;
align-items: center;
}

.header h1 {
margin-bottom: 5px;
font-size: 32px;
}

.header p {
font-size: 15px;
color: #555;
}

.content {
flex: 1;
padding: 30px 40px 30px 20px;
}

.friend-code-section {
display: flex;
flex-direction: row;
align-items: center;
gap: 10px;
background: #C8D9C2;
padding: 12px 20px;
border-radius: 10px;
}

.friend-code-label {
font-size: 14px;
color: #000;
font-weight: 500;
}

.friend-code-box {
background: #ffffff;
padding: 8px 15px;
border-radius: 8px;
display: flex;
align-items: center;
gap: 12px;
border: none;
}

.friend-code-box span {
font-weight: 600;
color: #000;
}

.section-title {
font-size: 20px;
font-weight: 600;
color: #333;
margin-bottom: 20px;
}

.card {
background: #ffffff;
padding: 25px;
border-radius: 15px;
margin-bottom: 20px;
box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.submission-card {
display: flex;
justify-content: space-between;
align-items: flex-start;
gap: 20px;
padding: 20px;
background: #f8f9fa;
border-radius: 12px;
margin-bottom: 15px;
}

.submission-left {
flex: 1;
}

.quest-title {
display: flex;
align-items: center;
gap: 8px;
font-size: 16px;
font-weight: 600;
color: #333;
margin-bottom: 12px;
}

.submission-details {
display: flex;
flex-direction: column;
gap: 6px;
}

.detail-row {
display: flex;
align-items: center;
gap: 8px;
font-size: 13px;
color: #666;
}

.detail-icon {
width: 16px;
text-align: center;
}

.submission-right {
display: flex;
flex-direction: column;
align-items: flex-end;
gap: 10px;
min-width: 200px;
}

.time-info {
text-align: right;
font-size: 12px;
color: #888;
}

.status-badge {
padding: 8px 20px;
border-radius: 8px;
font-weight: 600;
font-size: 13px;
text-align: center;
min-width: 120px;
}

.status-badge.approved {
background: #8fe07b;
color: #2d5016;
}

.status-badge.pending {
background: #f0dc67;
color: #6b5d0f;
}

.status-badge.rejected {
background: #f28b82;
color: #7a1e16;
}

.submission-image {
width: 180px;
height: 120px;
border-radius: 10px;
object-fit: cover;
}

/* Form Styles */
.form-group {
margin-bottom: 20px;
}

.form-label {
display: block;
font-size: 14px;
font-weight: 600;
color: #333;
margin-bottom: 8px;
}

.form-control {
width: 100%;
padding: 12px 15px;
border: 1px solid #ddd;
border-radius: 8px;
font-size: 14px;
font-family: inherit;
transition: border-color 0.3s ease;
}

.form-control:focus {
outline: none;
border-color: #99e78a;
}

select.form-control {
cursor: pointer;
}

textarea.form-control {
resize: vertical;
min-height: 100px;
}

.file-upload {
border: 2px dashed #99e78a;
border-radius: 12px;
padding: 30px;
text-align: center;
cursor: pointer;
transition: all 0.3s ease;
background: #f8fdf6;
}

.file-upload:hover {
background: #eef9eb;
border-color: #7dd66f;
}

.file-upload-input {
display: none;
}

.file-upload-label {
cursor: pointer;
display: block;
}

.upload-icon {
font-size: 48px;
margin-bottom: 10px;
color: #99e78a;
}

.upload-text {
font-size: 14px;
color: #666;
margin-bottom: 5px;
}

.upload-subtext {
font-size: 12px;
color: #999;
}

.image-preview {
max-width: 300px;
max-height: 200px;
border-radius: 10px;
margin-top: 15px;
display: none;
}

.submit-btn {
width: 100%;
padding: 15px;
background: #99e78a;
border: none;
border-radius: 10px;
font-size: 16px;
font-weight: 600;
color: #2d5016;
cursor: pointer;
transition: all 0.3s ease;
}

.submit-btn:hover {
background: #8fe07b;
transform: translateY(-2px);
box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.submit-btn:active {
transform: translateY(0);
}

.success-message {
background: #8fe07b;
color: #2d5016;
padding: 15px 20px;
border-radius: 10px;
margin-bottom: 20px;
font-weight: 500;
}

.back-btn {
display: inline-block;
padding: 10px 20px;
background: #e0e0e0;
color: #333;
text-decoration: none;
border-radius: 8px;
font-size: 14px;
font-weight: 500;
transition: all 0.3s ease;
margin-bottom: 20px;
}

.back-btn:hover {
background: #d0d0d0;
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
<li class="active">Profile</li>
<li>Settings</li>
</ul>

<div class="logout" onclick="logout()">Log Out</div>
</aside>

<main class="content">

<section class="header">
<div>
<h1>My Quest Submissions</h1>
<p>View your quest submission history</p>
</div>

<div class="friend-code-section">
<span class="friend-code-label">Friend Code</span>
<div class="friend-code-box">
<span><?php echo $friend_code; ?></span>
</div>
</div>
</section>

<a href="profile.php" class="back-btn">← Back to Profile</a>

<!-- Quest Submission History -->
<h3 class="section-title">Your Quest Submission History</h3>

<?php
$result = $conn->query("SELECT qs.*, q.quest_title, q.quest_emoji 
                        FROM quest_submission qs 
                        LEFT JOIN quest q ON qs.quest_id = q.quest_id 
                        WHERE qs.user_id = '$user_id'
                        ORDER BY qs.submitted_at DESC");

if ($result->num_rows > 0):
while($row = $result->fetch_assoc()):
    // Calculate time remaining (example: 12 hours)
    $submitted_time = strtotime($row['submitted_at']);
    $current_time = time();
    $hours_passed = floor(($current_time - $submitted_time) / 3600);
    $hours_remaining = max(0, 24 - $hours_passed);
?>

<div class="card">
<div class="submission-card">

<div class="submission-left">
<div class="quest-title">
<?= htmlspecialchars($row['quest_emoji'] ?? '🎯') ?> <?= htmlspecialchars($row['quest_title'] ?? 'Quest ID: ' . $row['quest_id']) ?>
</div>

<div class="submission-details">
<div class="detail-row">
<span class="detail-icon">📝</span>
<span><?= htmlspecialchars($row['quest_submission_description']) ?></span>
</div>
<div class="detail-row">
<span class="detail-icon">📅</span>
<span>Submission Date: <?= date('d/m/Y', strtotime($row['submitted_at'])) ?></span>
</div>
<div class="detail-row">
<span class="detail-icon">🕐</span>
<span>Submission Time: <?= date('g:iA', strtotime($row['submitted_at'])) ?></span>
</div>
<div class="detail-row">
<span class="detail-icon">👍</span>
<span>Approved by 666 users</span>
</div>
<div class="detail-row">
<span class="detail-icon">🚩</span>
<span>Reported by 2 users</span>
</div>
<div class="detail-row">
<span class="detail-icon">✓</span>
<span><?= $row['approval_status'] === 'approved' ? 'Verified by admin' : 'Unverified' ?></span>
</div>
</div>
</div>

<div class="submission-right">
<div class="time-info">
<?= $hours_remaining ?> hours remaining<br>
12.2K users completed
</div>
<div class="status-badge <?= strtolower($row['approval_status']) ?>">
<?= strtoupper($row['approval_status']) ?>
</div>
<?php if (!empty($row['proof_code']) && file_exists($row['proof_code'])): ?>
<img src="<?= htmlspecialchars($row['proof_code']) ?>" alt="Quest proof" class="submission-image">
<?php endif; ?>
</div>

</div>
</div>

<?php 
endwhile;
else:
?>
<div class="card">
<p style="text-align: center; color: #888; padding: 20px;">
No quest submissions yet.
</p>
</div>
<?php endif; ?>

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

<?php
$conn->close();
?>