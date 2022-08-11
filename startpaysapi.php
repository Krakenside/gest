<?php




// liste quantité des album et son de maniere global





// $table = $_GET['table'];



require 'connexion.php';



$_SESSION["nat_Id"] = $_GET['dest2'];
// $dt1 = $_GET['dt'];
// $dt2 = $_GET['dt2'];
// $dtf1 = $dt1."%";
// $dtf2 = $dt2."%";
// echo $test;

$mtnalbumgn = array();
$mtsngn = array();



$reA = "SELECT * FROM artiste INNER JOIN pays
    
                                                                    ON pays.code_pays = artiste.nationalite_artiste
    
                                                                     WHERE pays.code_pays = '" . $_GET['dest2'] . "'";



$artart = $bdd->query($reA);

// print_r($_GET['dest2']);



$nbT = [];

$affich_el = [];

$affich_el[0] = 1;

$indxArt = 1;

$indxGbl = 1;
$chifrAfglo = 0;



while ($art = $artart->fetch()) {

    $nbTrxa = 0;

    $nbTrxb = 0;

    $affich_el[$indxGbl][0] = $art['nom_artiste'];
    $mtsngn[$indxGbl][0] = 0;
    $mtsngn[$indxGbl][1] = 0;
    $mtnalbumgn[$indxGbl][0] = 0;
    $mtnalbumgn[$indxGbl][1] = 0;

    $re = "SELECT * FROM artiste INNER JOIN son
    
            ON son.id_artiste = artiste.id_artiste
    
            WHERE artiste.id_artiste = '" . $art['id_artiste'] . "'";

    $re2 = "SELECT * FROM artiste INNER JOIN album
    
                                                                        ON album.id_artiste = artiste.id_artiste
    
                                                                         WHERE artiste.id_artiste = '" . $art['id_artiste'] . "'";



    $arts = $bdd->query($re);

    $nbarts = $arts->rowCount();

    $arta = $bdd->query($re2);

    $nbarta = $arta->rowCount();

    $chx1 = "";

    $chx = "";




    $taux = 1;

    $rsltTx['signe_devise'] = 'FCFA';




    $i = 0;

    $u = 0;

    $idxPys = 1;



    $listSn = [];

    while ($artistsn = $arts->fetch()) {

        $chifrAf = 0;
        $caglbSon = 0;
        $nbTrx = 0;
        $chifrAfsgn = 0;
        $chifrAfAlsgn = 0;

        $listSn[$i][0] = $artistsn['reference_son'];
        $test41 = 0;

        // $chx = 'son-'.$artistsn['titre_son'].'-'.$artistsn['id_son'];



        $re3 = 'SELECT * FROM transaction 
        INNER JOIN pays on pays.id_pays = transaction.id_pays 
        WHERE transaction.statut_transaction = "SUCCESS"   AND date_transaction BETWEEN "2021-01-01%" AND "2021-12-31%" AND  (transaction.libelle_transaction LIKE "son-%"  AND transaction.libelle_transaction LIKE "%-' . $artistsn['id_son'] . '") ';



        // echo $re3;

        $un = $bdd->query($re3);

        // $un2 = $bdd -> query($re4);

        $nbTrs = $un->rowCount();

        $nbTrxa += $nbTrs;

        $nbTrx += $nbTrs;

        //print_r( $un->fetchAll());

        // $elt = $un->fetch();
        //var_dump($un->fetch());


        // while ($elt = $un->fetch()) {

        //     // var_dump($elt);

        //     // $mtsngn[$indxGbl][$art['id_artiste']][0] += intval($elt['montant_transaction']);


        //     // $chifrAf += intval($elt['montant_transaction']);
        //     $chifrAf += intval($elt[4]);
        //     $test41 = $elt[12];

        //     // $chifrAf += intval($elt['montant_transaction'])*$taux;

        //     // if ($elt['id_pays'] == 4) {
        //     //     //echo 'GN';
        //     //    //echo $elt['id_pays'];
        //     //     $mtsngn[$indxGbl][0] += intval($elt['montant_transaction']);
        //     //     // var_dump($elt['montant_transaction']);
        //     //     // var_dump($elt['id_transaction']);

        //     // }else{
        //     //     $chifrAf += intval($elt['montant_transaction']);
        //     // }

        //     // print_r($elt['montant_transaction']);
        //     //      print_r($chifrAf); 
        //     //  echo'<br>';

        //     // var_dump($chifrAf);



        // }
        foreach ($un->fetchAll() as $eltsn) {
            // $chifrAf += intval($eltsn['montant_transaction']);
            $test41 = $eltsn['nom_pays'];
            // echo $test41.' ';
            // echo $artistsn['id_son'].' ';
            // echo   intval($eltsn['montant_transaction']).' ';
            // echo $art['id_artiste'].'<br> '  ;
            if ($test41 == 'Guinee') {

                $mtsngn[$indxGbl][0] +=  intval($eltsn['montant_transaction']);
                // echo intval($eltsn['montant_transaction'])/10;
            } else {

                $chifrAfsgn +=  intval($eltsn['montant_transaction']);
                $mtsngn[$indxGbl][1] =  $chifrAfsgn;
            }
            // echo  'Montant GNF '.$mtsngn[$indxGbl][0].'<br>';
            //echo 'Montant Song sans GNF '.$chifrAfsgn.'<br> ';
        }
        // $mtsngn[$indxGbl][$art['id_artiste']][0] = $chifrAf;

        $listSn[$i][1] = $artistsn['titre_son'];

        $listSn[$i][2] = $artistsn['prix_son'] / $taux;

        $listSn[$i][3] = $nbTrs;

        $listSn[$i][4] = $chifrAfsgn . ' ' . $rsltTx['signe_devise'];

        $i++;

        $idxPys++;
        // echo $artistsn['titre_son'];
        // //print_r($test41);

        // echo $artistsn['prix_son'] / $taux * $nbTrs;
        // echo $nbTrs . ' ';
        // echo '<br>';

        // $chifrAfglo += $chifrAf;  
    }
    //print_r($chifrAfglo);
    $affich_el[$indxGbl][1]['son'] = $listSn;

    $nbT[0] = $nbTrxa;



    $listAl = [];

    while ($artistalb = $arta->fetch()) {


        $chifrAfsgn = 0;
        $chifrAf = 0;

        $nbTrx = 0;
        $test41 = "";
        $listAl[$u][0] = $artistalb['reference_album'];

        // $chx1 = 'album-'.$artistalb['titre_album'].'-'.$artistalb['id_album'];



        $re4 = 'SELECT * FROM transaction
        INNER JOIN pays on pays.id_pays = transaction.id_pays 
         WHERE transaction.statut_transaction = "SUCCESS"  AND date_transaction BETWEEN "2021-01-01%" AND "2021-12-31%" AND (transaction.libelle_transaction LIKE "album-%" AND transaction.libelle_transaction LIKE "%-' . $artistalb['id_album'] . '")';



        // echo $re3;

        $un1 = $bdd->query($re4);

        // $un2 = $bdd -> query($re4);

        $nbTra = $un1->rowCount();

        $nbTrxb += $nbTra;

        $nbTrx += $nbTra;



        // $elt = $un->fetch();

        foreach ($un1->fetchAll() as $eltalgn) {
            $test41 = $eltalgn['nom_pays'];
            if ($test41 == 'Guinee') {

                $mtnalbumgn[$indxGbl][0] +=  intval($eltalgn['montant_transaction']);
            } else {

                $chifrAfAlsgn +=  intval($eltsn['montant_transaction']);
                $mtnalbumgn[$indxGbl][1] =  $chifrAfAlsgn;
            }
        }

        // while ($elt1 = $un1->fetch()) {

        //     // var_dump($elt);



        //     // $chifrAf += intval($elt1['montant_transaction'])*$taux;
        //     if ($elt1['id_pays'] == 4) {
        //         //echo 'GN';
        //         $mtnalbumgn[$indxGbl][0] += intval($elt1['montant_transaction']);
        //         // var_dump($mtnalbumgn[1][0]);
        //     } else {
        //         $chifrAf += intval($elt1['montant_transaction']);
        //     }


        //     // var_dump($chifrAf);



        // }

        $listAl[$u][1] = $artistalb['titre_album'];

        $listAl[$u][2] = $artistalb['prix_album'] / $taux;

        $listAl[$u][3] = $nbTra;

        $listAl[$u][4] = $chifrAfAlsgn . ' ' . $rsltTx['signe_devise'];

        $u++;

        $idxPys++;



        // $i++;

        // $idxPys++;

    }

    $affich_el[$indxGbl][1]['album'] = $listAl;

    $nbT[1] = $nbTrxb;

    $affich_el[$indxGbl][2] = $nbT;

    $indxGbl++;
}

// $idxPys++;


$affich_e = array_shift($affich_el);

//var_dump($affich_e);

//var_dump($affich_el);
// print_r($affich_el);



//echo json_encode($affich_el);

//echo json_encode($affich_el);
// var_dump($mtsngn[1][0]);
// var_dump($mtnalbumgn[1][0]);
//var_dump($mtsngn);
//var_dump($mtnalbumgn);

$tablen =  sizeof($affich_el);
//var_dump($mtsngn);
//faire une boucle sur les lignes principales pour recuperer les infos principales 
$tabinfo = [];
$j = 0;
$indx = 0;
$smgen = 0;
$smgenal = 0;
$somdon = 0;
$tempsout = 0;
for ($i = 0; $i < $tablen; $i++) {
    //nom de l'artiste 
    $tabinfo[$i][0] = $affich_el[$i][0];
    //nombre singles vendus
    $tabinfo[$i][1] = $affich_el[$i][2][0];
    //nombre d'albums vendus
    $tabinfo[$i][2] = $affich_el[$i][2][1];
    //print_r($tabinfo[$i][0]);
    // print_r($affich_el[$j][1]['son'][0][4]);
    // determiner le nombre de sons 
    $lentabson = sizeof($affich_el[$j][1]['son']);
    // var_dump($lentabson);
    //determiner le nombre d'albums
    $lentabalbum = sizeof($affich_el[$i][1]['album']);
    //var_dump($affich_el[$i][1]['album']);
    // echo $lentabson."<br>";
    $temp = 0;
    //montants des sons [0] en CFA [1] en Francs Gn
    $tabinfo[$i][3] = $mtsngn[$i + 1][1];
    $tabinfo[$i][4] = $mtsngn[$i + 1][0];
    //montants des Albums [0] en CFA [1] en Francs Gn
    $tabinfo[$i][5] = $mtnalbumgn[$i + 1][0];
    $tabinfo[$i][6] = $mtnalbumgn[$i + 1][1];

    // $tabinfo[$i][4] = 
    //boucler sur les son et retourner les sommes 

    // for ($k = 0; $k < $lentabson; $k++) {

    //     $smgentempson = strstr($affich_el[$i][1]['son'][$k][4], ' ', true);
    //    // echo  $smgentempson;
    //     // $smgentempalb = strstr($affich_el[$i][1]['album'][$k][4], 'C', true);
    //     // var_dump($affich_el[$i][1]['album'][0][4]);
    //     $temp += intval($smgentempson);
    //     // var_dump($temp);
    //     $smgen +=  $temp;
    //     $tabinfo[$i][3] = $smgen;
    //     $tabinfo[$i][3] .= " " . $resdef['signe_devise'];
    // }
    // $smgen = 0;
    //boucler sur les albums et retourner les sommes 
    // $tempal = 0;
    // for ($l = 0; $l < $lentabalbum; $l++) {
    //     // var_dump($affich_el[$i][1]['album'][0][4]);
    //     $smgentempalb = strstr($affich_el[$i][1]['album'][0][4], ' ', true);
    //     $tempal += intval($smgentempalb);
    //     $smgenal += $tempal;
    //     $tabinfo[$i][7] =  $smgenal;
    //     $tabinfo[$i][8] .= " ";
    // }
    // $smgenal = 0;
    //var_dump($tabinfo[$i][3]);


    //recuperer les soutiens pour un artiste donné
    $reart = $bdd->query('select id_artiste from artiste where nom_artiste = "' . $tabinfo[$i][0] . '"');
    $resArt = $reart->fetch();
    //print_r($resArt);
    $tabinfo[$i][7] = 0;
    $tabinfo[$i][8] = 0;
    $reqdon1 = 'SELECT  DISTINCTROW transaction.id_transaction ,don.somme_don,transaction.libelle_transaction,pays.code_pays,pays.nom_pays from don
    INNER JOIN transaction ON transaction.id_transaction = don.id_transaction
    INNER JOIN pays ON pays.id_pays= transaction.id_pays
    INNER JOIN artiste ON artiste.id_artiste = don.id_artiste
     WHERE don.id_artiste="' . $resArt['id_artiste'] . '"
     AND transaction.statut_transaction="SUCCESS"
       
     AND transaction.date_transaction BETWEEN "2021-01-01%" AND "2021-12-31%"';
    $re24 = $bdd->query($reqdon1);


    $intval = 0;
    foreach ($re24->fetchAll() as $ln) {
        if ($ln['code_pays'] != 'GN') {
            //var_dump($ln['somme_don']);
            $intval  = intval($ln['somme_don']);
            //var_dump($intval);
            $tabinfo[$i][7] +=  $intval;
            // $tabinfo[$i][5] .= " FCFA" ;
            //var_dump( $tabinfo[$i][5]);

        } else {
            //echo 'GNF';
            // echo $ln['somme_don'];
            //  var_dump($ln['somme_don']);
            $intval  = intval($ln['somme_don']);
            //var_dump($intval);
            $tabinfo[$i][8] += $intval;
            //$tabinfo[$i][6] .= " GNF" ;
        }
        $intval = 0;
    }
    $re24->closeCursor();
    // $tabinfo[$i][5] = $ligne["SUM(don.somme_don)"];
    // $tabinfo[$i][5] .= " " . $resdef['signe_devise'];
    // var_dump( $tabinfo[$i][4] );
    //  $tabinfo[$i][3] = "<a  href='detailsart.php?indx=" . $indx . "&chM=" . $_GET["idMsn"] . "' class='btn btn-success' >Details</a>";

    $j++;
    // $j++;
    // 		// echo $tabinfo[$i][$j];
    // 		// echo $tabinfo[$i][$j+1];
    // 		// echo $tabinfo[$i][$j+2];
    //  print_r($tabinfo[$i][$j+3][$indx]);
    //print_r( $tabinfo[$i][6]);


}
echo json_encode($tabinfo,JSON_PRETTY_PRINT);
