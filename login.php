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
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="role">Role</label>
        <select id="role" name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select>
        <br>
        <input type="submit" value="Login">




    </form>
    
</body>
</html>