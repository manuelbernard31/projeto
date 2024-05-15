<?php
include('config.php');

// Função para obter o dia da semana a partir de uma data
function getDiaSemana($data) {
    $timestamp = strtotime($data);
    $dia_semana = date('w', $timestamp); // 0 (Domingo) a 6 (Sábado)
    return $dia_semana;
}

// Verifica se o usuário está logado e obtém o id_usuario correspondente
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login_cliente.php");
    exit();
}
$email = $_SESSION['email'];
    $sql_usuario = "SELECT email FROM usuarios WHERE email = '$email' AND perfil = 'Cliente'";
    $result_usuario = $conexao->query($sql_usuario);
    if (!$result_usuario) {
        die("Erro na consulta SQL do usuário: " . mysqli_error($conexao));
    }
    
    if (mysqli_num_rows($result_usuario) == 1) {
        $usuario = mysqli_fetch_assoc($result_usuario);
    } else {
        die("Erro ao obter informações do usuário.");
    }
    

// Verifica se o formulário foi enviado
if (isset($_POST['id_funcionario'], $_POST['data_falta'], $_POST['motivo_falta'], $_POST['dia_semana'])) {
    $id_funcionario = $_POST['id_funcionario'];
    $data_falta = $_POST['data_falta'];
    $motivo_falta = $_POST['motivo_falta'];
    $dias_semana = $_POST['dia_semana'];

    // Converter array de dias da semana em string para usar na consulta SQL
    $dias_semana_str = implode(',', $dias_semana);

    // Obter o dia da semana da data selecionada
    $dia_semana_falta = getDiaSemana($data_falta);

    // Verificar se o dia da semana está na escala de trabalho do funcionário
    $sql_escalas = "SELECT id 
                    FROM escalas_trabalho 
                    WHERE id_funcionario = $id_funcionario 
                    AND data_inicio <= '$data_falta' 
                    AND data_fim >= '$data_falta'";

    $result_escalas = $conexao->query($sql_escalas);

    if ($result_escalas->num_rows > 0) {
        // Inserir falta na tabela dias_escala com o id_usuario
        if (!is_null($email)) {
            $sql_insert = "INSERT INTO dias_escala (escala_id, data, dia_semana, motivo_falta) 
                           SELECT id, '$data_falta', $dia_semana_falta,'$motivo_falta'
                           FROM escalas_trabalho 
                           WHERE id_funcionario = $id_funcionario 
                           AND data_inicio <= '$data_falta' 
                           AND data_fim >= '$data_falta'";

            if ($conexao->query($sql_insert) === TRUE) {
                echo "Falta marcada com sucesso!";
                // Redirecionar para evitar reenvio do formulário
                header("Location: ".$_SERVER['PHP_SELF']);
                exit;
            } else{
                echo "Erro ao marcar falta: " . $conexao->error;
            }
        } else {
            echo "Erro ao obter o id do usuário logado.";
        }
    } else {
        echo "O funcionário não trabalha neste(s) dia(s) da semana na escala atual.";
    }
} else {
    echo "Por favor, preencha todos os campos do formulário.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Faltas</title>
</head>
<body>
    <h1>Marcar Falta</h1>
    <form action="" method="POST">
        <label for="id_funcionario">ID do Funcionário:</label>
        <input type="text" id="id_funcionario" name="id_funcionario">
        <br>
        <label for="data_falta">Data da Falta:</label>
        <input type="date" id="data_falta" name="data_falta" min="<?php echo date('Y-m-d'); ?>"><br>
        <br>
        <label for="motivo_falta">Motivo da Falta:</label>
        <input type="text" id="motivo_falta" name="motivo_falta">
        <br>
        <div class="weekdays">
            <span>
                <label for="segunda">SEG <input type="checkbox" id="segunda" value="1" name="dia_semana[]"></label>
            </span>
            <span>
                <label for="terca">TER <input type="checkbox" id="terca" value="2" name="dia_semana[]"></label>
            </span>
            <span>
                <label for="quarta">QUA <input type="checkbox" id="quarta" value="3" name="dia_semana[]"></label>
            </span>
            <span>
                <label for="quinta">QUI <input type="checkbox" id="quinta" value="4" name="dia_semana[]"></label>
            </span>
            <span>
                <label for="sexta">SEX <input type="checkbox" id="sexta" value="5" name="dia_semana[]"></label>
            </span>
            <span>
                <label for="sabado">SÁB <input type="checkbox" id="sabado" value="6" name="dia_semana[]"></label>
            </span>
            <span>
                <label for="domingo">DOM <input type="checkbox" id="domingo" value="0" name="dia_semana[]"></label>
            </span>
        </div>
        <br>
        <button type="submit">Marcar Falta</button>
    </form>

    <hr>
    <p>EMAIL: <?php echo $usuario['email']; ?></p>
   

    <h3>Funcionalidades Adicionais:</h3>
    <ul>
    <li><a href="mostar_faltas.php">Faltas</a></li>
        <li><a href="segurancas_postos.php">Segurança no Posto</a></li>
        <li><a href="exibir_escala.php">Adicionar Escala de Trabalho</a></li>
        <li><a href="enviar_reclamacao.php">Enviar Reclamação</a></li>
    </ul>
    <a href="logout_cliente.php">Sair</a>

</body>
</html>
