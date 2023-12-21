<?php
    session_start();
    require "functions.php";

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $phase = 0;
    if (isset($_POST["phase"])) {
        $_SESSION["phase"] = $_POST["phase"];
    }
    if (isset($_SESSION["phase"]) && ($_SESSION["phase"] == 0 || $_SESSION["phase"] == 1)) {
        $phase = $_SESSION["phase"];
    }
     if (!isset($_SESSION["ERROR"])) {
        $_SESSION["ERROR"] = NULL;
    }
    $_SESSION["phase"] = $phase;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="pictures/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="pictures/favicon.ico" type="image/x-icon" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <main>
        <?php
    switch ($phase) {
        case 0:
            $_SESSION["ERROR"] = NULL;
            ?>
        <form method="post" action="login.php">
            <input type="hidden" name="phase" value="1">
            <input type="submit" value="Sign in" class="button"><br>
        </form>
        <form method="post" action="action_page.php">
            <label for="username" class="label">Username</label><br>
            <input type="text" id="username" name="username" class="textfield" required maxlength="255" autofocus><br>
            <label for="password" class="label">Password</label><br>
            <input type="password" id="password" name="password" class="textfield" required maxlength="255"
                minlength="8"><br>
            <input type="submit" class="button">
        </form>
        <?php
        break;
        case 1:
            ?>
        <form method="post" action="login.php">
            <input type="hidden" name="phase" value="0">
            <input type="submit" value="Log in" class="button"><br>
        </form>
        <form method="post" action="action_page.php">
            <label for="fname" class="label">First name</label><br>
            <input type="text" id="fname" name="fname" class="textfield" required maxlength="255"><br>
            <label for="lname" class="label">Last name</label><br>
            <input type="text" id="lname" name="lname" class="textfield" required maxlength="255"><br>
            <label for="username" class="label">Username</label><br>
            <input type="text" id="username" name="username" class="textfield" required maxlength="255"><br>
            <label for="password1" class="label">Password</label><br>
            <input type="password" id="password1" name="password1" class="textfield" required maxlength="255"
                minlength="8"><br>
            <meter max="4" id="password-strength-meter"></meter>
            <p id="password-strength-text"></p>
            <script src="login.js"></script>
            <label for="password2" class="label">Repeat your password</label><br>
            <input type="password" id="password2" name="password2" class="textfield" required maxlength="255"
                minlength="8"><br>
            <input type="submit" class="button">
        </form>
        <?php
            if (isset($_SESSION["ERROR"])) {
                echo "<p id=\"error\">";
                if ($_SESSION["ERROR"] == 0) {
                    echo "All fields need to be filled in.";
                }
                elseif ($_SESSION["ERROR"] == 1) {
                    echo "This username is already in use. Choose a different one.";
                }
                elseif ($_SESSION["ERROR"] == 2) {
                    echo "The two filled in passwords need to be the same.";
                }
                elseif ($_SESSION["ERROR"] == 3) {
                    echo "Your password needs to be at least 8 characters long";
                }
                echo "</p>";
            }
        break;
        case 2:
            if (isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["username"]) && isset($_POST["password1"]) && isset($_POST["password2"])) {
                createUser($_POST["fname"], $_POST["lname"], $_POST["username"], $_POST["password1"], $_POST["password2"]);
                //header("Location: index.php");
            } else {
                $_SESSION["phase"] = 1;
                $_SESSION["ERROR"] = 0;
                //header("Location: index.php");
            }
        break;
    }
?>
    </main>
</body>

</html>