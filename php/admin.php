<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Panell Admin - Cantina</title>
<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

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

/* HERO */

.hero{
    height:320px;
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

.admin-container{
    max-width:1000px;
    margin:60px auto;
    padding:20px;
}

/* GRID DE BOTÕES */

.buttons-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
    gap:30px;
    margin-bottom:60px;
}

/* CARD DE BOTÃO */

.button-card{
    background:white;
    border-radius:16px;
    overflow:hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition:0.25s;
    animation: fadeInUp 0.85s ease both;
    text-decoration:none;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:40px 30px;
    cursor:pointer;
}

.button-card:hover{
    transform:translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}

.button-icon{
    font-size:60px;
    margin-bottom:20px;
}

.button-title{
    font-size:20px;
    font-weight:bold;
    color:#222;
    text-align:center;
}

.button-card.logout {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.button-card.logout .button-title {
    color: white;
}

.button-card.logout:hover {
    box-shadow: 0 12px 35px rgba(231, 76, 60, 0.3);
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

    .button-card{
        padding:30px 20px;
    }

    .button-icon{
        font-size:50px;
        margin-bottom:15px;
    }

    .button-title{
        font-size:18px;
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

<!-- NAVBAR -->

<div class="nav">

    <div class="logo">
        <a href="admin.php"><img src="images/inspedr.jpg" alt="Institut Pedralbes"></a>
    </div>

    <div class="nav-links">

        <a class="active" href="admin.php">Admin</a>
    </div>
    <div class="nav-right"><a class="logout" href="logout.php">Tanca sessió</a></div>

    <div></div>

</div>

<!-- HERO -->

<div class="hero">
    <h1>Panell d'administració</h1>
</div>

<!-- ADMIN PANEL -->

<div class="admin-container">

    <div class="buttons-grid">

        <a href="admin_products.php" class="button-card">
            <div class="button-icon">🥪</div>
            <div class="button-title">Productes</div>
        </a>

        <a href="admin_menu.php" class="button-card">
            <div class="button-icon">🍽️</div>
            <div class="button-title">Menú Infantil</div>
        </a>

        <a href="admin_menu2.php" class="button-card">
            <div class="button-icon">🍽️</div>
            <div class="button-title">Menú General</div>
        </a>

    

    </div>

</div>

<!-- FOOTER -->

<footer class="footer">

    <div class="footer-container">

        <div class="footer-section">

            <h3>El centre</h3>

            <p>
                Institut públic del districte de Les Corts,
                amb oferta d'ESO, Batxillerat,
                CFGM i CFGS d'Informàtica,
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