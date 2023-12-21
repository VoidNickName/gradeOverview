<?php
session_start();
require "functions.php";
databaseConnection();
if (!isset($_SESSION["user"])) {
    $_SESSION["phase"] = NULL;
    header("Location: login.php");
}
$_SESSION["phase"] = 8;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Settings</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navBar.css">
    <link rel="stylesheet" href="settings.css">
    <link rel="icon" href="pictures/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="pictures/favicon.ico" type="image/x-icon" />
  </head>
  <body>
    <nav>
      <?php navBar() ?>
    </nav>
    <main>
        <h1>Welcome to Grade Overview</h1>
        <button onclick="firstNameOnClick()" class="button">Change your first name</button><br>
        <form method="post" action="action_page.php" id="changeFirstName">
            <label for="firstName" class="label">To what do you want to change your first name?</label><br>
            <input id="firstName" name="firstName" type="text" class="textfield" required maxlength="255"><br>
            <label for="password" class="label">Password:</label><br>
            <input id="password" name="password" type="password" class="textfield" required maxlength="255" minlength="8"><br>
            <input type="submit" value="change" class="button"><br>
        </form>
        <button onclick="lastNameOnClick()" class="button">Change your last name</button><br>
        <form method="post" action="action_page.php" id="changeLastName">
            <label for="lastName" class="label">To what do you want to change your last name?</label><br>
            <input id="lastName" name="lastName" type="text" class="textfield" required maxlength="255"><br>
            <label for="password" class="label">Password:</label><br>
            <input id="password" name="password" type="password" class="textfield" required maxlength="255" minlength="8"><br>
            <input type="submit" value="change" class="button"><br>
        </form>
        <button onclick="usernameOnClick()" class="button">Change your username</button><br>
        <form method="post" action="action_page.php" id="changeUsername">
            <label for="username" class="label">To what do you want to change your username?</label><br>
            <input id="username" name="username" type="text" class="textfield" required maxlength="255"><br>
            <label for="password" class="label">Password:</label><br>
            <input id="password" name="password" type="password" class="textfield" required maxlength="255" minlength="8"><br>
            <input type="submit" value="change" class="button"><br>
        </form>
        <button onclick="passwordOnClick()" class="button">Change your password</button><br>
        <form method="post" action="action_page.php" id="changePassword">
            <label for="password1" class="label">What is you current password?</label><br>
            <input id="password1" name="password1" type="password" class="textfield" required maxlength="255" minlength="8"><br>
            <label for="password2" class="label">To what do you want change your password?</label><br>
            <input id="password2" name="password2" type="password" class="textfield" required maxlength="255" minlength="8"><br>
            <meter max="4" id="password-strength-meter"></meter>
                <p id="password-strength-text"></p>
                <script src="settings.js"></script>
            <label for="password3" class="label">Repeat your new password</label><br>
            <input id="password3" name="password3" type="password" class="textfield" required maxlength="255" minlength="8"><br>
            <input type="submit" value="change" class="button"><br>
        </form>
        <?php
        if (isset($_SESSION["successful"])) {
            successful($_SESSION["successful"]);
        }
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
          elseif ($_SESSION["ERROR"] == 4) {
            echo "The password you filled in is wrong.";
          }
          echo "</p>";
        }

        $conn->close();
        ?>
    </main>
  </body>
</html>