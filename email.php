<?php
include('config.php');
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login_cliente.php");
    exit();
}

unset($_SESSION['id_funcionario']);
unset($_SESSION['id_posto']);
if (isset($_SESSION['email'])) {
    unset($_SESSION['email']);
}
?>