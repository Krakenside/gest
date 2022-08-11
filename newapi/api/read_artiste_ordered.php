<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/artiste.php';

    $database = new Database();
    $db = $database->getConnection();

    $items = new Artiste($db);

    $stmt = $items->getOrderedArtiste();
    $itemCount = $stmt->rowCount();


    if($itemCount > 0){

        $singleArr = array();
        $singleArr["body"] = array();
        $singleArr["itemCount"] = $itemCount;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $e = array(
                "id" => $id_artiste,
                "name" => $nom_artiste,
                "id_maison" => $id_maison,
                "lien" => $lien_artiste,
                "pourcentage" => $pourcentage_artiste,
                "natio" => $nationalite_artiste,
                "cover_url" => $cover_artiste,
                "is_active" => $is_active,
                "date_verif" => $date_verif,
                "dte_enr" => $dte_enr,
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
