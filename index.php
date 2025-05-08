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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="box"> 
        <h2 style="margin-bottom: 20px;">Login</h2>
        <form class="form-inline" method="post" action="index.php" style="flex-direction: column;">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="new-password">
                <span class="toggle-password" onclick="togglePassword()">
                    <i class="fa-solid fa-eye-slash" id="toggleIcon"></i>
                </span>
            </div>
            <button type="submit">Login</button>

            <?php if (isset($error)) : ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </form>
        <p style="margin-top: 10px;">Belum punya akun? <a href="registrasi.php">Daftar</a></p>
    </div>
    <script src="script.js"></script>
</body>
</html>


