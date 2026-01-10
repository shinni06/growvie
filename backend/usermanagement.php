<?php
// backend/usermanagement.php

/**
 * Renders the JavaScript required for User Management interactions.
 */
function renderUserManagementScripts() {
    ?>
    <script>
        /**
         * Switches between User and Partner sub-tabs.
         */
        function userTab(role) {
            // UI Update: Toggle 'active' class
            document.querySelectorAll('#content5 .tab').forEach(btn => btn.classList.remove('active'));
            if (event && event.target) event.target.classList.add('active');

            // Redirect with current search value to maintain context
            const searchVal = document.getElementById('userSearchInput')?.value || '';
            window.location.href = `final.php?role=${role}&search=${searchVal}`;
        }

        /**
         * Triggered by the search bar input.
         */
        function handleUserSearch() {
            // Get current role from URL or default to 'Player'
            const urlParams = new URLSearchParams(window.location.search);
            const currentRole = urlParams.get('role') || 'Player';
            
            const searchVal = document.getElementById('userSearchInput').value;
            window.location.href = `final.php?role=${currentRole}&search=${searchVal}`;
        }   

        /**
         * Opens the Delete Confirmation Modal.
         * Only used for Players now.
         */
        function openUserDeleteModal(id, name) {
            const modal = document.getElementById('deleteModal');
            const inputId = document.getElementById('delete_quest_id'); // Reusing existing modal hidden input
            const title = document.getElementById('deleteQuestTitle');  // Reusing existing modal title
            const submitBtn = modal.querySelector('button[name="confirmDelete"]'); // Find the submit button

            if (!modal) return;

            // 1. Inject Data
            if(inputId) {
                inputId.value = id;
                inputId.name = "user_id"; // Change name so PHP 'handleUserActions' catches it
            }
            if(title) title.innerText = name;
            
            // 2. Switch Button Action
            if(submitBtn) {
                submitBtn.name = "confirmDeleteUser"; 
            }
            
            modal.style.display = 'block';
        }
    </script>
    <?php
}

/**
 * Renders the content based on the active role.
 */
function renderUserManagement($con, $role = 'Player', $search = '') {
    $search = mysqli_real_escape_string($con, $search);
    $role = mysqli_real_escape_string($con, $role);

    // --- PLAYER TAB LOGIC ---
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

            // Image Fallback
            $userPfp = "images/pfp/" . $u['username'] . ".png";
            $displayPfp = (file_exists(__DIR__ . "/../" . $userPfp)) ? $userPfp : "images/pfp/default_profile_picture.png";
            
            // Data vars
            $username = htmlspecialchars($u['username']);
            $name = htmlspecialchars($u['name']);
            $userId = htmlspecialchars($u['user_id']);
            $email = htmlspecialchars($u['email']);
            $questsCompleted = (int)($u['total_quests_completed'] ?? 0);
            $irlTrees = (int)($u['tree_planted_irl'] ?? 0);
            $virtualPlants = (int)($u['growvie_plants_planted'] ?? 0);
            $dateJoined = date("d M Y", strtotime($u['date_joined']));

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
                        <form method='POST' action='final.php?role=Player'>
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
    
    // --- PARTNER TAB LOGIC (UPDATED) ---
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

        echo '<div class="partner-grid">'; // Grid Container start
        while ($p = mysqli_fetch_assoc($res)) {
            // Profile Picture Logic
            $pfpPath = "images/pfp/" . $p['username'] . ".png";
            $displayPfp = (file_exists(__DIR__ . "/../" . $pfpPath)) ? $pfpPath : "images/pfp/default_profile_picture.png";
            
            // Description Fallback
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
        echo '</div>'; // Grid Container end
    }
}

/**
 * Handles the POST logic for Suspending and Deleting.
 * Only applies to Players.
 */
function handleUserActions($con) {
    if (isset($_POST['suspendUser'])) {
        $id = mysqli_real_escape_string($con, $_POST['user_id']);
        $query = "UPDATE user_player SET player_status = IF(player_status='Active', 'Suspended', 'Active') WHERE user_id = '$id'";
        mysqli_query($con, $query);
        header("Location: final.php?role=Player&action=status_updated");
        exit();
    }

    if (isset($_POST['confirmDeleteUser'])) {
        $id = mysqli_real_escape_string($con, $_POST['user_id']);
        $query = "UPDATE user_player SET player_status = 'Deleted' WHERE user_id = '$id'";
        mysqli_query($con, $query);
        header("Location: final.php?role=Player&action=user_deleted");
        exit();
    }

    if (isset($_POST['togglePartnerStatus'])) {
        $id = mysqli_real_escape_string($con, $_POST['user_id']);
        
        // Update partner table by joining with user table on email
        $query = "UPDATE partner p
                  INNER JOIN user u ON p.contact_email = u.email
                  SET p.partner_status = IF(p.partner_status='Active', 'Inactive', 'Active')
                  WHERE u.user_id = '$id'";
                  
        mysqli_query($con, $query);
        
        header("Location: final.php?role=Partner&action=partner_updated");
        exit();
    }

    if (isset($_POST['addNewPartner'])) {
        // 1. Generate new User ID (e.g., USR005)
        $lastSql = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1";
        $lastRes = mysqli_query($con, $lastSql);
        
        $newId = "USR001"; // Default if table is empty
        if ($row = mysqli_fetch_assoc($lastRes)) {
            // Extract number, increment, and pad back to USR00X
            $num = (int)substr($row['user_id'], 3) + 1;
            $newId = "USR" . str_pad($num, 3, "0", STR_PAD_LEFT);
        }

        // 1.1 Generate new Partner ID (e.g., PO006)
        $lastPartnerSql = "SELECT partner_id FROM partner ORDER BY partner_id DESC LIMIT 1";
        $lastPartnerRes = mysqli_query($con, $lastPartnerSql);
        
        $newPartnerId = "PO001"; // Default if table is empty
        if ($pRow = mysqli_fetch_assoc($lastPartnerRes)) {
            // Extract number, increment, and pad back to PO00X
            $pNum = (int)substr($pRow['partner_id'], 2) + 1;
            $newPartnerId = "PO" . str_pad($pNum, 3, "0", STR_PAD_LEFT);
        }

        // 2. Sanitize Inputs
        $name = mysqli_real_escape_string($con, $_POST['partner_name']);
        $username = mysqli_real_escape_string($con, $_POST['partner_username']);
        $email = mysqli_real_escape_string($con, $_POST['partner_email']);
        // Store password as plain text (school project requirement)
        $pass = mysqli_real_escape_string($con, $_POST['partner_password']); 
        $desc = mysqli_real_escape_string($con, $_POST['partner_desc']);
        $date = date('Y-m-d H:i:s');

        // 3. Insert into USER table first
        $sqlUser = "INSERT INTO user (user_id, username, password, email, name, role, date_joined) 
                    VALUES ('$newId', '$username', '$pass', '$email', '$name', 'Partner', '$date')";
        
        // 4. Insert into PARTNER table
        // Correctly including partner_id (POxxx) and organization_name
        $sqlPartner = "INSERT INTO partner (partner_id, organization_name, contact_email, description, partner_status) 
                       VALUES ('$newPartnerId', '$name', '$email', '$desc', 'Active')";

        if (mysqli_query($con, $sqlUser) && mysqli_query($con, $sqlPartner)) {
            header("Location: final.php?role=Partner&action=partner_added");
            exit();
        } else {
            // Simple error handling
            echo "<script>alert('Error adding partner: " . mysqli_error($con) . "'); window.history.back();</script>";
            exit();
        }
    }
}
?>