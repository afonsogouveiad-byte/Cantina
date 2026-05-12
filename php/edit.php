<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $sql = "UPDATE products 
            SET name='$name', price='$price', category='$category'
            WHERE id=$id";

    $conn->query($sql);

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<title>Editar Producte</title>

    <style>
:root {
    --bg: #f4f8fd;
    --surface: #ffffff;
    --surface-soft: #eef3fb;
    --primary: #0d4c9d;
    --primary-soft: #3f7be6;
    --accent: #00a2ff;
    --text: #172c45;
    --muted: #5f6f86;
    --border: rgba(13, 76, 157, 0.14);
    --shadow: 0 24px 60px rgba(15, 41, 78, 0.08);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    scroll-behavior: smooth;
}

body {
    min-height: 100vh;
    background: radial-gradient(circle at top left, rgba(0, 162, 255, 0.14), transparent 26%),
        linear-gradient(180deg, #f5f8ff 0%, #eef4fc 100%);
    color: var(--text);
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
    max-width: 400px;
    border: 1px solid var(--border);
    animation: fadeInUp 0.9s ease both;
}

.box h2 {
    text-align: center;
    margin-bottom: 24px;
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--text);
}

input {
    width: 100%;
    padding: 14px 16px;
    margin: 8px 0 16px 0;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    background: var(--surface);
    color: var(--text);
}

input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(0, 162, 255, 0.1);
}

button {
    width: 100%;
    padding: 14px 16px;
    background: linear-gradient(135deg, var(--accent), var(--primary-soft));
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 162, 255, 0.3);
    margin-top: 8px;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 162, 255, 0.4);
}
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    transition: 0.2s;
}

input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 5px rgba(0, 162, 255, 0.4);
}

button {
    width: 100%;
    padding: 12px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: 0.2s;
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

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
</head>

<body>

<div class="box">

    <h2>Editar Producte</h2>

    <form method="POST">

        <label for="name">Nom</label>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($product['name']) ?>" required>

        <label for="price">Preu</label>
        <input type="number" id="price" step="0.01" name="price"
               value="<?= htmlspecialchars($product['price']) ?>" required>

        <label for="category">Categoria</label>
        <input type="text" id="category" name="category"
               value="<?= htmlspecialchars($product['category']) ?>" required>

        <button type="submit">Actualitza</button>

    </form>

    <a class="back" href="admin.php">← Tornar</a>

</div>

</body>
</html>