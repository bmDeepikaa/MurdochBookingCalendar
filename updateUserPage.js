"use strict";

$(document).ready(function () {
    // Function to get URL parameters
    function getQueryParam(param) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Set values in the update form
    $('#userID').val(getQueryParam('userID'));
    $('#fullname').val(getQueryParam('name'));
    $('#username').val(getQueryParam('username'));
    $('#email').val(getQueryParam('email'));    
    $('#contactNo').val(getQueryParam('contactNumber'));
    $('#accessRights').val(getQueryParam('role'));

    $('#updateForm').on('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'updateUser.php',
            type: 'POST',
            data: formData,
            processData: false,  // Prevent jQuery from processing the data
            contentType: false,  // Prevent jQuery from setting content type
            success: function (response) {
                alert('User details have been updated.');
                window.location.href = "userManagementPageHTML.php";
            },
            error: function (xhr, status, error) {
                if (xhr.status === 409) {
                    // Display specific error message for username conflict
                    alert('Username already exists.');
                } else {
                    alert('An error occurred while saving details: ' + xhr.responseText);
                }
            }
        });
    });
});