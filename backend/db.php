<?php
$con = mysqli_connect("localhost","root","","growvie_db");

// Check connection (optional)
if (mysqli_connect_errno()) {
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>