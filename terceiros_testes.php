<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="Sistema/index.css" rel="stylesheet">
<?php

    require 'vendor/autoload.php';
    require_once "Sistema/configuracao.php";

    use Core\Helper;
    use Core\Mensagem;
    use Core\Controlador;

    $obj = new Mensagem;
    /*echo $obj->getTexto();
    $obj->setTexto('Teste 2');
    echo $obj->getTexto();*/

    echo $obj->sucesso("Testando mensagem diferente");
    echo $obj->erro();
    echo $obj->alerta();
    echo $obj->info();
    echo new Mensagem();

    $obj2 = new Mensagem("Objeto 2");
    echo $obj2;

    echo new Mensagem(Helper::dataAtualFormatada());

    echo LINK_LOCAL;

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