<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "growvie";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


$friend_code = "123456789"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Growvie Profile</title>
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
font-size: 20px;
font-weight: 600;
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
color: #666;
cursor: pointer;
}

.content {
    flex: 1;
    padding: 24px;
    display: flex;
    flex-direction: column;
}

.content-wrapper {
    width: 100%;
}

.header {
    padding:30px;
    margin-bottom: 20px;
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
margin-bottom: 5px;

}

.header p {
font-size: 14px;
color: #000000;
}

.friend-code-section {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 10px;
    background: #C8D9C2;
    padding: 12px 20px;
    border-radius: 12px;
}

.friend-code-label {
    font-size: 14px;
    color: #000000;
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

.copy-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    width: 20px;
    height: 20px;
    position: relative;
}

.copy-btn::before {
    font-size: 16px;
}

.copy-btn:hover {
    opacity: 0.7;
}

.history-section {
    background: #ffffff;
    border-radius: 22px;
    padding: 28px 32px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

.section-title {
font-weight: bold;
margin-bottom: 20px;
font-size: 18px;
}

.history-card {
    background: #f5f5f5;
    padding: 18px;
    border-radius: 12px;
    margin-bottom: 18px;
    display: flex;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.history-card img {
width: 140px;
height: 105px;
border-radius: 10px;
object-fit: cover;
}

.history-info {
font-size: 14px;
color: #333;
flex: 1;
}

.history-info div {
margin-bottom: 6px;
line-height: 1.5;
}

.history-info strong {
font-size: 16px;
font-weight: 700;
display: block;
margin-bottom: 8px;
}

.info-row {
    display: flex;
    gap: 40px;
}

.info-label {
    font-weight: 600;
    color: #666;
    min-width: 160px;
}

.info-value {
    color: #333;
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
<div class="logout">Log Out</div>
</aside>

<main class="content">
     <div class="content-wrapper">

<section class="header">
<div>
<h1>Profile</h1>
<p>All the trees that you have planted.</p>
</div>

<div class="friend-code-section">
    <span class="friend-code-label">Friend Code</span>
    <div class="friend-code-box">
        <span><?php echo $friend_code; ?></span>
        <button class="copy-btn" onclick="copyFriendCode()" title="Copy to clipboard"></button>
    </div>
</div>
</section>

<div class="history-section">

<div class="section-title">Real Tree Plants History</div>

<?php
$sql = "SELECT * FROM real_tree_record ORDER BY real_tree_id DESC";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){
while($row = $result->fetch_assoc()){
?>

<div class="history-card">

<img src="uploads/<?= $row['photo_code'] ?>" alt="Tree">

<div class="history-info">
<strong><?= $row['real_tree_id'] ?></strong>
<div class="info-row">
    <span class="info-label">Partner Organization</span>
    <span class="info-value"><?= $row['partner_id'] ?></span>
</div>
<div class="info-row">
    <span class="info-label">Request Date</span>
    <span class="info-value"><?= date("d/m/Y, g:i a", strtotime($row['date_reported'])) ?></span>
</div>
<div class="info-row">
    <span class="info-label">Fulfillment Date</span>
    <span class="info-value"><?= date("d/m/Y, g:i a", strtotime($row['date_reported'])) ?></span>
</div>
<div class="info-row">
    <span class="info-label">Planting Site</span>
    <span class="info-value"><?= $row['planting_site'] ?></span>
</div>
<div class="info-row">
    <span class="info-label">Location</span>
    <span class="info-value"><?= $row['location'] ?></span>
</div>
<div class="info-row">
    <span class="info-label">Coordinates</span>
    <span class="info-value"><?= $row['coordinates'] ?></span>
</div>
</div>

</div>

<?php
}}
else{
echo "<p>No planting history found.</p>";
}
?>
    </div>
    </div>
</main>

</div>

<script>
function copyFriendCode() {
    const friendCode = "<?php echo $friend_code; ?>";
    navigator.clipboard.writeText(friendCode).then(() => {
        alert('Friend code copied to clipboard!');
    });
}
</script>

</body>
</html>