<?php
	header("Access-Control-Allow-Origin: *");
	set_time_limit(0);
  // var_dump($_GET);
require 'connexion.php';



$gui = 1;
if ($_GET['rsx'] == "OMGN") {
//  $codeServ = "";
  $codeServ = "PAIEMENTMARCHANDOMPAYGN";
	$agent = "CONEX2831";
	$login = "628807777";
} else if($_GET['rsx'] == "MOMOGN"){
//  $codeServ = "PAIEMENTMARCHAND_MTN_CI";
  $codeServ = "PAIEMENTMARCHAND_MTN_GN";
	$agent = "CONEX2831";
	$login = "628807777";
} else if($_GET['rsx'] == "OMCI"){
  $codeServ = "PAIEMENTMARCHANDOMPAYCI";
	$agent = "VASCO2868";
	$login = "22964008585";
	$gui = 0;

} else if($_GET['rsx'] == "MOMOCI"){
  $codeServ = "PAIEMENTMARCHAND_MTN_CI";
	$agent = "VASCO2868";
	$login = "22964008585";
	$gui = 0;

} else if($_GET['rsx'] == "FLOOZCI"){
  $codeServ = "PAIEMENTMARCHAND_MOOV_CI";
	$agent = "VASCO2868";
	$login = "22964008585";
	$gui = 0;

}
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
		$ta->closeCursor();
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
	$req_maj_don->closeCursor();
	// $rep["statut"] = "INITIATED";/
} else {

	if ($_GET['rsx'] == "OMGN") {

		// $curl = curl_init("https://afreekaplay.com/gest/gestapp.php?nom_fct=18&t=tokenOM&mob=1");


		 // echo $rdfXML;

		// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		// // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
		// curl_setopt($curl, CURLOPT_TIMEOUT, 130);
		// //curl_setopt($curl, CURLOPT_HEADER, 1);
		// curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		//  $first_response = curl_exec($curl);
		//  $err = curl_error($curl);
		//  if ($err) {
		// 	  echo "cURL Error #:" . $err;
		// 	}
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://afreekaplay.com/gest/gestapp.php?nom_fct=18&t=tokenOM&mob=1",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 130,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "",

		]);

		$first_response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			  $first_respons = json_decode($first_response);
				// $timestamp = time();
				$key = $first_respons->access_token;
				// $hmac = hash_hmac('sha256', $timestamp, $key);
				   // var_dump($first_respons);

				 $curl = curl_init("https://api.orange.com/orange-money-webpay/gn/v1/webpayment");

				 $notifUrl = "https://www.afreekaplay.com/wait?ref=".$_GET['ref'];
				$headers = ["Accept: application/json",
    "Authorization: Bearer ".$key,
    "Content-Type: application/json"];
				$rdfXML = "{\n\t\"merchant_key\": \"8fbecd0b\",\n  \"currency\": \"GNF\",\n  \"order_id\": \"".$_GET['ref']."\",\n  \"amount\": ".$amount.",\n  \"return_url\":\"".$notifUrl."\",\n  \"notif_url\": \"https://www.afreekaplay.com/gest/kcabllac.php\",\n  \"cancel_url\": \"https://www.afreekaplay.com/\",\n \"lang\": \"fr\",\n \"reference\": \"AFREEKAPLAY\"\n}";


				 // echo $rdfXML;

				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
				// curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
				curl_setopt($curl, CURLOPT_TIMEOUT, 130);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
				//curl_setopt($curl, CURLOPT_HEADER, 1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
				 $first_response2 = curl_exec($curl);
				 $err = curl_error($curl);
				 if ($err) {
					  echo "cURL Error #:" . $err;
					} else {
					  echo $first_response2;
						$first_respons2 = json_decode($first_response2);
						if (isset($first_respons2->status) AND $first_respons2->status == 201) {
							$req_ajt = "INSERT INTO transactionOM (status_transactionOM, message_transactionOM, payToken_transactionOM, notifToken_transactionOM, id_transaction) VALUES (:status_transactionOM, :message_transactionOM, :payToken_transactionOM, :notifToken_transactionOM, :id_transaction)";
				        $req_aj_tok = $bdd -> prepare($req_ajt);
				        $req_aj_tok ->bindParam(':status_transactionOM', $first_respons2->status);
				        $req_aj_tok ->bindParam(':message_transactionOM', $first_respons2->message);
				        $req_aj_tok ->bindParam(':payToken_transactionOM', $first_respons2->pay_token);
				        $req_aj_tok ->bindParam(':notifToken_transactionOM', $first_respons2->notif_token);
				        $req_aj_tok ->bindParam(':id_transaction', $elt['id_transaction']);
				        $req_aj_tok ->execute();
						$req_aj_tok->closeCursor();
						}

						
					}
			}


	} else {

		 $curl = curl_init("https://api.gutouch.com/dist/api/touchpayapi/v1/".$agent."/transaction?loginAgent=".$login."&passwordAgent=0000");


		$headers[] = "Content-Type: application/json";
		$rdfXML = "{\n\t\"idFromClient\": \"".$_GET['ref']."\",\n\t\"additionnalInfos\": {\n\t\t\"recipientEmail\": \"\",\n\t\t\"recipientFirstName\": \"".$_GET['nm']."\",\n\t\t\"recipientLastName\": \"\",\n\t\t\"destinataire\": \"".$_GET['n']."\"\n\t},\n\t\"amount\": ".$amount.",\n\t\"callback\": \"https://www.afreekaplay.com/gest/kcabllac.php\",\n\t\"recipientNumber\": \"".$_GET['n']."\",\n\t\"serviceCode\": \"".$codeServ."\"\n}";

		// echo $rdfXML;

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl, CURLOPT_TIMEOUT, 130);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
		//curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_USERPWD, 'MTN:passer');
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
		 $first_response = curl_exec($curl);
	}



 //var_dump($first_response);
//666125099
$un->closeCursor();
}
 ?>
