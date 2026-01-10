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
    <!-- This define when the modal for creating new announcement should be shown or closed -->
   <script>
        function openModal(mode = 'create', announcementId = null, title = '', body = '', date = '') {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("modal").style.display = "block";
            
            if (mode === 'edit') {
                // Parse the date (format: YYYY-MM-DD)
                const dateObj = new Date(date);
                const day = dateObj.getDate();
                const month = dateObj.getMonth(); // 0-indexed
                const year = dateObj.getFullYear();
                
                // Pre-fill form fields for editing
                document.getElementById("announcementTitle").value = title;
                document.getElementById("announcementContent").value = body;
                document.getElementById("day").value = day;
                document.getElementById("month").selectedIndex = month;
                document.getElementById("year").value = year;
                document.getElementById("announcement_id").value = announcementId;
                document.getElementById("mode").value = 'edit';
                
                // Change UI for edit mode
                document.getElementById("modalTitle").textContent = "Edit Announcement";
                document.getElementById("submitBtn").textContent = "Re-upload";
                document.getElementById("deleteBtn").style.display = "block";
                document.getElementById("deleteBtn").setAttribute('data-id', announcementId);
                document.getElementById("deleteBtn").setAttribute('data-title', title);
            } else {
                // Reset for create mode
                document.getElementById("announcementForm").reset();
                const today = new Date();
                document.getElementById("day").value = today.getDate();
                document.getElementById("month").selectedIndex = today.getMonth();
                document.getElementById("year").value = today.getFullYear();
                document.getElementById("announcement_id").value = '';
                document.getElementById("mode").value = 'create';
                document.getElementById("modalTitle").textContent = "Create New Announcement";
                document.getElementById("submitBtn").textContent = "Post";
                document.getElementById("deleteBtn").style.display = "none";
            }
        }

        function closeModal() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("modal").style.display = "none";
        }

        document.getElementById("overlay").addEventListener("click", closeModal);
        
        function deleteFromModal() {
            const deleteBtn = document.getElementById("deleteBtn");
            const id = deleteBtn.getAttribute('data-id');
            const title = deleteBtn.getAttribute('data-title');
            
            if (confirm("Are you sure you want to delete the announcement: '" + title + "'?")) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_announcement_id';
                input.value = id;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
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
                       
                        <button 
                            type="button" 
                            class="edit-btn"
                            onclick="openModal(
                                'edit', 
                                '<?= $rowAnnouncementDetails['announcement_id'] ?>', 
                                '<?= addslashes($rowAnnouncementDetails['announce_title']) ?>', 
                                '<?= addslashes($rowAnnouncementDetails['announce_body']) ?>', 
                                '<?= $rowAnnouncementDetails['announce_schedule_date'] ?>'
                            )">
                            Edit
                        </button>
                    </div>
                </div>
            <?php } ?>
        </main>
        
    </div>
    <script>
        // This function is to get and format the date for displaying purpose
        const formattedDate = new Date().toLocaleDateString('en-GB', {day: 'numeric', month: 'long', year: 'numeric'});
        document.getElementById("date").innerHTML = formattedDate;
    </script>
</body>
</html>