<?php
    session_start();
    require "functions.php";
    databaseConnection();
    if ($_SESSION["phase"] == 0) {
        authenticate($_POST["username"], $_POST["password"]);
        echo "<script>location.href = 'index.php';</script>";
    } elseif ($_SESSION["phase"] == 1) {
        $_POST["fname"] = trim($_POST["fname"]);
        $_POST["lname"] = trim($_POST["lname"]);
        $_POST["username"] = trim($_POST["username"]);
        $_POST["password1"] = trim($_POST["password1"]);
        $_POST["password2"] = trim($_POST["password2"]);

        if ($_POST["fname"] == "" || $_POST["lname"] == "" || $_POST["username"] == "" || $_POST["password1"] == "" || $_POST["password2"] == "") {
            $_SESSION["phase"] = 1;
            $_SESSION["ERROR"] = 0;
            echo "<script>location.href = 'index.php';</script>";
        } elseif ($_POST["password1"] != $_POST["password2"]) {
            $_SESSION["phase"] = 1;
            $_SESSION["ERROR"] = 2;
            echo "<script>location.href = 'index.php';</script>";
        } elseif (strlen($_POST["password1"]) < 8) {
            $_SESSION["phase"] = 1;
            $_SESSION["ERROR"] = 3;
            echo "<script>location.href = 'index.php';</script>";
        } else {
            createUser($_POST["fname"], $_POST["lname"], $_POST["username"], $_POST["password1"]);
            authenticate($_POST["username"], $_POST["password1"]);
            echo "<script>location.href = 'index.php';</script>";
        }
    } elseif ($_SESSION["phase"] == 3) {
        addSubject($_POST["subject"]);
        echo "<script>location.href = 'add_grade.php';</script>";
    } elseif ($_SESSION["phase"] == 4) {
        addGrade($_POST["subject"], floatval($_POST["semester"]), floatval($_POST["grade"]), $_POST["description"], floatval($_POST["weight"]));
        echo "<script>location.href = 'add_grade.php';</script>";
    } elseif ($_SESSION["phase"] == 5) {
        deleteSubject($_POST["id"]);
        echo "<script>location.href = 'delete_grade.php';</script>";
    } elseif ($_SESSION["phase"] == 6) {
        deleteGrade($_POST["id"]);
        echo "<script>location.href = 'delete_grade.php';</script>";
    } elseif ($_SESSION["phase"] == 7) {
        whatGradeDoIHaveToGet($_POST["subject"], floatval($_POST["grade"]), floatval($_POST["weight"]));
        echo "<script>location.href = 'whatGradeDoIHaveToGet.php';</script>";
    } elseif ($_SESSION["phase"] == 8) {
        if (isset($_POST["firstName"]) && isset($_POST["password"])) {
            if ($_POST["firstName"] == "" || $_POST["password"] == "") {
                $_SESSION["ERROR"] = 0;
                echo "<script>location.href = 'settings.php';</script>";
            } elseif (authenticate($_SESSION["username"], $_POST["password"])) {
                changeUserData("firstname", $_POST["firstName"]);
                echo "<script>location.href = 'settings.php';</script>";
            } else {
                $_SESSION["ERROR"] = 4;
                echo "<script>location.href = 'settings.php';</script>";
            }
        } elseif (isset($_POST["lastName"]) && isset($_POST["password"])) {
            if ($_POST["lastName"] == "" || $_POST["password"] == "") {
                $_SESSION["ERROR"] = 0;
                echo "<script>location.href = 'settings.php';</script>";
            } elseif (authenticate($_SESSION["username"], $_POST["password"])) {
                changeUserData("lastname", $_POST["lastName"]);
                echo "<script>location.href = 'settings.php';</script>";
            } else {
                $_SESSION["ERROR"] = 4;
                echo "<script>location.href = 'settings.php';</script>";
            }
        } elseif (isset($_POST["username"]) && isset($_POST["password"])) {
            if ($_POST["username"] == "" || $_POST["password"] == "") {
                $_SESSION["ERROR"] = 0;
                echo "<script>location.href = 'settings.php';</script>";
            } elseif (doesUserExist($_POST["username"])) {
                $_SESSION["ERROR"] = 1;
                echo "<script>location.href = 'settings.php';</script>";
            } elseif (authenticate($_SESSION["username"], $_POST["password"])) {
                changeUserData("username", $_POST["username"]);
                $_SESSION["username"] = $_POST["username"];
                echo "<script>location.href = 'settings.php';</script>";
            } else {
                $_SESSION["ERROR"] = 4;
                echo "<script>location.href = 'settings.php';</script>";
            }
        } elseif (isset($_POST["password1"]) && isset($_POST["password2"]) && isset($_POST["password3"])) {
            if ($_POST["password1"] == "" || $_POST["password2"] == "" || $_POST["password3"] == "") {
                $_SESSION["ERROR"] = 0;
                echo "<script>location.href = 'settings.php';</script>";
            } elseif ($_POST["password2"] != $_POST["password3"]) {
                $_SESSION["ERROR"] = 2;
                echo "<script>location.href = 'settings.php';</script>";
            }  elseif (strlen($_POST["password2"]) < 8) {
                $_SESSION["ERROR"] = 3;
                echo "<script>location.href = 'settings.php';</script>";
            } elseif (authenticate($_SESSION["username"], $_POST["password1"])) {
                changeUserData("password", password_hash($_POST["password2"], PASSWORD_DEFAULT));
                echo "<script>location.href = 'index.php';</script>";
            } else {
                $_SESSION["ERROR"] = 4;
                echo "<script>location.href = 'settings.php';</script>";
            }
        } else {
            $_SESSION["ERROR"] = 0;
            echo "<script>location.href = 'settings.php';</script>";
        }
    } elseif ($_SESSION["phase"] == 9) {
        addSuggestion($_POST["suggestion"]);
        echo "<script>location.href = 'suggestions.php';</script>";
    }

    $conn->close();
?>