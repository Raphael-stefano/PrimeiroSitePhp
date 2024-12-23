<?php

    namespace Sistema\Modelo;
    use Core\Conexao;
    use PDO;

    abstract class AbstractModelo implements ModeloInterface{
        protected string $id, $status;
        protected string $table, $unidade, $title; 
        protected string $criterioOrdenacao;
        
        public function __construct(){
            $this->status = "status";
        }

        public function ler(string $ordem = null): array{
            $ordenacao = ($ordem !== null) ? $ordem : $this->criterioOrdenacao;
            $query = "SELECT * FROM {$this->table} {$ordenacao}";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            return $resultado;
        }
        public function lerCondicional(string $atributo, string|int  $condicao, string $ordem = null): array{
            $ordenacao = ($ordem !== null) ? $ordem : $this->criterioOrdenacao;
            $query = "SELECT * FROM {$this->table} WHERE {$atributo} = :condicao {$ordenacao}";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute([
                ":condicao" => $condicao
            ]);
            $resultado = $stmt->fetchAll();
            return $resultado;
        }
        public function lerValidos(string $ordem = null): array{
            $ordenacao = ($ordem !== null) ? trim($ordem) : $this->criterioOrdenacao;
            $query = "SELECT * FROM {$this->table} WHERE {$this->status} = 1 {$ordenacao}";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            return $resultado;
        }
        public function lerEspecifico($id): object|null{
            if (is_int($id)){
                $query = "SELECT * FROM {$this->table} WHERE {$this->id} = :id";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $resultado = $stmt->fetch();
                if($resultado){
                    return $resultado;        
                } else{
                    return null;
                }
            } elseif(is_string($id)){
                $indice = $this->acharIndice(trim($id));
                if($indice){
                    return $this->lerEspecifico($indice);
                } else{
                    return null;
                }
            }
        }
        public function ativar($indice): void{
            if (is_int($indice)){
                $query = "UPDATE {$this->table} SET {$this->status} = 1 WHERE {$this->id} = :indice";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindParam(':indice', $indice, PDO::PARAM_INT);
                $stmt->execute();        
            } elseif (is_string($indice)){
                $id = $this->acharIndice($indice);
                if ($id !== null) {
                    $this->ativar($id);
                } else{
                    echo "{$this->unidade} com título '{$indice}' não encontrado(a).";
                }
            } else{
                echo "Índice inválido.";
            }
        }
        public function desativar($indice): void {
            if (is_int($indice)){
                $query = "UPDATE {$this->table} SET {$this->status} = 0 WHERE {$this->id} = :indice";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->bindParam(':indice', $indice, PDO::PARAM_INT);
                $stmt->execute();        
            } elseif (is_string($indice)){
                $id = $this->acharIndice($indice);
                if ($id !== null) {
                    $this->desativar($id);
                } else{
                    echo "{$this->unidade} com título '{$indice}' não encontrado(a).";
                }
            } else{
                echo "Índice inválido.";
            }
        }  
        public function acharIndice(string $titulo): ?int{
            $titulo = trim($titulo);
            $query = "SELECT {$this->id} FROM {$this->table} WHERE TRIM({$this->title}) = :titulo";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
            if($resultado){
                return $resultado->{$this->id};
            } else{
                return null;
            }
        }
        public function cadastrar(array $dados): void{
            $campos = implode(', ', array_keys($dados));
            $parametros = ":" . implode(", :", array_keys($dados));
            $query = "INSERT INTO {$this->table} ({$campos}) VALUES ({$parametros})";
            $stmt = Conexao::getInstance()->prepare($query);
            $valores = array();
            foreach($dados as $campo => $valor){
                $valores[":$campo"] = $valor;
            }
            $stmt->execute($valores);
        }
        public function editar(int $id, array $dados): void{
            $campos = array();
            foreach(array_keys($dados) as $dado){
                $campos[$dado] = "{$dado} = :{$dado}";
            }
            $set = implode(", ", $campos);
            $query = "UPDATE {$this->table} SET {$set} WHERE {$this->id} = :id";
            $stmt = Conexao::getInstance()->prepare($query);
            $valores = array();
            foreach($dados as $campo => $valor){
                $valores[":$campo"] = $valor;
            }
            $valores[":id"] = $id;
            $stmt->execute($valores);
        }
        public function deletar(int $id): void{
            if($this->lerEspecifico($id) !== null){
                $query = "DELETE FROM {$this->table} WHERE {$this->id} = :id";
                $stmt = Conexao::getInstance()->prepare($query);
                $stmt->execute([
                    ':id' => $id,
                ]);
            }
        }
        public function total(): int{
            $query = "SELECT count(*) from {$this->table}";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute();
            $resultado = $stmt->fetchColumn();
            return (int) $resultado;
        }
        public function totalCondicional(string $atributo, string|int  $condicao): int{
            $query = "SELECT count(*) from {$this->table} WHERE {$atributo} = :condicao";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute([
                ':condicao' => $condicao
            ]);
            $resultado = $stmt->fetchColumn();
            return (int) $resultado;
        }
        public function totalAtivo(): int{
            $query = "SELECT count(*) from {$this->table} WHERE {$this->status} = 1";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute();
            $resultado = $stmt->fetchColumn();
            return (int) $resultado;
        }
        public function totalCondicionalAtivo(string $atributo, string|int $condicao): int{
            $query = "SELECT count(*) from {$this->table} WHERE {$this->status} = 1 AND {$atributo} = :condicao;";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute([
                ':condicao' => $condicao
            ]);
            $resultado = $stmt->fetchColumn();
            return (int) $resultado;
        }
    }

?>