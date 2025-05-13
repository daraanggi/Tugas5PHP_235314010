<?php
session_start();
require 'koneksi.php';

//Mengecek apakah user login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user'];

//Menambah data
if (isset($_POST['tambah']) && !empty(trim($_POST['todo']))) {
    $todo = htmlspecialchars($_POST['todo']);
    $stmt = $conn->prepare("INSERT INTO todolist (user_id, teks) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $todo);
    $stmt->execute();
    header("Location: todolist.php");
    exit;
}

//Menandai selesai
if (isset($_GET['selesai'])) {
    $id = (int)$_GET['selesai'];
    $stmt = $conn->prepare("UPDATE todolist SET selesai = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    header("Location: todolist.php");
    exit;
}

//Menghapus
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM todolist WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    header("Location: todolist.php");
    exit;
}

//Paginasi
$items_per_page = 6;
$page = isset ($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

//Menghitung total data
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM todolist WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_items = $row['total'];
$total_pages = ceil($total_items / $items_per_page);

//Ambil data to-do dari database dengan paginasi
$stmt = $conn->prepare("SELECT id, teks, selesai FROM todolist WHERE user_id = ? LIMIT ? OFFSET ?");
$stmt->bind_param("iii", $user_id, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
$todolist = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>To Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-container">
        <header class="profil-header">
            <img src="dara.jpeg" alt="Foto Profil">
            <div class="profil-info">
                <h1>Dara Anggi Puspa</h1>
                <p>235314010</p>
                <p>Login sebagai: <?= htmlspecialchars($_SESSION['username']) ?></p>
            </div>
        </header>

        <div class="box">
            <form method="POST" class="form-inline">
                <input type="text" name="todo" placeholder="Teks to do" required>
                <button type="submit" name="tambah">Tambah</button>
            </form>

            <div class="todo-list">
                <?php foreach ($todolist as $item): ?>
                    <div class="todo-item">
                        <input type="text" value="<?= htmlspecialchars($item['teks']) ?>" readonly class="<?= $item['selesai'] ? 'done' : '' ?>">
                        <div class="buttons">
                            <?php if (!$item['selesai']): ?>
                                <a href="?selesai=<?= $item['id'] ?>" class="btn">Selesai</a>
                            <?php else: ?>
                                <span class="btn disabled">Selesai</span>
                            <?php endif; ?>
                            <a href="?hapus=<?= $item['id'] ?>" class="btn">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="footer-container">
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" class="btn">Sebelumnya</a>
                    <?php endif; ?>
                        
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="btn <?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>            
                        <a href="?page=<?= $page + 1 ?>" class="btn">Berikutnya</a>
                    <?php endif; ?>
                </div>
                
                <a href="logout.php" class="btn logout-btn">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>

