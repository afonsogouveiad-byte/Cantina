<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Productes - Cantina</title>
<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">
<style>
:root {
    --primary: #0d4c9d;
    --accent: #00a2ff;
    --primary-soft: #3f7be6;
    --text: #172c45;
    --surface: #ffffff;
    --shadow: 0 24px 60px rgba(15, 41, 78, 0.08);
}
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}
body{
    background:#eef4fc;
    color:#222;
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
.logo a {
    display: inline-block;
    padding: 0 !important;
    border-radius: 0 !important;
    background: transparent !important;
    color: inherit !important;
    text-decoration: none !important;
    transition: none !important;
}
.logo a:hover {
    background: transparent !important;
    transform: none;
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
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 0.95rem;
}
.nav a:hover,
.nav a.active {
    background: var(--accent);
    color: white;
    transform: translateY(-1px);
}
.nav-right {
    display: flex;
    justify-content: flex-end;
}
.logout a {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
    padding: 10px 16px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
}
.hero{
    height:240px;
    background:url('images/cantina.png') center/cover;
    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
}
.hero::before{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(13, 76, 157, 0.55);
}
.hero h1{
    position:relative;
    z-index:2;
    color:white;
    font-size:42px;
    text-align:center;
    letter-spacing:1px;
}
.admin-container{
    max-width:1200px;
    margin:40px auto;
    padding:20px;
}
.action-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:16px;
    margin-bottom:24px;
}
.action-bar h2{
    font-size:28px;
    color:#111;
}
.search-form{
    display:flex;
    gap:10px;
    align-items:center;
    flex-wrap:wrap;
    width:min(100%,480px);
}
.search-input{
    flex:1;
    min-width:220px;
    padding:12px 16px;
    border:1px solid #d3dce6;
    border-radius:12px;
    font-size:1rem;
    color:#172c45;
    background:white;
}
.search-input:focus{
    outline:none;
    border-color:var(--accent);
    box-shadow:0 0 0 4px rgba(0,162,255,0.12);
}

.sr-only{
    position:absolute;
    width:1px;
    height:1px;
    padding:0;
    margin:-1px;
    overflow:hidden;
    clip:rect(0,0,0,0);
    white-space:nowrap;
    border:0;
}
.action-bar a.button{
    background: var(--accent);
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.action-bar a.button:hover{
    transform: translateY(-2px);
    box-shadow: 0 12px 20px rgba(0,162,255,0.2);
}
.category-heading{
    grid-column: 1 / -1;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--primary);
    margin: 16px 0 8px;
}
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:24px;
}
.card{
    background:white;
    border-radius:16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    overflow:hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.card:hover{
    transform: translateY(-5px);
    box-shadow: 0 16px 32px rgba(0,0,0,0.1);
}
.card img{
    width:100%;
    height:180px;
    object-fit:cover;
}
.card-body{
    padding:18px;
}
.card-body h3{
    font-size:20px;
    margin-bottom:10px;
}
.meta{
    color:#5f6f86;
    margin-bottom:14px;
}
.actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}
.actions a{
    display:inline-block;
    text-decoration:none;
    padding:10px 14px;
    border-radius:10px;
    font-weight:700;
    color:white;
}
.actions a.edit{background:var(--primary-soft);}
.actions a.delete{background:#e74c3c;}
.actions a.edit:hover{background:var(--primary);}
.actions a.delete:hover{background:#c0392b;}
.empty{
    background:white;
    border-radius:16px;
    padding:40px;
    text-align:center;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}
footer {
    background: var(--primary);
    color: white;
    margin-top: 40px;
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
.footer-section a {color: var(--accent); text-decoration: none;}
.footer-bottom {text-align:center; padding:20px 24px; border-top:1px solid rgba(255,255,255,0.1); font-size:0.9rem; color:rgba(255,255,255,0.7);}
@media(max-width:700px){.nav{grid-template-columns:1fr; gap:15px;}.nav-links{flex-wrap:wrap; justify-content:center;}.hero h1{font-size:32px;}.action-bar{flex-direction:column; align-items:flex-start;}.footer-container{grid-template-columns:1fr;}}
</style>
</head>

<body>
<div class="nav">
    <div class="logo"><a href="admin.php"><img src="images/inspedr.jpg" alt="Institut Pedralbes"></a></div>
    <div class="nav-links">
        <a href="admin.php">Panell</a>
        <a class="active" href="admin_products.php">Productes</a>
        <a href="admin_menu.php">Menú Infantil</a>
        <a href="admin_menu2.php">Menú General</a>
    </div>
    <div class="nav-right"><a class="logout" href="logout.php">Tanca sessió</a></div>
</div>
<div class="hero"><h1>Productes Admin</h1></div>
<div class="admin-container">
    <div class="action-bar">
        <h2>Gestió de productes</h2>
        <form class="search-form" method="get" action="admin_products.php">
            <input id="search-input" name="search" type="search" class="search-input" placeholder="Cerca per nom o categoria" value="<?= htmlspecialchars($search) ?>">
        </form>
        <a href="add.php" class="button">Afegir producte</a>
    </div>
    <?php if ($result && $result->num_rows > 0): ?>
    <?php $currentCategory = null; ?>
    <div class="grid">
        <?php while($row = $result->fetch_assoc()): ?>
            <?php $categoryLabel = !empty($row['category']) ? htmlspecialchars($row['category']) : 'Sense categoria'; ?>
            <?php if ($currentCategory !== $categoryLabel): ?>
                <?php $currentCategory = $categoryLabel; ?>
                <div class="category-heading"><?= $currentCategory ?></div>
            <?php endif; ?>
        <div class="card">
            <div class="card-body">
                <?php if ($row['image']): ?>
                    <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p class="meta">Preu: <?= number_format($row['price'], 2) ?> €</p>
                <?php if (!empty($row['category'])): ?><p class="meta">Categoria: <?= htmlspecialchars($row['category']) ?></p><?php endif; ?>
                <div class="actions">
                    <a class="edit" href="edit.php?id=<?= $row['id'] ?>">Editar</a>
                    <a class="delete" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Estàs segur que vols eliminar aquest producte?')">Eliminar</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="empty">Cap producte trobat. Afegeix un producte nou per començar.</div>
    <?php endif; ?>
</div>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section"><h3>El centre</h3><p>Institut públic del districte de Les Corts, amb oferta d’ESO, Batxillerat, CFGM i CFGS d’Informàtica, Imatge i So, i PFI.</p></div>
        <div class="footer-section"><h4>Contacte</h4><p>93 203 33 32</p><p>inspedralbes@xtec.cat</p></div>
        <div class="footer-section"><h4>Adreça</h4><p>Av. Esplugues, 36-42</p><p>08034 Barcelona</p></div>
        <div class="footer-section"><h4>Legal</h4><a href="#">Cookies</a><br><a href="#">Avís legal</a><br><a href="#">Protecció de dades</a></div>
    </div>
    <div class="footer-bottom">&copy; <?= date("Y") ?> Institut Pedralbes - Cantina</div>
</footer>
<script>
    (function() {
        const input = document.getElementById('search-input');
        const form = document.querySelector('.search-form');
        let timeoutId = null;

        if (!input || !form) {
            return;
        }

        input.addEventListener('input', function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(function() {
                form.submit();
            }, 350);
        });
    })();
</script>
</body>
</html>