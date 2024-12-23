<?php 

    namespace Sistema\Controlador;

    use Core\Conexao;
    use Core\Controlador;
    use Sistema\Modelo\PostModelo;
    use Core\Helper;
    use Core\Mensagem;
    use PDOException;
    use Sistema\Modelo\CategoriaModelo;
    use Sistema\Modelo\UsuarioModelo;
    use Core\Sessao;

    class AdminLogin extends UsuarioControlador{
        public function __construct(){
            parent::__construct("Templates/Admin/Views");

        }

        public function loginCriar(): void{
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(isset($dados)){
                $dados['senha'] = Helper::gerarSenha($dados['senha']);
                if($this->checarDadosCriar($dados)){

                    unset($dados['senha_confirm']);
                    $usuario = new UsuarioModelo();
                    
                    $usuario->cadastrar($dados);
                    $user = $usuario->lerEspecifico($dados['email']);
                    $this->mensagem->sucesso("Seja bem-vindo, {$user->nome}!")->flash();
                    //$usuario->editar($user->id, ['ultimo_login' => date("Y-m-d H-i-s")])
                    (new Sessao())->criar('id_usuario', $user->id_usuario);
                    Helper::redirecionar('/');
                    exit;
                } else{
                    Helper::redirecionar('/Admin/login');
                    exit;
                }
            }

            echo $this->template->renderizar("login.html.twig", [
                'titulo' => 'Título de teste',
                'subtitulo' => 'Subtítulo de teste',
                //'caminho' => [[' Home ', ''], ['/ Login ', 'login']]
            ]);
        }

        /* array(2) { ["email"]=> string(25) "raphaelestefano@gmail.com" ["senha"]=> string(4) "root" } object(stdClass)#130 (9) { ["id_usuario"]=> int(1) ["level"]=> int(3) ["nome"]=> string(15) "Raphael Stefano" ["email"]=> string(25) "raphaelestefano@gmail.com" ["senha"]=> string(4) "root" ["status"]=> int(0) ["ultimo_login"]=> NULL ["data_cadastro"]=> string(19) "2024-12-12 17:56:44" ["data_atualizacao"]=> string(19) "2024-12-12 17:56:44" } */ 

        public function loginEntrar(): void{
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(isset($dados)){

                //$dados['senha'] = Helper::gerarSenha($dados['senha']);
                //var_dump($dados['senha']);

                if($this->checarDados($dados)){
                    
                    $usuario = new UsuarioModelo();

                    if($this->validarLogin($dados)){
                        $user = $usuario->lerEspecifico($dados['email']);
                        if($this->validarAutorizacao($user, 3)){
                            $this->mensagem->sucesso("Seja bem-vindo, {$user->nome}!")->flash();
                            (new Sessao())->criar('id_usuario', $user->id_usuario);
                            Helper::redirecionar('Admin/');
                        } else{
                            (new Sessao())->criar('id_usuario', $user->id_usuario);
                            Helper::redirecionar('/');
                        }
                    }
                }
            }
            
            echo $this->template->renderizar("loginEntrar.html.twig", [
                'titulo' => 'Título de teste',
                'subtitulo' => 'Subtítulo de teste',
                //'caminho' => [[' Home ', ''], ['/ Login ', 'login'], ['/ Entrar ', 'login/entrar']]
            ]);
        }

    }

?>
