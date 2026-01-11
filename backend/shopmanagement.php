<?php
require_once __DIR__ . '/db.php';

// Render JS for Tab Switching Logic in Shop Management
function renderShopScripts() {
    ?>
    <script>
        function shopTab(category) {
            localStorage.setItem('activeTab', 4);
            window.location.href = "?shop_category=" + category;
        }

        // Modal for editing/creating shop items
        function openShopItemModal(mode, itemId = null, name = '', desc = '', price = '', category = '') {
            const modal = document.getElementById('shopItemModal');
            const title = document.getElementById('shopModalTitle');
            const btn = document.getElementById('shopSubmitBtn');
            const form = document.getElementById('shopForm');

            // Reset if image provided is invalid
            const errorMsg = document.getElementById('shop-image-error');
            if(errorMsg) errorMsg.classList.add('hidden');
            btn.disabled = false;

            const urlParams = new URLSearchParams(window.location.search);
            const currentCat = urlParams.get('shop_category') || 'seeds';
            const redirectInput = document.getElementById('shop_redirect_category');
            if(redirectInput) redirectInput.value = currentCat;

            // Create new shop item
            if (mode === 'add') {
                title.innerText = 'Add New Item';
                btn.innerText = 'Add Item';
                btn.name = 'addShopItem';
                form.reset();
                document.getElementById('edit_item_id').value = '';
                
                // Dynamic category for new shop item based on what tab is active
                const urlParams = new URLSearchParams(window.location.search);
                const currentCat = urlParams.get('shop_category');
                const catMap = {
                    'seeds': 'Plant Seeds',
                    'powerups': 'Power Ups',
                    'iap': 'In App Purchases'
                };
                if (currentCat && catMap[currentCat]) {
                    document.getElementById('shop_item_category').value = catMap[currentCat];
                }

            } else {
                // Edit current shop item
                title.innerText = 'Edit Item';
                btn.innerText = 'Save Changes';
                btn.name = 'editShopItem';
                
                document.getElementById('edit_item_id').value = itemId;
                document.getElementById('shop_item_name').value = name;
                document.getElementById('shop_item_desc').value = desc;
                document.getElementById('shop_item_price').value = price;
                
                // Ensure category matches option values
                // Passed category might be 'Plant Seeds', etc. directly from PHP
                document.getElementById('shop_item_category').value = category;
            }

            modal.style.display = 'block';
        }

        // Close shop item modal
        function closeShopItemModal() {
            document.getElementById('shopItemModal').style.display = 'none';
        }

        // Check if image uploaded for for new shop item is valid (PNG)
        function validateShopImage(input) {
            const errorMsg = document.getElementById('shop-image-error');
            const btn = document.getElementById('shopSubmitBtn');
            const file = input.files[0];
            
            if (file) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (ext !== 'png') {
                    errorMsg.classList.remove('hidden');
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                } else {
                    errorMsg.classList.add('hidden');
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                }
            } else {
                errorMsg.classList.add('hidden');
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
            }
        }
    </script>
    <?php
}

// Render content for shop items depending on the tab
function renderShopManagement($con, $currentTab, $searchQuery = '') {
    $categoryMap = [
        'seeds'    => 'Plant Seeds',
        'powerups' => 'Power Ups',
        'iap'      => 'In App Purchases'
    ];

    $dbCategory = $categoryMap[$currentTab] ?? 'Plant Seeds';

    $sql = "SELECT * FROM shop_item 
            WHERE item_category = ? 
            AND (item_name LIKE ? OR item_desc LIKE ?)";
    
    $stmt = $con->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("sss", $dbCategory, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<div class="shop-grid">';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $itemId = htmlspecialchars($row['item_id']);
            $name = htmlspecialchars($row['item_name']);
            $desc = htmlspecialchars($row['item_desc']);
            $price = $row['item_price'];
            
            $baseDir = __DIR__ . "/../assets/shop_items/"; // File system path for checking
            $webDir = "assets/shop_items/"; 
            $targetImage = $itemId . ".png";
            
            // Check if specific image for that Item ID exists
            if (!file_exists($baseDir . $targetImage)) {
                // Default images for each shop category
                if ($dbCategory === 'Plant Seeds') {
                    $targetImage = "plant_seeds.png";
                } elseif ($dbCategory === 'Power Ups') {
                    $targetImage = "power_ups.png";
                } elseif ($dbCategory === 'In App Purchases') {
                    $targetImage = "in_app_purchases.png";
                } else {
                    $targetImage = "plant_seeds.png";
                }
            }
            
            $imgSrc = $webDir . $targetImage; 

            // Change price display from coins to RMX.00 for In-App Purchases
            if ($dbCategory == 'In App Purchases') {
                $displayPrice = 'RM ' . number_format($price, 2); 
            } else {
                $icon = '🪙';
                $displayPrice = $icon . ' ' . number_format($price);
            }

            $jsName = addslashes($row['item_name']);
            $jsDesc = addslashes($row['item_desc']);
            $jsCat  = addslashes($dbCategory);

            echo "
            <div class='shop-card' id='card-{$itemId}'>
                <img src='{$imgSrc}' alt='{$name}'>
                <h3 class='item-title'>{$name}</h3>
                <p class='item-description'>{$desc}</p>
                <div class='shop-bottom-row'>
                    <span class='price'>{$displayPrice}</span>
                    <div class='item-actions'>
                        <button class='action-btn edit' id='shop-management-btn' onclick=\"openShopItemModal('edit', '{$itemId}', '{$jsName}', '{$jsDesc}', '{$price}', '{$jsCat}')\">Edit</button>
                        <button class='action-btn delete' id='shop-management-btn' onclick=\"openShopDeleteModal('{$itemId}', '{$jsName}')\">Delete</button>
                    </div>
                </div>
            </div>";
        }
    } 
    
    echo '</div>';
}

// Handle creating, editing/deleting shop items
function handleShopActions($con) {
    if (isset($_POST['addShopItem']) || isset($_POST['editShopItem'])) {
        $name = mysqli_real_escape_string($con, $_POST['item_name']);
        $desc = mysqli_real_escape_string($con, $_POST['item_desc']);
        $price = (int)$_POST['item_price'];
        $category = mysqli_real_escape_string($con, $_POST['item_category']);
        $redirectCat = $_POST['redirect_category'] ?? 'seeds';

        $targetDir = __DIR__ . '/../assets/shop_items/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Validate uploaded image for new shop item (PNG)
        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'png') {
                echo "<script>alert('File type not supported. Please upload a PNG file.'); window.history.back();</script>";
                exit();
            }
        }

        // Creation of new shop item
        if (isset($_POST['addShopItem'])) {
            // Generate ID for new shop item
            $sqlId = "SELECT item_id FROM shop_item ORDER BY item_id DESC LIMIT 1";
            $resId = mysqli_query($con, $sqlId);
            $newId = "ITM001";
            if ($row = mysqli_fetch_assoc($resId)) {
                $num = (int)substr($row['item_id'], 3) + 1;
                $newId = "ITM" . str_pad($num, 3, "0", STR_PAD_LEFT);
            }

            // Handle image upload (PNG)
            if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
                move_uploaded_file($_FILES['item_image']['tmp_name'], $targetDir . $newId . ".png");
            }

            $sql = "INSERT INTO shop_item (item_id, item_name, item_desc, item_price, item_category, item_availability) 
                    VALUES ('$newId', '$name', '$desc', '$price', '$category', 1)";
            
            if (mysqli_query($con, $sql)) {
                header("Location: admin.php?action=shop_item_added&shop_category=$redirectCat");
                exit();
            }

        } elseif (isset($_POST['editShopItem'])) {
            $id = mysqli_real_escape_string($con, $_POST['item_id']);
            
            // Handle image update (PNG Only)
            if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION));
                if ($ext === 'png') {
                    $targetFile = $targetDir . $id . ".png";
                    if (file_exists($targetFile)) unlink($targetFile);
                    move_uploaded_file($_FILES['item_image']['tmp_name'], $targetFile);
                }
            }

            $sql = "UPDATE shop_item SET item_name = '$name', item_desc = '$desc', item_price = '$price', item_category = '$category' WHERE item_id = '$id'";

            if (mysqli_query($con, $sql)) {
                header("Location: admin.php?action=shop_item_updated&shop_category=$redirectCat");
                exit();
            }
        }
    } elseif (isset($_POST['deleteShopItem'])) {
        // Deletion of shop item
        $id = mysqli_real_escape_string($con, $_POST['item_id']);
        $redirectCat = $_POST['redirect_category'] ?? 'seeds';
        
        // Delete the image file
        $target_file = __DIR__ . '/../assets/shop_items/' . $id . '.png';
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        $sql = "DELETE FROM shop_item WHERE item_id = '$id'";
        
        if (mysqli_query($con, $sql)) {
            header("Location: admin.php?action=shop_item_deleted&shop_category=$redirectCat");
            exit();
        } else {
            echo "<script>alert('Error deleting item: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>
