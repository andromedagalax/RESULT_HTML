<?php
$servername = "sql12.freesqldatabase.com";
$username = "sql12715515";
$password = "ajpZzTuL5h";
$dbname = "sql12715515";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select database
$db = mysqli_select_db($conn, $dbname);
if (!$db) {
    die("Database selection failed: " . mysqli_error($conn));
}
?>