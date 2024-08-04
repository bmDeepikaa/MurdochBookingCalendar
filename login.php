<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require("db.php"); 
$conn = mysqli_connect($host, $user, $passwd, $dbname);

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT u.UserID,r.RoleTitle FROM user u join role r on u.RoleID = r.RoleID WHERE username = ? AND password = ?");
    
    // Check if prepare() failed
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    if ($stmt->execute()) {
        $stmt->store_result();

        // Check if any results were returned
        if ($stmt->num_rows > 0) {
            // Bind result variables
            $stmt->bind_result($userID,$role);
            $stmt->fetch();
           // Set session variables
             $_SESSION['user_id'] = $userID;
            $_SESSION['role'] = $role;
            // Redirect based on the user's role
            if ($role == 'Admin') {
                header("Location: CalendarAdmin.php");
            } elseif ($role == 'Convenor') {
                header("Location: CalendarConvenorAccepted.php");
            } elseif ($role == 'Organizer') {
                header("Location: CalendarOrganiserAccepted.php");
            } else {
                header("Location: login.html");
            }
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
