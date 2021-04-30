// Check if the user has been inactive for 5 minutes
var schedulePlugs = function () {
    
    $.ajax({
        url: "http://localhost:8888/digital-systems-project/index.php?argument=schedule&act=device", // Pass the logout argument to index.php
        success: function(data) {
            console.log("Success");
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            console.log(error);
            console.log(status);
            console.log(xhr);
        }
    });

};