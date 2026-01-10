<?php
    include('conn.php');
    include ('sidebar.php');
    $currentPage = basename($_SERVER['PHP_SELF']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Growie History Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        //This gets all of information needed to display in history
        $queryRealTreeInfo = "SELECT real_tree_record.real_tree_id, 
                            real_tree_record.virtual_plant_id, 
                            real_tree_record.date_reported, 
                            real_tree_record.request_status, 
                            real_tree_record.planting_site, 
                            real_tree_record.coordinates,
                            real_tree_record.location,
                            real_tree_record.request_status,
                            virtual_plant.user_id, 
                            user.name, 
                            user.email
                            FROM real_tree_record
                            INNER JOIN virtual_plant ON
                            real_tree_record.virtual_plant_id = virtual_plant.virtual_plant_id
                            INNER JOIN user ON virtual_plant.user_id = user.user_id
                            WHERE real_tree_record.partner_id = '$partner_id'";

        $resultRealtreeInfo = mysqli_query($con, $queryRealTreeInfo);
        $resultRealtreeInfo = mysqli_query($con, $queryRealTreeInfo);

        if (!$resultRealtreeInfo) {
            die("SQL Error: " . mysqli_error($con));
        }


    ?>
    <div class="container">
        <main class="content">
            <h1>History</h1>
            <h3>Completed Real Tree Request</h3>
            <!-- This contains every single plant histroy -->
            <div class="plantHistoryWrapper">
                <?php 
                // For every result gotten it will generate one individual block
                if (mysqli_num_rows($resultRealtreeInfo) === 0) {
                    echo "No records found for this partner.";
                    exit;
                }

                while($realTreeRow = mysqli_fetch_assoc($resultRealtreeInfo)) { ?> 
                    <div class="plantContainer">
                        <div class="plantImg">
                            <!-- This looks for the specific picture for the real tree planted, if not available default picture is used -->
                            <img src="../PNGS/<?= $realTreeRow['real_tree_id'] ?>.png" 
                            alt="Tree <?= $realTreeRow['real_tree_id'] ?>"
                            onerror="this.src='../PNGS/defaultImage.png'">
                        </div>
                        <div class="plantDetails">
                            <!-- This display the ID for real tree planted -->
                            <div class="realTreeID"><?= $realTreeRow['real_tree_id'] ?></div>
                                <!-- The following is for displaying the information regarding the real tree -->
                                <div class="reqInfoWrapper">
                                    <div class="requestRows">
                                        <div class="label">User</div>
                                        <span><?= $realTreeRow['name'] ?><br>
                                            <span style="color: #7E7E7E"><?= $realTreeRow['email'] ?></span>
                                        </span>
                                    </div>
                                    <div class="requestRows">
                                        <div class="label">Virtual Plant ID</div>
                                        <span><?= $realTreeRow['virtual_plant_id'] ?></span>
                                    </div>
                                    <div class="requestRows">
                                        <div class="label">Fulfillment Date</div>
                                        <span><?= $realTreeRow['date_reported'] ?></span>
                                    </div>
                                    <div class="requestRows">
                                        <div class="label">Planting Site</div>
                                        <span><?= $realTreeRow['planting_site'] ?></span>
                                    </div>
                                    <div class="requestRows">
                                        <div class="label">Location</div>
                                        <span><?= $realTreeRow['location'] ?></span>
                                    </div>
                                    <div class="requestRows">
                                        <div class="label">Coordinates</div>
                                        <span><?= $realTreeRow['coordinates'] ?></span>
                                    </div>
                                    <div class="requestRows">
                                        <div class="label">Request Status</div>
                                        <span><?= $realTreeRow['request_status'] ?></span>
                                    </div>
                                </div>
                            </div>                                           
                        </div>
                <?php } ?>
            </div>
        </main>
    </div>
    
</body>
</html>