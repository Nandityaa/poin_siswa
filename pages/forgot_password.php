<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
</head>
<body>
    <center>
        <h2>Reset Password</h2>
        <p>Masukkan Username (Guru) atau NIS (Siswa) untuk mereset password ke default.</p>
        <form action="../process/reset_password_process.php" method="post">
            <label for="username">Username / NIS:</label>
            <input type="text" id="username" name="username" placeholder="Username / NIS" required><br><br>
            <label for="new_password">Password Baru:</label>
            <input type="password" id="new_password" name="new_password" placeholder="Password Baru" required><br><br>
            <button type="submit">Reset Password</button>
        </form>
        <br>
        <a href="../login.php">Kembali ke Login</a>
    </center>
</body>
</html>
