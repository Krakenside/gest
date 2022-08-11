<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/single.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Single($db);

    $stmt = $items->getNewSingle();
    $itemCount = $stmt->rowCount();


    if($itemCount > 0){

        $singleArr = array();
        $singleArr["body"] = array();
        $singleArr["itemCount"] = $itemCount;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $e = array(
                "id" => $id_son,
                "reference" => $reference_son,
                "titre_son" => $titre_son,
                "fichier_son" => $fichier_son,
                "cover_url" => $cover_son,
                "price" => $prix_son,
                "duree_son" => $duree_son,
                "url" => $url_son,
                "dateSortie" => $dateSortie_son,
                "visible_son" => $visible_son,
                "lien" => $lien_son,
                "id_album" => $id_album,
                "id_artiste" => $id_artiste,
                "genre" => $id_genre,
                "is_active" => $is_active,
                "date_verif" => $date_verif,
                "dte_enr_son" => $dte_enr_son,
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
