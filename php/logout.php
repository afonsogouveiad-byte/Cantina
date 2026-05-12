<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        session_destroy();
        header('Location: index.php');
        exit();
    }

    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmar sortida</title>
<style>
:root {--primary:#0d4c9d;--accent:#00a2ff;--primary-soft:#3f7be6;--text:#172c45;--surface:#ffffff;--border:rgba(13,76,157,0.14);--shadow:0 24px 60px rgba(15,41,78,0.08);}*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;}body{min-height:100vh;background:#eef4fc;color:#222;display:flex;justify-content:center;align-items:center;padding:20px;} .box{background:#fff;padding:36px;border-radius:20px;max-width:460px;width:100%;box-shadow:var(--shadow);border:1px solid var(--border);} h2{text-align:center;margin-bottom:24px;font-size:2rem;color:#111;} p{margin-bottom:28px;color:#444;font-size:1rem;line-height:1.6;} .buttons{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;} .buttons button{padding:12px 24px;border:none;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;transition:transform 0.2s ease,box-shadow 0.2s ease;} .buttons button.confirm{background:linear-gradient(135deg,#e74c3c,#c0392b);color:white;} .buttons button.cancel{background:var(--primary-soft);color:white;} .buttons button:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,0.12);} </style>
</head>
<body>
<div class="box">
    <h2>Confirmar sortida</h2>
    <p>Estàs segur que vols tancar la sessió?</p>
    <form method="POST" class="buttons">
        <button type="submit" name="confirm" value="yes" class="confirm">Sí</button>
        <button type="submit" name="confirm" value="no" class="cancel">No</button>
    </form>
</div>
</body>
</html>