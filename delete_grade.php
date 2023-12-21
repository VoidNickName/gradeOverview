<?php
session_start();
require "functions.php";
databaseConnection();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
$phase = 6;
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
    <title>Delete Grade</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navBar.css">
    <link rel="stylesheet" href="delete_grade.css">
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
        if (pullData("subjects", $_SESSION["user"]) == NULL && $phase != 5) {
          ?>
          <form method="post" action="delete_grade.php">
            <input type="hidden" name="phase" value="5">
            <input type="submit" value="Delete Subject" class="button"><br>
          </form>
          <?php
        } else {
            if ($phase != 5) {
            ?>
            <form method="post" action="delete_grade.php">
                <input type="hidden" name="phase" value="5">
                <input type="submit" value="Delete Subject" class="button"><br>
            </form>
            <?php
            }
            if (pullData("subjects", $_SESSION["user"]) != NULL && $phase != 6) {
            ?>
            <form method="post" action="delete_grade.php">
                <input type="hidden" name="phase" value="6">
                <input type="submit" value="Delete Grade" class="button"><br>
            </form>
            <?php
            }
        }

        switch ($phase) {
            case 5:
                deleteSubjectTable();
                if (isset($_SESSION["successful"])) {
                    successful($_SESSION["successful"]);
                }
                ?>
                <!-- The Modal -->
                <div id="myModalDelete" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p>Are you sure that you want to delete this subject?</p>
                        <form method="post" action="action_page.php">
                            <input type="hidden" name="phase" value="6">
                            <input type="hidden" name="id" id="id" value="?">
                            <input type="submit" value="Yes" class="button">
                        </form>
                        <form>
                            <input type="submit" value="No" class="button">
                        </form>
                    </div>
                </div>
                <script src="delete_grade.js"></script>
                <?php
            break;
            case 6:
                deleteGradeTable();
                if (isset($_SESSION["successful"])) {
                    successful($_SESSION["successful"]);
                }
                ?>
                <!-- The Modal -->
                <div id="myModalDelete" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p>Are you sure that you want to delete this grade?</p>
                        <form method="post" action="action_page.php">
                            <input type="hidden" name="phase" value="5">
                            <input type="hidden" name="id" id="id" value="?">
                            <input type="submit" value="Yes" class="button">
                        </form>
                        <form>
                            <input type="submit" value="No" class="button">
                        </form>
                    </div>
                </div>
                <script src="delete_grade.js"></script>
                <?php
        }

        $conn->close();
        ?>
    </main>
  </body>
</html>