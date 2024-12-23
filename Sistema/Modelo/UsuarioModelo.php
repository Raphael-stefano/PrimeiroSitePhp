<?php

    namespace Sistema\Modelo;
    use Core\Conexao;
use Core\Helper;
use PDO;


    class UsuarioModelo extends AbstractModelo{
        protected string $senha, $ultimoAcesso, $level, $nome;
        protected string $email, $dataCadastro, $ultimaAlteracao;
        public function __construct(){
            parent::__construct();
            $this->table = "usuario";
            $this->unidade = "usuario";
            $this->id = "id_usuario";
            $this->nome = "nome";
            $this->senha = "senha";
            $this->level = "level";
            $this->email = "email";
            $this->title = $this->email;
            $this->ultimoAcesso = "ultimo_login";
            $this->dataCadastro = "data_cadastro";
            $this->ultimaAlteracao = "data_atualizacao";
            $this->criterioOrdenacao = "order by id_usuario desc";
        }

        public function encontrarNome(string $nome): ?int{
            $nome = trim($nome);
            $query = "SELECT {$this->id} FROM {$this->table} WHERE TRIM({$this->nome}) = :nome";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
            if($resultado){
                return $resultado->{$this->id};
            } else{
                return null;
            }
        }

        public function editar(int $id, array $dados): void{
            $data = date('Y-m-d H-i-s');
            $query = "UPDATE {$this->table} SET {$this->ultimoAcesso} = :dataAtual WHERE {$this->id} = :id";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':dataAtual' => $data
            ]);
            parent::editar($id, $dados);
        }

        public function getIdName(): string {
            return $this->id;
        }
        
        public function getUltimoAcessoName(): string{
            return $this->ultimoAcesso;
        }

        public function posts(int $id): array{
            return (new PostModelo)->lerCondicional('id_usuario', $id);
        }

        /*public function atualizarSenha(int $id) : void{
            $user = $this->lerEspecifico($id);
            $senha = Helper::gerarSenha($user->senha);
            $query = "UPDATE {$this->table} SET {$this->senha} = :senhaAlterada WHERE {$this->id} = :id;";
            $stmt = Conexao::getInstance()->prepare($query);
            $stmt->execute([
                ':senhaAlterada' => $senha,
                ':id' => $id
            ]);
        }*/

        /*public function cadastrar(array $dados): void{

            //

            parent::cadastrar($dados);
        }*/

    }
?>