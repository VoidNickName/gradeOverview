<?php
function databaseConnection() {
    $serverName = "localhost";
    $username = "2GlW1dSNPySl%r*X";
    $password = "*lIV8e\$vEVfhHpg2gjY#0cYgLX#s04y73LPWgnVAiu86P4@!Z1";
    $dbname = "xh8mnz55*odvutk6iw#39cu3hhugcl3&mzuj!wd1nlokvuqx0j";

    // Create connection
    global $conn;
    $conn = new mysqli($serverName, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

function createUser($a, $b, $c, $d) {
    global $conn;
    $firstname = NULL;
    $lastname = NULL;
    $username = NULL;
    $password = NULL;

    if (!doesUserExist($c)) {
            // prepare and bind
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $firstname, $lastname, $username, $password);

            // set parameters and execute
            $firstname = $a;
            $lastname = $b;
            $username = $c;
            $password = password_hash($d, PASSWORD_DEFAULT);
            $stmt->execute();

            $_SESSION["phase"] = 0;

            $stmt->close();
        }else {
        $_SESSION["ERROR"] = 1;
        $_SESSION["phase"] = 1;
    }
}

function doesUserExist($username) {
    global $conn;
    $sql = "SELECT id FROM users WHERE username=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $user = $result->fetch_assoc(); // fetch data
    if (isset($user)) {
        return true;
    } else {
        return false;
    }
}

function authenticate($username, $password) {
    global $conn;
    $sql = "SELECT id, password FROM users WHERE username=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $hash = $result->fetch_assoc(); // fetch data
    if (isset($hash) && password_verify($password, $hash["password"])) {
        $_SESSION["user"] = $hash["id"];
        $_SESSION["username"] = $username;
        return true;
    }
}

function changeUserData($label, $data) {
    global $conn;
    $sql = "UPDATE users set $label=? WHERE id=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $data, $_SESSION["user"]);
    $stmt->execute();
    $stmt->close();
    $_SESSION["successful"] = "You successfully changed your $label!";
}

function navBar() {
    ?>
    <div id="navBar">
        <div id="navBarDropdown">
            <button id="menu-button"><img id="menu-icon" src="pictures\menu-icon.png"></button>
            <div id="navBarDropdown-dropdown">
                <form method="post" action="add_grade.php" id="add_grade-form">
                    <input type="hidden" name="phase" value="4">
                    <input type="submit" value="Add Grade" id="add_grade-button"><br>
                </form>
                <form method="post" action="delete_grade.php" id="delete_grade-form">
                    <input type="hidden" name="phase" value="6">
                    <input type="submit" value="Delete Grade" id="delete_grade-button"><br>
                </form>
                <form method="post" action="whatGradeDoIHaveToGet.php" id="whatGradeDoIHaveToGet-form">
                    <input type="submit" value="What grade do I have to get?" id="whatGradeDoIHaveToGet-button"><br>
                </form>
                <form method="post" action="suggestions.php" id="suggestions-form">
                    <input type="submit" value="suggestions?" id="suggestions-button"><br>
                </form>
            </div>
        </div>  
        <form method="post" action="index.php" id="home-form">
          <button id="home-button"><img id="home-icon" src="pictures\home-icon.png"></button>
        </form>
        <form method="post" action="index.php" id="logout-form">
          <input type="hidden" name="logout" value="true">
          <button id="logout-button"><img id="logout-icon" src="pictures\logout-icon.png"></button>
        </form>
        <?php
        if (isset($_POST["logout"]) && $_POST["logout"] == "true") {
          $_SESSION["user"] = NULL;
          $_SESSION["username"] = NULL;
          $_SESSION["phase"] = NULL;
          header("Location: login.php");
        }
        ?>
        <form method="post" action="settings.php" id="settings-form">
            <button id="settings-button"><img id="settings-icon" src="pictures\settings-icon.png"></button>
        </form>
    </div>
        <?php
}

function pullData($tablename, $id) {
    global $conn;
    $sql = "SELECT id FROM $tablename WHERE user=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_assoc(); // fetch data
    return $data;
}

function addSubject($a) {
    global $conn;
    $sql = "SELECT subject FROM subjects WHERE user=? AND subject=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $_SESSION["user"], $a);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_assoc(); // fetch data
    if ($data == NULL) {
        $user = NULL;
        $subject = NULL;
        
        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO subjects (user, subject) VALUES (?, ?)");
        $stmt->bind_param("ss", $user, $subject);

        // set parameters and execute
        $user = $_SESSION["user"];
        $subject = $a;
        $stmt->execute();

        $stmt->close();
        $_SESSION["successful"] = "You successfully added a new subject!";
        $_SESSION["phase"] = 3;
    }
}

function addGrade($a, $b, $c, $d, $e) {
    global $conn;
    $sql = "SELECT subject FROM subjects WHERE user=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row["subject"];
    }
    if (in_array($a, $subjects)) {
        $user = NULL;
        $subject = NULL;
        $semester = NULL;
        $grade = NULL;
        $description = NULL;
        $weight = NULL;

        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO grades (user, subject, semester, grade, description, weight) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $user, $subject, $semester, $grade, $description, $weight);

        // set parameters and execute
        $user = $_SESSION["user"];
        $subject = $a;
        $semester = $b;
        $grade = $c;
        $description = $d;
        $weight = $e;
        $stmt->execute();

        $stmt->close();
        $_SESSION["successful"] = "You successfully added a new grade!";
        $_SESSION["phase"] = 4;
    }
}

function subjectList() {
    global $conn;
    $sql = "SELECT subject FROM subjects WHERE user=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    while ($row = $result->fetch_assoc()) {
        if (isset($_GET["subject"]) && $_GET["subject"] == $row["subject"]) {
            echo "<option value=\"" . $row["subject"] . "\" selected>" . $row["subject"] . "</option>";
        } else {
            echo "<option value=\"" . $row["subject"] . "\">" . $row["subject"] . "</option>";
        }
    }
}

function mainGradeOverview() {
    global $conn;
    $sql = "SELECT subject FROM grades WHERE user=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row["subject"];
    }
    $subjects = array_unique($subjects);
    $sql = "SELECT semester FROM grades WHERE user=? ORDER BY semester DESC"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    while ($row = $result->fetch_assoc()) {
        $semesters[] = $row["semester"];
    }
    $maxSemester = $semesters[0];
    echo "<div class=\"gradeOverviewTable\">\n";
    echo "<table>\n";
    echo "<tr>\n";
    echo "<th class=\"firstColum\">Subject</th>\n";
    for ($x = 1; $x <= $maxSemester; $x++) {
        echo "<th class=\"semesterGrades\">Semester " . $x . "</th>\n";
    }
    echo "<th>Average</th>\n";
    echo "</tr>\n";
    $grade = array();
    foreach ($subjects as $subject) {
        echo "<tr>";
        echo "<td class=\"firstColum\"><b>" . $subject . "<b></td>";
        for ($x = 1; $x <= $maxSemester; $x++) {
            $sql = "SELECT semester, grade, weight FROM grades WHERE user=? AND subject=? AND semester=?"; // SQL with parameters
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("ssi", $_SESSION["user"], $subject, $x);
            $stmt->execute();
            $result = $stmt->get_result(); // get the mysqli result
            $data = $result->fetch_all(MYSQLI_ASSOC);
            if (isset($data[0])) {
                foreach ($data as $row) {
                    if (isset($grade[$subject][$x]["grade"])) {
                        $grade[$subject][$x]["grade"] += $row["grade"]*$row["weight"];
                        $grade[$subject][$x]["weight"] += $row["weight"];
                    } else {
                        $grade[$subject][$x]["grade"] = $row["grade"]*$row["weight"];
                        $grade[$subject][$x]["weight"] = $row["weight"];
                    }
                }
                if (isset($grade[$subject][$x-1]["grade"])) {
                    $grade[$subject][$x]["grade"] += $grade[$subject][$x-1]["grade"];
                    $grade[$subject][$x]["weight"] += $grade[$subject][$x-1]["weight"];
                    $totalGrade = $grade[$subject][$x]["grade"];
                    $totalWeight = $grade[$subject][$x]["weight"];
                } else {
                $totalGrade = $grade[$subject][$x]["grade"];
                $totalWeight = $grade[$subject][$x]["weight"];
                }
                $averageGrade = $totalGrade/$totalWeight;
                if ($x == $maxSemester) {
                    echo "<td>" . round($averageGrade, 1) . "</td>\n";
                }
                echo "<td class=\"semesterGrades\">" . round($averageGrade, 1) . "</td>\n";
            } elseif (isset($grade[$subject][$x-1]["grade"])){
                $grade[$subject][$x]["grade"] = $grade[$subject][$x-1]["grade"];
                $grade[$subject][$x]["weight"] = $grade[$subject][$x-1]["weight"];
                $totalGrade = $grade[$subject][$x-1]["grade"];
                $totalWeight = $grade[$subject][$x-1]["weight"];
                $averageGrade = $totalGrade/$totalWeight;
                echo "<td class=\"semesterGrades\">" . round($averageGrade, 1) . "</td>\n";
                if ($x == $maxSemester) {
                    echo "<td>" . round($averageGrade, 1) . "</td>\n";
                }
            } else {
                echo "<td class=\"semesterGrades\">-</td>\n";
                if ($x == $maxSemester) {
                    echo "<td>-</td>\n";
                }
            }
        }
        echo "</tr>";
    }
    echo "<tr id=\"lastRow\">";
    echo "<td class=\"firstColum\"><b>Average</b></td>";
    for ($x = 1; $x <= $maxSemester; $x++) {
        $count = 0;
        $averageGrade = NULL;
        foreach ($subjects as $subject) {
            if (isset($grade[$subject][$x]["grade"])) {
                $totalGrade = $grade[$subject][$x]["grade"];
                $totalWeight = $grade[$subject][$x]["weight"];
                $averageGrade += $totalGrade/$totalWeight;
                $count++;
            }
        }
        if ($count != 0) {
            echo "<td class=\"semesterGrades\">" . round($averageGrade/$count, 1) . "</td>\n";
            if ($x == $maxSemester) {
                echo "<td>" . round($averageGrade/$count, 1) . "</td>\n";
            }
        } else {
            echo "<td class=\"semesterGrades\">-</td>\n";
        }
    }
    echo "</tr>\n";
    echo "</table>\n";
    echo "</div>\n";
    //print_r($grade);
}

function subjectGradeOverview($subject) {
    global $conn;
    $sql = "SELECT subject FROM subjects WHERE user=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row["subject"];
    }
    if (in_array($subject, $subjects)) {
        $sql = "SELECT semester FROM grades WHERE user=? ORDER BY semester DESC"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $_SESSION["user"]);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        while ($row = $result->fetch_assoc()) {
            $semesters[] = $row["semester"];
        }
        $maxSemester = $semesters[0];
        echo "<div class=\"gradeOverviewTable\">\n";
        echo "<table>\n";
        echo "<tr>";
        echo "<th class=\"firstColum\"></th>";
        for ($x = 1; $x <= $maxSemester; $x++) {
            echo "<th>Semester " . $x . "</th>";
            echo "<th></th>";
            echo "<th></th>";
        }
        echo "</tr>\n";
        echo "<tr>";
        echo "<th class=\"firstColum secondHeader\"></th>";
        for ($x = 1; $x <= $maxSemester; $x++) {
            echo "<th class=\"secondHeader\">Description</th>";
            echo "<th class=\"secondHeader\">Grade</th>";
            echo "<th class=\"secondHeader\">Weight</th>";
        }
        echo "</tr>\n";
        $y = 0;
        do {
            echo "<tr>";
            $print = false;
            echo "<td class=\"firstColum\">" . ($y + 1) . "</td>";
            for ($x = 1; $x <= $maxSemester; $x++) {
                $sql = "SELECT description, grade, weight FROM grades WHERE user=? AND subject=? AND semester=?"; // SQL with parameters
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("ssi", $_SESSION["user"], $subject, $x);
                $stmt->execute();
                $result = $stmt->get_result(); // get the mysqli result
                $data = $result->fetch_all(MYSQLI_ASSOC);
                if (isset($data[$y])) {
                    echo "<td>" . $data[$y]["description"] . "</td>";
                    echo "<td>" . round($data[$y]["grade"], 1) . "</td>";
                    echo "<td>" . $data[$y]["weight"] . "</td>";
                    $print = true;
                } else {
                    echo "<td>-</td>";
                    echo "<td>-</td>";
                    echo "<td>-</td>";
                }
            }
            $y++;
            echo "</tr>\n";
        } while ($print);
        echo "<tr id=\"lastRow\">";
            echo "<td class=\"firstColum\">Average</td>";
            for ($x = 1; $x <= $maxSemester; $x++) {
                $sql = "SELECT grade, weight FROM grades WHERE user=? AND subject=? AND semester=?"; // SQL with parameters
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("ssi", $_SESSION["user"], $subject, $x);
                $stmt->execute();
                $result = $stmt->get_result(); // get the mysqli result
                $data = $result->fetch_all(MYSQLI_ASSOC);
                if (isset($data[0]["grade"])) {
                    foreach ($data as $row) {
                        if (isset($totalGrade[$x]) && isset($totalWeight[$x])) {
                            $totalGrade[$x] += $row["grade"]*$row["weight"];
                            $totalWeight[$x] += $row["weight"];
                        } else {
                            $totalGrade[$x] = $row["grade"]*$row["weight"];
                            $totalWeight[$x] = $row["weight"];
                        }
                    }
                    if (isset($totalGrade[$x-1]) && isset($totalWeight[$x-1])) {
                        $totalGrade[$x] += $totalGrade[$x-1];
                        $totalWeight[$x] += $totalWeight[$x-1];
                    }
                    echo "<td></td>";
                    echo "<td>" . round($totalGrade[$x]/$totalWeight[$x], 1) . "</td>";
                    echo "<td></td>";
                } else {
                    if (isset($totalGrade[$x-1]) && isset($totalWeight[$x-1])) {
                        $totalGrade[$x] = $totalGrade[$x-1];
                        $totalWeight[$x] = $totalWeight[$x-1];
                        echo "<td></td>";
                        echo "<td>" . round($totalGrade[$x]/$totalWeight[$x], 1) . "</td>";
                        echo "<td></td>";
                    } else {
                        echo "<td></td>";
                        echo "<td>-</td>";
                        echo "<td></td>";
                    }
                }
            }
        echo "</tr>\n";
        echo "</table>\n";
        echo "</div>\n";
    }
}

function chart() {
    global $conn;
    $sql = "SELECT subject FROM grades WHERE user=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row["subject"];
    }
    $subjects = array_unique($subjects);
    $sql = "SELECT semester FROM grades WHERE user=? ORDER BY semester DESC"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    while ($row = $result->fetch_assoc()) {
        $semesters[] = $row["semester"];
    }
    $maxSemester = $semesters[0];
    $grade = array();
    $dataPoints = array();
    foreach ($subjects as $subject) {
        for ($x = 1; $x <= $maxSemester; $x++) {
            $sql = "SELECT semester, grade, weight FROM grades WHERE user=? AND subject=? AND semester=?"; // SQL with parameters
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("ssi", $_SESSION["user"], $subject, $x);
            $stmt->execute();
            $result = $stmt->get_result(); // get the mysqli result
            $data = $result->fetch_all(MYSQLI_ASSOC);
            if (isset($data[0])) {
                foreach ($data as $row) {
                    if (isset($grade[$subject][$x]["grade"])) {
                        $grade[$subject][$x]["grade"] += $row["grade"]*$row["weight"];
                        $grade[$subject][$x]["weight"] += $row["weight"];
                    } else {
                        $grade[$subject][$x]["grade"] = $row["grade"]*$row["weight"];
                        $grade[$subject][$x]["weight"] = $row["weight"];
                    }
                }
                if (isset($grade[$subject][$x-1]["grade"])) {
                    $grade[$subject][$x]["grade"] += $grade[$subject][$x-1]["grade"];
                    $grade[$subject][$x]["weight"] += $grade[$subject][$x-1]["weight"];
                    $totalGrade = $grade[$subject][$x]["grade"];
                    $totalWeight = $grade[$subject][$x]["weight"];
                } else {
                $totalGrade = $grade[$subject][$x]["grade"];
                $totalWeight = $grade[$subject][$x]["weight"];
                }
                $averageGrade = $totalGrade/$totalWeight;
                $dataPoints[$subject][] = array("x" => $x, "y" => round($averageGrade, 1));
            } elseif (isset($grade[$subject][$x-1]["grade"])){
                $grade[$subject][$x]["grade"] = $grade[$subject][$x-1]["grade"];
                $grade[$subject][$x]["weight"] = $grade[$subject][$x-1]["weight"];
                $totalGrade = $grade[$subject][$x-1]["grade"];
                $totalWeight = $grade[$subject][$x-1]["weight"];
                $averageGrade = $totalGrade/$totalWeight;
                $dataPoints[$subject][] = array("x" => $x, "y" => round($averageGrade, 1));
            }
        }
    }
    for ($x = 1; $x <= $maxSemester; $x++) {
        $count = 0;
        $averageGrade = NULL;
        foreach ($subjects as $subject) {
            if (isset($grade[$subject][$x]["grade"])) {
                $totalGrade = $grade[$subject][$x]["grade"];
                $totalWeight = $grade[$subject][$x]["weight"];
                $averageGrade += $totalGrade/$totalWeight;
                $count++;
            }
        }
        if ($count != 0) {
            $dataPoints["Average"][] = array("x" => $x, "y" => round($averageGrade/$count, 1));
        }
    }
    ?>
    <script>
    window.onload = function () {
    
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        exportEnabled: true,

        theme: "dark2",

        title:{
            text: "Grade Overview Chart"
        },
        axisY: {
            title: "Grade",
        },
        axisX: {
            includeZero: true,
            interval: 1,
            prefix: "Semester "
        },
        legend:{
            cursor: "pointer",
            itemclick: toggleDataSeries
        },
        data: [
            <?php
            $count = 0;
            foreach ($dataPoints as $key => $dataPoint) {
                $count++;
                if (count($dataPoints) == $count) {
                    ?>
            {
                type: "spline",
                name: <?php echo "\"" . $key . "\"" ?>,
                markerSize: 10,
                lineThickness: 3,
                color: "red",
                showInLegend: true,
                toolTipContent: "{name}:<br>Semester {x}: {y}",
                dataPoints: <?php echo json_encode($dataPoint, JSON_NUMERIC_CHECK); ?>
            },
            <?php
                } else {
            ?>
            {
                type: "spline",
                name: <?php echo "\"" . $key . "\"" ?>,
                markerSize: 5,
                showInLegend: true,
                toolTipContent: "{name}:<br>Semester {x}: {y}",
                dataPoints: <?php echo json_encode($dataPoint, JSON_NUMERIC_CHECK); ?>
            },
            <?php }}
        ?>]
    });
    
    chart.render();

    function toggleDataSeries(e){
        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        }
        else{
            e.dataSeries.visible = true;
        }
        chart.render();
    }
    
    }
    </script>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script type="text/javascript" src="canvasjs.min.js"></script>
    <?php
}

function deleteGradeTable() {
    global $conn;
    $sql = "SELECT id, subject, semester, grade, description, weight FROM grades WHERE user=? ORDER BY semester ASC, subject"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_all(MYSQLI_ASSOC); // fetch data
    echo "<input type=\"text\" id=\"myInput\" onkeyup=\"search('gradesTable')\" placeholder=\"Search for grades..\">\n";
    echo "<div id=\"deleteTable\">";
    echo "<table id=\"gradesTable\">\n";
    echo "<tr>";
    echo "<th onclick=sortTable(0,\"gradesTable\")>subject</th>";
    echo "<th onclick=sortTable(1,\"gradesTable\")>semester</th>";
    echo "<th onclick=sortTable(2,\"gradesTable\")>grade</th>";
    echo "<th onclick=sortTable(3,\"gradesTable\")>description</th>";
    echo "<th onclick=sortTable(4,\"gradesTable\")>weight</th>";
    echo "<th></th>";
    echo "</tr>\n";
    foreach ($data as $inf) {
        echo "<tr>";
        echo "<td>" . $inf["subject"] . "</td>";
        echo "<td>" . $inf["semester"] . "</td>";
        echo "<td>" . round($inf["grade"], 1) . "</td>";
        echo "<td>" . $inf["description"] . "</td>";
        echo "<td>" . $inf["weight"] . "</td>";
        echo "<td><input type=\"image\" src=\"pictures/trash_bin.png\" class=\"trash_bin\" class=\"myBtn\" onclick=modalButtonDelete(" . $inf["id"] . ")></td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "</div";
}

function deleteSubjectTable() {
    global $conn;
    $sql = "SELECT id, subject FROM subjects WHERE user=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $_SESSION["user"]);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_all(MYSQLI_ASSOC); // fetch data
    echo "<input type=\"text\" id=\"myInput\" onkeyup=\"search('subjectsTable')\" placeholder=\"Search for subjects..\">\n";
    echo "<div id=\"deleteTable\">";
    echo "<table id=\"subjectsTable\">\n";
    echo "<tr>";
    echo "<th onclick=\"sortTable(0,'subjectsTable')\">subject</th>";
    echo "<th></th>";
    echo "</tr>\n";
    foreach ($data as $inf) {
        echo "<tr>";
        echo "<td>" . $inf["subject"] . "</td>";
        echo "<td><input type=\"image\" src=\"pictures/trash_bin.png\" class=\"trash_bin\" class=\"myBtn\" onclick=modalButtonDelete(" . $inf["id"] . ")></td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "</div>";
}

function deleteGrade($id) {
    global $conn;
    $sql = "SELECT user FROM grades WHERE id=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_all(MYSQLI_ASSOC); // fetch data
    if ($data[0]["user"] == $_SESSION["user"]) {
        $sql = "DELETE FROM grades WHERE id=?"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $_SESSION["successful"] = "You successfully deleted a grade!";
        $_SESSION["phase"] = 6;
    }
}

function deleteSubject($id) {
    global $conn;
    $sql = "SELECT user, subject FROM subjects WHERE id=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_all(MYSQLI_ASSOC); // fetch data
    if ($data[0]["user"] == $_SESSION["user"]) {
        $sql = "DELETE FROM subjects WHERE id=?"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $sql = "DELETE FROM grades WHERE subject=?"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $data[0]["subject"]);
        $stmt->execute();
        $_SESSION["successful"] = "You successfully deleted a subject!";
        $_SESSION["phase"] = 5;
    }
}

function whatGradeDoIHaveToGet($subject, $grade, $weight) {
    global $conn;
    $sql = "SELECT user FROM subjects WHERE subject=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $subject);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_all(MYSQLI_ASSOC); // fetch data
    if ($data[0]["user"] == $_SESSION["user"]) {
        $sql = "SELECT grade, weight FROM grades WHERE subject=?"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $subject);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        $data = $result->fetch_all(MYSQLI_ASSOC); // fetch data
        $totalWeight = NULL;
        $totalGrade = NULL;
        foreach ($data as $row) {
            $totalGrade += $row["grade"]*$row["weight"];
            $totalWeight += $row["weight"];
        }
        $_SESSION["whatGradeDoIHaveToGet"] = ($grade*($weight+$totalWeight)-$totalGrade)/$weight;
    }
}

function successful($message) {
    ?>
    <!-- The Modal -->
    <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <img id="checkmark" src="pictures/checkmark.png">
                <h1><?php echo $message; ?></h1>
            </div>
        </div>
        <script src="popup.js"></script>
    <?php
    $_SESSION["successful"] = NULL;
    echo "<script>modalButton()</script>";
}

function addSuggestion($data) {
    global $conn;
    $suggestion = NULL;
    $user = NULL;
        
    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO suggestions (user, suggestion) VALUES (?, ?)");
    $stmt->bind_param("is", $user, $suggestion);

    // set parameters and execute
    $user = $_SESSION["user"];
    $suggestion = $data;
    $stmt->execute();

    $stmt->close();
    $_SESSION["successful"] = "You successfully added a suggestion!";
}

function suggestionsTable() {
    global $conn;
    $sql = "SELECT user, suggestion FROM suggestions"; // SQL with parameters
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $data = $result->fetch_all(MYSQLI_ASSOC); // fetch data
    if (isset($data)) {
        echo "<div class=\"suggestionsTable\">\n";
        echo "<table>\n";
        echo "<tr>";
        echo "<th class=\"firstColum\">user</th>";
        echo "<th>suggestion</th>";
        echo "</tr>";
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td class=\"firstColum\">" . $row["user"] . "</td>";
            echo "<td>" . $row["suggestion"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }
}
?>