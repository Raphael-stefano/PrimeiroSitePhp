<?php 

    namespace Sistema\Controlador;

    use Core\Conexao;
    use Core\Controlador;
    use Sistema\Modelo\PostModelo;
    use Core\Helper;
    use Core\Sessao;
    use Sistema\Modelo\CategoriaModelo;
    use Sistema\Modelo\UsuarioModelo;

    class SiteControlador extends Controlador{
        
        private $categorias;

        public function __construct(){
            parent::__construct("Templates/Site/Views");
            $this->categorias = (new CategoriaModelo)->ler();
        }

        public function home(): void{
            $posts = (new PostModelo)->ler();
            echo $this->template->renderizar("home.html.twig", [
                'titulo' => 'Título de teste',
                'subtitulo' => 'Subtítulo de teste',
                'posts' => $posts,
                'categorias' => $this->categorias,
            ]);
        }

        public function sobre(): void{
            echo $this->template->renderizar("sobre.html.twig", [
                'titulo' => 'Título de sobre',
                'subtitulo' => null,
                'categorias' => $this->categorias
            ]);
        }

        public function erro404(): void{
            echo $this->template->renderizar("404.html.twig", [
                'titulo' => 'Página nao encontrada',
                'categorias' => $this->categorias
            ]);
        }

        public function post(int $id): void{
            $posts = new PostModelo();
            $post = $posts->lerEspecifico($id);

            if($post == null){
                Helper::redirecionar("404");
            }

            $sessao = new Sessao();

            if(!isset($_SESSION["visita_post_{$id}"])){
                $dados['visitas'] = $post->visitas + 1;
                $posts->editar($id, $dados);
                $sessao->criar("visita_post_{$id}", true);
            }

            if(!isset($_SESSION["ultima_visita_post_{$id}"])){
                $sessao->criar("ultima_visita_post_{$id}", Helper::dataFormatoDMA($post->ultimo_acesso));
                $dados['ultimo_acesso'] = date('Y-m-d H-i-s');
                $posts->editar($id, $dados);
                $ultima_visita = $_SESSION["ultima_visita_post_{$id}"];
            }

            $ultima_visita = $_SESSION["ultima_visita_post_{$id}"];

            echo $this->template->renderizar("post.html.twig", [
                "post" => $post,
                'categorias' => $this->categorias,
                'ultima_visita' => $ultima_visita
            ]);
        }

        public function categoria(int $id): void{
            /*$categoria = (new CategoriaModelo())->lerEspecifico($id);
            if($categoria == null){
                Helper::redirecionar("404");
            }
            echo $this->template->renderizar("categoria.html.twig", [
                "categoria" => $categoria,
                'categorias' => $this->categorias
            ]);*/
            $posts = (new CategoriaModelo)->postsAtivos($id);
            echo $this->template->renderizar("categoria.html.twig", [
                "posts" => $posts,
                'categorias' => $this->categorias
            ]);
        }

        public function postsUsuario(int $id): void{
            $posts = (new UsuarioModelo)->posts($id);
            echo $this->template->renderizar("postsUsuario.html.twig", [
                'titulo' => 'Título de teste',
                'subtitulo' => 'Subtítulo de teste',
                'posts' => $posts,
                'categorias' => $this->categorias,
            ]);
        }

        public function editar(int $id): void{
            $post = (new PostModelo)->lerEspecifico($id);
            if($post == null){
                Helper::redirecionar("404");
            }

            $userId = (new Sessao)->id_usuario;

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if(isset($dados) AND $userId == $post->id_usuario){
                (new PostModelo())->editar($id, $dados);
                $this->mensagem->info('Post editado com sucesso!')->flash();
                header("Location: " . Helper::url("postsUsuario/{$userId}"));
                exit();
            }

            if($userId == $post->id_usuario){
                echo $this->template->renderizar("editar.html.twig", [
                    'categorias' => $this->categorias,
                    "post" => $post,
                ]);
            } else{
                Helper::redirecionar("404");
            }

        }

        public function buscar(): void{
            $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if(isset($busca)){
                $posts = (new PostModelo())->pesquisar($busca['busca']);
            }
            echo $this->template->renderizar("buscar.html.twig", [
                'titulo' => 'Título de teste',
                'subtitulo' => 'Subtítulo de teste',
                'posts' => $posts,
                'categorias' => $this->categorias
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
            Helper::redirecionar('/');
        }
    }

?>