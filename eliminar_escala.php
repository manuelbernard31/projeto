<?php
include_once('config.php');

if (!empty($_GET['id'])) {
    $id_funcionario = $_GET['id'];

    $sqlSelecionarescala = "SELECT * FROM escalas_trabalho WHERE id = {$id_funcionario}"; 

    $resultadoescala = $conexao->query($sqlSelecionarescala);

    if($resultadoescala)
    {
        $sqlEliminarescala = "DELETE FROM escalas_trabalho WHERE id = {$id_funcionario}"; 
        $resultadoEliminarescala = $conexao->query($sqlEliminarescala); 
    }
    header('Location: exibir_escala.php');
}
?>