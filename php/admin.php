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
}

.nav {
    background: var(--primary);
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    padding: 12px 24px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(13, 76, 157, 0.15);
}

.logo img {
    height: 45px;
    border-radius: 8px;
}

.nav-links {
    display: flex;
    justify-content: center;
    gap: 8px;
}

.nav a {
    color: white;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 0.95rem;
}

.nav a:hover {
    background: var(--accent);
    color: white;
    transform: translateY(-1px);
}

.nav a.active {
    background: var(--accent);
    color: white;
    box-shadow: 0 4px 12px rgba(0, 162, 255, 0.3);
}

.logout {
    text-align: right;
}

.logout a {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

.logout a:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(231, 76, 60, 0.4);
}

.grid {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    padding: 28px 24px 40px;
}

.card {
    background: var(--surface);
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fadeInUp 0.85s ease both;
    border: 1px solid var(--border);
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 32px 64px rgba(15, 41, 78, 0.12);
}

.card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.info {
    padding: 20px;
}

.name {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: var(--text);
}

.price {
    color: var(--accent);
    font-weight: 600;
    font-size: 1.2rem;
    margin-bottom: 12px;
}

.actions {
    display: flex;
    justify-content: space-between;
    margin-top: 16px;
    gap: 8px;
}

.actions a {
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    flex: 1;
    text-align: center;
}

.actions a:first-child {
    background: var(--primary-soft);
    color: white;
}

.actions a:first-child:hover {
    background: var(--primary);
    transform: translateY(-1px);
}

.actions a:last-child {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.actions a:last-child:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
    transform: translateY(-1px);
}
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 14px;
}

.edit {
    background: var(--primary-soft);
    color: white;
@media (max-width: 1024px) {
    .grid {
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        padding: 24px 20px 32px;
    }
}

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
        gap: 16px;
        padding: 20px 16px 28px;
    }

    .nav {
        grid-template-columns: 1fr;
        text-align: center;
        padding: 10px 16px;
    }

    .nav-links {
        flex-direction: column;
        gap: 6px;
        margin-top: 12px;
    }

    .nav a {
        padding: 8px 16px;
        font-size: 0.9rem;
    }

    .logout {
        text-align: center;
        margin-top: 8px;
    }

    .actions {
        flex-direction: column;
        gap: 6px;
    }

    .actions a {
        padding: 10px 12px;
    }
}

@media (max-width: 480px) {
    .grid {
        padding: 16px 12px 24px;
    }

    .card {
        border-radius: 12px;
    }

    .info {
        padding: 16px;
    }
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