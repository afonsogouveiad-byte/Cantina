<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$image = '';
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

    if ($error === '') {
        // -------------------------
        //CARREGA LA IMATGE (COMPONENT AMB ALTRES FITXERS)
        // -------------------------
        if (!empty($_FILES['image']['name'])) {
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $error = "Error: hi ha hagut un problema amb la càrrega de la imatge.";
            } else {
                $maxSize = 100 * 1024 * 1024; // 100 MB
                if ($_FILES['image']['size'] > $maxSize) {
                    $error = "Error: la imatge és massa gran (màxim 100 MB).";
                } else {
                    $check = getimagesize($_FILES['image']['tmp_name']);

                    if ($check === false) {
                        $error = "Error: el fitxer no és una imatge vàlida.";
                    } else {
                        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                        if (!in_array($check['mime'], $allowed, true)) {
                            $error = "Error: tipus d’imatge no permès.";
                        } else {
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
                                $error = "Error: No es pot desar la imatge.";
                            }
                        }
                    }
                }
            }
        }
    }

    if ($error === '') {
    // -------------------------
    // INSERT BD
    // -------------------------
    $stmt = $conn->prepare("
        INSERT INTO products (name, price, category, image)
        VALUES (?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Error SQL: " . $conn->error);
    }

    $stmt->bind_param("sdss", $name, $price, $category, $image);
    $stmt->execute();

    header("Location: admin.php");
    exit();
    }
}

$categoryOptions = [];
$result = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categoryOptions[] = $row['category'];
    }
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

input,
select {
    width: 100%;
    padding: 14px;
    margin: 8px 0 16px 0;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    background: white;
}

input:focus,
select:focus {
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

    <?php if ($error !== ''): ?>
        <div style="margin-bottom:16px;padding:14px;background:#ffe5e5;color:#900;border:1px solid #f5c2c2;border-radius:12px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="name" placeholder="Nom del producte" value="<?= htmlspecialchars($name) ?>" required>

        <input type="number" step="0.01" name="price" placeholder="Preu" value="<?= htmlspecialchars($price) ?>" required>

        <select name="category" required>
            <option value="" disabled hidden<?= $category === '' ? ' selected' : '' ?>>Categoria</option>
            <?php foreach ($categoryOptions as $option): ?>
                <option value="<?= htmlspecialchars($option) ?>"<?= $option === $category ? ' selected' : '' ?>><?= htmlspecialchars($option) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="file" name="image" accept="image/*">

        <button type="submit">Desar canvis</button>

    </form>

    <a class="back" href="admin_products.php">← Tornar al panell</a>
</div>

</body>
</html>