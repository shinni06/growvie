<?php

// Connect to growvie_db database
$con = mysqli_connect("localhost","root","","Growvie");

// Check connection and print error message if failed
if (mysqli_connect_errno()) {
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>