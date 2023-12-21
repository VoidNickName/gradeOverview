<?php
session_start();
require "functions.php";
databaseConnection();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
$phase = 4;
if (isset($_POST["phase"])) {
    $_SESSION["phase"] = $_POST["phase"];
}
if (isset($_SESSION["phase"])) {
    $phase = $_SESSION["phase"];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add grade</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navBar.css">
    <link rel="stylesheet" href="add_grade.css">
    <link rel="icon" href="pictures/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="pictures/favicon.ico" type="image/x-icon" />
  </head>
  <body>
    <nav>
      <?php navBar() ?>
    </nav>
    <main>
        <h1>Welcome to Grade Overview</h1>
        <?php
        if (pullData("subjects", $_SESSION["user"]) == NULL && $phase != 3) {
          ?>
          <form method="post" action="add_grade.php">
            <input type="hidden" name="phase" value="3">
            <input type="submit" value="Add Subject" class="button"><br>
          </form>
          <?php
        } else {
            if ($phase != 3) {
            ?>
            <form method="post" action="add_grade.php">
                <input type="hidden" name="phase" value="3">
                <input type="submit" value="Add Subject" class="button"><br>
            </form>
            <?php
            }
            if (pullData("subjects", $_SESSION["user"]) != NULL && $phase != 4) {
            ?>
            <form method="post" action="add_grade.php">
                <input type="hidden" name="phase" value="4">
                <input type="submit" value="Add Grade" class="button"><br>
            </form>
            <?php
            }
        }

        switch ($phase) {
            case 3:
                ?>
                <form method="post" action="action_page.php">
                    <label id="subject" class="label">Subject you would like to add:</label><br>
                    <input type="text" id="subject" name="subject" class="textfield" required maxlength="255"><br>
                    <input type="submit" value="Add Subject" class="button"><br>
                </form>
                <?php
                if (isset($_SESSION["successful"])) {
                    successful($_SESSION["successful"]);
                }
            break;
            case 4:
                ?>
                <form method="post" action="action_page.php">
                    <label id="subject" class="label">Subject:</label><br>
                    <select class="dropdown" id="subject" name="subject" required>
                    <?php subjectList(); ?>
                    </select><br>
                    <label id="semester" class="label">Semester:</label><br>
                    <input type="text" id="semester" name="semester" class="textfield" required maxlength="25"><br>
                    <label id="grade" class="label">Grade:</label><br>
                    <input type="text" id="grade" name="grade" class="textfield" required maxlength="255"><br>
                    <label id="description" class="label">Description:</label><br>
                    <input type="text" id="description" name="description" class="textfield" required maxlength="255"><br>
                    <label id="weight" class="label">Weight:</label><br>
                    <input type="text" id="weight" name="weight" class="textfield" required maxlength="255"><br>
                    <input type="submit" value="Add Grade" class="button"><br>
                </form>
                <?php
                if (isset($_SESSION["successful"])) {
                    successful($_SESSION["successful"]);
                }
            break;
        }

        $conn->close();
        ?>
    </main>
  </body>
</html>