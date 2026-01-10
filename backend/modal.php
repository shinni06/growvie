<?php

// Render modal for quest editing/creation
function renderQuestModal() {
    ?>
    <div id="questModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('questModal').style.display='none'">&times;</span>
            <h3 id="modalTitle">Add New Quest</h3>
            <form method="POST" action="admin.php">
                <input type="hidden" name="quest_id" id="edit_quest_id">

                <div class="modal-form-row modal-spacer-small">
                    <div class="emoji-selector-inline">
                        <input type="text" name="quest_emoji" id="form_emoji" value="✅" readonly required class="emoji-display-minimal">
                        <button type="button" class="action-btn gray" onclick="toggleEmojiPicker()">Change Icon</button>
                    </div>
                </div>
                
                <div id="emojiPicker" class="emoji-grid hidden">
                    <span onclick="selectEmoji('✅')">✅</span>
                    <span onclick="selectEmoji('🏷️')">🏷️</span>
                    <span onclick="selectEmoji('📌')">📌</span>
                    <span onclick="selectEmoji('🌍')">🌍</span>
                    <span onclick="selectEmoji('❤️')">❤️</span>
                    <span onclick="selectEmoji('🌱')">🌱</span>
                    <span onclick="selectEmoji('🌊')">🌊</span>
                    <span onclick="selectEmoji('⚡')">⚡</span>
                    <span onclick="selectEmoji('🥤')">🥤</span>
                    <span onclick="selectEmoji('🛍️')">🛍️</span>
                    <span onclick="selectEmoji('🍱')">🍱</span>
                    <span onclick="selectEmoji('🥗')">🥗</span>
                    <span onclick="selectEmoji('🥕')">🥕</span>
                    <span onclick="selectEmoji('🏡')">🏡</span>
                    <span onclick="selectEmoji('🚲')">🚲</span>
                    <span onclick="selectEmoji('🚶')">🚶</span>
                    <span onclick="selectEmoji('👕')">👕</span>
                    <span onclick="selectEmoji('🛒')">🛒</span>
                    <span onclick="selectEmoji('🚮')">🚮</span>
                    <span onclick="selectEmoji('♻️')">♻️</span>                    
                </div>

                <label>Quest Title</label>
                <input type="text" name="quest_title" id="form_title" required>

                <label>Description</label><textarea name="quest_description" id="form_desc" required></textarea>
                <label>Category</label>
                <select name="category" id="form_cat">
                    <option value="Community">Waste Reduction</option>
                    <option value="Personal">Energy & Transport</option>
                    <option value="Eco">Sustainable Living</option>
                    <option value="Waste Reduction">Community & Nature</option>
                </select>
                <div class="modal-form-row gap-10">
                    <div class="flex-1"><label>Drops</label><input type="number" name="drop_reward" id="form_drops"></div>
                    <div class="flex-1"><label>EcoCoins</label><input type="number" name="eco_coin_reward" id="form_coins"></div>
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

// Render modal for quest deactivation
function renderDeactivateModal() {
    ?>
    <div id="deactivateModal" class="modal">
        <div class="modal-content modal-content-small">
            <h3 class="modal-title-warning">Confirm Deactivation</h3>
            <p class="modal-subtext">
                Are you sure you want to deactivate <strong id="deactivateQuestTitle"></strong>? <br>
                It will be moved to the inactive list and hidden from players.
            </p>
            <form method="POST" action="admin.php">
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

// Render modal for quest deletion
function renderDeleteModal() {
    ?>
    <div id="deleteModal" class="modal">
        <div class="modal-content modal-content-small">
            <h3 class="modal-title-danger">Confirm Deletion</h3>
            <p class="modal-subtext">
                Are you sure you want to delete <strong id="deleteQuestTitle"></strong>? This cannot be undone.
            </p>
            <form method="POST" action="admin.php">
                <input type="hidden" name="delete_id" id="delete_quest_id">
                <div class="modal-footer">
                    <button type="button" class="action-btn gray" onclick="document.getElementById('deleteModal').style.display='none'">Cancel</button>
                    <button type="submit" name="confirmDelete" class="action-btn cfmdelete">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

// Render modal for handling shop items
function renderShopModals() {
    ?>
    <!-- Render modal for editing/creating shop item -->
    <div id="shopItemModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeShopItemModal()">&times;</span>
            <h3 id="shopModalTitle">Add New Item</h3>
            
            <form id="shopForm" method="POST" action="admin.php" enctype="multipart/form-data">
                <input type="hidden" name="item_id" id="edit_item_id">
                <input type="hidden" name="redirect_category" id="shop_redirect_category">
                
                <label>Item Name</label>
                <input type="text" name="item_name" id="shop_item_name" required>
                
                <label>Description</label>
                <textarea name="item_desc" id="shop_item_desc" rows="3" required></textarea>
                
                <label>Category</label>
                <select name="item_category" id="shop_item_category">
                    <option value="Plant Seeds">Plant Seeds</option>
                    <option value="Power Ups">Power Ups</option>
                    <option value="In App Purchases">In App Purchases</option>
                </select>
                
                <label>Price</label>
                <input type="number" name="item_price" id="shop_item_price" required>
                
                <label>Image (Upload)</label>
                <input type="file" name="item_image" id="shop_item_image" accept="image/png" onchange="validateShopImage(this)">
                <p id="shop-image-error" class="form-error hidden">File type not supported. Please upload a PNG file.</p>

                <div class="modal-footer">
                    <button type="button" class="action-btn gray" onclick="closeShopItemModal()">Cancel</button>
                    <button type="submit" name="addShopItem" id="shopSubmitBtn" class="action-btn green">Add Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Render modal for confirming deletion of shop item -->
    <div id="deleteShopModal" class="modal">
        <div class="modal-content modal-content-small">
            <h3 class="modal-title-danger">Delete Item</h3>
            <p class="modal-subtext">
                Are you sure you want to delete <strong id="del_shop_item_name"></strong>? This cannot be undone.
            </p>
            <form method="POST" action="admin.php">
                <input type="hidden" name="item_id" id="del_shop_item_id">
                <input type="hidden" name="redirect_category" id="del_redirect_category">
                <div class="modal-footer">
                    <button type="button" class="action-btn gray" onclick="document.getElementById('deleteShopModal').style.display='none'">Cancel</button>
                    <button type="submit" name="deleteShopItem" class="action-btn cfmdelete">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Render modal for deletion of shop item
        function openShopDeleteModal(id, name) {
            document.getElementById('del_shop_item_id').value = id;
            document.getElementById('del_shop_item_name').innerText = name;
            
            // Redirect the user back to last used tab
            const urlParams = new URLSearchParams(window.location.search);
            document.getElementById('del_redirect_category').value = urlParams.get('shop_category') || 'seeds';

            document.getElementById('deleteShopModal').style.display = 'block';
        }
    </script>
    <?php
}

// Render modal for adding new partner organization
function renderAddPartnerModal() {
    ?>
    <div id="addPartnerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('addPartnerModal').style.display='none'">&times;</span>
            <h3>Add New Partner Organization</h3>
            
            <form method="POST" action="admin.php">
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

// Render general success modal for completed actions
function renderSuccessModal() {
    ?>
    <div id="successModal" class="modal">
        <div class="modal-content modal-content-small">
            <h3>Success!</h3>
            <p class="modal-subtext">Your operation was completed successfully.</p>
            <div class="modal-footer">
              <button class="action-btn green" onclick="document.getElementById('successModal').style.display='none'">OK</button>
            </div>
        </div>
    </div>
    <?php
}

?>