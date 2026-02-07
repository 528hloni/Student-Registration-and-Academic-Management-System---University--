<?php
  
 include('connection.php');
session_start();

//session check: only admin is allowed here
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: index.php");
    exit();
}



try{

//Getting student_id from URL and validating it (using GET method)
if (isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {
    $student_id = trim($_GET['student_id']);

    // Fetching student data to prefill the form
 $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "Student not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}



//user input update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $name = htmlentities(trim($_POST['name']??''));
    $surname = htmlentities(trim($_POST['surname']??''));
    $identity_number = trim($_POST['identity_number'] ?? '');
    $date_of_birth = htmlentities(trim($_POST['date_of_birth']??''));
    $course = htmlentities(trim($_POST['course']??''));
    $enrollment_date = htmlentities(trim($_POST['enrollment_date']??''));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);


    

    if($action === 'Return To Dashboard'){
     header('Location: dashboard.php');
        exit();
}    

    // Update student table
    if($action === 'Update Student' && $name && $surname && $identity_number && $date_of_birth && $course && $enrollment_date && $email){
    try {
     // Update students table
    $stmt = $pdo->prepare("UPDATE students SET name = ?, surname = ?, identity_number = ?, date_of_birth = ?, 
                          course = ?, enrollment_date = ?, email = ? WHERE student_id = ?");
        $stmt->execute([$name, $surname, $identity_number, $date_of_birth, $course, $enrollment_date, $email, $student_id]);

        // Update users table (email might change)
        if ($email !== $student['email']){
           $stmt2 = $pdo->prepare("UPDATE users SET email = ? WHERE email = ?");
           $stmt2->execute([$email, $student['email']]);
        }

        

        // Show success message then redirect
       

 echo '<script>
        alert("Successfully updated student record");
        window.location.href = "dashboard.php";
       </script>';
        exit();

} catch (PDOException $e) {
  $errorMessage = "Database error: " . $e->getMessage();
                echo '<script>alert("' . $errorMessage . '");</script>';
            }

            } else {
            // Validation failed - show which fields are missing
            $missing = [];
            if (!$name) $missing[] = "Name";
            if (!$surname) $missing[] = "Surname";
            if (!$identity_number) {
                $missing[] = "Identity Number";
                } elseif (!preg_match('/^\d{13}$/', $identity_number)) {
                $missing[] = "Identity Number (must be exactly 13 digits)";
                }
            if (!$date_of_birth) $missing[] = "Date of Birth";
            if (!$course) $missing[] = "Course";
            if (!$enrollment_date) $missing[] = "Enrollment Date";
            if (!$email) $missing[] = "Email";
            
            $errorMsg = "Missing or invalid fields: " . implode(", ", $missing);
            echo '<script>alert("' . $errorMsg . '");</script>';
        }
 
        }
        } catch (Exception $e) {
    // General error handler
    echo "Error: " . $e->getMessage();
}

    


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
  <link rel="stylesheet" href="update.css">
</head>
<body>
    

<div class="container">
    <h1> Update Student : <?= htmlentities($student['name']) ?> <?= htmlentities($student['surname']) ?> </h1>
    <br><br>
        <br> <br>
        <form method="POST">
            <label for="name">Name </label>
            <input type="text" id="name" name="name" 
            value="<?= htmlentities($student['name']) ?>" required>
            <br><br>

            <label for="surname">Surname </label>
            <input type="text" id="surname" name="surname" 
            value="<?= htmlentities($student['surname']) ?>" required>
            <br><br>

            <label for="identity_number">Identity Number </label>
            <input type="number" id="identity_number" name="identity_number" 
            value="<?= htmlentities($student['identity_number']) ?>" required>
            <br><br>

            <label for="date_of_birth">Date of Birth </label>
            <input type="date" id="date_of_birth" name="date_of_birth"  
            value="<?= htmlentities($student['date_of_birth']) ?>" required>
            <br><br>

            <label for="course">Course </label>
            <select id="course" name="course" required>
    <option value="" disabled>Select Course</option>
    <?php
        $courses = [
            "Computer Science", "Engineering", "Business",
            "Medicine", "Law", "Arts", "Science", "Education"
        ];

        foreach ($courses as $c) {
            if ($student['course'] === $c) {
                // If this course matches the student's course, mark it as selected
                echo "<option value='$c' selected>$c</option>";
            } else {
                // Otherwise just show the option normally
                echo "<option value='$c'>$c</option>";
            }
        }
    ?>
</select>
        <br><br>

        <label for="enrollment_date">Enrollment Date </label>
        <input type="date" id="enrollment_date" name="enrollment_date" 
        value="<?= htmlentities($student['enrollment_date']) ?>" required>
        <br><br>

        <label for="email">Email </label>
        <input type="email" id="email" name="email"  
        value="<?= htmlentities($student['email']) ?>" required>
        <br><br>

        <input type="submit" name="action" value="Update Student">
        <input type="reset" name="action" value="Reset"> <br>
        <input type="submit" name="action" value="Return To Dashboard" formnovalidate>
</form>
    </div>
</body>
</html>