<?php
    require('config/conexao.php');

    if(isset($_GET['cod_confirm']) && !empty($_GET['cod_confirm'])){
        $codigo = limpaPost($_GET['cod_confirm']); //LIMPANDO O GET
        //CONSULTAR SE ALGUM USUÁRIO POSSUI ESTE CÓDIGO DE CONFIRMAÇÃO
        $sql = $pdo->prepare("SELECT * FROM usuarios WHERE codigo_confirmacao=? AND senha=? LIMIT 1");
        $sql->execute(array($codigo));
        $usuario = $sql->fetch(PDO:: FETCH_ASSOC);
        if($usuario){
            //MUDAR O STATUS PARA CONFIRMADO
            $status = "confirmado";
            $sql = $pdo->prepare("UPDATE usuarios SET status=? WHERE codigo_confirmacao=?");
            if($sql->execute(array($status,$codigo))){
                header('location: index.php?result=ok');
            } else {
                echo "<h1>Código de confirmação inválido!!</h1>";
            }
        }
    }
?>