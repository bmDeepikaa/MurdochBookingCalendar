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

// Query to fetch users from the database
$query = "SELECT UserID, RoleID, Name, Email, PhoneNumber, Username FROM user WHERE RoleID IN (1, 2, 3)";
$result = mysqli_query($dbc, $query);

if (!$result) {
    die("Error retrieving data: " . mysqli_error($dbc));
}

// Prepare an array to store fetched data
$data = array();

// Fetch data from the result set
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Encode fetched data as JSON for AJAX response
echo json_encode($data);

// Close database connection
mysqli_close($dbc);

?>
