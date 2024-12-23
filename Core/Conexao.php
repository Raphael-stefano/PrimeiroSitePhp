<?php

    namespace Core;
    require_once "Sistema/configuracao.php";

    use Exception;
    use PDO;
    use PDOException;

    class Conexao{
        private static PDO $instance;

        public static function getInstance(): PDO{
            if(empty(self::$instance)){
                try{
                    self::$instance = new PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USER, DB_PASS, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_CASE => PDO::CASE_NATURAL
                    ]);
                } catch(PDOException $ex){
                    die("Erro de conexao:: " . $ex->getMessage());
                }
                return self::$instance;
            }

            return self::$instance;
        }

        protected function __construct(){
            
        }

        protected function __clone(): void{
            
        }

    }

?>