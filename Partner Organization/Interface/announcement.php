<?php
    include('conn.php');
    // This sets the browser time to be local time
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentPage = basename($_SERVER['PHP_SELF']);
    include ('sidebar.php');
    //This will automatically publish scheduled announcement when the date is matching
    mysqli_query($con, "
        UPDATE announcement 
        SET announce_status='Published' 
        WHERE announce_status='Scheduled' 
        AND announce_schedule_date <= CURDATE()
    ");
    // Handle delete request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_announcement_id'])) {
        $deleteId = mysqli_real_escape_string($con, $_POST['delete_announcement_id']);
        
        // This checks if the announcement exists
        $checkQuery = "SELECT announcement_id FROM announcement WHERE announcement_id = '$deleteId'";
        $checkResult = mysqli_query($con, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            // Delete the record
            $deleteQuery = "DELETE FROM announcement WHERE announcement_id = '$deleteId'";
            
            if (mysqli_query($con, $deleteQuery)) {
                if (mysqli_affected_rows($con) > 0) {
                    echo '<script>
                        alert("Announcement deleted successfully!");
                        window.location.href = "announcement.php";
                    </script>';
                    exit;
                } else {
                    echo '<script>alert("No rows were deleted. ID: ' . $deleteId . '");</script>';
                }
            } else {
                echo '<script>alert("Error: ' . mysqli_error($con) . '");</script>';
            }
        } else {
            echo '<script>alert("Announcement not found.");</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growie Announcement Page</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <?php include("createAnnouncement.php"); ?> 
    <!-- This define when the modal for creatign new announcement should be shown or closed -->
   <script>
        function openModal() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("modal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("modal").style.display = "none";
        }

        document.getElementById("overlay").addEventListener("click", closeModal);
    </script>

    <?php
        // This gets all of announcements to display when annouce status is Published
        $queryAnnouncementDetails = "SELECT * FROM announcement WHERE announce_status = 'Published' ";
        $resultAnnouncementDetails = mysqli_query($con, $queryAnnouncementDetails);
    ?>

    <div class="container">       
        <main class="content">
            <h1>Announcements</h1>
            <h3>Welcome to announcement page!</h3>
            <!-- This button is to open create announcement modal -->
            <button class="newAnnouncement" onclick ="openModal()">+ Create a new announcement</button>
            <!-- This is to display the local date -->
            <div id="date"></div>
            <?php 
                // Generates a block to display announcement for every result retrived
                while ($rowAnnouncementDetails = mysqli_fetch_assoc($resultAnnouncementDetails)) { 
            ?> 
                <div class="announcementBoxWrapper">
                    <div class="announcementBox">
                        <div class="upperAnnouncementBox">
                            <div class="announcementTitle"><?= $rowAnnouncementDetails['announce_title'] ?></div>
                            <div class="announcementDate"><?= $rowAnnouncementDetails['announce_schedule_date'] ?></div>
                        </div>
                        <div class="announcement">
                            <?= $rowAnnouncementDetails['announce_body'] ?>
                        </div>
                        <!-- This is to delete announcement that is on display -->
                        <form method="POST" action="" onsubmit="return confirmDelete('<?= htmlspecialchars($rowAnnouncementDetails['announce_title']) ?>')">
                            <input type="hidden" name="delete_announcement_id" value="<?= $rowAnnouncementDetails['announcement_id'] ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </main>
        
    </div>
    <script>
        // This function is to get and format the date for displaying purpose
        const formattedDate = new Date().toLocaleDateString('en-GB', {day: 'numeric', month: 'long', year: 'numeric'});
        document.getElementById("date").innerHTML = formattedDate;
        // To confirm annoucement selected to be deleted
        function confirmDelete(title) {
            return confirm("Are you sure you want to delete the announcement: '" + title + "'?");
        }
    </script>
</body>
</html>