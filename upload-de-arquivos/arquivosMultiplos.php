<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    
    <title>Upload de arquivos</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 text-center">Upload de Arquivos</h1>
        <form method="post" enctype="multipart/form-data" class="m-3">
            <div class="input-group">
                <input type="file" class="form-control" name='arquivo[]' id="arquivo" multiple aria-describedby="inputGroupFileAddon04" aria-label="Upload" required>
                <button class="btn btn-primary" type="submit" name="enviar" id="enviar">Enviar</button>
            </div>
        </form>
    </div>

    <?php
    function reArrayFiles(&$file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);
    
        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
    
        return $file_ary;
    }

    if(isset($_POST['enviar'])){
        $arquivoArray = reArrayFiles($_FILES['arquivo']);
        foreach ($arquivoArray as $arquivo) {
            //VALIDAÇÕES
            $tamanhoMaximo = 2097152; // 2MB
            $permitido = ["jpg", "jpeg", "png", "mp4"];
            /* A linha de código abaixo está pegando a chave 'arquivo' do array que está sendo passada através do post e também está pegando o atributo name deste mesmo array. E então está atribuindo a extensão desse arquivo para a variável extensão. */

            // VERIFICAR TAMANHO
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            if($arquivo['size'] >= $tamanhoMaximo){
                echo '<div class="alert alert-danger text-center" role="alert">
                        '.$arquivo['name'].' - [ERRO...] Arquivo maior que 2MB
                      </div>';
            } else {

                // VERIFICAR A EXTENSÃO
                if(in_array($extensao, $permitido)){
                    // echo "Permitido!";
                    
                    $pasta = "imagens/";
                    if(!is_dir($pasta)){
                        mkdir($pasta, 0755);
                    }

                    $tmp = $arquivo['tmp_name'];
                    $novoNome = uniqid().".$extensao";
                    if(move_uploaded_file($tmp,$pasta.$novoNome)){
                        echo '<div class="alert alert-success text-center" role="alert">
                                ' .$arquivo['name'].' - Upload realizado com sucesso!!
                            </div>';
                    } else {
                        echo '<div class="alert alert-danger text-center" role="alert">
                                '.$arquivo['name'].'- [ERRO...] Não foi possível realizar o upload
                            </div>';
                    }
                } else {
                    echo '<div class="alert alert-danger text-center" role="alert">
                            '.$arquivo['name'].' - [ERRO...] Extensão ('.$extensao.') não permitida!!
                          </div>';
                }
            }
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>