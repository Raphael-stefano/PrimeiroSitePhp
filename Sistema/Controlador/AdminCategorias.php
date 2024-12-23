<?php 

    namespace Sistema\Controlador;

    use Core\Conexao;
    use Core\Controlador;
    use Sistema\Modelo\PostModelo;
    use Core\Helper;
use PDOException;
use Sistema\Modelo\CategoriaModelo;

    class AdminCategorias extends AdminControlador{
        
        public function __construct(){
            parent::__construct("Templates/Admin/Views");
        }

        public function listar(): void{
            $categorias = new CategoriaModelo();
            echo $this->template->renderizar("Categorias/listar.html.twig", [
                'titulo' => 'Título de teste',
                'subtitulo' => 'Subtítulo de teste',
                'categorias' => $categorias->ler(),
                'total' => $categorias->total(),
                'total_ativos' => $categorias->totalAtivo(),
                'caminho' => [[' Home ', ''], ['/ Categorias ', 'categorias/listar']]
            ]);
        }

        public function cadastrar(): void{
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            /*if(isset($dados)){
                (new CategoriaModelo())->cadastrar($dados['titulo'], $dados['texto'], $dados['status']);
                $this->mensagem->sucesso('Categoria cadastrada com sucesso!')->flash();
                header("Location: " . Helper::url('Admin/categorias/listar'));
                exit();
            }*/
            if(isset($dados)){
                if($this->validarDadosGenericos($dados)){
                    (new CategoriaModelo())->cadastrar($dados);
                    $this->mensagem->sucesso('Categoria cadastrada com sucesso!')->flash();
                    header("Location: " . Helper::url('Admin/categorias/listar'));
                    exit();
                } else{
                    header("Location: " . Helper::url('Admin/categorias/cadastrar'));
                    exit();
                }
            }
            echo $this->template->renderizar("Categorias/cadastrar.html.twig", [
                'caminho' => [[' Home ', ''], ['/ Categorias ', 'categorias/listar'], ['/ Cadastrar ', 'categorias/cadastrar']]
            ]);
        }

        public function editar(int $id){
            $categoria = (new CategoriaModelo)->lerEspecifico($id);
            if($categoria == null){
                Helper::redirecionar("404");
            }

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            /*if(isset($dados)){
                (new CategoriaModelo())->editar($id, $dados['texto'], $dados['titulo'], $dados['status']);
                $this->mensagem->info('Categoria editada com sucesso!')->flash();
                header("Location: " . Helper::url('Admin/categorias/listar'));
                exit();
            }*/
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if(isset($dados)){
                (new CategoriaModelo())->editar($id, $dados);
                $this->mensagem->info('Categoria editada com sucesso!')->flash();
                header("Location: " . Helper::url('Admin/categorias/listar'));
                exit();
            }

            echo $this->template->renderizar("Categorias/editar.html.twig", [
                "categoria" => $categoria,
                'caminho' => [[' Home ', ''], ['/ Categorias ', 'categorias/listar'], ['/ Editar ', "categorias/editar/{$id}"]]
            ]);
        }

        public function deletar(int $id): void{
            $categoria = new CategoriaModelo();
            if($categoria->lerEspecifico($id) !== null){
                if(!$categoria->posts($id)){
                    $categoria->deletar($id);
                    $this->mensagem->alerta('Categoria excluída com sucesso!')->flash();
                } else{
                    $this->mensagem->perigo("A categoria em questao possui posts cadastrados, altere-os ou os delete para excluir a categoria")->flash();
                }
                Helper::redirecionar('Admin/categorias/listar');
            } else{
                $this->mensagem->alerta('A categoria em questao nao existe!')->flash();
                Helper::redirecionar('Admin/categorias/listar');
            }
        }

    }

?>