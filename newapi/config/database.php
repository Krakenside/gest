<?php
    class Database {
        private $host = "localhost";
//        private $database_name = "afreekaplay";
//        private $username = "root";
//        private $password = "root";
         private $database_name = "afrekply";
         private $username = "RuT";
         private $password = "projetafricain";
        private $port = "3306";

        public $conn;

        public function getConnection(){
            $this->conn = null;
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);

                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }
?>
