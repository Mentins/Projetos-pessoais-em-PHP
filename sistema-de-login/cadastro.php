<?php
    require('config/conexao.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'config/PHPMailer/src/Exception.php';
    require 'config/PHPMailer/src/PHPMailer.php';
    require 'config/PHPMailer/src/SMTP.php';

    //VERIFICAR SE EXISTEM OS POSTS DE ACORDO COM OS CAMPOS
    if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["senha"]) && isset($_POST["repete_senha"])){

        //VERIFICAR SE TODOS OS CAMPOS FORAM PREENCHIDOS
        if(empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha']) || empty($_POST['repete_senha']) || empty($_POST['termos'])){
            $erroGeral = "Todos os campos são obrigatórios!";
        } else {
            //RECEBER E LIMPAR VALORES DO POST
            $nome = limpaPost($_POST['nome']);
            $email = limpaPost($_POST['email']);
            $senha = limpaPost($_POST['senha']);
            $senhaCript = sha1($senha);
            $repete_senha = limpaPost($_POST['repete_senha']);
            $checkbox = limpaPost($_POST['termos']);

            //VERIFICAR SE O NOME É APENAS LETRAS E ESPAÇOS
            if(!preg_match("/^[a-zA-Z-' ]*$/", $nome)){
                $erroNome = "Preencha apenas com letras e espaços em branco!";
            }

            //VERIFICAR SE EMAIL É VÁLIDO
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erroEmail = "Preencha com um email válido!";
            }

            //VERIFICAR SE A SENHA É ACEITÁVEL
            if(strlen($senha) < 6){
                $erroSenha = "A senha precisa ter no mínimo 6 caracteres!";
            }

            //VERIFICAR SE REPETE_SENHA É IGUAL A SENHA
            if($repete_senha != $senha){
                $erroRepeteSenha = "As senhas não coincidem!";
            }

            //VERIFICAR SE FOI ACEITO OS TERMOS
            if($checkbox!="ok"){
                $erroCheckbox = "É preciso aceitar os termos!";
            }

            //CASO NÃO SE OBTENHA NENHUM ERRO DOS CAMPOS ACIMA
            if(!isset($erroGeral) && !isset($erroNome) && !isset($erroEmail) && !isset($erroSenha) && !isset($erroRepeteSenha) && !isset($erroCheckbox)){
                //VERIFICAR SE EMAIL JÁ ESTÁ CADASTRADO
                $sql = $pdo->prepare("SELECT * FROM usuarios WHERE email=? LIMIT 1");
                $sql->execute(array($email)); 
                $usuario = $sql->fetch(); //RETURNA TRUE ou FALSE
                //SE NÃO EXISTIR O USUÁRIO - ADICIONAR NO BANCO
                if(!$usuario){
                    //SE USUÁRIO NÃO ESTIVER CADASTRADO
                    $recuperaSenha = "";
                    $token = "";
                    $codigoConfirmacao = uniqid();
                    $status = "novo";
                    $dataCadastro = date('d/m/Y-H:i:s');
                    $sql = $pdo->prepare("INSERT INTO usuarios VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    if($sql->execute(array($nome, $email, $senhaCript, $recuperaSenha, $token, $codigoConfirmacao, $status, $dataCadastro))){
                        //CASO O MODO SEJA LOCAL, O USUÁRIO SERÁ REDIRECIONADO
                        if($modo=="local"){
                            header('location: index.php?result=ok');
                        }

                        //CASO O MODO SEJA PRODUÇÃO
                        if($modo == "producao"){
                            //ENVIAR EMAIL PARA O USUÁRIO
                            $mail = new PHPMailer(true);
                            try {
                                //RECEPIENTES
                                $mail->setFrom('sistema@emailsistema.com', 'Sistema de Login'); //QUEM ESTÁ ENVIANDO EMAIL
                                $mail->addAddress($email, $nome); //QUEM ESTÁ RECEBENDO

                                //CONTENTS
                                $mail->isHTML(true); //CORPO DO EMAIL COMO HTML
                                $mail->Subject = 'Confirmar cadastro'; //TÍTULO DO E-MAIL
                                $mail->Body    = '<h1>Por favor, confirma o seu email abaixo</h1><br><br><a style="background-color:green; text-decoration:none; color:white; padding:20px; border-radius: 5px;" href="http://thiagomartins.epizy.com/sistema-de-login/confirmacao.php?cod_confirm='.$codigoConfirmacao.'">Cofirmar E-mail</a>'; //CORPO DO EMAIL

                                $mail->send();
                                if($mail->send()){
                                    header('location: obrigado.php');
                                }

                            } catch (Exception $e){
                                echo "Houve um problema ao enviar e-mail de confirmação  {$mail->ErrorInfo}";
                            }
                        }
                    }

                }else{
                    //SE USUÁRIO JÁ ESTIVER CADASTRADO, EXIBA O ERRO
                    $erroGeral = "Usuário já cadastrado!";
                }
            }
        }
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
        <h1>Cadastrar</h1>

        <?php if(isset($erroGeral)){
            echo 
            "<div class='erro-geral animate__animated animate__rubberBand'>
                $erroGeral
            </div>";
        } ?>

        <div class="input-group">
            <img class="input-icon" src="img/card.png">
            <input <?php if(isset($erroGeral) or isset($erroNome)){echo 'class="erro-input"';}?> type="text" name="nome" id="nome" placeholder="Nome Completo" <?php if(isset($_POST['nome'])){ echo "value='".$_POST['nome']."'";} ?> required>
            <?php if(isset($erroNome)){ ?>
                <div class="erro"><?php echo $erroNome; ?></div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input <?php if(isset($erroGeral) or isset($erroEmail)){echo 'class="erro-input"';}?> type="email" name="email" id="email" placeholder="Email" <?php if(isset($_POST['email'])){ echo "value='".$_POST['email']."'";} ?> required>
            <?php if(isset($erroEmail)){ ?>
                <div class="erro"><?php echo $erroEmail; ?></div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/lock.png">
            <input <?php if(isset($erroGeral) or isset($erroSenha)){echo 'class="erro-input"';}?> type="password" name="senha" id="senha" placeholder="Digite sua senha" required>
            <?php if(isset($erroSenha)){ ?>
                <div class="erro"><?php echo $erroSenha; ?></div>
            <?php } ?>
        </div>

        <div class="input-group">
            <img class="input-icon" src="img/unlock.png">
            <input <?php if(isset($erroGeral) or isset($erroRepeteSenha)){echo 'class="erro-input"';}?> type="password" name="repete_senha" id="repete_senha" placeholder="Repetição da senha" required>
            <?php if(isset($erroRepeteSenha)){ ?>
                <div class="erro"><?php echo $erroRepeteSenha; ?></div>
            <?php } ?>
        </div>

        <div <?php if(isset($erroGeral) or isset($erroCheckbox)){echo 'class="erro-input input-group"';}else{echo 'class="input-group"';}?>>
            <input type="checkbox" name="termos" id="termos" value="ok" required>
            <label for="termos">Ao se cadastrar você concorda com a nossa <a class="link" href="#">Política de Privacidade</a> e os <a class="link" href="#">Termos de uso</a></label>
            <?php if(isset($erroCheckbox)){ ?>
                <div class="erro"><?php echo $erroCheckbox; ?></div>
            <?php } ?>
        </div>

        <button class="btn-blue" type="submit">Fazer Cadastro</button>
        <p>Já tem uma conta?</p>
        <a href="index.php">Faça Login!</a>
    </form>
</body>
</html>