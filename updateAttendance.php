<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establish connection to the database
require("db.php");
$sqlconn = mysqli_connect($host, $user, $passwd, $dbname);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if all required POST variables are set
if (!isset($_POST['meetingID'], $_POST['attendance'], $_POST['remark'])) {
    http_response_code(400);
    die("Error: Required data not set.");
}

// Get information and store in variables
$bookingID = $_POST['meetingID'];
$status = $_POST['attendance'];
$remarks = $_POST['remark'];

// Check if any of the variables are empty
if (empty($bookingID) || empty($status)) {
    http_response_code(400);
    die("Error: One or more fields are empty.");
}

// SQL query to insert with prepared statement
$sql = "UPDATE booking SET Status=?, Remark=? WHERE MeetingID=?";


// Prepare statement
$stmt = mysqli_prepare($sqlconn, $sql);

if (!$stmt) {
    http_response_code(500);
    die("Error: " . mysqli_error($sqlconn));
}
// Bind parameters
mysqli_stmt_bind_param($stmt, "ssi", $status, $remarks, $bookingID);

// Execute statement
if (mysqli_stmt_execute($stmt)) {
    echo "Data saved successfully";
} else {
    http_response_code(500);
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($sqlconn);
?>