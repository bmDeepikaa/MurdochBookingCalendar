<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not, redirect to the login page
    echo '<script type="text/javascript">';
    echo 'alert("Please login in view.");';
    echo 'window.location.href = "index.html";'; 
    echo '</script>';
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
          integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
            integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="navBar.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="import.js"></script>


    <title>Service</title>
</head>

<body>

  <div class="container-fluid">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-danger" style="color: white;">
        <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
      
        
        <img src="../images/logo.png">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-4" aria-controls="navbar-collapse-4" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbar-collapse-4">
          <ul class="navbar-nav ml-auto">
          <li class="nav-item">
              <a class="nav-link" href="CalendarConvenorAccepted.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="CalendarConvenor.php">Full Calendar</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="meetingStatConHTML.php">Meeting Status</a>
            </li>
        
            <li class="nav-item">
              <a class="nav-link" href="readExcelConHTML.php">Import CSV</a>
            </li>
            </ul> <!-- Added closing tag for <ul> -->
            <div class="dropdown">
              <a class="btn btn-danger dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Profile
              </a>
            
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="logout.php">Logout</a>
              </div>
            </div>
        </div>
            </div>
        </div> <!-- Added closing tag for <div class="container"> -->
    </nav>




    <div class="container mt-5">

      <h2 style="padding-top: 50px;margin-bottom: 50px; "> Send email to Attendees </h2>

        <form id="emailForm" method="post" enctype="multipart/form-data">
            <div class="row justify-content-center">
                <div class="card">
                    <label class="form-label" for="customFile">Input CSV file to send emails</label>
                    <input type="file" class="form-control" id="customFile" name="file"
                           style="border-color: black ; color: black; margin-bottom: 20px;" />
                </div>
            </div>
            
            
            <div class="row justify-content-end" style="margin-top:30px ;padding-bottom: 50px;">
                 <div class="col-4">
                      <button type="button" action="??" class="btn btn-secondary" style="margin-left: 100px;">Cancel</button>
                      <button type="submit" id="submit" name="submit" class="btn btn-success" style="margin-left: 100px;">Submit</button>
                 </div>
            </div>
            <div id="responseMessage" style="margin-top: 20px;"></div>                          
        </form>
    </div>
</body>

</html>