<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $error = "Username sudah digunakan.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - To Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="box"> 
        <h2 style="margin-bottom: 20px;">Registrasi</h2>
        <form class="form-inline" method="post" action="registrasi.php" style="flex-direction: column;">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="new-password">

            <button type="submit">Daftar</button>

            <?php if (isset($error)) : ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </form>
        <p style="margin-top: 10px;">Sudah punya akun? <a href="index.php">Login</a></p>
    </div>
</body>
</html>


