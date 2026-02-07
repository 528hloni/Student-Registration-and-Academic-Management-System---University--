<?php 
include('connection.php');
session_start();




//user input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']??'');
    $role = htmlentities(trim($_POST['role']??''));

    try{


    if ($action === 'Login' && $email && $password && $role){ //checking if button Login was clicked and all inputs are filled
         $sql = "SELECT * FROM users WHERE email = ? AND role = ?"; // query to find user with matching rmail and role
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $role]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

           

            //if matching user is found then compare passwords(input and database)
            if ($user) {

                

                //redirects user based on their role
                if ($password == $user['password']) {    
                    if ($role == ('Administrator')){
                         $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['loggedin'] = true;
                        header('Location: dashboard.php');
                        exit();
                    }
                    elseif ($role == ('Student')){
                   
                        $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ?");
                        $stmt->execute([$email]);
                        $student = $stmt->fetch(PDO::FETCH_ASSOC);
                        $student_id = $student['student_id'];



                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['loggedin'] = true;
                         header('Location: profile.php?student_id=' . $student_id);
                        exit();
                    }

                }else{ //alert if password is incorrect
                    echo '<script>  
                    alert("Login failed, Invalid email,password or role! ")
                    </script>';
                }
            
            } else { //alert if user not found
     echo '<script>  
    alert("Login failed, Invalid email, password or role !")
    </script>';
   
}

    }

} catch (Exception $e) {
    // Handle general errors
    echo "Error: " . $e->getMessage();
}
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <h1>University Login</h1>
        <form method="POST">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="Administrator">Administrator</option>
                <option value="Student">Student</option>
            </select>

            <input type="submit" name="action" value="Login">
        </form>
    </div>
</body>
</html>


