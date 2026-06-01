<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$image = null;
$error = '';
$week = '';
$day = '';
$name = '';
$price = '';

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $week = $_POST['week'] ?? '';
    $day  = $_POST['day'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';

    $allowedDays = ['dilluns', 'dimarts', 'dimecres', 'dijous', 'divendres'];

    if ($week === '' || $day === '' || $name === '' || $price === '') {
        $error = 'Error: falta informació del formulari.';
    }

    if ($error === '' && !preg_match('/^[1-4]$/', $week)) {
        $error = 'Error: setmana invàlida.';
    }

    if ($error === '' && !in_array($day, $allowedDays, true)) {
        $error = 'Error: dia invàlid.';
    }

    if ($error === '' && !preg_match('/^[\p{L}\d\s\-\'\.]+$/u', $name)) {
        $error = 'Error: nom del plat invàlid.';
    }

    if ($error === '' && !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
        $error = 'Error: preu invàlid.';
    }

    if ($error === '') {
        $price = (float)$price;
        if ($price <= 0) {
            $error = 'Error: preu invàlid.';
        }
    }

    if ($error === '') {
        // -------------------------
        // CARREGA LA IMATGE
        // -------------------------
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $maxSize = 100 * 1024 * 1024; // 100 MB
        if ($_FILES['image']['size'] > $maxSize) {
            $error = "Error: la imatge és massa gran (màxim 100 MB).";
        } else {

        $check = getimagesize($_FILES['image']['tmp_name']);

        if ($check === false) {
            die('Error: el fitxer no és una imatge vàlida.');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($check['mime'], $allowed, true)) {
            die('Error: tipus d’imatge no permès.');
        }

        $uploadDir = __DIR__ . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $image = uniqid('img_', true) . '_' . preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            basename($_FILES['image']['name'])
        );

        $uploadPath = $uploadDir . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            die('Error: No es pot desar la imatge.');
        }
        }
    }

    // se não houver imagem, garante string vazia
    if ($image === null) {
        $image = '';
    }

    // -------------------------
    // INSERT BD
    // -------------------------
    $stmt = $conn->prepare("
        INSERT INTO menus2 (week, day, name, price, image)
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Error SQL: " . $conn->error);
    }

    $stmt->bind_param("issds", $week, $day, $name, $price, $image);
    $stmt->execute();

    header("Location: admin_menu2.php");
    exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Afegir menú general</title>

<style>
:root {
    --primary:#0d4c9d;
    --accent:#00a2ff;
    --primary-soft:#3f7be6;
    --text:#172c45;
    --surface:#ffffff;
    --border:rgba(13,76,157,0.14);
    --shadow:0 24px 60px rgba(15,41,78,0.08);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    min-height:100vh;
    background:#eef4fc;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.box{
    background:#fff;
    padding:36px;
    border-radius:20px;
    width:100%;
    max-width:460px;
    box-shadow:var(--shadow);
    border:1px solid var(--border);
}

h2{
    text-align:center;
    margin-bottom:24px;
    color:#111;
}

label{
    display:block;
    margin:12px 0 6px;
    font-weight:600;
    color:#444;
}

input, select{
    width:100%;
    padding:14px;
    border:2px solid var(--border);
    border-radius:12px;
    font-size:1rem;
}

input:focus, select:focus{
    outline:none;
    border-color:var(--accent);
    box-shadow:0 0 0 3px rgba(0,162,255,0.12);
}

button{
    width:100%;
    margin-top:20px;
    padding:14px;
    border:none;
    border-radius:12px;
    font-weight:700;
    color:#fff;
    background:linear-gradient(135deg,var(--accent),var(--primary-soft));
    cursor:pointer;
    transition:transform 0.2s ease, box-shadow 0.2s ease;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(0,162,255,0.2);
}

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#555;
    text-decoration:none;
}

.back:hover{
    color:var(--accent);
}
</style>

</head>

<body>

<div class="box">
    <h2>Afegir menú general</h2>

    <form method="POST" enctype="multipart/form-data">

    <?php if ($error !== ''): ?>
        <div style="margin-bottom:16px;padding:14px;background:#ffe5e5;color:#900;border:1px solid #f5c2c2;border-radius:12px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

        <label>Setmana</label>
        <select name="week" required>
            <option value="" disabled <?= $week === '' ? 'selected' : '' ?>>Selecciona setmana</option>
            <option value="1" <?= $week == '1' ? 'selected' : '' ?>>1</option>
            <option value="2" <?= $week == '2' ? 'selected' : '' ?>>2</option>
            <option value="3" <?= $week == '3' ? 'selected' : '' ?>>3</option>
            <option value="4" <?= $week == '4' ? 'selected' : '' ?>>4</option>
        </select>

        <label>Dia</label>
        <select name="day" required>
            <option value="" disabled <?= $day === '' ? 'selected' : '' ?>>Selecciona dia</option>
            <option value="dilluns" <?= $day === 'dilluns' ? 'selected' : '' ?>>DILLUNS</option>
            <option value="dimarts" <?= $day === 'dimarts' ? 'selected' : '' ?>>DIMARTS</option>
            <option value="dimecres" <?= $day === 'dimecres' ? 'selected' : '' ?>>DIMECRES</option>
            <option value="dijous" <?= $day === 'dijous' ? 'selected' : '' ?>>DIJOUS</option>
            <option value="divendres" <?= $day === 'divendres' ? 'selected' : '' ?>>DIVENDRES</option>
        </select>

        <label>Nom del plat</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

        <label>Preu</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>" required>

        <label>Imatge</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Desar canvis</button>
    </form>

    <a class="back" href="admin_menu2.php">← Tornar al panell</a>
</div>

</body>
</html>