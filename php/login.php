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
<title>Login</title>
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

body{font-family:Arial;background:#eef4fc;display:flex;justify-content:center;align-items:center;height:100vh}
.box{background:#fff;padding:30px;border-radius:12px;width:300px; animation: fadeInUp 0.9s ease both;}
input{width:100%;padding:10px;margin:8px 0}
button{width:100%;padding:10px;background:var(--accent);color:#fff;border:none}

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

<div class="box">
<h2>Login</h2>

<form method="POST">
<input name="username" placeholder="User">
<input type="password" name="password" placeholder="Pass">
<button>Entrar</button>
</form>

<p style="color:red"><?= $error ?></p>
</div>

</body>
</html>