<?php

    namespace Sistema\Suporte;
    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;
    use Twig\Lexer;
    use Twig\TwigFunction;
    use Core\Helper;
use Sistema\Controlador\UsuarioControlador;
use Sistema\Modelo\CategoriaModelo;
use Sistema\Modelo\PostModelo;

    class Template{
        private Environment $twig;

        public function __construct(string $diretorio){
            $loader = new FilesystemLoader($diretorio);
            $this->twig = new Environment($loader);

            $lexer = new Lexer($this->twig, array($this->helpers()));
            $this->twig->setLexer($lexer);
        }

        public function renderizar(string $view, array $dados): string{
            return $this->twig->render($view, $dados);
        }

        private function helpers(): void{
            array(
                $this->twig->addFunction(
                    new TwigFunction("url", function(string $url = ""){
                        return Helper::url($url);
                    })   
                ),
                $this->twig->addFunction(
                    new TwigFunction("saudacao", function(): string{
                        return Helper::saudacao();
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("textoResumido", function(string $texto, int $limite, string $continue = '...'): string{
                        return Helper::textoResumido($texto, $limite, $continue);
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("flash", function(){
                        return Helper::flash();
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("usuario", function(){
                        return UsuarioControlador::usuario();
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("redirecionar", function(string $url){
                        return Helper::redirecionar($url);
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("contarTempo", function(string $data){
                        return Helper::contarTempo($data);
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("dataFormatoDMA", function(string $data){
                        return Helper::dataFormatoDMA($data);
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("horaFormatoMH", function(string $data){
                        return Helper::horaFormatoMH($data);
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("categoria", function(object $post){
                        return PostModelo::categoria($post);
                    })
                ),
                $this->twig->addFunction(
                    new TwigFunction("postUsuario", function(object $post){
                        return PostModelo::usuario($post);
                    })
                )
            );
        }
    }
?>