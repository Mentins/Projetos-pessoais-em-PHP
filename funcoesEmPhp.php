<?php

    // FUNÇÃO PARA DELETAR ARQUIVOS E A PASTA
    function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    // FUNÇÃO PARA LIMPAR POSTS
    function limpaPost($valor){
        $valor = trim($valor);
        $valor = stripslashes($valor);
        $valor = htmlspecialchars($valor);
        return $valor;
    }

    // FUNÇÃO PARA ENVIO DE ARQUIVOS MÚLTIPLOS
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

        /* 
        Não esqueça de passar essa função ao seu array de arquivos (exemplo: $arrayDeArquivos = reArrayFiles($_FILES['arquivo']);) e combe esta função com um laço de repetição foreach, para que fique mais fácil caminhar sobre todo o array sem muitas dificuldades, poupando linhas de códigos, facilitando manutenção, ajudando o programador que irá rever seu código e poupando vida útil do teclado.
        
        May the force be with you...
        */
    }
?>