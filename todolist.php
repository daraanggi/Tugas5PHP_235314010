<!DOCTYPE html>
<html>
<head>
    <title>To Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>To Do List</h2>
    <form method="post">
        <input type="text" name="item" placeholder="Tulis tugas..." required>
        <button type="submit" name="tambah">Tambah</button>
    </form>
    <hr>
    <?php
    session_start();

    if (!isset($_SESSION['todolist'])) $_SESSION['todolist'] = [];

    if (isset($_POST['tambah']) && !empty(trim($_POST['item']))) {
        $_SESSION['todolist'][] = ['teks' => htmlspecialchars($_POST['item']), 'selesai' => false];
        header("Location: todo.php");
        exit;
    }

    if (isset($_GET['selesai'])) {
        $index = $_GET['selesai'];
        if (isset($_SESSION['todolist'][$index])) {
            $_SESSION['todolist'][$index]['selesai'] = true;
        }
        header("Location: todo.php");
        exit;
    }

    if (isset($_GET['hapus'])) {
        $index = $_GET['hapus'];
        if (isset($_SESSION['todolist'][$index])) {
            array_splice($_SESSION['todolist'], $index, 1);
        }
        header("Location: todo.php");
        exit;
    }

    foreach ($_SESSION['todolist'] as $index => $task) {
        echo '<div class="task">';
        echo '<span class="' . ($task['selesai'] ? 'done' : '') . '">' . $task['teks'] . '</span>';
        if (!$task['selesai']) {
            echo ' <a href="?selesai=' . $index . '">Selesai</a>';
        }
        echo ' <a href="?hapus=' . $index . '">Hapus</a>';
        echo '</div>';
    }
    ?>
    <script src="script.js"></script>
</body>
</html>
