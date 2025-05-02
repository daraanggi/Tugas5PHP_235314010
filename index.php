<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    
    if ($stmt->fetch()) {
        if (password_verify($password, $hash)) {
            $_SESSION['user'] = $id;
            $_SESSION['username'] = $username;
            header("Location: todolist.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Akun tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - To Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="box"> 
        <h2 style="margin-bottom: 20px;">Login</h2>
        <form class="form-inline" method="post" action="index.php" style="flex-direction: column;">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">

            <button type="submit">Login</button>

            <?php if (isset($error)) : ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </form>
        <p style="margin-top: 10px;">Belum punya akun? <a href="registrasi.php">Daftar</a></p>
    </div>
</body>
</html>


