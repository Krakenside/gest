<?php
require 'connexion.php';
// ce fichier servira a determiner les transactions de moov afreekaplay togo 

// initialisation de la date 
$dt = "2021-01-01";
$dt2 = "2022-12-01";
// print_r($dt);
$dt .= '%';
$dt2 .= '%';
// $dtt ="2019-08-14";
// $minus = date("Y-m-d", strtotime($dtt . "-1 week"));
// var_dump($minus);
# determinons les transactions du jour pour moov afp 
#global 
$reqMvt = "SELECT * FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND integrateur_transaction.id_integrateur = 6
AND transaction.date_transaction BETWEEN  :dt  AND :dt2
 ";
$st1 = $bdd->prepare($reqMvt);

$st1->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);
$nbr_trx  = $st1->rowCount();
// echo 'Nombre total de transactions pour Moov TOGO : ' . $nbr_trx . '<br>';
// foreach ($st1->fetchAll() as $elmt1) {
//     var_dump("Statut transactions", $elmt1["statut_transaction"]);
//     var_dump("Montant Transac ", $elmt1["montant_transaction"]);
//     echo ("<br>");
// }
# detrminons les transactions par credit AIRTG

#success 
$nbr_trx_su_airtg = 0;
$reqSuccesAirTg = "SELECT * FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND transaction.statut_transaction = 'SUCCESS'
AND integrateur_transaction.id_integrateur = 6
AND integrateur_transaction.detail_integrateur_transaction = 'AIRTG'
AND transaction.date_transaction BETWEEN  :dt  AND :dt2
 ";

$st2 = $bdd->prepare($reqSuccesAirTg);
$st2->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);
$res1 = $st2->fetchAll();
$nbr_trx_su_airtg = $st2->rowCount();

$nbr_trx_fl_airtg = 0;
$reqFailAirTg = "SELECT * FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND transaction.statut_transaction = 'FAIL'
AND integrateur_transaction.id_integrateur = 6
AND integrateur_transaction.detail_integrateur_transaction = 'AIRTG'
AND transaction.date_transaction BETWEEN  :dt  AND :dt2
 ";

$st3 = $bdd->prepare($reqFailAirTg);
$st3->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);
$res2 = $st3->fetchAll();
$nbr_trx_fl_airtg = $st3->rowCount();


$nbr_trx_pend_airtg = 0;
$reqPendAirTg = "SELECT * FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND transaction.statut_transaction = 'ATTENTE'
AND integrateur_transaction.id_integrateur = 6
AND integrateur_transaction.detail_integrateur_transaction = 'AIRTG'
AND transaction.date_transaction BETWEEN  :dt  AND :dt2
 ";

$st4 = $bdd->prepare($reqPendAirTg);
$st4->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);
$res3 = $st4->fetchAll();
$nbr_trx_pend_airtg = $st4->rowCount();
echo '<br> <h2> Achat via Credit de communication  </h2> <br>';
echo ("Success  :" . $nbr_trx_su_airtg . "<br>");
echo ("Fail  : " . $nbr_trx_fl_airtg . "<br>");
echo ("Pending  : " . $nbr_trx_pend_airtg . "<br>");
// var_dump("Success AIRTG : ",$nbr_trx_su_airtg,"<br>" );
// var_dump("Success AIRTG : ",$nbr_trx_su_airtg,"<br>" );

#determinons les utilisateurs de facon distincte
$reqDistinctUser = "SELECT DISTINCT transaction.nom_transaction FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND integrateur_transaction.id_integrateur = 6
AND integrateur_transaction.detail_integrateur_transaction = 'AIRTG'
AND transaction.date_transaction BETWEEN  :dt  AND :dt2 ";

$st5 = $bdd->prepare($reqDistinctUser);
$st5->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);

$nbUser  = $st5->rowCount();

echo ("Nombre d'utilisateurs distincs  : " . $nbUser . "<br>");


//determinons les stats pour les achats via flooz 
$nbr_trx_su_fl = 0;
$reqSuccesFlTg = "SELECT * FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND transaction.statut_transaction = 'SUCCESS'
AND integrateur_transaction.id_integrateur = 6
AND integrateur_transaction.detail_integrateur_transaction = 'FLOOZTG'
AND transaction.date_transaction BETWEEN  :dt  AND :dt2
 ";

$st6 = $bdd->prepare($reqSuccesFlTg);
$st6->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);
$res4 = $st6->fetchAll();
$nbr_trx_su_fl = $st6->rowCount();


$nbr_trx_fl_Flz_tg = 0;
$reqFail_Flz_Tg = "SELECT * FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND transaction.statut_transaction = 'FAIL'
AND integrateur_transaction.id_integrateur = 6
AND integrateur_transaction.detail_integrateur_transaction = 'FLOOZTG'
AND transaction.date_transaction BETWEEN  :dt  AND :dt2
 ";

$st7 = $bdd->prepare($reqFail_Flz_Tg);
$st7->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);
$res4 = $st7->fetchAll();
$nbr_trx_fl_Flz_Tg = $st7->rowCount();



$nbr_trx_pend_Flz = 0;
$reqPend_Flz_Tg = "SELECT * FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND transaction.statut_transaction = 'ATTENTE'
AND integrateur_transaction.id_integrateur = 6
AND integrateur_transaction.detail_integrateur_transaction = 'FLOOZTG'
AND transaction.date_transaction BETWEEN  :dt  AND :dt2
 ";

$st8 = $bdd->prepare($reqPend_Flz_Tg);
$st8->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);
$res5 = $st8->fetchAll();
$nbr_trx_pend_Flz_Tg = $st8->rowCount();

echo '<br> <h2> Stats achats via  Flooz TOGO  </h2><br>';
echo ("Success  :" . $nbr_trx_su_fl . "<br>");
echo ("Fail   : " . $nbr_trx_fl_Flz_Tg . "<br>");
echo ("Pending   : " . $nbr_trx_pend_Flz_Tg . "<br>");

$reqDistinctUser_Flz = "SELECT DISTINCT transaction.nom_transaction FROM transaction 
INNER JOIN integrateur_transaction 
ON transaction.id_transaction = integrateur_transaction.id_transaction 
AND integrateur_transaction.id_integrateur = 6
AND integrateur_transaction.detail_integrateur_transaction = 'FLOOZTG'
AND transaction.date_transaction BETWEEN  :dt  AND :dt2 ";

$st9 = $bdd->prepare($reqDistinctUser_Flz);
$st9->execute(
    array(
        'dt' => $dt,
        'dt2' => $dt2
    )
);

$nbUser_Flz  = $st9->rowCount();

echo ("Nombre d'utilisateurs distincs  : " . $nbUser_Flz . "<br>");
