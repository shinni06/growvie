<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "growvie";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get current user from session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$current_user_id = $_SESSION['user_id'];

// Get user's friend code (user_id serves as friend code)
$friend_code = $current_user_id;
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
height: 100px;
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
    padding: 0 0 0 30px; 
    margin-bottom: 80px;
    margin-top: 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
margin-bottom: 5px;
}

.header p {
font-size: 14px;
color: #000000ff;
}

.friend-box {
background: #f1f7ee;
padding: 6px 12px;
border-radius: 10px;
}

.friend-box span {
font-weight: bold;
margin-right: 6px;
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
    color: #000000ff;
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

.section-title {
font-weight: bold;
margin-bottom: 20px;
}

.history-section {
    background: #ffffff;
    border-radius: 22px;
    padding: 28px 32px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

.history-card {
    background: #d3e0d3;
    padding: 18px;
    border-radius: 16px;
    margin-bottom: 18px;
    display: flex;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.history-card img {
width: 120px;
height: 90px;
border-radius: 10px;
object-fit: cover;
}

.history-info {
font-size: 14px;
color: #333;
}

.history-info div {
margin-bottom: 4px;
line-height: 1.4;
}

.history-info strong {
font-size: 15px;
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
<li>Notifications </a></li>
<li>Friends</li>
<li class="active">Profile</li>
<li>Settings</li>
</ul>
<div class="logout" onclick="logout()">Log Out</div>
</aside>

<main class="content">
     <div class="content-wrapper">

<section class="header">
<div>
<h1>Profile</h1>
<p>All the trees you've planted so far</p>
</div>

<div class="friend-code-section">
    <span class="friend-code-label">Friend Code</span>
    <div class="friend-code-box">
        <span><?php echo $friend_code; ?></span>
        <button class="copy-btn" onclick="copyFriendCode()" title="Copy to clipboard">📋</button>
    </div>
</div>
</section>

<div class="history-section">

<div class="section-title">Real Tree Plants History</div>

<?php
// Get real tree records for current user's virtual plants
$sql = "SELECT rtr.* 
        FROM real_tree_record rtr
        INNER JOIN virtual_plant vp ON rtr.virtual_plant_id = vp.virtual_plant_id
        WHERE vp.user_id = ?
        ORDER BY rtr.date_reported DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result && $result->num_rows > 0){
while($row = $result->fetch_assoc()){
?>

<div class="history-card">

<img src="uploads/<?= $row['photo_code'] ?>" alt="Tree">

<div class="history-info">
<div><strong><?= $row['real_tree_id'] ?></strong></div>
<div>Virtual Plant ID: <?= $row['virtual_plant_id'] ?></div>
<div>Partner ID: <?= $row['partner_id'] ?></div>
<div>Location: <?= $row['location'] ?></div>
<div>Coordinates: <?= $row['coordinates'] ?></div>
<div>Planting Site: <?= $row['planting_site'] ?></div>
<div>Reported: <?= date("d/m/Y, g:i a", strtotime($row['date_reported'])) ?></div>
</div>

</div>

<?php
}}
else{
echo "<p>No planting history found.</p>";
}

$stmt->close();
$conn->close();
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

function logout() {
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'logout.php';
    }
}
</script>

</body>
</html>