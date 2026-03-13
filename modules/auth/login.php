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
        <form action="process.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username / NIS" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required><br>
            <div style="margin-top: 5px; margin-bottom: 15px; text-align: left; display: inline-block; width: 170px;">
                <input type="checkbox" id="show_password" onclick="togglePassword()" style="cursor: pointer;">
                <label for="show_password" style="font-size: 13px; cursor: pointer; user-select: none;">Tampilkan Password</label>
            </div>
            <br>
            <button type="submit">Login</button>

            <script>
            function togglePassword() {
                var pwdInput = document.getElementById("password");
                if (pwdInput.type === "password") {
                    pwdInput.type = "text";
                } else {
                    pwdInput.type = "password";
                }
            }
            </script>
            <br><br>
            <a href="/poin_siswa/modules/auth/forgot_password.php" style="text-decoration: none; font-size: 14px; color: #007bff;">Lupa Password?</a>
        </form>
    </center>
</body>
</html>