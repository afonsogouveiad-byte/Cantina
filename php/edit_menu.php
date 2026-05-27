<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* SELECT */
$stmt = $conn->prepare("SELECT * FROM menus WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$menu = $result ? $result->fetch_assoc() : null;

if (!$menu) {
    header("Location: admin_menu.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $week = (int)$_POST['week'];
    $day  = $_POST['day'];
    $name = $_POST['name'];
    $price = (float)$_POST['price'];

    $image = $menu['image'];

    /* ---------------- UPLOAD ---------------- */
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $check = getimagesize($_FILES['image']['tmp_name']);

        if ($check === false) {
            die('Erro: não é imagem válida.');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($check['mime'], $allowed, true)) {
            die('Erro: tipo inválido.');
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
            die('Erro upload.');
        }

        /* apagar antigo */
        if (!empty($menu['image']) && file_exists($uploadDir . $menu['image'])) {
            unlink($uploadDir . $menu['image']);
        }

        $image = $newImage;
    }

    /* UPDATE */
    $stmt = $conn->prepare("
        UPDATE menus 
        SET week = ?, day = ?, name = ?, price = ?, image = ?
        WHERE id = ?
    ");

    $stmt->bind_param("issdsi", $week, $day, $name, $price, $image, $id);
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
<title>Editar menú infantil</title>

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
    max-width:460px;
    width:100%;
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

img{
    margin-top:10px;
    border-radius:10px;
}
</style>
</head>

<body>

<div class="box">

<h2>Editar menú infantil</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Setmana</label>
    <select name="week" required>
        <?php for ($i=1;$i<=4;$i++): ?>
            <option value="<?= $i ?>" <?= $menu['week']==$i?'selected':'' ?>>
                <?= $i ?>
            </option>
        <?php endfor; ?>
    </select>

    <label>Dia</label>
    <select name="day" required>
        <?php
        $days = [
            'dilluns'=>'DILLUNS',
            'dimarts'=>'DIMARTS',
            'dimecres'=>'DIMECRES',
            'dijous'=>'DIJOUS',
            'divendres'=>'DIVENDRES'
        ];
        foreach ($days as $v=>$l): ?>
            <option value="<?= $v ?>" <?= $menu['day']===$v?'selected':'' ?>>
                <?= $l ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Nome</label>
    <input type="text" name="name" value="<?= htmlspecialchars($menu['name']) ?>" required>

    <label>Preço</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($menu['price']) ?>" required>

    <label>Imagem</label>
    <input type="file" name="image" accept="image/*">

    <?php if (!empty($menu['image'])): ?>
        <p>Atual:</p>
        <img src="uploads/<?= htmlspecialchars($menu['image']) ?>" width="120">
    <?php endif; ?>

    <button type="submit">Guardar</button>

</form>

</div>

</body>
</html>