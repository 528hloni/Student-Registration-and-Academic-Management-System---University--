<?php
include('connection.php');
session_start();

//session check: only admin allowed
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: login.php");
    exit();
}

//check if ID is passed
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input

    //Fetch student info before deletion (Log deleted record information to a file)
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // Delete student securely
        $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt->execute([$id]);

        //Also delete from users table (so login no longer works)
        $stmt2 = $pdo->prepare("DELETE FROM users WHERE email = ?");
        $stmt2->execute([$student['email']]);

        //Log deleted record to a file
        $logFile = "deletions.log";
        $logEntry = date("Y-m-d H:i:s") . " - Deleted Student: "
                  . "ID: {$student['student_id']}, Name: {$student['name']} {$student['surname']}, "
                  . "Email: {$student['email']}, Course: {$student['course']}\n";

        file_put_contents($logFile, $logEntry, FILE_APPEND);

        //Redirect with  message
        echo '<script>
        alert("Successfully deleted student record");
        window.location.href = "dashboard.php";
       </script>';
        exit();
        
    } else {
    
        echo '<script>
        alert("Student not found");
        window.location.href = "dashboard.php";
       </script>';
    }
} else {
    
    echo '<script>
        alert("Invalid request");
        window.location.href = "dashboard.php";
       </script>';
}
?>