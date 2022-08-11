<?php
	header("Access-Control-Allow-Origin: *");
	set_time_limit(0);
  // var_dump($_GET);
require 'connexion.php';




$tx = 1;
$_GET['n'] = str_replace(' ','',$_GET['n']);
$numero1 = $_GET['n'];

if(substr($_GET['n'], 0, 2) == "00"){
	$numero1 = substr($_GET['n'], 2);

}
if(substr($_GET['n'], 0, 1) == "+"){
	$numero1 = substr($_GET['n'], 1);

}
 //echo $numero1;

$repays = "SELECT * FROM pays";
$rqpays = $bdd -> query($repays);
$idxPys = 0;

while ($pays = $rqpays -> fetch()) {

	$indiPys[$idxPys] = $pays['indicatif_pays'];

	$idxPys++;
}

// if(substr($_POST['n'], 0, 2) == "22" || substr($_POST['n'], 0, 2) == "23"){
if(in_array(substr($numero1, 0, 3), $indiPys)){
	$numero1 = substr($numero1, 3);
}

if (substr($numero1, 0, 1) == "0") {
	if(substr($numero1, 0, 2) == "06"){
		$operateur = "MC";
	} else if(substr($numero1, 0, 2) == "07"){
		$operateur = "AM";
	}

} else {
	if(substr($numero1, 0, 1) == "6"){
		$operateur = "MC";
	} else if(substr($numero1, 0, 1) == "7"){
		$operateur = "AM";
	}
	$numero1 = "0".$numero1;
}

//echo $numero1;

$opt = explode('-',$_GET['ref']);
if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
  $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
  $amount = $elt['montant_rechargement'];
  $un->closeCursor();
} else {
  $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();

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



		$amount = $sons['prix_'.$lib[0]]*$tx;
		$son->closeCursor();
	}
	$un->closeCursor();
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


		//  $curl = curl_init("https://mypvit.com/pvit-secure-full-api.kk");
		//
		//
		// $headers[] = "Content-Type: application/json";
		// $rdfXML = "{\n\t\"idFromClient\": \"".$_GET['ref']."\",\n\t\"additionnalInfos\": {\n\t\t\"recipientEmail\": \"\",\n\t\t\"recipientFirstName\": \"".$_GET['nm']."\",\n\t\t\"recipientLastName\": \"\",\n\t\t\"destinataire\": \"".$_GET['n']."\"\n\t},\n\t\"amount\": ".$amount.",\n\t\"callback\": \"https://www.afreekaplay.com/gest/kcabllac.php\",\n\t\"recipientNumber\": \"".$_GET['n']."\",\n\t\"serviceCode\": \"".$codeServ."\"\n}";
		//
		// //echo $rdfXML;
		//
		// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		// curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
		// curl_setopt($curl, CURLOPT_TIMEOUT, 130);
		// curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
		// //curl_setopt($curl, CURLOPT_HEADER, 1);
		// curl_setopt($curl, CURLOPT_USERPWD, 'MTN:passer');
		// curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		// curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		// curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
		//  $first_response = curl_exec($curl);
		// $curl = curl_init("https://afreekaplay.com/gest/gestapp.php?nom_fct=18&t=tokenAIRT&mob=1");
		//
		//
		//  // echo $rdfXML;
		//
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
		// 		echo "hhhh";
		// 	} else {
				$re = "SELECT * FROM tokenAIRT ORDER BY id_tokenAIRT DESC";
				$rq = $bdd -> query($re);
				$token = $rq -> fetch();
				// $rq->closeCursor();


				$first_response['access_token'] = $token['token_tokenAIRT'];
				$rep['status'] = "ATTENTE";
			  //$first_respons = json_decode($first_response);
				// $timestamp = time();
				//$key = $first_respons->access_token;
				$key = $first_response['access_token'];
				//echo $key;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_URL,"https://mypvit.com/pvit-secure-full-api.kk");
				curl_setopt($curl, CURLOPT_POSTFIELDS,"tel_marchand=076514488&montant=".$amount."&ref=".$_GET['ref']."&tel_client=".$numero1."&token=".$key."&action=1&service=REST&operateur=".$operateur."&agent=siteafp");
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$first_response = curl_exec($curl);
				$err = curl_error($curl);
	 		 if ($err) {
	 			  echo "cURL Error #:" . $err;
	 			} else {
					//echo "tel_marchand=076514488&montant=".$amount."&ref=".$_GET['ref']."&tel_client=".$numero1."&token=".$key."&action=1&service=REST&operateur=".$operateur."&agent=siteafp";
					$data_received_xml=new SimpleXMLElement($first_response);
					$ligne_response=$data_received_xml[0];
					$rep['status']=$ligne_response->STATUT;
				}
				echo json_encode($rep);
			//}





$rq->closeCursor();
 //var_dump($first_response);
//666125099
$un->closeCursor();
}
 ?>
