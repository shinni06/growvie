<?php

// Function to handle quest submissions
function handleReviewAction(mysqli $con) {
    if (isset($_POST['actionReview'])) {
        $submission_id = mysqli_real_escape_string($con, $_POST['submission_id']);
        $status = mysqli_real_escape_string($con, $_POST['actionReview']); 

        // Handle approving quest submissions
        if ($status === 'Approved') {
            // Get reward amount
            $rewardSql = "SELECT qs.user_id, q.drop_reward, q.eco_coin_reward 
                          FROM quest_submission qs JOIN quest q ON qs.quest_id = q.quest_id 
                          WHERE qs.submission_id = '$submission_id'";
            $rewardData = mysqli_fetch_assoc(mysqli_query($con, $rewardSql));
            
            $uid = $rewardData['user_id'];
            $drops = $rewardData['drop_reward'];
            $coins = $rewardData['eco_coin_reward'];

            // Send rewards to players and increase quest completion count
            mysqli_query($con, "UPDATE user_player SET eco_coins = eco_coins + $coins, 
                                drops_progress = drops_progress + $drops, 
                                total_quests_completed = total_quests_completed + 1 
                                WHERE user_id = '$uid'");
        }

        // Update status of quest submission to "Approved"
        mysqli_query($con, "UPDATE quest_submission SET approval_status = '$status' WHERE submission_id = '$submission_id'");
        header("Location: final.php?review_success=" . strtolower($status));
        exit();
    }
}

// Render HTML for Quest Submissions from Partner
function renderReviewTab(mysqli $con) {
    $sql = "SELECT qs.*, q.quest_title, q.quest_description, q.quest_emoji, q.category, u.username, up.player_tier 
            FROM quest_submission qs 
            JOIN quest q ON qs.quest_id = q.quest_id
            JOIN user u ON qs.user_id = u.user_id 
            JOIN user_player up ON u.user_id = up.user_id 
            WHERE qs.approval_status = 'Pending' AND up.player_status != 'Deleted'
            ORDER BY qs.submitted_at ASC";
    
    $res = mysqli_query($con, $sql);
    if (!$res || mysqli_num_rows($res) === 0) {
        echo "<div class='empty-state'>No submissions pending approval!</div>";
        return;
    }

    $index = 0;
    while ($row = mysqli_fetch_assoc($res)) {
        $username = $row['username'];
        $tier = (int)($row['player_tier'] ?? 1);
        $display = ($index === 0) ? "flex" : "none";
        $userPfp = (file_exists(__DIR__ . "/../images/pfp/" . $username . ".png")) ? "images/pfp/" . $username . ".png" : "images/pfp/default_profile_picture.png";
        ?>
        <div class="review-card" id="review-card-<?php echo $index; ?>" style="display: <?php echo $display; ?>;">
            <div class="review-image-side"><img src="images/submission/<?php echo $row['submission_id']; ?>.png" onerror="this.src='images/submission/null.png'"></div>
            
            <div class="review-details-side">
                <div class="review-content-scrollable">
                    <div>
                        <h3 class="item-title">
                            <?php echo htmlspecialchars($row['quest_emoji']); ?> 
                            <?php echo htmlspecialchars($row['quest_title']); ?>
                        </h3>
                        <p class="item-description"><?php echo htmlspecialchars($row['quest_description']); ?></p>
                        <span class="category-pill"><?php echo htmlspecialchars($row['category']); ?></span>
                    </div>

                    <div class="content-section-gap">
                        <div class="message-bubble">
                            <div class="submitter-row">
                                <img src="<?php echo $userPfp; ?>" class="lb-avatar tier-border-<?php echo $tier; ?>">
                                <div class="user-meta">
                                    <p class="user-handle"><?php echo htmlspecialchars($username); ?></p>
                                    <p class="submit-date">@<?php echo htmlspecialchars($username); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="message-info-block content-section-gap">
                        <div class="message-bubble">
                            <p class="item-description no-margin"><?php echo htmlspecialchars($row['quest_submission_description']); ?></p>
                        </div>
                        <p class="date-text" style="text-align: right; margin-top: 10px;">Submitted on <?php echo date("d M Y", strtotime($row['submitted_at'])); ?></p>
                    </div>
                </div>

                <div class="card-footer-wrapper">
                    <div class="review-footer">
                        <p class="submission-count">Submission <?php echo ($index+1); ?> of <?php echo mysqli_num_rows($res); ?></p>
                        <div>
                            <button type="button" class="nav-btn-alt" onclick="navigateReview(<?php echo $index-1; ?>)" <?php if($index==0) echo 'disabled'; ?>>Back</button>
                            <button type="button" class="nav-btn-alt" onclick="navigateReview(<?php echo $index+1; ?>)" <?php if($index==mysqli_num_rows($res)-1) echo 'disabled'; ?>>Next</button>
                        </div>
                    </div>
                    <form method="POST" action="final.php" class="review-actions">
                        <input type="hidden" name="submission_id" value="<?php echo $row['submission_id']; ?>">
                        <button type="submit" name="actionReview" value="Rejected" class="action-btn large cfmdelete">Reject</button>
                        <button type="submit" name="actionReview" value="Approved" class="action-btn large submit">Approve</button>
                    </form>
                </div>
            </div>
        </div>
        <?php $index++;
    }
    ?>
    <script>
        function navigateReview(idx) {
            document.querySelectorAll('[id^="review-card-"]').forEach(card => card.style.display = 'none');
            const target = document.getElementById('review-card-' + idx);
            if(target) target.style.display = 'flex';
        }
    </script>
    <?php
}

// Render HTML for Approval Log
function renderSubmissionHistory($con) {
    $query = "SELECT q.quest_title, q.quest_emoji, u.username, qs.submitted_at 
              FROM quest_submission qs 
              JOIN quest q ON qs.quest_id = q.quest_id 
              JOIN user u ON qs.user_id = u.user_id
              JOIN user_player up ON u.user_id = up.user_id
              WHERE qs.approval_status = 'Approved' AND up.player_status != 'Deleted'
              ORDER BY qs.submitted_at DESC";
    $result = mysqli_query($con, $query);

    echo '<div class="lb-list">';
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="lb-row"> 
                <div class="card-icon-small"><?php echo htmlspecialchars($row['quest_emoji']); ?></div>
                <div class="lb-meta"> 
                    <div class="lb-name"><?php echo htmlspecialchars($row['quest_title']); ?></div>
                    <div class="lb-sub">by @<?php echo htmlspecialchars($row['username']); ?> • <?php echo date('d M Y', strtotime($row['submitted_at'])); ?></div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<p class="empty-state">No approved quests found.</p>';
    }
    echo '</div>';
}
?>