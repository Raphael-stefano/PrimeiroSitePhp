<?php

    namespace Sistema\Modelo;
    use Core\Conexao;
    use PDO;

    interface ModeloInterface{
        public function ler(): array;
        public function lerValidos(): array;
        public function lerEspecifico($id): object|null;
        public function ativar($indice): void;
        public function desativar($indice): void; 
        public function acharIndice(string $busca): ?int;
        public function cadastrar(array $dados): void;
        public function editar(int $id, array $dados): void;
        public function deletar(int $id): void;
        public function total(): int;
        public function totalAtivo(): int;
    }

?>