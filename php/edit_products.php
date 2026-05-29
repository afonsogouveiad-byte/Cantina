<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* SELECT */
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$product = $result ? $result->fetch_assoc() : null;

if (!$product) {
    header("Location: admin_products.php");
    exit();
}

$error = '';
$name = '';
$price = '';
$category = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $category = trim($_POST['category'] ?? '');

    if ($name === '' || $category === '' || $price === '') {
        $error = "Error: campos inválidos.";
    }

    if ($error === '' && !preg_match('/^[\p{L}\d\s\-\'\.]+$/u', $name)) {
        $error = "Error: nom del producte invàlid.";
    }

    if ($error === '' && !preg_match('/^[\p{L}\d\s\-\'\.]+$/u', $category)) {
        $error = "Error: categoria invàlida.";
    }

    if ($error === '' && !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
        $error = "Error: preu invàlid.";
    }

    if ($error === '') {
        $price = (float)$price;
        if ($price <= 0) {
            $error = "Error: preu invàlid.";
        }
    }

    $image = $product['image'];

    if ($error === '') {
        /* UPLOAD */
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $check = getimagesize($_FILES['image']['tmp_name']);

        if ($check === false) {
            die("Error: el fitxer no és una imatge vàlida.");
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($check['mime'], $allowed, true)) {
            die("Error: tipus d’imatge no permès.");
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
            die("Error: no s'ha pogut guardar la imatge.");
        }

        if (!empty($product['image']) && file_exists($uploadDir . $product['image'])) {
            unlink($uploadDir . $product['image']);
        }

        $image = $newImage;
    }

    /* UPDATE */
    $stmt = $conn->prepare("
        UPDATE products 
        SET name = ?, price = ?, category = ?, image = ?
        WHERE id = ?
    ");

    $stmt->bind_param("sdssi", $name, $price, $category, $image, $id);
    $stmt->execute();

    header("Location: admin_products.php");
    exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<title>Editar producte</title>

<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

<style>
:root {
    --bg:#eef4fc;
    --surface:#fff;
    --primary:#0d4c9d;
    --primary-soft:#3f7be6;
    --accent:#00a2ff;
    --text:#172c45;
    --border:rgba(13,76,157,0.14);
    --shadow:0 24px 60px rgba(15,41,78,0.08);
}

* {
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body {
    min-height:100vh;
    background:var(--bg);
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

.box {
    background:var(--surface);
    padding:40px;
    border-radius:20px;
    box-shadow:var(--shadow);
    width:100%;
    max-width:420px;
}

h2 {
    text-align:center;
    margin-bottom:20px;
}

label {
    display:block;
    margin:10px 0 5px;
    font-weight:600;
    color:#333;
}

input {
    width:100%;
    padding:14px;
    margin-bottom:12px;
    border:2px solid var(--border);
    border-radius:12px;
}

input:focus {
    border-color:var(--accent);
    outline:none;
    box-shadow:0 0 5px rgba(0,162,255,0.3);
}

button {
    width:100%;
    padding:14px;
    background:var(--accent);
    color:#fff;
    border:none;
    border-radius:12px;
    font-weight:bold;
    cursor:pointer;
}

button:hover {
    background:var(--primary-soft);
    transform:translateY(-2px);
}

.back {
    display:block;
    text-align:center;
    margin-top:15px;
    color:#555;
    text-decoration:none;
}

.back:hover {
    color:var(--accent);
}

img {
    margin-top:10px;
    border-radius:8px;
}
</style>
</head>

<body>

<div class="box">

<h2>Editar producte</h2>

<form method="POST" enctype="multipart/form-data">

    <?php if ($error !== ''): ?>
        <div style="margin-top:0;margin-bottom:16px;padding:14px;background:#ffe5e5;color:#900;border:1px solid #f5c2c2;border-radius:12px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <label>Nom</label>
    <input type="text" name="name" value="<?= htmlspecialchars($name !== '' ? $name : $product['name']) ?>" required>

    <label>Preu</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price !== '' ? $price : $product['price']) ?>" required>

    <label>Categoria</label>
    <input type="text" name="category" value="<?= htmlspecialchars($category !== '' ? $category : $product['category']) ?>" required>

    <label>Imatge</label>
    <input type="file" name="image" accept="image/*">

    <?php if (!empty($product['image'])): ?>
        <p style="margin-top:10px;">Imatge actual:</p>
        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" width="120">
    <?php endif; ?>

    <button type="submit">Guardar canvis</button>

</form>

<a class="back" onclick="history.back()">← Tornar al panell</a>

</div>

</body>
</html>