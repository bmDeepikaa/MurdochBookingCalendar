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
//$userID = intval($_POST['userID']);
$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$phoneNumber = intval($_POST['phoneNumber']);

// Map roles to their corresponding integer values
$roleID = 0; // Default value in case no valid role is found
$roleName = $_POST['accessRights'];

switch ($roleName) {
    case 'Admin':
        $roleID = 1;
        break;
    case 'Organizer':
        $roleID = 2;
        break;
    case 'Convenor':
        $roleID = 3;
        break;
    default:
        http_response_code(400);
        die("Error: No valid role found.");
}

// Check if the username already exists
$checkUsernameQuery = "SELECT COUNT(*) FROM user WHERE Username = ?";
$checkStmt = mysqli_prepare($dbc, $checkUsernameQuery);
if (!$checkStmt) {
    http_response_code(500);
    die("Error: " . mysqli_error($dbc));
}
mysqli_stmt_bind_param($checkStmt, "s", $username);
mysqli_stmt_execute($checkStmt);
mysqli_stmt_bind_result($checkStmt, $usernameCount);
mysqli_stmt_fetch($checkStmt);
mysqli_stmt_close($checkStmt);

if ($usernameCount > 0) {
    http_response_code(409); // Conflict
    die("Error: Username already exists.");
}

/// SQL query to insert with prepared statement
$sql = "INSERT INTO user (RoleID, Name, Email, PhoneNumber, Password, Username) 
        VALUES (?, ?, ?, ?, ?, ?)";

// Prepare statement
$stmt = mysqli_prepare($dbc, $sql);

if (!$stmt) {
    http_response_code(500);
    die("Error: " . mysqli_error($dbc));
}
// Bind parameters
mysqli_stmt_bind_param($stmt, "ississ", $roleID, $name, $email, $phoneNumber, $password, $username);

// Execute statement
if (mysqli_stmt_execute($stmt)) {
    echo "Data saved successfully";
} else {
    http_response_code(500);
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($dbc);
?>