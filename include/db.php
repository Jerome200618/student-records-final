<?php
$host = "sql110.infinityfree.com"; 
$user = "if0_40953440";
$password = "3p3cFTUjw1fD3";
$database = "if0_40953440_student_records_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
