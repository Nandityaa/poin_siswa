<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <center>
        <h2>Login</h2>
        <form action="process/login_process.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username / NIS" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Login</button>
            <br><br>
            <a href="pages/forgot_password.php" style="text-decoration: none; font-size: 14px; color: #007bff;">Lupa Password?</a>
        </form>
    </center>
</body>
</html>