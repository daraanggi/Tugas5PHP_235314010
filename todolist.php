<?php
session_start();

//Cek apakah 'todolist' sudah ada di session. Jika belum, buat array kosong untuk menampung to-do list
if (!isset($_SESSION['todolist'])) {
    $_SESSION['todolist'] = [];
}

//Proses saat tombol 'Tambah' ditekan
if (isset($_POST['tambah']) && !empty(trim($_POST['todo']))) {
    $_SESSION['todolist'][] = ['teks' => htmlspecialchars($_POST['todo']), 'selesai' => false];
    header("Location: todolist.php");
    exit;
}

//Proses saat tombol 'Selesai' ditekan untuk menandai item sebagai selesai
if (isset($_GET['selesai'])) {
    $i = $_GET['selesai'];
    if (isset($_SESSION['todolist'][$i])) {
        $_SESSION['todolist'][$i]['selesai'] = true;
    }
    header("Location: todolist.php");
    exit;
}

//Proses saat tombol 'Hapus' ditekan untuk menghapus item dari to-do list
if (isset($_GET['hapus'])) {
    $i = $_GET['hapus'];
    if (isset($_SESSION['todolist'][$i])) {
        array_splice($_SESSION['todolist'], $i, 1);
    }
    header("Location: todolist.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>To Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="box">

        <!-- Form untuk menambah item baru ke dalam to-do list -->
        <form method="POST" class="form-inline">
            <input type="text" name="todo" placeholder="<Teks to do>" required>
            <button type="submit" name="tambah">Tambah</button>
        </form>

        <div class="todo-list">
            
            <!-- Menampilkan semua item dalam to-do list -->
            <?php foreach ($_SESSION['todolist'] as $index => $item): ?>
                <div class="todo-item">
                    <input type="text" value="<?= $item['teks'] ?>" readonly class="<?= $item['selesai'] ? 'done' : '' ?>">
                    <div class="buttons">
                        <?php if (!$item['selesai']): ?>
                            <a href="?selesai=<?= $index ?>" class="btn">Selesai</a>
                        <?php else: ?>
                            <span class="btn disabled">Selesai</span>
                        <?php endif; ?>
                        <a href="?hapus=<?= $index ?>" class="btn">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
