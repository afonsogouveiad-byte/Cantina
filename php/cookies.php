<!DOCTYPE html>
<html lang="ca">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Cookies</title>

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

/* HERO */

.hero{
    height:280px;
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
    z-index:2;
    color:white;
    font-size:48px;
    text-align:center;
    letter-spacing:1px;
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

.back{
    display:block;
    width:max-content;
    margin:24px auto 0;
    padding:12px 20px;
    background:var(--primary);
    color:white;
    border-radius:12px;
    text-decoration:none;
    box-shadow:0 12px 25px rgba(13,76,157,0.14);
    transition:background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
}
.back:hover{
    background:var(--primary-soft);
    transform:translateY(-1px);
    box-shadow:0 14px 28px rgba(13,76,157,0.18);
}

.card h2{
    color:var(--primary);
    margin-bottom:20px;
    font-size:30px;
}

.card p{
    line-height:1.9;
    color:#333;
    font-size:1rem;
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

<!-- HERO -->

<div class="hero">
    <h1>Política de Cookies</h1>
</div>

<!-- CONTENT -->

<div class="container">

    <div class="card">

        <h2>Ús de cookies</h2>

        <p>
            Amb aquest avís, l’Institut Pedralbes de Barcelona comunica als usuaris/àries
            que utilitzen galetes (cookies) quan naveguen per les diferents pantalles
            i pàgines del web.
        </p>

        <br>

        <p>
            Les galetes utilitzades per l’Institut Pedralbes són emmagatzemades
            al disc dur de l’usuari/ària però no poden llegir les dades contingudes
            al disc ni els arxius creats per altres proveïdors.
        </p>

        <br>

        <p>
            L’Institut Pedralbes utilitza les galetes amb l’objectiu de reconèixer
            paràmetres dels usuaris/àries a efectes estadístics i per millorar el servei.
        </p>

        <br>

        <p>
            Així mateix, són utilitzades de manera totalment anònima sobre dades
            d’accés (data, hora, minut, freqüència, etc.), per a mesurar alguns
            paràmetres de trànsit en el lloc web i calcular el nombre de visites,
            de manera que l’Institut Pedralbes pugui ajustar els serveis oferts.
        </p>

    </div>



</div>

<!-- FOOTER -->
    <div style="max-width:1000px;margin:0 auto;padding:0 20px;">
        <a class="back" href="javascript:history.back()">← Tornar enrere</a>
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

            <a href="cookies.php" >Cookies</a>
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