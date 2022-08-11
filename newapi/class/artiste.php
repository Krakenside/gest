<?php
    class Artiste
    {

        // Connection
        private $conn;

        // Table
        private $db_table = "artiste";

        // Columns
        public $id_artiste;
        public $id_maison;
        public $lien_artiste;
        public $pourcentage_artiste;
        public $dateSortie_album;
        public $nationalite_artiste;
        public $dob_artiste;
        public $cover_artiste;
        public $nom_artiste;
        public $is_active;
        public $date_verif;
        public $dte_enr;

        // Db connection
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // GET ALL
        public function getArtiste()
        {
            $sqlQuery = "SELECT *  FROM  " . $this->db_table . "  ORDER BY RAND() LIMIT 100"  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // GET ALL
        public function getOrderedArtiste()
        {
            $sqlQuery = "SELECT *  FROM  " . $this->db_table . "ORDER BY nom_artiste ORDER BY RAND() LIMIT 10"  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // GET NB DOWNLOAD
        public function getNbDownloadSingle($id_son)
        {
            $sqlQuery = "SELECT COUNT(`id_telechargements`) AS downloads FROM `telechargements` WHERE `id_son`=$id_son"  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
    
        // GET ARTISTE  OF SINGLE
        public function getArtisteSingle($id_artiste)
        {
            $sqlQuery = "SELECT *  FROM `artiste` WHERE `id_artiste`=$id_artiste"  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
    }
