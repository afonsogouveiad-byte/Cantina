<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<title>Admin - Cantina</title>

<style>
:root {
    --primary: #0d4c9d;
    --accent: #00a2ff;
    --primary-soft: #3f7be6;
    --text: #172c45;
    --muted: #5f6f86;
    --surface: #ffffff;
    --shadow: 0 24px 60px rgba(15, 41, 78, 0.08);
}

body {
    font-family: Arial;
    margin: 0;
    background: #eef4fc;
}

.nav {
    background: var(--primary);
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    padding: 10px 20px;
    position: sticky;
    top: 0;
}

.logo img {
    height: 40px;
}

.nav-links {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.nav a {
    color: white;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.25s;
}

.nav a:hover {
    background: var(--accent);
    color: white;
}

.nav a.active {
    background: var(--accent);
    color: white;
}

.logout {
    text-align: right;
}

.logout a {
    background: #e74c3c;
    color: white;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
}

.logout a:hover {
    background: #c0392b;
}

.grid {
    max-width: 1000px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 20px;
}

.card {
    background: var(--surface);
    border-radius: 12px;
    box-shadow: var(--shadow);
    overflow: hidden;
    animation: fadeInUp 0.85s ease both;
}

.card img {
    width: 100%;
    height: 140px;
    object-fit: cover;
}

.info {
    padding: 15px;
}

.name {
    font-weight: bold;
}

.price {
    color: var(--accent);
    font-weight: bold;
}

.actions {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.actions a {
    text-decoration: none;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 14px;
}

.edit {
    background: var(--primary-soft);
    color: white;
}

.delete {
    background: #e74c3c;
    color: white;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(18px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
</head>

<body>

<<div class="nav">

    <div class="logo">
        <a href="index.php">
            <img src="images/logo.png" alt="Institut Pedralbes">
        </a>
    </div>

    <div class="nav-links">
        <a href="index.php">Início</a>
        <a href="llistar.php">Produtos</a>
        <a href="menus.php">Menu</a>

        <?php if (isset($_SESSION['user'])): ?>
            <a href="admin.php">Admin</a>
        <?php endif; ?>
    </div>

    <div class="right">
        <?php if (isset($_SESSION['user'])): ?>
            <a class="logout" href="logout.php">Logout</a>
        <?php endif; ?>
    </div>

</div>

<h1 style="text-align:center; margin:20px 0;">Painel Admin</h1>

<div class="grid">

<?php while($row = $result->fetch_assoc()): ?>
    <div class="card">

        <img src="images/<?= $row['images'] ?>">

        <div class="info">
            <div class="name"><?= $row['name'] ?></div>
            <div class="price"><?= $row['price'] ?> €</div>

            <div class="actions">
                <a class="edit" href="edit.php?id=<?= $row['id'] ?>">Editar</a>
                <a class="delete" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Apagar?')">Apagar</a>
            </div>
        </div>

    </div>
<?php endwhile; ?>

</div>

</body>
</html>