<?php
/**
 * Handles Partner Organization Actions (Approve/Reject)
 */
function handlePartnerActions($con) {
    if (isset($_POST['partnerAction'])) {
        $treeId = mysqli_real_escape_string($con, $_POST['real_tree_id']);
        $action = $_POST['partnerAction'];

        if ($action === 'Approve') {
            // 1. Update status to Approved
            $updateSql = "UPDATE real_tree_record SET request_status = 'Approved' WHERE real_tree_id = '$treeId'";
            mysqli_query($con, $updateSql);

            // 2. Find associated user via virtual_plant
            $findUserSql = "SELECT vp.user_id 
                            FROM real_tree_record r
                            JOIN virtual_plant vp ON r.virtual_plant_id = vp.virtual_plant_id
                            WHERE r.real_tree_id = '$treeId'";
            $res = mysqli_query($con, $findUserSql);
            
            if ($row = mysqli_fetch_assoc($res)) {
                $userId = $row['user_id'];
                // 3. Increment tree_planted_irl for the user
                $updateStats = "UPDATE user_player SET tree_planted_irl = tree_planted_irl + 1 WHERE user_id = '$userId'";
                mysqli_query($con, $updateStats);
            }

        } elseif ($action === 'Reject') {
            // Update status to Rejected
            $updateSql = "UPDATE real_tree_record SET request_status = 'Rejected' WHERE real_tree_id = '$treeId'";
            mysqli_query($con, $updateSql);
        }

        header("Location: final.php?action=partner_updated");
        exit();
    }
}

/**
 * Renders the Partner Organization requests from real_tree_record.
 */
function renderPartnerRequests($con) {
    // Join real_tree_record -> virtual_plant -> user to get user details
    $sql = "SELECT r.*, u.username, u.name as fullname 
            FROM real_tree_record r
            JOIN virtual_plant vp ON r.virtual_plant_id = vp.virtual_plant_id
            JOIN user u ON vp.user_id = u.user_id
            WHERE r.request_status = 'Pending'
            ORDER BY r.date_reported DESC";
    
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $treeId = htmlspecialchars($row['real_tree_id']);
            $username = htmlspecialchars($row['username']);
            $fullname = htmlspecialchars($row['fullname']);
            $date = date("d/m/Y", strtotime($row['date_reported']));
            $site = htmlspecialchars($row['planting_site']);
            $loc = htmlspecialchars($row['location']);
            $coords = htmlspecialchars($row['coordinates']);
            
            // Image handling
            $imgName = $row['real_tree_id'] . ".jpg";
            $imgPath = "images/real_trees_planted/" . $imgName;
            $fallback = "images/submission/null.jpg"; // Reusing existing null placeholder

            // Buttons: Reuse 'action-btn' styles but standard size (not large)
            echo "
            <div class='request-card'>
                <img src='$imgPath' onerror=\"this.src='$fallback'\" alt='Tree Evidence'>
                
                <div class='request-info'>
                    <div class='info-grid'>
                        <span class='info-label'>User</span>
                        <span class='info-value'>$fullname <span class='username-at'>@$username</span></span>

                        <span class='info-label'>Request ID</span>
                        <span class='info-value'>$treeId</span>

                        <span class='info-label'>Report Date</span>
                        <span class='info-value'>$date</span>

                        <span class='info-label'>Planting Site</span>
                        <span class='info-value'>$site</span>

                        <span class='info-label'>Location</span>
                        <span class='info-value'>$loc</span>

                        <span class='info-label'>Coordinates</span>
                        <span class='info-value'>$coords</span>
                    </div>
                </div>

                <form method='POST' action='final.php' class='item-actions'>
                    <input type='hidden' name='real_tree_id' value='$treeId'>
                    <button type='submit' name='partnerAction' value='Reject' class='action-btn cfmdelete'>Reject</button>
                    <button type='submit' name='partnerAction' value='Approve' class='action-btn submit'>Approve</button>
                </form>
            </div>";
        }
    } else {
        echo "<p style='color:#666; text-align:center; margin-top:40px;'>No pending requests found.</p>";
    }
}

/**
 * Renders the History of Partner Organization requests (Approved/Rejected).
 */
function renderPartnerHistory($con) {
    // Join real_tree_record -> virtual_plant -> user
    $sql = "SELECT r.*, u.username, u.name as fullname 
            FROM real_tree_record r
            JOIN virtual_plant vp ON r.virtual_plant_id = vp.virtual_plant_id
            JOIN user u ON vp.user_id = u.user_id
            WHERE r.request_status != 'Pending'
            ORDER BY r.date_reported DESC";
    
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $treeId = htmlspecialchars($row['real_tree_id']);
            $username = htmlspecialchars($row['username']);
            $fullname = htmlspecialchars($row['fullname']);
            $date = date("d/m/Y", strtotime($row['date_reported']));
            $site = htmlspecialchars($row['planting_site']);
            $loc = htmlspecialchars($row['location']);
            $coords = htmlspecialchars($row['coordinates']);
            $status = htmlspecialchars($row['request_status']);
            
            // Image handling
            $imgName = $row['real_tree_id'] . ".jpg";
            $imgPath = "images/real_trees_planted/" . $imgName;
            $fallback = "images/submission/null.jpg"; 

            // Status Badge Logic
            $statusClass = ($status === 'Approved') ? 'status-approved' : 'status-rejected';

            echo "
            <div class='request-card'>
                <img src='$imgPath' onerror=\"this.src='$fallback'\" alt='Tree Evidence'>
                
                <div class='request-info'>
                    <div class='info-grid'>
                        <span class='info-label'>User</span>
                        <span class='info-value'>$fullname <span class='username-at'>@$username</span></span>

                        <span class='info-label'>Request ID</span>
                        <span class='info-value'>$treeId</span>

                        <span class='info-label'>Report Date</span>
                        <span class='info-value'>$date</span>

                        <span class='info-label'>Planting Site</span>
                        <span class='info-value'>$site</span>

                        <span class='info-label'>Location</span>
                        <span class='info-value'>$loc</span>

                        <span class='info-label'>Coordinates</span>
                        <span class='info-value'>$coords</span>
                    </div>
                </div>

                <div class='item-actions align-right'>
                    <span class='status-badge $statusClass'>$status</span>
                </div>
            </div>";
        }
    } else {
        echo "<p style='color:#666; text-align:center; margin-top:40px;'>No history found.</p>";
    }
}
?>
