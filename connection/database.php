<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "sam123");
define("DB_NAME", "Managment");

// Create connection
$dbConnection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>