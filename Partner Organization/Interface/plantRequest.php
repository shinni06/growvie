<?php
    include('conn.php');
    $currentPage = basename($_SERVER['PHP_SELF']);
    
    // Process form submission BEFORE any HTML output
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $uploadOk = 1; 
        $photo_code = null;
        $errors = [];

        // Check if required POST data exists first
        if (empty($_POST['virtual_plant_id'])) {
            $errors[] = "Virtual Plant ID is missing.";
        }
        if (empty($_POST['partner_id'])) {
            $errors[] = "Partner ID is missing.";
        }

        // Generate real_tree_id BEFORE file upload
        $real_tree_id = null;
        if (empty($errors) && $con) {
            // Get the highest numeric value from real_tree_id
            $result = mysqli_query($con, "SELECT MAX(CAST(SUBSTRING(real_tree_id, 3) AS UNSIGNED)) AS max_num FROM real_tree_record");
            
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $max_number = $row['max_num'];
                
                if ($max_number !== null) {
                    // Continue from the highest number, regardless of gaps
                    $new_number = $max_number + 1;
                } else {
                    // No records exist, start from 1
                    $new_number = 1;
                }
                
                $real_tree_id = 'RT' . str_pad($new_number, 3, '0', STR_PAD_LEFT);
            } else {
                // No records exist, start from RT001
                $real_tree_id = 'RT001';
            }
            
            error_log("Generated Real Tree ID: " . $real_tree_id);
        }

        // Handle file upload with real_tree_id as filename
        if (isset($_FILES['plant_image']) && $_FILES['plant_image']['error'] === UPLOAD_ERR_OK && $real_tree_id) {

            $imageFileType = strtolower(pathinfo($_FILES["plant_image"]["name"], PATHINFO_EXTENSION));

            // to check if file is png
            if ($imageFileType !== 'png') {
                echo "<script>alert('Only PNG images are allowed.'); window.location.href='dashboard.php';</script>";
                exit;
            }

            // Double-check real MIME type (prevents fake .png files)
            $mime = mime_content_type($_FILES["plant_image"]["tmp_name"]);
            if ($mime !== 'image/png') {
                echo "<script>alert('Invalid PNG file.'); window.location.href='dashboard.php';</script>";
                exit;
            }

            $photo_code = $real_tree_id . '.png';
            $target_file = "../PNGS/" . $photo_code;

            if (!move_uploaded_file($_FILES["plant_image"]["tmp_name"], $target_file)) {
                echo "<script>alert('Image upload failed.'); window.location.href='dashboard.php';</script>";
                exit;
            }
        }


        // Set default if no file uploaded
        if ($photo_code === null) {
            $photo_code = 'default.png';
        }

        // Insert into database only if no errors so far
        if (empty($errors) && $con && $real_tree_id) {
            $virtual_plant_id = mysqli_real_escape_string($con, $_POST['virtual_plant_id']);
            $partner_id       = mysqli_real_escape_string($con, $_POST['partner_id']);
            $planting_site    = mysqli_real_escape_string($con, $_POST['planting_sites']);
            $location         = mysqli_real_escape_string($con, $_POST['location']);
            $coordinates      = mysqli_real_escape_string($con, $_POST['coordinates']);
            $date_reported    = date('Y-m-d');

            // Verify partner_id exists
            $check_partner = mysqli_query($con, "SELECT partner_id FROM partner WHERE partner_id = '$partner_id'");
            
            if (!$check_partner) {
                $errors[] = "Database query error: " . mysqli_error($con);
            } elseif (mysqli_num_rows($check_partner) == 0) {
                $all_partners = mysqli_query($con, "SELECT partner_id FROM partner");
                $available = [];
                while ($p = mysqli_fetch_assoc($all_partners)) {
                    $available[] = $p['partner_id'];
                }
                $errors[] = "Invalid partner ID: '$partner_id'. Available partners: " . implode(', ', $available);
            } else {
                // Partner exists, proceed with insert
               $sql = "INSERT INTO 
                        real_tree_record (
                        real_tree_id, 
                        virtual_plant_id, 
                        partner_id, 
                        location, 
                        coordinates, 
                        planting_site, 
                        date_reported)
                        VALUES 
                        (?, ?, ?, ?, ?, ?, ?)";


                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssss",
                    $real_tree_id,
                    $virtual_plant_id,
                    $partner_id,
                    $location,
                    $coordinates,
                    $planting_site,
                    $date_reported);


                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Real tree record successfully saved with ID: $real_tree_id'); window.location.href='dashboard.php';</script>";
                    exit;
                } else {
                    $errors[] = "Database error: " . mysqli_error($con);
                }
                mysqli_stmt_close($stmt);
            }
        } elseif (!$con) {
            $errors[] = "Database connection error.";
        }

        // Display errors
        if (!empty($errors)) {
            echo "<script>alert('" . addslashes(implode("\\n", $errors)) . "');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- To give a dark transparent background look -->
    <div id="overlay" class="overlay"></div>
    <form action="plantRequest.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="modal-wrapper">
        <div id="modal" class="modal">
            <div class="modal-content">
                <!-- To contain img and upload button -->
                <div class="left">
                    <div class="img-box">
                        <img id="preview" src="../PNGS/defaultImage.png" alt="defaultPic">
                    </div>
                    <!-- Button to preview and upload picture -->
                    <input type="file" name="plant_image" id="plant_image" style="display:none" accept="image/*">
                    <button type="button" class="upload-btn" onclick="document.getElementById('plant_image').click()">Upload Image</button>
                    
                </div>
                <!-- To contain all of the information and user input on the rigth side of modal -->
                <div class="right">
                    <!-- To pass virtual plant id and partner id without displaying -->
                    <input type="hidden" id="modalVirtualPlantId" name="virtual_plant_id">
                    <input type="hidden" id="modalPartnerId" name="partner_id">
                    <div class="row" style="display:none;">
                        <label>Debug - Partner ID: </label>
                        <span id="debugPartnerId"></span>
                    </div>

                    <div class="row">
                        <label>User: </label>
                        <span id="modalUserName"></span>
                    </div>

                    <div class="row">
                        <label>Email:</label>
                        <span id="modalUserEmail"></span>
                    </div>

                    <div class="row">
                        <label>Plant:</label>
                        <span id="modalPlantName"></span>
                    </div>

                    <div class="row">
                        <label>Fulfillment Date:</label>
                        <span id="modalFulfillmentDate"></span>
                    </div>
                    
                    <label>Planting Site</label>
                    <input type="text   " name="planting_sites" required>

                    <label>Location</label>
                    <input type="text" name="location" required>

                    <label>Coordinates</label>
                    <input type="text" name="coordinates" required>
                </div>       
            </div>
            <!-- button for user to submit form -->
            <button type="submit" class="reqUpload-btn">Upload</button>
        </div>       
        </div>
    </form>
    <script>
        // This is to change default image to user's desire image for previewing purpose
        document.getElementById('plant_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type - accept all image types
                if (!file.type.match('image.*')) {
                    alert('Please select an image file.');
                    this.value = '';
                    document.getElementById('preview').src = '../PNGS/defaultImage.png';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // This is to confirm if virtual plant id and partner id is being passed
        function validateForm() {
            const virtualPlantId = document.getElementById('modalVirtualPlantId').value;
            const partnerId = document.getElementById('modalPartnerId').value;

            if (!virtualPlantId || virtualPlantId.trim() === '') {
                alert('Virtual Plant ID is missing. Please select a valid plant request.');
                return false;
            }

            if (!partnerId || partnerId.trim() === '') {
                alert('Partner ID is missing. Please select a valid plant request.');
                return false;
            }

            return true;
        }

        // Monitor changes to hidden inputs
        const observer = new MutationObserver(updateDebugInfo);
        observer.observe(document.getElementById('modalVirtualPlantId'), { attributes: true, attributeFilter: ['value'] });
        observer.observe(document.getElementById('modalPartnerId'), { attributes: true, attributeFilter: ['value'] });
    </script>

</body>
</html>