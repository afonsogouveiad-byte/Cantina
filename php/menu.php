<?php
session_start();
require_once 'connexio.php';

$result = $conn->query("SELECT * FROM menus ORDER BY week, id");

if (!$result) {
    die("Error a la consulta: " . $conn->error);
}

$menus = [];

while($row = $result->fetch_assoc()) {

    $week = $row['week'] ?? '';
    $day = $row['day'] ?? '';

    if($week === '' || $day === '') {
        continue;
    }

    $menus[$week][$day][] = $row;
}

$dias = [
    'dilluns'   => 'DILLUNS',
    'dimarts'   => 'DIMARTS',
    'dimecres'  => 'DIMECRES',
    'dijous'    => 'DIJOUS',
    'divendres' => 'DIVENDRES'
];

$semanas = [
    1 => 'SETMANA 1',
    2 => 'SETMANA 2',
    3 => 'SETMANA 3',
    4 => 'SETMANA 4'
];
?>

<!DOCTYPE html>
<html lang="ca">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Menú Infantil</title>

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

/* NAVBAR */

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


/* HERO */

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

/* CONTAINER */

.menu-container{
    max-width:1300px;
    margin:50px auto;
    padding:20px;
}

/* SETMANES */

.setmana{
    margin-bottom:60px;
}

.setmana-title{
    font-size:34px;
    margin-bottom:25px;
    color:#111;
    border-left:6px solid var(--accent);
    padding-left:15px;
}

/* GRID */

.dies-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
    gap:20px;
}

/* CARD */

.dia-card{
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
    transition:0.25s;
    animation: fadeInUp 0.85s ease both;
}

.dia-card:hover{
    transform:translateY(-5px);
}

.dia-header{
    background:var(--primary);
    color:white;
    text-align:center;
    padding:16px;
    font-size:18px;
    font-weight:bold;
}

.dia-content{
    padding:18px;
}
/* MENUS */

.prato{
    background:#f8f9fb;
    border-radius:12px;
    padding:14px;
    margin-bottom:14px;
    border:1px solid #ececec;
    transition:0.2s;
}

.prato:hover{
    transform:translateY(-2px);
    box-shadow:0 4px 12px rgba(0,0,0,0.06);
}

.prato:last-child{
    margin-bottom:0;
}

.nome{
    font-size:16px;
    font-weight:bold;
    margin-bottom:8px;
    color:#222;
}

.preco{
    color:var(--accent);
    font-weight:bold;
    margin-bottom:10px;
    font-size:15px;
}

.buit{
    text-align:center;
    color:#888;
    padding:20px 0;
}

/* BOTÕES */

.actions{
    margin-top:12px;
}

.actions a{
    display:inline-block;
    text-decoration:none;
    padding:7px 12px;
    border-radius:8px;
    font-size:13px;
    margin-right:6px;
    color:white;
    font-weight:bold;
    transition:0.2s;
}

.actions a:hover{
    opacity:0.9;
    transform:scale(1.03);
}

.actions a:first-child{
    background:var(--primary-soft);
}

.actions a:last-child{
    background:#e74c3c;
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

<!-- NAVBAR -->

<div class="nav">

    <div class="logo">
        <img src="images/inspedr.jpg" alt="Institut Pedralbes">
    </div>

    <div class="nav-links">
        <a href="index.php">Inici</a>
        <a href="products.php">Productes</a>
        <a class="active" href="menu.php">Menú Infantil</a>
        <a href="menu2.php">Menú General</a>
    </div>

    <div></div>

</div>

<!-- HERO -->

<div class="hero">
    <h1>Menú Infantil</h1>
</div>

<!-- MENUS -->

<div class="menu-container">

<?php foreach($semanas as $weekKey => $weekName): ?>

    <div class="setmana">

        <h2 class="setmana-title">
            <?= $weekName ?>
        </h2>

        <div class="dies-grid">

        <?php foreach($dias as $dayKey => $dayName): ?>

            <div class="dia-card">

                <div class="dia-header">
                    <?= $dayName ?>
                </div>

                <div class="dia-content">

                <?php
                if(isset($menus[$weekKey][$dayKey])):

                    foreach($menus[$weekKey][$dayKey] as $menu):
                ?>

                    <div class="prato">

                        <div class="nome">
                            <?= htmlspecialchars($menu['name']) ?>
                        </div>

                        <div class="preco">
                            <?= number_format($menu['price'], 2) ?> €
                        </div>

                    </div>

                <?php
                    endforeach;

                else:
                ?>

                    <p class="buit">
                        Sense menú
                    </p>

                <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

        </div>

    </div>

<?php endforeach; ?>

</div>

<!-- FOOTER -->

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

            <a href="#">Cookies</a>
            <br>
            <a href="#">Avís legal</a>
            <br>
            <a href="#">Protecció de dades</a>

        </div>

    </div>

    <div class="footer-bottom">
        &copy; <?= date("Y") ?> Institut Pedralbes - Cantina
    </div>

</footer>

</body>
</html>