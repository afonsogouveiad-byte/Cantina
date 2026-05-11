<?php
session_start();
require_once 'connexio.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$conn->query("DELETE FROM products WHERE id=$id");

header("Location: admin.php");