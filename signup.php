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
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p>Fill in all fields</p>";
            } else if ($_GET["error"] == "invaliduid") {
                echo "<p>Choose a proper username!</p>";
            } else if ($_GET["error"] == "invalidemail") {
                echo "<p>Choose a proper email!</p>";
            } else if ($_GET["error"] == "passworddontmatch") {
                echo "<p>Passwords doesn't match!</p>";
            } else if ($_GET["error"] == "stmtfailed") {
                echo "<p>Something went wrong, try again!</p>";
            } else if ($_GET["error"] == "usernametaken") {
                echo "<p>Username already taken, choose another username!</p>";
            } else if ($_GET["error"] == "none") {
                echo "<p>You have signed up!</p>";
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