<?php
    include_once 'header.php';
?>
    <section class="signup-form">
        <div class="signup-form-form"></div>
            <form action="includes/login.inc.php " method="post">
                <div class='container'>
                    <div class='center'>
                        <input type="text" name="uid" class='textInput' placeholder="Username/Email...">
                        <input type="password" name="pwd" class='textInput' placeholder="Password...">
                        <button type="submit" name="submit" class='button'>Log In</button>
                    </div>
                </div>
            </form>
        </div>

        <?php 
        // Error handling for login
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<script type='text/javascript'>alert('Fill in all fields');</script>";
            } else if ($_GET["error"] == "wronglogin") {
                echo "<script type='text/javascript'>alert('Incorrect login');</script>";
            } 
        } else if (isset($_SESSION["useruid"])) { // If the user is logged in then redirect away from signup page
            header('Location: index.php');
            exit();
        } 
        ?>

    </section>

<?php
    include_once 'footer.php';
?>