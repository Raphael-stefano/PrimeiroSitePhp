<?php

    namespace Sistema\Modelo;
    use Core\Conexao;
    use PDO;

    abstract class PostCategoriaAbstract extends AbstractModelo{
        private string $texto;
        public function __construct(){
            parent::__construct();
            $this->title = "titulo";
            $this->texto = "texto";
        }

    }

?>