<?php
    include('conn.php');
    $currentPage = basename($_SERVER['PHP_SELF']);
    include('sidebar.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growvie Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("plantRequest.php"); ?> 
    <?php 
        // To retrieve the total number of plant request made
        $queryPlantReqs = "SELECT COUNT(*) as total FROM virtual_plant WHERE is_completed = '1'";
        $resultPlantReqs = mysqli_query($con, $queryPlantReqs);
        $totalPlantReqs= mysqli_fetch_assoc($resultPlantReqs)['total'];
        
        // To retrieve the total number of pending request
        $queryPendingPlantReqs = "SELECT COUNT(*) as pending FROM virtual_plant LEFT JOIN real_tree_record ON virtual_plant.virtual_plant_id = real_tree_record.virtual_plant_id WHERE is_completed = '1' AND real_tree_record.virtual_plant_id IS NULL";
        $resultPendingPlantReqs = mysqli_query($con, $queryPendingPlantReqs);
        $totalPendingPlantReqs= mysqli_fetch_assoc($resultPendingPlantReqs)['pending'];

        // To retrieve total count of real trees planted
        $queryPlantedTrees= "SELECT COUNT(*) as planted FROM real_tree_record";
        $resultPlantedTrees = mysqli_query($con, $queryPlantedTrees);
        $totalPlantedTrees= mysqli_fetch_assoc($resultPlantedTrees)['planted'];

        // To calculate percentage for planted trees over plant request for progress bar
        $percentage = ($totalPlantReqs > 0) ? ($totalPlantedTrees / $totalPlantReqs) * 100 : 0;
        // To round percentage to 2 decimal
        $roundedPercentage = round($percentage, 2);
        
        // To get plant request and relevent information
        $queryUserPlantReqs = "SELECT 
                                virtual_plant.virtual_plant_id, 
                                plant.plant_name, user.name,
                                user.email 
                                FROM virtual_plant 
                                INNER JOIN user ON virtual_plant.user_id = user.user_id 
                                INNER JOIN plant ON virtual_plant.plant_id = plant.plant_id 
                                LEFT JOIN real_tree_record ON virtual_plant.virtual_plant_id = real_tree_record.virtual_plant_id 
                                WHERE virtual_plant.is_completed = '1' 
                                AND real_tree_record.virtual_plant_id IS NULL";
        $resultUserPlantReqs = mysqli_query($con, $queryUserPlantReqs);
    ?>
   <div class="container">
        <main class="content"> 
            <!-- displays partner organization name(user name) -->
            <h1>Welcome back, <?php echo $row['organization_name']; ?> </h1>
            <!-- displays partner organization description -->
            <h3><?php echo $row['description']; ?></h3>
            <div class="box">
                <div class="tpr">
                    <!-- Display all of request made and display progress bar -->
                    <div class="tpr-title">Total plant requests</div>

                    <div class="tpr-content">
                        <div class="plantProgressBar">
                            <div class="plantProgressBarFill" style="width: <?= $roundedPercentage ?>%;">
                                <?= $roundedPercentage ?>%
                            </div>
                        </div>

                        <div class="tpr-total">
                            <span>Total</span>
                            <h3><?= $totalPlantReqs ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Display how many real trees has been planted -->
                <div class="tp">
                    Trees planted
                    <h3><?php echo $totalPlantedTrees; ?></h3>
                </div>
                <!-- Display how much virtual plant has not been planted into real trees left -->
                <div class="pr">
                    Pending requests
                    <h3 id="totalPendingReqs"><?php echo $totalPendingPlantReqs; ?></h3>
                </div>
            </div>
            <h2>Plant Requests</h2>
            <!-- Displays every pending request -->
            <div id="pRequestContainer">
                <?php while ($requestRow = mysqli_fetch_assoc($resultUserPlantReqs)) { ?>
                    <div class="requests">
                        <div class="virtualPlantID">
                            <?= $requestRow ['virtual_plant_id'] ?>
                        </div>               
                        <div class="requestRows">
                            <div class="label">User</div>
                            <span><?= htmlspecialchars($requestRow ['name']) ?><br>
                                <span class="userEmail" style="color: #7E7E7E;"><?= htmlspecialchars($requestRow ['email']) ?></span>
                            </span>
                        </div>
                        <div class="requestRows">
                            <div class="label">Plant</div>
                            <span><?= $requestRow ['plant_name'] ?></span>
                        </div>
                        <!-- For partner(user) to input information when virtual plant has been planted as a real tree -->
                        <button class="completeBtn"
                            data-id="<?= $requestRow['virtual_plant_id'] ?>"
                            data-partner="<?= $row['partner_id'] ?>"
                            data-name="<?= htmlspecialchars($requestRow['name'], ENT_QUOTES) ?>"
                            data-email="<?= htmlspecialchars($requestRow['email'], ENT_QUOTES) ?>"
                            data-plant="<?= htmlspecialchars($requestRow['plant_name'], ENT_QUOTES) ?>">
                            Complete
                        </button>
                    </div>
                <?php } ?>         
            </div>
        </main>
    </div>
    
    <script> 
        // This define when the modal for creatign new announcement should be shown or closed
        function openModal(requestId) {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("modal").style.display = "block";
        }
        function closeModal() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("modal").style.display = "none";
        }

        document.getElementById("overlay").addEventListener("click", closeModal);

        // Control what information passed when complete button is clicked
        document.querySelectorAll('.completeBtn').forEach(button => {
            button.addEventListener('click', () => {
                
                const id = button.dataset.id;
                const name = button.dataset.name;
                const email = button.dataset.email;
                const plant = button.dataset.plant;
                const partnerId = button.dataset.partner;

                document.getElementById('modalUserName').innerText = name;
                document.getElementById('modalUserEmail').innerText = email;
                document.getElementById('modalPlantName').innerText = plant;
                document.getElementById('modalVirtualPlantId').value = id;
                document.getElementById('modalPartnerId').value = partnerId;
                
                // Get current local date
                const today = new Date();

                // Extract year, month, day
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');

                // Format as yyyy-mm-dd
                const localDate = `${yyyy}-${mm}-${dd}`;

                // Set the modal span to this date
                document.getElementById('modalFulfillmentDate').innerText = localDate;
                 openModal(id, name, email, plant,partnerId);
            });
        }); 
    </script>
</body>
</html>