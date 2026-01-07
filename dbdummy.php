<!-- replace with main database  -->

<?php
$con = mysqli_connect("localhost", "root", "", "dummy");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
