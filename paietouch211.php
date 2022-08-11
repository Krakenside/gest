<?php
	header("Access-Control-Allow-Origin: *");
	set_time_limit(0);
  // var_dump($_GET);
require 'connexion.php';



$gui = 1;

$tx = 1;
$re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
$un = $bdd -> query($re);
//$nbuser = $un->rowCount();
$elt = $un->fetch();

$lib = explode('-', $elt['libelle_transaction']);
$tail = sizeof($lib);

if (end($lib) == "don") {
	$id = $lib[$tail-2];
} else {
	$id = $lib[$tail-1];
	//var_dump($id);

}
if (end($lib) == "don") {
	$amount = $_GET['s'];

} else {

	$re = "SELECT * FROM ".$lib[0]." WHERE ".$lib[0].".id_".$lib[0]." = ".$id;
	//echo $re;
	$son = $bdd -> query($re);
	//$nbson = $son -> rowCount();
	$sons = $son->fetch();


	if($gui == 1){


		$reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =2";
		$ta = $bdd -> query($reqT);
		$taux = $ta->fetch();
		$tx = $taux['taux_taux'];
		//$amount = $sons['prix_son']*$tx;
	}

	$amount = $sons['prix_'.$lib[0]]*$tx;
}

if ($amount == 0) {
	$statutt = "SUCCESS";
	$req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
  $req_maj_don = $bdd -> prepare($req_majd);
  //$req_maj_don ->bindParam(':montant_transaction', $amount);
  $req_maj_don ->bindParam(':statut_transaction', $statutt);
  $req_maj_don ->bindParam(':id_transaction', $elt['id_transaction']);
  $req_maj_don ->execute();
	//header("Location:https://afreekaplay.com/callback?ref=".$_GET['ref']);
	$rep["status"] = "INITIATED";
	echo json_encode($rep);
	// $rep["statut"] = "INITIATED";/
} else {

	
		$timestamp = time();
		$key = "9oeod8TteR4mAwYDb1S8ISgfWfZgBuosO2kG7Smd";
		$hmac = hash_hmac('sha256', $timestamp, $key);
		 // var_dump($timestamp);
		 $_GET['ref'] = "AFPTX-PqScdgJ9363";
		 $amount = 1000;
		 $curl = curl_init("https://payment.billetfacile.com:443/api/payments");


		$headers = ["Content-Type: application/json", "Authorization :Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZDgxMGIwZjMyODc3MWFhMmMwYjg0MTcxNzlhOWQ3YjlmZjhmMmU2OTExNmI2NGRhMDIxYWQxYzBmMWZkMWNjYzU0YTJlYmIyMzY4YzRiMDQiLCJpYXQiOiIxNjEzNjUwNjA2Ljg2MzExMyIsIm5iZiI6IjE2MTM2NTA2MDYuODYzMTE5IiwiZXhwIjoiMTY0NTE4NjYwNi44NTgzMzMiLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.PU6yIDqjYMW_6j_1GGskfczmzbilhMRqAsN6bq1iD4rM4OpltclZ0sMDeqHEjCLeNH8eKyblTw8rZMlr40pDr5UaimeRjQup6AmxhHhUSzSq21A6-HKsPDr_YI2GE-3d3NfYhw60Eq7dXAkouQM8I1LAjk49iOCjhprgQmzAFRRYD1rYCzN6bHif8IjVJUnpbxLBPa2y_x-lTEy8jFihqNVK8zAeNNlAa1AsNISiA2kW4GEsZjGVlAjyKo43XfMpYvk_Rwb-XfOH_RwDZsX_wlDFmTuGSLaUw8AouYuJBUaDTJj-WBQjo4vUimQj3iOLrx9ogMPmq1h9Ad3NgCH5JBy4ghHguRJ-e0rpH1SzHuZIzmPzMPSl6wAODLti_odvOmrp6PV3P_fB2vFmC3QXMQ8Yu_6Q5gR6DDj9lqn1ZecnllOY5y7EHkO0DmzVrZbVSxoYrrjQrr3CPkY7MSLYXl4FJWBCS98RonATZpNVArieTm0T2VkfEUaTBZ25rJablH0Pp70cCsyoUmZw8bxgqbjKZJVotj_WW_MW46dBXVCJsNw2Cd7UViibUtugtvS_Y87mBc-ZqZPJETP9iBLO_MiErzjEGjwydOP3iaj1eJ_M8EsbWY2YqiKhSdG2a0ivzXDWJHziDGIO5hgU4jI83uuKc2jQ4AaIKajrd8UsoVo"];
		$rdfXML = "{\n\t\"hmac\": \"".$hmac."\",\n\t\"timestamp\": \"".$timestamp."\",\n\t\t\"transaction_id\": \"".$_GET['ref']."\",\n\t\t\"return_url\": \"https://afreekaplay.com/\"\n\t,\n\t\"amount\": ".$amount.",\n\t\"callback_url\": \"https://www.afreekaplay.com/gest/kcabllac.php\"}";

		 // echo $rdfXML;

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		// curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl, CURLOPT_TIMEOUT, 130);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
		//curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
		 $first_response = curl_exec($curl);
		 $err = curl_error($curl);
		 if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  echo $first_response;
			}




 //var_dump($first_response);
//666125099
}
 ?>
