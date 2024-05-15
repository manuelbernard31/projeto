<?php
include('protect.php');
include('config.php');

if (isset($_POST['submit'])) {

    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_fim = $_POST['horario_fim'];
    $horas_trabalho = $_POST['horas_trabalho'];
    $observacoes = $_POST['observacoes'];
    $id_posto = $_POST['posto'];
    $id_funcionario = $_POST['funcionario'];

    // Verificar se a escala já existe para esse funcionário, posto e período
    $sql_verificar_escala = "SELECT * FROM escalas_trabalho 
                                 WHERE id_funcionario = $id_funcionario 
                                 AND id_posto = $id_posto 
                                 AND data_inicio = '$data_inicio'";
    $resultado_verificar_escala = $conexao->query($sql_verificar_escala);

    if ($resultado_verificar_escala->num_rows > 0) {
        // Escala já existe ou mostrar uma mensagem de aviso
    } else {
        // O funcionário existe e a escala não existe, podemos inserir na tabela escalas_trabalho
        $sqlescala = "INSERT INTO escalas_trabalho (data_inicio, data_fim, horario_inicio, horario_fim, horas_trabalho, observacoes, id_posto, id_funcionario) 
                        VALUES ('$data_inicio', '$data_fim', '$horario_inicio', '$horario_fim', '$horas_trabalho', '$observacoes', '$id_posto', '$id_funcionario')";

        if ($conexao->query($sqlescala) === TRUE) {
            $escala_id = $conexao->insert_id;
            $start_date = date_create($data_inicio);
            $end_date = date_create($data_fim);

            $interval = DateInterval::createFromDateString('1 day');
            $daterange = new DatePeriod($start_date, $interval, $end_date);

            $selectedDays = $_POST['dia_semana'];
            foreach ($daterange as $key => $date) {
                if (in_array($date->format('w'), $selectedDays)) {
                    $data = $date->format('Y-m-d');
                    $dia_semana = $date->format('w');

                    $dia_escala = "INSERT INTO dias_escala (escala_id, data, dia_semana) 
                        VALUES ('$escala_id', '$data', '$dia_semana')";

                    $conexao->query($dia_escala);
                }
            }
            echo "Nova escala de trabalho adicionada com sucesso!";
        } else {
            echo "Erro ao adicionar nova escala de trabalho: " . $conexao->error;
        }
    }
    if ($resultado_verificar_escala) {
        header("Location: exibir_escala.php"); // retornar a tabela 
        exit();
    }
}

$sql_funcionario = "SELECT * FROM funcionarios WHERE cargo = 'Segurança'";
$resultado_funcionarios = $conexao->query($sql_funcionario);

$sql_postos = "SELECT id_posto, nome_posto FROM postos";
$resultado_postos = $conexao->query($sql_postos);
?>

<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adicionar.css">
    <script src="script.js" defer></script>
    <title>Adicionar Escala de Trabalho</title>
</head>

<body>
    <h1>Adicionar Escala de Trabalho</h1>
    <a href="exibir_escala.php" class="btn-voltar">Voltar para a Tabela</a>
    <div class="container">
        <form action="" method="post">
            <select style="border-radius: 10px; background-color: #906921; color:#fff;" required name="funcionario" id="funcionario">
                <option value="">Escolha um funcionário</option>
                <?php while ($funcionario = $resultado_funcionarios->fetch_assoc()) { ?>
                    <option value="<?php echo $funcionario['id_funcionario']; ?>"><?php echo $funcionario['nome_funcionario']; ?></option>
                <?php } ?>
            </select>

            <select style="border-radius: 10px; background-color: #906921; color:#fff;" required name="posto" id="posto">
                <option value="">Escolha um posto</option>
                <?php while ($posto = $resultado_postos->fetch_assoc()) { ?>
                    <option value="<?php echo $posto['id_posto']; ?>"><?php echo $posto['nome_posto']; ?></option>
                <?php } ?>
            </select>

          <label for="data_inicio">Data de Início:</label>
<input type="date" id="data_inicio" name="data_inicio" min="<?php echo date('Y-m-d'); ?>"><br>



            <style>
                .weekdays {
                    display: flex;
                    justify-content: space-between;
                }

                .weekdays span {
                    align-items: center;
                }

                .weekdays span label {
                    font-size: .7rem;
                    font-weight: normal;
                }

                .weekdays span label input[type=checkbox]:hover {
                    cursor: pointer;
                }
            </style>

            <label for="data_fim">Data de Fim:</label>
            <input type="date" id="data_fim" name="data_fim" min="<?php echo date('Y-m-d'); ?>"><br>

            <label for="dias_semana">Dias da Semana:</label>
            <div class="weekdays">
                <span>
                    <label for="">SEG <input type="checkbox" value="1" name="dia_semana[]"></label>
                </span>
                <span>
                    <label for="">TER <input type="checkbox" value="2" name="dia_semana[]"></label>
                </span>
                <span>
                    <label for="">QUA <input type="checkbox" value="3" name="dia_semana[]"></label>
                </span>
                <span>
                    <label for="">QUI <input type="checkbox" value="4" name="dia_semana[]"></label>
                </span>
                <span>
                    <label for="">SEX <input type="checkbox" value="5" name="dia_semana[]"></label>
                </span>
                <span>
                    <label for="">SÁB <input type="checkbox" value="6" name="dia_semana[]"></label>
                </span>
                <span>
                    <label for="">DOM <input type="checkbox" value="0" name="dia_semana[]"></label>
                </span>
            </div>

            <label for="horario_inicio">Horário de Início:</label>
            <input type="time" id="horario_inicio" name="horario_inicio"><br>

            <label for="horario_fim">Horário de Fim:</label>
            <input type="time" id="horario_fim" name="horario_fim"><br>


            <label for="observacoes">Observações:</label><br>
            <textarea id="observacoes" name="observacoes" rows="4" cols="50"></textarea><br>

            <button style="background-color: #906921; color:#fff" type="submit" name="submit">Adicionar Escala</button>
        </form>
    </div>
    <div class="loader"></div>
</body>

</html>