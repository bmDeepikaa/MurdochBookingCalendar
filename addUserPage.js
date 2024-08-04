"use strict";
$(document).ready(function () {    
    $('#addUserForm').on('submit', function (event) {
        event.preventDefault();

        // Get form data using FormData object
        var formData = new FormData(this);

        // Send form data to PHP via Ajax
        $.ajax({
            type: 'POST',
            url: 'addUser.php',
            data: formData,
            processData: false,  // Prevent jQuery from processing the data
            contentType: false,   // Ensure that FormData is sent correctly
            success: function (response) {
                // Display response message
                $('#responseMessage').html('<div class="alert alert-success" role="alert">Data saved successfully.</div>');
                // Clear the form after successful submission
                $('#addUserForm')[0].reset();
            },
            error: function (xhr, status, error) {
                if (xhr.status === 409) {
                    // Display specific error message for username conflict
                    $('#responseMessage').html('<div class="alert alert-danger" role="alert">Error: Username already exists.</div>');
                } else {
                    // Display general error message
                    $('#responseMessage').html('<div class="alert alert-danger" role="alert">Error saving data.</div>');
                }
            }
        });
    });
});
