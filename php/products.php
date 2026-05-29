<?php
session_start();
require_once 'connexio.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchNormalized = str_replace(',', '.', $search);

if ($search !== '') {
    $searchLike = "%{$search}%";

    if (is_numeric($searchNormalized)) {
        $price = (float) $searchNormalized;
        $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ? OR price = ? ORDER BY category ASC, name ASC");
        $stmt->bind_param("ssd", $searchLike, $searchLike, $price);
    } else {
        $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ? ORDER BY category ASC, name ASC");
        $stmt->bind_param("ss", $searchLike, $searchLike);
    }

    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products ORDER BY category ASC, name ASC");
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<title>Productes</title>
<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

<style>

:root {
    --primary: #0d4c9d;
    --primary-soft: #3f7be6;
    --accent: #00a2ff;
    --text: #172c45;
    --muted: #5f6f86;
    --shadow: 0 24px 60px rgba(15, 41, 78, 0.08);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #eef4fc;
    color: var(--text);
}

/* NAVBAR (IGUAL AO TEU PADRÃO) */

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
    gap: 8px;
    justify-content: center;
}

.nav a {
    color: white;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.25s ease;
}

.nav a:hover {
    background: var(--accent);
    transform: translateY(-1px);
}

.nav a.active {
    background: var(--accent);
    box-shadow: 0 4px 12px rgba(0,162,255,0.3);
}

/* HERO */

.hero {
    height: 320px;
    background: url('images/cantina.jpeg') center/cover;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

.hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(13, 76, 157, 0.5);
}

.hero h1 {
    position: relative;
    color: white;
    font-size: 52px;
}

/* SEARCH */

.search-wrapper {
    max-width: 1180px;
    margin: 0 auto;
    padding: 24px;
}

.search-input {
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid #d3dce6;
    transition: 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(0,162,255,0.25);
}

/* GRID */

.grid {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    padding: 28px 24px;
}

/* CARD (AQUI ESTÁ A ANIMAÇÃO CERTA) */

.card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: 0.3s;

    animation: fadeInUp 0.6s ease both;
}

.card:hover {
    transform: translateY(-6px);
}

/* IMAGEM */

.product-img {
    width: calc(100% - 24px);
    height: 200px;
    object-fit: cover;
    display: block;
    margin: 12px auto 0 auto;
    border-radius: 12px;
}

/* INFO */

.info {
    padding: 20px;
}

.name {
    font-weight: 600;
    margin-bottom: 6px;
}

.price {
    color: var(--accent);
    font-weight: bold;
    margin-bottom: 6px;
}

.category {
    color: var(--muted);
    font-size: 0.9rem;
}

/* FOOTER */

footer {
    background: var(--primary);
    color: white;
    margin-top: 60px;
    position: relative;
}

.footer::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent), var(--primary-soft));
}

.footer-container {
    max-width: 1180px;
    margin: 0 auto;
    padding: 40px 24px;

    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 32px;
}

.footer-section h3,
.footer-section h4 {
    margin-bottom: 16px;
    font-weight: 600;
    color: white;
}

.footer-section p,
.footer-section a {
    font-size: 0.95rem;
    line-height: 1.7;
    color: rgba(255,255,255,0.9);
}

.footer-section a {
    color: var(--accent);
    text-decoration: none;
    transition: all 0.25s ease;
}

.footer-section a:hover {
    color: white;
    text-decoration: none;
    transform: translateX(2px);
}

.footer-bottom {
    text-align: center;
    padding: 20px 24px;
    border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
}

/* RESPONSIVE */

@media(max-width:1000px){

    .footer-container{
        grid-template-columns:1fr 1fr;
    }
}

@media(max-width:700px){

    .nav{
        grid-template-columns:1fr;
        gap:15px;
    }

    .nav-links{
        flex-wrap:wrap;
    }

    .hero h1{
        font-size:36px;
    }

    .footer-container{
        grid-template-columns:1fr;
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

<div class="nav">

    <div class="logo">
        <a href="index.php"><img src="images/inspedr.jpg"></a>
    </div>

    <div class="nav-links">
        <a href="index.php">Inici</a>
        <a class="active" href="products.php">Productes</a>
        <a href="menu.php">Menú Infantil</a>
        <a href="menu2.php">Menú General</a>
    </div>

    <div></div>

</div>

<div class="hero">
    <h1>Productes de menjador</h1>
</div>

<div class="search-wrapper">
    <form method="get">
        <input class="search-input"
               name="search"
               value="<?= htmlspecialchars($search) ?>"
               placeholder="Cerca...">
    </form>
</div>

<div class="grid">

<?php while($row = $result->fetch_assoc()): ?>

<div class="card">

    <?php if (!empty($row['image'])): ?>
        <img class="product-img"
             src="uploads/<?= htmlspecialchars($row['image']) ?>"
             alt="<?= htmlspecialchars($row['name']) ?>">
    <?php endif; ?>

    <div class="info">
        <div class="name"><?= htmlspecialchars($row['name']) ?></div>
        <div class="price"><?= number_format($row['price'], 2) ?> €</div>
        <div class="category"><?= htmlspecialchars($row['category']) ?></div>
    </div>

</div>

<?php endwhile; ?>

</div>
<footer class="footer">

    <div class="footer-container">

        <div class="footer-section">

            <h3>El centre</h3>

            <p>
                Institut públic del districte de Les Corts,
                amb oferta d’ESO, Batxillerat,
                CFGM i CFGS d’Informàtica,
                Imatge i So, i PFI.
            </p>

        </div>

        <div class="footer-section">

            <h4>Contacte</h4>

            <p>93 203 33 32</p>
            <p>inspedralbes@xtec.cat</p>

        </div>

        <div class="footer-section">

            <h4>Adreça</h4>

            <p>Av. Esplugues, 36-42</p>
            <p>08034 Barcelona</p>

        </div>

        <div class="footer-section">

            <h4>Legal</h4>

            <a href="cookies.php">Cookies</a>
            <br>
            <a href="legal.php">Avís legal</a>
            <br>
            <a href="privacy.php">Protecció de dades</a>

        </div>

    </div>

    <div class="footer-bottom">
        &copy; <?= date("Y") ?> Institut Pedralbes - Cantina
    </div>

</footer>

</body>
</html>