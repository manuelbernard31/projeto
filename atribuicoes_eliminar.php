<?php
include_once('config.php');

session_start();
// Verifica se o usuário está logado e se é um administrador ('Admin')
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Admin') {
    // Redireciona para uma página de acesso negado ou exibe uma mensagem de erro
    echo "Você não tem permissão para excluir funcionários de postos.";
    exit(); // Encerra o script
}

if (!empty($_GET['id_funcionario'])) {
    $id_funcionario = $_GET['id_funcionario'];

    $sqlSelecionarfuncionario = "SELECT * FROM funcionario_posto WHERE id_funcionario = $id_funcionario"; 
    $resultadofuncionario = $conexao->query($sqlSelecionarfuncionario);

    if ($resultadofuncionario) {
        $sqlEliminarfuncionario = "DELETE FROM funcionario_posto WHERE id_funcionario = $id_funcionario"; 
        $resultadoEliminarfuncionario = $conexao->query($sqlEliminarfuncionario); 
        if ($resultadoEliminarfuncionario) {
            header('Location: atribuicoes.php');
            exit();
        } else {
            echo "Erro ao excluir o funcionário do posto: " . $conexao->error;
        }
    } 
} 
?>
