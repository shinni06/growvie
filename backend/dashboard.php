<?php

// Render HTML for Quest Cards
function renderQuestCards(mysqli $con, int $limit = 20) {
    $sql = "
        SELECT
            q.quest_id, q.quest_title, q.quest_description, q.quest_emoji, q.category,
            q.drop_reward, q.eco_coin_reward, q.date_created,
            COUNT(DISTINCT CASE WHEN qs.approval_status = 'Approved' THEN qs.user_id END) AS users_completed
        FROM quest q
        LEFT JOIN quest_submission qs ON qs.quest_id = q.quest_id
        WHERE q.status = 'Active' 
        GROUP BY q.quest_id, q.quest_title, q.quest_description, q.quest_emoji, q.category, q.drop_reward, q.eco_coin_reward, q.date_created
        ORDER BY q.date_created DESC
        LIMIT $limit
    ";

    $res = mysqli_query($con, $sql);
    if (!$res) return;

    while ($q = mysqli_fetch_assoc($res)) {
        ?>
        <div class="quest-card">
            <div class="quest-left">
                <div class="quest-icon"><?php echo htmlspecialchars($q["quest_emoji"]); ?></div>
                <div class="quest-content">
                    <div class="item-title"><?php echo htmlspecialchars($q["quest_title"]); ?></div>
                    <div class="item-description"><?php echo htmlspecialchars($q["quest_description"]); ?></div>
                    <div class="quest-meta-row"><span class="quest-category"><?php echo htmlspecialchars($q["category"]); ?></span></div>
                    <div class="item-actions">
                        <button type="button" class="action-btn edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($q)); ?>)">Edit</button>
                        <button type="button" class="action-btn deactivate" onclick="openDeactivateModal('<?php echo $q['quest_id']; ?>', '<?php echo addslashes($q['quest_title']); ?>')">Deactivate</button>
                        <button type="button" class="action-btn delete" onclick="openDeleteModal('<?php echo $q['quest_id']; ?>', '<?php echo addslashes($q['quest_title']); ?>')">Delete</button>
                    </div>
                </div>
            </div>
            <div class="quest-right">
                <div class="reward-badges top-right-info">
                    <div class="badge drop-badge">💧 <?php echo (int)$q["drop_reward"]; ?> <span class="badge-unit">Drops</span></div>
                    <div class="badge coin-badge">🪙 <?php echo (int)$q["eco_coin_reward"]; ?> <span class="badge-unit">EcoCoins</span></div>
                </div>
                <span class="date-text bottom-right-info">Completed <?php echo number_format((int)$q["users_completed"]); ?> times</span>
            </div>
        </div>
        <?php
    }
}

// Render HTML for Inactive Quest Cards
function renderInactiveQuestCards(mysqli $con) {
    $sql = "SELECT * FROM quest WHERE status != 'Active' ORDER BY date_created DESC";
    $res = mysqli_query($con, $sql);

    if (!$res || mysqli_num_rows($res) === 0) {
        echo "<p class='empty-state'>No inactive quests found.</p>";
        return;
    }

    $today = date('Y-m-d');

    while ($q = mysqli_fetch_assoc($res)) {
        // Determine the display message
        $activationDate = $q['date_created'];
        $statusLabel = ($activationDate > $today) 
            ? "Scheduled: " . date("d M Y", strtotime($activationDate)) 
            : "Manually Deactivated";
        ?>
        <div class="quest-card quest-card-inactive">
            <div class="quest-left">
                <div class="quest-icon"><?php echo htmlspecialchars($q["quest_emoji"]); ?></div>
                <div class="quest-content">
                    <div class="item-title"><?php echo htmlspecialchars($q["quest_title"]); ?></div>
                    <div class="item-description"><?php echo htmlspecialchars($q["quest_description"]); ?></div>
                    <div class="quest-meta-row"><span class="quest-category"><?php echo htmlspecialchars($q["category"]); ?></span></div>
                    <div class="item-actions">
                        <form method="POST" action="admin.php">
                            <input type="hidden" name="activate_id" value="<?php echo $q['quest_id']; ?>">
                            <button type="submit" name="activateQuest" class="action-btn activate">Activate</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="quest-right">
                <div class="reward-badges top-right-info">
                    <div class="badge drop-badge">💧 <?php echo (int)$q["drop_reward"]; ?> <span class="badge-unit">Drops</span></div>
                    <div class="badge coin-badge">🪙 <?php echo (int)$q["eco_coin_reward"]; ?> <span class="badge-unit">EcoCoins</span></div>
                </div>
                <span class="date-text bottom-right-info"><?php echo $statusLabel; ?></span>
            </div>
        </div>
        <?php
    }
}

// Render HTML for Player Leaderboard
function renderLeaderboard(mysqli $con) {
    $sql = "SELECT u.username, up.total_quests_completed, up.player_tier 
            FROM user u 
            JOIN user_player up ON u.user_id = up.user_id 
            ORDER BY up.total_quests_completed DESC";
    $res = mysqli_query($con, $sql);
    
    echo "<div class='lb-list'>";
    $rank = 1;
    while($row = mysqli_fetch_assoc($res)) {
        $rankClass = ($rank <= 3) ? "rank-top-" . $rank : "";
        $tier = (int)($row['player_tier'] ?? 1);
        $userPfp = "images/pfp/" . $row['username'] . ".png";
        $displayPfp = (file_exists(__DIR__ . "/../" . $userPfp)) ? $userPfp : "images/pfp/default_profile_picture.png";

        echo "
        <div class='lb-row {$rankClass}'>
            <div class='lb-rank'>$rank</div>
            <img src='{$displayPfp}' class='lb-avatar tier-border-{$tier}'>
            <div class='lb-meta'>
                <div class='lb-name'>" . htmlspecialchars($row['username']) . "</div>
                <div class='lb-sub'>" . $row['total_quests_completed'] . " quests completed</div>
            </div>
        </div>";
        $rank++;
    }
    echo "</div>";
}

// Function to handle quests
function handleCreateQuest(mysqli $con) {
    // Quest activation
    if (isset($_POST['activateQuest'])) {
        $id = mysqli_real_escape_string($con, $_POST['activate_id']);
        mysqli_query($con, "UPDATE quest SET status = 'Active' WHERE quest_id = '$id'");
        header("Location: admin.php?quest_success=activated");
        exit();
    }

    // Quest deactivation
    if (isset($_POST['deactivateQuest'])) {
        $id = mysqli_real_escape_string($con, $_POST['deactivate_id']);
        $sql = "UPDATE quest SET status = 'Inactive' WHERE quest_id = '$id'";
        
        if (mysqli_query($con, $sql)) {
            header("Location: admin.php?quest_success=deactivated");
            exit();
        } else {
            die("Database Error: " . mysqli_error($con));
        }
    }

    // Quest deletion
    if (isset($_POST['confirmDelete'])) {
        $id = mysqli_real_escape_string($con, $_POST['delete_id']);
        mysqli_query($con, "DELETE FROM quest WHERE quest_id = '$id'");
        header("Location: admin.php?quest_success=deleted");
        exit();
    }

    // Quest editing/creation
    if (isset($_POST['submitQuest'])) {
        $quest_id  = mysqli_real_escape_string($con, $_POST['quest_id']);
        $emoji     = mysqli_real_escape_string($con, $_POST['quest_emoji']);
        $title     = mysqli_real_escape_string($con, $_POST['quest_title']); 
        $desc      = mysqli_real_escape_string($con, $_POST['quest_description']);
        $cat       = mysqli_real_escape_string($con, $_POST['category']);
        $drops     = (int)$_POST['drop_reward'];
        $coins     = (int)$_POST['eco_coin_reward'];
        $startDate = mysqli_real_escape_string($con, $_POST['quest_date']);

        // Determine if quest is active/inactive based on the scheduled date and today's date
        $today = date('Y-m-d');
        $status = ($startDate > $today) ? 'Inactive' : 'Active';

        if (!empty($quest_id)) {
            $sql = "UPDATE quest SET 
                quest_title='$title', quest_description='$desc', category='$cat', 
                drop_reward=$drops, eco_coin_reward=$coins, 
                date_created='$startDate', status='$status', quest_emoji='$emoji' 
                WHERE quest_id='$quest_id'";
        } else {
            $lastIdSql = "SELECT quest_id FROM quest ORDER BY quest_id DESC LIMIT 1";
            $res = mysqli_query($con, $lastIdSql);
            $new_id = ($row = mysqli_fetch_assoc($res)) ? "Q" . str_pad((int)substr($row['quest_id'], 1) + 1, 3, "0", STR_PAD_LEFT) : "Q001";

            $sql = "INSERT INTO quest (quest_id, quest_title, quest_description, quest_emoji, drop_reward, eco_coin_reward, category, created_by, status, date_created) 
                VALUES ('$new_id', '$title', '$desc', '$emoji', $drops, $coins, '$cat', 'USR002', '$status', '$startDate')";
        }

        if (mysqli_query($con, $sql)) {
            header("Location: admin.php?quest_success=true");
            exit();
        }
    }
}

// Render JS for dashboard functions
function renderDashboardScripts() {
    ?>
    <script>
        // Modal for editing quests
        function openEditModal(q) {
            document.getElementById('modalTitle').innerText = "Edit Quest";
            document.getElementById('edit_quest_id').value = q.quest_id;
            document.getElementById('form_title').value = q.quest_title;
            document.getElementById('form_desc').value = q.quest_description;
            document.getElementById('form_cat').value = q.category;
            document.getElementById('form_drops').value = q.drop_reward;
            document.getElementById('form_coins').value = q.eco_coin_reward;
            document.getElementById('form_date').value = q.date_created;
            document.getElementById('questModal').style.display = 'block';
        }

        // Modal for creating quests
        function openCreateModal() {
            document.getElementById('modalTitle').innerText = "Add New Quest";
            document.getElementById('edit_quest_id').value = "";
            document.getElementById('questModal').style.display = 'block';
        }

        // Modal for deactivating quests
        function openDeactivateModal(id, title) {
            document.getElementById('deactivate_quest_id').value = id;
            document.getElementById('deactivateQuestTitle').innerText = title;
            document.getElementById('deactivateModal').style.display = 'block';
        }

        // Modal for deleting quests
        function openDeleteModal(id, title) {
            const input = document.getElementById('delete_quest_id');
            const btn = document.querySelector('#deleteModal button[type="submit"]');
            
            if(input) {
                input.value = id;
                input.name = "delete_id";
            }
            if(btn) {
                btn.name = "confirmDelete";
            }
            
            document.getElementById('deleteQuestTitle').innerText = title;
            document.getElementById('deleteModal').style.display = 'block';
        }

        // Display emoji keypad during quest creation
        function toggleEmojiPicker() {
            const picker = document.getElementById('emojiPicker');
            picker.classList.toggle('hidden');
        }

        // Select an emoji during quest creation
        function selectEmoji(emoji) {
            document.getElementById('form_emoji').value = emoji;
            document.getElementById('emojiPicker').classList.add('hidden');
        }
    </script>
    <?php
}
?>