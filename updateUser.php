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
$username = $_POST['username'];
$email = $_POST['email'];
$phoneNumber = intval($_POST['contactNo']); // Convert to an integer

// Map roles to their corresponding integer values
$roleID = 0;
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
        // Handle error case if no valid role is found
        http_response_code(400); // Bad Request
        die("Error: Invalid role.");
}

// Check if the username already exists for other users
$checkUsernameQuery = "SELECT COUNT(*) FROM user WHERE Username = ? AND UserID != ?";
$checkStmt = mysqli_prepare($dbc, $checkUsernameQuery);
if (!$checkStmt) {
    http_response_code(500);
    die("Error: " . mysqli_error($dbc));
}
mysqli_stmt_bind_param($checkStmt, "si", $username, $userID); // Bind parameters
mysqli_stmt_execute($checkStmt);
mysqli_stmt_bind_result($checkStmt, $usernameCount);
mysqli_stmt_fetch($checkStmt);
mysqli_stmt_close($checkStmt);

if ($usernameCount > 0) {
    http_response_code(409); // Conflict
    die("Error: Username already exists for another user.");
}

// SQL to update records
$sql = "UPDATE user SET Username=?, Email=?, PhoneNumber=?, RoleID=? WHERE UserID=?";

// Prepare SQL statement 
$stmt = mysqli_prepare($dbc, $sql);
if (!$stmt) {
    http_response_code(500);
    die("Error: " . mysqli_error($dbc));
}

// Bind the information to the statment
mysqli_stmt_bind_param($stmt, "ssiii", $username, $email, $phoneNumber, $roleID, $userID);

// Execute statement
if (mysqli_stmt_execute($stmt)) {
    echo "Success.";
} else {
    http_response_code(500);
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($dbc);
?>

