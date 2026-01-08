<?php
// backend/modal.php

/**
 * Renders the modal for creating and editing quests.
 */
function renderQuestModal() {
    ?>
    <div id="questModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('questModal').style.display='none'">&times;</span>
            <h3 id="modalTitle">Add New Quest</h3>
            <form method="POST" action="final.php">
                <input type="hidden" name="quest_id" id="edit_quest_id">
                <div class="modal-form-row modal-spacer-small" style="align-items: flex-end;">
                <label>Quest Icon</label>
                <div class="emoji-selector-inline">
                    <input type="text" name="quest_emoji" id="form_emoji" value="✅" readonly required class="emoji-display-minimal">
                    
                    <button type="button" class="action-btn gray" onclick="toggleEmojiPicker()">Change Icon</button>
                </div>
                </div>
                
                <div id="emojiPicker" class="emoji-grid hidden">
                    <span onclick="selectEmoji('✅')">✅</span>
                    <span onclick="selectEmoji('🏷️')">🏷️</span>
                    <span onclick="selectEmoji('🥤')">🥤</span>
                    <span onclick="selectEmoji('🛍️')">🛍️</span>
                    <span onclick="selectEmoji('🍱')">🍱</span>
                    <span onclick="selectEmoji('🚲')">🚲</span>
                    <span onclick="selectEmoji('🥕')">🥕</span>
                    <span onclick="selectEmoji('🏡')">🏡</span>
                    <span onclick="selectEmoji('📄')">📄</span>
                    <span onclick="selectEmoji('🔖')">🔖</span>
                    <span onclick="selectEmoji('♻️')">♻️</span>
                    <span onclick="selectEmoji('🌱')">🌱</span>
                    <span onclick="selectEmoji('🌳')">🌳</span>
                    <span onclick="selectEmoji('🌊')">🌊</span>
                    <span onclick="selectEmoji('☀️')">☀️</span>
                    <span onclick="selectEmoji('⚡')">⚡</span>
                    <span onclick="selectEmoji('💡')">💡</span>
                    <span onclick="selectEmoji('🧭')">🧭</span>
                    <span onclick="selectEmoji('📌')">📌</span>
                    <span onclick="selectEmoji('🌍')">🌍</span>
                </div>
                <label>Quest Title</label><input type="text" name="quest_title" id="form_title" required>
                <label>Description</label><textarea name="quest_description" id="form_desc" required></textarea>
                <label>Category</label>
                <select name="category" id="form_cat">
                    <option value="Community">Community</option>
                    <option value="Personal">Personal</option>
                    <option value="Eco">Eco</option>
                    <option value="Waste Reduction">Waste Reduction</option>
                </select>
                <div style="display:flex; gap:10px;">
                    <div><label>Drops</label><input type="number" name="drop_reward" id="form_drops"></div>
                    <div><label>EcoCoins</label><input type="number" name="eco_coin_reward" id="form_coins"></div>
                </div>
                <label>Activation Date</label><input type="date" name="quest_date" id="form_date" required>
                <div class="modal-footer">
                    <button type="submit" name="submitQuest" id="submitBtn" class="action-btn green">Save Quest</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

function renderDeactivateModal() {
    ?>
    <div id="deactivateModal" class="modal">
        <div class="modal-content modal-content-small">
            <h3 style="color: #92400e;">Confirm Deactivation</h3>
            <p class="modal-subtext">
                Are you sure you want to deactivate <strong id="deactivateQuestTitle"></strong>? <br>
                It will be moved to the inactive list and hidden from players.
            </p>
            <form method="POST" action="final.php">
                <input type="hidden" name="deactivate_id" id="deactivate_quest_id">
                <div class="modal-footer">
                    <button type="button" class="action-btn gray" onclick="document.getElementById('deactivateModal').style.display='none'">Cancel</button>
                    <button type="submit" name="deactivateQuest" class="action-btn red">Confirm Deactivation</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

function renderDeleteModal() {
    ?>
    <div id="deleteModal" class="modal">
        <div class="modal-content" style="width: 400px;">
            <h3 style="color: #d9534f;">Confirm Deletion</h3>
            <p class="modal-subtext">
                Are you sure you want to delete <strong id="deleteQuestTitle"></strong>? This cannot be undone.
            </p>
            <form method="POST" action="final.php">
                <input type="hidden" name="delete_id" id="delete_quest_id">
                <div class="modal-footer">
                    <button type="button" class="action-btn gray" onclick="document.getElementById('deleteModal').style.display='none'">Cancel</button>
                    <button type="submit" name="confirmDelete" class="action-btn red">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Renders the global success modal for all actions.
 */
function renderSuccessModal() {
    ?>
    <div id="successModal" class="modal">
        <div class="modal-content" style="width: 400px;">
            <h3>Success!</h3>
            <p>Your operation was completed successfully.</p>
            <div class="modal-footer">
              <button class="action-btn green" onclick="document.getElementById('successModal').style.display='none'">OK</button>
            </div>
        </div>
    </div>
    <?php
}

function renderAddPartnerModal() {
    ?>
    <div id="addPartnerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('addPartnerModal').style.display='none'">&times;</span>
            <h3>Add New Partner Organization</h3>
            
            <form method="POST" action="final.php">
                <label>Organization Name</label>
                <input type="text" name="partner_name" required placeholder="e.g. Green Earth NGO">

                <label>Username</label>
                <input type="text" name="partner_username" required placeholder="e.g. green_earth">

                <label>Email Address</label>
                <input type="email" name="partner_email" required placeholder="contact@example.com">

                <label>Password</label>
                <input type="password" name="partner_password" required placeholder="Set a temporary password">

                <label>Description</label>
                <textarea name="partner_desc" rows="3" placeholder="Briefly describe the organization..."></textarea>

                <div class="modal-footer">
                    <button type="button" class="action-btn gray" onclick="document.getElementById('addPartnerModal').style.display='none'">Cancel</button>
                    <button type="submit" name="addNewPartner" class="action-btn green">Add Partner</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openAddPartnerModal() {
            document.getElementById('addPartnerModal').style.display = 'block';
        }
    </script>
    <?php
}

?>