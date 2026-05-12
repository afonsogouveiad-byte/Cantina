<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $week = intval($_POST['week']);
    $day = $_POST['day'];
    $name = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);

    $sql = "INSERT INTO menus2 (week, day, name, price) VALUES ($week, '" . $conn->real_escape_string($day) . "', '$name', $price)";
    $conn->query($sql);

    header("Location: admin_menu2.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Afegir menú gran</title>
<style>
:root {--primary:#0d4c9d;--accent:#00a2ff;--primary-soft:#3f7be6;--text:#172c45;--surface:#ffffff;--border:rgba(13,76,157,0.14);--shadow:0 24px 60px rgba(15,41,78,0.08);}*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;}body{min-height:100vh;background:#eef4fc;color:#222;display:flex;justify-content:center;align-items:center;padding:20px;} .box{background:#fff;padding:36px;border-radius:20px;max-width:460px;width:100%;box-shadow:var(--shadow);border:1px solid var(--border);} h2{text-align:center;margin-bottom:24px;font-size:1.9rem;color:#111;} label{display:block;margin:14px 0 6px;color:#444;font-weight:600;} input, select{width:100%;padding:14px 16px;border:2px solid var(--border);border-radius:12px;background:#fff;color:#172c45;font-size:1rem;transition:all 0.25s ease;} input:focus, select:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(0,162,255,0.12);} button{width:100%;padding:14px 16px;background:linear-gradient(135deg,var(--accent),var(--primary-soft));color:#fff;border:none;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;margin-top:22px;transition:transform 0.2s ease,box-shadow 0.2s ease;} button:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,162,255,0.2);} .back{display:block;text-align:center;margin-top:18px;color:#444;text-decoration:none;} .back:hover{color:var(--accent);} </style>
</head>
<body>
<div class="box">
    <h2>Afegir menú gran</h2>
    <form method="POST">
        <label for="week">Setmana</label>
        <select id="week" name="week" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
        <label for="day">Dia</label>
        <select id="day" name="day" required>
            <option value="dilluns">DILLUNS</option>
            <option value="dimarts">DIMARTS</option>
            <option value="dimecres">DIMECRES</option>
            <option value="dijous">DIJOUS</option>
            <option value="divendres">DIVENDRES</option>
        </select>
        <label for="name">Nom del plat</label>
        <input type="text" id="name" name="name" required>
        <label for="price">Preu</label>
        <input type="number" step="0.01" id="price" name="price" required>
        <button type="submit">Desa</button>
    </form>
    <a class="back" href="admin_menu2.php">← Tornar al panell</a>
</div>
</body>
</html>