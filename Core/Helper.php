<?php

    namespace Core;
    require_once "Sistema/configuracao.php";
    require 'vendor/autoload.php';
    use Bissolli\ValidadorCpfCnpj\CPF;
    use Bissolli\ValidadorCpfCnpj\CNPJ;
    use Exception;

    class Helper{

        /**
         * Retorna uma data no formato "18/12/2024" recebendo por parametro uma data no formato "2024-12-18 17:48:50"
         *
         * @param string $data data no formato "2024-12-18 17:48:50" do MySQL
         * @return string
         */
        public static function dataFormatoDMA(string $data): string{
            return date('d/m/Y', strtotime($data));
        }

        /**
         * Retorna uma data no formato "17:48" recebendo por parametro uma data no formato "2024-12-18 17:48:50"
         *
         * @param string $data data no formato "2024-12-18 17:48:50" do MySQL
         * @return string
         */
        public static function horaFormatoMH(string $data): string{
            return date('H:i', strtotime($data));
        }

        /**
         * Verifica se a senha possui entre 4 e 50 caracteres, limites mínimo e máximo de número de caracteres 
         *
         * @param string $senha 
         * @return boolean
         */
        public static function validarSenha(string $senha): bool{
            if(mb_strlen($senha) >= 4 AND mb_strlen($senha) <= 50){
                return true;
            }
            return false;
        }

        public static function gerarSenha(string $senha): string{
            return password_hash($senha, PASSWORD_DEFAULT, ['cost' => 10]);
        }

        public static function verificarSenha(string $senha, string $hash): bool{
            return password_verify($senha, $hash);
        }

        /**
         * Mostra uma mensagem flash, se houver, na tela do usuário
         *
         * @return string|null
         */
        public static function flash(): ?string{
            $sessao = new Sessao();
            if($flash = $sessao->flash()){
                echo $flash;
            }
            return null;
        }

        public static function redirecionar(string $url = null): void{
            header("HTTP/1.1 302 Found");
            $local = $url ? self::url($url) : self::url();
            header("Location: {$local}");
            exit();
        }

        /**
         * Saúda o usuário baseado no horário
         *
         * @return string
         */
        public static function saudacao(): string{
            $time = date('H');
            if($time >= 18 OR $time < 6){
                return "Boa noite!";
            } elseif($time >= 6 AND $time <12){
                return "Bom dia!";
            } else{
                return "Boa tarde!";
            }
        }

        /**
         * Resume um texto
         * @param string $texto texto que será resumido
         * @param int $limite ponto em que o texto será cortado
         * @param string $continue opcional - texto que será adicionado ao final caso o texto de fato seja cortado
         * @return string texto resumido
         */
        public static function textoResumido(string $texto, int $limite, string $continue = '...'): string{
            
            $texto_limpo = trim(strip_tags($texto));

            $retorno = mb_strlen($texto_limpo) <= $limite ? $texto_limpo : trim(mb_substr($texto_limpo, 0, $limite)).$continue;

            return $retorno;
        }

        /**
         * Formata um número
         * @param float $valor Nímero a ser formatado
         * @param int $casas_decimais numero de casas decimais
         * @return string retorna o número arredondado para baixo em duas casas decimais, ou em outro número especidicado de casas decimais, com a parte decimal sendo separada por vírgula e os milhares sendo separados por ponto
         */
        public static function formatarValor(float $valor = null, int $casas_decimais = 2): string{
            return number_format(($valor ?? 0), $casas_decimais, ',', '.');
        }

        /**
         * Formata um número para formato monetário em reais
         *
         * @param float|null $valor número a ser formatado
         * @return string número formatado em duas casas decimais antecedido de 'R$'
         */
        public static function formatarMonetario(float $valor = null): string{
            return "R$ ".self::formatarValor($valor);
        }

        /**
         * Conta o tempo desde uma data especificada até o momento atual
         *
         * @param string $data data fornecida em formato americano: 'Y-m-d H:i:s'
         * @return string Tempo na maior unidade de medida de tempo possível
         */
        public static function contarTempo(string $data): string{
            $agora_bruto = date('Y-m-d H:i:s');
            $agora = strtotime($agora_bruto);
            $data_tratada = strtotime($data);
            $diferenca = $agora - $data_tratada;

            $segundos = $diferenca;
            $minutos = floor($diferenca / 60);
            $horas = floor($minutos / 60);
            $dias = floor($horas / 24);
            $semanas = floor($dias / 7);

            if($segundos < 60){
                return 'Agora';
            } elseif($minutos < 60){
                return $minutos == 1 ? "{$minutos} minuto atrás" : "{$minutos} minutos atrás";
            } elseif($horas < 24){
                return $horas == 1 ? "{$horas} hora atrás" : "{$horas} horas atrás";
            } elseif($dias < 7){
                return $dias == 1 ? "{$dias} dia atrás" : "{$dias} dias atrás";
            } elseif($dias < 31){
                return $semanas == 1 ? "{$semanas} semana atrás" : "{$semanas} semanas atrás";
            } elseif($dias < 365){
                $mes_atual = date('m');
                $data_mes = mb_substr($data, 5, 2);

                $dia_atual = date('d');
                $data_dia = mb_substr($data, 8, 2);
                $diferenca_dias = $dia_atual - $data_dia;

                $retorno = $diferenca_dias >= 0 ? (int)$mes_atual - (int)$data_mes : (int)$mes_atual - (int)$data_mes - 1;
                return $retorno == 1 ? "{$retorno} mes atrás" : "{$retorno} meses atrás";
            } else{
                $ano_atual = date('Y');
                $data_ano = mb_substr($data, 0, 4);

                $mes_atual = date('m');
                $data_mes = mb_substr($data, 5, 2);
                $diferenca_meses = $mes_atual - $data_mes;

                $retorno = $diferenca_meses >= 0 ? (int)$ano_atual - (int)$data_ano : (int)$ano_atual - (int)$data_ano - 1;
                return $retorno == 1 ? "{$retorno} ano atrás" : "{$retorno} anos atrás";
            }

        }

        /**
         * validacao de email feita de forma caseira
         *
         * @param string $email email a ser validado
         * @return boolean se é válido ou nao
         */
        public static function validarEmail(string $email): bool{

            $tem_arroba = strpos($email, '@');
            $tem_ponto = strpos(mb_substr($email, $tem_arroba), '.');

            if(!$tem_arroba){
                return false;
            } elseif(!$tem_ponto){
                return false;
            }

            return true;
        }

        /**
         * filtro de email usando métodos nativos
         *
         * @param string $email email a ser validado
         * @return boolean se é válido ou nao
         */
        public static function validarEmailNativo(string $email): bool{
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        /**
         * Verifica se a conexao é ou nao local
         *
         * @return boolean
         */
        public static function localhost(): bool{
            $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

            if($servidor == 'localhost') return true;

            return false;
        }

        /**
         * Retorna uma url a partir do ambiente em que a aplicaçao está rodando
         *
         * @param string $url
         * @return string
         */
        public static function url(string $url = ""): string{
            $ambiente = self::localhost() ? LINK_LOCAL : LINK_EXTERNO;
            if(str_starts_with($url, '/')) return $ambiente.$url;
            return $ambiente.'/'.$url;
        }

        /**
         * Retorna a data atual no formato '[dia_semana], [dia_mes] de [mes] de [ano]' 
         *
         * @return string
         */
        public static function dataAtualFormatada(): string{
            //$data = date('w, j \d\e n \d\e Y');
            $meses = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
            $dias_semana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
            $dia_atual = date('j');
            $dia_semana = date('w');
            $mes_atual = date('n');
            $ano_atual = date('Y');

            return $dias_semana[$dia_semana] . ', ' . $dia_atual . ' de ' . $meses[$mes_atual - 1] . ' de ' . $ano_atual;

        }

        /**
         * Gera uma url amigavel
         *
         * @param string $url url a ser tratada
         * @return string
         */
        public static function slug(string $url): string{

            $mapa = [
                'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
                'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
                'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
                'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
                'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
                'ç' => 'c', 'ñ' => 'n',
                'Á' => 'a', 'À' => 'a', 'Ã' => 'a', 'Â' => 'a', 'Ä' => 'a',
                'É' => 'e', 'È' => 'e', 'Ê' => 'e', 'Ë' => 'e',
                'Í' => 'i', 'Ì' => 'i', 'Î' => 'i', 'Ï' => 'i',
                'Ó' => 'o', 'Ò' => 'o', 'Õ' => 'o', 'Ô' => 'o', 'Ö' => 'o',
                'Ú' => 'u', 'Ù' => 'u', 'Û' => 'u', 'Ü' => 'u',
                'Ç' => 'c', 'Ñ' => 'n',
                '&' => 'e', '@' => '', '%' => '', '$' => '', '#' => '',
                '*' => '', '+' => '', '=' => '', ',' => '', '.' => '',
                '!' => '', '?' => '', ':' => '', '(' => '', ')' => '',
                '[' => '', ']' => '', '{' => '', '}' => ''
            ];

            $slug = strtr($url, $mapa);

            $slug = strip_tags(trim($slug));

            //$slug = trim('-', $slug);

            $slug = mb_strtolower($slug, 'UTF-8');

            $slug = preg_replace('/[\s-]+/', '-', $slug);

            return $slug;
        }

        public static function validarCpf(string $cpf): bool{
            $obj = new CPF($cpf);
            return $obj->isValid();
        }

        public static function validarCnpj(string $cnpj): bool{
            $obj = new CNPJ($cnpj);
            return $obj->isValid();
        }

        /**
         * Retorna o cpf com apenas números
         *
         * @param string $cpf
         * @return string
         */
        public static function limparCpf(string $cpf): string{
            $retorno = preg_replace('/[^0-9]/', '', $cpf);
            return $retorno;
        }

        /**
         * Recebe um cpf com apenas números e retorna um cpf com pontos e traço
         *
         * @param string $cpf
         * @return string
         */
        public static function formatarCpf(string $cpf): string{
            if(mb_strlen($cpf) != 11){
                throw new Exception("O cpf precisa ter 11 dígitos");
            }
            
            if(!self::validarCpf($cpf)){
                throw new Exception("Nao é um cpf válido");
            }

            $parte1 = mb_substr($cpf, 0, 3);
            $parte2 = mb_substr($cpf, 3, 3);
            $parte3 = mb_substr($cpf, 6, 3);
            $parte4 = mb_substr($cpf, 9);

            $retorno = "{$parte1}.{$parte2}.{$parte3}-{$parte4}";

            return $retorno;
        }
    }

?>