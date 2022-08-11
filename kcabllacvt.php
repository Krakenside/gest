<?php
header("Access-Control-Allow-Origin: *");

 // var_dump ($_POST);
 set_time_limit(0);
 ini_set('memory_limit', '512M');

 require_once __DIR__.'/../vendor/autoload.php';
   require 'connexion.php';

// var_dump($_POST);
$ddj = date ('Y-m-d H:i:s');
$data_received=file_get_contents("php://input");
$data_received_xml=new SimpleXMLElement($data_received);
$ligne_response=$data_received_xml[0];
$interface_received=$ligne_response->INTERFACEID;
$reference_received=$ligne_response->REF;
$type_received=$ligne_response->TYPE;
$statut_received=$ligne_response->STATUT;
$operateur_received=$ligne_response->OPERATEUR;
$client_received=$ligne_response->TEL_CLIENT;
$message_received=$ligne_response->MESSAGE;
$token_received=$ligne_response->TOKEN;
$agent_received=$ligne_response->AGENT;

var_dump($_GET);
$v1 = json_encode($data_received_xml[0]);
$lib = "NIVEAU 1 (AIRTEL GABON) --- ".$v1;
//echo $lib;
$req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
$req_aj_tel = $bdd -> prepare($req_ajt);
$req_aj_tel ->bindParam(':date_log', $ddj);
$req_aj_tel ->bindParam(':libelle_log', $lib);
$req_aj_tel ->execute();

 if(isset($statut_received)) {

   $re = "SELECT * FROM tokenAIRT ORDER BY id_tokenAIRT DESC";
   $rq = $bdd -> query($re);
   $token = $rq -> fetch();

   if(!isset($token['expire_tokenAIRT']) OR $ddj > $token['expire_tokenAIRT']){
     $my_date_time=time("Y-m-d H:i:s");

     $my_new_date_time=$my_date_time+3600;
     $dateTime=date("Y-m-d H:i:s",$my_new_date_time);

     $req_ajt = "INSERT INTO tokenAIRT (token_tokenAIRT, expire_tokenAIRT) VALUES (:token_tokenAIRT, :expire_tokenAIRT)";
       $req_aj_tok = $bdd -> prepare($req_ajt);
       $req_aj_tok ->bindParam(':expire_tokenAIRT', $dateTime);
       $req_aj_tok ->bindParam(':token_tokenAIRT', $token_received);
       $req_aj_tok ->execute();
   }

   $statutt = ($statut_received == 200) ? 'SUCCESS' : 'FAIL';
   $re = "SELECT * FROM transaction WHERE reference_transaction = '".$reference_received."'";
   $un = $bdd -> query($re);
   $untr = $un->fetch();

   $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
   $req_maj_don = $bdd -> prepare($req_majd);
   //$req_maj_don ->bindParam(':montant_transaction', $amount);
   $req_maj_don ->bindParam(':statut_transaction', $statutt);
   $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
   $req_maj_don ->execute();

   // if ($statutt == 'SUCCESS') {
   //   header("Location:https://afreekaplay.com/callback?ref=".$reference_received);
   // } else {
   //   header("Location:https://afreekaplay.com/artists";
   //
   // }


} else {
  echo "nada";
  header("Location:https://afreekaplay.com/artists");

}





?>
