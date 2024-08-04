<?php
require("db.php");

$sqlconn = mysqli_connect($host, $user, $passwd, $dbname);

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['meetingID'])) {
    $meetingID = intval($_GET['meetingID']);
    $query = "SELECT u.UserID as attendeeNo, b.StartTime as date, b.StartTime as time, m.Location as venue
              FROM booking b
              JOIN meeting m ON b.MeetingID = m.MeetingID
              JOIN user u ON b.UserID = u.UserID
              WHERE m.MeetingID = ?";
    
    $stmt = mysqli_prepare($sqlconn, $query);
    mysqli_stmt_bind_param($stmt, "i", $meetingID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    echo json_encode($data);
}

mysqli_close($sqlconn);
?>
