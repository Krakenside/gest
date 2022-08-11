<?php
	header("Access-Control-Allow-Origin: *");
	set_time_limit(0);
  // var_dump($_GET);
require 'connexion.php';



$gui = 1;
$pwdagnt = "0000";
if ($_GET['rsx'] == "OMGN") {
//  $codeServ = "";
  $codeServ = "PAIEMENTMARCHANDOMPAYGN";
	$agent = "CONEX2831";
	$login = "628807777";
	$user = "70233ce6be7f59aa3b1314e2beef418c";
	$pwd = "734f7d01911f4f3b64ea4dac0a436f1d";
} else if($_GET['rsx'] == "MOMOGN"){
//  $codeServ = "PAIEMENTMARCHAND_MTN_CI";
  $codeServ = "PAIEMENTMARCHAND_MTN_GN";
	$agent = "CONEX2831";
	$login = "628807777";
	$user = "70233ce6be7f59aa3b1314e2beef418c";
	$pwd = "734f7d01911f4f3b64ea4dac0a436f1d";
} else if($_GET['rsx'] == "OMCI"){
  $codeServ = "PAIEMENTMARCHANDOMPAYCI";
	$agent = "VASCO2868";
	$login = "22964008585";
	$gui = 0;
	$user = "EFB77C5C17844580828661841244B2F7A980BA648C4944952B25C68E18CC948F";
	$pwd = "4B4DF11FFCDB700191FB8437B5923DD0DD14538E5F36C4CAF9F2657C3DD32B83";

} else if($_GET['rsx'] == "MOMOCI"){
  $codeServ = "PAIEMENTMARCHAND_MTN_CI";
	$agent = "VASCO2868";
	$login = "22964008585";
	$gui = 0;
	$user = "EFB77C5C17844580828661841244B2F7A980BA648C4944952B25C68E18CC948F";
	$pwd = "4B4DF11FFCDB700191FB8437B5923DD0DD14538E5F36C4CAF9F2657C3DD32B83";

} else if($_GET['rsx'] == "FLOOZCI"){
  $codeServ = "PAIEMENTMARCHAND_MOOV_CI";
	$agent = "VASCO2868";
	$login = "22964008585";
	$gui = 0;
	$user = "EFB77C5C17844580828661841244B2F7A980BA648C4944952B25C68E18CC948F";
	$pwd = "4B4DF11FFCDB700191FB8437B5923DD0DD14538E5F36C4CAF9F2657C3DD32B83";

} else if($_GET['rsx'] == "OMBF"){
  $codeServ = "BF_CASHIN_OM_PART";
	$agent = "BFVAS1933";
	$login = "22964008585";
	$gui = 0;
	$user = "EFB77C5C17844580828661841244B2F7A980BA648C4944952B25C68E18CC948F";
	$pwd = "4B4DF11FFCDB700191FB8437B5923DD0DD14538E5F36C4CAF9F2657C3DD32B83";

} else if($_GET['rsx'] == "OMML"){
  $codeServ = "ML_PAIEMENTMARCHAND_OM_TP";
	$agent = "VSXML4921";
	$login = "57480777";
	$gui = 0;
	$user = "D13EBA3FE54E24CC113011C12021E67E64EF44261CC7FBAFB45CDDE68C178327";
	$pwd = "315D7706E8BE565F5954F6A537A4837EB356A2CDD2B1F2A69678A4D74CCBC68C";

} else if($_GET['rsx'] == "FLOOZML"){
  $codeServ = "ML_PAIEMENTMARCHAND_MOOV_TP";
	$agent = "VSXML4921";
	$login = "57480777";
	$gui = 0;
	$user = "D13EBA3FE54E24CC113011C12021E67E64EF44261CC7FBAFB45CDDE68C178327";
	$pwd = "315D7706E8BE565F5954F6A537A4837EB356A2CDD2B1F2A69678A4D74CCBC68C";

}
else if($_GET['rsx'] == "OMSN"){
  $codeServ = "PAIEMENTMARCHANDOM";
	$agent = "VSXSN16917";
	$login = "674807777";
	$gui = 0;
	$user = "1bbd12343b8438f93214d7b6b504f6ee2373ccf34edff031014a705df6ad5b94";
	$pwd = "b3a50651897fb27437e5077bf55a3ca1e1bdf96ce8b1c0375354e15d2a5e997f";

}
else if($_GET['rsx'] == "EMSN"){
  $codeServ = "PAIEMENTMARCHANDEM";
	$agent = "VSXSN16917";
	$login = "674807777";
	$gui = 0;
	$user = "1bbd12343b8438f93214d7b6b504f6ee2373ccf34edff031014a705df6ad5b94";
	$pwd = "b3a50651897fb27437e5077bf55a3ca1e1bdf96ce8b1c0375354e15d2a5e997f";

}
else if($_GET['rsx'] == "FREESN"){
  $codeServ = "PAIEMENTMARCHANDTIGO";
	$agent = "VSXSN16917";
	$login = "674807777";
	$gui = 0;
	$user = "1bbd12343b8438f93214d7b6b504f6ee2373ccf34edff031014a705df6ad5b94";
	$pwd = "b3a50651897fb27437e5077bf55a3ca1e1bdf96ce8b1c0375354e15d2a5e997f";

}
else if($_GET['rsx'] == "WAVESN"){
  $codeServ = "SNPAIEMENTWAVE";
	$agent = "VSXSN16917";
	$login = "674807777";
	$gui = 0;
	$user = "1bbd12343b8438f93214d7b6b504f6ee2373ccf34edff031014a705df6ad5b94";
	$pwd = "b3a50651897fb27437e5077bf55a3ca1e1bdf96ce8b1c0375354e15d2a5e997f";

}
else if($_GET['rsx'] == "WIZALLSN"){
  $codeServ = "SN_PAIEMENTMARCHAND_WIZALL";
	$agent = "VSXSN16917";
	$login = "674807777";
	$gui = 0;
	$user = "1bbd12343b8438f93214d7b6b504f6ee2373ccf34edff031014a705df6ad5b94";
	$pwd = "b3a50651897fb27437e5077bf55a3ca1e1bdf96ce8b1c0375354e15d2a5e997f";

}
else if($_GET['rsx'] == "MOMOCM"){
  $codeServ = "PAIEMENTMARCHAND_MTN_CM";
	$agent = "VXCMR6447";
	$login = "574807777";
	$gui = 0;
	$user = "3CED9BA7E7675952241701C97F015D6DEAC4FA197C6732DA5BF2BE46C536F74B";
	$pwd = "F41A61A12B955715C2E48E7BAE91A9C28DE8CFFD7E3E881B0EBA5AF0345F0A00";

}
else if($_GET['rsx'] == "OMCM"){
  $codeServ = "CM_PAIEMENTMARCHAND_OM_TP";
	$agent = "VXCMR6447";
	$login = "574807777";
	$pwdagnt = "Hw99GaaqfV";
	$gui = 0;
	$user = "3CED9BA7E7675952241701C97F015D6DEAC4FA197C6732DA5BF2BE46C536F74B";
	$pwd = "F41A61A12B955715C2E48E7BAE91A9C28DE8CFFD7E3E881B0EBA5AF0345F0A00";

}
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
	// echo $lpays['id_devise'].' == '.$eltw['id_devise'].'<br>';
	// if ($lpays['id_devise'] == $eltw['id_devise']) {
	// 	$amount = $elt['montant_rechargement'];
	// } else {
	// 	$reT = "SELECT * FROM taux WHERE from_taux = ".$lpays['id_devise']." AND to_taux = ".$eltw['id_devise'];
	// 	$Tx = $bdd -> query($reT);
	// 	$taux = $Tx->fetch();
	// 	$amount = $elt['montant_rechargement']/$taux['taux_taux'];
	// }
	$idTrsx = $elt['id_rechargement'];

}
else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {
  $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
	$libT = "Forfait";

	$re = "SELECT * FROM forfaitStream WHERE id_forfaitStream = ".$elt['id_forfaitStream'];
	$un = $bdd -> query($re);
	$eltw = $un->fetch();

	if($gui == 1){


		$reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =2";
		$ta = $bdd -> query($reqT);
		$taux = $ta->fetch();
		$tx = $taux['taux_taux'];
		//$amount = $sons['prix_son']*$tx;
	}

  $amount = $eltw['prix_forfaitStream']*$tx;

	$idTrsx = $elt['id_forfaitTrsx'];

}
else {
  $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
  $amount = $elt['montant_transaction'];
	$libT = "Achat";
	$optwal = "";

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
	$idTrsx = $elt['id_transaction'];
}

// echo $amount.' - '.$taux['taux_taux'].'<br>';
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
}
else {

	if ($_GET['rsx'] == "OMGN") {


		//________

        $datee = date ('Y-m-d H:i:s');

    // var_dump($datee);


      $re = "SELECT * FROM tokenOM ORDER BY id_tokenOM DESC";
      $rq = $bdd -> query($re);
      $token = $rq -> fetch();

      if(!isset($token['expire_tokenOM']) OR $datee > $token['expire_tokenOM']){

        // $dateTime = new DateTime($datee);
        // $dateTime->modify('+60 minutes');
        // $affich_el[0]=1;
        $my_date_time=time("Y-m-d H:i:s");

        $my_new_date_time=$my_date_time+3600;
        $dateTime=date("Y-m-d H:i:s",$my_new_date_time);

            // var_dump($dateTime);
            // $dat = $dateTime->date;
            $curl = curl_init("https://api.orange.com/oauth/v3/token");


           $headers = ["Authorization: Basic SUtFOHRvQTVpS2hxREk4ZWNiMTZSaDdrYlNjaGlSejA6cndrMjRtSnppYklJOE1QbQ==",
        "Content-Type: application/x-www-form-urlencoded"];
           $rdfXML = "grant_type=client_credentials";

            // echo $rdfXML;

           curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
           // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
           curl_setopt($curl, CURLOPT_TIMEOUT, 130);
           curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
           //curl_setopt($curl, CURLOPT_HEADER, 1);
           curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
           curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $first_response = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
               echo "cURL Error #:" . $err;
             } else {
               $first_response = json_decode($first_response);
               // var_dump($first_response) ;
             }


        $req_ajt = "INSERT INTO tokenOM (token_tokenOM, expire_tokenOM) VALUES (:token_tokenOM, :expire_tokenOM)";
          $req_aj_tok = $bdd -> prepare($req_ajt);
          $req_aj_tok ->bindParam(':expire_tokenOM', $dateTime);
          $req_aj_tok ->bindParam(':token_tokenOM', $first_response->access_token);
          $req_aj_tok ->execute();

          // $affich_e = array_shift($affich_el);
          // $affich_el['reference']=$ref;
          //$affich_el['reference']=$_POST['numero'];
            $key = $first_response->access_token;
        } else {
          $first_response['access_token'] = $token['token_tokenOM'];
          $key = $token['token_tokenOM'];
        }

				//________

     // echo $key;

		if ($key !=="") {
			//	var_dump($first_response);
			  //$first_respons = json_decode($first_response);
				// $timestamp = time();
			//	$key = $first_respons->access_token;
				// $hmac = hash_hmac('sha256', $timestamp, $key);
				   // var_dump($first_respons);

				 $curl = curl_init("https://api.orange.com/orange-money-webpay/gn/v1/webpayment");

				 $notifUrl = "https://www.afreekaplay.com/wait?ref=".$_GET['ref'];
				$headers = ["Accept: application/json","Authorization: Bearer ".$key,"Content-Type: application/json"];
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
							$req_ajt = "INSERT INTO transactionOM (status_transactionOM, message_transactionOM, payToken_transactionOM, notifToken_transactionOM, libelle_transactionOM, id_transaction) VALUES (:status_transactionOM, :message_transactionOM, :payToken_transactionOM, :notifToken_transactionOM, :libelle_transactionOM, :id_transaction)";
				        $req_aj_tok = $bdd -> prepare($req_ajt);
				        $req_aj_tok ->bindParam(':status_transactionOM', $first_respons2->status);
				        $req_aj_tok ->bindParam(':message_transactionOM', $first_respons2->message);
				        $req_aj_tok ->bindParam(':payToken_transactionOM', $first_respons2->pay_token);
				        $req_aj_tok ->bindParam(':notifToken_transactionOM', $first_respons2->notif_token);
				        $req_aj_tok ->bindParam(':libelle_transactionOM', $libT);
				        $req_aj_tok ->bindParam(':id_transaction', $idTrsx);
				        $req_aj_tok ->execute();
						}


					}
			}


	}
	else {

		 $curl = curl_init("https://api.gutouch.com/dist/api/touchpayapi/v1/".$agent."/transaction?loginAgent=".$login."&passwordAgent=".$pwdagnt);


		$headers[] = "Content-Type: application/json";
		$rdfXML = "{\n\t\"idFromClient\": \"".$_GET['ref']."\",\n\t\"additionnalInfos\": {\n\t\t\"recipientEmail\": \"\",\n\t\t\"recipientFirstName\": \"".$_GET['nm']."\",\n\t\t\"recipientLastName\": \"\",\n\t\t\"destinataire\": \"".$_GET['n']."\"\n\t},\n\t\"amount\": ".$amount.",\n\t\"callback\": \"https://www.afreekaplay.com/gest/kcabllac.php".$optwal."\",\n\t\"recipientNumber\": \"".$_GET['n']."\",\n\t\"serviceCode\": \"".$codeServ."\"\n}";

		// echo $rdfXML;

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl, CURLOPT_TIMEOUT, 130);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
		//curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_USERPWD, $user.':'.$pwd);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
		 $first_response = curl_exec($curl);
	}



 //var_dump($first_response);
//666125099
}
 ?>
