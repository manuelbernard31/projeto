<?php
include_once('config.php');
include('protect.php');
$sql_funcionarios = "SELECT * FROM funcionarios WHERE cargo = 'Segurança'";
$resultado_funcionario = $conexao->query($sql_funcionarios);

if (isset($_GET['funcionario']) && isset($_GET['estoque'])) {
    $funcionario = $_GET['funcionario'];
    $estoque_selecionado = $_GET['estoque'];

    // Verificar se o registro já existe na tabela funcionario_posto
    $sql_verifi = "SELECT * FROM funcionario_item_estoque WHERE id_funcionario = $funcionario AND id_item = (SELECT id_item FROM estoque WHERE nome_item = '$estoque_selecionado')";
    $resultado_verifi = $conexao->query($sql_verifi);

    if ($resultado_verifi->num_rows == 0) {
        // Inserir os dados na tabela funcionario_posto
        $sql_insert = "INSERT INTO funcionario_item_estoque (id_funcionario, id_item) VALUES ($funcionario, (SELECT id_item FROM estoque WHERE nome_item = '$estoque_selecionado'))";
        if ($conexao->query($sql_insert) === TRUE) {
        }
    }
}

// Consulta SQL para obter os funcionários e os postos associados
$sql_junto = "SELECT funcionarios.nome_funcionario, estoque.nome_item, funcionarios.cargo, funcionarios.id_funcionario
               FROM funcionarios 
               INNER JOIN funcionario_item_estoque ON funcionarios.id_funcionario = funcionario_item_estoque.id_funcionario 
               INNER JOIN estoque ON funcionario_item_estoque.id_item = estoque.id_item";
$resultado_junto = $conexao->query($sql_junto);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Funcionário e Item</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>

<body>
<?php include_once 'menu.php' ?>
    <div class="container-funcionario">
        <h3>Atribuição dos funcionários aos Itens</h3>
        <form action="" method="get">
            <select style="border-radius: 5px; margin: 20px 50px; background-color: #906921; color:#fff;" <?php if (isset($_GET['funcionario'])) ?> required name="funcionario" id="funcionario">
                <option value="">Escolha um funcionário</option>
                <?php while ($funcionario = $resultado_funcionario->fetch_assoc()) { ?>
                    <option <?php if (isset($_GET['funcionario']) && $_GET['funcionario'] == $funcionario['id_funcionario']) echo "selected"; ?> value="<?php echo $funcionario['id_funcionario']; ?>"><?php echo $funcionario['nome_funcionario']; ?></option>
                <?php } ?>
            </select>
            <?php if (isset($_GET['funcionario'])) { ?>
                <select style="border-radius: 5px; border:none; background-color: #906921; color:#fff;" required name="estoque" id="estoque">
                    <option value="">Escolha um Item</option>
                    <?php
                    $sql_estoque = "SELECT nome_item FROM estoque";
                    $resultado_estoque = $conexao->query($sql_estoque);
                    while ($estoque = $resultado_estoque->fetch_assoc()) { ?>
                        <option value="<?php echo $estoque['nome_item']; ?>"><?php echo $estoque['nome_item']; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
            <button style=" border-radius: 5px; cursor:pointer; padding: 2px 10px; border:none; background-color: #906921; color:#fff" type="submit">Enviar</button>
        </form>

        <!-- Tabela para exibir os resultados -->
        <table border="1">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Cargo</th>
                    <th>Nome do Item</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($atribuicoes = $resultado_junto->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $atribuicoes['nome_funcionario'] . "</td>";
                    echo "<td>" . $atribuicoes['cargo'] . "</td>";
                    echo "<td>" . $atribuicoes['nome_item'] . "</td>";

                    echo "<td>
                    <a class='buttons' href='atribuicoes_estoque_eliminar.php?id_funcionario=$atribuicoes[id_funcionario]'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                    <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                    </svg>
                        </a>
                        
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="loader"></div>
</body>

</html>