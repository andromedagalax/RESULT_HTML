<?php
include('init.php');
session_start();

// Check if the session variable is set
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit();
}

$user_check = $_SESSION['login_user'];

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch the logged-in user's details
$ses_sql = mysqli_query($conn, "SELECT userid FROM admin_login WHERE userid = '$user_check'");

if (!$ses_sql) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

$login_session = $row['userid'];

if (!isset($login_session)) {
    header("Location: login.php");
    exit();
}
?>