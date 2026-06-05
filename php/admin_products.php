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
        $stmt = $conn->prepare("
            SELECT * FROM products 
            WHERE name LIKE ? OR category LIKE ? OR price = ? 
            ORDER BY category ASC, name ASC
        ");
        $stmt->bind_param("ssd", $searchLike, $searchLike, $price);
    } else {
        $stmt = $conn->prepare("
            SELECT * FROM products 
            WHERE name LIKE ? OR category LIKE ? 
            ORDER BY category ASC, name ASC
        ");
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
<title>Admin Productes</title>
<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

<style>

:root{
    --primary:#0d4c9d;
    --accent:#00a2ff;
    --primary-soft:#3f7be6;
    --text:#172c45;
    --muted:#5f6f86;
    --shadow:0 24px 60px rgba(15,41,78,0.08);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#eef4fc;
    color:#222;
}

.nav{
    background:var(--primary);
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

.logo a{
    display:inline-block;
    padding:0 !important;
    border-radius:0 !important;
    background:transparent !important;
    color:inherit !important;
    text-decoration:none !important;
    transition:none !important;
}

.logo a:hover{
    background:transparent !important;
    transform:none !important;
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
    transition:all 0.25s cubic-bezier(0.4,0,0.2,1);
    font-size:0.95rem;
}

.nav a:hover,.nav a.active{
    background:var(--accent);
    color:white;
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
    background:url('images/cantina.jpeg')center/cover;
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
    z-index:2;
    color:white;
    font-size:42px;
    text-align:center;
    letter-spacing:1px;
}

/* ACTION BAR */
.action-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:16px;
    margin-bottom:24px;
    max-width:1180px;
    margin-left:auto;
    margin-right:auto;
    padding:24px 24px 0 24px;
}

.action-bar h2{
    font-size:28px;
    color:#111;
}

.action-bar a.button{
    background:var(--accent);
    color:white;
    padding:12px 20px;
    border-radius:12px;
    text-decoration:none;
    font-weight:700;
    transition:transform 0.2s ease,box-shadow 0.2s ease;
}

.action-bar a.button:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 20px rgba(0,162,255,0.2);
}

/* SEARCH */
.search-wrapper{
    max-width:1180px;
    margin:0 auto;
    padding:24px;
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
.grid{
    max-width:1180px;
    margin:0 auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:24px;
    padding:28px 24px;
}

/* CATEGORY TITLE (SÓ ADIÇÃO) */
.category-title{
    grid-column:1 / -1;
    font-size:20px;
    font-weight:700;
    color:var(--primary);
    margin-top:10px;
}

/* CARD (INTACTO) */
.card{
    background:white;
    border-radius:16px;
    box-shadow:var(--shadow);
    overflow:hidden;
    transition:0.3s;
    animation:fadeInUp 0.6s ease both;
    display:flex;
    flex-direction:column;
    height:100%;
}

.card:hover{
    transform:translateY(-6px);
}

/* IMAGEM */
.product-img{
    height:200px;
    object-fit:cover;
    margin:12px auto 0 auto;
    border-radius:12px;
    display:block;
}

/* INFO */
.info{
    padding:20px;
    display:flex;
    flex-direction:column;
    flex:1;
}

.name{
    font-weight:600;
    margin-bottom:8px;
}

.price{
    color:var(--accent);
    font-weight:bold;
    margin-bottom:4px;
}

.category{
    color:var(--muted);
    margin-bottom:10px;
}

/* BOTÕES (INTACTOS) */
.actions{
    display:flex;
    gap:8px;
    margin-top:10px;
}

.actions a{
    display:inline-block;
    text-decoration:none;
    padding:7px 12px;
    border-radius:8px;
    font-size:13px;
    font-weight:bold;
    color:white;
}

.actions .edit{
    background:var(--primary-soft);
}

.actions .delete{
    background:#e74c3c;
}

/* FOOTER */
footer{
    background:var(--primary);
    color:white;
    margin-top:60px;
    position:relative;
}

.footer::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    right:0;
    height:4px;
    background:linear-gradient(90deg,var(--accent),var(--primary-soft));
}

.footer-container{
    max-width:1180px;
    margin:0 auto;
    padding:40px 24px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:32px;
}

.footer-section h3,
.footer-section h4{
    margin-bottom:16px;
    font-weight:600;
    color:white;
}

.footer-section p,
.footer-section a{
    font-size:0.95rem;
    line-height:1.7;
    color:rgba(255,255,255,0.9);
}

.footer-section a{
    color:var(--accent);
    text-decoration:none;
    transition:all 0.25s ease;
}

.footer-section a:hover{
    color:white;
    transform:translateX(2px);
}

.footer-bottom{
    text-align:center;
    padding:20px 24px;
    border-top:1px solid rgba(255,255,255,0.1);
    font-size:0.9rem;
    color:rgba(255,255,255,0.7);
}

/* RESPONSIVE */

@media(max-width:700px){
    .nav{
        grid-template-columns:1fr;
        gap:15px;
    }
    .nav-links{
        flex-wrap:wrap;
        justify-content:center;
    }
    .hero h1{
        font-size:32px;
    }
    .action-bar{
        flex-direction:column;
        align-items:flex-start;
    }
    .footer-container{
        grid-template-columns:1fr;
    }
}

/* ANIMAÇÃO */

@keyframes fadeInUp {
    from {
        opacity:0;
        transform:translateY(30px);
    }
    to {
        opacity:1;
        transform:translateY(0);
    }
}

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

<div class="hero">
    <h1>Productes Admin</h1>
</div>

<div class="action-bar">
    <h2>Productes</h2>
    <a href="add_products.php" class="button">Afegir producte</a>
</div>

<div class="search-wrapper">
    <form method="get">
        <input class="search-input"
       name="search"
       id="search"
       value="<?= htmlspecialchars($search) ?>"
       placeholder="Cerca...">
    </form>
</div>

<div class="grid">

<?php
$currentCategory = null;

while($row = $result->fetch_assoc()):

    if ($currentCategory !== $row['category']) {
        $currentCategory = $row['category'];
        echo '<div class="category-title">'.htmlspecialchars($currentCategory).'</div>';
    }
?>

<div class="card">

    <?php if (!empty($row['image'])): ?>
        <img class="product-img"
             src="uploads/<?= htmlspecialchars($row['image']) ?>">
    <?php endif; ?>

    <div class="info">
        <div class="name"><?= htmlspecialchars($row['name']) ?></div>
        <div class="price"><?= number_format($row['price'], 2) ?> €</div>
        <div class="category"><?= htmlspecialchars($row['category']) ?></div>

        <div class="actions">
            <a class="edit" href="edit_products.php?id=<?= $row['id'] ?>">Editar</a>
            <a class="delete" href="delete_products.php?id=<?= $row['id'] ?>"
               onclick="return confirm('Tens a certeza?')">Eliminar</a>
        </div>

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

            <a href="cookies.php" target="_blank" rel="noopener noreferrer">Cookies</a>
            <br>
            <a href="legal.php" target="_blank" rel="noopener noreferrer">Avís legal</a>
            <br>
            <a href="privacy.php" target="_blank" rel="noopener noreferrer">Protecció de dades</a>

        </div>

    </div>

    <div class="footer-bottom">
        &copy; <?= date("Y") ?> Institut Pedralbes - Cantina
    </div>

</footer>
<script>
const input = document.querySelector('.search-input');
const grid = document.querySelector('.grid');

let timeout = null;

input.addEventListener('input', function () {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        fetch("?search=" + encodeURIComponent(input.value))
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");

                const newGrid = doc.querySelector(".grid");

                if (newGrid) {
                    grid.innerHTML = newGrid.innerHTML;
                }
            });
    }, 300); // delay para não spammar o servidor
});
</script>
</body>
</html>