<?php






// $table = $_GET['table'];



require 'connexion.php';



// $_SESSION["nat_Id"] = $_GET['dest2'];
// $dt1 = $_GET['dt'];
// $dt2 = $_GET['dt2'];
// $dtf1 = $dt1."%";
// $dtf2 = $dt2."%";
// echo $test;
$tabinfo[][] = 0 ;
$mtnalbumgn = array();
$mtsngn = array();




// $reA = "SELECT * FROM artiste INNER JOIN pays

//                                                                     ON pays.code_pays = artiste.nationalite_artiste

//                                                                      WHERE pays.code_pays = '" . $_GET['dest2'] . "'";



$reA = "SELECT * FROM artiste ";





$artart = $bdd->query($reA);
// var_dump($artart->fetchAll());
// print_r($_GET['dest2']);

// determinons les sons pour cet artiste donné 
$res1 = $artart->fetchAll();
$nbr_art = $artart->rowCount();
$i = 0;
foreach ($res1 as $key => $value) {
    #pour chaque artiste dterminons les sons 
    var_dump($value["nom_artiste"]);
    echo '<br>';

    $reqsong = "SELECT * FROM son WHERE son.id_artiste = :id_artiste";
    $st1 = $bdd->prepare($reqsong);
    $st1->execute(array('id_artiste' => $value["id_artiste"]));
    $res2 = $st1->fetchAll();
    foreach ($res2 as $cle2 => $elmt) {
        // var_dump($elmt["titre_son"]);
        // var_dump($elmt["id_son"]);
        $tabinfo[$i][$value["id_artiste"]][1] = 

        #determinons les transactions pour ce son 
        $reqtrsons = 'SELECT * FROM transaction 
        INNER JOIN pays on pays.id_pays = transaction.id_pays 
        WHERE transaction.statut_transaction = "SUCCESS"   AND date_transaction BETWEEN "2021-01-01%" AND "2022-12-31%" AND  (transaction.libelle_transaction LIKE "son-%"  AND transaction.libelle_transaction LIKE "%-' . $elmt["id_son"] . '") ';
        $st3 = $bdd->query($reqtrsons);
        echo 'Nombre de transactions pour le son : ' . $elmt["titre_son"] . ' ';
        var_dump($st3->rowCount());
        foreach ($st3->fetchAll() as $elmt3) {

            if ($elmt3["id_pays"] == 4) {
                echo  'Montant GN : ';
                var_dump($elmt3["montant_transaction"]);
            } else {
                echo 'Montant cfa: ';
                var_dump($elmt3["montant_transaction"]);
            }
        }
        echo '<br>';
    }
    echo '<br>';
}





// echo json_encode($tabinfo, JSON_PRETTY_PRINT);
