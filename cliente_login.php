<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['email'];  // Mudado de 'usuario' para 'email'
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE perfil = 'Cliente' AND email = '$usuario' AND senha = '$senha'";
    $resultado = $conexao->query($sql);

    if ($resultado->num_rows == 1) {
        // Login bem-sucedido
        $_SESSION['email'] = $usuario;
        header("Location: cliente.php");
        exit();  
    } else {
        echo "Email ou senha estão incorretos";  // Mensagem de erro
        exit(); 
    }
} 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="cliente_login.php"> <!-- Corrigido para POST e ação para login.php -->
        <label for="email">Email:</label> <!-- Corrigido de 'usuario' para 'email' -->
        <input type="text" id="email" name="email" required><br> <!-- Mudado de 'usuario' para 'email' -->

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
