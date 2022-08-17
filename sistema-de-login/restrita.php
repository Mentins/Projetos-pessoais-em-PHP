<?php
    require('config/conexao.php');

    //VERIFICAÇÃO DE AUTENTICAÇÃO
    $user = autorizada($_SESSION['TOKEN']);
    if($user){
        echo "<h1> SEJA BEM-VINDO <b style='color:red'>". $user['nome'] . "</b></h1><br>";
        echo "<a style='background-color:green; text-decoration:none; color:white; padding:20px; border-radius: 5px;' href='logout.php'>Fazer Logout</a>";
    } else {
        //REDIRECIONA PARA LOGIN
        header('location: index.php');
    }

    /*
    //VERIFICAR SE TEM AUTORIZAÇÃO
    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
    $sql->execute(array($_SESSION['TOKEN']));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
    //SE NÃO ENCONTRAR O USUÁRIO
    if($usuario){
        echo "<h1> SEJA BEM-VINDO <b style='color:red'>". $usuario['nome']. "</b></h1><br>";
        echo "<a style='background-color:green; text-decoration:none; color:white; padding:20px; border-radius: 5px;' href='logout.php'>Fazer Logout</a>";

    } else {
        header('location: index.php');
    }
    */
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logado</title>
</head>
<body>
    
</body>
</html>