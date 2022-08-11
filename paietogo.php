<?php
	header("Access-Control-Allow-Origin: *");
	set_time_limit(0);
 // var_dump($_GET);
require 'connexion.php';



// $gui = 1;
// if ($_GET['rsx'] == "OMGN") {
// //  $codeServ = "";
//   $codeServ = "PAIEMENTMARCHANDOMPAYGN";
// } else if($_GET['rsx'] == "MOMOGN"){
// //  $codeServ = "PAIEMENTMARCHAND_MTN_CI";
//   $codeServ = "PAIEMENTMARCHAND_MTN_GN";
// } else if($_GET['rsx'] == "OMCI"){
//   $codeServ = "PAIEMENTMARCHANDOMPAYCI";
// 	$gui = 0;
//
// } else if($_GET['rsx'] == "MOMOCI"){
//   $codeServ = "PAIEMENTMARCHAND_MTN_CI";
// 	$gui = 0;
//
// } else if($_GET['rsx'] == "FLOOZCI"){
//   $codeServ = "PAIEMENTMARCHAND_MOOV_CI";
// 	$gui = 0;
//
// }
$tx = 1;
$opt = explode('-',$_GET['ref']);
if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
  $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
  $amount = $elt['montant_rechargement'];
	$libT = "Rechargement";
	$optwal = "&wall=1";
	// $reP = "SELECT * FROM pays INNER JOIN devise
	// 													ON devise.id_devise = pays.id_devise
	// 													WHERE pays.id_pays = ".$elt['id_pays'];
	// $lpays = $bdd -> query($reP);
	// $lpays = $lpays->fetch();

	$re = "SELECT * FROM walletP WHERE id_user = ".$elt['id_user'];
	$un = $bdd -> query($re);
	//$nbuser = $un->rowCount();
	$eltw = $un->fetch();
	// var_dump($re);

		$msg_prompt = "Vous+souhaiter+faire+un+achat+de+rechargement+de+".$amount."+taper+1+pour+confirmer:";

	$idTrsx = $elt['id_rechargement'];
	$un->closeCursor();

} else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {
  $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
	$libT = "Forfait";

	$re = "SELECT * FROM forfaitStream WHERE id_forfaitStream = ".$elt['id_forfaitStream'];
	$un = $bdd -> query($re);
	$eltw = $un->fetch();


  $amount = $eltw['prix_forfaitStream'];

	$idTrsx = $elt['id_forfaitTrsx'];

	$msg_prompt = "Vous+souhaiter+faire+un+achat+de+".$eltw['nom_forfaitStream']."+a+".$amount."+taper+1+pour+confirmer:";

	$un->closeCursor();
} else {
	$re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
	$un = $bdd -> query($re);
	//$nbuser = $un->rowCount();
	$elt = $un->fetch();
	 // var_dump($elt);

	$lib = explode('-', $elt['libelle_transaction']);
	$tail = sizeof($lib);

	if (end($lib) == "don") {
		$id = $lib[$tail-2];
	} else {
		$id = $lib[$tail-1];

	}
	if (end($lib) == "don") {
		$amount = $_GET['s'];
		$msg_prompt = "Vous+souhaiter+faire+un+don+de+".$amount."+taper+1+pour+confirmer:";
	} else {

		$re = "SELECT * FROM ".$lib[0]." WHERE ".$lib[0].".id_".$lib[0]." = ".$id;
		//echo $re;
		$son = $bdd -> query($re);
		//$nbson = $son -> rowCount();
		$sons = $son->fetch();
		// var_dump($sons);


		// if($gui == 1){
		//
		//
		// 	$reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =2";
		// 	$ta = $bdd -> query($reqT);
		// 	$taux = $ta->fetch();
		// 	$tx = $taux['taux_taux'];
		// 	//$amount = $sons['prix_son']*$tx;
		// }

		$amount = $sons['prix_'.$lib[0]]*$tx;
		$msg_prompt = "Vous+souhaiter+acheter+la+chanson+".$lib[1]."+à+".$amount."+taper+1+pour+confirmer:";
	}
	$idTrsx = $elt['id_transaction'];
	$un->closeCursor();
	$son->closeCursor();
}


if ($amount == 0) {
	$statutt = "SUCCESS";
	$req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
  $req_maj_don = $bdd -> prepare($req_majd);
  //$req_maj_don ->bindParam(':montant_transaction', $amount);
  $req_maj_don ->bindParam(':statut_transaction', $statutt);
  $req_maj_don ->bindParam(':id_transaction', $idTrsx);
  $req_maj_don ->execute();
	//header("Location:https://afreekaplay.com/callback?ref=".$_GET['ref']);
	$rep["status"] = "0";
	echo json_encode($rep);
	// $rep["statut"] = "INITIATED";/
	$req_maj_don->closeCursor();
} else {
	if($_GET['rsx'] == "FLOOZTG"){
		initpay($amount);
	}
	else if($_GET['rsx'] == "AIRTG") {
		// echo $_GET['n']."--".$lib[1]."--".$_GET['ref'];
		if($opt[0]=='AFPTX'){
			$lib[1] = ucwords($lib[1]);
			$lib[1] = str_replace(' ','',$lib[1]);
			$lib[1] = urlencode($lib[1]);
		}

		// echo $lib[1];
		$curl1 = curl_init("http://5.9.195.246/moovFlash/flash.php?n=".$_GET['n']."&nomAcht=".$lib[1]."&p=".$amount."&ref=".$_GET['ref']);

		 // echo $rdfXML;
		// curl_setopt($curl1, CURLOPT_PORT, "6655");
		curl_setopt($curl1, CURLOPT_FOLLOWLOCATION, TRUE);
		// curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl1, CURLOPT_TIMEOUT, 130);
		//curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl1, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl1, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl1, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		 $first_response = curl_exec($curl1);
		 // var_dump($first_response);
		 $err = curl_error($curl1);
		 // curl_close($curl1);

		 if ($err) {
			  echo "cURL Error #:" . $err;
			} else {

				$first_response3 = explode('ErrorCode',$first_response);
		     // var_dump($first_response);
		    $rep["status"] = $first_response3[1][1];
				if ($rep["status"] == 0) {
					$statutt = "SUCCESS";
					$ref = $_GET['ref'];
					if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

			      // $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
			      // $un = $bdd -> query($re);
			      // $untr = $un -> fetch();

			      $re = "SELECT * FROM walletP WHERE id_walletP = '".$elt['id_walletP']."'";
			      $un2 = $bdd -> query($re);
			      $wal = $un2 -> fetch();
			      $solde = (isset($wal['solde_walletP'])) ? $eltw['solde_walletP'] : 0;

			      $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
			      $req_maj_don = $bdd -> prepare($req_majd);

			      $req_maj_don ->bindParam(':statut_rechargement', $statutt);
			      $req_maj_don ->bindParam(':id_rechargement', $elt['id_rechargement']);
			      $req_maj_don ->execute();
			      if ($statutt == "SUCCESS") {
			        $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
			        $req_maj_wal = $bdd -> prepare($req_majw);
			        $amountAj = $solde + $elt['montant_rechargement'];

			        $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
			        $req_maj_wal ->bindParam(':id_walletP', $eltw['id_walletP']);
			        $req_maj_wal ->execute();
			      }
				  $un2->closeCursor();
				  $req_maj_don->closeCursor();
				  $req_maj_wal->closeCursor();
			    } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

			      $dateAuj =  date ('Y-m-d H:i:s');

			      // $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$ref."'";
			      // $un = $bdd -> query($re);
			      // $untr = $un -> fetch();

			      if ($statutt == "SUCCESS") {
						$reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =" . $elt['id_forfaitStream'];
						// echo $reqF;
						$for = $bdd->query($reqF);
						$forfait = $for->fetch();
						$nbScd = $forfait['temps_forfaitStream'] * 86400;
						$datee2Ts = date('Y-m-d H:i:s', time() + $nbScd);
						// Moyen de paiement 1 mobile mo, 0 Airtime
						$renouv = 0;
						$moyenPaie = "MM";

						$req_ajuf =
						"INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :id_user, :id_forfaitStream)";
						$req_aj_uf = $bdd->prepare($req_ajuf);
						$req_aj_uf->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
						$req_aj_uf->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
						$req_aj_uf->bindParam(':renouvellement_user_forfaitStream', $renouv);
						$req_aj_uf->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
						$req_aj_uf->bindParam(':id_user', $elt["id_user"]);
						$req_aj_uf->bindParam(':id_forfaitStream', $elt['id_forfaitStream']);
						$req_aj_uf->execute();


						$for->closeCursor();
						$req_aj_uf->closeCursor();

					  
			      }

			      $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
			      $req_maj_don = $bdd -> prepare($req_majd);

			      $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
			      $req_maj_don ->bindParam(':id_forfaitTrsx', $elt['id_forfaitTrsx']);
			      $req_maj_don ->execute();
				  

			    } else {
			      // $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
			      // $un = $bdd -> query($re);
			      // $untr = $un->fetch();

			      $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
			      $req_maj_don = $bdd -> prepare($req_majd);
			      //$req_maj_don ->bindParam(':montant_transaction', $amount);
			      $req_maj_don ->bindParam(':statut_transaction', $statutt);
			      $req_maj_don ->bindParam(':id_transaction', $elt['id_transaction']);
			      $req_maj_don ->execute();

			      $lib = explode('-', $elt['libelle_transaction']);

			      if (isset($elt['id_user']) AND $elt['montant_transaction'] >= 2000 AND end($lib) !== "don" AND $statutt == "SUCCESS") {
			        $re = "SELECT * FROM walletB WHERE id_user = ".$elt['id_user'];
			        $rq = $bdd -> query($re);
			        $wall = $rq -> fetch();

			        $solde = $wall['solde_walletB'] + $elt['montant_transaction']*0.05;

			        $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
			        $req_msold = $bdd -> prepare($req_msol);
			        $req_msold ->bindParam(':sold', $solde);
			        $req_msold ->bindParam(':id', $wall['id_walletB']);
			        $req_msold -> execute();
			      }
				  $req_maj_don->closeCursor();
			    }
					// $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
				  // $req_maj_don = $bdd -> prepare($req_majd);
				  // //$req_maj_don ->bindParam(':montant_transaction', $amount);
				  // $req_maj_don ->bindParam(':statut_transaction', $statutt);
				  // $req_maj_don ->bindParam(':id_transaction', $elt['id_transaction']);
				  //  $req_maj_don ->execute();

					 //sms
					//  $curl1 = curl_init("http://localhost:14013/cgi-bin/sendsms?username=svam&password=svam&text=bonjour&from=AFREEPLAY&to=".$_GET['n']);
					//
			 		//  // echo $rdfXML;
			 		// // curl_setopt($curl1, CURLOPT_PORT, "6655");
			 		// curl_setopt($curl1, CURLOPT_FOLLOWLOCATION, TRUE);
			 		// // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
			 		// curl_setopt($curl1, CURLOPT_TIMEOUT, 130);
			 		// //curl_setopt($curl, CURLOPT_HEADER, 1);
			 		// curl_setopt($curl1, CURLOPT_CUSTOMREQUEST, 'GET');
			 		// curl_setopt($curl1, CURLOPT_RETURNTRANSFER, TRUE);
			 		// curl_setopt($curl1, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
					//
			 		//  $first_response = curl_exec($curl1);
			 		//  // var_dump($first_response);
			 		//  $err = curl_error($curl1);
			 		//  // curl_close($curl1);
					//
			 		//  if ($err) {
			 		// 	  echo "cURL Error #:" . $err;
			 		// 	} else {
					//
					// 	}



				} else {
					$statutt = "FAIL";
					$req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
				  $req_maj_don = $bdd -> prepare($req_majd);
				  //$req_maj_don ->bindParam(':montant_transaction', $amount);
				  $req_maj_don ->bindParam(':statut_transaction', $statutt);
				  $req_maj_don ->bindParam(':id_transaction', $elt['id_transaction']);
				   $req_maj_don ->execute();
				   $req_maj_don->closeCursor();
				}
				
				echo json_encode($rep);


			}
	}

}

function initpay($amount){

 require 'connexion.php';
	$retk = "SELECT * FROM tokenTG ORDER BY id_tokenTG DESC";
	$rqtk = $bdd -> query($retk);
	$tokenTG = $rqtk -> fetch();
	$nbb = $tokenTG['num_tokenTG']+1;
	// $nbb = 22;
	$input = $nbb.":vasconexprod:k6BvN5RUb5eNw82C";

	$key = hex2bin("746C633132333435746C633132333435746C633132333435746C633132333435");
	$encodedEncryptedData = base64_encode(@openssl_encrypt($input, "AES-256-CBC", $key, OPENSSL_RAW_DATA));

	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => "http://5.9.195.252/afreekaplay/gest/paietogoNew.php",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 130,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "n=".$_GET['n']."&ref=".$_GET['ref']."&token=".$encodedEncryptedData."&p=".$amount,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/x-www-form-urlencoded"
		],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #:" . $err;
	} else {
		$req_ajt = "INSERT INTO tokenTG (token_tokenTG, num_tokenTG) VALUES (:token_tokenTG, :num_tokenTG)";
			$req_aj_tok = $bdd -> prepare($req_ajt);
			$req_aj_tok ->bindParam(':token_tokenTG', $encodedEncryptedData);
			$req_aj_tok ->bindParam(':num_tokenTG', $nbb);
			$req_aj_tok ->execute();
		$respons = json_decode($response);
		// echo $respons['status']." 1<br>2 ";
		// echo $respons->status;
		if ($respons->status == 99 AND $respons->message== "Invalid authozition") {
			// echo "on reprends";
			initpay($amount);

		} else {
			echo $response;

		}
		$req_aj_tok->closeCursor();
	}
			// var_dump($first_response);
 
}
 ?>
