<?php
    $dbHost = 'Localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'sistema';

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
    mysqli_set_charset($conexao, "utf8");


    //if($conexao->connect_errno)
    //{
    //    echo "Erro"; 
    //}
    //else
    //{
     //   echo "Conexão efectuada com succeso";
    //}
?> 