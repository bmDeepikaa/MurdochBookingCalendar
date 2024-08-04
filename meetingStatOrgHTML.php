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
    <title>Rights page</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="login.css">
      <link rel="stylesheet" href="navBar.css">
        <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Custom JS -->
  <script src="meetingStatCon.js"></script>

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
              <a class="nav-link" href="CalendarOrganiserAccepted.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="CalendarOrganiser.php">Full Calendar</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="meetingStatOrgHTML.php">Meeting Status</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="readExcelOrgHTML.php">Import CSV</a>
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

      

  <div class="container">
    <div class="row mt-5">
      <div class="col">
        <h2 style="padding-top: 50px;margin-bottom: 50px; ">  Meeting Status </h2>
        <div class="card mt-5">
          <div class="card-body">
            <table id="attendeeTable" class="table table-bordered text-center">
              <thead class="bg-dark text-white">
                <tr >
                <td>Fullname</td>
                <td>Email Address</td>
                <td>Contact Number</td>
                <td>Meeting Title</td>
                <td>Status</td>
              </tr>
            </thead>
              <tbody>
                <!-- replace this part with php connection  -->

              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>


</body>

</html>