<?php
    require('config/conexao.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require 'config/PHPMailer/src/Exception.php';
    require 'config/PHPMailer/src/PHPMailer.php';
    require 'config/PHPMailer/src/SMTP.php';

    if(isset($_POST['email']) && !empty($_POST['email'])){
        $email = limpaPost($_POST['email']);
        $status = "confirmado";
        $cod = sha1(uniqid());

        //ATUALIZAR O CÓDIGO DE RECURAÇÃO DO USUÁRIO NO BANCO
        $sql = $pdo->prepare("UPDATE usuarios SET recupera_senha=? WHERE email=?");
        if($sql->execute(array($cod,$email))){
            //RECEPIENTES
            try {
                $mail->setFrom('sistema@emailsistema.com', 'Sistema de Login'); //QUEM ESTÁ ENVIANDO EMAIL
                $mail->addAddress($email, $nome); //QUEM ESTÁ RECEBENDO

                //CONTENTS
                $mail->isHTML(true); //CORPO DO EMAIL COMO HTML
                $mail->Subject = 'Recuperar a senha'; //TÍTULO DO E-MAIL
                $mail->Body    = '<h1>Recuperar a senha</h1><br><br><a style="background-color:green; text-decoration:none; color:white; padding:20px; border-radius: 5px;" href="http://thiagomartins.epizy.com/sistema-de-login/recuperar_senha.php?cod='.$cod.'">Recuperar a senha</a>'; //CORPO DO EMAIL

                if($mail->send()){
                    header('location: email-enviado-recupera.php');
                }

            } catch (Exception $e){
                echo "Houve um problema ao enviar e-mail de confirmação  {$mail->ErrorInfo}";
            }
                
        }
    } 
    else {
        $erroUsuario = "Houve um problema ao buscar e-mail. Tente novamente!";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Recuperar Senha</title>
</head>
<body>
    <form method="post">
        <h1>Recuperar Senha</h1>

        <?php 
            if(isset($erroUsuario)){
        ?>
            <div style="text-align:center" class="erro-geral animate__animated animate__rubberBand">
                    <?php echo $erroUsuario ?>
            </div>
            
        <?php } ?>

        <div class="input-group">
            <img class="input-icon" src="img/user.png">
            <input type="email" name="email" id="email" placeholder="Informe o email cadastrado no sistema" required>
        </div>

        <button class="btn-blue" type="submit">Recuperar a Senha</button>
        <div>
            <a href="index.php">Fazer Login</a>
        </div>
    </form>
</body>
</html>