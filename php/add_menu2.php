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

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $week = intval($_POST['week'] ?? 0);
    $day  = $_POST['day'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = floatval($_POST['price'] ?? 0);

    // -------------------------
    // UPLOAD IMAGEM
    // -------------------------
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

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

        <label>Setmana</label>
        <select name="week" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>

        <label>Dia</label>
        <select name="day" required>
            <option value="dilluns">DILLUNS</option>
            <option value="dimarts">DIMARTS</option>
            <option value="dimecres">DIMECRES</option>
            <option value="dijous">DIJOUS</option>
            <option value="divendres">DIVENDRES</option>
        </select>

        <label>Nom del plat</label>
        <input type="text" name="name" required>

        <label>Preu</label>
        <input type="number" step="0.01" name="price" required>

        <label>Imatge</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Desar canvis</button>
    </form>

    <a class="back" href="admin_menu2.php">← Tornar al panell</a>
</div>

</body>
</html>