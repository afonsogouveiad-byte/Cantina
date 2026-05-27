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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST['name'] ?? '';
    $price = (float)($_POST['price'] ?? 0);
    $category = $_POST['category'] ?? '';

    $image = $product['image']; // mantém imagem antiga

    /* ---------------- UPLOAD ---------------- */
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            die("Erro: ficheiro não é imagem válida.");
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($check['mime'], $allowed, true)) {
            die("Erro: tipo de imagem inválido.");
        }

        $uploadDir = __DIR__ . '/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newName = uniqid('img_', true) . '_' . preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            basename($_FILES['image']['name'])
        );

        $uploadPath = $uploadDir . $newName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            die("Erro: falha ao guardar imagem.");
        }

        /* apagar antiga */
        if (!empty($product['image']) && file_exists($uploadDir . $product['image'])) {
            unlink($uploadDir . $product['image']);
        }

        $image = $newName;
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
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<title>Editar Producte</title>

<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

<style>
:root {
    --bg: #f4f8fd;
    --surface: #ffffff;
    --primary: #0d4c9d;
    --primary-soft: #3f7be6;
    --accent: #00a2ff;
    --text: #172c45;
    --border: rgba(13, 76, 157, 0.14);
    --shadow: 0 24px 60px rgba(15, 41, 78, 0.08);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    min-height: 100vh;
    background: #eef4fc;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.box {
    background: var(--surface);
    padding: 40px;
    border-radius: 20px;
    box-shadow: var(--shadow);
    width: 100%;
    max-width: 420px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

input {
    width: 100%;
    padding: 14px;
    margin: 8px 0 16px 0;
    border: 2px solid var(--border);
    border-radius: 12px;
}

input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 5px rgba(0,162,255,0.3);
    outline: none;
}

button {
    width: 100%;
    padding: 14px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: var(--primary-soft);
    transform: translateY(-2px);
}

.back {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #555;
    text-decoration: none;
}

.back:hover {
    color: var(--accent);
}

img {
    margin-top: 10px;
    border-radius: 8px;
}
</style>
</head>

<body>

<div class="box">

<h2>Editar Producte</h2>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

    <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>

    <input type="file" name="image" accept="image/*">

    <?php if (!empty($product['image'])): ?>
        <p>Imagem atual:</p>
        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" width="120">
    <?php endif; ?>

    <button type="submit">Atualizar</button>

</form>

<a class="back" href="admin.php">← Voltar</a>

</div>

</body>
</html>