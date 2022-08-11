<?php
	header("Access-Control-Allow-Origin: *");
	set_time_limit(0);
  // var_dump($_POST);

require 'connexion.php';

$datee = date ('Y-m-d H:i:s');
$date2 = date ('Y-m-d\TH:i:s');
$mltpl = 1;
$tx = 1;
if ($_GET['t'] == "app") {
	$_GET['ref'] = $_POST['ref'];
} else {
	$_POST['n'] = $_GET['n'];
	$_POST['ref'] = $_GET['ref'];
}

$indic = substr($_POST['n'], 0, 3);
// echo $indic;

if($indic !== '229') {

	$indic2 = substr($_POST['n'], 0, 2);
	if ($indic2 == '90' OR $indic2 == '91' OR $indic2 == '97' OR $indic2 == '96' OR $indic2 == '61' OR $indic2 == '62' OR $indic2 == '66' OR $indic2 == '67' OR $indic2 == '69' OR $indic2 == '56') {
		// $_POST['rsx'] == "MOMOBN";
		$codeServ = "MTNMoMo";
		$mltpl = 1;
	} elseif ($indic2 == '94' OR $indic2 == '95' OR $indic2 == '98' OR $indic2 == '99' OR $indic2 == '60' OR $indic2 == '63' OR $indic2 == '64' OR $indic2 == '68') {
		// $_POST['rsx'] == "MOOVBN";
		$codeServ = "MOOV";
		$mltpl = 1;
	}


	$_POST['n'] = '229'.$_POST['n'];


} else {
	$indic2 = substr($_POST['n'], 3, 2);
	// echo $indic2;
	if ($indic2 == '90' OR $indic2 == '91' OR $indic2 == '97' OR $indic2 == '96' OR $indic2 == '61' OR $indic2 == '62' OR $indic2 == '66' OR $indic2 == '67' OR $indic2 == '69' OR $indic2 == '56') {
		// $_POST['rsx'] == "MOMOBN";
		$codeServ = "MTNMoMo";
		$mltpl = 1;
	} elseif ($indic2 == '94' OR $indic2 == '95' OR $indic2 == '98' OR $indic2 == '99' OR $indic2 == '60' OR $indic2 == '63' OR $indic2 == '64' OR $indic2 == '68') {
		// $_POST['rsx'] == "MOOVBN";
		$codeServ = "MOOV";
		$mltpl = 1;
	}
}

// var_dump($codeServ);


$opt = explode('-',$_GET['ref']);
if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
  $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
  $amount = $elt['montant_rechargement'];
	$tabl = "rechargement";
	$idTrsx = $elt['id_rechargement'];
	// $un->closeCursor();
} else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {
  $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();
	$tabl = "forfaitTrsx";
	$un->closeCursor();

	$re = "SELECT * FROM forfaitStream WHERE id_forfaitStream = ".$elt['id_forfaitStream'];
	$un = $bdd -> query($re);
	$eltw = $un->fetch();
  $amount = $eltw['prix_forfaitStream'];
	$idTrsx = $elt['id_forfaitTrsx'];
	// $un->closeCursor();


} else {
  $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
  $un = $bdd -> query($re);
  $elt = $un -> fetch();

	$tabl = "transaction";

	$lib = explode('-', $elt['libelle_transaction']);
	$tail = sizeof($lib);

	if (end($lib) == "don") {
		$id = $lib[$tail-2];
	} else {
		$id = $lib[$tail-1];
		//var_dump($id);

	}
	if (end($lib) == "don") {
		$amount = $elt['montant_transaction'];

	} else {

		$re = "SELECT * FROM ".$lib[0]." WHERE ".$lib[0].".id_".$lib[0]." = ".$id;
		 //echo $re;
		$son = $bdd -> query($re);
		//$nbson = $son -> rowCount();
		$sons = $son->fetch();



		$amount = $sons['prix_'.$lib[0]]*$mltpl;
	}
	$idTrsx = $elt['id_transaction'];
	$son->closeCursor();
	$un->closeCursor();
}



if ($amount == 0) {
	$statutt = "SUCCESS";
	$req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
	$req_maj_don = $bdd->prepare($req_majd);
	//$req_maj_don ->bindParam(':montant_transaction', $amount);
	$req_maj_don->bindParam(':statut_transaction', $statutt);
	$req_maj_don->bindParam(':id_transaction', $elt['id_transaction']);
	$req_maj_don->execute();
	//header("Location:https://afreekaplay.com/callback?ref=".$_GET['ref']);
	$rep["status"] = "INITIATED";
	echo json_encode($rep);
	// $rep["statut"] = "INITIATED";/
	$req_maj_don->closeCursor();
} else {



		 $curl = curl_init("https://api.mobilecrosspay.com/");

		$headers = ["Content-Type: application/json","SP-Key: R2dnfk4jYh3dhuxP@Zj"];
		$rdfXML = "{\n\t\"requestId\": \"".$_POST['ref']."\",\n\t\"type\": \"DEBIT\",\n\t\"accountRef\": \"".$_POST['n']."\",\n\t\"bankCode\": \"".$codeServ."\",\n\t\"details\": {\n\t\t\"amount\": \"".$amount."\",\n\t\t\"serviceCode\": \"VASCONEX_STR-APP\",\n\t\t\"currencyCode\": \"XOF\"\n\t} \n}";
//		,\n\t\"submitTime\": \"".$date2."\"

		 //echo $rdfXML;

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($curl, CURLOPT_TIMEOUT, 130);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		 $first_response = curl_exec($curl);
		 $err = curl_error($curl);
		 // curl_close($curl1);

		 if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
				$v1 = json_encode($first_response);
				$lib = "NIVEAU 2 (PAIECROSS BENIN) --- ".$v1;
				// echo $lib;
				$req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
				$req_aj_tel = $bdd -> prepare($req_ajt);
				$req_aj_tel ->bindParam(':date_log', $datee);
				$req_aj_tel ->bindParam(':libelle_log', $lib);
				//$req_aj_tel ->execute();

	 		  //echo $first_response;
	 		 // echo 'ooooo';
			 $first_response3 = explode('resultCode',$first_response);
			 $rep["resultCode"] = $first_response3[1][2];
			 // var_dump($rep["resultCode"]);
				 if(isset($rep['resultCode'])) {
				   // echo 'o -'.$_POST['requestId'];
				   $statutt = ($rep['resultCode'] == '0') ? 'SUCCESS' : 'FAIL';


						 try
						 {
							 //$old_errlevel = error_reporting(0);
					 	 	$bdd->query("SELECT 1");

						 	}
								catch (Exception $e) {
									try{
				              //$bdd =    new PDO ('mysql:host=localhost; dbname=afreekaplay', 'afreekaplay', '*_VF_G9-e*Dq', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
				              $bdd =    new PDO ('mysql:host=localhost; dbname=afrekply', 'RuT', 'projetafricain', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
											}
				           catch (Exception $e) {
				           echo "Erreur de connexion à la base de données. <br />";
				           die( 'Erreur : '.$e->getmessage());

				           }

								}
								//error_reporting($old_errlevel);

				   $req_majt = "UPDATE ".$tabl." SET statut_".$tabl."=:statut_".$tabl." WHERE id_".$tabl."=:id_".$tabl;
				   $req_maj_tr = $bdd -> prepare($req_majt);
				   //$req_maj_don ->bindParam(':montant_transaction', $amount);
				   //$req_maj_don ->bindParam(':statut_'.$tabl, $statutt);
				   //$req_maj_don ->bindParam(':id_'.$tabl, $idTrsx);
				   $req_maj_tr ->bindParam(':statut_transaction', $statutt);
				   $req_maj_tr ->bindParam(':id_transaction', $idTrsx);
				   $req_maj_tr ->execute();
				   $req_maj_tr->closeCursor();

					 if ($statutt == "SUCCESS") {
						 if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

  						 $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
  				     $un2 = $bdd -> query($re);
  				     $wal = $un2 -> fetch();
  				     $solde = $wal['solde_walletP'];

  						 $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
  				     $req_maj_wal = $bdd -> prepare($req_majw);
  				     $amountAj = $solde + $montant;

  				     $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
  				     $req_maj_wal ->bindParam(':id_walletP', $req_maj_wal['id_walletP']);
  				     $req_maj_wal ->execute();
					 $req_maj_wal->closeCursor();

  					 }

             if (isset($elt['id_user']) AND $elt['montant_transaction'] >= 2000 AND end($lib) !== "don"AND $tabl == "transaction") {
               $re = "SELECT * FROM walletB WHERE id_user = ".$elt['id_user'];
               $rq = $bdd -> query($re);
               $wall = $rq -> fetch();

               $solde = $wall['solde_walletB'] + $elt['montant_transaction']*0.05;

               $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
               $req_msold = $bdd -> prepare($req_msol);
               $req_msold ->bindParam(':solde_walletB', $solde);
               $req_msold ->bindParam(':id', $wall['id_walletB']);
               $req_msold -> execute();
			   $req_msold->closeCursor();

             }
						 else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

							$dateAuj =  date ('Y-m-d H:i:s');

							$re3 = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$_GET['ref']."'";
							$un3 = $bdd -> query($re3);
							$untr3 = $un3 -> fetch();

							$reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$untr3['id_forfaitStream'];
							// echo $reqF;
							$for = $bdd -> query($reqF);
							$forfait = $for->fetch();
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
								$req_aj_uf ->bindParam(':id_user', $untr3["id_user"]);
								$req_aj_uf ->bindParam(':id_forfaitStream', $untr3['id_forfaitStream']);
								$req_aj_uf ->execute();

								$un3->closeCursor();
								$req_aj_uf->closeCursor();

						}
					 }




				     $rep['status'] = $statutt;
				     echo json_encode($rep);

				//
				//
				} else {
				  $rep['status'] = 'FAIL';
				  echo json_encode($rep);

				}
			}





 //var_dump($first_response);
//666125099
}
