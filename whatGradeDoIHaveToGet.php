<?php
session_start();
require "functions.php";
databaseConnection();
if (!isset($_SESSION["user"])) {
    $_SESSION["phase"] = NULL;
    header("Location: login.php");
}
$_SESSION["phase"] = 7;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>What grade do I have to get?</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navBar.css">
    <link rel="stylesheet" href="whatGradeDoIHaveToGet.css">
    <link rel="icon" href="pictures/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="pictures/favicon.ico" type="image/x-icon" />
    <script src="index.js"></script>
  </head>
  <body>
    <nav>
      <?php navBar() ?>
    </nav>
    <main>
        <h1>Welcome to Grade Overview</h1><br>
        
        <form method="post" action="action_page.php">
            <label id="subject" class="label">Subject:</label><br>
            <select id="subject" name="subject" class="dropdown" required>
                <?php subjectList(); ?>
            </select><br>
            <label id="grade" class="label">What is you desired average:</label><br>
            <input type="text" id="grade" name="grade" class="textfield" required maxlength="255"><br>
            <label id="weight" class="label">Weight for upcoming test:</label><br>
            <input type="text" id="weight" name="weight" class="textfield" required maxlength="255"><br>
            <input type="submit" value="Calculate" class="button"><br>
        </form>

        <!-- The Modal -->
        <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>This is the grade you have to get:</p>
                <h1><?php 
                if (isset($_SESSION["whatGradeDoIHaveToGet"]) && is_float($_SESSION["whatGradeDoIHaveToGet"]) || isset($_SESSION["whatGradeDoIHaveToGet"]) && is_int($_SESSION["whatGradeDoIHaveToGet"])) {
                    echo round($_SESSION["whatGradeDoIHaveToGet"], 1);
                }
                ?></h1>
            </div>
        </div>
        <script src="whatGradeDoIHaveToGet.js"></script> 
        <?php

        if (isset($_SESSION["whatGradeDoIHaveToGet"]) && is_float($_SESSION["whatGradeDoIHaveToGet"]) || isset($_SESSION["whatGradeDoIHaveToGet"]) && is_int($_SESSION["whatGradeDoIHaveToGet"])) {
            $_SESSION["whatGradeDoIHaveToGet"] = NULL;
            echo "<script>modalButton()</script>";
        }

        $conn->close();
        ?>
    </main>
  </body>
</html>