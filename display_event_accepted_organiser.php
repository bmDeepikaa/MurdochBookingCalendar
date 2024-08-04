<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php'; // Assuming this file contains database connection parameters
$con = mysqli_connect($host, $user, $passwd, $dbname);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

// Prepare SQL query with placeholders
$display_query = "
SELECT 
    meeting.MeetingName,
    meeting.MeetingDetails,
    meeting.OrganiserID,
    meeting.CategoryID,
    meeting.Location,
    booking.*
FROM 
    meeting
JOIN 
    booking ON meeting.MeetingID = booking.MeetingID
WHERE 
    booking.Status = 'Accepted' 
    AND
    booking.EmailSender = ?
";

// Prepare statement
$stmt = mysqli_prepare($con, $display_query);
if ($stmt === false) {
    die('Error: ' . mysqli_error($con));
}

// Bind parameters
mysqli_stmt_bind_param($stmt, 'i', $user_id); // 'i' specifies the type of the parameter (integer)

// Execute query
mysqli_stmt_execute($stmt);

// Get results
$results = mysqli_stmt_get_result($stmt);

// Check if query executed successfully
if (!$results) {
    die('Error: ' . mysqli_error($con));
}

$count = mysqli_num_rows($results);
if ($count > 0) {
    $data_arr = array();
    $i = 1;
    while ($data_row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
        $data_arr[$i]['event_id'] = $data_row['MeetingID'];
        $data_arr[$i]['title'] = $data_row['MeetingName'];
        $data_arr[$i]['start'] = $data_row['StartTime'];
        $data_arr[$i]['status'] = $data_row['Status'];
        $data_arr[$i]['color'] = '#' . substr(uniqid(), -6); // Generating random color
        $data_arr[$i]['details'] = $data_row['MeetingDetails'];
        $data_arr[$i]['OrganiserID'] = $data_row['OrganiserID'];
        $data_arr[$i]['CategoryID'] = $data_row['CategoryID'];
        $data_arr[$i]['Location'] = $data_row['Location'];
        $data_arr[$i]['Remark'] = $data_row['Remark'];
        $i++;
    }

    $data = array(
        'status' => true,
        'msg' => 'Successfully fetched meetings!',
        'data' => $data_arr
    );
} else {
    $data = array(
        'status' => false,
        'msg' => 'No pending meetings found for this user.'
    );
}

echo json_encode($data);

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($con);
?>