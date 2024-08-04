"use strict";
$(document).ready(function () {
    // Function to fetch user data from PHP and populate the table
    function fetchUsers() {
        $.ajax({
            url: 'fetchUsers.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                let tableBody = $('#userTable tbody');
                tableBody.empty();

                $.each(response, function (index, user) {
                    let role = "";
                    if (user.RoleID == 1) {
                        role = "Admin";
                    } else if (user.RoleID == 2) {
                        role = "Organizer";
                    } else if (user.RoleID == 3) {
                        role = "Convenor";
                    }
                    var row = '<tr>' +
                        '<td>' + user.UserID + '</td>' +
                        '<td>' + user.Name + '</td>' +
                        '<td>' + user.Username + '</td>' +
                        '<td>' + user.Email + '</td>' +                        
                        '<td>' + user.PhoneNumber + '</td>' +
                        '<td>' + role + '</td>' +
                        '<td>' +
                        '<button class="btn btn-primary update-btn">Update</button>  ' +
                        '<button class="btn btn-danger delete-btn">Delete</button>' +
                        '</td>' +
                        '</tr>';
                    tableBody.append(row);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching users:', error);
            }
        });
    }

    // Call fetchUsers() when the page loads
    fetchUsers();



    // Function to handle clicking the update button
    $('#userTable').on('click', 'button.update-btn', function (event) {
        event.preventDefault();

        // Get the row data
        let row = $(this).closest('tr');
        let userID = row.find('td:eq(0)').text().trim();
        let name = row.find('td:eq(1)').text().trim();
        let username = row.find('td:eq(2)').text().trim();
        let email = row.find('td:eq(3)').text().trim();
        let contactNumber = row.find('td:eq(4)').text().trim();
        let role = row.find('td:eq(5)').text().trim();

        // Construct the URL with query parameters
        var url = 'updateUserPageHTML.php?' +
            'userID=' + encodeURIComponent(userID) +
            '&name=' + encodeURIComponent(name) +
            '&username=' + encodeURIComponent(username) +
            '&email=' + encodeURIComponent(email) +
            '&contactNumber=' + encodeURIComponent(contactNumber) +
            '&role=' + encodeURIComponent(role);

        // Redirect to the update page with the query parameters
        window.location.href = url;
    });



    // Function to handle clicking the delete button
    $('#userTable').on('click', 'button.delete-btn', function (event) {
        event.preventDefault();

        // Get the user ID from the row
        let row = $(this).closest('tr');
        let userID = row.find('td:eq(0)').text().trim();

        // Show a confirmation dialog
        if (confirm('Are you sure you want to delete this user?')) {
            // Send a delete request to PHP via Ajax
            $.ajax({
                url: 'deleteUser.php',
                type: 'POST',
                data: { userID: userID },
                success: function (response) {
                    // Display success message and refresh the user table
                    alert('User deleted successfully.');
                    fetchUsers();
                },
                error: function (xhr, status, error) {
                    // Display error message
                    alert('Error deleting user.');
                    console.error('Error:', error);
                }
            });
        }
    });
});

