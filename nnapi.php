<?php

require 'connexion.php';
$req = "select count(id_artiste) from artiste ";
$req2 = "select id_maison from maison";
$req2st = $bdd->query($req2);
$repst  = $bdd->query($req);
$repres = $repst->fetch();
$resreq2 = $req2st->fetchAll();
$nbit2 = intval($resreq2);
$nbit = intval($repres['count(id_artiste)']);
//$nbit2 = intval($resreq2['id_maison']);
$bnn = sizeof($resreq2);
// echo json_encode($bnn);
// print_r($resreq2);
$arrid = array();
for ($j = 0; $j < $bnn; $j++) {
    // echo $resreq2[$j]['id_maison'];

$arrid[$j] = $resreq2[$j]['id_maison'];

}
// boucler sur les maisons de production et obtenir les infos sur les artistes

for($i=0;$i<$bnn;$i++){
    echo $arrid[$i];
    
    // $reA = "SELECT * FROM artiste INNER JOIN maison
    
    //                                                                 ON maison.id_maison = artiste.id_maison
    
    //                                                                  WHERE maison.id_maison = '" .$arrid[$i]  . "'";



    // $artart = $bdd->query($reA);





    // $nbT = [];

    // $affich_el = [];

    // $affich_el[0] = 1;

    // $indxArt = 1;

    // $indxGbl = 1;
    // $chifrAfglo = 0;



    // while ($art = $artart->fetch()) {

    //     $nbTrxa = 0;

    //     $nbTrxb = 0;

    //     $affich_el[$indxGbl][0] = $art['nom_artiste'];

    //     $re = "SELECT * FROM artiste INNER JOIN son
    
    //                                                                     ON son.id_artiste = artiste.id_artiste
    
    //                                                                      WHERE artiste.id_artiste = '" . $art['id_artiste'] . "'";

    //     $re2 = "SELECT * FROM artiste INNER JOIN album
    
    //                                                                     ON album.id_artiste = artiste.id_artiste
    
    //                                                                      WHERE artiste.id_artiste = '" . $art['id_artiste'] . "'";



    //     $arts = $bdd->query($re);

    //     $nbarts = $arts->rowCount();

    //     $arta = $bdd->query($re2);

    //     $nbarta = $arta->rowCount();

    //     $chx1 = "";

    //     $chx = "";



    //     // recuperation des pays dont la devise peut etre convertis vers le CFA

    //     // $reqPysNCFA='SELECT * FROM pays INNER JOIN taux

    //     // 														ON pays.id_devise = taux.from_taux

    //     // 														where to_taux = 1';

    //     // $rqPNCFA  = $bdd ->query($reqPysNCFA);

    //     // $indPNC = 0;

    //     // // $rsltPNCFA[] = [];

    //     // while ($rsltPNCFA = $rqPNCFA->fetch()) {

    //     // 	// echo $rsltPNCFA['id_pays'];

    //     //  $rsltatPNCFA[$indPNC] = $rsltPNCFA['id_pays'];

    //     //  $txPNCFA[$indPNC] = $rsltPNCFA['taux_taux'];

    //     //  $indPNC++;

    //     // }

    //     // $pos = array_search($_GET['p'], $rsltatPNCFA);

    //     // var_dump($pos);

    //     // if($rsltPays['id_pays'] == 4){

    //     // 	echo intval($elt['montant_transaction']);

    //     // }

    //     // if (!is_numeric($pos)) {

    //     $taux = 1;

    //     $rsltTx['signe_devise'] = 'FCFA';

    //     // } else {

    //     // 	// var_dump($elt);

    //     // 	// echo intval($elt['montant_transaction']);

    //     // 	$reqTx='SELECT *  FROM pays INNER JOIN devise

    //     // 															ON devise.id_devise = pays.id_devise

    //     // 															INNER JOIN taux

    //     // 															 ON pays.id_devise = taux.from_taux

    //     // 															 where pays.id_pays = '.$_GET['p'].' AND to_taux = 1';

    //     // 	 $rqTx  = $bdd ->query($reqTx);

    //     // 	 $rsltTx = $rqTx->fetch();

    //     // 	  // var_dump($rsltTx);

    //     // 	 $taux = $rsltTx['taux_taux'];

    //     // 	 // var_dump($chifrAf);

    //     // }



    //     $i = 0;

    //     $u = 0;

    //     $idxPys = 1;



    //     $listSn = [];

    //     while ($artistsn = $arts->fetch()) {

    //         $chifrAf = 0;

    //         $nbTrx = 0;

    //         $listSn[$i][0] = $artistsn['reference_son'];

    //         // $chx = 'son-'.$artistsn['titre_son'].'-'.$artistsn['id_son'];



    //         $re3 = 'SELECT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" AND (transaction.libelle_transaction LIKE "son-%" AND transaction.libelle_transaction LIKE "%-' . $artistsn['id_son'] . '")';



    //         // echo $re3;

    //         $un = $bdd->query($re3);

    //         // $un2 = $bdd -> query($re4);

    //         $nbTrs = $un->rowCount();

    //         $nbTrxa += $nbTrs;

    //         $nbTrx += $nbTrs;



    //         // $elt = $un->fetch();



    //         while ($elt = $un->fetch()) {

    //             // var_dump($elt);



    //             // $chifrAf += intval($elt['montant_transaction'])*$taux;

    //             $chifrAf += intval($elt['montant_transaction']);
    //             // print_r($elt['montant_transaction']);
    //         //      print_r($chifrAf); 
    //         //  echo'<br>';

    //             // var_dump($chifrAf);



    //         }

    //         $listSn[$i][1] = $artistsn['titre_son'];

    //         $listSn[$i][2] = $artistsn['prix_son'] / $taux;

    //         $listSn[$i][3] = $nbTrs;

    //         $listSn[$i][4] = $chifrAf . ' ' . $rsltTx['signe_devise'];

    //         $i++;

    //         $idxPys++;
           
    //        // $chifrAfglo += $chifrAf;  
    //     }
    //     //print_r($chifrAfglo);
    //     $affich_el[$indxGbl][1]['son'] = $listSn;

    //     $nbT[0] = $nbTrxa;



    //     $listAl = [];

    //     while ($artistalb = $arta->fetch()) {



    //         $chifrAf = 0;

    //         $nbTrx = 0;

    //         $listAl[$u][0] = $artistalb['reference_album'];

    //         // $chx1 = 'album-'.$artistalb['titre_album'].'-'.$artistalb['id_album'];



    //         $re4 = 'SELECT * FROM transaction WHERE transaction.statut_transaction = "SUCCESS" AND (transaction.libelle_transaction LIKE "album-%" AND transaction.libelle_transaction LIKE "%-' . $artistalb['id_album'] . '")';



    //         // echo $re3;

    //         $un1 = $bdd->query($re4);

    //         // $un2 = $bdd -> query($re4);

    //         $nbTra = $un1->rowCount();

    //         $nbTrxb += $nbTra;

    //         $nbTrx += $nbTra;



    //         // $elt = $un->fetch();



    //         while ($elt1 = $un1->fetch()) {

    //             // var_dump($elt);



    //             // $chifrAf += intval($elt1['montant_transaction'])*$taux;

    //             $chifrAf += intval($elt1['montant_transaction']);
              
    //             // var_dump($chifrAf);



    //         }

    //         $listAl[$u][1] = $artistalb['titre_album'];

    //         $listAl[$u][2] = $artistalb['prix_album'] / $taux;

    //         $listAl[$u][3] = $nbTra;

    //         $listAl[$u][4] = $chifrAf . ' ' . $rsltTx['signe_devise'];

    //         $u++;

    //         $idxPys++;


           
    //         // $i++;

    //         // $idxPys++;

    //     }

    //     $affich_el[$indxGbl][1]['album'] = $listAl;

    //     $nbT[1] = $nbTrxb;

    //     $affich_el[$indxGbl][2] = $nbT;

    //     $indxGbl++;
    // }

    // // $idxPys++;

    
    // $affich_e = array_shift($affich_el);

}