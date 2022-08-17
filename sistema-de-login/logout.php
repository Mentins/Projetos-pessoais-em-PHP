<?php
    //PÁGINA RESPONSÁVEL PELO LOGOUT DO SITE
    /* Esta página está inicializando uma sessão; está desfazendo esta mesma sessão; depois de desfazer, destrói a mesma e então redireciona a página do principal (vulgo, login) */
    session_start();
    session_unset();
    session_destroy();
    header('location: index.php');