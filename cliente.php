<?php
// Incluir arquivo de conexão com o banco de dados e verificar a sessão do cliente
include('config.php');

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login_cliente.php");
    exit();
}

// Consulta SQL para obter as informações do cliente e do posto associado a ele
$email = $_SESSION['email']; // Supondo que o e-mail do cliente está na sessão
$query = "SELECT p.nome_posto, p.localizacao 
          FROM postos p
          INNER JOIN clientes c ON p.id_cliente = c.id_cliente
          INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
          WHERE u.email='$email'";
$resultado = mysqli_query($conexao, $query);

if (!$resultado) {
    echo "Erro na consulta SQL: " . mysqli_error($conexao);
    exit();
}

if (mysqli_num_rows($resultado) == 1) {
    $posto = mysqli_fetch_assoc($resultado);
} else {
    echo "Erro ao obter informações do posto.";
    exit();
}

$queryUsuario = "SELECT nome, email FROM usuarios WHERE email = '$email' AND perfil = 'Cliente'";
$resultadoUsuario = mysqli_query($conexao, $queryUsuario);

if (!$resultadoUsuario) {
    die("Erro na consulta SQL do usuário: " . mysqli_error($conexao));
}

if (mysqli_num_rows($resultadoUsuario) == 1) {
    $usuario = mysqli_fetch_assoc($resultadoUsuario);
} else {
    die("Erro ao obter informações do usuário.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
</head>
<body>
    <h2>Painel do Cliente</h2>
    <h3>Informações do Usuário:</h3>
    <p>Nome do Usuário: <?php echo $usuario['nome']; ?></p>
    <p>Email do Usuário: <?php echo $usuario['email']; ?></p>
    <hr>
    <hr>


    <h3>Informações do Posto:</h3>
    <p>Nome do Posto: <?php echo isset($posto['nome_posto']) ? $posto['nome_posto'] : 'N/A'; ?></p>
    <p>Localização: <?php echo isset($posto['localizacao']) ? $posto['localizacao'] : 'N/A'; ?></p>

    <hr>

    <h3>Funcionalidades Adicionais:</h3>
    <ul>
        <li><a href="segurancas_postos.php">Segurança no Posto</a></li>
        <li><a href="exibir_escala.php">Adicionar Escala de Trabalho</a></li>
        <li><a href="registrar_falta.php">Registrar Falta</a></li>
        <li><a href="enviar_reclamacao.php">Enviar Reclamação</a></li>
    </ul>
    <a href="logout_cliente.php">Sair</a>
</body>
</html>