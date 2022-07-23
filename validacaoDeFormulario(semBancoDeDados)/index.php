<?php
$erroNome = "";
$erroEmail = "";
$erroSenha = "";
$erroRepeteSenha = "";
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    //Verificar se o POST nome está vazio
    if(empty($_POST['nome'])){ 
      $erroNome = "<strong>[ERRO...]</strong> Preencha com um nome...";
    } else {
      //Limar a váriavel nome através da função limpaPost
      $nome = limpaPost($_POST['nome']);

      /*A condição abaixo verificará se a variável nome possui apenas caracteres de a-z ou A-Z, apóstrofe e espaço em branco. Caso a variável caracteres que não sejam esses, disparará um erro com a mensagem mostrada abaixo*/
      if(!preg_match("/^[a-zA-Z' ]*$/", $nome)){
        $erroNome = "<strong>[ERRO...]</strong> Preencha apenas com letras e espaços em branco!";
      }
    }

    //Verificar se o POST email está vazio
    if(empty($_POST['email'])){
      $erroEmail = "<strong>[ERRO...]</strong> Informe um e-mail...";
    } else {
      $email = limpaPost($_POST['email']);
      //A condição abaixo verificará se o e-mail é válido. Caso não seja, disparará a mensagem 
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erroEmail = "<strong>[ERRO...]</strong> Insira um e-mail válido";
      }
    }

    //Verificar se o POST senha está vazio
    if(empty($_POST['senha'])){
      $erroSenha = "<strong>[ERRO...]</strong> Preencha com uma senha";
    } else {
      $senha = limpaPost($_POST['senha']);
      if(strlen($senha) < 6){
        $erroSenha = "<strong>[ERRO...]</strong> A senha precisa ter no mínimo 6 digítos";
      }
    }

    //Verificar se o POST repete_senha está vazio
    if(empty($_POST['repete_senha'])){
      $erroRepeteSenha = "<strong>[ERRO...]</strong> As senhas não coincidem!";
    } else {
      $repeteSenha = limpaPost($_POST['repete_senha']);
      if($repeteSenha !== $senha){
        $erroRepeteSenha = "<strong>[ERRO...]</strong> As senhas não coincidem!";
      }
    }

    //Caso esteja tudo certo, enviar o usuário para a página de obrigado
    if(($erroNome === "") && ($erroEmail === "") && ($erroSenha === "") && ($erroRepeteSenha === "")){
      header('Location: obrigado.php');
    }
  }

  function limpaPost($valor){
    $valor = trim($valor);
    $valor = stripslashes($valor);
    $valor = htmlspecialchars($valor);
    return $valor;
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de Formulário</title>
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>
    <main>
    <h1><span>AULA PHP</span><br>Validação de Formulário</h1>

     <form method="post">

        <!-- NOME COMPLETO -->
        <label> Nome Completo </label>
        <input type="text" <?php if(!empty($erroNome)){echo "class='invalido'";}?> <?php if(isset($_POST['nome'])){ echo "value='".$_POST['nome']."'"; } ?> name="nome" placeholder="Digite seu nome">
        <br><span class="erro"><?php echo $erroNome ?></span>

        <!-- EMAIL -->
        <label> E-mail </label>
        <input type="email" <?php if(!empty($erroEmail)){echo "class='invalido'";}?> <?php if(isset($_POST['email'])){ echo "value='".$_POST['email']."'"; } ?> name="email" placeholder="email@provedor.com">
        <br><span class="erro"><?php echo $erroEmail ?></span>

        <!-- SENHA -->
        <label> Senha </label>
        <input type="password" <?php if(!empty($erroSenha)){echo "class='invalido'";}?> <?php if(isset($_POST['senha'])){ echo "value='".$_POST['senha']."'"; } ?> name="senha" placeholder="Digite uma senha">
        <br><span class="erro"><?php echo $erroSenha ?></span>

        <!-- REPETE SENHA -->
        <label> Repete Senha </label>
        <input type="password" <?php if(!empty($erroRepeteSenha)){echo "class='invalido'";}?> <?php if(isset($_POST['repete_sneha'])){ echo "value='".$_POST['repete_senha']."'"; } ?> name="repete_senha" placeholder="Repita a senha">
        <br><span class="erro"><?php echo $erroRepeteSenha ?></span>

        <button type="submit"> Enviar Formulário </button>

      </form>
    </main>
</body>
</html>