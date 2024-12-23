<?php 

    require "vendor/autoload.php";
    use Core\Helper;

    //use League\Route\Http\Exception\NotFoundException;
    use Pecee\SimpleRouter\SimpleRouter;
    use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;

    //$url = "AulasPhp/";

    try{
        SimpleRouter::setDefaultNamespace("Sistema\Controlador");
        
        SimpleRouter::group(['prefix' => 'AulasPhp'], function () {
            SimpleRouter::get('/', 'SiteControlador@home');
            SimpleRouter::get('/sobre', 'SiteControlador@sobre');
            SimpleRouter::get('/post/{id}', 'SiteControlador@post');
            SimpleRouter::get('/404', 'SiteControlador@erro404');
            SimpleRouter::get('/categoria/{id}', 'SiteControlador@categoria');
            SimpleRouter::post('/buscar', 'SiteControlador@buscar');
            SimpleRouter::get('/perfil', 'SiteControlador@perfil');
            SimpleRouter::get('/postsUsuario/{id}', 'SiteControlador@postsUsuario');
            SimpleRouter::match(['get', 'post'], '/editar/{id}', 'SiteControlador@editar');
            SimpleRouter::get('/sair', 'SiteControlador@sair');
        });

        SimpleRouter::group(['prefix' => 'AulasPhp/Admin'], function () {
            SimpleRouter::get('/', 'AdminControlador@dashboard');
            SimpleRouter::get('/sair', 'AdminControlador@sair');
            SimpleRouter::get('/perfil', 'AdminControlador@perfil');
            SimpleRouter::match(['get', 'post'], '/login', 'AdminLogin@loginCriar');
            SimpleRouter::match(['get', 'post'], '/login/entrar', 'AdminLogin@loginEntrar');
            SimpleRouter::get('/posts/listar', 'AdminPosts@listar');
            SimpleRouter::get('/categorias/listar', 'AdminCategorias@listar');
            SimpleRouter::get('/usuarios/listar', 'AdminUsuarios@listar');
            SimpleRouter::match(['get', 'post'], '/posts/cadastrar', 'AdminPosts@cadastrar');
            SimpleRouter::match(['get', 'post'], '/categorias/cadastrar', 'AdminCategorias@cadastrar');
            SimpleRouter::match(['get', 'post'], '/usuarios/cadastrar', 'AdminUsuarios@cadastrar');
            SimpleRouter::match(['get', 'post'], '/posts/editar/{id}', 'AdminPosts@editar');
            SimpleRouter::match(['get', 'post'], '/categorias/editar/{id}', 'AdminCategorias@editar');
            SimpleRouter::match(['get', 'post'], '/usuarios/editar/{id}', 'AdminUsuarios@editar');
            SimpleRouter::get('/posts/deletar/{id}', 'AdminPosts@deletar');
            SimpleRouter::get('/categorias/deletar/{id}', 'AdminCategorias@deletar');
            SimpleRouter::get('/usuarios/deletar/{id}', 'AdminUsuarios@deletar');
        });

        SimpleRouter::start();
    } catch(NotFoundHttpException $ex){
        //Helper::redirecionar("404");
    }

?>