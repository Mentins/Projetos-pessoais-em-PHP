<?php

    $texto = $_POST['texto'];
    $dados = json_decode($texto, true);

    $dados['aluno'] = 'Thiago Martins';

    $json = json_encode($dados);
    echo $json;