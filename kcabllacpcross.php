<?php
header("Access-Control-Allow-Origin: *");

 set_time_limit(0);
 ini_set('memory_limit', '512M');


   require 'connexion.php';

   $_POST = json_decode(file_get_contents('php://input'), true);
     // var_dump($_POST['details']);
$ddj = date ('Y-m-d H:i:s');
$v1 = json_encode($_POST);
$lib = "NIVEAU 2 (PAIE BENIN) --- ".$v1;
// echo $lib;
$req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
$req_aj_tel = $bdd -> prepare($req_ajt);
$req_aj_tel ->bindParam(':date_log', $ddj);
$req_aj_tel ->bindParam(':libelle_log', $lib);
$req_aj_tel ->execute();
//
 if(isset($_POST['resultCode'])) {
   // echo 'o -'.$_POST['requestId'];
   $statutt = ($_POST['resultCode'] == 0) ? 'SUCCESS' : 'FAIL';
   $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_POST['requestId']."'";
   $un = $bdd -> query($re);
   $untr = $un->fetch();
   // var_dump($statutt);

   $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
   $req_maj_don = $bdd -> prepare($req_majd);
   //$req_maj_don ->bindParam(':montant_transaction', $amount);
   $req_maj_don ->bindParam(':statut_transaction', $statutt);
   $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
   $req_maj_don ->execute();

     $rep['statut'] = $statutt;
     echo json_encode($rep);

//
//
} else {
  $rep['statut'] = 'FAIL';
  echo json_encode($rep);

}
//




?>
