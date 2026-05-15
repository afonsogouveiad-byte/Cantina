<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $week  = $_POST['week'] ?? '';
    $day   = $_POST['day'] ?? '';
    $name  = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';

    if ($week === '' || $day === '' || $name === '' || $price === '') {
        die("Erro: campos em falta.");
    }

    $week = (int)$week;
    $price = (float)$price;

    $image = '';

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {

        $uploadDir = __DIR__ . "/images/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $image = uniqid() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
    }

    $stmt = $conn->prepare("
        INSERT INTO menus (week, day, name, price, image)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("issds", $week, $day, $name, $price, $image);
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
<title>Afegir menú</title>

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
            <option value="dilluns">DILLUNS</option>
            <option value="dimarts">DIMARTS</option>
            <option value="dimecres">DIMECRES</option>
            <option value="dijous">DIJOUS</option>
            <option value="divendres">DIVENDRES</option>
        </select>

        <label>Nome do prato</label>
        <input type="text" name="name" required>

        <label>Preço</label>
        <input type="number" step="0.01" name="price" required>

        <label>Imagem</label>
        <input type="file" name="image">

        <button type="submit">Guardar</button>
    </form>

    <a class="back" href="admin_menu.php">← Voltar</a>
</div>

</body>
</html>