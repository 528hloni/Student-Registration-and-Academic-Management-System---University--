<?php 
include('connection.php');
session_start();


//user input
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']??'');
    $role = htmlentities(trim($_POST['role']??''));


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
                         $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['loggedin'] = true;
                        header('Location: profile.php');
                        exit();
                    }

                }else{ //alert if password is incorrect
                    echo '<script>  
                    alert("Login failed, Invalid email,password or role 111!!! ")
                    </script>';
                }
            
            } else { //alert if user not found
     echo '<script>  
    alert("Login failed, Invalid email, password or role 222!!!")
    </script>';
    header("Location: ".$_SERVER['PHP_SELF']); 
        exit();
}

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University</title>
</head>
<body>
    <h1>University Login</h1>
    <form method="POST">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="role">Role</label>
        <select id="role" name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="Administrator">Administrator</option>
            <option value="Student">Student</option>
        </select>
        <br>
        <input type="submit" name="action" value="Login">




    </form>
    
</body>
</html>




