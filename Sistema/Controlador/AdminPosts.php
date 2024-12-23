<?php 

    namespace Sistema\Controlador;

    use Core\Conexao;
    use Core\Controlador;
    use Sistema\Modelo\PostModelo;
    use Core\Helper;
    use Sistema\Modelo\CategoriaModelo;

    class AdminPosts extends AdminControlador{
        
        public function __construct(){
            parent::__construct("Templates/Admin/Views");
        }

        public function listar(): void{
            $posts = new PostModelo();
            echo $this->template->renderizar("Posts/listar.html.twig", [
                'titulo' => 'Título de teste',
                'subtitulo' => 'Subtítulo de teste',
                'posts' => $posts->ler(),
                'total' => $posts->total(),
                'total_ativos' => $posts->totalAtivo(),
                'caminho' => [[' Home ', ''], ['/ Posts ', 'posts/listar']]
            ]);
        }

        public function cadastrar(): void{
            $categorias = (new CategoriaModelo)->ler();
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            /*if(isset($dados)){
                (new PostModelo())->cadastrar($dados['titulo'], $dados['texto'], $dados['status'], $dados['categoria']);
                $this->mensagem->sucesso('Post cadastrado com sucesso!')->flash();
                header("Location: " . Helper::url('Admin/posts/listar'));
                exit();
            }*/
            if(isset($dados)){
                if($this->validarDadosGenericos($dados)){
                    (new PostModelo())->cadastrar($dados);
                    $this->mensagem->sucesso('Post cadastrado com sucesso!')->flash();
                    header("Location: " . Helper::url('Admin/posts/listar'));
                    exit();
                } else{
                    header("Location: " . Helper::url('Admin/posts/cadastrar'));
                    exit();
                }
            }
            echo $this->template->renderizar("Posts/cadastrar.html.twig", [
                'categorias' => $categorias,
                'caminho' => [[' Home ', ''], ['/ Posts ', 'posts/listar'], ['/ Cadastrar ', 'posts/cadastrar']]
            ]);
        }

        public function editar(int $id): void{
            $categorias = (new CategoriaModelo)->ler();

            $post = (new PostModelo)->lerEspecifico($id);
            if($post == null){
                Helper::redirecionar("404");
            }

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            /*if(isset($dados)){
                (new PostModelo())->editar($id, $dados['texto'], $dados['titulo'], $dados['status'], $dados['categoria']);
                $this->mensagem->info('Post editado com sucesso!')->flash();
                header("Location: " . Helper::url('Admin/posts/listar'));
                exit();
            }*/
            if(isset($dados)){
                (new PostModelo())->editar($id, $dados);
                $this->mensagem->info('Post editado com sucesso!')->flash();
                header("Location: " . Helper::url('Admin/posts/listar'));
                exit();
            }

            echo $this->template->renderizar("Posts/editar.html.twig", [
                'categorias' => $categorias,
                "post" => $post,
                'caminho' => [[' Home ', ''], ['/ Posts ', 'posts/listar'], ['/ Editar ', "posts/editar/{$id}"]]
            ]);

        }

        public function deletar(int $id): void{
            $post = new PostModelo();
            if($post->lerEspecifico($id) !== null){
                $post->deletar($id);
                $this->mensagem->alerta('Post excluído com sucesso!')->flash();
                Helper::redirecionar('Admin/posts/listar');
            } else{
                $this->mensagem->alerta('O post em questao nao existe!')->flash();
                Helper::redirecionar('Admin/posts/listar');
            }
        }

    }

?>