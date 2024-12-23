<?php 

    namespace Sistema\Controlador;

    use Core\Conexao;
    use Core\Controlador;
    use Sistema\Modelo\PostModelo;
    use Core\Helper;
    use Core\Mensagem;
    use Sistema\Modelo\CategoriaModelo;
    use Core\Sessao;
    use Sistema\Modelo\UsuarioModelo;

    class UsuarioControlador extends Controlador{
        
        public function __construct(){
            parent::__construct("Templates/Admin/Views");
        } 

        public static function usuario(){
            $sessao = new Sessao();
            if(!$sessao->checar('id_usuario')){
                return null;
            }
            return (new UsuarioModelo())->lerEspecifico($sessao->id_usuario);
        }

        protected function verificarDisponibilidadeEmail(string $email): bool{
            if((new UsuarioModelo())->acharIndice($email) !== null){
                $this->mensagem->alerta('Já há uma conta cadastrada nesse E-mail!')->flash();
                return false;
            }
            return true;
        }

        protected function verificarDisponibilidadeNome(string $nome): bool{
            if((new UsuarioModelo())->encontrarNome($nome) !== null){
                $this->mensagem->alerta('Esse nome de usuário nao está disponível!')->flash();
                return false;
            }
            return true;
        }

        protected function validarLogin(array $dados): bool{
            $usuario = new UsuarioModelo();
            
            if(isset($dados['email'])){
                $user = $usuario->lerEspecifico($dados['email']);
                if($user !== null AND $dados["email"] === $user->email AND Helper::verificarSenha($dados['senha'], $user->senha)){
                    
                    $query = "UPDATE usuario SET {$usuario->getUltimoAcessoName()} = current_timestamp() WHERE {$usuario->getIdName()} = :id";
                    $stmt = Conexao::getInstance()->prepare($query);
                    $stmt->execute([
                        ':id' => $user->id_usuario
                    ]);

                    return true;
                } else{
                    $this->mensagem->alerta('E-mail ou senha inválidos!')->flash();
                    return false;
                }
            }
        }

        protected function validarAutorizacao(array|object $dadosUser, int $minLevel): bool{
            if(is_array($dadosUser)){
                if($dadosUser['level'] >= $minLevel){
                    return true;
                } else{
                    $this->mensagem->perigo("Infelizmente, voce nao tem autorizaçao para acessar esta área")->flash();
                    return false;
                }
            }
            if(is_object($dadosUser)){
                if($dadosUser->level >= $minLevel){
                    return true;
                } else{
                    $this->mensagem->perigo("Infelizmente, voce nao tem autorizaçao para acessar esta área")->flash();
                    return false;
                }
            }
        }

        protected function checarDados(array $dados): bool{
            if(empty($dados['email'])){
                $this->mensagem->alerta('Preencha todos os campos com dados válidos')->flash();
                return false;
            }
            if(!Helper::validarEmail($dados['email'])){
                $this->mensagem->alerta("Insira um endereço de E-mail válido!")->flash();
                return false;
            }
            if(empty($dados['senha'])){
                $this->mensagem->alerta('Preencha todos os campos com dados válidos')->flash();
                return false;
            }
            return true;
        }

        protected function checarDadosCriar(array $dados): bool{
            if(!$this->checarDados($dados)){
                return false;
            }
            if(empty($dados['nome'])){
                $this->mensagem->alerta('Preencha todos os campos com dados válidos')->flash();
                return false;
            }
            if(empty($dados['senha_confirm'])){
                $this->mensagem->alerta('Preencha todos os campos com dados válidos')->flash();
                return false;
            }
            if(!Helper::validarSenha($dados['senha'])){
                $this->mensagem->alerta("A senha precisa ter entre 4 e 50 caracteres!")->flash();
                return false;
            }
            if($dados['senha_confirm'] != $dados['senha']){
                $this->mensagem->alerta("A senha precisa ser igual nos dois Campos!")->flash();
                return false;
            }
            if(!$this->verificarDisponibilidadeEmail($dados['email'])){
                return false;
            }
            if(!$this->verificarDisponibilidadeNome($dados['nome'])){
                return false;
            }
            return true;
        }

        protected function validarEmail(array|string $dados): bool{
            if(is_array($dados)){
                if(!Helper::validarEmail($dados['email'])){
                    $this->mensagem->alerta('Insira um endereço de E-mail válido!')->flash();
                    return false;
                }
            }
            if(is_string($dados)){
                if(!Helper::validarEmail($dados)){
                    $this->mensagem->alerta('Insira um endereço de E-mail válido!')->flash();
                    return false;
                }
            }
            return true;
        }

    }

?>