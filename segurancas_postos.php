<?php
include_once('config.php');

// Verificar se o usuário está logado
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login_cliente.php");
    exit();
}
// Obter o email do segurança logado
$email = $_SESSION['email'];

// Consulta SQL para obter funcionários atribuídos ao segurança pelo email
$sql_funcionarios_seguranca = "SELECT funcionarios.nome_funcionario, postos.nome_posto, postos.localizacao
                               FROM funcionarios 
                               INNER JOIN funcionario_posto ON funcionarios.id_funcionario = funcionario_posto.id_funcionario 
                               INNER JOIN usuarios ON funcionario_posto.id_usuario = usuarios.id_usuario
                               INNER JOIN postos ON funcionario_posto.id_posto = postos.id_posto
                               WHERE funcionarios.cargo = 'Segurança' 
                               AND usuarios.email = '$email'";

$resultado_funcionarios_seguranca = $conexao->query($sql_funcionarios_seguranca);

if (!$resultado_funcionarios_seguranca) {
    die("Erro ao obter funcionários atribuídos ao segurança: " . $conexao->error);
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Cliente</title>
</head>
<body>
    
<h3>Funcionários de Segurança</h3>
        <!-- Tabela para exibir os resultados -->
        <table border="1">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Posto</th>
                    <th>Localização</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($funcionario = $resultado_funcionarios_seguranca->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $funcionario['nome_funcionario']; ?></td>
                        <td><?php echo $funcionario['nome_posto']; ?></td>
                        <td><?php echo $funcionario['localizacao']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

<hr>
<h3>Funcionalidades Adicionais:</h3>
<ul>
<li><a href="cliente.php">Painel</a></li>
    <li><a href="exibir_escala.php">Adicionar Escala de Trabalho</a></li>
    <li><a href="registrar_falta.php">Registrar Falta</a></li>
    <li><a href="enviar_reclamacao.php">Enviar Reclamação</a></li>
</ul>
<a href="logout_cliente.php">Sair</a>
</body>
</html>
