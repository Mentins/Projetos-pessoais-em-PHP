<?php
    require('config/conexao.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'config/PHPMailer/src/Exception.php';
    require 'config/PHPMailer/src/PHPMailer.php';
    require 'config/PHPMailer/src/SMTP.php';

    if(isset($_GET['cod']) && !empty($_GET['cod'])){
        //LIMPAR O GET
        $cod = limpaPost($_GET['cod']);

        //VERIFICAR SE EXISTEM OS POSTS DE ACORDO COM OS CAMPOS
        if(isset($_POST["senha"]) && isset($_POST["repete_senha"])){

            //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS
            if(empty($_POST['senha']) || empty($_POST['repete_senha'])){
                $erroGeral = "Todos os campos são obrigatórios!";
            } 
            else {
                //RECEBER E LIMPAR VALORES DO POST
                $senha = limpaPost($_POST['senha']);
                $senhaCript = sha1($senha);
                $repete_senha = limpaPost($_POST['repete_senha']);

                //VERIFICAR SE A SENHA É ACEITÁVEL
                if(strlen($senha) < 6){
                    $erroSenha = "A senha precisa ter no mínimo 6 caracteres!";
                }

                //VERIFICAR SE REPETE_SENHA É IGUAL A SENHA
                if($repete_senha != $senha){
                    $erroRepeteSenha = "As senhas não coincidem!";
                }

                //CASO NÃO SE OBTENHA NENHUM ERRO DOS CAMPOS ACIMA
                if(!isset($erroGeral)&& !isset($erroSenha) && !isset($erroRepeteSenha)){
                    //VERIFICAR SE A RECUPERAÇÃO DE SENHA EXISTE
                    $sql = $pdo->prepare("SELECT * FROM usuarios WHERE recupera_senha=? LIMIT 1");
                    $sql->execute(array($cod)); 
                    $usuario = $sql->fetch(); //RETURNA TRUE ou FALSE

                    //SE NÃO EXISTIR O USUÁRIO
                    if(!$usuario){
                        echo "Recuperação de senha inválida!";
                    }else{
                        //JÁ EXISTE USUÁRIO COM ESTE CÓDIGO DE RECUPERAÇÃO
                        //ATUALIZAR O TOKEN DO USUÁRIO NO BANCO
                        $sql = $pdo->prepare("UPDATE usuarios SET senha=? WHERE recupera_senha=?");
                        if($sql->execute(array($senha_cript, $cod))){
                            //REDIRECIONAR PARA O LOGIN
                            header('location: index.php'); 
                        }
                    }
                }
            }
        }
    } 
    else {
        header('locarion: index.php');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <title>Login</title>
</head>
<body>
    <form method="post">
        <h1>Redefinir a senha</h1>

        <?php if(isset($erroGeral)){
            echo 
            "<div class='erro-geral animate__animated animate__rubberBand'>
                $erroGeral
            </div>";
        } ?>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if(isset($erroGeral) or isset($erroSenha)){echo 'class="erro-input"';}?> type="password" name="senha" id="senha" placeholder="Insira a nova senha" required>
            <?php if(isset($erroSenha)){ ?>
                <div class="erro"><?php echo $erroSenha; ?></div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/unlock.png">
            <input <?php if(isset($erroGeral) or isset($erroRepeteSenha)){echo 'class="erro-input"';}?> type="password" name="repete_senha" id="repete_senha" placeholder="Repita a nova senha" required>
            <?php if(isset($erroRepeteSenha)){ ?>
                <div class="erro"><?php echo $erroRepeteSenha; ?></div>
            <?php } ?>
        </div>

        <button class="btn-blue" type="submit">Redefinir Senha</button>
    </form>
</body>
</html>