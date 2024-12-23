<?php
    
    require_once 'Sistema/configuracao.php';
    include_once 'Core/helpers.php';
    include_once 'Core/Mensagem.php';

    //var_dump($_SERVER);
    //echo url('PaginaQualquer'); 
    //$meses = array();
    function slaMano(){
        $meses = ['Início da lista' => 'Iniciando lista', 0 => 'Nao há um mes zero' , 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
        $meses['descricao'] = 'Lista de meses de um ano';
        var_dump($meses);
        echo '<hr>';
        echo $meses['Início da lista'];
        echo '<hr>';
        echo $meses[1];
        echo '<hr>';
        echo $meses['descricao'];
        echo '<hr>';
        echo '<hr>';
        echo '<hr>';
        foreach($meses as $mes){
            echo $mes;
            echo '<hr>';
        }
        for($i = 1; $i < 13; $i++){
            echo $meses[$i].'<hr>';
        }
    }
    //slaMano();

    /*echo dataAtualFormatada();
    echo '<hr>';
    echo slug('Qualquer coisa')*/

    function validacoes(){
        $cpf1 = '123.456.789-00';
        $cpf2 = '123-456-789-00';
        $cpf3 = '123.456.789.00';
        $cpf4 = '1a3.456.789-00';
        $cpf5 = '123.a56.789-00';
        $cpf6 = '123.456.78a-00';
        $cpf7 = '123.456.789-0a';
        $cpf8 = '123.456.789a00';
        $cpf9 = '111.111.111-11';
        $cpf10 = '123.456.789-000';

        echo (validarCpfCompleto($cpf1) ?: 0) . "<br>" . (validarCpfCompleto($cpf2) ?: 0) . "<br>" . (validarCpfCompleto($cpf3) ?: 0) . "<br>" . (validarCpfCompleto($cpf4) ?: 0) . "<br>" . (validarCpfCompleto($cpf5) ?: 0) . "<br>" . (validarCpfCompleto($cpf6) ?: 0) . "<br>" . (validarCpfCompleto($cpf7) ?: 0) . "<br>" . (validarCpfCompleto($cpf8) ?: 0) . "<br>" . (validarCpfCompleto($cpf9) ?: 0) . "<br>" . (validarCpfCompleto($cpf10) ?: 0) . "<br>"; 

        echo '<hr>';

        $cpf11 = '12345678900';
        $cpf12 = 'a2345678900';
        $cpf13 = '1234567890a';
        $cpf14 = '123.456.789-00';
        $cpf15 = '123a5678900';
        $cpf16 = '1234567a900';
        $cpf17 = '11111111111';
        $cpf18 = '123456789000';
    
        echo (validarCpfNumeros($cpf11) ?: 0) . "<br>" . (validarCpfNumeros($cpf12) ?: 0) . "<br>" . (validarCpfNumeros($cpf13) ?: 0) . "<br>" . (validarCpfNumeros($cpf14) ?: 0) . "<br>" . (validarCpfNumeros($cpf15) ?: 0) . "<br>" . (validarCpfNumeros($cpf16) ?: 0) . "<br>" . (validarCpfNumeros($cpf17) ?: 0) . "<br>" . (validarCpfNumeros($cpf18) ?: 0) . "<br>";

        echo '<hr>';

        $cpf21 = '12345678900';
        $cpf22 = '27467878365';
        $cpf23 = '96349692375';
        $cpf24 = '23535465473';
        $cpf25 = '96495353407';
        $cpf26 = '68052252063';
        $cpf27 = 'a8052252063';
        $cpf28 = '22222222222';
        $cpf29 = '123456789000';
    
        echo (formatarCpf($cpf21)) . "<br>" . (formatarCpf($cpf22)) . "<br>" . (formatarCpf($cpf23)) . "<br>" . (formatarCpf($cpf24)) . "<br>" . (formatarCpf($cpf25)) . "<br>" . (formatarCpf($cpf26)) . "<br>" . (formatarCpf($cpf27)) . "<br>" . (formatarCpf($cpf28)) . "<br>" . (formatarCpf($cpf29));
    } //validacoes();

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