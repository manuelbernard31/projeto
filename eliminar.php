<?php
include('protect.php'); // Verifica se o usuário está logado
include_once('config.php');
mysqli_set_charset($conexao, "utf8");


// Verifica se o usuário logado é um administrador ('Admin')
if ($_SESSION['perfil'] !== 'Admin') {
    // Redireciona para uma página de acesso negado ou exibe uma mensagem de erro
    echo "Você não tem permissão para excluir funcionários.";
    exit(); // Encerra o script
}

// Continua com o código de exclusão apenas se o usuário for um administrador
if (isset($_GET['id_funcionario'])) {
    $id_funcionario = $_GET['id_funcionario'];

    // Código para excluir o funcionário com base no $id_funcionario
    // Exemplo:
    $sql = "DELETE FROM funcionarios WHERE id_funcionario = $id_funcionario";
    if ($conexao->query($sql) === TRUE) {
        header("Location: tabela_funcionario.php");
    } else {
        echo "Erro ao excluir funcionário: " . $conexao->error;
    }
} 
?>
