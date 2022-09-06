<?php






// $table = $_GET['table'];



require 'connexion.php';



// $_SESSION["nat_Id"] = $_GET['dest2'];
// $dt1 = $_GET['dt'];
// $dt2 = $_GET['dt2'];
// $dtf1 = $dt1."%";
// $dtf2 = $dt2."%";
// echo $test;
$tabinfo[][] = 0;
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
$nbr_vt_sons_art = 0;
$nbr_vt_alb = 0;
$mtn_vt_art_cfa = 0;
$mtn_vt_art_GNF = 0;
$mtn_art_Alb_CFA  = 0;
$mtn_art_Alb_GNF  = 0;
$mtn_dons_cfa = 0;
$mnt_dons_GNF = 0;
$mtn_dons_alb_cfa = 0;
$mtn_dons_alb_gnf = 0;
$mtn_dons_total = 0;
$nbr_dons = 0;

foreach ($res1 as $key => $value) {
    #pour chaque artiste determinons les sons 
    var_dump($value["nom_artiste"]);
    echo '<br>';

    $reqsong = "SELECT * FROM son WHERE son.id_artiste = :id_artiste";
    $st1 = $bdd->prepare($reqsong);
    $st1->execute(array('id_artiste' => $value["id_artiste"]));
    $res2 = $st1->fetchAll();
    foreach ($res2 as $cle2 => $elmt) {
        // var_dump($elmt["titre_son"]);
        // var_dump($elmt["id_son"]);
        // $tabinfo[$i][$value["id_artiste"]][1] = 

        #determinons les transactions pour ce son 
        $reqtrsons = 'SELECT *
        FROM TRANSACTION
        INNER JOIN pays ON pays.id_pays = transaction.id_pays
        WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2021-01-01%" AND "2022-12-31%" AND (transaction.libelle_transaction LIKE "son-%" AND transaction.libelle_transaction LIKE "%-' . $elmt["id_son"] . '") ';
        $st2 = $bdd->query($reqtrsons);
        $nbr_vt_sons_art += $st2->rowCount();
        foreach ($st2->fetchAll() as $elmt4) {

            if ($elmt4["id_pays"] == 4) {
                // echo  'Montant GN : ';
                // var_dump($elmt3["montant_transaction"]);
                $mtn_vt_art_GNF += $elmt4["montant_transaction"];
            } else {
                // echo 'Montant cfa: ';
                // var_dump($elmt3["montant_transaction"]);
                $mtn_vt_art_cfa += $elmt4["montant_transaction"];
            }
        }
        //determinons les dons pour ce son

        $reqDons = 'SELECT *
        FROM TRANSACTION
        INNER JOIN pays ON pays.id_pays = transaction.id_pays
        WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2021-01-01%" AND "2022-12-31%" AND (transaction.libelle_transaction LIKE "son-' . $elmt["titre_son"] . '%") AND transaction.libelle_transaction LIKE "%-don" ';
        $st3 = $bdd->query($reqDons);
        // var_dump($reqDons);
        // echo 'Nombre de transactions pour le son : ' . $elmt["titre_son"] . ' ';
        // var_dump($st3->rowCount());


        $nbr_dons += $st3->rowCount();
        foreach ($st3->fetchAll() as $elmt3) {

            if ($elmt3["id_pays"] == 4) {
                // echo  'Montant GN : ';
                // var_dump($elmt3["montant_transaction"]);
                $mnt_dons_GNF += $elmt3["montant_transaction"];
            } else {
                // echo 'Montant cfa: ';
                // var_dump($elmt3["montant_transaction"]);
                $mtn_dons_cfa += $elmt3["montant_transaction"];
            }
        }
    }
    //insertion des permieres données dans la bd 
    // $reqIns_1 = " INSERT INTO banque(Id_artiste_banque,Nom_artiste_banque, " ; 
    echo '<br>';

    echo   $nbr_vt_sons_art . '<br>';
    echo ("Montant Dons sons  CFA " . $mtn_vt_art_cfa . "<br>");
    echo ("Montant Dons sons GNF " . $mtn_vt_art_GNF . "<br>");
    echo ("Montant  sons  CFA " . $mtn_dons_cfa . "<br>");
    echo ("Montant  sons GNF " . $mnt_dons_GNF . "<br>");
    $mtn_vt_art_cfa = 0;
    $mtn_vt_art_GNF = 0;
    $nbr_vt_sons_art = 0;
    $mtn_dons_cfa = 0;
    $mnt_dons_GNF = 0;


    #determinons les transactions pour chaque album

    $reqAlb = "SELECT * FROM album where album.id_artiste = :id_art";
    $st4 =  $bdd->prepare($reqAlb);
    $st4->execute(
        array(
            'id_art' => $value["id_artiste"]
        )
    );

    foreach ($st4->fetchAll() as $cle2 => $elmt2) {
        // var_dump($elmt["titre_son"]);
        // var_dump($elmt["id_son"]);
        // $tabinfo[$i][$value["id_artiste"]][1] = 

        #determinons les transactions pour ce album
        $reqtrAlb = 'SELECT * FROM transaction 
        INNER JOIN pays on pays.id_pays = transaction.id_pays 
        WHERE transaction.statut_transaction = "SUCCESS"   
        AND date_transaction BETWEEN "2021-01-01%" AND "2022-12-31%"
         AND  (transaction.libelle_transaction LIKE "album-%"  AND transaction.libelle_transaction LIKE "%-' . $elmt2["id_album"] . '") ';
        $st6 = $bdd->query($reqtrAlb);
        // echo 'Nombre de transactions pour l"album : ' . $elmt2["id_album"] . ' ';
        // echo ($elmt2["prix_album"]);
        // var_dump($st6->rowCount());
        $nbr_vt_alb += $st6->rowCount();
        foreach ($st6->fetchAll() as $elmt3) {

            if ($elmt3["id_pays"] == 4) {
                // echo  'Montant GN : ';
                // var_dump($elmt3["montant_transaction"].'<br>');
                $mtn_art_Alb_GNF += $elmt3["montant_transaction"];
            } else {
                // echo 'Montant cfa: ';
                // var_dump($elmt3["montant_transaction"].'<br>');
                $mtn_art_Alb_CFA += $elmt3["montant_transaction"];
            }
        }
        // var_dump($st6->rowCount());


        $reqtr_don_Alb = 'SELECT *
        FROM TRANSACTION
        INNER JOIN pays ON pays.id_pays = transaction.id_pays
        WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2021-01-01%" AND "2022-12-31%" AND (transaction.libelle_transaction LIKE "album-' . $elmt2["titre_album"] . '%") AND transaction.libelle_transaction LIKE "%-don"  ';
        $st8 = $bdd->query($reqtr_don_Alb);
        // var_dump($reqtr_don_Alb);
        // echo 'Nombre de transactions pour l"album : ' . $elmt2["id_album"] . ' ';
        // echo ($elmt2["prix_album"]);
        // var_dump($st6->rowCount());
        $nbr_vt_alb += $st8->rowCount();
        foreach ($st8->fetchAll() as $elmtd) {

            if ($elmtd["id_pays"] == 4) {
                // echo  'Montant GN : ';
                // var_dump($elmt3["montant_transaction"].'<br>');
                $mtn_dons_alb_gnf += $elmtd["montant_transaction"];
            } else {
                // echo 'Montant cfa: ';
                // var_dump($elmt3["montant_transaction"].'<br>');
                $mtn_dons_alb_cfa += $elmtd["montant_transaction"];
            }
        }
    }
    echo '<br>';
    $i++;
    echo   $nbr_vt_alb . '<br>';
    echo ("Montant ventes Albums CFA " . $mtn_art_Alb_CFA . "<br>");
    echo ("Montant ventes Albums GNF " . $mtn_art_Alb_GNF . "<br>");
    echo ("Montant Dons  Albums CFA " . $mtn_dons_alb_cfa . "<br>");
    echo ("Montant Dons Albums GNF " . $mtn_dons_alb_gnf . "<br>");
    // les valeurs sont correctes inserons les  dans la bd 

    // $reqIns = "INSERT INTO banque(Id_artiste_banque,Nom_artiste_banque,Montant_genere_banque,Montant_alb_cfa_banque,Montant_alb_gnf_banque,     Montant_sons_cfa_banque,Montant_sons_gnf_banque,Montant_dons_cfa_banque,Montant_dons_gnf_banque) 
    //     VALUES(:Id_artiste_banque,:Nom_artiste_banque,:Montant_genere_banque,:Montant_alb_cfa_banque,:Montant_alb_gnf_banque,   :Montant_sons_cfa_banque,:Montant_sons_gnf_banque,:Montant_dons_cfa_banque,:Montant_dons_gnf_banque) ";

    // $stIns = $bdd->prepare($reqIns);

    // $stIns->execute(array(
    //     'Id_artiste_banque' => $value["id_artiste"],
    //     'Nom_artiste_banque' => $value["nom_artiste"],
    //     'Montant_alb_cfa_banque' => $mtn_art_Alb_CFA
    // ));
    $nbr_vt_alb = 0;
    $mtn_art_Alb_CFA = 0;
    $mtn_art_Alb_GNF = 0;
    $mtn_dons_total = 0;
    $nbr_dons = 0;
    $mtn_dons_alb_cfa = 0;
    $mtn_dons_alb_gnf = 0;
     

    if ($i == 2) die;
}





// echo json_encode($tabinfo, JSON_PRETTY_PRINT);