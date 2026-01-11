<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "growvie";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$current_user_id = $_SESSION['user_id'];


if (isset($_POST['mark_read'])) {
    $announcement_id = $_POST['announcement_id'];
    

    $check_stmt = $conn->prepare("SELECT * FROM announcement_read WHERE user_id = ? AND announcement_id = ?");
    $check_stmt->bind_param("ss", $current_user_id, $announcement_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {

        $id_query = "SELECT announcement_read_id FROM announcement_read ORDER BY announcement_read_id DESC LIMIT 1";
        $id_result = $conn->query($id_query);
        
        if ($id_result->num_rows > 0) {
            $last_id = $id_result->fetch_assoc()['announcement_read_id'];
            $num = intval(substr($last_id, 2)) + 1;
            $new_id = 'AR' . str_pad($num, 3, '0', STR_PAD_LEFT);
        } else {
            $new_id = 'AR001';
        }
        
        $stmt = $conn->prepare("INSERT INTO announcement_read (announcement_read_id, user_id, announcement_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $new_id, $current_user_id, $announcement_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => true, 'message' => 'Already marked as read']);
    }
    
    $check_stmt->close();
    exit;
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
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    }
    .card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
    }
    .card.read {
    opacity: 0.6;
    background-color: #f9f9f9;
    }
    .card-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    align-items: center;
    }
    .card-header h4 {
    display: flex;
    align-items: center;
    gap: 8px;
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
    .unread-badge {
    width: 8px;
    height: 8px;
    background-color: #4CAF50;
    border-radius: 50%;
    display: inline-block;
    }
    .read-indicator {
    font-size: 11px;
    color: #888;
    padding: 2px 8px;
    background-color: #e8e8e8;
    border-radius: 12px;
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

        $sql = "SELECT a.*, ar.announcement_read_id 
                FROM announcement a 
                LEFT JOIN announcement_read ar ON a.announcement_id = ar.announcement_id 
                    AND ar.user_id = ? 
                ORDER BY a.announce_created_at DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $current_user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $currentDate = "";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $date = date("d F Y", strtotime($row['announce_created_at']));
                $time = date("h:i A", strtotime($row['announce_created_at']));
                $isRead = !empty($row['announcement_read_id']);

                if ($date !== $currentDate) {
                    echo "<h3 class='date'>$date</h3>";
                    $currentDate = $date;
                }
        ?>

        <div class="card <?php echo $isRead ? 'read' : ''; ?>" 
             data-announcement-id="<?php echo $row['announcement_id']; ?>"
             onclick="markAsRead(this, '<?php echo $row['announcement_id']; ?>')">
            <div class="card-header">
                <h4>
                    <?php if (!$isRead): ?>
                        <span class="unread-badge"></span>
                    <?php endif; ?>
                    <?php echo htmlspecialchars($row['announce_title']); ?>
                </h4>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <?php if ($isRead): ?>
                        <span class="read-indicator">Read</span>
                    <?php endif; ?>
                    <span><?php echo $time; ?></span>
                </div>
            </div>
            <p><?php echo nl2br(htmlspecialchars($row['announce_body'])); ?></p>
        </div>

        <?php
            }
        } else {
            echo "<p>No announcements available.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>

    </main>

</div>

<script>
function markAsRead(element, announcementId) {

    if (element.classList.contains('read')) {
        return;
    }

    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'mark_read=1&announcement_id=' + announcementId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {

            element.classList.add('read');
            
            const badge = element.querySelector('.unread-badge');
            if (badge) {
                badge.remove();
            }
            
            const timeContainer = element.querySelector('.card-header > div');
            if (timeContainer && !timeContainer.querySelector('.read-indicator')) {
                const readIndicator = document.createElement('span');
                readIndicator.className = 'read-indicator';
                readIndicator.textContent = 'Read';
                timeContainer.insertBefore(readIndicator, timeContainer.firstChild);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

</body>
</html>