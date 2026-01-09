<?php
function handleAnnouncementActions(mysqli $con) {
    if (isset($_POST['postAnnouncement'])) {

        $id     = mysqli_real_escape_string($con, $_POST['announcement_id'] ?? '');
        $title  = mysqli_real_escape_string($con, $_POST['announce_title']);
        $body   = mysqli_real_escape_string($con, $_POST['announce_body']);
        $date   = mysqli_real_escape_string($con, $_POST['announce_schedule_date']);

        $today  = date('Y-m-d');
        $status = ($date > $today) ? 'Scheduled' : 'Published';

        if (!empty($id)) {
            $sql = "
                UPDATE announcement SET
                    announce_title = '$title',
                    announce_body = '$body',
                    announce_schedule_date = '$date',
                    announce_status = '$status'
                WHERE announcement_id = '$id'
            ";
        } else {
            $last = mysqli_query($con, "SELECT announcement_id FROM announcement ORDER BY announcement_id DESC LIMIT 1");
            $row  = mysqli_fetch_assoc($last);
            $newId = $row
                ? "A" . str_pad((int)substr($row['announcement_id'], 1) + 1, 3, "0", STR_PAD_LEFT)
                : "A001";

            $sql = "
                INSERT INTO announcement (
                    announcement_id,
                    announce_title,
                    announce_body,
                    announce_created_at,
                    announce_status,
                    announce_schedule_date
                ) VALUES (
                    '$newId',
                    '$title',
                    '$body',
                    '$today',
                    '$status',
                    '$date'
                )
            ";
        }

        mysqli_query($con, $sql);
        header("Location: final.php?announcement_success=true");
        exit();
    }

    if (isset($_POST['deleteAnnouncement'])) {
        $id = mysqli_real_escape_string($con, $_POST['delete_announcement_id']);
        mysqli_query($con, "DELETE FROM announcement WHERE announcement_id = '$id'");
        header("Location: final.php?announcement_success=deleted");
        exit();
    }
}

function renderAnnouncements(mysqli $con) {

    $sql = "
        SELECT *
        FROM announcement
        WHERE announce_status IN ('Published','Scheduled')
        ORDER BY announce_schedule_date DESC
    ";

    $res = mysqli_query($con, $sql);

    if (!$res || mysqli_num_rows($res) === 0) {
        echo "<p class='empty-state'>No announcements found.</p>";
        return;
    }

    while ($a = mysqli_fetch_assoc($res)) {
        ?>
        <div class="announcement-card">
            <div class="announcement-header">
                <h3 class="item-title title-spaced"><?php echo htmlspecialchars($a['announce_title']); ?></h3>
                <span class="time"> 
                    <?php echo date("d F Y", strtotime($a['announce_created_at'])); ?>
                </span>
            </div>

            <p class="item-description"><?php echo htmlspecialchars($a['announce_body']); ?></p>

            <div class="item-actions align-right">
                <button class="action-btn edit" onclick='openEditAnnouncement(<?php echo json_encode($a); ?>)'>Edit</button>
                <button class="action-btn delete" onclick="openDeleteAnnouncement('<?php echo $a['announcement_id']; ?>','<?php echo addslashes($a['announce_title']); ?>')">Delete</button>
            </div>
        </div>
        <?php
    }
}
