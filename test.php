<?php
$host = "localhost:3306";
$user = "admin_event";
$pass = "admin_event"; // your password here
$db   = "admin_event";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";
