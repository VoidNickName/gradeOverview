<?php
session_start();
require "functions.php";
databaseConnection();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
} else {
  $_SESSION["ERROR"] = NULL;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Grade Overview</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navBar.css">
    <link rel="stylesheet" href="index.css">
    <link rel="icon" href="pictures/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="pictures/favicon.ico" type="image/x-icon" />
    <script src="index.js"></script>
  </head>
  <body>
    <nav>
      <?php navBar() ?>
    </nav>
    <main>
        <h1>Welcome to Grade Overview</h1>
        <?php
        if (pullData("grades", $_SESSION["user"]) == NULL) {
          ?>
          <form method="post" action="add_grade.php">
            <input type="hidden" name="phase" value="4">
            <input type="submit" value="Add Grade" class="button"><br>
          </form>
          <?php
        } elseif (pullData("grades", $_SESSION["user"]) != NULL) {
          ?>
            <select class="dropdown" id="overview" name="overview" onchange="overviewChange()">
              <option value="mainGradeOverview">All Grades</option>
              <?php 
              subjectList();
              if (isset($_GET["subject"]) && $_GET["subject"] == "Average") {
              ?>
              <option value="Average" selected>Average</option>
              <?php } else {?>
                <option value="Average">Average</option>
              <?php } 
              if (isset($_GET["subject"]) && $_GET["subject"] == "Chart") {
              ?>
              <option value="Chart" selected>Chart</option>
              <?php } else {?>
                <option value="Chart">Chart</option>
              <?php } ?>
            </select><br>
          <?php
          if (!isset($_GET["subject"]) || $_GET["subject"] == "mainGradeOverview") {
            mainGradeOverview();
          } elseif ($_GET["subject"] == "Average") {
            mainGradeOverview();
            ?>
            <style>
              .semesterGrades {
                  display:none;
                  }
              .gradeOverviewTable {
                display: flex;
                justify-content: center;
              }
            </style>
            <?php
          } elseif ($_GET["subject"] == "Chart") {
            chart();
          } else {
            subjectGradeOverview($_GET["subject"]);
          }
        }
        $conn->close();
        ?>
    </main>
  </body>
</html>