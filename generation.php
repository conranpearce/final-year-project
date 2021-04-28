<?php
    include_once 'header.php';
?>
<section>
    <?php 
        include('index-functions/get-requests.php');
        include('index-functions/curl-templates.php');
        include('index-functions/post-requests.php');
        include('index-functions/set-variables.php');
        include('index-functions/on-off-buttons.php');
        include('index-functions/schedule-buttons.php');

        if (isset($_SESSION["useruid"])) {
            $_LOGGED_IN = True; // Set $_LOGGED_IN to true so that information is only displayed if the user is logged in
        } else {
            header('Location: index.php');
            exit();
        }

        // Display the current generation mix to the user if they are logged in
        if ($_LOGGED_IN == True) {
            getCurrentGenerationMix();
        }            
    ?>
</section>

<?php
    include_once 'footer.php';
?>