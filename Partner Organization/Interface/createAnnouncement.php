<?php
    include('conn.php');
    $currentPage = basename($_SERVER['PHP_SELF']);
    date_default_timezone_set('Asia/Kuala_Lumpur');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Announcement</title>
</head>
<body>
    <!-- Gives the modal a dark transparent look  -->
    <div id="overlay" class="overlay"></div>
    <div class="modal-wrapper">
        <div id="modal" class="modal">
            <form method="POST" action="" id="announcementForm">
                <div class="modal-content announcementModal">
                    <!-- NEW: Hidden fields for edit functionality -->
                    <input type="hidden" id="announcement_id" name="announcement_id" value="">
                    <input type="hidden" id="mode" name="mode" value="create">
                    
                    <!-- NEW: Modal title that changes -->
                    <h3 id="modalTitle" style="margin-bottom: 15px;">Create New Announcement</h3>
                    
                    <h5 class="scheduleHeading" style="margin-bottom: 20px; font-size: 15px;">Schedule Date</h5>
                    <div class="dateSelector">
                        <!-- Day -->
                        <input type="number" min="1" max="31" class="date-box" id="day" name="day" required>

                        <!-- Month -->
                        <select class="month-box" id="month" name="month" required>
                            <option value="0">January</option>
                            <option value="1">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                            <option value="5">June</option>
                            <option value="6">July</option>
                            <option value="7">August</option>
                            <option value="8">September</option>
                            <option value="9">October</option>
                            <option value="10">November</option>
                            <option value="11">December</option>
                        </select>

                        <!-- Year -->
                        <input type="number" class="year-box" id="year" name="year" required>
                    </div>
                    
                    <!-- For users to enter announcemnt title -->
                    <h5 class="scheduleHeading">Announcement Title</h5>
                    <input type="text" id="announcementTitle" name="announcementTitle" required>

                    <!-- For users to enter announcement content -->
                    <h5 class="scheduleHeading">Announcement Content</h5>
                    <textarea class="announcementContent" id="announcementContent" name="announcementContent" required></textarea>
                </div>
                <div class="modal-button-container">
                    <button type="button" id="deleteBtn" onclick="deleteFromModal()" class="modal-delete-btn">
                        Delete Announcement
                    </button>
                    <button type="submit" id="submitBtn" class="reqUpload-btn">Post</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // This gets local today date
        const today = new Date();
        const dayInput = document.getElementById("day");
        const monthSelect = document.getElementById("month");
        const yearInput = document.getElementById("year");

        // This is to input the local date
        dayInput.value = today.getDate();
        monthSelect.selectedIndex = today.getMonth();
        yearInput.value = today.getFullYear();

        // This is to ensure date enter is not past
        function validateDate() {
            // This is to get user's input 
            const d = parseInt(dayInput.value);
            const m = monthSelect.selectedIndex;
            const y = parseInt(yearInput.value);

            // Here creates and formats the date 
            const chosen = new Date(y, m, d);
            const now = new Date();
            now.setHours(0,0,0,0);

            // alert shows when date input is past
            if (chosen < now) {
                alert("This date is unavailable.");
                dayInput.value = today.getDate();
                monthSelect.selectedIndex = today.getMonth();
                yearInput.value = today.getFullYear();
            }
        }

        dayInput.addEventListener("change", validateDate);
        monthSelect.addEventListener("change", validateDate);
        yearInput.addEventListener("change", validateDate);
    </script>

    <?php 
        // This function is to automatically  generate announcement ID
        function generateAnnouncementID($con) {
            // To get last annoucement id
            $queryAnnouncementID = "SELECT announcement_id 
                                    FROM announcement
                                    ORDER BY announcement_id DESC 
                                    LIMIT 1";
            $resultAnnouncementID = mysqli_query($con, $queryAnnouncementID);
            
            if ($resultAnnouncementID && mysqli_num_rows($resultAnnouncementID) > 0) {
                $row = mysqli_fetch_assoc($resultAnnouncementID);
                $largestAnnouncementID = $row['announcement_id'];

                // This is to separate pattern and number for ID
                $largestNumber = (int)substr($largestAnnouncementID, 1);
                // Increase number for new ID generation
                $newNumber = $largestNumber + 1;
                // Generate new announcement ID
                $createNewAnnouncementID = "A" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);
            } else {
                // this is used when there is no other announcement ID found in database
                $createNewAnnouncementID = "A001";
            }
            return $createNewAnnouncementID;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mode'])) {
            $mode = $_POST['mode'];
            
            // This is to get the data from user input
            $announcementTitle = mysqli_real_escape_string($con, $_POST['announcementTitle']);
            $announceBody = mysqli_real_escape_string($con, $_POST['announcementContent']);

            // Gets selected date
            $day = intval($_POST['day']);
            $month = intval($_POST['month']) + 1;
            $year = intval($_POST['year']);

            // Formats the date
            $scheduleDate = sprintf("%04d-%02d-%02d", $year, $month, $day);

            // checks if the schedule date is the same as local date to set annouce status
            $today = date('Y-m-d');
            if ($scheduleDate == $today) {
                $status = "Published";
            } else {
                $status = "Scheduled";
            }

            // Check if user is editing or creating
            if ($mode === 'edit' && isset($_POST['announcement_id']) && !empty($_POST['announcement_id'])) {
                // UPDATE existing announcement
                $announcementId = mysqli_real_escape_string($con, $_POST['announcement_id']);
                
                $stmt = mysqli_prepare($con, "UPDATE announcement 
                                              SET announce_title = ?,
                                                  announce_body = ?,
                                                  announce_status = ?,
                                                  announce_schedule_date = ?
                                              WHERE announcement_id = ?");
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssss", 
                        $announcementTitle,
                        $announceBody,
                        $status,
                        $scheduleDate,
                        $announcementId
                    );
                    
                    if (mysqli_stmt_execute($stmt)) {
                        echo '<script>alert("Announcement updated successfully!");
                        window.location.href = "announcement.php";
                        </script>';
                    } else {
                        echo '<script>alert("Error: ' . mysqli_stmt_error($stmt) . '");</script>';
                    }
                    
                    mysqli_stmt_close($stmt);
                } else {
                    echo '<script>alert("Error preparing update statement: ' . mysqli_error($con) . '");</script>';
                }
            } else {
                // to get the date announcement is created
                $createAt = date('Y-m-d');

                // To generate new announcement id
                $announcement_Id = generateAnnouncementID($con);

                // The following is to insert the data into announcement table in database
                $stmt = mysqli_prepare($con, "INSERT INTO announcement 
                        (announcement_id, announce_title, announce_body, announce_created_at, announce_status, announce_schedule_date) 
                        VALUES (?, ?, ?, ?, ?, ?)");

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssssss", 
                        $announcement_Id, 
                        $announcementTitle, 
                        $announceBody, 
                        $createAt, 
                        $status, 
                        $scheduleDate
                    );

                    if (mysqli_stmt_execute($stmt)) {
                        echo '<script>alert("Announcement created successfully!");
                        window.location.href = "announcement.php";
                        </script>';
                    } else {
                        echo '<script>alert("Error: ' . mysqli_stmt_error($stmt) . '");</script>';
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo '<script>alert("Error preparing statement: ' . mysqli_error($con) . '");</script>';
                }
            }

            mysqli_close($con);
        }
    ?>
</body>
</html>