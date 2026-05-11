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
body{font-family:Arial;background:#f4f6f8;display:flex;justify-content:center;align-items:center;height:100vh}
.box{background:#fff;padding:30px;border-radius:12px;width:300px}
input{width:100%;padding:10px;margin:8px 0}
button{width:100%;padding:10px;background:#1abc9c;color:#fff;border:none}
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