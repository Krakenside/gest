<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/single.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Single($db);
    $stmt = $items->getFreeSingle();
    $itemCount = $stmt->rowCount();

    if($itemCount > 0){

        $singleArr = array();
        $singleArr["body"] = array();
        $singleArr["itemCount"] = $itemCount;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $nbdownload = $items->getNbDownloadSingle('son'+$titre_son+'-'+$id_son)->fetch(PDO::FETCH_ASSOC);
            $artiste = $items->getArtisteSingle($id_artiste)->fetch(PDO::FETCH_ASSOC);
            $album = $items->getAlbumSingle($id_album)->fetch(PDO::FETCH_ASSOC);
            $genre = $items->getGenreSingle($id_genre)->fetch(PDO::FETCH_ASSOC);
            $e = array(
                "id" => $id_son,
                "reference" => $reference_son,
                "name" => $titre_son,
                "artist" => $artiste['nom_artiste'],
                "album" => $album['titre_album'],
                "genre" => $genre['titre_genre'],
                "fichier" => $fichier_son,
                "cover_url" => $cover_son,
                "cover_art_url" => $artiste['cover_artiste'],
                "price" => $prix_son,
                "downloads" => $nbdownload['downloads'],
                "duree_son" => $duree_son,
                "url" => $url_son,
                "dateSortie" => $dateSortie_son,
                "visible" => $visible_son,
                "lien" => $lien_son,
                "id_album" => $id_album,
                "id_artiste" => $id_artiste,
                "id_genre" => $id_genre,
                "is_active" => $is_active,
                "date_verif" => $date_verif,
                "dte_enr" => $dte_enr_son,
                "lyrics" => "",
                "devise" => "FCFA",
            );
            array_push($singleArr["body"], $e);
        }
        echo json_encode($singleArr);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>
