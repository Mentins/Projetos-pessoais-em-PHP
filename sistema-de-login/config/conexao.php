<?php
    session_start();

    //MODO LOCAL
    $modo = 'producao';
    if($modo == 'local'){
        $servidor = "localhost";
        $usuario = "root";
        $senha = "";
        $banco = "login";

    } 
    //MODO PRODUÇÃO
    if ($modo == 'producao'){
        $servidor ='sql213.epizy.com';
        $usuario = 'epiz_32354589';
        $senha ='KVhJDttgcVKyi';
        $banco = 'epiz_32354589_login';
    }

    try {
        $pdo = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Banco conectado";

    } catch(PDOException $erro) {
        echo "Falha ao se conectar com o banco!!";//. $erro->getMessage();
    }

    //FUNÇÃO PARA LIMPAR O POST
    function limpaPost($valor){
        $valor = trim($valor); //Remove espaços vazios e outros caracteres do inicio e do fim da string.
        $valor = stripslashes($valor); //Remove barras invertidas.
        $valor = htmlspecialchars($valor);  //Converte todos os caracteres predefinidos em entidades HTML.
        return $valor;
    }

    //FUNÇÃO PARA VERIFICAR SE DETERMINADA PESSOA POSSUI ACESSO A PÁGINA QUE ESTÁ QUERENDO ACESSAR
    function autorizada($tokenSessao){
        global $pdo;
        $sql = $pdo->prepare("SELECT * FROM usuarios WHERE token=? LIMIT 1");
        $sql->execute(array($tokenSessao));
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);
        //SE NÃO ENCONTRAR O USUÁRIO
        if($usuario){
            return $usuario;

        } else {
            return false;
        }
    }
?>