<?php
// backend/questsubmission.php

/**
 * Processes approvals, awards coins/drops, and updates the player leaderboard.
 */
function handleReviewAction(mysqli $con) {
    if (isset($_POST['actionReview'])) {
        $submission_id = mysqli_real_escape_string($con, $_POST['submission_id']);
        $status = mysqli_real_escape_string($con, $_POST['actionReview']); 

        if ($status === 'Approved') {
            // Fetch rewards
            $rewardSql = "SELECT qs.user_id, q.drop_reward, q.eco_coin_reward 
                          FROM quest_submission qs JOIN quest q ON qs.quest_id = q.quest_id 
                          WHERE qs.submission_id = '$submission_id'";
            $rewardData = mysqli_fetch_assoc(mysqli_query($con, $rewardSql));
            
            $uid = $rewardData['user_id'];
            $drops = $rewardData['drop_reward'];
            $coins = $rewardData['eco_coin_reward'];

            // Update player account
            // This correctly targets user_player for game stats
            mysqli_query($con, "UPDATE user_player SET eco_coins = eco_coins + $coins, 
                                drops_progress = drops_progress + $drops, 
                                total_quests_completed = total_quests_completed + 1 
                                WHERE user_id = '$uid'");
        }

        // Update submission status
        mysqli_query($con, "UPDATE quest_submission SET approval_status = '$status' WHERE submission_id = '$submission_id'");
        header("Location: final.php?review_success=" . strtolower($status));
        exit();
    }
}

function renderReviewTab(mysqli $con) {
    // UPDATED: Now joins user_player to check 'player_status' instead of 'user.status'
    // This ensures that players 'Deleted' via User Management are correctly hidden here.
    $sql = "SELECT qs.*, q.quest_title, q.quest_description, q.quest_emoji, q.category, u.username 
            FROM quest_submission qs 
            JOIN quest q ON qs.quest_id = q.quest_id
            JOIN user u ON qs.user_id = u.user_id 
            JOIN user_player up ON u.user_id = up.user_id 
            WHERE qs.approval_status = 'Pending' AND up.player_status != 'Deleted'";
    
    $res = mysqli_query($con, $sql);
    if (!$res || mysqli_num_rows($res) === 0) {
        echo "<div class='empty-submission-msg'>🎉 No submissions pending approval!</div>";
        return;
    }

    $index = 0;
    while ($row = mysqli_fetch_assoc($res)) {
        $username = $row['username'];
        $display = ($index === 0) ? "flex" : "none";
        $userPfp = (file_exists(__DIR__ . "/../images/pfp/" . $username . ".jpg")) ? "images/pfp/" . $username . ".jpg" : "images/pfp/default_profile_picture.jpg";
        ?>
        <div class="review-card" id="review-card-<?php echo $index; ?>" style="display: <?php echo $display; ?>;">
            <div class="review-image-side"><img src="images/submission/<?php echo $row['submission_id']; ?>.jpg" onerror="this.src='images/submission/null.jpg'"></div>
            
            <div class="review-details-side">
                <div class="quest-info-block">
                    <h3 class="item-title">
                        <?php echo htmlspecialchars($row['quest_emoji']); ?> 
                        <?php echo htmlspecialchars($row['quest_title']); ?>
                    </h3>
                    <p class="item-description"><?php echo htmlspecialchars($row['quest_description']); ?></p>
                    <span class="category-pill"><?php echo htmlspecialchars($row['category']); ?></span>
                </div>

                <div class="user-info-block" style="margin-top: 20px;">
                    <div class="submitter-row">
                        <img src="<?php echo $userPfp; ?>" class="lb-avatar">
                        <div class="user-meta">
                            <p class="user-handle">@<?php echo htmlspecialchars($username); ?></p>
                            <p class="submit-date">Submitted on <?php echo date("d M Y", strtotime($row['submitted_at'])); ?></p>
                        </div>
                    </div>
                </div>

                <div class="message-info-block" style="margin-top: 20px;">
                    <h4 class="user-message-title">User Message</h4>
                    <div class="message-bubble"><p class="item-description"><?php echo htmlspecialchars($row['quest_submission_description']); ?></p></div>
                </div>

                <div class="card-footer-wrapper">
                    <div class="review-footer">
                        <p class="submission-count">Submission <?php echo ($index+1); ?> of <?php echo mysqli_num_rows($res); ?></p>
                        <div class="nav-btns">
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

function renderSubmissionHistory($con) {
    // UPDATED: Now joins user_player to check 'player_status'
    // This cleans up the history log by hiding activities from deleted players.
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
        echo '<p class="empty-msg">No approved quests found.</p>';
    }
    echo '</div>';
}
?>