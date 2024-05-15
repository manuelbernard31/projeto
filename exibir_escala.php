<?php
include('config.php');

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login_cliente.php");
    exit();
}

$email = $_SESSION['email'];

// Consulta SQL para buscar as escalas de trabalho do usuário logado
$sql_escalas = "SELECT escalas_trabalho.id, 
                    funcionarios.nome_funcionario, 
                    funcionarios.num_telemovel,
                    postos.nome_posto,
                    escalas_trabalho.data_inicio,
                    escalas_trabalho.data_fim,  
                    escalas_trabalho.horario_inicio,
                    escalas_trabalho.horario_fim,
                    escalas_trabalho.horas_trabalho,
                    escalas_trabalho.observacoes
                FROM escalas_trabalho
                INNER JOIN funcionarios ON escalas_trabalho.id_funcionario = funcionarios.id_funcionario
                INNER JOIN postos ON escalas_trabalho.id_posto = postos.id_posto
                INNER JOIN usuarios ON postos.id_usuario = usuarios.id_usuario
                WHERE usuarios.email = '$email'";


$resultado_escalas = $conexao->query($sql_escalas);

function calcularTotalHorasTrabalho($horario_inicio, $horario_fim)
{
    // Converter os horários para objetos DateTime
    $inicio = new DateTime($horario_inicio);
    $fim = new DateTime($horario_fim);

    // Calcular a diferença entre os horários
    $diferenca = $inicio->diff($fim);

    // Obter o total de horas e minutos trabalhados
    $total_horas = $diferenca->h; // Horas
    $total_minutos = $diferenca->i; // Minutos

    // Calcular o total em minutos
    $total_minutos_trabalho = ($total_horas * 60) + $total_minutos;

    // Converter o total de minutos para horas e minutos formatados
    $total_horas_trabalho = floor($total_minutos_trabalho / 60);
    $total_minutos_trabalho_formatado = $total_minutos_trabalho % 60;

    // Retornar o total de horas de trabalho formatado
    return $total_horas_trabalho . " horas e " . $total_minutos_trabalho_formatado . " minutos";
}

?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <title>Lista de Escalas de Trabalho</title>
</head>

<body>
    <h3>Funcionalidades Adicionais:</h3>
    <ul>
        <li><a href="segurancas_postos.php">Segurança no Posto</a></li>
        <li><a href="exibir_escala.php">Adicionar Escala de Trabalho</a></li>
        <li><a href="registrar_falta.php">Registrar Falta</a></li>
        <li><a href="enviar_reclamacao.php">Enviar Reclamação</a></li>
    </ul>
    <a href="logout_cliente.php">Sair</a>
    <h3>Lista de Escalas de Trabalho</h3>
    <a href="adicionar_escala.php" style="padding-left: 30px;"><button type="submit" class="add_funcionario">Adicionar Escala</button></a>
    <table>
        <thead>
            <tr>
                <th>Nº</th>
                <th>Funcionário</th>
                <th>Posto de Trabalho</th>
                <th>Numero de Telemovel</th>
                <th>Data de Início</th>
                <th>Data de Fim</th>

                <th>Horário de Início</th>
                <th>Horário de Fim</th>
                <th>Horas de Trabalho</th>
                <th>Observações</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($escala = $resultado_escalas->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $escala['id']; ?></td>
                    <td><?php echo $escala['nome_funcionario']; ?></td>
                    <td><?php echo $escala['nome_posto']; ?></td>
                    <td><?php echo $escala['num_telemovel']; ?></td>
                    <td><?php echo $escala['data_inicio']; ?></td>
                    <td><?php echo $escala['data_fim']; ?></td>

                    <td><?php echo $escala['horario_inicio']; ?></td>
                    <td><?php echo $escala['horario_fim']; ?></td>
                    <td><?php echo calcularTotalHorasTrabalho($escala['horario_inicio'], $escala['horario_fim']); ?></td>
                    <td><?php echo $escala['observacoes']; ?></td>
                    <td>
                        <a class='buttons' href='eliminar_escala.php?id=<?php echo $escala['id']; ?>'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5' />
                            </svg>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
    <div class="loader"></div>
</body>

</html>