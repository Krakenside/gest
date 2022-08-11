<?php
header("Access-Control-Allow-Origin: *");

 // var_dump ($_POST);
 set_time_limit(0);
 ini_set('memory_limit', '512M');

 require_once __DIR__.'/../vendor/autoload.php';
   require 'connexion.php';

// var_dump($_POST);
$ddj = date ('Y-m-d H:i:s');
// $_GET = (isset($_POST)) ? $_POST : $_GET;
var_dump($_GET);
$v1 = json_encode($_GET);
$lib = "NIVEAU 1 --- ".$v1;
//echo $lib;
$req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
$req_aj_tel = $bdd -> prepare($req_ajt);
$req_aj_tel ->bindParam(':date_log', $ddj);
$req_aj_tel ->bindParam(':libelle_log', $lib);
$req_aj_tel ->execute();

 if(isset($_GET['referenceNumber'])) {
   //paiement pro
   $statutt = ($_GET['responsecode'] == 0) ? 'SUCCESS' : 'FAIL' ;


   $opt = explode('-',$_GET['referenceNumber']);
   if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

     $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$_GET['referenceNumber']."'";
     $un = $bdd -> query($re);
     $untr = $un -> fetch();

     $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
     $req_maj_don = $bdd -> prepare($req_majd);

     $req_maj_don ->bindParam(':statut_rechargement', $statutt);
     $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
     $req_maj_don ->execute();

     if ($statutt == "SUCCESS") {

       $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
       $un2 = $bdd -> query($re);
       $wal = $un2 -> fetch();
       $solde = $wal['solde_walletP'];
       $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
       $req_maj_wal = $bdd -> prepare($req_majw);
       $amountAj = $solde + $untr['montant_rechargement'];

       $req_maj_don ->bindParam(':solde_walletP', $amountAj);
       $req_maj_don ->bindParam(':id_walletP', $req_maj_wal['id_walletP']);
       $req_maj_don ->execute();
     }

   } else {
     $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['referenceNumber']."'";
     $un = $bdd -> query($re);
     $untr = $un->fetch();

     $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
     $req_maj_don = $bdd -> prepare($req_majd);
     //$req_maj_don ->bindParam(':montant_transaction', $amount);
     $req_maj_don ->bindParam(':statut_transaction', $statutt);
     $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
     $req_maj_don ->execute();

     if (isset($untr['id_user']) AND $untr['montant_transaction'] >= 2000 AND end($lib) !== "don" AND $statutt == "SUCCESS") {
       $re = "SELECT * FROM walletB WHERE id_user = ".$untr['id_user'];
       $rq = $bdd -> query($re);
       $wall = $rq -> fetch();

       $solde = $wall['solde_walletB'] + $untr['montant_transaction']*0.05;

       $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
       $req_msold = $bdd -> prepare($req_msol);
       $req_msold ->bindParam(':solde_walletB', $solde);
       $req_msold ->bindParam(':id', $wall['id_walletB']);
       $req_msold -> execute();
     }
   }


   // if(preg_match("#-don#", $untr['libelle_transaction'])){
   //
   //   $infoT = explode('-',$untr['libelle_transaction']);
   //   $limTab = sizeof($infoT);
   //   $idSA = $infoT[$limTab-2];
   //   $tableee = $infoT[0];
   //   $re2 = "SELECT * FROM ".$tableee." WHERE id_".$tableee." = '".$idSA."'";
   //   $un2 = $bdd -> query($re2);
   //   $untr2 = $un2->fetch();
   //
   //   $req_ajt = "INSERT INTO don (nom_don, somme_don, numero_don, id_artiste, id_transaction) VALUES (:nom_don, :somme_don, :numero_don, :id_artiste, :id_transaction)";
 	// 		$req_aj_tel = $bdd -> prepare($req_ajt);
 	// 		$req_aj_tel ->bindParam(':nom_don', $untr['nom_transaction']);
 	// 		$req_aj_tel ->bindParam(':somme_don', $untr['montant_transaction']);
 	// 		$req_aj_tel ->bindParam(':numero_don', $untr['telephone_transaction']);
   //     $req_aj_tel ->bindParam(':id_artiste', $untr2['id_artiste']);
   //     $req_aj_tel ->bindParam(':id_transaction', $untr['id_transaction']);
   //
 	// 		$req_aj_tel ->execute();
   //     //$don = "&don=don";
   // }

   if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
     $rep['status'] = "SUCCESS";
     echo json_encode($rep);
   } else {
     header("Location:https://afreekaplay.com/callback?ref=".$_GET['referenceNumber']);
   }


} else {
  echo "nada";
  header("Location:https://afreekaplay.com/artists");

}





?>
