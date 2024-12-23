<?php
    
    require_once 'Sistema/configuracao.php';
    include_once 'Core/helpers.php';

    function testes1(){
        $texto = 'Texto de teste para a funçao mb_strlen';
        $valor = 22024;
        $valor2 = 1800;

        echo strpos(mb_strtolower($texto), 't');
        echo '<hr>';
        echo date('d/m/Y H:i');
        echo '<hr>';
        echo textoResumido($texto, 15);
        echo '<hr>';
        echo formatarValor($valor, 0);
        echo '<hr>';
        echo formatarValor($valor2);
        echo '<hr>';
        echo contarTempo('2024-11-08 01:35:00');
    }
    //testes1();
    
    function testeEmail(){
        $email = 'raphaelestefano@gmail.com';
        $email2 = 'ful.ano@gmail';
        $email3 = 'fulano@gmail.com';
        $email4 = 'ful.ano@gmail.com';
        $email5 = 'ful.ano';
        $email6 = 'fulano';
        $email7 = 'fulano@gmail';

        echo validarEmailNativo($email) ?: 0;
        echo '<hr>';
        echo validarEmailNativo($email2) ?: 0;
        echo '<hr>';
        echo validarEmailNativo($email3) ?: 0;
        echo '<hr>';
        echo validarEmailNativo($email4) ?: 0;
        echo '<hr>';
        echo validarEmailNativo($email5) ?: 0;
        echo '<hr>';
        echo validarEmailNativo($email6) ?: 0;
        echo '<hr>';
        echo validarEmailNativo($email7) ?: 0;
        echo '<hr>';
    }
    //testeEmail();
    
    define('SITE_NOME', 'meusite.com'); //criacao de uma constante
    const SITE_URL = 'https://meusite.com'; // outra forma de criar uma constante
    //echo SITE_NOME;
    //echo SITE_URL;

    var_dump($_SERVER);

?>

<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Página de testes</title>

        <style>
            body{
                background-color: black;
                color: white;
            }
        </style>

    </head>
    <body>

    </body>
</html>