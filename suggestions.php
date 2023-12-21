<?php
session_start();
require "functions.php";
databaseConnection();
if (!isset($_SESSION["user"])) {
    $_SESSION["phase"] = NULL;
    header("Location: login.php");
}
$_SESSION["phase"] = 9;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Suggestions</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navBar.css">
    <link rel="stylesheet" href="suggestions.css">
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
            <label for="suggestion" class="label">Leave a suggestion or something that could be improved:</label>
            <textarea name="suggestion" id="suggestion" class="textarea" required></textarea>
            <input type="submit" class="button">
        </form>
        <?php
        suggestionsTable();
        if (isset($_SESSION["successful"])) {
            successful($_SESSION["successful"]);
        }
        $conn->close();
        ?>
    </main>
  </body>
</html>