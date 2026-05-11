<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];

    $sql = "INSERT INTO products (name, price, stock, images)
            VALUES ('$name', '$price', '$stock', '$image')";

    $conn->query($sql);

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<title>Adicionar Produto</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #f4f6f8;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.box {
    background: white;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    width: 350px;
}

.box h2 {
    text-align: center;
    margin-bottom: 20px;
}

input {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: 1px solid #ddd;
    border-radius: 8px;
    outline: none;
    transition: 0.2s;
}

input:focus {
    border-color: #1abc9c;
    box-shadow: 0 0 5px rgba(26,188,156,0.4);
}

button {
    width: 100%;
    padding: 12px;
    background: #1abc9c;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 10px;
    transition: 0.2s;
}

button:hover {
    background: #16a085;
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
    color: #1abc9c;
}
</style>
</head>

<body>

<div class="box">
    <h2>Adicionar Produto</h2>

    <form method="POST">

        <input type="text" name="name" placeholder="Nome do produto" required>

        <input type="number" step="0.01" name="price" placeholder="Preço" required>

        <input type="number" name="stock" placeholder="Stock" required>

        <input type="text" name="image" placeholder="nome_da_imagem.jpg" required>

        <button type="submit">Guardar</button>

    </form>

    <a class="back" href="admin.php">← Voltar ao painel</a>
</div>

</body>
</html>