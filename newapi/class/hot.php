<?php
    class Hot
    {

        // Connection
        private $conn;

        // Table
        private $db_table = "hot";

        // Columns
        public $id;
        public $date_debut;
        public $date_fin;
        public $id_son;
        public $id_album;

        // Db connection
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // GET ALL
        public function getHotSingle()
        {
            $sqlQuery = "SELECT *  FROM  " . $this->db_table . " WHERE NOW() BETWEEN date_debut AND date_fin "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
       
    }
