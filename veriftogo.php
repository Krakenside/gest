<?php
	header("Access-Control-Allow-Origin: *");
	set_time_limit(0);
// var_dump($_POST);
require 'connexion.php';



$opt = explode('-',$_GET['ref']);
if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
  $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
  $amount = $elt['montant_rechargement'];

	$re2 = "SELECT * FROM walletP WHERE id_walletP = '".$elt['id_walletP']."'";
	$un2 = $bdd -> query($re2);
	$wal = $un2 -> fetch();

	$solde = (isset($wal['solde_walletP'])) ? $wal['solde_walletP'] : 0;
	$tabl = "rechargement";
	$idTrsx = $elt['id_rechargement'];

} else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {
  $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
	$tabl = "forfaitTrsx";

	$re = "SELECT * FROM forfaitStream WHERE id_forfaitStream = ".$elt['id_forfaitStream'];
	$un = $bdd -> query($re);
	$eltw = $un->fetch();
  $amount = $eltw['prix_forfaitStream'];
	$idTrsx = $elt['id_forfaitTrsx'];


} else {
	$re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
	$un = $bdd -> query($re);
	//$nbuser = $un->rowCount();
	$elt = $un->fetch();

	 // var_dump($elt);
	$amount = $elt["montant_transaction"];
	$idTrsx = $elt['id_transaction'];

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
	$first_response["status"] = "0";
	$first_response['details']['status'] = "0";
	echo json_encode($first_response);
	// $rep["statut"] = "INITIATED";/
} else {


function verifyTG($id, $op) {

	require 'connexion.php';
	$retk = "SELECT * FROM tokenTG ORDER BY id_tokenTG DESC";
	$rqtk = $bdd -> query($retk);
	$tokenTG = $rqtk -> fetch();
	$nbb = $tokenTG['num_tokenTG']+1;
	$input = $nbb.":vasconexprod:k6BvN5RUb5eNw82C";

	$key = hex2bin("746C633132333435746C633132333435746C633132333435746C633132333435");
	$encodedEncryptedData = base64_encode(@openssl_encrypt($input, "AES-256-CBC", $key, OPENSSL_RAW_DATA));



	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => "http://5.9.195.252/afreekaplay/gest/veriftogoNew.php",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 130,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "ref=".$_GET['ref']."&token=".$encodedEncryptedData,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/x-www-form-urlencoded"
		],
	]);

	$first_response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #:" . $err;
	} else {

		 // $v2 = json_encode($headers);
		 $ddj = date ('Y-m-d H:i:s');
		 $lib = "Moov verify --- ".$first_response."  ---------  ";
		 //.$rdfXML;
		 //echo $lib;
		 $req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
		 $req_aj_tel = $bdd -> prepare($req_ajt);
		 $req_aj_tel ->bindParam(':date_log', $ddj);
		 $req_aj_tel ->bindParam(':libelle_log', $lib);
		 $req_aj_tel ->execute();
		 $req_ajt = "INSERT INTO tokenTG (token_tokenTG, num_tokenTG) VALUES (:token_tokenTG, :num_tokenTG)";
 			$req_aj_tok = $bdd -> prepare($req_ajt);
 			$req_aj_tok ->bindParam(':token_tokenTG', $encodedEncryptedData);
 			$req_aj_tok ->bindParam(':num_tokenTG', $nbb);
 			$req_aj_tok ->execute();

		$reto = json_decode($first_response, true);

		if ($reto['status'] == 99 AND ($reto['message']== "Invalid authozition" OR $reto['message']== "No record found")) {
			// echo "on reprends";
			verifyTG($id, $op);

		} else {
			if ($reto['details']['status'] == '0') {
				 // echo $reto['details']['mrchreference'];
				// $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
				// $un = $bdd -> query($re);
				// //$nbuser = $un->rowCount();
				// $elt = $un->fetch();
				$statutt = "SUCCESS";

				if (isset($op) AND $op=='AFPRCH') {

					$rer = "SELECT * FROM rechargement WHERE id_rechargement = ".$id;
				  $unr = $bdd -> query($rer);
				  $eltr = $unr -> fetch();

					$req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
					$req_maj_don = $bdd -> prepare($req_majd);

					$req_maj_don ->bindParam(':statut_rechargement', $statutt);
					$req_maj_don ->bindParam(':id_rechargement', $id);
					$req_maj_don ->execute();

					$req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
					$req_maj_wal = $bdd -> prepare($req_majw);
					$amountAj = $solde + $eltr['montant_rechargement'];

					$req_maj_wal ->bindParam(':solde_walletP', $amountAj);
					$req_maj_wal ->bindParam(':id_walletP', $eltr['id_walletP']);
					$req_maj_wal ->execute();

				} else if (isset($op) AND $op=='AFPFOR') {
					$ref2 = "SELECT * FROM forfaitTrsx WHERE id_forfaitTrsx = '".$id."'";
				  $unf2 = $bdd -> query($ref2);
				  $eltf2 = $unf2 -> fetch();

					$reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$eltf2['id_forfaitStream'];
					// echo $reqF;
					$for = $bdd -> query($reqF);
					$forfait = $for->fetch();
					$dateAuj =  date ('Y-m-d H:i:s');
					$nbScd = $forfait['temps_forfaitStream']*86400;
					$datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
					// Moyen de paiement 1 mobile mo, 0 Airtime
					$renouv = 0;
					$moyenPaie = "MM";

					$req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :id_user, :id_forfaitStream)";
						$req_aj_uf = $bdd -> prepare($req_ajuf);
						$req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
						$req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
						$req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
						$req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
						$req_aj_uf ->bindParam(':id_user', $eltf2["id_user"]);
						$req_aj_uf ->bindParam(':id_forfaitStream', $forfait['id_forfaitStream']);
						$req_aj_uf ->execute();

					$req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
					$req_maj_don = $bdd -> prepare($req_majd);

					$req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
					$req_maj_don ->bindParam(':id_forfaitTrsx', $id);
					$req_maj_don ->execute();

				}  else {
					$req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
					$req_maj_don = $bdd -> prepare($req_majd);
					//$req_maj_don ->bindParam(':montant_transaction', $amount);
					$req_maj_don ->bindParam(':statut_transaction', $statutt);
					$req_maj_don ->bindParam(':id_transaction', $id);
					$req_maj_don ->execute();
				}


				// echo "excec";
			}
				echo $first_response;

		}


 // else if ($reto['details']['status'] == '') {
	// // code...
	}



}

verifyTG($idTrsx, $opt[0]);

}
 ?>
