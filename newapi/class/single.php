<?php
    class Single
    {

        // Connection
        private $conn;

        // Table
        private $db_table = "son";

        // Columns
        public $id_son;
        public $reference_son;
        public $titre_son;
        public $fichier_son;
        public $cover_son;
        public $prix_son;
        public $duree_son;
        public $url_son;
        public $dateSortie_son;
        public $visible_son;
        public $lien_son;
        public $id_album;
        public $id_artiste;
        public $id_genre;
        public $is_active;
        public $date_verif;
        public $dte_enr_son;
        public $is_free;

        // Db connection
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // GET ALL
        public function getNewSingle()
        {
            $sqlQuery = "SELECT DISTINCT id_son, reference_son, titre_son, fichier_son, cover_son, prix_son, duree_son, url_son, dateSortie_son, visible_son, lien_son, id_album, id_artiste, id_genre, is_active, date_verif, dte_enr_son FROM  " . $this->db_table . " GROUP BY id_artiste ORDER BY id_son DESC LIMIT 7 "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }


         // GET ALL
         public function getSingleById($id_son)
         {
             $sqlQuery = "SELECT DISTINCT * FROM  " . $this->db_table . " WHERE `id_son`=$id_son "  ;
             $stmt = $this->conn->prepare($sqlQuery);
             $stmt->execute();
             return $stmt;
         }

       

        // GET NB DOWNLOAD
        public function getNbDownloadSingle($libelle_transaction)
        {
            $sqlQuery = "SELECT COUNT(`libelle_transaction`) AS downloads FROM `transaction` WHERE `libelle_transaction`=$libelle_transaction "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }


        // GET POPULAR SON
        public function getNbDownloadSingleBetweenPeriode($libelle_transaction)
        {
            $sqlQuery = " SELECT COUNT(`libelle_transaction`) AS downloads FROM `transaction` WHERE `date_transaction` BETWEEN ADDDATE(LAST_DAY(DATE_SUB(NOW(),INTERVAL 2 MONTH)), INTERVAL 1 DAY) AND DATE_SUB(NOW(),INTERVAL 1 MONTH) AND `libelle_transaction`= $libelle_transaction   ORDER BY id_transaction DESC LIMIT 10 "  ;
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


        // GET ALBUM  OF SINGLE
        public function getAlbumSingle($id_album)
        {
            $sqlQuery = "SELECT *  FROM `album` WHERE `id_album`=$id_album"  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }


        // GET ALBUM  OF SINGLE
        public function getGenreSingle($id_genre)
        {
            $sqlQuery = "SELECT *  FROM `genre` WHERE `id_genre`=$id_genre"  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }



        // GET ALL
        public function getSingleForAlbum(){
            $sqlQuery = "SELECT DISTINCT id_son, reference_son, titre_son, fichier_son, cover_son, prix_son, duree_son, url_son, dateSortie_son, visible_son, lien_son, id_album, id_artiste, id_genre, is_active, date_verif, dte_enr_son FROM  " . $this->db_table . " GROUP BY id_album ORDER BY id_son DESC LIMIT 7 "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }


              // GET ALL
              public function getMostPopularSingle(){
                $sqlQuery = "SELECT DISTINCT id_son, reference_son, titre_son, fichier_son, cover_son, prix_son, duree_son, url_son, dateSortie_son, visible_son, lien_son, id_album, id_artiste, id_genre, is_active, date_verif, dte_enr_son FROM  " . $this->db_table . " GROUP BY id_artiste "  ;
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->execute();
                return $stmt;
            }

               // GET ALL
        public function getMostPopularSingleForAlbum(){
            $sqlQuery = "SELECT DISTINCT id_son, reference_son, titre_son, fichier_son, cover_son, prix_son, duree_son, url_son, dateSortie_son, visible_son, lien_son, id_album, id_artiste, id_genre, is_active, date_verif, dte_enr_son FROM  " . $this->db_table . " GROUP BY id_album "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

          // GET ALL
          public function getHotSingle(){
            $sqlQuery = "SELECT DISTINCT id_son, reference_son, titre_son, fichier_son, cover_son, prix_son, duree_son, url_son, dateSortie_son, visible_son, lien_son, id_album, id_artiste, id_genre, is_active, date_verif, dte_enr_son FROM  " . $this->db_table . " GROUP BY id_artiste ORDER BY RAND() LIMIT 20 "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

          // GET ALL
          public function getHotSingleForAlbum(){
            $sqlQuery = "SELECT DISTINCT id_son, reference_son, titre_son, fichier_son, cover_son, prix_son, duree_son, url_son, dateSortie_son, visible_son, lien_son, id_album, id_artiste, id_genre, is_active, date_verif, dte_enr_son FROM  " . $this->db_table . " GROUP BY id_album ORDER BY RAND() LIMIT 20 "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

          // GET ALL
          public function getFreeSingle(){
            $sqlQuery = "SELECT DISTINCT id_son, reference_son, titre_son, fichier_son, cover_son, prix_son, duree_son, url_son, dateSortie_son, visible_son, lien_son, id_album, id_artiste, id_genre, is_active, date_verif, dte_enr_son  FROM  ". $this->db_table . " WHERE  prix_son = 0 GROUP BY id_artiste ORDER BY RAND() LIMIT 30 "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
    }
