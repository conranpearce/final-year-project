// Update the calls to the APIs every 30 minutes, in lines with the National Grid API updating their information displayed
var updateAPIs = setInterval(function () {
    $.ajax({
        url: "http://localhost:8888/digital-systems-project/index.php?act=update", // Pass the logout argument to index.php
        success: function(data) {
            window.location.href = "index.php";
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            console.log(error);
            console.log(status);
            console.log(xhr);
        }
    });
}, 1800 * 1000); // Update every 30 minutes