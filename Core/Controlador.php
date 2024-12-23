<?php

    namespace Core;
    use Sistema\Suporte\Template;

    class Controlador{
        protected Template $template;
        protected Mensagem $mensagem;

        public function __construct(string $diretorio){
            $this->template = new Template($diretorio);

            $this->mensagem = new Mensagem();
        }

        protected function validarDadosGenericos(array $dados): bool{
            $campos = array_keys($dados);
            foreach($campos as $campo){
                if(empty($dados[$campo])){
                    $this->mensagem->alerta("Preencha o campo {$campo} para continuar!")->flash();
                    return false;
                }
            }
            return true;
        }

    }



?>