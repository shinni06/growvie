<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "growvie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Growvie Announcements</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

    *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
    }
    body {
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;;
    background-color: #DAE4D6;
    height: 100px;
    }
    .container {
    display: flex;
    min-height: 100vh;
    }
    .sidebar {
    width: 220px;
    background: #ffffffff;
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
    background-color: #A7EF89;
    }
    .logout {
    margin-top: auto;
    color: #666;
    cursor: pointer;
    }
    .header {
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
    }
    .header h1 {
    margin-bottom: 5px;
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
    .content {
    flex: 1;
    padding: 30px 40px 30px 20px;
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
            <li class="active">Notifications</li>
            <li>Friends</li>
            <li>Profile</li>
            <li>Settings</li>
        </ul>

        <div class="logout">Log Out</div>
    </aside>
    <main class="content">

        <section class="header">
            <h1>Notifications</h1>
            <p>Latest notifications all here </p>
        </section>

        <?php
        $sql = "SELECT * FROM announcement ORDER BY announce_created_at DESC";
        $result = $conn->query($sql);

        $currentDate = "";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $date = date("d F Y", strtotime($row['announce_created_at']));
                $time = date("h:i A", strtotime($row['announce_created_at']));

                if ($date !== $currentDate) {
                    echo "<h3 class='date'>$date</h3>";
                    $currentDate = $date;
                }
        ?>

        <div class="card">
            <div class="card-header">
                <h4><?php echo htmlspecialchars($row['announce_title']); ?></h4>
                <span><?php echo $time; ?></span>
            </div>
            <p><?php echo nl2br(htmlspecialchars($row['announce_body'])); ?></p>
        </div>

        <?php
            }
        } else {
            echo "<p>No announcements available.</p>";
        }

        $conn->close();
        ?>

    </main>

</div>

</body>
</html>