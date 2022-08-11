<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/album.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Album($db);

    $stmt = $items->getLastAlbum();
    $itemCount = $stmt->rowCount();


    if($itemCount > 0){

        $albumArr = array();
        $albumArr["body"] = array();
        $albumArr["itemCount"] = $itemCount;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $nbdownload = $items->getNbDownloadSingle('album'+$album['titre_album']+'-'+$id_album)->fetch(PDO::FETCH_ASSOC);
            $artiste = $items->getArtisteSingle($id_artiste)->fetch(PDO::FETCH_ASSOC);
            $e = array(
                "id" => $id_album,
                "reference" => $reference_album,
                "name" => $titre_album,
                "artist" => $artiste['nom_artiste'],
                "genre" => $genre['titre_genre'],
                "cover_art_url" => $artiste['cover_artiste'],
                "downloads" => $nbdownload['downloads'],
                "devise" => "FCFA",
                "fichier" => $fichier_album,
                "cover_art_url" => $cover_album,
                "cover_url" => $cover_album,
                "price" => $prix_album,
                "visible" => $visible_album,
                "dateSortie" => $dateSortie_album,
                "url" => $url_album,
                "lien" => $lien_album,
                "lien_album" => $lien_album,
                "id_artiste" => $id_artiste,
                "genre" => $id_genre,
                "devise" => "FCFA",
                "is_active" => $is_active,
                "date_verif" => $date_verif,
                "dte_enr" => $dte_enr,
            );
            array_push($albumArr["body"], $e);
        }
        echo json_encode($albumArr);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>
