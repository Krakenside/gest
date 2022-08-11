<?php
require 'connexion.php';
// recuperer les transactions du jour 
$msg = '';
// $dt = date('Y-m-d');
$dt = "2022-06-14";
$dt .= '%';
$NbrAchSCi = 0;

$dtaff = str_replace("%", " ", $dt);
// var_dump($dt);

//trends par pays 
// $nbrtrxGlobCi 
// $NbrAchSCi

// $reqSucglbci =  'SELECT DISTINCT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" 
// AND  transaction.date_transaction LIKE "' . $dt . '" 
// AND transaction.id_pays = 3 ';

$reqSucglbci =  'SELECT DISTINCT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" 
AND  transaction.date_transaction LIKE "' . $dt . '" 
AND transaction.id_pays = 4
AND transaction.libelle_transaction LIKE "son-%" ';

$st8 = $bdd->prepare($reqSucglbci);

$st8->execute();
// echo 'GN son ';
// var_dump($st8->rowCount());
// echo '<br>';
$st8->closeCursor();


// // requete dynamique 
//   dons réussis
$arrstp1 = array();
$reqDyn = 'SELECT DISTINCT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" 
AND  transaction.date_transaction LIKE "' . $dt . '" 
AND transaction.id_pays = :py
AND transaction.libelle_transaction LIKE "%-don" ';


$reqSucgl =  'SELECT DISTINCT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" 
AND  transaction.date_transaction LIKE "' . $dt . '" 
AND transaction.id_pays = :py
AND transaction.libelle_transaction LIKE "son-%" ';



$reqAlbn = 'SELECT DISTINCT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" 
  AND  transaction.date_transaction LIKE "' . $dt . '" 
AND transaction.id_pays =:py
 AND transaction.libelle_transaction LIKE "album-%" ';


// $reqpays = "select * from pays ";

$stp = $bdd->prepare("select * from pays ");
$stp->execute();
$i = 0;
foreach ($stp->fetchAll() as  $stpelt) {
    $mtgnf = 0;
    $my_cfa = 0;
    $arrstp1[$stpelt['nom_pays']]["mtn"] = 0;

    $reqSTp1 = $bdd->prepare($reqDyn);
    $reqSTp1->execute(array(
        'py' => $stpelt['id_pays']
    ));
    $reqSTp2 = $bdd->prepare($reqSucgl);
    $reqSTp2->execute(
        array(
            'py' => $stpelt['id_pays']
        )
    );
    $reqSTp3 = $bdd->prepare($reqAlbn);
    $reqSTp3->execute(
        array(
            'py' => $stpelt['id_pays']
        )
    );
    $res1 = $reqSTp2->fetchAll();
    // var_dump($res1);

    // $arrstp1[$stpelt['nom_pays']]["nbr_dons"] =  $reqSTp1->rowCount();

    foreach ($res1 as $elt2) {
        // var_dump($elt2["montant_transaction"]);
        // var_dump($elt2["id_pays"]);
        if ($elt2["id_pays"] == 4) {
            // echo 'GN' ;
            $mtgnf = intval($elt2["montant_transaction"]);
            // montant genéré pour ce pays
            $arrstp1[$stpelt['nom_pays']]["mtn"] +=  $mtgnf;
        } else {
            // echo 'autre pays <br>';
            $my_cfa += intval($elt2["montant_transaction"]);
            $arrstp1[$stpelt['nom_pays']]["mtn"] +=  $my_cfa;
        }
    }
    $arrstp1[$stpelt['nom_pays']]["vt_sons"]  = $reqSTp2->rowCount();
    $arrstp1[$stpelt['nom_pays']]["vt_alb"]  = $reqSTp3->rowCount();
    $arrstp1[$stpelt['nom_pays']]["nbr_dons"] =  $reqSTp1->rowCount();
    $arrstp1[$stpelt['nom_pays']]["dev"] = ($arrstp1[$stpelt['nom_pays']] == "Guinee") ? ("GNF") : ("CFA");
    // $arrstp1[$stpel['nom_pays']][1] =  $reqSTp1->rowCount();
    $i++;
}
// var_dump($arrstp1);
// echo json_encode($arrstp1,JSON_PRETTY_PRINT);


?>
<div class=table100>

    <ul>
        <?php
        foreach ($arrstp1 as $cle => $elmt) {
            // var_dump($elmt);
        ?>

            <tr>
            <?php
            echo ("<li>" . $dtaff . " </li>");
            echo ("<li>" .  $cle . "</li>");
            echo ("<li> <h4> Nombre de ventes sons " . $elmt["vt_sons"] . " </h4></li>");
            echo ("<li> <h4> Nombre de ventes Album " . $elmt["vt_alb"] . "  </h4></li>");
            echo ("<li> <h4> Nombre de dons " . $elmt["nbr_dons"] . "</h4> </li> ");
            echo ("<li><h4> Montant généré " . $elmt["mtn"] . " " . $elmt["dev"] . " </h4></li></tr>");

            echo '<br>';
        } ?>


            <li></li>
    </ul>
</div>