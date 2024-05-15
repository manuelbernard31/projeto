<?php
include_once('config.php');
session_start();

// Verifica se o usuário está logado e se é um administrador ('Admin')
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Admin') {
    // Redireciona para uma página de acesso negado ou exibe uma mensagem de erro
    echo "Você não tem permissão para excluir funcionários de itens de estoque.";
    exit(); // Encerra o script
}

if (!empty($_GET['id_funcionario'])) {
    $id_funcionario = $_GET['id_funcionario'];

    $sqlSelecionarFuncionario = "SELECT * FROM funcionario_item_estoque WHERE id_funcionario = $id_funcionario"; 
    $resultadoFuncionario = $conexao->query($sqlSelecionarFuncionario);

    if ($resultadoFuncionario) {
        $sqlEliminarFuncionario = "DELETE FROM funcionario_item_estoque WHERE id_funcionario = $id_funcionario"; 
        $resultadoEliminarFuncionario = $conexao->query($sqlEliminarFuncionario); 
        if ($resultadoEliminarFuncionario) {
            header('Location: atribuicoes_estoque.php');
            exit();
        } else {
            echo "Erro ao excluir o funcionário do item de estoque: " . $conexao->error;
        }
    } else {
        echo "Erro ao selecionar o funcionário do item de estoque: " . $conexao->error;
    }
} 
?>
