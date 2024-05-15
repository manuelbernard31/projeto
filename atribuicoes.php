<?php
include('protect.php'); // Assegura que o usuário está logado
include_once('config.php');

// Consulta SQL para obter funcionários com cargo 'Segurança'
$sql_funcionario = "SELECT * FROM funcionarios WHERE cargo = 'Segurança'";
$resultado_funcionarios = $conexao->query($sql_funcionario);

$sql_emails_clientes =  "SELECT email FROM usuarios WHERE perfil = 'Cliente'";
$resultado_emails_clientes = $conexao->query($sql_emails_clientes);

if (isset($_GET['id_funcionario']) && isset($_GET['posto']) && isset($_GET['email'])) {
    $funcionario_id = $_GET['id_funcionario'];
    $posto_selecionado = $_GET['posto'];
    $email_usuario = $_GET['email'];

    // Obter o ID do usuário com base no email fornecido
    $sql_id_usuario = "SELECT id_usuario FROM usuarios WHERE email = '$email_usuario'";
    $resultado_id_usuario = $conexao->query($sql_id_usuario);
    $id_usuario = null;

    if ($resultado_id_usuario->num_rows > 0) {
        $row = $resultado_id_usuario->fetch_assoc();
        $id_usuario = $row['id_usuario'];

        // Verificar se o registro já existe na tabela funcionario_posto
        $sql_verificar = "SELECT * FROM funcionario_posto WHERE id_funcionario = $funcionario_id AND id_posto = (SELECT id_posto FROM postos WHERE nome_posto = '$posto_selecionado') AND id_usuario = $id_usuario";
        $resultado_verificar = $conexao->query($sql_verificar);

        if ($resultado_verificar->num_rows == 0) {
            // Inserir os dados na tabela funcionario_posto
            $sql_inserir = "INSERT INTO funcionario_posto (id_usuario, id_funcionario, id_posto) VALUES ($id_usuario, $funcionario_id, (SELECT id_posto FROM postos WHERE nome_posto = '$posto_selecionado'))";
            if ($conexao->query($sql_inserir) === TRUE) {
            } else {
            }
        }
    }
}

// Consulta SQL para obter os funcionários e os postos associados
$sql_juntar = "SELECT funcionarios.nome_funcionario, funcionarios.cargo, postos.nome_posto, postos.localizacao, funcionarios.id_funcionario
               FROM funcionarios 
               INNER JOIN funcionario_posto ON funcionarios.id_funcionario = funcionario_posto.id_funcionario 
               INNER JOIN postos ON funcionario_posto.id_posto = postos.id_posto
              ";

$resultado_juntar = $conexao->query($sql_juntar);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Funcionário e Posto</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>

<body>
    <?php include_once 'menu.php' ?>
    <div class="container-funcionario">
        <h3>Atribuição dos funcionários aos Postos</h3>
        <form action="" method="get">
            <select style=" margin: 20px 50px; border-radius: 5px; border:none; background-color: #906921; color:#fff;" <?php if (isset($_GET['id_funcionario'])) ?> autocomplete="off" required name="id_funcionario" id="id_funcionario">
                <option value="">Escolha um funcionário</option>
                <?php while ($funcionario = $resultado_funcionarios->fetch_assoc()) { ?>
                    <option <?php if (isset($_GET['id_funcionario']) && $_GET['id_funcionario'] == $funcionario['id_funcionario']) echo "selected"; ?> value="<?php echo $funcionario['id_funcionario']; ?>"><?php echo $funcionario['nome_funcionario']; ?></option>
                <?php } ?>
            </select>
            <?php if (isset($_GET['id_funcionario'])) { ?>
                <select style="border-radius: 10px; background-color: #906921; color:#fff;" autocomplete="off" required name="posto" id="posto">
                    <option value="">Escolha um posto</option>
                    <?php
                    $sql_postos = "SELECT nome_posto FROM postos";
                    $resultado_postos = $conexao->query($sql_postos);
                    while ($posto = $resultado_postos->fetch_assoc()) { ?>
                        <option value="<?php echo $posto['nome_posto']; ?>"><?php echo $posto['nome_posto']; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>
            <select style=" margin: 20px 50px; border-radius: 5px; border:none; background-color: #906921; color:#fff;" autocomplete="off" required name="email" id="email">
                <option value="">Escolha o email do cliente</option>
                <?php while ($cliente = $resultado_emails_clientes->fetch_assoc()) { ?>
                    <option value="<?php echo $cliente['email']; ?>"><?php echo $cliente['email']; ?></option>
                <?php } ?>
            </select>
            <button style=" border-radius: 5px; cursor:pointer;  padding: 2px 10px; border:none;background-color: #906921; color:#fff" type="submit">Enviar</button>
        </form>

        <!-- Tabela para exibir os resultados -->
        <table border="1">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Cargo</th>
                    <th>Posto</th>
                    <th>localização</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($atribuicoes = mysqli_fetch_assoc($resultado_juntar)) {
                    echo "<tr>";
                    echo "<td>" . $atribuicoes['nome_funcionario'] . "</td>";
                    echo "<td>" . $atribuicoes['cargo'] . "</td>";
                    echo "<td>" . $atribuicoes['nome_posto'] . "</td>";
                    echo "<td>" . $atribuicoes['localizacao'] . "</td>";
                    echo "<td>
                    <a class='buttons' href='atribuicoes_eliminar.php?id_funcionario=$atribuicoes[id_funcionario]'>
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