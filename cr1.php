<?php
require 'connexion.php';
// recuperer les transactions du jour 
$msg = '';
$dt = date('Y-m-d');
// $dt = "2022-06-04";
$dt .= '%';

$dtaff = str_replace("%", " ", $dt);



//trends par pays 
// $nbrtrxGlobCi 
// $NbrAchSCi

// $reqSucglbci =  'SELECT DISTINCT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" 
// AND  transaction.date_transaction LIKE "' . $dt . '" 
// AND transaction.id_pays = 3 ';

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
// var_dump( $dt);
$req1  = 'SELECT DISTINCT count(transaction.id_transaction) FROM transaction WHERE transaction.statut_transaction = "SUCCESS" 
                    AND  transaction.date_transaction like "' . $dt . '"';

$rqst1 = $bdd->prepare($req1);
$rqst1->execute();
$res1 = $rqst1->fetch();
$msg .= $res1["count(transaction.id_transaction)"];


// echo 'SUCESS ';
// var_dump($res1);



$req2  = 'SELECT count(transaction.id_transaction) FROM transaction WHERE transaction.statut_transaction = "ATTENTE" 
        AND  transaction.date_transaction like "' . $dt . '"';


$rqst2 = $bdd->prepare($req2);
$rqst2->execute();
$res2 = $rqst2->fetch();
$msg .= $res2["count(transaction.id_transaction)"];
// echo 'ATTENTE ';
// var_dump($res2);

//

$req3  = 'SELECT count(transaction.id_transaction) FROM transaction WHERE transaction.statut_transaction = "FAIL" 
                    AND  transaction.date_transaction like "' . $dt . '"';

$rqst3 = $bdd->prepare($req3);
$rqst3->execute();
$res3 = $rqst3->fetch();
// echo 'FAIl ';
// var_dump($res3);


/// envoi des données par mail 
$msg .= $res3["count(transaction.id_transaction)"];

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg, 70);

// var_dump($msg);
// send email
// mail("yanneric225@gmail.com","Res",$msg);


// trier les transactions réussies par type 
// albums en CFA
$mt = 0;
$reqSucCFA = 'SELECT transaction.montant_transaction,pays.nom_pays
FROM transaction
INNER JOIN pays ON pays.id_pays = transaction.id_pays
WHERE transaction.statut_transaction = "SUCCESS" AND transaction.id_pays != 4  AND date_transaction LIKE "' . $dt . '"  AND(transaction.libelle_transaction LIKE "album-%" )';
$st1 = $bdd->prepare($reqSucCFA);
$st1->execute();
// echo '<br> Albums en cfa <br>';
foreach ($st1->fetchAll() as $elmt) {

    $mt +=  intval($elmt['montant_transaction']);
    // var_dump($elmt['montant_transaction']);
    // var_dump($elmt);
};

// var_dump($st1->fetchAll());
// var_dump($st1->rowCount());
$nbrAlbGnf = $st1->rowCount();
// echo '<br> montant  CFA <br>';
// var_dump($mt);

//montant ventes album en GNF 
$mtAlbGnf = 0;
$reqSucGnf = 'SELECT transaction.montant_transaction,pays.nom_pays
FROM transaction
INNER JOIN pays ON pays.id_pays = transaction.id_pays
WHERE transaction.statut_transaction = "SUCCESS" AND transaction.id_pays = 4  AND date_transaction LIKE "' . $dt . '"  AND(transaction.libelle_transaction LIKE "album-%" )';
$st2 = $bdd->prepare($reqSucGnf);
$st2->execute();
foreach ($st2->fetchAll() as $elmt2) {
    $mtAlbGnf += $elmt2['montant_transaction'];
    // var_dump($elmt2['montant_transaction']);
}
// echo '<br> Albums EN GNF <br>';
// var_dump($st2->fetchAll());
// var_dump($st2->rowCount());
$nbrAlbCfa = $st2->rowCount();
// echo '<br> Montant albums GNF ';
// echo $mtAlbGnf . '<br>';


$nbrVtAlbG = $nbrAlbCfa + $nbrAlbGnf;


//montant singles en CFA
$mtsCFA = 0;
$reqSucSonCFA = 'SELECT transaction.montant_transaction,pays.nom_pays
FROM transaction
INNER JOIN pays ON pays.id_pays = transaction.id_pays
WHERE transaction.statut_transaction = "SUCCESS" AND transaction.id_pays != 4  AND date_transaction LIKE "' . $dt . '"  AND(transaction.libelle_transaction LIKE "son-%" )';
$st3 = $bdd->prepare($reqSucSonCFA);
$st3->execute();
// echo '<br> sons EN cfa <br>';
// var_dump($reqSucSonCFA);
$i = 0;
foreach ($st3->fetchAll() as $elmt) {
    // echo $i.' ';
    $mtsCFA +=  intval($elmt['montant_transaction']);
    // var_dump($elmt['montant_transaction']);
    // echo ' ';
    // $i++;
};

// var_dump($st3->fetchAll());
// var_dump($st3->rowCount());
// echo '<br> montant sons CFA <br>';
// var_dump($mtsCFA);
$nbrSonCfa = $st3->rowCount();

//montant singles en GNF
$mtsGnf = 0;
$reqSucSonGnf = 'SELECT transaction.montant_transaction,pays.nom_pays
FROM transaction
INNER JOIN pays ON pays.id_pays = transaction.id_pays
WHERE transaction.statut_transaction = "SUCCESS" AND transaction.id_pays = 4  AND date_transaction LIKE "' . $dt . '"  AND(transaction.libelle_transaction LIKE "son-%" )';
$st4 = $bdd->prepare($reqSucSonGnf);
$st4->execute();
// echo '<br> sons EN GNF <br>';
foreach ($st4->fetchAll() as $elmt) {

    $mtsGnf +=  intval($elmt['montant_transaction']);
};

// var_dump($st4->fetchAll());
// var_dump($st4->rowCount());
// echo '<br> montant sons GNF <br>';
// var_dump($mtsGnf);
$nbSonGnf = $st4->rowCount();
$nbsonglbCfa = $nbSonGnf + $nbrSonCfa;


// dons du jour en CFA
$mtdoCFA = 0;
$reqSucDonCFA = 'SELECT transaction.montant_transaction,pays.nom_pays
FROM transaction
INNER JOIN pays ON pays.id_pays = transaction.id_pays
WHERE transaction.statut_transaction = "SUCCESS" AND transaction.id_pays != 4  AND date_transaction LIKE "' . $dt . '"  AND(transaction.libelle_transaction LIKE "don-%" )';
$st5 = $bdd->prepare($reqSucDonCFA);
$st5->execute();
// echo '<br> sons EN cfa <br>';
// var_dump($reqSucDonCFA);
foreach ($st5->fetchAll() as $elmt) {

    $mtdoCFA +=  intval($elmt['montant_transaction']);
    // var_dump($elmt);
};

// var_dump($st5->fetchAll());
// var_dump($st5->rowCount());
$nbrDonsCfa = $st5->rowCount();
// echo '<br> montant dons CFA <br>';
// var_dump($mtdoCFA);


//dons du jour en GNF
$mtdoGnf = 0;
$reqSucDonGNF = 'SELECT transaction.montant_transaction,pays.nom_pays
FROM transaction
INNER JOIN pays ON pays.id_pays = transaction.id_pays
WHERE transaction.statut_transaction = "SUCCESS" AND transaction.id_pays = 4  AND date_transaction LIKE "' . $dt . '"  AND(transaction.libelle_transaction LIKE "don-%" )';
$st6 = $bdd->prepare($reqSucDonGNF);
$st6->execute();
// echo '<br> Dons en GNF <br>';
// var_dump($reqSucDonCFA);
foreach ($st6->fetchAll() as $elmt) {

    $mtdoGnf +=  intval($elmt['montant_transaction']);
    // var_dump($elmt);
};

// var_dump($st6->fetchAll());
// var_dump($st6->rowCount());
// echo '<br> montant Dons GNF <br>';
// var_dump($mtdoGnf);
$nbrDonsGnf =  $st6->rowCount();
$nbrDonsGlobal = $nbrDonsCfa + $nbrDonsGnf;

$mtGnfGlob = $mtdoGnf + $mtsGnf + $mtAlbGnf;
$mtCfaGlob = $mtdoCFA + $mtsCFA + $mt;
$msg = '';
// $msg .= '<a href="https://www.afreekaplay.com/gest/cr1.php">Version complete</a>';


$msg .= "
<link rel=canonical href=https://colorlib.com/etc/tb/Table_Responsive_v1/index.html>

<style>
   
</style>
</head>

<body>
    <center>    
<div class=limiter>
        <div class=container-table100>
            <div class=wrap-table100>
            <h2> Statistiques generales </h2>
                <div class=table100>
                    <table>
                        <thead>
                            <tr class=table100-head>
                                <th class=column1>Date</th>
                                <th class=column2>Type transaction</th>
                                <th class=column3>Quantité</th>
                                <th class=column4>Montant Gnf</th>
                                <th class=column5>Montant XOF</th>
                                <th class=column6>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class='column1'>  $dtaff</td>
                                <td class='column2'>Trx Réussies</td>
                                <td class='column3'>" . $res1['count(transaction.id_transaction)'] . "</td>
                                <td class='column4'>  $mtGnfGlob </td>
                                <td class='column5'>  $mtCfaGlob </td>
                               
                            </tr>
                            <tr>
                                <td class='column1'>   $dtaff </td>
                                <td class='column2'>Trx en ATTENTE</td>
                                <td class='column3'>" .  $res3['count(transaction.id_transaction)'] . "</td>
                                <td class='column4'>N/D</td>
                                <td class='column5'>N/D</td>
                                
                            </tr>
                            <tr>
                                <td class='column1'>  $dtaff </td>
                                <td class='column2'>Trx en ECHEC</td>
                                <td class='column3'>  " . $res2['count(transaction.id_transaction)'] . "</td>
                                <td class='column4'>N/D</td>
                                <td class='column5'>N/D</td>
                                
                            </tr>
                            <tr>
                                <td class='column1'>  $dtaff </td>
                                <td class='column2'>Achat de sons </td>
                                <td class='column3'> $nbsonglbCfa </td>
                                <td class='column4'> $mtsGnf</td>
                                <td class='column5'> $mtsCFA </td>
                                
                            </tr>
                            <tr>
                                <td class='column1'>$dtaff</td>
                                <td class='column2'>Achat Album</td>
                                <td class='column3'> $nbrVtAlbG </td>
                                <td class='column4'> $mtAlbGnf </td>
                                <td class='column5'> $mt </td>
                                
                            </tr>
                            <tr>
                                <td class='column1'>   $dtaff </td>
                                <td class='column2'>Dons</td>
                                <td class='column3'> $nbrDonsGlobal </td>
                                <td class='column4'> $nbrDonsGnf </td>
                                <td class='column5'> $nbrDonsCfa </td>
                                
                            </tr>


                        </tbody>
                    </table>
                </div>
                </center>    
                
                ";


$msg .= " </div>
</div> ";
$msg .= "<h2>Stats par pays </h2> ";
foreach ($arrstp1 as $cle => $elmt) {
    $msg .= "
    <ul>

<li>" . $dtaff . " </li>
<li> <h3>" .  $cle . "</h3></li>
<li> <h4> Nombre de ventes sons :" . $elmt["vt_sons"] . " </h4></li>
<li> <h4> Nombre de ventes Album :" . $elmt["vt_alb"] . "  </h4></li>
<li> <h4> Nombre de dons :" . $elmt["nbr_dons"] . "</h4> </li>
<li><h4> Montant généré : " . $elmt["mtn"] . " " . $elmt["dev"] . " </h4></li></tr>
<br>


</ul>
</div>
    ";
}





// $to = 'yanneric225@gmail.com';
// $to2 = 'thimak@gmail.com';
// $to3 = 'thimak@vasconex.com';

// $subject = 'Recap ';

// $headers = "From: Afreekaplay\r\n";
// $headers .= "Reply-To: contact@Afreekaplay.com\r\n";
// $headers .= "CC: thimak@vasconex.com\r\n";
// $headers .= "MIME-Version: 1.0\r\n";
// $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
// mail($to, $subject, $msg, $headers);
// // mail($to2, $subject, $msg, $headers);
// // mail($to3, $subject, $msg, $headers);
// // mail("","Res",$msg);
echo $msg;

$msg .= '<a href="https://www.afreekaplay.com/gest/cr1.php">Version complete</a>';
