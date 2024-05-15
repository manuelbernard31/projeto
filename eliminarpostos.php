<?php
include_once('config.php');

// Verifica se o usuário está logado e se é um administrador ('Admin')
session_start();
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Admin') {
    // Redireciona para uma página de acesso negado ou exibe uma mensagem de erro
    echo "Você não tem permissão para excluir postos.";
    exit(); // Encerra o script
}

if (!empty($_GET['id_posto'])) {
    $id_posto = $_GET['id_posto'];

    $sqlSelecionarposto = "SELECT * FROM postos WHERE id_posto = $id_posto"; 
    $resultadopostos = $conexao->query($sqlSelecionarposto);

    if ($resultadopostos) {
        $sqlEliminarpostos = "DELETE FROM postos WHERE id_posto = $id_posto"; 
        $resultadoEliminarpostos = $conexao->query($sqlEliminarpostos); 
        if ($resultadoEliminarpostos) {
            header('Location: postos_tabela.php');
            exit();
        } else {
            echo "Erro ao excluir o posto: " . $conexao->error;
        }
    } else {
        echo "Erro ao selecionar o posto: " . $conexao->error;
    }
} else {
    echo "ID do posto não especificado.";
}
?>
