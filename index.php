<?php

use Sistema\Modelo\CategoriaModelo;

    require 'vendor/autoload.php';
    require_once "Sistema/configuracao.php";
    use Core\Sessao;
    use Sistema\Modelo\UsuarioModelo;

    //use Sistema\Modelo\UsuarioModelo;

    //use Sistema\Modelo\PostModelo;
    require "rotas.php";

    

    //$user = new UsuarioModelo();

    //var_dump($user->lerCondicional('level', '3'));

    /*$model = new UsuarioModelo();
    $users = $model->ler();

    foreach($users as $user){
        var_dump($user->senha);
        var_dump($user->id_usuario);
        echo "<hr>";
    }

    foreach($users as $user){
        $model->alterarSenha($user->id_usuario, "root");
    }*/

    /*foreach($users as $user){
        $model->atualizarSenha($user->id_usuario);
    }*/

    function ocultar(){
            echo "";
            //use Exception;
        
            //echo "<hr> <h1>Testando</h1> <hr> <br>";
            
            /*$cpf1 = "19431931770";
            $cpf2 = "19431931771";

            try{
                echo Helper::formatarCpf($cpf1)."<hr>";
                echo Helper::formatarCpf($cpf2);
            } catch(Exception $e){
                echo $e->getMessage();
            } finally{
                echo "<hr>";
            }
            require "Rotas.php";*/

            /*use Core\Helper;
            use Core\Conexao;
            use Sistema\Modelo\PostModelo;*/

            /*$con = Conexao::getInstance();
            
            echo "<h1>Teste<h1><hr>";

            $post = new PostModelo();

            var_dump($post->ler());

            $posts = $post->ler();

            echo "<hr>";*/

            /*$post->ativar("Título 1");
            $post->ativar("Título 2");
            $post->ativar("Título 3");
            $post->ativar("Título 4");*/

            /*$post->desativar("Título 1");
            $post->desativar("Título 2");
            $post->desativar("Título 3");
            $post->desativar("Título 4");*/

            /*foreach($posts as $item){
                echo $item->titulo." / ".$item->texto." / ".$item->status."<br>";
            }

            echo "<hr>";

            $posts2 = $post->lerValidos();

            foreach($posts2 as $item){
                echo $item->titulo." / ".$item->texto." / ".$item->status."<br>";
            }

            echo "<hr>";

            $posts3 = $post->lerPostEspecifico("Título 3");

            foreach($posts3 as $item){
                echo $item->titulo." / ".$item->texto." / ".$item->status."<br>";
            }

            echo "<hr>";

            $posts4 = $post->lerPostEspecifico(4);

            foreach($posts4 as $item){
                echo $item->titulo." / ".$item->texto." / ".$item->status."<br>";
            }*/




            /*$posts = new PostModelo();

            $post = $posts->lerPostEspecifico(2);

            echo "Id: {$post->id_post} <br>
                Título: {$post->titulo} <br>
                Texto: {$post->texto} <br>
                Status: {$post->status} <hr>
                ";*/

            /*echo "<hr>";

            $cat = new CategoriaModelo();

            var_dump($cat);

            echo "<hr>";

            var_dump($cat->ler());

            echo "<hr>";

            $categoria = $cat->lerEspecifico("MySql");

            echo "Id: {$categoria->id_categoria} <br>
                Título: {$categoria->titulo} <br>
                Texto: {$categoria->texto} <br>
                Status: {$categoria->status} <hr>";*/
    }
    function ocultarSessao(){
        session_start();
        echo session_id();
    
        echo "<hr>";
    
        $_SESSION['nome'] = "Raphael Stefano";
        if(isset($_SESSION['visitas'])){
            $_SESSION['visitas'] += 1;
        } else{
            $_SESSION['visitas'] = 1;
        }
        //unset($_SESSION['visitas']);
        //session_destroy();
        echo "{$_SESSION['nome']} visitou {$_SESSION['visitas']} vezes.";
    }
    function ocultarObjetoSessao(){
        $sessao = new Sessao();
        $sessao->criar('usuario', ['id' => 10, 'nome' => 'Stefano']);
        $sessao->criar('usuario', ['id' => 10, 'nome' => 'Stefano']);
        var_dump($sessao->carregar());
        echo "<hr>";
        var_dump($sessao->checar('usuario'));
        echo "<hr>";
        var_dump($sessao->checar('teste'));
        $sessao->criar('teste', 'test is true');
        echo "<hr>";
        var_dump($sessao->checar('teste'));
        $sessao->limpar('usuario');
        echo "<hr>";
        var_dump($sessao->carregar());
        $sessao->deletar();
        echo "<hr>";
    }


?>