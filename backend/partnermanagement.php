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
 * Mirrors the structure of renderReviewTab in questsubmission.php
 */
function renderPartnerRequests($con) {
    // Join real_tree_record -> virtual_plant -> user to get player details
    // Join with partner table to get the organization details
    // Join with user table AGAIN (as 'up') to get the partner's username
    $sql = "SELECT r.*, 
                   u.username as player_username, u.name as player_fullname, 
                   p.organization_name, p.partner_id as org_id,
                   up.username as partner_username
            FROM real_tree_record r
            JOIN virtual_plant vp ON r.virtual_plant_id = vp.virtual_plant_id
            JOIN user u ON vp.user_id = u.user_id
            LEFT JOIN partner p ON r.partner_id = p.partner_id
            LEFT JOIN user up ON p.contact_email = up.email
            WHERE r.request_status = 'Pending'
            ORDER BY r.date_reported ASC";
    
    $res = mysqli_query($con, $sql);

    if (!$res || mysqli_num_rows($res) === 0) {
        echo "<div class='empty-state'>🎉 No pending tree planting requests!</div>";
        return;
    }

    $index = 0;
    $total = mysqli_num_rows($res);
    while ($row = mysqli_fetch_assoc($res)) {
        $treeId = htmlspecialchars($row['real_tree_id']);
        $display = ($index === 0) ? "flex" : "none";
        
        $partnerName = htmlspecialchars($row['organization_name'] ?? 'Unknown Partner');
        $partnerUsername = htmlspecialchars($row['partner_username'] ?? 'unknown');
        
        $date = date("d M Y", strtotime($row['date_reported']));
        $site = htmlspecialchars($row['planting_site']);
        $loc = htmlspecialchars($row['location']);
        $coords = htmlspecialchars($row['coordinates']);
        
        $imgPath = "images/real_trees_planted/" . $row['real_tree_id'] . ".jpg";
        $fallback = "images/submission/null.jpg";
        
        // Player Avatar
        $playerPfp = "images/pfp/" . $row['player_username'] . ".jpg";
        $playerAvatar = (file_exists(__DIR__ . "/../" . $playerPfp)) ? $playerPfp : "images/pfp/default_profile_picture.jpg";
        
        // Partner Avatar
        $partnerPfp = "images/pfp/" . $partnerUsername . ".jpg";
        $partnerAvatar = (file_exists(__DIR__ . "/../" . $partnerPfp)) ? $partnerPfp : "images/pfp/default_profile_picture.jpg"; 
        ?>
        <div class="review-card" id="partner-review-card-<?php echo $index; ?>" style="display: <?php echo $display; ?>;">
            <div class="review-image-side">
                <img src="<?php echo $imgPath; ?>" onerror="this.src='<?php echo $fallback; ?>'" alt="Tree Evidence">
            </div>
            
            <div class="review-details-side">
                <div class="review-content-scrollable">
                    <div class="partner-header-top title-spaced">
                        <h3 class="item-title"><?php echo $treeId; ?></h3>
                        <p class="date-text top-right-info">Reported on <?php echo $date; ?></p>
                    </div>

                    <div class="info-panel">
                        <span class="info-label">Requested by</span>
                        <div class="submitter-row partner-submitter-gap">
                            <img src="<?php echo $playerAvatar; ?>" class="lb-avatar">
                            <div class="user-meta">
                                <p class="user-handle"><?php echo htmlspecialchars($row['player_fullname']); ?></p>
                                <p class="submit-date">@<?php echo htmlspecialchars($row['player_username']); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="info-panel">
                        <span class="info-label">Handled by</span>
                        <div class="submitter-row partner-submitter-gap">
                            <img src="<?php echo $partnerAvatar; ?>" class="partner-avatar-square">
                            <div class="user-meta">
                                <p class="user-handle"><?php echo $partnerName; ?></p>
                                <p class="submit-date">@<?php echo $partnerUsername; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="info-panel">
                        <div class="partner-info-grid">
                            <span class="info-label">Planting Site</span>
                            <span class="info-value"><?php echo $site; ?></span>

                            <span class="info-label">Location</span>
                            <span class="info-value"><?php echo $loc; ?></span>

                            <span class="info-label">Coordinates</span>
                            <span class="info-value"><?php echo $coords; ?></span>
                        </div>
                    </div>
                </div>

                <div class="card-footer-wrapper">
                    <div class="review-footer">
                        <p class="submission-count">Request <?php echo ($index+1); ?> of <?php echo $total; ?></p>
                        <div class="nav-btns">
                            <button type="button" class="nav-btn-alt" onclick="navigatePartner(<?php echo $index-1; ?>)" <?php if($index==0) echo 'disabled'; ?>>Back</button>
                            <button type="button" class="nav-btn-alt" onclick="navigatePartner(<?php echo $index+1; ?>)" <?php if($index==$total-1) echo 'disabled'; ?>>Next</button>
                        </div>
                    </div>
                    <form method="POST" action="final.php" class="review-actions">
                        <input type="hidden" name="real_tree_id" value="<?php echo $row['real_tree_id']; ?>">
                        <button type="submit" name="partnerAction" value="Reject" class="action-btn large cfmdelete">Reject</button>
                        <button type="submit" name="partnerAction" value="Approve" class="action-btn large submit">Approve</button>
                    </form>
                </div>
            </div>
        </div>
        <?php $index++;
    }
    ?>
    <script>
        function navigatePartner(idx) {
            document.querySelectorAll('[id^="partner-review-card-"]').forEach(card => card.style.display = 'none');
            const target = document.getElementById('partner-review-card-' + idx);
            if(target) target.style.display = 'flex';
        }
    </script>
    <?php
}

function renderPartnerHistory($con) {
    // Join real_tree_record -> virtual_plant -> user
    // AND Join with partner table to get the organization details
    $sql = "SELECT r.*, u.username, u.name as player_name, u.user_id, p.organization_name, p.partner_id
            FROM real_tree_record r
            JOIN virtual_plant vp ON r.virtual_plant_id = vp.virtual_plant_id
            JOIN user u ON vp.user_id = u.user_id
            LEFT JOIN partner p ON r.partner_id = p.partner_id
            WHERE r.request_status != 'Pending'
            ORDER BY r.date_reported DESC";
    
    $result = mysqli_query($con, $sql);

    echo '<div class="lb-list">';
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $treeId = htmlspecialchars($row['real_tree_id']);
            $username = htmlspecialchars($row['username']);
            $fullName = htmlspecialchars($row['player_name'] ?? $username);
            $orgName = htmlspecialchars($row['organization_name'] ?? 'Unknown Partner');
            $status = htmlspecialchars($row['request_status']);
            $statusClass = strtolower($status);

            ?>
            <div class="lb-row partner-history-row"> 
                <div class="lb-meta"> 
                    <div class="history-top-row">
                        <span class="lb-name"><?php echo $treeId; ?></span>
                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                    </div>
                    <div class="history-info-grid">
                        <span class="info-label">Partner</span> 
                        <span class="info-value"><?php echo $orgName; ?></span>
                        
                        <span class="info-label">Player</span> 
                        <span class="info-value"><?php echo $fullName; ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<p class="empty-state">No history found.</p>';
    }
    echo '</div>';
}
?>