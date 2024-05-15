<?php

include_once('config.php');

if (!empty($_GET['id_funcionario'])) {
    $id_funcionario = $_GET['id_funcionario'];

    $sqlSelecionar = "SELECT * FROM funcionarios WHERE id_funcionario = $id_funcionario"; 

    $resultado = $conexao->query($sqlSelecionar);

    if ($resultado->num_rows > 0) {
        while ($usuario = mysqli_fetch_assoc($resultado)) {
            $nome = $usuario['nome_funcionario'];
            $cargo = $usuario['cargo'];
            $data_nascimento = $usuario['data_nascimento'];
            $num_telemovel = $usuario['num_telemovel'];
            $morada = $usuario['morada'];
            $contacto_emergencia = $usuario['contacto_emergencia'];
            $observacoes = $usuario['observacoes'];
            $genero = $usuario['genero'];
        }
       
    }
    else
        {
            header('Location: tabela_funcionario.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <title>Cadastro de Funcionário</title>
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
    <a class="btn-voltar" href="tabela_funcionario.php">Voltar</a>
    <div class="container">
        <h1>Cadastro de Funcionário</h1>
        <form action="salvofuncionarios.php" method="POST">
            <div class="formulario">
                <h2>Dados Pessoais</h2>
                <div class="campo">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome_funcionario" name="nome_funcionario" value="<?php echo $nome; ?>"  required>
                </div>
                <div class="campo">
                    <label for="cargo">Cargo:</label>
                    <input type="text" id="cargo" name="cargo"  value="<?php echo $cargo; ?>" required>
                </div>
                <div class="campo">
                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo $data_nascimento; ?>" required>
                </div>
                <div class="campo">
                    <label for="num_telemovel">Número de Telemóvel:</label>
                    <input type="tel" id="num_telemovel" name="num_telemovel" value="<?php echo $num_telemovel; ?>" required>
                </div>
                <div class="campo">
                    <label for="morada">Morada:</label>
                    <input type="text" id="morada" name="morada" value="<?php echo $morada; ?>" required>
                </div>
                <div class="campo">
                    <label for="contacto_emergencia">Contacto de Emergência:</label>
                    <input type="tel" id="contacto_emergencia" name="contacto_emergencia" value="<?php echo $contacto_emergencia ?>" required>
                </div>
                <div class="campo">
                    <label for="observacoes">Observações:</label>
                    <textarea id="observacoes" name="observacoes"><?php echo $observacoes; ?></textarea>
                </div>
                <div class="campo">
                    <label for="genero">genero:</label>
                    <select id="genero" name="genero" required <?php echo $genero; ?>>
                        <option value="Masculino">Masculino</option>
                        <option value="Feminino">Feminino</option>
                    </select>
                </div>
                <div class="campo">
                <input type="hidden" id="id_funcionario" name="id_funcionario" value="<?php echo $id_funcionario; ?>">
                    <input style="background-color: #906921;border-radius:15px" type="submit" name="ATUALIZAR" value="ATUALIZAR">
                </div>
            </div>

        </form>
    </div>
    <div class="loader"></div>
</body>

</html>