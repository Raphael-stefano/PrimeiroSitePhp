<?php

    namespace Core;

    /**
     * Classe que administra uma mensagem de texto
     * 
     * @author Raphael Stefano Barros Erbetta <raphaelestefano@email.com>
     */
    class Mensagem{
        private string $texto, $css;
        
        public function __construct(string $texto = "Mensagem padrao", string $css = "padrao"){
            $this->texto = $texto;
            $this->css = $css;
        }

        public function __toString(){
            return $this->renderizar();
        }

        public function filtrar(): string{
            return strip_tags(trim($this->texto)); 
        }

        private function renderizar(): string{
            return "<div class='{$this->css} alert-dismissible fade show' role='alert'>{$this->texto}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }

        public function sucesso(string $mensagem = "Mensagem de sucesso"): Mensagem{
            $this->texto = "$mensagem";
            $this->css = "alert alert-success";
            return $this;
        }

        public function perigo(string $mensagem = "Mensagem de erro"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "alert alert-danger";
            return $this;
        }

        public function alerta(string $mensagem = "Mensagem de alerta"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "alert alert-warning";
            return $this;
        }

        public function info(string $mensagem = "Mensagem de info"): Mensagem{
            $this->texto = $mensagem;
            $this->css = "alert alert-info";
            return $this;
        }

        public function getTexto(): string{
            return $this->texto;
        }

        public function setTexto(string $texto): void{
            $this->texto = $texto;
        }

        public function flash(): void{
            $sessao = new Sessao();
            $sessao->criar('flash', $this);
        }
    }

?>