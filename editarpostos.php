<?php

include_once('config.php');

if (!empty($_GET['id_posto'])) {
    $id_posto = $_GET['id_posto'];

    $sqlSelecionarposto = "SELECT * FROM postos WHERE id_posto = $id_posto"; 

    $resultadopostos = $conexao->query($sqlSelecionarposto);

    if ($resultadopostos->num_rows > 0) {
        while ($usuario_postos = mysqli_fetch_assoc($resultadopostos)) 
        {
            $nome_contratante = $usuario_postos['nome_contratante'];
            $nome_posto = $usuario_postos['nome_posto'];
            $num_contratante = $usuario_postos['num_contratante'];
            $inicio_contrato = $usuario_postos['inicio_contrato'];
            $termino_contrato = $usuario_postos['termino_contrato'];
            $localizacao = $usuario_postos['localizacao'];
            $email = $usuario_postos['email'];
            $observacoes = $usuario_postos['observacoes'];
        }
    } 
    else 
    {
        header('Location: postos_tabela.php');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <title>Postos tabela</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .formulario {
            margin-top: 20px;
        }

        .campo {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="tel"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 80px; 
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-voltar {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #ccc;
            color: #333;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        </style>
</head>

<body>
    <a class="btn-voltar" href="postos_tabela.php">Voltar</a>
    <div class="container">
        <h1>Adicionar Postos</h1>
        <form action="salvarpostos.php" method="POST">
            <div class="formulario">
                <h2>Dados do Posto</h2>
                <div class="campo">
                    <label for="nome_contratante">Nome do Contratante:</label>
                    <input type="text" id="nome_contratante" name="nome_contratante" value="<?php echo $nome_contratante; ?>" required>
                </div>
                <div class="campo">
                    <label for="nome_posto">Nome do Posto</label>
                    <input type="text" id="nome_posto" name="nome_posto" value="<?php echo $nome_posto; ?>" required>
                </div>
                <div class="campo">
                <div class="campo">
                    <label for="num_contratante">Número do Contratante:</label>
                    <input type="text" id="num_contratante" name="num_contratante" value="<?php echo $num_contratante; ?>" required>
                </div>
                    <label for="inicio_contrato">Inicio do Contrato:</label>
                    <input type="date" id="inicio_contrato" name="inicio_contrato" value="<?php echo $inicio_contrato; ?>" required>
                </div>
                <div class="campo">
                    <label for="termino_contrato">Termino do Contrato:</label>
                    <input type="date" id="termino_contrato" name="termino_contrato" value="<?php echo $termino_contrato; ?>" required>
                </div>
                <div class="campo">
                    <label for="localizacao">Localização:</label>
                    <input type="text" id="localizacao" name="localizacao" value="<?php echo $localizacao; ?>" required>
                </div>
                <div class="campo">
                    <label for="observacoes">Observações:</label>
                    <textarea id="observacoes" name="observacoes"><?php echo $observacoes; ?></textarea>
                </div>
                <div class="campo">
                <input type="hidden" id="id_posto" name="id_posto" value="<?php echo $id_posto; ?>">
                    <input style="background-color: #906921;border-radius:15px" type="submit" name="ATUALIZAR" value="ATUALIZAR">
                </div>
            </div>

        </form>
    </div>
    <div class="loader"></div>
</body>

</html>