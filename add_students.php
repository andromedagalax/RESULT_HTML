<?php
include('init.php');
include('session.php');

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['student_name']);
    $rno = trim($_POST['roll_no']);
    $class_name = isset($_POST['class_name']) ? trim($_POST['class_name']) : '';

    // Validation
    $errors = [];
    if (empty($name)) $errors[] = 'Please enter name';
    if (empty($rno)) $errors[] = 'Please enter your roll number';
    if (empty($class_name)) $errors[] = 'Please select your class';
    if (!empty($rno) && preg_match("/[a-z]/i", $rno)) $errors[] = 'Please enter valid roll number';
    if (!empty($name) && !preg_match("/^[a-zA-Z ]*$/", $name)) $errors[] = 'No numbers or symbols allowed in name';

    if ($errors) {
        foreach ($errors as $error) {
            echo '<p class="error">'.htmlspecialchars($error).'</p>';
        }
    } else {
        // Check if class exists
        $class_check_stmt = $conn->prepare("SELECT `name` FROM `class` WHERE `name` = ?");
        $class_check_stmt->bind_param("s", $class_name);
        $class_check_stmt->execute();
        $class_check_stmt->store_result();
        
        if ($class_check_stmt->num_rows > 0) {
            // Class exists, proceed with insertion
            $stmt = $conn->prepare("INSERT INTO `students` (`name`, `rno`, `class_name`) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $rno, $class_name);
            
            if ($stmt->execute()) {
                echo '<script>alert("Successful");</script>';
            } else {
                echo '<script>alert("Invalid Details");</script>';
            }
            
            $stmt->close();
        } else {
            echo '<p class="error">Invalid class name selected.</p>';
        }
        
        $class_check_stmt->close();
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
    <link rel="stylesheet" type="text/css" href="./css/form.css" media="all">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome-4.7.0/css/font-awesome.css">
    <title>Add Students</title>
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
                <a href="" class="dropbtn">Classes &nbsp
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-content" id="1">
                    <a href="add_classes.php">Add Class</a>
                    <a href="manage_classes.php">Manage Class</a>
                </div>
            </li>
            <li class="dropdown" onclick="toggleDisplay('2')">
                <a href="#" class="dropbtn">Students &nbsp
                    <span class="fa fa-angle-down"></span>
                </a>
                <div class="dropdown-content" id="2">
                    <a href="add_students.php">Add Students</a>
                    <a href="manage_students.php">Manage Students</a>
                </div>
            </li>
            <li class="dropdown" onclick="toggleDisplay('3')">
                <a href="#" class="dropbtn">Results &nbsp
                    <span class="fa fa-angle-down"></span>
                </a>
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
                <legend>Add Student</legend>
                <input type="text" name="student_name" placeholder="Student Name" value="<?php echo isset($_POST['student_name']) ? htmlspecialchars($_POST['student_name']) : ''; ?>">
                <input type="text" name="roll_no" placeholder="Roll No" value="<?php echo isset($_POST['roll_no']) ? htmlspecialchars($_POST['roll_no']) : ''; ?>">
                <?php
                    $class_result=mysqli_query($conn,"SELECT `name` FROM `class`");
                        echo '<select name="class_name">';
                        echo '<option selected disabled>Select Class</option>';
                    while($row = mysqli_fetch_array($class_result)){
                        $display=$row['name'];
                        $selected = (isset($_POST['class_name']) && $_POST['class_name'] == $display) ? 'selected' : '';
                        echo '<option value="'.$display.'" '.$selected.'>'.$display.'</option>';
                    }
                    echo'</select>';
                ?>
                <input type="submit" value="Submit">
            </fieldset>
        </form>
    </div>

    <div class="footer">
        <!-- <span>&copy Designed & Coded By Jibin Thomas</span> -->
    </div>
</body>
</html>