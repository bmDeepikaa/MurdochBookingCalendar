<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php'; 
$con = mysqli_connect($host, $user, $passwd, $dbname);


session_start();



if (!isset($_SESSION['user_id'])) {
    echo '<script type="text/javascript">';
    echo 'alert("Please Login First To Access the Event Calendar");';
    echo 'window.location.href = "index.html";'; 
    echo '</script>';
    exit; 
}

?>
<!DOCTYPE html>

<html>
<head>
<title></title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
<!-- JS for jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<!-- bootstrap css and js -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


<link rel="stylesheet" href="login.css">
<link rel="stylesheet" href="navBar.css">
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
            <div class="row">
                <div class="col-lg-12">
                <h2 style="padding-top: 50px;margin-bottom: 50px; ">  Full Calendar </h2>

                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Start popup dialog box -->
        <div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Add New Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="MeetingName">Event name</label>
                            <input type="text" name="MeetingName" id="MeetingName" class="form-control" placeholder="Enter your meeting name">
                        </div>
                        <div class="form-group">
                            <label for="StartTime">Event start </label>
                            <input type="datetime-local" name="StartTime" id="StartTime" class="form-control onlydatepicker" placeholder="Event start date">
                        </div>
                 
                        <div class="form-group">
                            <label for="EndTimeevent">Remark</label> <!-- Corrected label "for" attribute -->
                            <input type="text" name="Remark" id="Remark" class="form-control" placeholder="Addtional information">
                        </div>
                        <div class="form-group">
                            <label for="MeetingDetails">Meeting Details</label>
                            <input type="text" name="MeetingDetails" id="MeetingDetails" class="form-control" placeholder="Enter your meeting details">
                        </div>
                        <div class="form-group">
                            <label for="Location">Location</label>
                            <input type="text" name="Location" id="Location" class="form-control" placeholder="Enter your location or remote link">
                        </div>
                     
                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End popup dialog box -->
        
        <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Title:</strong> <span id="eventTitle"></span></p>
                        <p><strong>Details:</strong> <span id="detials"></span></p> <!-- Corrected spelling: "details" -->
                      
                        <p><strong>Location:</strong> <span id="Locationtext"></span></p>
                        <p><strong>Start Date:</strong> <span id="eventStart"></span></p>
                      
                        <p><strong>Status:</strong> <span id="Status"></span></p>
                        <p><strong>Remarks:</strong> <span id="Remarks"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Added closing tag for <div class="container-fluid"> -->
</body>
</html>

<script>
$(document).ready(function() {

    display_events();
}); // end document.ready block

function display_events() {
    var events = [];
    $.ajax({
        url: 'display_event_organiser.php',
        dataType: 'json',
        success: function (response) {
            var result = response.data;
            console.log(result);
            $.each(result, function (i, item) {
                events.push({
                    event_id: item.event_id,
                    title: item.title,
                    start: item.start,
                     Remark: item.Remark,
                      Status: item.status,
                    color: item.color,
                    detials: item.details, // Corrected spelling: "details"
                    OrganiserID: item.OrganiserID,
                    CategoryID: item.CategoryID,
                    Location: item.Location,
                   
                });
            });

            $('#calendar').fullCalendar({
                defaultView: 'month',
                timeZone: 'local',
                editable: true,
                selectable: true,
                selectHelper: true,
                select: function(start, end) {
                    $('#StartTime').val(moment(start).format('YYYY-MM-DD'));
                    $('#EndTime').val(moment(end).format('YYYY-MM-DD')); // Corrected ID: from event_end_date to EndTime
                    $('#event_entry_modal').modal('show');
                },
                events: events,
                eventRender: function(event, element, view) {
                    element.bind('click', function() {
                        // Inject event details into the modal
                        $('#eventTitle').text(event.title);
                        $('#detials').text(event.detials); // Corrected spelling: "details"
                        $('#OrganiserIDtext').text(event.OrganiserID);
                        $('#CategoryIDtext').text(event.CategoryID);
                        $('#Locationtext').text(event.Location);
                        $('#eventStart').text(event.start);
                        $('#Status').text(event.Status);
                        $('#Remarks').text(event.Remark);

                        // Show the modal
                        $('#eventModal').modal('show');
                    });
                }
            });
        },
        error: function (xhr, status) {
            alert('Error: ' + xhr.responseText);
        }
    });
}

function save_event() {
    var MeetingName = $("#MeetingName").val();
    var StartTime = $("#StartTime").val();
    var Remark = $("#Remark").val();
    var MeetingDetails = $("#MeetingDetails").val();
    var Location = $("#Location").val();
    var CategoryID = $("#CategoryID").val();
    var OrganiserID = $("#OrganiserID").val();
    // var StartTimeevent = $("#StartTimeevent").val();
    // var EndTimeevent = $("#EndTimeevent").val();

    if (MeetingName == "" || StartTime == "" || Remark == "" || MeetingDetails == "" || Location == "" || CategoryID == "" || OrganiserID == "") {
        alert("Please enter all required details.");
        return false;
    }

    $.ajax({
        url: "save_event.php",
        type: "POST",
        dataType: 'json',
        data: {
            MeetingName: MeetingName,
            StartTime: StartTime,
            Remark: Remark,
            MeetingDetails: MeetingDetails,
            Location: Location,
            CategoryID: CategoryID,
            OrganiserID: OrganiserID,
            // StartTimeevent: StartTimeevent,
            // EndTimeevent: EndTimeevent
        },
        success: function(response) {
            $('#event_entry_modal').modal('hide');
            if (response.status == true) {
                alert(response.msg);
                location.reload();
            } else {
                alert(response.msg);
            }
        },
        error: function (xhr, status) {
            console.log('ajax error = ' + xhr.statusText);
            alert(xhr.responseText); // Corrected alert message to display xhr.responseText
        }
    });

    return false;
}
</script>