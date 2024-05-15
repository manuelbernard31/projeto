<?php
include('config.php');

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login_cliente.php");
    exit();
}

$email = $_SESSION['email'];
$sql_usuario = "SELECT id_usuario FROM usuarios WHERE email = '$email'";
$result_usuario = $conexao->query($sql_usuario);
if (!$result_usuario) {
    die("Erro na consulta SQL do usuário: " . mysqli_error($conexao));
}

$row_usuario = $result_usuario->fetch_assoc();
$id_usuario = $row_usuario['id_usuario'];

$sql_faltas = "SELECT escala_id, data, dia_semana, motivo_falta 
               FROM dias_escala 
               WHERE escala_id IN (SELECT id FROM escalas_trabalho WHERE id_usuario = $id_usuario)";
$result_faltas = $conexao->query($sql_faltas);
if (!$result_faltas) {
    die("Erro na consulta SQL das faltas: " . mysqli_error($conexao));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faltas Registradas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Faltas Registradas</h1>
    <table>
        <thead>
            <tr>
                <th>Escala ID</th>
                <th>Data</th>
                <th>Dia da Semana</th>
                <th>Motivo da Falta</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result_faltas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['escala_id'] . "</td>";
                echo "<td>" . $row['data'] . "</td>";
                echo "<td>" . $row['dia_semana'] . "</td>"; // Você pode modificar essa coluna conforme necessário
                echo "<td>" . $row['motivo_falta'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
