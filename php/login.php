<?php
session_start();
require_once 'connexio.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['user'] = $user;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Login inválido";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>inici de sessió</title>
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
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.box {
    background: var(--surface);
    padding: 40px;
    border-radius: 20px;
    width: 100%;
    max-width: 400px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    animation: fadeInUp 0.9s ease both;
}

.box h2 {
    text-align: center;
    margin-bottom: 24px;
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--text);
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

input {
    width: 100%;
    padding: 14px 16px;
    margin: 8px 0 16px 0;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    background: var(--surface);
    color: var(--text);
}

input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(0, 162, 255, 0.1);
}

button {
    width: 240px;
    padding: 14px 16px;
    background: linear-gradient(135deg, var(--accent), var(--primary-soft));
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 162, 255, 0.3);
    margin: 0 auto 24px;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 162, 255, 0.4);
}

.error {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 16px;
    font-size: 0.9rem;
    font-weight: 500;
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

<div class="box">
<h2>inici de sessió</h2>

<form method="POST">
<input name="username" placeholder="User">
<input type="password" name="password" placeholder="Pass">
<button>Entrar</button>
</form>

<p style="text-align:center; margin-bottom: 24px;"><a href="index.php" style="color:#0d4c9d; text-decoration:none; font-weight:700;">Tornar</a></p>

<p style="color:red"><?= $error ?></p>
</div>

</body>
</html>