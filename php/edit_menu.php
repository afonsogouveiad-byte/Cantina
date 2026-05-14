<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
$result = $conn->query("SELECT * FROM menus WHERE id=$id");
$menu = $result ? $result->fetch_assoc() : null;
if (!$menu) {
    header("Location: admin_menu.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $week = isset($_POST['week']) ? (int)$_POST['week'] : $menu['week'];
    $day  = isset($_POST['day']) ? $conn->real_escape_string($_POST['day']) : $menu['day'];
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : $menu['name'];
    $price = isset($_POST['price']) ? (float)$_POST['price'] : $menu['price'];

    $image = $menu['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = basename($_FILES['image']['name']);
        $uploadDir = __DIR__ . '/images';
        $uploadPath = $uploadDir . '/' . $image;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (file_exists($uploadPath)) {
            chmod($uploadPath, 0666);
            unlink($uploadPath);
        }

        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
        }
    }

    $sql = "UPDATE menus 
            SET week=$week, day='$day', name='$name', price=$price, image='$image' 
            WHERE id=$id";

    $conn->query($sql);

    header("Location: admin_menu2.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar menú petit</title>
<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">
<style>
:root {--primary:#0d4c9d;--accent:#00a2ff;--primary-soft:#3f7be6;--text:#172c45;--surface:#ffffff;--border:rgba(13,76,157,0.14);--shadow:0 24px 60px rgba(15,41,78,0.08);}*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;}body{min-height:100vh;background:#eef4fc;color:#222;display:flex;justify-content:center;align-items:center;padding:20px;} .box{background:#fff;padding:36px;border-radius:20px;max-width:460px;width:100%;box-shadow:var(--shadow);border:1px solid var(--border);} h2{text-align:center;margin-bottom:24px;font-size:1.9rem;color:#111;} label{display:block;margin:14px 0 6px;color:#444;font-weight:600;} input, select{width:100%;padding:14px 16px;border:2px solid var(--border);border-radius:12px;background:#fff;color:#172c45;font-size:1rem;transition:all 0.25s ease;} input:focus, select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(0,162,255,0.12);} button{width:100%;padding:14px 16px;background:linear-gradient(135deg,var(--accent),var(--primary-soft));color:#fff;border:none;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;margin-top:22px;transition:transform 0.2s ease,box-shadow 0.2s ease;} button:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,162,255,0.2);} .back{display:block;text-align:center;margin-top:18px;color:#444;text-decoration:none;} .back:hover{color:var(--accent);} </style>
</head>
<body>
<div class="box">
    <h2>Editar menú petit</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="week">Setmana</label>
        <select id="week" name="week" required>
            <?php for ($i=1; $i<=4; $i++): ?>
                <option value="<?= $i ?>" <?= $menu['week']==$i ? 'selected' : '' ?>><?= $i ?></option>
            <?php endfor; ?>
        </select>
        <label for="day">Dia</label>
        <select id="day" name="day" required>
            <?php $days = ['dilluns'=>'DILLUNS','dimarts'=>'DIMARTS','dimecres'=>'DIMECRES','dijous'=>'DIJOUS','divendres'=>'DIVENDRES']; ?>
            <?php foreach ($days as $value => $label): ?>
                <option value="<?= $value ?>" <?= $menu['day'] === $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <label for="name">Nom del plat</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($menu['name']) ?>" required>
        <label for="price">Preu</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($menu['price']) ?>" required>
        <label for="image">Imatge</label>
        <input type="file" id="image" name="image" accept="image/*">
        <?php if ($menu['image']): ?>
            <p>Imatge actual: <img src="images/<?= htmlspecialchars($menu['image']) ?>" alt="Imatge actual" style="max-width: 100px;"></p>
        <?php endif; ?>
        <button type="submit">Desa canvis</button>
    </form>
    <a class="back" href="admin_menu.php">← Tornar al panell</a>
</div>
</body>
</html>