<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM menus ORDER BY week, day, id");
?>

<!DOCTYPE html>
<html lang="ca">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menú Infantil</title>
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
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial, sans-serif;}
body{background:#eef4fc;color:#222;}
.nav{background: var(--primary);display:grid;grid-template-columns:1fr auto 1fr;align-items:center;padding:12px 24px;position:sticky;top:0;z-index:1000;box-shadow:0 4px 20px rgba(13,76,157,0.15);}
.logo img{height:45px;border-radius:8px;}
.logo a{display:inline-block;padding:0 !important;border-radius:0 !important;background:transparent !important;color:inherit !important;text-decoration:none !important;transition:none !important;}
.logo a:hover{background:transparent !important;transform:none !important;}
.nav-links{display:flex;gap:8px;justify-content:center;}
.nav a{color:white;text-decoration:none;padding:10px 18px;border-radius:10px;font-weight:600;transition:all 0.25s cubic-bezier(0.4,0,0.2,1);font-size:0.95rem;}
.nav a:hover,.nav a.active{background:var(--accent);color:white;transform:translateY(-1px);}
.nav-right{display:flex;justify-content:flex-end;}
.logout a{background:linear-gradient(135deg,#e74c3c,#c0392b);color:white;padding:10px 16px;border-radius:10px;text-decoration:none;font-weight:600;}
.hero{height:240px;background:url('images/cantina.png')center/cover;display:flex;justify-content:center;align-items:center;position:relative;}
.hero::before{content:"";position:absolute;inset:0;background:rgba(13,76,157,0.55);}
.hero h1{position:relative;z-index:2;color:white;font-size:42px;text-align:center;letter-spacing:1px;}
.admin-container{max-width:1200px;margin:40px auto;padding:20px;}
.action-bar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px;}
.action-bar h2{font-size:28px;color:#111;}
.action-bar a.button{background:var(--accent);color:white;padding:12px 20px;border-radius:12px;text-decoration:none;font-weight:700;transition:transform 0.2s ease,box-shadow 0.2s ease;}
.action-bar a.button:hover{transform:translateY(-2px);box-shadow:0 12px 20px rgba(0,162,255,0.2);}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;}
.card{background:white;border-radius:16px;box-shadow:0 8px 25px rgba(0,0,0,0.08);overflow:hidden;transition:transform 0.25s ease,box-shadow 0.25s ease;}
.card:hover{transform:translateY(-5px);box-shadow:0 16px 32px rgba(0,0,0,0.1);}
.card-body{padding:18px;}
.card-body h3{font-size:20px;margin-bottom:10px;}
.meta{color:#5f6f86;margin-bottom:10px;}
.actions{display:flex;gap:10px;flex-wrap:wrap;}
.actions a{display:inline-block;text-decoration:none;padding:10px 14px;border-radius:10px;font-weight:700;color:white;}
.actions a.edit{background:var(--primary-soft);} .actions a.delete{background:#e74c3c;}
.actions a.edit:hover{background:var(--primary);} .actions a.delete:hover{background:#c0392b;}

.setmana{margin-bottom:40px;}
.setmana-title{font-size:32px;margin-bottom:20px;color:#111;border-left:6px solid var(--accent);padding-left:15px;}
.dies-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;grid-auto-rows:1fr;}
.dia-card{background:white;border-radius:14px;overflow:hidden;box-shadow:0 6px 20px rgba(0,0,0,0.08);transition:0.25s;animation:fadeInUp 0.85s ease both;display:flex;flex-direction:column;height:100%;}
.dia-card:hover{transform:translateY(-4px);}
.dia-header{background:var(--primary);color:white;text-align:center;padding:14px;font-size:17px;font-weight:bold;}
.dia-content{padding:18px;display:flex;flex-direction:column;flex:1;}
.prato{background:#f8f9fb;border-radius:12px;padding:14px;margin-bottom:14px;border:1px solid #ececec;transition:0.2s;}
.prato:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.06);}
.prato:last-child{margin-bottom:0;}
.nome{font-size:16px;font-weight:bold;margin-bottom:8px;color:#222;}
.preco{color:var(--accent);font-weight:bold;margin-bottom:10px;font-size:15px;}
.buit{text-align:center;color:#888;padding:20px 0;}
.dia-content .actions{margin-top:12px;}
.dia-content .actions a{display:inline-block;text-decoration:none;padding:7px 12px;border-radius:8px;font-size:13px;margin-right:6px;color:white;font-weight:bold;transition:0.2s;}
.dia-content .actions a:hover{opacity:0.9;transform:scale(1.03);}
.dia-content .actions a:first-child{background:var(--primary-soft);}
.dia-content .actions a:last-child{background:#e74c3c;}

.empty{background:white;border-radius:16px;padding:40px;text-align:center;box-shadow:0 8px 25px rgba(0,0,0,0.08);}
footer{background:var(--primary);color:white;margin-top:40px;position:relative;}
.footer::before{content:"";position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--accent),var(--primary-soft));}
.footer-container{max-width:1180px;margin:0 auto;padding:40px 24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:32px;}
.footer-section h3,.footer-section h4{margin-bottom:16px;font-weight:600;color:white;}
.footer-section p,.footer-section a{font-size:0.95rem;line-height:1.7;color:rgba(255,255,255,0.9);}
.footer-section a{color:var(--accent);text-decoration:none;}
.footer-bottom{text-align:center;padding:20px 24px;border-top:1px solid rgba(255,255,255,0.1);font-size:0.9rem;color:rgba(255,255,255,0.7);}
@media(max-width:700px){.nav{grid-template-columns:1fr;gap:15px;}.nav-links{flex-wrap:wrap;justify-content:center;}.hero h1{font-size:32px;}.action-bar{flex-direction:column;align-items:flex-start;}.footer-container{grid-template-columns:1fr;}}    @keyframes fadeInUp {from {opacity:0;transform:translateY(30px);}to {opacity:1;transform:translateY(0);}}
</style>
</head>

<body>
<div class="nav">
    <div class="logo"><a href="admin.php"><img src="images/inspedr.jpg" alt="Institut Pedralbes"></a></div>
    <div class="nav-links">
        <a href="admin.php">Panell</a>
        <a href="admin_products.php">Productes</a>
        <a class="active" href="admin_menu.php">Menú Infantil</a>
        <a href="admin_menu2.php">Menú General</a>
    </div>
    <div class="nav-right"><a class="logout" href="logout.php">Tanca sessió</a></div>
</div>
<div class="hero"><h1>Admin Menú Infantil</h1></div>
<div class="admin-container">
    <div class="action-bar">
        <h2>Gestió del menú infantil</h2>
        <a href="add_menu.php" class="button">Afegir menú</a>
    </div>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php
            $menus = [];
            while ($row = $result->fetch_assoc()) {
                $week = $row['week'] ?? '';
                $day = $row['day'] ?? '';
                if ($week === '' || $day === '') continue;
                $menus[$week][$day][] = $row;
            }

            $dias = [
                'dilluns' => 'DILLUNS',
                'dimarts' => 'DIMARTS',
                'dimecres' => 'DIMECRES',
                'dijous' => 'DIJOUS',
                'divendres' => 'DIVENDRES',
            ];

            $semanas = [
                1 => 'SETMANA 1',
                2 => 'SETMANA 2',
                3 => 'SETMANA 3',
                4 => 'SETMANA 4',
            ];
        ?>

        <?php foreach ($semanas as $weekKey => $weekName): ?>
            <div class="setmana">
                <h2 class="setmana-title"><?= $weekName ?></h2>
                <div class="dies-grid">
                    <?php foreach ($dias as $dayKey => $dayName): ?>
                        <div class="dia-card">
                            <div class="dia-header"><?= $dayName ?></div>
                            <div class="dia-content">
                                <?php if (isset($menus[$weekKey][$dayKey])): ?>
                                    <?php foreach ($menus[$weekKey][$dayKey] as $menu): ?>
                                        <div class="prato">
                                            <?php if ($menu['image']): ?>
                                                <img src="images/<?= htmlspecialchars($menu['image']) ?>" alt="<?= htmlspecialchars($menu['name']) ?>" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                                            <?php endif; ?>
                                            <div class="nome"><?= htmlspecialchars($menu['name']) ?></div>
                                            <div class="preco"><?= number_format($menu['price'], 2) ?> €</div>
                                            <div class="actions">
                                                <a href="edit_menu.php?id=<?= $menu['id'] ?>">Editar</a>
                                                <a href="delete_menu.php?id=<?= $menu['id'] ?>" onclick="return confirm('Estàs segur que vols eliminar aquest menú?')">Eliminar</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="buit">Sense menú</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="empty">No s'ha trobat cap menú. Afegeix un element nou.</div>
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
</body>
</html>