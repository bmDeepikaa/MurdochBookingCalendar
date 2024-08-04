<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establish connection to the database
require("db.php"); 
$dbc = mysqli_connect($host, $user, $passwd, $dbname);

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get information and store in variables
$userID = intval($_POST['userID']); // Convert to an integer

// Delete user query
$sql = "DELETE FROM user WHERE UserID = ?";

// Prepare statement
$stmt = mysqli_prepare($dbc, $sql);

if (!$stmt) {
    http_response_code(500);
    die("Error: " . mysqli_error($dbc));
}

// Bind parameters
mysqli_stmt_bind_param($stmt, "i", $userID);

// Execute statement
if (mysqli_stmt_execute($stmt)) {
    echo "User deleted successfully";
} else {
    http_response_code(500);
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($dbc);
?>