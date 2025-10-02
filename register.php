<?php
include('connection.php');
session_start();

//session check: only admin is allowed here
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: login.php");
    exit();
}

//user input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $name = htmlentities(trim($_POST['name']??''));
    $surname = htmlentities(trim($_POST['surname']??''));
    $identity_number = filter_input(INPUT_POST, 'identity_number', FILTER_VALIDATE_INT);
    $date_of_birth = htmlentities(trim($_POST['date_of_birth']??''));
    $course = htmlentities(trim($_POST['course']??''));
    $enrollment_date = htmlentities(trim($_POST['enrollment_date']??''));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    // generating password: ID number + first letter of name + first letter of surname
$password = $identity_number . strtoupper(substr($name, 0, 1)) . strtoupper(substr($surname, 0, 1));

if($action === 'Register Student' && $name && $surname && $identity_number && $date_of_birth && $course && $enrollment_date && $email){



    $stmt1 = $pdo->prepare("INSERT INTO students (name, surname, identity_number, date_of_birth, course, enrollment_date, email) VALUES (?,?,?,?,?,?,?)");
    $stmt1->execute([$name, $surname, $identity_number, $date_of_birth, $course, $enrollment_date, $email]);

    $stmt2 = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt2->execute([$email, $password, 'Student' ]);

    echo  '<script>
         alert("New student added successfull")
        </script>';

    } else {
     echo   '<script>
         alert("Operation unsuccessful")
        </script>';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Register New Student</h1>
        <br><br>
        <form method="POST">
            <input type="text" id="name" name="name" placeholder="Name" required>
            <br><br>
            <input type="text" id="surname" name="surname" placeholder="Surname" required>
            <br><br>
            <input type="number" id="identity_number" name="identity_number" placeholder="Identity Number" required>
            <br><br>
            <label for="date_of_birth">Date of Birth </label>
            <br>
            <input type="date" id="date_of_birth" name="date_of_birth"  required>
            <br><br>
            <select id="course" name="course" required>
            <option value="" disabled selected>Select Course</option>
            <option value="Computer Science">Computer Science</option>
            <option value="Engineering">Engineering</option>
            <option value="Business">Business</option>
            <option value="Medicine">Medicine</option>
            <option value="Law">Law</option>
            <option value="Arts">Arts</option>
            <option value="Science">Science</option>
            <option value="Education">Education</option>
        </select>
        <br><br>
        <label for="enrollment_date">Enrollment Date </label>
        <br>
        <input type="date" id="enrollment_date" name="enrollment_date" required>
        <br><br>
        <input type="email" id="email" name="email" placeholder="Email Address" required>
        <br><br>
        <input type="submit" name="action" value="Register Student">
        <input type="reset" name="action" value="Reset">



        </form>

</body>
</html>