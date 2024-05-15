<?php
session_start();

// Verifica se o usuário não está logado
if (!isset($_SESSION['id'])) {
    // Define a mensagem de erro
    $_SESSION['error_msg'] = "Você precisa fazer login para acessar esta página.";
    
    // Redireciona para a página de login
    header("Location: login_cliente.php");
    exit();
}
?>
