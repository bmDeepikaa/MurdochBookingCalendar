<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'];
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


// Establish connection to the database
require("db.php");
$sqlconn = mysqli_connect($host, $user, $passwd, $dbname);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize inputs
function sanitize_input($data, $sqlconn) {
    return mysqli_real_escape_string($sqlconn, trim($data));
}
// Function to check if a user exists based on email
function userExists($email, $sqlconn) {
    $email = mysqli_real_escape_string($sqlconn, $email);
    $query = "SELECT UserID FROM user WHERE Email = '$email'";
    $result = mysqli_query($sqlconn, $query);
    return mysqli_num_rows($result) > 0;
}

// Function to check if a meeting exists based on name and start time
function meetingExists($meetingName, $startTime, $sqlconn) {
    $meetingName = mysqli_real_escape_string($sqlconn, $meetingName);
    $startTime = mysqli_real_escape_string($sqlconn, $startTime);
    $query = "SELECT MeetingID FROM meeting WHERE MeetingName = '$meetingName' AND StartTime = '$startTime'";
    $result = mysqli_query($sqlconn, $query);
    return mysqli_num_rows($result) > 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $file = $_FILES["file"]["tmp_name"]; 
    $filename = $_FILES["file"]["name"];
    
    // Check if file exists and is readable
    if (!is_uploaded_file($file) || !file_exists($file)) {
        http_response_code(400);
        echo json_encode(array("message" => "Uploaded file not found or could not be read."));
        exit;
    }

    // Check if file has .csv extension
    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'csv') {
        http_response_code(400);
        echo json_encode(array("message" => "Uploaded file must be a CSV."));
        exit;
    }
    
    // Open the CSV file
    if (($handle = fopen($file, "r")) !== FALSE) {
        // Skip the header row
        fgetcsv($handle, 1000, ",");

        // Prepare insert statements
        $insert_user = mysqli_prepare($sqlconn, "INSERT INTO user (RoleID,Name, Email, PhoneNumber) VALUES ('4',?, ?, ?)");
        $insert_meeting = mysqli_prepare($sqlconn, "INSERT INTO meeting (MeetingName, MeetingDetails, BookingLink, Location, UserID) VALUES (?, ?, ?, ?, ?)");
        $insert_booking = mysqli_prepare($sqlconn, "INSERT INTO booking (UserID, MeetingID, StartTime, Status, MeetingHost, EmailSender) VALUES (?, ?, ?, 'Pending',?,?)");
        
        // Bind parameters
        mysqli_stmt_bind_param($insert_user, "sss", $name, $email, $phone);
        mysqli_stmt_bind_param($insert_meeting, "ssssi", $meetingName, $meetingDetails, $bookingLink, $location, $userID);
        mysqli_stmt_bind_param($insert_booking, "iisii", $userID, $meetingID, $startTime,$meetingHost,$user_id);

        // Process CSV rows
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $no = sanitize_input($data[0], $sqlconn);
            $fullname = sanitize_input($data[1], $sqlconn);
            $time = sanitize_input($data[2], $sqlconn);
            $date = sanitize_input($data[3], $sqlconn);

           // Check if user already exists
           $email = sanitize_input($data[4], $sqlconn);
           if (userExists($email, $sqlconn)) {
               // User already exists, skip insertion
               continue;
           }            
            $phonenumber = sanitize_input($data[5], $sqlconn);
            $meetingname = sanitize_input($data[6], $sqlconn);
            $meetingdetails = sanitize_input($data[7], $sqlconn);
            //$bookinglink = sanitize_input($data[8], $sqlconn);
            $location = sanitize_input($data[8], $sqlconn);
            $meetingHost = sanitize_input($data[9], $sqlconn);

            $datetime = $date . ' ' . $time;
        
            // Convert to datetime object
            $datetimeObject = DateTime::createFromFormat('d/m/Y H:i', $datetime);
            if (!$datetimeObject) {
                http_response_code(400);
                echo json_encode(array("message" => "Invalid datetime format: $datetime"));
                exit;
            }
            $startTime = $datetimeObject->format('Y-m-d H:i:s');
           
            // Insert into user table
            $name = ucwords(strtolower($fullname));
            $phone = $phonenumber;
            if (!mysqli_stmt_execute($insert_user)) {
                http_response_code(500);
                echo json_encode(array("message" => "Error inserting user data: " . mysqli_error($sqlconn)));
                exit;
            }
            $userID = mysqli_insert_id($sqlconn);

            // Insert into meeting table
            $meetingName = $meetingname;
            $meetingDetails = $meetingdetails;
            $bookingLink = $bookinglink;
            $location = $location;
            if (!mysqli_stmt_execute($insert_meeting)) {
                http_response_code(500);
                echo json_encode(array("message" => "Error inserting meeting data: " . mysqli_error($sqlconn)));
                exit;
            }
            $meetingID = mysqli_insert_id($sqlconn);

            // Insert into booking table
            $startTime = $startTime;
            if (!mysqli_stmt_execute($insert_booking)) {
                http_response_code(500);
                echo json_encode(array("message" => "Error inserting booking data: " . mysqli_error($sqlconn)));
                exit;
            }

            sendInvitationEmail($email, $name, $meetingName, $meetingDetails, $startTime, $location, $meetingID);

        }

        // // Generate unique URL for the attendee - need to replace with your domain
        // $uniqueURL = "http://localhost/Lynette/attendanceForm.html?meetingID=" . $meetingID;

        // // Email content - generated with the same data as the CSV above
        // $subject = "Meeting Invitation: " . $meetingName;
        // $message = "
        //     <html>
        //     <head>
        //         <title>Meeting Invitation</title>
        //     </head>
        //     <body>
        //         <p>Dear $name ,</p>
        //         <p>You are invited to the following meeting:</p>
        //         <p>Meeting Name: $meetingName<br>
        //         Meeting Details: $meetingDetails<br>
        //         Start Time: $startTime<br>
        //         Location: $location</p>
        //         <p>Please confirm your attendance by clicking the following link:</p>
        //         <a href='$uniqueURL'>Confirm Attendance</a>
        //     </body>
        //     </html>
        // ";
        // $headers = "MIME-Version: 1.0" . "\r\n";
        // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        // // Replace with your email address
        // $headers .= "From: no-reply@yourdomain.com" . "\r\n";

        // // Send email - using mail() function
        // if (!mail($email, $subject, $message, $headers)) {
        //     http_response_code(500);
        //     echo json_encode(array("message" => "Error sending email: " . error_get_last()['message']));
        //     exit;
        // }
    

        fclose($handle);

        echo json_encode(array("message" => "Data saved successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Error opening the file."));
    }
} else {
    http_response_code(500);
    echo json_encode(array("message" => "No file uploaded."));
}

// Close connection
mysqli_close($sqlconn);

// Function to send invitation email using PHPMailer
function sendInvitationEmail($recipientEmail, $recipientName, $meetingName, $meetingDetails, $startTime, $location, $meetingID) {
    // Instantiate PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 2; // Enable verbose debug output for testing (change to 2 for more details)
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com' ;                      // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'ngsamson90@gmail.com
';                 // SMTP username
        $mail->Password = 'qzmn idcs aikp bivu';                           // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption, PHPMailer::ENCRYPTION_SMTPS also accepted
        $mail->Port = 587;  // Outlook SMTP port
        // TCP port to connect to

        // Recipients
        $mail->setFrom('no-reply@gmail.com', 'Mailer');
        $mail->addAddress($recipientEmail, $recipientName);   // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Meeting Invitation: " . $meetingName;
        $mail->Body = "
            <html>
            <head>
                <title>Meeting Invitation</title>
            </head>
            <body>
                <p>Dear $recipientName,</p>
                <p>You are invited to the following meeting:</p>
                <p>Meeting Name: $meetingName<br>
                Meeting Details: $meetingDetails<br>
                Start Time: $startTime<br>
                Location: $location</p>
                <p>Please confirm your attendance by clicking the following link:</p>
                <a href='http://appoinmate.lovestoblog.com/attendeeForm.html?meetingID=$meetingID'>Confirm Attendance</a>
            </body>
            </html>
        ";
        
        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Handle error
        error_log("Failed to send email to $recipientEmail: {$mail->ErrorInfo}");
        return false;
    }
}
?>