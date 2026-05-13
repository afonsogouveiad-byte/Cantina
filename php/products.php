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
<title>Productes de menjador</title>
<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

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

.hero{
    height:320px;
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
    background:rgba(13, 76, 157, 0.5);
}

.hero h1{
    position:relative;
    z-index:2;
    color:white;
    font-size:52px;
    text-align:center;
    letter-spacing:1px;
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
    transform: none !important;
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
    font-family: Arial, sans-serif;
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

.nav-right {
    display: flex;
}

.search-wrapper {
    max-width: 1180px;
    margin: 0 auto;
    padding: 24px 24px 0;
}

.search-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.search-input {
    flex: 1;
    min-width: 220px;
    padding: 12px 16px;
    border: 1px solid #d3dce6;
    border-radius: 12px;
    background: #fff;
    color: #172c45;
}

.search-input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 4px rgba(0, 162, 255, 0.12);
}


.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

.category-heading {
    grid-column: 1 / -1;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--primary);
    margin: 24px 0 12px;
}

h1 {
    text-align: center;
    margin: 20px 0;
}

.grid {
    max-width: 1180px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    padding: 28px 24px 40px;
}

@media (max-width: 1024px) {
    .grid {
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        padding: 24px 20px 32px;
    }
}

@media (max-width: 768px) {
    .hero {
        min-height: 40vh;
        padding: 32px 20px;
    }

    .hero h1 {
        font-size: clamp(2.2rem, 8vw, 3.2rem);
    }

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
}

@media (max-width: 480px) {
    .hero {
        min-height: 35vh;
        padding: 24px 16px;
    }

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

.product-img {
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
    margin-bottom: 8px;
}

.category {
    color: var(--muted);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.price {
    color: var(--accent);
    font-weight: bold;
}

.stock.low {
    color: red;
}

.footer {
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

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 24px;
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
        <a href="index.php"><img src="images/inspedr.jpg" alt="Institut Pedralbes"></a>
    </div>

    <div class="nav-links">
        <a href="index.php">Inici</a>
        <a class="active" href="products.php">Productes</a>
        <a href="menu.php">Menú Infantil</a>
        <a href="menu2.php">Menú General</a>
    </div>

    <div class="nav-right"></div>

</div>

<div class="hero">
    <div class="hero-content">
        <h1>Productes de menjador</h1>
    </div>
</div>

<div class="search-wrapper">
    <form class="search-form" method="get" action="products.php">
  
        <input id="search-input" name="search" type="search" class="search-input" placeholder="Cerca per nom, categoria o preu" value="<?= htmlspecialchars($search) ?>">
    </form>
</div>

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
        <div class="info">
            <?php if ($row['image']): ?>
                <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" style="max-width: 100%; height: auto; margin-bottom: 10px;">
            <?php endif; ?>
            <div class="name"><?= htmlspecialchars($row['name']) ?></div>
            <div class="price"><?= number_format($row['price'], 2) ?> €</div>
            <div class="category"><?= htmlspecialchars($row['category']) ?></div>
            <?php if(isset($_SESSION['user'])): ?>
            <div class="actions">
                <a class="edit" href="edit.php?id=<?= $row['id'] ?>">Editar</a>
                <a class="delete" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Estàs segur que vols eliminar aquest producte?')">Eliminar</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php else: ?>
    <div class="empty">Cap producte trobat. Prova amb una altra cerca o afegeix productes.</div>
<?php endif; ?>
<footer class="footer">

    <div class="footer-container">

        <div class="footer-section">
            <h3>El centre</h3>
            <p>
                Institut públic del districte de Les Corts, amb oferta d’ESO, Batxillerat,
                CF d’Informàtica i d’Imatge i So, i PFI.
            </p>
        </div>

        <div class="footer-section">
            <h4>Contacte</h4>
            <p>93 203 33 32</p>
            <p>inspadralbes@xtec.cat</p>
            <p>Web AFA</p>
        </div>

        <div class="footer-section">
            <h4>Adreça</h4>
            <p>Av. Esplugues, 36-42</p>
            <p>08034 Barcelona</p>
        </div>

        <div class="footer-section">
            <h4>Legal</h4>
            <a href="#">Cookies</a>
            <br>
            <a href="#">Avís legal</a>
            <br>
            <a href="#">Protecció de dades</a>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Institut Pedralbes - Cantina</p>
    </div>

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
