<?php
require_once __DIR__ . '/db.php';

/**
 * Renders the JavaScript required for Shop Management interactions.
 */
function renderShopScripts() {
    ?>
    <script>
        // Tab Switcher Logic
        function shopTab(category) {
            localStorage.setItem('activeTab', 4);
            window.location.href = "?shop_category=" + category;
        }

        // Search Logic
        function handleShopSearch() {
            const query = document.getElementById('shopSearchInput').value;
            const urlParams = new URLSearchParams(window.location.search);
            const currentCat = urlParams.get('shop_category') || 'seeds';
            
            localStorage.setItem('activeTab', 4);
            window.location.href = `?shop_category=${currentCat}&search=${encodeURIComponent(query)}`;
        }

        // Modal Logic
        function openShopItemModal(mode, itemId = null, name = '', desc = '', price = '', category = '') {
            const modal = document.getElementById('shopItemModal');
            const title = document.getElementById('shopModalTitle');
            const btn = document.getElementById('shopSubmitBtn');
            const form = document.getElementById('shopForm');

            // Set Redirect Category
            const urlParams = new URLSearchParams(window.location.search);
            const currentCat = urlParams.get('shop_category') || 'seeds';
            const redirectInput = document.getElementById('shop_redirect_category');
            if(redirectInput) redirectInput.value = currentCat;

            if (mode === 'add') {
                title.innerText = 'Add New Item';
                btn.innerText = 'Add Item';
                btn.name = 'addShopItem';
                form.reset();
                document.getElementById('edit_item_id').value = '';
                
                // Pre-select category based on current tab if possible
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
                // Edit Mode
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

        function closeShopItemModal() {
            document.getElementById('shopItemModal').style.display = 'none';
        }
    </script>
    <?php
}

/**
 * Renders the Grid of Shop Items
 * UPDATED: Uses .grid/.card layout and implements image fallback logic
 */
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

    echo '<div class="grid">';

    // 2. Render Items
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $itemId = htmlspecialchars($row['item_id']);
            $name = htmlspecialchars($row['item_name']);
            $desc = htmlspecialchars($row['item_desc']);
            $price = $row['item_price'];
            
            // Image Logic with Fallback
            $baseDir = __DIR__ . "/../assets/shop_items/"; // File system path for checking
            $webDir = "assets/shop_items/";                // Web path for <img> src
            
            // Default attempt: [ItemID].png
            $targetImage = $itemId . ".png";
            
            // Check if exists
            if (!file_exists($baseDir . $targetImage)) {
                // Fallback based on category
                if ($dbCategory === 'Plant Seeds') {
                    $targetImage = "plant_seeds.png";
                } elseif ($dbCategory === 'Power Ups') {
                    $targetImage = "power_ups.png";
                } elseif ($dbCategory === 'In App Purchases') {
                    $targetImage = "in_app_purchases.png";
                } else {
                    $targetImage = "plant_seeds.png"; // Final safety net
                }
            }

            $imgSrc = $webDir . $targetImage;

            // Price formatting
            if ($dbCategory == 'In App Purchases') {
                $displayPrice = 'RM ' . number_format($price / 100, 2); // Assuming price is in cents for IAP
            } else {
                $icon = ($dbCategory == 'Power Ups') ? '💎' : '🪙';
                $displayPrice = $icon . ' ' . number_format($price);
            }

            // Escape for JS params
            $jsName = addslashes($row['item_name']);
            $jsDesc = addslashes($row['item_desc']);
            $jsCat  = addslashes($dbCategory);

            echo "
            <div class='card' id='card-{$itemId}'>
                <img src='{$imgSrc}' alt='{$name}'>
                <h3>{$name}</h3>
                <p>{$desc}</p>
                <div class='bottom-row'>
                    <span class='price'>{$displayPrice}</span>
                    <div class='review-actions';'>
                        <button class='action-btn edit' id='shop-management-btn' onclick=\"openShopItemModal('edit', '{$itemId}', '{$jsName}', '{$jsDesc}', '{$price}', '{$jsCat}')\">Edit</button>
                        <button class='action-btn delete' id='shop-management-btn' onclick=\"openShopDeleteModal('{$itemId}', '{$jsName}')\">Delete</button>
                    </div>
                </div>
            </div>";
        }
    } 
    
    echo '</div>'; // End .grid
}

/**
 * UPDATED: renderShopSection to use dynamic item images + .png
 */
function renderShopSection($con) {
    // Fetch all items
    $sql = "SELECT * FROM shop_item ORDER BY item_category, item_name";
    $result = mysqli_query($con, $sql);

    $itemsByCategory = [];
    $categories = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cat = $row['item_category'];
            if (!in_array($cat, $categories)) {
                $categories[] = $cat;
            }
            $itemsByCategory[$cat][] = $row;
        }
    }
    
    // (Removed the hardcoded $images array since we use dynamic item images now)
    ?>
    <div class="shop-management-container">
        <h1>Shop Management</h1>
        <p class="subtitle">Manage shop items, inventory, and pricing.</p>

        <div class="tabs">
            <?php 
            $first = true;
            foreach ($categories as $cat) {
                $activeClass = $first ? 'active' : '';
                $catId = preg_replace('/[^a-zA-Z0-9]/', '', $cat);
                echo "<button class='tab $activeClass' onclick=\"openShopTab('$catId', event)'>$cat</button>";
                $first = false;
            }
            ?>
        </div>

        <div class="shop-content-wrapper">
            <?php 
            $first = true;
            foreach ($categories as $cat) {
                $catId = preg_replace('/[^a-zA-Z0-9]/', '', $cat);
                $hiddenClass = $first ? '' : 'hidden';
                $first = false;
                ?>
                <div id="shop-cat-<?php echo $catId; ?>" class="shop-category-content <?php echo $hiddenClass; ?>">
                    <div class="grid">
                        <div class="card add-card" onclick="openAddShopItemModal('<?php echo htmlspecialchars($cat); ?>')">
                            <div class="add-icon-circle">
                                <span class="plus">+</span>
                            </div>
                            <p>Add new<br><?php echo htmlspecialchars($cat); ?></p>
                        </div>

                        <?php foreach ($itemsByCategory[$cat] as $item): ?>
                            <?php 
                                // UPDATED: Use specific item image + .png
                                $img = "assets/shop_items/" . htmlspecialchars($item['item_id']) . ".png";
                            ?>
                            <div class="card item-card">
                                <div class="card-image-container">
                                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                                </div>
                                <div class="card-details">
                                    <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                                    <p class="desc"><?php echo htmlspecialchars($item['item_desc']); ?></p>
                                    <div class="bottom-row">
                                        <span class="price">
                                            <?php if ($cat == 'In App Purchases'): ?>
                                                RM <?php echo number_format($item['item_price'] / 100, 2); ?>
                                            <?php else: ?>
                                                🪙 <?php echo number_format($item['item_price']); ?>
                                            <?php endif; ?>
                                        </span>
                                        <button class="edit-btn">Edit</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function openShopTab(catId, event) {
            document.querySelectorAll('.shop-category-content').forEach(el => el.classList.add('hidden'));
            const container = event.target.closest('.shop-management-container');
            container.querySelectorAll('.tab').forEach(el => el.classList.remove('active'));
            document.getElementById('shop-cat-' + catId).classList.remove('hidden');
            event.target.classList.add('active');
        }
    </script>
    <?php
}

/**
 * Handles Form Submissions for Shop Items
 */
function handleShopActions($con) {
    if (isset($_POST['addShopItem']) || isset($_POST['editShopItem'])) {
        $name = mysqli_real_escape_string($con, $_POST['item_name']);
        $desc = mysqli_real_escape_string($con, $_POST['item_desc']);
        $price = (int)$_POST['item_price'];
        $category = mysqli_real_escape_string($con, $_POST['item_category']);
        $redirectCat = $_POST['redirect_category'] ?? 'seeds';

        if (isset($_POST['addShopItem'])) {
            // Generate ID
            $sqlId = "SELECT item_id FROM shop_item ORDER BY item_id DESC LIMIT 1";
            $resId = mysqli_query($con, $sqlId);
            $newId = "ITM001";
            if ($row = mysqli_fetch_assoc($resId)) {
                $num = (int)substr($row['item_id'], 3) + 1;
                $newId = "ITM" . str_pad($num, 3, "0", STR_PAD_LEFT);
            }

            // Handle Image Upload for New Item
            if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
                $target_dir = __DIR__ . '/../assets/shop_items/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                // Get extension
                $ext = strtolower(pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION));
                
                // FIXED: Strictly allow only PNG to match the item_id.png frontend logic
                if ($ext === 'png') {
                    // Always save as [ID].png
                    $target_file = $target_dir . $newId . ".png";
                    
                    // Remove any existing file (unlikely for new ID, but good practice)
                    if (file_exists($target_file)) unlink($target_file);

                    move_uploaded_file($_FILES['item_image']['tmp_name'], $target_file);
                } else {
                    // Optional: Alert user that only PNG is accepted
                    echo "<script>alert('Only PNG images are allowed for new items.');</script>";
                }
            }

            // SQL Insert (Removed item_image_code)
            // Ensure you have dropped the column in DB or this will fail
            $sql = "INSERT INTO shop_item (item_id, item_name, item_desc, item_price, item_category, item_availability) 
                    VALUES ('$newId', '$name', '$desc', '$price', '$category', 1)";
            
            if (mysqli_query($con, $sql)) {
                header("Location: final.php?action=shop_item_added&shop_category=$redirectCat");
                exit();
            } else {
                echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
            }

        } elseif (isset($_POST['editShopItem'])) {
            $id = mysqli_real_escape_string($con, $_POST['item_id']);
            
            // 1. Update Basic Info
            $sql = "UPDATE shop_item SET 
                    item_name = '$name', 
                    item_desc = '$desc', 
                    item_price = '$price', 
                    item_category = '$category' 
                    WHERE item_id = '$id'";

            if (mysqli_query($con, $sql)) {
                // 2. Handle Image Update (Optional)
                if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
                    $target_dir = __DIR__ . '/../assets/shop_items/';
                    $ext = strtolower(pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION));
                    
                    if ($ext === 'png') {
                        $target_file = $target_dir . $id . ".png";
                        // Overwrite existing
                        if (file_exists($target_file)) unlink($target_file);
                        move_uploaded_file($_FILES['item_image']['tmp_name'], $target_file);
                    }
                }
                
                header("Location: final.php?action=shop_item_updated&shop_category=$redirectCat");
                exit();
            } else {
                 echo "<script>alert('Error updating item: " . mysqli_error($con) . "');</script>";
            }
        }
    } elseif (isset($_POST['deleteShopItem'])) {
        $id = mysqli_real_escape_string($con, $_POST['item_id']);
        $redirectCat = $_POST['redirect_category'] ?? 'seeds';
        
        // 1. Delete the image file to save space
        $target_file = __DIR__ . '/../assets/shop_items/' . $id . '.png';
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        // 2. Delete from DB
        $sql = "DELETE FROM shop_item WHERE item_id = '$id'";
        
        if (mysqli_query($con, $sql)) {
            header("Location: final.php?action=shop_item_deleted&shop_category=$redirectCat");
            exit();
        } else {
            echo "<script>alert('Error deleting item: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>
