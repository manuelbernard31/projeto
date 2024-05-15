<?php
include('config.php');

if(isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $perfil = isset($_POST['perfil_admin']) ? 'admin' : (isset($_POST['perfil_secretaria']) ? 'secretaria' : '');

    if(empty($nome) || empty($email) || empty($senha) || empty($perfil)) {
        // Trate a ausência de dados como desejar
        echo "Por favor, preencha todos os campos!";
    } else {
        // Verifique o comprimento da senha
        if(strlen($senha) < 10) {
            
        } else {
            // Use password_hash() para criar um hash seguro da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Insere o novo usuário no banco de dados com a senha protegida
            $sql = "INSERT INTO usuarios (nome, email, senha, perfil) VALUES ('$nome', '$email', '$senha_hash', '$perfil')";
            if ($conexao->query($sql) === TRUE) {
                header("Location: usuarios.php");
                exit();
            } else {
                // Trate erros de inserção no banco de dados
                echo "Erro ao cadastrar usuário: " . $conexao->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuários</title>
    <link rel="stylesheet" href="adicionar.css">
    <script src="script.js" defer></script>
</head>

<body>
    <a href="usuarios.php" class="btn-voltar">Voltar para a Tabela</a>
    <div class="container">
        <form action="add_usuario.php" method="POST">
            <div class="formulario">
                <div class="campo">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" id="nome" required>
                </div>
                <div class="campo">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="campo">
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" id="senha" required>
                    <!-- Notificação de comprimento da senha -->
                    <div id="senha-notification" style="color: red; display: none;">A senha deve ter pelo menos 10 caracteres!</div>
                </div>
                <div class="campo">
                    <label>Perfil:</label>
                    <label for="perfil_admin">Admin</label>
                    <input type="checkbox" name="perfil_admin" id="perfil_admin" value="admin">
                   
                    <label for="perfil_cliente">Secretaria</label>
                    <input type="checkbox" name="perfil_secretaria" id="perfil_secretaria" value="secretaria">
                    
                </div>
                <button type="submit" name="submit">Cadastrar</button>
            </div>
        </form>
    </div>

    <script>
        const senhaInput = document.getElementById('senha');
        const senhaNotification = document.getElementById('senha-notification');

        senhaInput.addEventListener('input', function() {
            if (senhaInput.value.length < 10) {
                senhaNotification.style.display = 'block'; // Mostra a notificação
            } else {
                senhaNotification.style.display = 'none'; // Oculta a notificação
            }
        });
    </script>
</body>

</html>
