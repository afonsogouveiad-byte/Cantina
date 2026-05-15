<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $price = floatval($_POST['price']);
    $category = $_POST['category'];
    $image = '';

    // Upload de imagem
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

        $image = basename($_FILES['image']['name']);
        $uploadPath = __DIR__ . '/images/' . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $image = '';
        }
    }

    // PREPARED STATEMENT (seguro contra SQL injection)
    $stmt = $conn->prepare("
        INSERT INTO products (name, price, category, image)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("sdss", $name, $price, $category, $image);

    $stmt->execute();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<title>Afegir producte</title>
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
}

body {
    min-height: 100vh;
    background: #f5f8ff;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    font-family: Arial, sans-serif;
    color: var(--text);
}

.box {
    background: var(--surface);
    padding: 40px;
    border-radius: 20px;
    box-shadow: var(--shadow);
    width: 100%;
    max-width: 400px;
    border: 1px solid var(--border);
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
    font-size: 1rem;
}

input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(0, 162, 255, 0.1);
}

button {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, var(--accent), var(--primary-soft));
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    transform: translateY(-2px);
    background: var(--primary-soft);
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
</style>

</head>

<body>

<div class="box">
    <h2>Afegir producte</h2>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="name" placeholder="Nom do produto" required>

        <input type="number" step="0.01" name="price" placeholder="Preu" required>

        <input type="text" name="category" placeholder="Categoria" required>

        <input type="file" name="image" accept="image/*">

        <button type="submit">Desa</button>

    </form>

    <a class="back" href="admin.php">← Tornar ao panell</a>
</div>

</body>
</html>