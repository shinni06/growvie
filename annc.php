<?php
$con = mysqli_connect("localhost","root","","Growvie.sql");

if(mysqli_connect_errno()){
    die("Failed to connect to MySQL: " .mysqli_connect_error());
}

$sql = "SELECT * FROM announcement"

$result = mysqli_query($con,$sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($con));
}

while($row = mysqli_fetch_assoc($result)) {
    echo "Title: " . $row['announce_title'] . "<br>";
    echo "Content: " . $row['announce_body'] . "<br>";
    echo "Schedule Date: " . $row['announce_schedule_date'] . "<br><hr>";
}

mysqli_close($con);
?>

<?php
$sql = "SELECT * FROM announcement"
while
?>