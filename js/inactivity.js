// Check if the user has been inactive for 5 minutes
var inactivity = function () {
    var time;
    // Check if the user is not carrying out any of these DOM events
    window.onload = resetInactivity;
    window.onmousemove = resetInactivity;
    window.onkeypress = resetInactivity;
    window.ontouchstart = resetInactivity; 
    window.onclick = resetInactivity;      
    window.onkeydown = resetInactivity;   
    window.addEventListener('scroll', resetInactivity, true);

    // Log the user out if there are no DOM events, using AJAX
    function logout() {
        $.ajax({
            url: "http://localhost:8888/login-system/index.php?argument=logout", // Pass the logout argument to index.php
            success: function(data) {
                window.location.href = "login.php"; // Redirect the user to the login page if there is inactivity
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error);
                console.log(status);
                console.log(xhr);
            }
        });
    }
    // Set a 5 minute timer to check for DOM events (inactivity)
    function resetInactivity() {
        clearTimeout(time);
        time = setTimeout(logout, 300000)
    }
};