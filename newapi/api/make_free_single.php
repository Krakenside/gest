<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../class/single.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new Single($db);
    
    $data = json_decode(file_get_contents("php://input"));

    echo(log($item->makeFreeSingle()));
    $item->id_son = $data->id_son;
    
    // singe values
    $item->is_free = $data->is_free;
    
    if($item->makeFreeSingle()){
        echo json_encode("Single makes free successfully.");
    } else{
        echo json_encode("Single cannot make free");
    }
?>