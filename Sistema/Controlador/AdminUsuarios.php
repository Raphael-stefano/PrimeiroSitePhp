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

    class AdminUsuarios extends UsuarioControlador{
        
        public function __construct(){
            parent::__construct("Templates/Admin/Views");
        }

        public function listar(): void{
            $usuarios = new UsuarioModelo();
            echo $this->template->renderizar('Usuarios/listar.html.twig', [
                'teste' => 'testando',
                'usuarios' => $usuarios->ler(),
                'total' => $usuarios->total(),
                'total_ativos' => $usuarios->totalAtivo(),
                'caminho' => [[' Home ', ''], ['/ Usuarios ', 'usuarios/listar']]
            ]);
        }

        public function cadastrar(): void{
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(isset($dados)){
                $dados['senha'] = Helper::gerarSenha($dados['senha']);

                if(!$this->validarEmail($dados['email']) OR !$this->verificarDisponibilidadeEmail($dados['email']) OR !$this->verificarDisponibilidadeNome($dados['nome'])){
                    header("Location: " . Helper::url('Admin/usuarios/cadastrar'));
                    exit();
                }
                
                (new UsuarioModelo())->cadastrar($dados);
                $this->mensagem->sucesso('Usuario cadastrado com sucesso!')->flash();
                header("Location: " . Helper::url('Admin/usuarios/listar'));
                exit();
            }
            echo $this->template->renderizar("Usuarios/cadastrar.html.twig", [
                'caminho' => [[' Home ', ''], ['/ Usuarios ', 'usuarios/listar'], ['/ Cadastrar ', 'usuarios/cadastrar']]
            ]);
        }

        public function editar(int $id){
            $usuario = (new UsuarioModelo)->lerEspecifico($id);
            if($usuario == null){
                Helper::redirecionar("404");
            }

            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if(isset($dados)){
                if(
                        !Helper::validarEmail($dados['email']) 
                        OR 
                        (!UsuarioControlador::verificarDisponibilidadeEmail($dados['email']) AND $dados['email'] != $usuario->email) 
                        OR 
                        (!UsuarioControlador::verificarDisponibilidadeNome($dados['nome'])  AND $dados['nome'] != $usuario->nome)
                    ){
                    header("Location: " . Helper::url('Admin/usuarios/cadastrar'));
                    exit();
                }
                if(empty($dados['senha'])){
                    unset($dados['senha']);
                } else{

                    $dados['senha'] = Helper::gerarSenha($dados['senha']);

                }
                //$dados['data_atualizacao'] = date("Y-m-d H-i-s");
                (new UsuarioModelo())->editar($id, $dados);
                $this->mensagem->info('Usuário editado com sucesso!')->flash();
                header("Location: " . Helper::url('Admin/usuarios/listar'));
                exit();
            }

            echo $this->template->renderizar("Usuarios/editar.html.twig", [
                "usuario" => $usuario,
                'caminho' => [[' Home ', ''], ['/ Usuarios ', 'usuarios/listar'], ['/ Editar ', "usuarios/editar/{$id}"]]
            ]);

        }

        public function deletar(int $id): void{
            $usuario = new UsuarioModelo();
            if($usuario->lerEspecifico($id) !== null){
                $usuario->deletar($id);
                $this->mensagem->perigo('Usuario excluído com sucesso!')->flash();
                Helper::redirecionar('Admin/usuarios/listar');
            } else{
                $this->mensagem->alerta('O usuario em questao nao existe!')->flash();
                Helper::redirecionar('Admin/usuarios/listar');
            }
        }

    }
?>

