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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

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
        overflow: hidden;
        gap:20px;
    }


    .sidepanel {
        width: 260px;
        flex-shrink: 0;
    }


    .settings-content {
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
    }


    .header-row h1{
        flex-shrink:0;
        font-weight:600;
        margin-top:5px;
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
    <div class = "container-wrapper">
        <?php include "sidepanel.php"; ?>

        <!--main dashboard-->
        <div class="settings-content">
            <div class="header-row">
                <div class="header-text">
                    <h1>Notifications</h1>
                    <p class="tagline">Stay Updated with Notifications and Announcements!</p>
                </div>

            </div>
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
        </div>
    </div>

