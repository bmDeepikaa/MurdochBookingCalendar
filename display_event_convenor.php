<?php
session_start();
// Enable error reporting for debugging
 error_reporting(E_ALL);
 ini_set('display_errors', 1);

require 'db.php'; 
$con = mysqli_connect($host, $user, $passwd, $dbname);

 $user_id=$_SESSION['user_id'];
 
 
// $display_query = "select MeetingName,MeetingDetails,OrganiserID,CategoryID,Location from meeting";   

$display_query = "
SELECT 
    meeting.MeetingName ,
    meeting.MeetingDetails ,
    meeting.OrganiserID,
    meeting.CategoryID,
    meeting.Location,
    booking.*
FROM 
    meeting
JOIN 
    booking ON meeting.MeetingID = booking.MeetingID
WHERE 
    booking.MeetingHost = $user_id
";
$results = mysqli_query($con,$display_query);   
$count = mysqli_num_rows($results);  
if($count>0) 
{
    $data_arr=array();
    $i=1;
    while($data_row = mysqli_fetch_array($results, MYSQLI_ASSOC))
    {   
     $data_arr[$i]['event_id'] = $data_row['MeetingID'];
     $data_arr[$i]['title'] = $data_row['MeetingName'];
     $data_arr[$i]['start'] = $data_row['StartTime'];
     $data_arr[$i]['status'] = $data_row['Status'];
    $data_arr[$i]['color'] = '#'.substr(uniqid(),-6); // 'green'; pass colour name
    $data_arr[$i]['details'] = $data_row['MeetingDetails'];
    $data_arr[$i]['OrganiserID'] = $data_row['OrganiserID'];
    $data_arr[$i]['CategoryID'] = $data_row['CategoryID'];
    $data_arr[$i]['Location'] = $data_row['Location'];
      $data_arr[$i]['Remark'] = $data_row['Remark'];
    //   $data_arr[$i]['event_end_time'] = $data_row['event_end_time'];
    $i++;
    }
    
    $data = array(
                'status' => true,
                'msg' => 'successfully!',
                'data' => $data_arr
            );
}
else
{
    $data = array(
                'status' => false,
                'msg' => 'Error!'               
            );
}
echo json_encode($data);

?>
