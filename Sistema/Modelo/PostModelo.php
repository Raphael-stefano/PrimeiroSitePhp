<?php

    namespace Sistema\Modelo;
    use Core\Conexao;


    class PostModelo extends PostCategoriaAbstract{
        protected string $categoria, $dataPostagem, $ultimaAlteracao;
        public function __construct() {
            parent::__construct();
            $this->table = "posts";
            $this->unidade = "post";
            $this->id = "id_post";
            $this->criterioOrdenacao = "order by id_post desc";
            $this->categoria = "id_categoria";
            $this->dataPostagem = "data_postagem";
            $this->ultimaAlteracao = "ultima_alteracao";
        }

        public function pesquisar(string $busca, string $ordem = null): array{
            $buscaLower = trim(strtolower($busca));
            if($ordem !== null){
                $query = "select * from posts where status = 1 and lower(titulo) like '%{$buscaLower}%' {$ordem}";
            } else{
                $query = "select * from posts where status = 1 and lower(titulo) like '%{$buscaLower}%' {$this->criterioOrdenacao}";
            }
            $stmt = Conexao::getInstance()->query($query);
            $resultado = $stmt->fetchAll();

            return $resultado;
        }

        public static function categoria(object $post): ?object{
            if($post->id_categoria){
                return (new CategoriaModelo)->lerEspecifico($post->id_categoria);
            }
            return null;
        }

        public static function usuario(object $post): ?object{
            if($post->id_usuario){
                return (new UsuarioModelo)->lerEspecifico($post->id_usuario);
            }
            return null;
        }

        public function editar(int $id, array $dados): void{
            if(isset($dados['titulo']) OR isset($dados['texto'])){
                $data = date('Y-m-d H-i-s');
                $query = "UPDATE {$this->table} SET {$this->ultimaAlteracao} = :dataAtual WHERE {$this->id} = :id";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->execute([
                    ':id' => $id,
                    ':dataAtual' => $data
                ]);
            }
            parent::editar($id, $dados);
        }

    }

?>