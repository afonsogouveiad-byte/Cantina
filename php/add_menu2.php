<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

error_log('add_menu2.php accessed');

session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$image = '';

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $week = intval($_POST['week'] ?? 0);
    $day = $_POST['day'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = floatval($_POST['price'] ?? 0);

    error_log("Form data: week=$week, day=$day, name=$name, price=$price");

    // -------------------------
    // UPLOAD DA IMAGEM
    // -------------------------
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        error_log('File upload: ' . print_r($_FILES['image'], true));

        $check = getimagesize($_FILES['image']['tmp_name']);

        if ($check === false) {
            die('Erro: ficheiro não é imagem válida.');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($check['mime'], $allowed, true)) {
            die('Erro: tipo de imagem não permitido.');
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
            die('Erro: não foi possível guardar a imagem.');
        }

        error_log("Imagem guardada em: $uploadPath");
    }

    // -------------------------
    // INSERT BD
    // -------------------------
    $stmt = $conn->prepare("
        INSERT INTO menus2 (week, day, name, price, image)
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Erro SQL: " . $conn->error);
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

<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

<style>
body {
    font-family: Arial;
    background: #eef4fc;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.box {
    background: white;
    padding: 30px;
    border-radius: 16px;
    width: 420px;
}

label {
    display: block;
    margin-top: 12px;
    font-weight: bold;
}

input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
}

button {
    width: 100%;
    margin-top: 20px;
    padding: 12px;
    background: #00a2ff;
    border: none;
    color: white;
    font-weight: bold;
    cursor: pointer;
}
</style>

</head>

<body>

<div class="box">

<h2>Afegir menú</h2>

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
        <option value="dilluns">Dilluns</option>
        <option value="dimarts">Dimarts</option>
        <option value="dimecres">Dimecres</option>
        <option value="dijous">Dijous</option>
        <option value="divendres">Divendres</option>
    </select>

    <label>Nome do prato</label>
    <input type="text" name="name" required>

    <label>Preço</label>
    <input type="number" step="0.01" name="price" required>

    <label>Imagem</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Guardar</button>

</form>

</div>

</body>
</html>