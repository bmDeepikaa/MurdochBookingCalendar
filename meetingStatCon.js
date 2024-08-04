"use strict";
$(document).ready(function () {
    // Function to fetch user data from PHP and populate the table
    function fetchAttendees() {
        $.ajax({
            url: 'fetchAttendees.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                let tableBody = $('#attendeeTable tbody');
                tableBody.empty();

                $.each(response, function (index, user) {
                    
                    var row = '<tr>' +
                        '<td>' + user.Name + '</td>' +
                        '<td>' + user.Email + '</td>' +                        
                        '<td>' + user.PhoneNumber + '</td>' +
                        '<td>' + user.MeetingDetails + '</td>' +
                        '<td>' + user.Status + '</td>'  +
                        '</tr>';
                    tableBody.append(row);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching users:', error);
            }
        });
    }
    fetchAttendees();
});
