<?php
    include_once 'header.php';
?>
    <section class="signup-form">
        <div class="center"></div>
            <form action="includes/signup.inc.php " method="post">
                <div class='container'>
                        <div class='center'>
                            <input type="text" name="name" class='textInput' placeholder="Full name...">
                            <input type="text" name="email" class='textInput' placeholder="Email...">
                            <input type="text" name="uid" class='textInput' placeholder="Username...">
                            <input type="password" name="pwd" class='textInput' placeholder="Password...">
                            <input type="password" name="pwdrepeat" class='textInput' placeholder="Repeat password...">
                            <input type="text" name="tplinkuser" class='textInput' placeholder="TP-Link username...">
                            <input type="password" name="tplinkpwd" class='textInput' placeholder="TP-Link password...">
                            <button type="submit" name="submit" class='button'>Sign Up</button>
                        </div>
                    </div>
            </form>
        </div>

        <?php 
        // Error catching for signup
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<script type='text/javascript'>alert('Fill in all fields');</script>";
            } else if ($_GET["error"] == "invaliduid") {
                echo "<script type='text/javascript'>alert('Choose a valid username');</script>";
            } else if ($_GET["error"] == "invalidemail") {
                echo "<script type='text/javascript'>alert('Choose a valid email');</script>";
            } else if ($_GET["error"] == "passworddontmatch") {
                echo "<script type='text/javascript'>alert('Please enter matching passwords');</script>";
            } else if ($_GET["error"] == "stmtfailed") {
                echo "<script type='text/javascript'>alert('Something went wrong, please try again!');</script>";
            } else if ($_GET["error"] == "usernametaken") {
                echo "<script type='text/javascript'>alert('Username already taken, please choose another username!');</script>";
            } else if ($_GET["error"] == "none") {
                echo "<script type='text/javascript'>alert('Sign up successful!');</script>";
                header('Location: login.php');
                exit();
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