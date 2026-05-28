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
<title>Admin Productes</title>
<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

<style>

:root {
    --primary: #0d4c9d;
    --accent: #00a2ff;
    --primary-soft: #3f7be6;
    --text: #172c45;
    --muted: #5f6f86;
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

/* NAV (IGUAL AO TEU PADRÃO) */

.nav{
    background: var(--primary);
    display:grid;
    grid-template-columns:1fr auto 1fr;
    align-items:center;
    padding:12px 24px;
    position:sticky;
    top:0;
    z-index:1000;
    box-shadow:0 4px 20px rgba(13,76,157,0.15);
}

.logo img{
    height:45px;
    border-radius:8px;
}

.nav-links{
    display:flex;
    gap:8px;
    justify-content:center;
}

.nav a{
    color:white;
    text-decoration:none;
    padding:10px 18px;
    border-radius:10px;
    font-weight:600;
    font-size:0.95rem;
    transition:all 0.25s cubic-bezier(0.4,0,0.2,1);
}

.nav a:hover,
.nav a.active{
    background:var(--accent);
    transform:translateY(-1px);
}

.nav-right{
    display:flex;
    justify-content:flex-end;
}

.logout a{
    background:linear-gradient(135deg,#e74c3c,#c0392b);
    color:white;
    padding:10px 16px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
}

/* HERO */

.hero{
    height:240px;
    background:url('images/cantina.jpeg') center/cover;
    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
}

.hero::before{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(13,76,157,0.55);
}

.hero h1{
    position:relative;
    color:white;
    font-size:42px;
    text-align:center;
}

/* SEARCH */

.search-wrapper{
    max-width:1200px;
    margin:20px auto;
    padding:0 20px;
}

.search-input{
    width:100%;
    padding:12px 16px;
    border-radius:12px;
    border:1px solid #ccc;
    transition:0.2s;
}

.search-input:focus{
    outline:none;
    border-color:var(--accent);
    box-shadow:0 0 0 3px rgba(0,162,255,0.25);
}

/* GRID */

.admin-container{
    max-width:1200px;
    margin:30px auto;
    padding:20px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:24px;
}

/* CARD (ANIMAÇÃO SÓ AQUI) */

.card{
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
    transition:0.25s;
    animation:fadeInUp 0.6s ease both;
}

.card:hover{
    transform:translateY(-5px);
    box-shadow:0 16px 32px rgba(0,0,0,0.1);
}

.card img{
    width: calc(100% - 24px);
    height: 200px;
    object-fit: cover;
    display:block;
    margin:12px auto 0 auto;
    border-radius:12px;
}

.card-body{
    padding:18px;
}

.meta{
    color:#5f6f86;
    margin-bottom:10px;
}

.category-heading{
    grid-column:1 / -1;
    font-size:1.3rem;
    font-weight:700;
    color:var(--primary);
    margin-top:10px;
}

/* EMPTY */

.empty{
    background:white;
    padding:40px;
    border-radius:16px;
    text-align:center;
}

/* ANIMAÇÃO */

@keyframes fadeInUp{
    from{
        opacity:0;
        transform:translateY(25px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

</style>
</head>

<body>

<div class="nav">

    <div class="logo">
        <a href="admin.php"><img src="images/inspedr.jpg"></a>
    </div>

    <div class="nav-links">
        <a href="admin.php">Panell</a>
        <a class="active" href="admin_products.php">Productes</a>
        <a href="admin_menu.php">Menú Infantil</a>
        <a href="admin_menu2.php">Menú General</a>
    </div>

    <div class="nav-right">
        <a class="logout" href="logout.php">Tanca sessió</a>
    </div>

</div>

<div class="hero">
    <h1>Productes Admin</h1>
</div>

<div class="search-wrapper">
    <input id="search-input" class="search-input"
           type="search"
           placeholder="Cerca..."
           value="<?= htmlspecialchars($search) ?>">
</div>

<div class="admin-container">
<div id="search-results">

<?php if ($result && $result->num_rows > 0): ?>
<div class="grid">

<?php $currentCategory = null; ?>

<?php while($row = $result->fetch_assoc()): ?>

<?php $categoryLabel = !empty($row['category']) ? htmlspecialchars($row['category']) : 'Sense categoria'; ?>

<?php if ($currentCategory !== $categoryLabel): ?>
    <?php $currentCategory = $categoryLabel; ?>
    <div class="category-heading"><?= $currentCategory ?></div>
<?php endif; ?>

<div class="card">

    <?php if (!empty($row['image'])): ?>
        <img src="uploads/<?= htmlspecialchars($row['image']) ?>">
    <?php endif; ?>

    <div class="card-body">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p class="meta"><?= number_format($row['price'], 2) ?> €</p>
    </div>

</div>

<?php endwhile; ?>

</div>

<?php else: ?>
<div class="empty">Cap producte trobat.</div>
<?php endif; ?>

</div>
</div>

<script>
const input = document.getElementById('search-input');
const results = document.getElementById('search-results');

let timeout;

input.addEventListener('input', () => {

    clearTimeout(timeout);

    timeout = setTimeout(() => {

        const url = new URL(window.location.href);
        url.searchParams.set('search', input.value.trim());

        fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(r => r.text())
        .then(html => {
            const doc = new DOMParser().parseFromString(html,'text/html');
            const newResults = doc.getElementById('search-results');
            if(newResults) results.innerHTML = newResults.innerHTML;
        });

    }, 300);

});
</script>

</body>
</html>