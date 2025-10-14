 <?php
  
 include('connection.php');
session_start();


if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'Administrator' && $_SESSION['role'] !== 'Student')) {
    header("Location: login.php");
    exit();
}


try{
$role = $_SESSION['role'];


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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action ==='Logout'){
        session_destroy();
        header('Location: login.php');
        exit();
    }
}

} catch (Exception $e) {
    // Handle general errors
    echo "Error: " . $e->getMessage();
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

<form method="POST">
    <input type="submit" name="action" value="Logout">
</form>    



   <h1> Student Profile : <?= htmlentities($student['name']) ?> <?= htmlentities($student['surname']) ?> </h1> 


   <p><strong>Student ID:</strong> <?= htmlentities($student['student_id']) ?></p>
   <p><strong>Email:</strong> <?= htmlentities($student['email']) ?></p>
   <p><strong>Active</strong></p>
   <br>
   <p><strong>Date Of Birth</strong> <br> 
   <?= htmlentities($student['date_of_birth']) ?></p>
   <p><strong>Course Of Study</strong> <br>
   <?= htmlentities($student['course']) ?></p>
   <p><strong>Enrollment Date</strong> <br>
   <?= htmlentities($student['enrollment_date']) ?></p>
   <p><strong>Academic Status</strong> <br>
    Full Time Student</p>
<br> <br>

<?php if ($role === 'Administrator'): ?>
<button onclick="window.location.href='dashboard.php'">Return to Dashboard</button>
<?php endif; ?>


<?php if ($role === 'Student'): ?>
<hr>
<h2> Download Official Student Documents </h2>
<hr>
<br>
<h3> Profile Summary Report </h3>
<a href="generate_pdf.php?type=profile&id=<?php echo $student['student_id']; ?>">
<button>Download PDF</button>
</a>
<br><br>
<h3> Registration Confirmation </h3>
<a href="generate_pdf.php?type=registration&id=<?php echo $student['student_id']; ?>">
<button>Download PDF</button>
</a>
<?php endif; ?>


</body>
</html>