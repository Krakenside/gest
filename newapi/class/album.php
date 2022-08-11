<?php
    class Album{

        // Connection
        private $conn;

        // Table
        private $db_table = "album";

        // Columns
        public $id_album;
        public $reference_album;
        public $titre_album;
        public $fichier_album;
        public $cover_album;
        public $prix_album;
        public $visible_album;
        public $dateSortie_album;
        public $url_album;
        public $lien_album;
        public $id_artiste;
        public $id_genre;
        public $is_active;
        public $date_verif;
        public $dte_enr;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function getLastAlbum(){
            $sqlQuery = "SELECT id_album ,reference_album ,titre_album ,fichier_album ,cover_album, prix_album ,visible_album ,dateSortie_album ,url_album ,lien_album ,id_artiste ,id_genre, is_active ,date_verif, dte_enr  FROM  " . $this->db_table . " GROUP BY id_artiste ORDER BY id_album DESC LIMIT 6 "  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }


         // GET NB DOWNLOAD
         public function getNbDownloadSingle($libelle_transaction){
            $sqlQuery = "SELECT COUNT(`libelle_transaction`) AS downloads FROM `transaction` WHERE `libelle_transaction`=$libelle_transaction"  ;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

                // GET ARTISTE  OF SINGLE
                public function getArtisteSingle($id_artiste){
                    $sqlQuery = "SELECT *  FROM `artiste` WHERE `id_artiste`=$id_artiste"  ;
                    $stmt = $this->conn->prepare($sqlQuery);
                    $stmt->execute();
                    return $stmt;
                }


                 // GET ALBUM  OF SINGLE
                 public function getAlbumSingle($id_album){
                    $sqlQuery = "SELECT *  FROM `album` WHERE `id_album`=$id_album"  ;
                    $stmt = $this->conn->prepare($sqlQuery);
                    $stmt->execute();
                    return $stmt;
                }


                 // GET ALBUM  OF SINGLE
                 public function getGenreSingle($id_genre){
                    $sqlQuery = "SELECT *  FROM `genre` WHERE `id_genre`=$id_genre"  ;
                    $stmt = $this->conn->prepare($sqlQuery);
                    $stmt->execute();
                    return $stmt;
                }


      

    }
?>