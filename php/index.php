<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantina - Institut Pedralbes</title>
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

        a {
            color: inherit;
            text-decoration: none;
        }

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

        .main {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 26px;
            max-width: 1180px;
            margin: 0 auto;
            padding: 28px 24px 40px;
        }

        .left {
            display: grid;
            gap: 16px;
            align-content: start;
        }

        .left::before {
            
            display: block;
            margin-bottom: 10px;
            font-size: 0.95rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 20px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            font-weight: 700;
            transition: transform 0.24s ease, box-shadow 0.24s ease, background 0.24s ease;
            box-shadow: 0 18px 30px rgba(0, 162, 255, 0.18);
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 42px rgba(0, 162, 255, 0.22);
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 28px 30px;
            box-shadow: var(--shadow);
            margin-bottom: 22px;
            animation: fadeInUp 0.85s ease both;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 30px 72px rgba(15, 41, 78, 0.12);
        }

        .card h3 {
            margin-bottom: 14px;
            font-size: 1.35rem;
            color: #11223d;
        }

        .card h4 {
            margin: 20px 0 8px;
            font-size: 1rem;
            color: var(--text);
        }

        .card p {
            line-height: 1.8;
            color: var(--muted);
            margin-bottom: 12px;
        }

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

        @media (max-width: 1024px) {
            .main {
                grid-template-columns: 1fr;
                margin-top: -40px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 36px 18px;
            }

            .main {
                padding: 0 18px 32px;
            }

            .footer-container {
                grid-template-columns: 1fr;
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

<div class="hero">
    <div class="hero-content">
        <h1>Cantina</h1>
    </div>
</div>

<div class="main">

    <div class="left">
        <a class="btn" href="products.php">Productes</a>
        <a class="btn" href="menu.php">Menú Infantil</a>
        <a class="btn" href="menu2.php">Menú General</a>
        <a class="btn" href="login.php">Admin</a>
    </div>

    <div>

        <div class="card">
            <h3>Informació de l'Institut Pedralbes</h3>
            <p>
                L’Institut Pedralbes és un centre d’educació secundària públic situat a Barcelona.
                Ofereix ESO, Batxillerat i cicles formatius amb aposta per la innovació i tecnologia.
            </p>
            <p>
                Aquest sistema de cantina permet consultar productes i menús de manera ràpida i accessible.
            </p>

        </div>

        <div class="card">
            <h3>Horaris de la cantina</h3>

            <h4>Dinar</h4>
            <p>Dilluns - Divendres: 13:00 - 16:00</p>

            <h4>Aperitiu / Snack</h4>
            <p>Dilluns - Divendres: 07:00 - 13:00 / 16:00 - 19:00</p>
        </div>

    </div>

</div>

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
            <a href="cookies.php">Cookies</a>
            <br>
            <a href="legal.php">Avís legal</a>
            <br>
            <a href="privacy.php">Protecció de dades</a>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Institut Pedralbes - Cantina</p>
    </div>

</footer>

</body>

</html>