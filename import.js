"use strict";
$(document).ready(function () {
    $('#emailForm').on('submit', function (event) {
        event.preventDefault();

        // Get form data using FormData object
        var formData = new FormData(this);

        // Send form data to PHP via Ajax
        $.ajax({
            type: 'POST',
            url: 'import.php',
            data: formData,
            processData: false,  // Prevent jQuery from processing the data
            contentType: false,   // Ensure that FormData is sent correctly
            success: function (response) {
                // Display response message
                $('#responseMessage').html('<div class="alert alert-success" role="alert">Data saved successfully.</div>');
                // Clear the form after successful submission
                $('#emailForm')[0].reset();
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                $('#responseMessage').html('<div class="alert alert-danger" role="alert">' + res.message + '</div>');
            }
        });
    });
});
