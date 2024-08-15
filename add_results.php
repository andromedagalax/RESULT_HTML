<?php
include("init.php");
include("session.php");

if (isset($_POST['rno'], $_POST['SL'], $_POST['Eng'], $_POST['CS'], $_POST['Phy'], $_POST['Che'], $_POST['Maths'])) {
    $rno = $_POST['rno'];
    $class_name = isset($_POST['class_name']) ? $_POST['class_name'] : null;
    $p1 = (int)$_POST['SL'];
    $p2 = (int)$_POST['Eng'];
    $p3 = (int)$_POST['CS'];
    $p4 = (int)$_POST['Phy'];
    $p5 = (int)$_POST['Che'];
    $p6 = (int)$_POST['Maths'];

    $marks = $p1 + $p2 + $p3 + $p4 + $p5 + $p6;
    $percentage = $marks / 6;

    // validation
    $errors = [];
    if (empty($class_name)) $errors[] = 'Please select class';
    if (empty($rno)) $errors[] = 'Please enter roll number';
    if (preg_match("/[a-z]/i", $rno)) $errors[] = 'Please enter valid roll number';
    if ($p1 > 100 || $p2 > 100 || $p3 > 100 || $p4 > 100 || $p5 > 100 || $p1 < 0 || $p2 < 0 || $p3 < 0 || $p4 < 0 || $p5 < 0) {
        $errors[] = 'Please enter valid marks';
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<p class="error">' . $error . '</p>';
        }
        exit();
    }

    $name_query = mysqli_query($conn, "SELECT `name` FROM `students` WHERE `rno`='$rno' and `class_name`='$class_name'");
    if ($row = mysqli_fetch_array($name_query)) {
        $display = $row['name'];

        $sql = "INSERT INTO `result` (`name`, `rno`, `class`, `SL`, `Eng`, `CS`, `Phy`, `Che`, `Maths`, `marks`, `percentage`) 
                VALUES ('$display', '$rno', '$class_name', '$p1', '$p2', '$p3', '$p4', '$p5','$p6', '$marks', '$percentage')";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Successful")</script>';
        } else {
            echo '<script>alert("Invalid Details")</script>';
        }
    } else {
        echo '<p class="error">Student not found</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/home.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="./css/form.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="title">
        <a href="dashboard.php"><img src="./images/logo1.png" alt="" class="logo"></a>
        <span class="heading">Dashboard</span>
        <a href="logout.php" style="color: white"><span class="fa fa-sign-out fa-2x">Logout</span></a>
    </div>
    <div class="nav">
        <ul>
            <li class="dropdown" onclick="toggleDisplay('1')">
                <a href="" class="dropbtn">Classes &nbsp<span class="fa fa-angle-down"></span></a>
                <div class="dropdown-content" id="1">
                    <a href="add_classes.php">Add Class</a>
                    <a href="manage_classes.php">Manage Class</a>
                </div>
            </li>
            <li class="dropdown" onclick="toggleDisplay('2')">
                <a href="#" class="dropbtn">Students &nbsp<span class="fa fa-angle-down"></span></a>
                <div class="dropdown-content" id="2">
                    <a href="add_students.php">Add Students</a>
                    <a href="manage_students.php">Manage Students</a>
                </div>
            </li>
            <li class="dropdown" onclick="toggleDisplay('3')">
                <a href="#" class="dropbtn">Results &nbsp<span class="fa fa-angle-down"></span></a>
                <div class="dropdown-content" id="3">
                    <a href="add_results.php">Add Results</a>
                    <a href="manage_results.php">Manage Results</a>
                </div>
            </li>
        </ul>
    </div>
    <div class="main">
        <form action="" method="post">
            <fieldset>
                <legend>Enter Marks</legend>
                <select name="class_name">
                    <option selected disabled>Select Class</option>
                    <?php
                    $select_class_query = "SELECT `name` from `class`";
                    $class_result = mysqli_query($conn, $select_class_query);
                    while ($row = mysqli_fetch_array($class_result)) {
                        $display = $row['name'];
                        echo '<option value="' . $display . '">' . $display . '</option>';
                    }
                    ?>
                </select>
                <input type="text" name="rno" placeholder="Roll No">
                <input type="text" name="SL" placeholder="Second language">
                <input type="text" name="Eng" placeholder="English">
                <input type="text" name="CS" placeholder="Computer science">
                <input type="text" name="Phy" placeholder="Physics">
                <input type="text" name="Che" placeholder="Chemistry">
                <input type="text" name="Maths" placeholder="Mathematics">
                <input type="submit" value="Submit">
            </fieldset>
        </form>
    </div>
</body>
</html>