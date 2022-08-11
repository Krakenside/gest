<?php




// liste quantité des album et son de maniere global





// $table = $_GET['table'];



require 'connexion.php';
if (isset($_GET["indx"])) {
    if (isset($_GET['a']) and $_GET['a'] == 1) {

        $_SESSION["prodID"] = $_GET['idMsn'];
    }

    $reA = "SELECT * FROM artiste INNER JOIN maison
    
                                                                    ON maison.id_maison = artiste.id_maison
    
                                                                     WHERE maison.id_maison = '" . $_SESSION["prodID"] . "'";



    $artart = $bdd->query($reA);





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



        // recuperation des pays dont la devise peut etre convertis vers le CFA

        // $reqPysNCFA='SELECT * FROM pays INNER JOIN taux

        // 														ON pays.id_devise = taux.from_taux

        // 														where to_taux = 1';

        // $rqPNCFA  = $bdd ->query($reqPysNCFA);

        // $indPNC = 0;

        // // $rsltPNCFA[] = [];

        // while ($rsltPNCFA = $rqPNCFA->fetch()) {

        // 	// echo $rsltPNCFA['id_pays'];

        //  $rsltatPNCFA[$indPNC] = $rsltPNCFA['id_pays'];

        //  $txPNCFA[$indPNC] = $rsltPNCFA['taux_taux'];

        //  $indPNC++;

        // }

        // $pos = array_search($_GET['p'], $rsltatPNCFA);

        // var_dump($pos);

        // if($rsltPays['id_pays'] == 4){

        // 	echo intval($elt['montant_transaction']);

        // }

        // if (!is_numeric($pos)) {

        $taux = 1;

        $rsltTx['signe_devise'] = 'FCFA';

        // } else {

        // 	// var_dump($elt);

        // 	// echo intval($elt['montant_transaction']);

        // 	$reqTx='SELECT *  FROM pays INNER JOIN devise

        // 															ON devise.id_devise = pays.id_devise

        // 															INNER JOIN taux

        // 															 ON pays.id_devise = taux.from_taux

        // 															 where pays.id_pays = '.$_GET['p'].' AND to_taux = 1';

        // 	 $rqTx  = $bdd ->query($reqTx);

        // 	 $rsltTx = $rqTx->fetch();

        // 	  // var_dump($rsltTx);

        // 	 $taux = $rsltTx['taux_taux'];

        // 	 // var_dump($chifrAf);

        // }



        $i = 0;

        $u = 0;

        $idxPys = 1;



        $listSn = [];

        while ($artistsn = $arts->fetch()) {

            $chifrAf = 0;

            $nbTrx = 0;

            $listSn[$i][0] = $artistsn['reference_son'];

            // $chx = 'son-'.$artistsn['titre_son'].'-'.$artistsn['id_son'];



            $re3 = 'SELECT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" AND (transaction.libelle_transaction LIKE "son-%" AND transaction.libelle_transaction LIKE "%-' . $artistsn['id_son'] . '")';



            // echo $re3;

            $un = $bdd->query($re3);

            // $un2 = $bdd -> query($re4);

            $nbTrs = $un->rowCount();

            $nbTrxa += $nbTrs;

            $nbTrx += $nbTrs;



            // $elt = $un->fetch();



            while ($elt = $un->fetch()) {

                // var_dump($elt);



                // $chifrAf += intval($elt['montant_transaction'])*$taux;

                $chifrAf += intval($elt['montant_transaction']);
                // print_r($elt['montant_transaction']);
            //      print_r($chifrAf); 
            //  echo'<br>';

                // var_dump($chifrAf);



            }

            $listSn[$i][1] = $artistsn['titre_son'];

            $listSn[$i][2] = $artistsn['prix_son'] / $taux;

            $listSn[$i][3] = $nbTrs;

            $listSn[$i][4] = $chifrAf . ' ' . $rsltTx['signe_devise'];

            $i++;

            $idxPys++;
           
           // $chifrAfglo += $chifrAf;  
        }
        //print_r($chifrAfglo);
        $affich_el[$indxGbl][1]['son'] = $listSn;

        $nbT[0] = $nbTrxa;



        $listAl = [];

        while ($artistalb = $arta->fetch()) {



            $chifrAf = 0;

            $nbTrx = 0;

            $listAl[$u][0] = $artistalb['reference_album'];

            // $chx1 = 'album-'.$artistalb['titre_album'].'-'.$artistalb['id_album'];



            $re4 = 'SELECT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" AND (transaction.libelle_transaction LIKE "album-%" AND transaction.libelle_transaction LIKE "%-' . $artistalb['id_album'] . '")';



            // echo $re3;

            $un1 = $bdd->query($re4);

            // $un2 = $bdd -> query($re4);

            $nbTra = $un1->rowCount();

            $nbTrxb += $nbTra;

            $nbTrx += $nbTra;



            // $elt = $un->fetch();



            while ($elt1 = $un1->fetch()) {

                // var_dump($elt);



                // $chifrAf += intval($elt1['montant_transaction'])*$taux;

                $chifrAf += intval($elt1['montant_transaction']);
              
                // var_dump($chifrAf);



            }

            $listAl[$u][1] = $artistalb['titre_album'];

            $listAl[$u][2] = $artistalb['prix_album'] / $taux;

            $listAl[$u][3] = $nbTra;

            $listAl[$u][4] = $chifrAf . ' ' . $rsltTx['signe_devise'];

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



    // echo json_encode($affich_el);

    //echo json_encode($affich_el);
    $tablen =  sizeof($affich_el);
    //faire une boucle sur les ligne principales pour recuperer les infos principales 
    $tabinfo = [];
    $j = 3;
    $indx = 0;
    for ($i = 0; $i < $tablen; $i++) {
        $tabinfo[$i][0] = $affich_el[$i][0];
        $tabinfo[$i][1] = $affich_el[$i][2][0];
        $tabinfo[$i][2] = $affich_el[$i][2][1];
        $tabinfo[$i][3] = "<a  href='detailsart.php?indx=" . $indx . "&chM=" . $_GET["idMsn"] . "' class='btn btn-success' >Details</a>";
        $indx++;
        // $j++;
        // 		// echo $tabinfo[$i][$j];
        // 		// echo $tabinfo[$i][$j+1];
        // 		// echo $tabinfo[$i][$j+2];
        // print_r($tabinfo[$i][$j+3][$indx]);
        //echo json_encode($tabinfo);


    }

    $tabinfoart = [];

    $indx = $_GET["indx"];


    //     $tabinfart[0] = $affich_el[$indx][1]['son'];
    $llen = sizeof($affich_el[$indx][1]['son']);
$j = 0 ;
$bnkt = '';
$bank = 0;
    for ($i = 0; $i < $llen; $i++) {
        if($i==0){
            $tabinfoart[0] = $affich_el[$indx][1]['son'][0];
            // print_r($tabinfoart[1]);
            $i++;
        }

        $tabinfoart[$i] = $affich_el[$indx][1]['son'][$i];
        $bnkt = strstr($affich_el[$indx][1]['son'][$i][4],'F',true);
        $bank += intval($bnkt);

        
         
        //  $j++;
    }
   // echo $bank;
    // print_r($llen);
    $arr = array_shift($tabinfoart);
    //print_r($affich_el[$indx][1]['son'][0][1]);
    echo json_encode($tabinfoart);
    // print_r($tabinfart);
    //  echo '<br /><br /><br>';
    //  echo json_encode($affich_el[$indx][1]['son'][0]);




} else {
    if (isset($_GET['a']) and $_GET['a'] == 1) {

        $_SESSION["prodID"] = $_GET['idMsn'];
    }

    $reA = "SELECT * FROM artiste INNER JOIN maison
    
                                                                    ON maison.id_maison = artiste.id_maison
    
                                                                     WHERE maison.id_maison = '" . $_SESSION["prodID"] . "'";



    $artart = $bdd->query($reA);





    $nbT = [];

    $affich_el = [];

    $affich_el[0] = 1;

    $indxArt = 1;

    $indxGbl = 1;



    while ($art = $artart->fetch()) {

        $nbTrxa = 0;

        $nbTrxb = 0;

        $affich_el[$indxGbl][0] = $art['nom_artiste'];

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



        // recuperation des pays dont la devise peut etre convertis vers le CFA

        // $reqPysNCFA='SELECT * FROM pays INNER JOIN taux

        // 														ON pays.id_devise = taux.from_taux

        // 														where to_taux = 1';

        // $rqPNCFA  = $bdd ->query($reqPysNCFA);

        // $indPNC = 0;

        // // $rsltPNCFA[] = [];

        // while ($rsltPNCFA = $rqPNCFA->fetch()) {

        // 	// echo $rsltPNCFA['id_pays'];

        //  $rsltatPNCFA[$indPNC] = $rsltPNCFA['id_pays'];

        //  $txPNCFA[$indPNC] = $rsltPNCFA['taux_taux'];

        //  $indPNC++;

        // }

        // $pos = array_search($_GET['p'], $rsltatPNCFA);

        // var_dump($pos);

        // if($rsltPays['id_pays'] == 4){

        // 	echo intval($elt['montant_transaction']);

        // }

        // if (!is_numeric($pos)) {

        $taux = 1;

        $rsltTx['signe_devise'] = 'FCFA';

        // } else {

        // 	// var_dump($elt);

        // 	// echo intval($elt['montant_transaction']);

        // 	$reqTx='SELECT *  FROM pays INNER JOIN devise

        // 															ON devise.id_devise = pays.id_devise

        // 															INNER JOIN taux

        // 															 ON pays.id_devise = taux.from_taux

        // 															 where pays.id_pays = '.$_GET['p'].' AND to_taux = 1';

        // 	 $rqTx  = $bdd ->query($reqTx);

        // 	 $rsltTx = $rqTx->fetch();

        // 	  // var_dump($rsltTx);

        // 	 $taux = $rsltTx['taux_taux'];

        // 	 // var_dump($chifrAf);

        // }



        $i = 0;

        $u = 0;

        $idxPys = 1;



        $listSn = [];

        while ($artistsn = $arts->fetch()) {

            $chifrAf = 0;

            $nbTrx = 0;

            $listSn[$i][0] = $artistsn['reference_son'];

            // $chx = 'son-'.$artistsn['titre_son'].'-'.$artistsn['id_son'];



            $re3 = 'SELECT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" AND (transaction.libelle_transaction LIKE "son-%" AND transaction.libelle_transaction LIKE "%-' . $artistsn['id_son'] . '")';



            // echo $re3;

            $un = $bdd->query($re3);

            // $un2 = $bdd -> query($re4);

            $nbTrs = $un->rowCount();

            $nbTrxa += $nbTrs;

            $nbTrx += $nbTrs;



            // $elt = $un->fetch();



            while ($elt = $un->fetch()) {

                // var_dump($elt);



                // $chifrAf += intval($elt['montant_transaction'])*$taux;

                $chifrAf += intval($elt['montant_transaction']);

                // var_dump($chifrAf);



            }

            $listSn[$i][1] = $artistsn['titre_son'];

            $listSn[$i][2] = $artistsn['prix_son'] / $taux;

            $listSn[$i][3] = $nbTrs;

            $listSn[$i][4] = $chifrAf . ' ' . $rsltTx['signe_devise'];

            $i++;

            $idxPys++;
        }

        $affich_el[$indxGbl][1]['son'] = $listSn;

        $nbT[0] = $nbTrxa;



        $listAl = [];

        while ($artistalb = $arta->fetch()) {



            $chifrAf = 0;

            $nbTrx = 0;

            $listAl[$u][0] = $artistalb['reference_album'];

            // $chx1 = 'album-'.$artistalb['titre_album'].'-'.$artistalb['id_album'];



            $re4 = 'SELECT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" AND (transaction.libelle_transaction LIKE "album-%" AND transaction.libelle_transaction LIKE "%-' . $artistalb['id_album'] . '")';



            // echo $re3;

            $un1 = $bdd->query($re4);

            // $un2 = $bdd -> query($re4);

            $nbTra = $un1->rowCount();

            $nbTrxb += $nbTra;

            $nbTrx += $nbTra;



            // $elt = $un->fetch();



            while ($elt1 = $un1->fetch()) {

                // var_dump($elt);



                // $chifrAf += intval($elt1['montant_transaction'])*$taux;

                $chifrAf += intval($elt1['montant_transaction']);

                // var_dump($chifrAf);



            }

            $listAl[$u][1] = $artistalb['titre_album'];

            $listAl[$u][2] = $artistalb['prix_album'] / $taux;

            $listAl[$u][3] = $nbTra;

            $listAl[$u][4] = $chifrAf . ' ' . $rsltTx['signe_devise'];

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

    // var_dump($affich_el);



    // echo json_encode($affich_el);

    //echo json_encode($affich_el);
    $tablen =  sizeof($affich_el);
    //faire une boucle sur les lignes principales pour recuperer les infos principales 
    $tabinfo = [];
    $j = 3;
    $indx = 0;
    for ($i = 0; $i < $tablen; $i++) {
        $tabinfo[$i][0] = $affich_el[$i][0];
        $tabinfo[$i][1] = $affich_el[$i][2][0];
        $tabinfo[$i][2] = $affich_el[$i][2][1];
        $tabinfo[$i][3] = "<a  href='detailsart.php?indx=" . $indx . "&chM=" . $_GET["idMsn"] . "' class='btn btn-success' >Details</a>";

        $indx++;
        // $j++;
        // 		// echo $tabinfo[$i][$j];
        // 		// echo $tabinfo[$i][$j+1];
        // 		// echo $tabinfo[$i][$j+2];
        // print_r($tabinfo[$i][$j+3][$indx]);


    }


    echo json_encode($tabinfo);
}
