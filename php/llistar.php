<?php
require_once 'connexio.php';
$result = $conn->query("SELECT * FROM products");
?>

<?php if(isset($_SESSION['user'])): ?>
    <div class="actions">
        <a class="edit" href="edit.php?id=<?= $row['id'] ?>">Editar</a>
        <a class="delete" href="delete.php?id=<?= $row['id'] ?>">Apagar</a>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<title>Productes de menjador</title>

<style>
    .hero {
    width: 100%;
    height: 420px;
    background: url('images/cantina.png') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    position: relative;
}

.hero::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.45);
}

.hero h1 {
    position: relative;
    font-size: 32px;
    z-index: 1;
}

body {
    font-family: Arial;
    margin: 0;
    background: #f4f6f8;
}

.nav {
    background: #111;
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    padding: 10px 20px;
    position: sticky;
    top: 0;
}

.logo img {
    height: 40px;
}

.nav-links {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.nav a {
    color: #ddd;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.25s;
}

.nav a:hover {
    background: #1abc9c;
    color: white;
}

.nav a.active {
    background: #1abc9c;
    color: white;
}

.nav-right {
    display: flex;
}

h1 {
    text-align: center;
    margin: 20px 0;
}

.grid {
    max-width: 1000px;
    margin: auto;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 20px;
}

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }

    .nav {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .nav-links {
        flex-direction: column;
        margin-top: 10px;
    }
}

.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.product-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.info {
    padding: 15px;
}

.name {
    font-weight: bold;
    font-size: 18px;
}

.price {
    color: #1abc9c;
    font-weight: bold;
}

.stock.low {
    color: red;
}

.footer {
            background: #111;
            color: white;
            margin-top: 40px;
        }

        .footer-container {
            max-width: 1100px;
            margin: auto;
            padding: 30px 20px;

            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .footer-section h3,
        .footer-section h4 {
            margin-bottom: 10px;
        }

        .footer-section p,
        .footer-section a {
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-section a {
            color: #1abc9c;
            text-decoration: none;
        }

        .footer-section a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            text-align: center;
            padding: 15px;
            border-top: 1px solid #333;
            font-size: 13px;
        }

  
        @media (max-width: 768px) {
            .main {
                grid-template-columns: 1fr;
            }

            .footer-container {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 28px;
            }
        }
</style>
</head>

<body>

<div class="nav">

    <div class="logo">
        <img src="images/inspedr.jpg" alt="Institut Pedralbes">
    </div>

    <div class="nav-links">
        <a href="index.php">Inici</a>
        <a class="active" href="llistar.php">Productes</a>
        <a href="menu.php">Menú Petits</a>
        <a href="menu2.php">Menú Grandes</a>
    </div>

    <div class="nav-right"></div>

</div>

<div class="hero">
    <h1>Productes de menjador</h1>
</div>

<div class="grid">

<?php while($row = $result->fetch_assoc()): ?>
    <div class="card">


        <div class="info">
            <div class="name"><?= $row['name'] ?></div>
            <div class="price"><?= $row['price'] ?> €</div>
            <div class="category"><?= $row['category']?></div>
        </div>

    </div>
<?php endwhile; ?>

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
</body>
</html>