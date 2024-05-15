<?php
if (isset($_POST['enviar'])) {

    include_once('config.php');


    $nome_item = $_POST['nome_item'];
    $quantidade = $_POST['quantidade'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];
    $data_atual = $_POST['data_entrada'];


    $resultadoestoque = mysqli_query($conexao, "INSERT INTO estoque(nome_item,quantidade,categoria,descricao, data_entrada) 
       VALUES('$nome_item','$quantidade','$categoria','$descricao', CURDATE())");
         if ($resultadoestoque) {
            header("Location: estoque_tabela.php");// retornar a tabela 
            exit(); 
        } 
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Estoque</title>
    <link rel="stylesheet" href="adicionar.css">
    <script src="script.js" defer></script>
</head>

<body>
    <a href="estoque_tabela.php" class="btn-voltar">Voltar para a Tabela</a>
    <div class="container">
        <h1>Adicionar Itens ao Estoque</h1>
        <form action="estoque.php" method="POST">
            <div class="formulario">
                <h2>Dados do Posto</h2>
                <div class="campo">
                    <label for="nome_item">Nome do Item:</label>
                    <input type="text" id="nome_item" name="nome_item" required>
                </div>
                <div class="campo">
                    <label for="quantidade">Quantidade do item:</label>
                    <input type="text" id="quantidade" name="quantidade" required>
                </div>
                <div class="campo">
                    <label for="categoria">Categoria:</label>
                    <input type="text" id="categoria" name="categoria" required>
                </div>
                <div class="campo">
                    <label for="descricao">Descricao:</label>
                    <textarea id="descricao" name="descricao"></textarea>
                </div>
                <div class="campo">
                    <input type="submit" name="enviar" value="Enviar">
                </div>
            </div>

        </form>
    </div>
    <div class="loader"></div>
</body>

</html>