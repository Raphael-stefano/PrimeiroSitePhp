<?php 

    namespace Sistema\Controlador;

    use Core\Conexao;
    use Core\Controlador;
    use Sistema\Modelo\PostModelo;
    use Core\Helper;
    use Core\Mensagem;
    use Sistema\Modelo\CategoriaModelo;
    use Sistema\Controlador\UsuarioControlador;
    use Core\Sessao;
    use Sistema\Modelo\UsuarioModelo;

    class AdminControlador extends Controlador{
        protected $usuario;
        public function __construct(){
            parent::__construct("Templates/Admin/Views");

            $this->usuario = UsuarioControlador::usuario();
            if($this->usuario === null OR $this->usuario->level != 3){

                if($this->usuario === null){
                    $this->mensagem->perigo("Faça login para acessar o painel de controle!")->flash();
                }

                if($this->usuario->level != 3){
                    $this->mensagem->perigo("Infelizmente, voce nao tem autorizaçao para acessar esta área")->flash();
                }

                Helper::redirecionar('Admin/login');
            }

        }

        public function dashboard(): void{
            $posts = new PostModelo();
            $categorias = new CategoriaModelo();
            $usuarios = new UsuarioModelo();
            echo $this->template->renderizar("dashboard.html.twig", [
                'caminho' => [[' Home ', '']],
                'cards' => [
                    'posts' => [
                        'nome_tabela' => 'Posts',
                        'total' => $posts->total(),
                        'ativos' => $posts->totalAtivo(),
                        'inativos' => $posts->total() - $posts->totalAtivo(),
                        'icone' => "bi bi-postcard-fill fs-3"
                    ],
                    'categorias' => [
                        'nome_tabela' => 'Categorias',
                        'total' => $categorias->total(),
                        'ativos' => $categorias->totalAtivo(),
                        'inativos' => $categorias->total() - $categorias->totalAtivo(),
                        'icone' => "bi bi-tags-fill fs-3"
                    ],
                    'usuarios' => [
                        'nome_tabela' => 'Usuários',
                        'total' => $usuarios->total(),
                        'ativos' => $usuarios->totalAtivo(),
                        'inativos' => $usuarios->total() - $usuarios->totalAtivo(),
                        'icone' => "bi bi-people-fill fs-3"
                    ],
                    'admins' => [
                        'nome_tabela' => 'Administradores',
                        'total' => $usuarios->totalCondicional('level', 3),
                        'ativos' => $usuarios->totalCondicionalAtivo('level', 3),
                        'inativos' => $usuarios->totalCondicional('level', 3) - $usuarios->totalCondicionalAtivo('level', 3),
                        'icone' => "bi fs-3 bi-person-vcard-fill"
                    ]
                ],
                'mensagem' => true
            ]);
        }

        public function perfil(): void{
            echo $this->template->renderizar('perfil.html.twig', [
                'teste' => 'testando',
                'usuario' => (new UsuarioControlador)->usuario(),
            ]);
        }

        public function sair(): void{
            $sesssao = new Sessao();
            $sesssao->deletar();
            $this->usuario = null;
            var_dump($sesssao->id_usuario);
            $this->mensagem->alerta("Voce saiu do painel de controle!")->flash();
            Helper::redirecionar('Admin/login');
        }

    }

?>