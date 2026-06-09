<!DOCTYPE html>
<html lang="ca">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Protecció de Dades</title>

<link rel="icon" href="images/inspedr.jpg" type="image/jpeg">

<style>

:root {
    --primary: #0d4c9d;
    --accent: #00a2ff;
    --text: #172c45;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#eef4fc;
    color:var(--text);
}

a {
    color: inherit;
    text-decoration: none;
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
    margin: 0 !important;
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
    height:260px;
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
    font-size:48px;
    text-align:center;
}

/* CONTENT */
.container{
    max-width:1000px;
    margin:50px auto;
    padding:0 20px;
}

.card{
    background:white;
    border-radius:18px;
    padding:40px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
    animation:fadeInUp 0.6s ease both;
}


.card h2{
    color:var(--primary);
    margin-bottom:20px;
    font-size:28px;
}

.card p{
    line-height:1.8;
    margin-bottom:14px;
    color:#333;
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

    .hero h1{
        font-size:36px;
    }

    .card{
        padding:25px;
    }

    .footer-container{
        grid-template-columns:1fr;
    }
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

<!-- NAV -->
<div class="nav">
    <div class="logo"><a class="back" href="javascript:history.back()"><img src="images/inspedr.jpg" alt="Institut Pedralbes"></a></div>
    <div class="nav-links">
        <a class="active" href="privacy.php">Protecció de Dades</a>
    </div>
    <div class="nav-right"></div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Protecció de Dades</h1>
</div>

<!-- CONTENT -->
<div class="container">

    <div class="card">

        <h2>Tractament de dades personals</h2>

        <p>
            L’Institut Pedralbes de Barcelona garanteix la protecció i confidencialitat
            de les dades personals proporcionades pels usuaris/àries del lloc web.
        </p>

        <p>
            Les dades recollides s’utilitzen exclusivament per a finalitats administratives,
            de gestió del servei i comunicació amb els usuaris/àries.
        </p>

        <p>
            En cap cas les dades seran cedides a tercers sense consentiment previ,
            excepte obligació legal.
        </p>

        <p>
            Els usuaris poden exercir els seus drets d’accés, rectificació, cancel·lació
            i oposició contactant amb el centre.
        </p>

    </div>


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