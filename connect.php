<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";

// Connection to the earist_database 
$dbname1 = "earist_database";
$conn1 = new mysqli($servername, $username, $password, $dbname1);

if ($conn1->connect_error) {
    die("Connection to earist_database failed: " . $conn1->connect_error);
}

// Connection to the earistlearningcommonbooking database
$dbname2 = "earistlearningcommonbooking";
$conn2 = new mysqli($servername, $username, $password, $dbname2);

if ($conn2->connect_error) {
    die("Connection to earistlearningcommonbooking failed: " . $conn2->connect_error);
}
?>
