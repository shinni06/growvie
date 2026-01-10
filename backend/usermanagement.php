<?php

// Render JS for user management functions
function renderUserManagementScripts() {
    ?>
    <script>
        // Render JS for Tab Switching Logic in User Management
        function userTab(role) {
            document.querySelectorAll('#content5 .tab').forEach(btn => btn.classList.remove('active'));
            if (event && event.target) event.target.classList.add('active');

            const searchVal = document.getElementById('userSearchInput')?.value || '';
            window.location.href = `admin.php?role=${role}&search=${searchVal}`;
        }

        // Logic for search bar
        function handleUserSearch() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentRole = urlParams.get('role') || 'Player';
            
            const searchVal = document.getElementById('userSearchInput').value;
            window.location.href = `admin.php?role=${currentRole}&search=${searchVal}`;
        }   

        // Modal for deleting users
        function openUserDeleteModal(id, name) {
            const modal = document.getElementById('deleteModal');
            const inputId = document.getElementById('delete_quest_id'); // Reusing existing modal hidden input
            const title = document.getElementById('deleteQuestTitle');  // Reusing existing modal title
            const submitBtn = modal.querySelector('button[name="confirmDelete"]'); // Find the submit button

            if (!modal) return;

            if(inputId) {
                inputId.value = id;
                inputId.name = "user_id";
            }
            if(title) title.innerText = name;
            
            if(submitBtn) {
                submitBtn.name = "confirmDeleteUser"; 
            }
            
            modal.style.display = 'block';
        }
    </script>
    <?php
}

// Render content for users depending on the tab
function renderUserManagement($con, $role = 'Player', $search = '') {
    $search = mysqli_real_escape_string($con, $search);
    $role = mysqli_real_escape_string($con, $role);

    // Logic for player tab
    if ($role === 'Player') {
        $sql = "SELECT u.*, up.total_quests_completed, up.player_tier, 
                       up.tree_planted_irl, up.growvie_plants_planted,
                       up.player_status 
                FROM user u 
                LEFT JOIN user_player up ON u.user_id = up.user_id 
                WHERE u.role = 'Player' 
                AND (up.player_status != 'Deleted' OR up.player_status IS NULL)";
        
        if (!empty($search)) {
            $sql .= " AND (u.username LIKE '%$search%' OR u.name LIKE '%$search%')";
        }

        $res = mysqli_query($con, $sql);

        if (!$res || mysqli_num_rows($res) === 0) {
            echo "<div class='empty-state'>No player accounts found.</div>";
            return;
        }

        echo '<div class="list-container">';
        while ($u = mysqli_fetch_assoc($res)) {
            $isSuspended = (($u['player_status'] ?? 'Active') === 'Suspended');
            $suspendedClass = $isSuspended ? "suspended" : "";
            
            $tier = (int)($u['player_tier'] ?? 1);
            $tierBadgeClass = 'tier-' . $tier;
            $tierLabel = 'TIER ' . $tier;

            // Attempt to find user pfp from user ID, if cannot be found use default pfp
            $userPfp = "images/pfp/" . $u['username'] . ".png";
            $displayPfp = (file_exists(__DIR__ . "/../" . $userPfp)) ? $userPfp : "images/pfp/default_profile_picture.png";

            $username = htmlspecialchars($u['username']);
            $name = htmlspecialchars($u['name']);
            $userId = htmlspecialchars($u['user_id']);
            $email = htmlspecialchars($u['email']);
            $questsCompleted = (int)($u['total_quests_completed'] ?? 0);
            $irlTrees = (int)($u['tree_planted_irl'] ?? 0);
            $virtualPlants = (int)($u['growvie_plants_planted'] ?? 0);
            $dateJoined = date("d M Y", strtotime($u['date_joined']));

            // Display user card for each user
            echo "
            <div class='user-card $suspendedClass' id='card-$userId'>
                <div class='left-section'>
                    <img src='$displayPfp' alt='PFP' class='user-card-pfp tier-border-$tier'>
                    <div class='info'>
                        <div class='user-header'>
                            <h3 class='item-title'>$name <span>@$username</span></h3>
                            <span class='tier-badge $tierBadgeClass'>$tierLabel</span>
                        </div>
                        <div class='user-meta-info'>
                            <span class='meta-item'><strong>ID:</strong> $userId</span>
                            <span class='meta-item'><strong>Email:</strong> $email</span>
                        </div>
                        <div class='user-stats-row'>
                            <span class='stat'>🎯 $questsCompleted Quests</span>
                            <span class='stat'>🌳 $irlTrees IRL Trees</span>
                            <span class='stat'>🌱 $virtualPlants Plants</span>
                        </div>
                    </div>
                </div>

                <div class='right-section'>
                    " . ($isSuspended ? "<div class='status-tag suspended top-right-info'>Suspended</div>" : "<p class='date-text top-right-info'>Joined $dateJoined</p>") . "

                    <div class='item-actions bottom-right-info'>
                        <form method='POST' action='admin.php?role=Player'>
                            <input type='hidden' name='user_id' value='$userId'>
                            " . ($isSuspended ? "<button type='submit' name='suspendUser' class='action-btn activate'>Unsuspend</button>" : "<button type='submit' name='suspendUser' class='action-btn deactivate'>Suspend</button>") . "
                        </form>

                        <button type='button' class='action-btn delete' 
                            onclick=\"openUserDeleteModal('$userId', '" . addslashes($name) . "')\">
                            Delete
                        </button>
                    </div>
                </div>
            </div>";
        }
        echo '</div>';
    } 
    
    // Logic for partner tab
    elseif ($role === 'Partner') {
        $sql = "SELECT u.*, p.description, p.partner_status 
                FROM user u 
                LEFT JOIN partner p ON u.email = p.contact_email 
                WHERE u.role = 'Partner' AND p.partner_status != 'Deleted'";

        if (!empty($search)) {
            $sql .= " AND (u.name LIKE '%$search%' OR p.description LIKE '%$search%')";
        }

        $res = mysqli_query($con, $sql);

        if (!$res || mysqli_num_rows($res) === 0) {
            echo "<div class='empty-state'>No partner accounts found.</div>";
            return;
        }

        echo '<div class="partner-grid">';
        while ($p = mysqli_fetch_assoc($res)) {
            // Attempt to find user pfp from user ID, if cannot be found use default pfp
            $pfpPath = "images/pfp/" . $p['username'] . ".png";
            $displayPfp = (file_exists(__DIR__ . "/../" . $pfpPath)) ? $pfpPath : "images/pfp/default_profile_picture.png";
            
            // Attempt to get description for partner, if cannot be found print corresponding message
            $description = !empty($p['description']) ? $p['description'] : "No description available.";
            $status = !empty($p['partner_status']) ? $p['partner_status'] : "Active";
            ?>
            <div class="card-panel partner-card">
                <div class="partner-header-row">
                    <img src="<?php echo $displayPfp; ?>" alt="Avatar" class="partner-pfp">
                    
                    <div class="partner-info">
                        <h3 class="item-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                        <p class="p-username">@<?php echo htmlspecialchars($p['username']); ?></p>
                        <p class="p-email"><?php echo htmlspecialchars($p['email']); ?></p>
                    </div>
                    
                    <span class="status-tag <?php echo strtolower($status); ?>"><?php echo htmlspecialchars($status); ?></span>
                </div>

                <div class="partner-body">
                    <p class="item-description"><?php echo htmlspecialchars($description); ?></p>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }
}

// Function to handle suspension/deletion of player and adding new partner
function handleUserActions($con) {
    // Suspend user, change status to "Suspended"
    if (isset($_POST['suspendUser'])) {
        $id = mysqli_real_escape_string($con, $_POST['user_id']);
        $query = "UPDATE user_player SET player_status = IF(player_status='Active', 'Suspended', 'Active') WHERE user_id = '$id'";
        mysqli_query($con, $query);
        header("Location: admin.php?role=Player&action=status_updated");
        exit();
    }

    // Delete user, change status to "Deleted"
    if (isset($_POST['confirmDeleteUser'])) {
        $id = mysqli_real_escape_string($con, $_POST['user_id']);
        $query = "UPDATE user_player SET player_status = 'Deleted' WHERE user_id = '$id'";
        mysqli_query($con, $query);
        header("Location: admin.php?role=Player&action=user_deleted");
        exit();
    }

    // Add new partner
    if (isset($_POST['addNewPartner'])) {
        // Generate new user ID
        $lastSql = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
        $lastRes = mysqli_query($con, $lastSql);
        
        $newId = "USR001";
        if ($row = mysqli_fetch_assoc($lastRes)) {
            $num = (int)substr($row['user_id'], 3) + 1;
            $newId = "USR" . str_pad($num, 3, "0", STR_PAD_LEFT);
        }

        // Generate new partner ID
        $lastPartnerSql = "SELECT partner_id FROM partner ORDER BY partner_id DESC LIMIT 1";
        $lastPartnerRes = mysqli_query($con, $lastPartnerSql);
        
        $newPartnerId = "PO001";
        if ($pRow = mysqli_fetch_assoc($lastPartnerRes)) {
            $pNum = (int)substr($pRow['partner_id'], 2) + 1;
            $newPartnerId = "PO" . str_pad($pNum, 3, "0", STR_PAD_LEFT);
        }

        // Validate inputs
        $name = mysqli_real_escape_string($con, $_POST['partner_name']);
        $username = mysqli_real_escape_string($con, $_POST['partner_username']);
        $email = mysqli_real_escape_string($con, $_POST['partner_email']);
        $pass = mysqli_real_escape_string($con, $_POST['partner_password']); 
        $desc = mysqli_real_escape_string($con, $_POST['partner_desc']);
        $date = date('Y-m-d H:i:s');

        // Insert row into user table
        $sqlUser = "INSERT INTO user (user_id, username, password, email, name, role, date_joined) 
                    VALUES ('$newId', '$username', '$pass', '$email', '$name', 'Partner', '$date')";
        
        // Insert row into partner table
        $sqlPartner = "INSERT INTO partner (partner_id, organization_name, contact_email, description, partner_status) 
                       VALUES ('$newPartnerId', '$name', '$email', '$desc', 'Active')";

        if (mysqli_query($con, $sqlUser) && mysqli_query($con, $sqlPartner)) {
            header("Location: admin.php?role=Partner&action=partner_added");
            exit();
        } else {
            echo "<script>alert('Error adding partner: " . mysqli_error($con) . "'); window.history.back();</script>";
            exit();
        }
    }
}
?>