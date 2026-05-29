<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* SELECT */
$stmt = $conn->prepare("SELECT * FROM menus WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$menu = $result ? $result->fetch_assoc() : null;

if (!$menu) {
    header("Location: admin_menu.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $week = (int)$_POST['week'];
    $day  = $_POST['day'];
    $name = $_POST['name'];
    $price = (float)$_POST['price'];

    $image = $menu['image']; // mantém imagem antiga

    // -------------------------
    // UPLOAD IMATGE
    // -------------------------
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $check = getimagesize($_FILES['image']['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if ($check === false || !in_array($check['mime'], $allowedTypes, true)) {
            die('Error: el fitxer no és una imatge vàlida.');
        }

        $uploadDir = __DIR__ . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newImage = uniqid('img_', true) . '_' . preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            basename($_FILES['image']['name'])
        );

        $uploadPath = $uploadDir . $newImage;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            die('Error: no s\'ha pogut carregar la imatge.');
        }

        // eliminar antiga
        if ($menu['image'] && file_exists($uploadDir . $menu['image'])) {
            unlink($uploadDir . $menu['image']);
        }

        $image = $newImage;
    }

    /* UPDATE */
    $stmt = $conn->prepare("
        UPDATE menus 
        SET week = ?, day = ?, name = ?, price = ?, image = ?
        WHERE id = ?
    ");

    $stmt->bind_param("issdsi", $week, $day, $name, $price, $image, $id);
    $stmt->execute();

    header("Location: admin_menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar menú infantil</title>

<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

<style>
:root {--primary:#0d4c9d;--accent:#00a2ff;--primary-soft:#3f7be6;--text:#172c45;--surface:#ffffff;--border:rgba(13,76,157,0.14);--shadow:0 24px 60px rgba(15,41,78,0.08);}
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;}
body{min-height:100vh;background:#eef4fc;color:#222;display:flex;justify-content:center;align-items:center;padding:20px;}
.box{background:#fff;padding:36px;border-radius:20px;max-width:460px;width:100%;box-shadow:var(--shadow);border:1px solid var(--border);}
h2{text-align:center;margin-bottom:24px;font-size:1.9rem;color:#111;}
label{display:block;margin:14px 0 6px;color:#444;font-weight:600;}
input, select{width:100%;padding:14px 16px;border:2px solid var(--border);border-radius:12px;background:#fff;color:#172c45;font-size:1rem;}
button{width:100%;padding:14px;background:linear-gradient(135deg,var(--accent),var(--primary-soft));color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;margin-top:20px;}
.back{display:block;text-align:center;margin-top:18px;color:#444;text-decoration:none;}
.back:hover{color:var(--accent);}
</style>

</head>
<body>

<div class="box">
    <h2>Editar menú infantil</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Setmana</label>
        <select name="week" required>
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <option value="<?= $i ?>" <?= $menu['week'] == $i ? 'selected' : '' ?>>
                    <?= $i ?>
                </option>
            <?php endfor; ?>
        </select>

        <label>Dia</label>
        <select name="day" required>
            <?php
            $days = [
                'dilluns' => 'DILLUNS',
                'dimarts' => 'DIMARTS',
                'dimecres' => 'DIMECRES',
                'dijous' => 'DIJOUS',
                'divendres' => 'DIVENDRES'
            ];
            foreach ($days as $value => $label):
            ?>
                <option value="<?= $value ?>" <?= $menu['day'] === $value ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Nom del plat</label>
        <input type="text" name="name" value="<?= htmlspecialchars($menu['name']) ?>" required>

        <label>Preu</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($menu['price']) ?>" required>

        <label>Imatge</label>
        <input type="file" name="image" accept="image/*">

        <?php if (!empty($menu['image'])): ?>
            <p>Imatge actual:</p>
            <img src="uploads/<?= htmlspecialchars($menu['image']) ?>" style="max-width:120px;">
        <?php endif; ?>

        <button type="submit">Desar canvis</button>
    </form>

    <a class="back" onclick="history.back()">← Tornar al panell</a>
</div>

</body>
</html>