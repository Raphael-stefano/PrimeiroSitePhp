<?php

    namespace Sistema\Modelo;
    use Core\Conexao;
use PDOException;

    class CategoriaModelo extends PostCategoriaAbstract{
        public function __construct() {
            parent::__construct();
            $this->table = "categoria";
            $this->unidade = "categoria";
            $this->id = "id_categoria";
            $this->criterioOrdenacao = "order by id_categoria desc";
        }
        /*public function posts(int $id): array{
            $query = "select * from posts where id_categoria = {$id} order by id_post desc";
            $stmt = Conexao::getInstance()->query($query);
            $resultado = $stmt->fetchAll();
            return $resultado;
        }*/
        public function posts(int $id): array{
            return (new PostModelo)->lerCondicional('id_categoria', $id);
        }
        
        public function postsAtivos(int $id): array{
            $retorno = (new PostModelo)->lerCondicional('id_categoria', $id);
            $retorno = array_filter($retorno, function($item) {
                return $item->status != 0; // Retorna true para os itens que devem ser mantidos
            });
            return $retorno;
        }
    }

?>