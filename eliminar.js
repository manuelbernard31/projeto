// Script no seu arquivo HTML/PHP
$(document).ready(function() {
    $('.eliminar-funcionario').click(function(event) {
        event.preventDefault(); // Impede o comportamento padrão do link

        var idFuncionario = $(this).data('id'); // Obtém o ID do funcionário a ser removido

        $.ajax({
            type: 'POST',
            url: 'eliminar.php',
            data: { id_funcionario: idFuncionario },
            success: function(response) {
                // Remova a linha da tabela apenas se a remoção for bem-sucedida no servidor
                if (response.trim() == 'success') {
                    $(this).closest('tr').remove();
                }
            }
        });
    });
});
