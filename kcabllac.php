<?php
header("Access-Control-Allow-Origin: *");

 // var_dump ($_POST);
 set_time_limit(0);
 ini_set('memory_limit', '512M');

 require_once __DIR__.'/../vendor/autoload.php';
   require 'connexion.php';
   //7780
//$don = "";

$_POST = (sizeof($_GET) !== 0) ? $_GET : $_POST;
 // var_dump($_POST); //===================
$ddj = date ('Y-m-d H:i:s');
$dateAuj = date ('Y-m-d H:i:s');

$v1 = json_encode($_POST);
$lib = "NIVEAU 1 --- ".$v1;
//echo $lib;
$req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
$req_aj_tel = $bdd -> prepare($req_ajt);
$req_aj_tel ->bindParam(':date_log', $ddj);
$req_aj_tel ->bindParam(':libelle_log', $lib);
$req_aj_tel ->execute();

if (isset($_POST['referenceNumber'])) {
  //paiement pro
  $statutt = ($_POST['responsecode'] == 0) ? 'SUCCESS' : 'FAIL' ;
  $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_POST['referenceNumber']."'";
  $un = $bdd -> query($re);
  $untr = $un->fetch();
  $libTrans = $untr['reference_transaction'];
  $idTrans = $untr['id_transaction'];

  //var_dump($re);
  //var_dump($untr);
  //$amount = intval($_POST['amount']);

  $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
  $req_maj_don = $bdd -> prepare($req_majd);
  //$req_maj_don ->bindParam(':montant_transaction', $amount);
  $req_maj_don ->bindParam(':statut_transaction', $statutt);
  $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
  $req_maj_don ->execute();

  if ($statutt == "SUCCESS") {
    if (isset($untr['id_user'])) {
      $requet = $bdd -> query("SELECT * FROM user WHERE user.id_user =".$untr['id_user']);
      $colon = $requet -> fetch();
    } else {
      $requet = $bdd -> query("SELECT * FROM user WHERE user.telephone_user LIKE '%".$untr['telephone_transaction']."'");
      $colon = $requet -> fetch();
    }

    if (isset($colon['id_user'])) {
      //echo "l'id est".$colon['id_user'];
      $reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$colon['id_user']." AND libelle_user_forfaitStream ='promo1'";
      // echo $reqF;
      $for = $bdd -> query($reqF);
      $forfait = $for->rowCount();
      if ($forfait == 0) {
        $nbScd = 86400;
        $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
        $libForf = "promo1";
        // Moyen de paiement 1 mobile mo, 0 Airtime
        $renouv = 0;
        $idForfait = 1;
        $moyenPaie = "MM";

        $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
        $req_aj_uf = $bdd -> prepare($req_ajuf);
        $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
        $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
        $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
        $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
        $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
        $req_aj_uf ->bindParam(':id_user', $colon["id_user"]);
        $req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
        $req_aj_uf ->execute();
      }
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

  header("Location:https://afreekaplay.com/callback?ref=".$_POST['referenceNumber']);

}
else if(isset($_POST['transaction_id'])){
  //kkiapay
  //$public_key = "b9874f90c5fa11eab47ae11769a4e527";
  $public_key = "f20269e8d632191f7d495a9746a3b6b354fb076a";

  //$private_key = "pk_b98776a1c5fa11eab47ae11769a4e527";
  $private_key = "pk_edb2cee9ce2239e465d5f7148d0707f669f45d6c4c040e8844039db1dc48a6db";

  //$secret = "sk_b98776a2c5fa11eab47ae11769a4e527";
  $secret = "sk_6170eb16b6bb00e8452d221f87ffb3ab93e50c000353e994287512ec5b71ab43";


  $transaction_id = $_POST['transaction_id'];

  $kkiapay = new Kkiapay\Kkiapay($public_key, $private_key, $secret);
  $verify = $kkiapay->verifyTransaction($transaction_id);

  if(isset($_POST['transaction_id']) && !empty($_POST['transaction_id'])){

    $statutt = $verify  -> status;
    $amount = $verify  -> amount;
    $ref = $verify  -> state;
      // var_dump($transaction_id);
      // var_dump($verify);

    $opt = explode('-',$ref);
    if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

      $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
      $un = $bdd -> query($re);
      $untr = $un -> fetch();

      $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
      $un2 = $bdd -> query($re);
      $wal = $un2 -> fetch();
      $solde = (isset($wal['solde_walletP'])) ? $wal['solde_walletP'] : 0;

      $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
      $req_maj_don = $bdd -> prepare($req_majd);

      $req_maj_don ->bindParam(':statut_rechargement', $statutt);
      $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
      $req_maj_don ->execute();
      if ($statutt == "SUCCESS") {
        $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
        $req_maj_wal = $bdd -> prepare($req_majw);
        $amountAj = $solde + $untr['montant_rechargement'];

        $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
        $req_maj_wal ->bindParam(':id_walletP', $wal['id_walletP']);
        $req_maj_wal ->execute();
      }

    } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

      $dateAuj =  date ('Y-m-d H:i:s');

      $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$ref."'";
      $un = $bdd -> query($re);
      $untr = $un -> fetch();

      if ($statutt == "SUCCESS") {
        $reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$untr['id_forfaitStream'];
        // echo $reqF;
        $for = $bdd -> query($reqF);
        $forfait = $for->fetch();
        $nbScd = $forfait['temps_forfaitStream']*86400;
        $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
        $libForf = "souscription";
        // Moyen de paiement 1 mobile mo, 0 Airtime
        $renouv = 0;
        $moyenPaie = "MM";

        $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
          $req_aj_uf = $bdd -> prepare($req_ajuf);
          $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
          $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
          $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
          $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
          $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
          $req_aj_uf ->bindParam(':id_user', $untr["id_user"]);
          $req_aj_uf ->bindParam(':id_forfaitStream', $untr['id_forfaitStream']);
          $req_aj_uf ->execute();
      }

      $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
      $req_maj_don = $bdd -> prepare($req_majd);

      $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
      $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
      $req_maj_don ->execute();

    } else {
      $dateAuj =  date ('Y-m-d H:i:s');

      $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
      $un = $bdd -> query($re);
      $untr = $un->fetch();
      $libTrans = $untr['reference_transaction'];
      $idTrans = $untr['id_transaction'];

      $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
      $req_maj_don = $bdd -> prepare($req_majd);
      //$req_maj_don ->bindParam(':montant_transaction', $amount);
      $req_maj_don ->bindParam(':statut_transaction', $statutt);
      $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
      $req_maj_don ->execute();

      $lib = explode('-', $untr['libelle_transaction']);
      if ($statutt == "SUCCESS") {

        if (isset($untr['id_user']) AND $untr['montant_transaction'] >= 2000 AND end($lib) !== "don") {
          $re = "SELECT * FROM walletB WHERE id_user = ".$untr['id_user'];
          $rq = $bdd -> query($re);
          $wall = $rq -> fetch();

          $solde = $wall['solde_walletB'] + $untr['montant_transaction']*0.05;

          $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
          $req_msold = $bdd -> prepare($req_msol);
          $req_msold ->bindParam(':sold', $solde);
          $req_msold ->bindParam(':id', $wall['id_walletB']);
          $req_msold -> execute();
        }
        if (isset($untr['id_user'])) {
          $requet = $bdd -> query("SELECT * FROM user WHERE user.id_user =".$untr['id_user']);
          $colon = $requet -> fetch();
        } else {
          $requet = $bdd -> query("SELECT * FROM user WHERE user.telephone_user LIKE '%".$untr['telephone_transaction']."'");
          $colon = $requet -> fetch();
        }

        if (isset($colon['id_user'])) {
          //echo "l'id est".$colon['id_user'];
          $reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$colon['id_user']." AND libelle_user_forfaitStream ='promo1'";
          // echo $reqF;
          $for = $bdd -> query($reqF);
          $forfait = $for->rowCount();
          if ($forfait == 0) {
            $nbScd = 86400;
            $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
            $libForf = "promo1";
            // Moyen de paiement 1 mobile mo, 0 Airtime
            $renouv = 0;
            $idForfait = 1;
            $moyenPaie = "MM";

            $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
            $req_aj_uf = $bdd -> prepare($req_ajuf);
            $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
            $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
            $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
            $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
            $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
            $req_aj_uf ->bindParam(':id_user', $colon["id_user"]);
            $req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
            $req_aj_uf ->execute();
          }
        }
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
      //
      // }

    if (isset($_GET['wall']) AND $_GET['wall']==1) {
      $rep['status'] = "SUCCESS";
      echo json_encode($rep);
    } else {
      header("Location:https://afreekaplay.com/callback?ref=".$ref);
    }
  }
}
else if (isset($_POST['referenceID'])) {
  //itouch
  $statutt = ($_POST['status'] == 'SUCCESSFUL') ? 'SUCCESS' : 'FAIL' ;
  //echo $statutt;
  $ref = $_POST['referenceID'];

      $opt = explode('-',$ref);
      if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

        $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
        $un = $bdd -> query($re);
        $untr = $un -> fetch();
        if ($untr['statut_rechargement'] !== "SUCCESS") {
          $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
          $req_maj_don = $bdd -> prepare($req_majd);

          $req_maj_don ->bindParam(':statut_rechargement', $statutt);
          $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
          $req_maj_don ->execute();

          if ($statutt == "SUCCESS") {
            $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
            $un2 = $bdd -> query($re);
            $wal = $un2 -> fetch();
            $solde = (isset($wal['solde_walletP'])) ? $wal['solde_walletP'] : 0;
            $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
            $req_maj_wal = $bdd -> prepare($req_majw);
            $amountAj = $solde + $untr['montant_rechargement'];

            $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
            $req_maj_wal ->bindParam(':id_walletP', $wal['id_walletP']);
            $req_maj_wal ->execute();
          }
        }


      } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

        $dateAuj =  date ('Y-m-d H:i:s');

        $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$ref."'";
        $un = $bdd -> query($re);
        $untr = $un -> fetch();
        if ($statutt == "SUCCESS") {
          $reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$untr['id_forfaitStream'];
      		// echo $reqF;
      		$for = $bdd -> query($reqF);
      		$forfait = $for->fetch();
      		$nbScd = $forfait['temps_forfaitStream']*86400;
      		$datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
          $libForf = "souscription";
      		// Moyen de paiement 1 mobile mo, 0 Airtime
      		$renouv = 0;
      		$moyenPaie = "MM";

          $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
            $req_aj_uf = $bdd -> prepare($req_ajuf);
            $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
            $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
            $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
            $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
            $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
      			$req_aj_uf ->bindParam(':id_user', $untr["id_user"]);
      			$req_aj_uf ->bindParam(':id_forfaitStream', $untr['id_forfaitStream']);
      			$req_aj_uf ->execute();
        }


        $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
        $req_maj_don = $bdd -> prepare($req_majd);

        $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
        $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
        $req_maj_don ->execute();

      } else {
        $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
        $un = $bdd -> query($re);
        $untr = $un->fetch();
        $libTrans = $untr['reference_transaction'];
        $idTrans = $untr['id_transaction'];

        $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
        $req_maj_don = $bdd -> prepare($req_majd);
        //$req_maj_don ->bindParam(':montant_transaction', $amount);
        $req_maj_don ->bindParam(':statut_transaction', $statutt);
        $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
        $req_maj_don ->execute();

        if ($statutt == "SUCCESS") {
          if (isset($untr['id_user']) AND $untr['montant_transaction'] >= 2000 AND end($lib) !== "don") {
            $re = "SELECT * FROM walletB WHERE id_user = ".$untr['id_user'];
            $rq = $bdd -> query($re);
            $wall = $rq -> fetch();

            $solde = $wall['solde_walletB'] + $untr['montant_transaction']*0.05;

            $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
            $req_msold = $bdd -> prepare($req_msol);
            $req_msold ->bindParam(':sold', $solde);
            $req_msold ->bindParam(':id', $wall['id_walletB']);
            $req_msold -> execute();
          }

          if (isset($untr['id_user'])) {
            $requet = $bdd -> query("SELECT * FROM user WHERE user.id_user =".$untr['id_user']);
            $colon = $requet -> fetch();
          } else {
            $requet = $bdd -> query("SELECT * FROM user WHERE user.telephone_user LIKE '%".$untr['telephone_transaction']."'");
            $colon = $requet -> fetch();
          }

          if (isset($colon['id_user'])) {
            //echo "l'id est".$colon['id_user'];
            $reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$colon['id_user']." AND libelle_user_forfaitStream ='promo1'";
            // echo $reqF;
            $for = $bdd -> query($reqF);
            $forfait = $for->rowCount();
            if ($forfait == 0) {
              $nbScd = 86400;
              $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
              $libForf = "promo1";
              // Moyen de paiement 1 mobile mo, 0 Airtime
              $renouv = 0;
              $idForfait = 1;
              $moyenPaie = "MM";

              $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
              $req_aj_uf = $bdd -> prepare($req_ajuf);
              $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
              $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
              $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
              $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
              $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
              $req_aj_uf ->bindParam(':id_user', $colon["id_user"]);
              $req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
              $req_aj_uf ->execute();
            }
          }
        }

      }


  $affich_el[0]=1;
  $affich_el['rep']=0;
  $affich_e = array_shift($affich_el);
  //var_dump($affich_e);
  //var_dump($affich_el);

  echo json_encode($affich_el);
}
else if(isset($_POST['hmac'])) {

  $ddj = date ('Y-m-d H:i:s');
  $key = "9oeod8TteR4mAwYDb1S8ISgfWfZgBuosO2kG7Smd";
  $hmac = hash_hmac('sha256', $_POST['timestamp'], $key);




    if ($hmac == $_POST['hmac']) {

        $id_transaction = $_POST['transaction_id'];
        $ref = $_POST['transaction_id'];

        $opt = explode('-',$ref);
        if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

          $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
          $un = $bdd -> query($re);
          $untr = $un -> fetch();
          $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
          $un2 = $bdd -> query($re);
          $wal = $un2 -> fetch();
          $solde = (isset($wal['solde_walletP'])) ? $wal['solde_walletP'] : 0;

        } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

          $dateAuj =  date ('Y-m-d H:i:s');

          $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$ref."'";
          $un = $bdd -> query($re);
          $untr = $un -> fetch();


        } else {

          $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
          $un = $bdd -> query($re);
          $untr = $un->fetch();
          $libTrans = $untr['reference_transaction'];
          $idTrans = $untr['id_transaction'];
          //var_dump($re);
          //var_dump($untr);
        }



        		// $req_aj_tel ->execute();
        // Verification de l'etat du traitement de la commande
        if ($untr['statut_transaction'] == 'SUCCESS') {
            // La commande a été déjà traité
            // Arret du script
            die();
        }




            if ($_POST['status'] == 'success') {


              $statutt = 'SUCCESS';

              //$opt = explode('-',$ref);
              if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

                $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
                $req_maj_don = $bdd -> prepare($req_majd);

                $req_maj_don ->bindParam(':statut_rechargement', $statutt);
                $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
                $req_maj_don ->execute();

                $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
                $req_maj_wal = $bdd -> prepare($req_majw);
                $amountAj = $solde + $untr['montant_rechargement'];

                $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
                $req_maj_wal ->bindParam(':id_walletP', $wal['id_walletP']);
                $req_maj_wal ->execute();

              } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

                $reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$untr['id_forfaitStream'];
            		// echo $reqF;
            		$for = $bdd -> query($reqF);
            		$forfait = $for->fetch();

            		$nbScd = $forfait['temps_forfaitStream']*86400;
            		$datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
                $libForf = "souscription";
            		// Moyen de paiement 1 mobile mo, 0 Airtime
            		$renouv = 0;
            		$moyenPaie = "MM";

                $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
                  $req_aj_uf = $bdd -> prepare($req_ajuf);
                  $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
                  $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
                  $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
                  $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
                  $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
            			$req_aj_uf ->bindParam(':id_user', $untr["id_user"]);
            			$req_aj_uf ->bindParam(':id_forfaitStream', $untr['id_forfaitStream']);
            			$req_aj_uf ->execute();

                $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
                $req_maj_don = $bdd -> prepare($req_majd);

                $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
                $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
                $req_maj_don ->execute();

              }  else {

                $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
                $req_maj_don = $bdd -> prepare($req_majd);
                //$req_maj_don ->bindParam(':montant_transaction', $amount);
                $req_maj_don ->bindParam(':statut_transaction', $statutt);
                $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
                $req_maj_don ->execute();



                if (isset($untr['id_user']) AND $untr['montant_transaction'] >= 2000 AND end($lib) !== "don") {
                  $re = "SELECT * FROM walletB WHERE id_user = ".$untr['id_user'];
                  $rq = $bdd -> query($re);
                  $wall = $rq -> fetch();

                  $solde = $wall['solde_walletB'] + $untr['montant_transaction']*0.05;

                  $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
                  $req_msold = $bdd -> prepare($req_msol);
                  $req_msold ->bindParam(':sold', $solde);
                  $req_msold ->bindParam(':id', $wall['id_walletB']);
                  $req_msold -> execute();
                }

                if (isset($untr['id_user'])) {
                  $requet = $bdd -> query("SELECT * FROM user WHERE user.id_user =".$untr['id_user']);
                  $colon = $requet -> fetch();
                } else {
                  $requet = $bdd -> query("SELECT * FROM user WHERE user.telephone_user LIKE '%".$untr['telephone_transaction']."'");
                  $colon = $requet -> fetch();
                }

                if (isset($colon['id_user'])) {
                  //echo "l'id est".$colon['id_user'];
                  $reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$colon['id_user']." AND libelle_user_forfaitStream ='promo1'";
                  // echo $reqF;
                  $for = $bdd -> query($reqF);
                  $forfait = $for->rowCount();
                  if ($forfait == 0) {
                    $nbScd = 86400;
                    $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
                    $libForf = "promo1";
                    // Moyen de paiement 1 mobile mo, 0 Airtime
                    $renouv = 0;
                    $idForfait = 1;
                    $moyenPaie = "MM";

                    $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
                    $req_aj_uf = $bdd -> prepare($req_ajuf);
                    $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
                    $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
                    $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
                    $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
                    $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
                    $req_aj_uf ->bindParam(':id_user', $colon["id_user"]);
                    $req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
                    $req_aj_uf ->execute();
                  }
                }
              }

                $affich_el[0]=1;
                $affich_el['rep']=0;
                $affich_e = array_shift($affich_el);
                //var_dump($affich_e);
                //var_dump($affich_el);

            } else {
              $statutt = 'FAIL';

              // $opt = explode('-',$ref);
              if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

                $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
                $req_maj_don = $bdd -> prepare($req_majd);

                $req_maj_don ->bindParam(':statut_rechargement', $statutt);
                $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
                $req_maj_don ->execute();

              } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

                $dateAuj =  date ('Y-m-d H:i:s');

                $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
                $req_maj_don = $bdd -> prepare($req_majd);

                $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
                $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
                $req_maj_don ->execute();

              } else {

                $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
                $req_maj_don = $bdd -> prepare($req_majd);
                //$req_maj_don ->bindParam(':montant_transaction', $amount);
                $req_maj_don ->bindParam(':statut_transaction', $statutt);
                $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
                $req_maj_don ->execute();

              }

                $affich_el[0]=1;
                $affich_el['rep']=0;
                $affich_e = array_shift($affich_el);
                //var_dump($affich_e);
                //var_dump($affich_el);

            }

            echo json_encode($affich_el);
        } else {
          $statutt = 'FAIL';
          $id_transaction = $_POST['transaction_id'];
          $ref = $_POST['transaction_id'];

          $opt = explode('-',$ref);
          if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

            $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
            $un = $bdd -> query($re);
            $untr = $un -> fetch();
            $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
            $un2 = $bdd -> query($re);
            $wal = $un2 -> fetch();
            $solde = $wal['solde_walletP'];

            $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
            $req_maj_don = $bdd -> prepare($req_majd);

            $req_maj_don ->bindParam(':statut_rechargement', $statutt);
            $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
            $req_maj_don ->execute();

          } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

            $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
            $req_maj_don = $bdd -> prepare($req_majd);

            $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
            $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
            $req_maj_don ->execute();

          } else {

            $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
            $un = $bdd -> query($re);
            $untr = $un->fetch();

            $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
            $req_maj_don = $bdd -> prepare($req_majd);
            //$req_maj_don ->bindParam(':montant_transaction', $amount);
            $req_maj_don ->bindParam(':statut_transaction', $statutt);
            $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
            $req_maj_don ->execute();
            //var_dump($re);
            //var_dump($untr);
          }


            $affich_el[0]=1;
            $affich_el['rep']=0;
            $affich_e = array_shift($affich_el);
            //var_dump($affich_e);
            //var_dump($affich_el);

        }


}
elseif (isset($_POST['mrchrefid'])) {
  $v1 = json_encode($_POST);
  $lib = "NIVEAU Moov --- ".$v1;
  //echo $lib;
  $req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
  $req_aj_tel = $bdd -> prepare($req_ajt);
  $req_aj_tel ->bindParam(':date_log', $ddj);
  $req_aj_tel ->bindParam(':libelle_log', $lib);
  $req_aj_tel ->execute();

  $statutt = ($_POST['status'] == 0) ? 'SUCCESS' : 'FAIL' ;
  //echo $statutt;
  $ref = $_POST['refid'];
  //  var_dump($re);
  //var_dump($untr);

  $opt = explode('-',$ref);
  if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

    $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
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

      $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
      $req_maj_wal ->bindParam(':id_walletP', $wal['id_walletP']);
      $req_maj_wal ->execute();
    }

  } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

    $dateAuj =  date ('Y-m-d H:i:s');

    $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$ref."'";
    $un = $bdd -> query($re);
    $untr = $un -> fetch();
    if ($statutt == "SUCCESS") {
      $reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$untr['id_forfaitStream'];
      // echo $reqF;
      $for = $bdd -> query($reqF);
      $forfait = $for->fetch();
      $nbScd = $forfait['temps_forfaitStream']*86400;
      $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
      $libForf = "souscription";
      // Moyen de paiement 1 mobile mo, 0 Airtime
      $renouv = 0;
      $moyenPaie = "MM";

      $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
        $req_aj_uf = $bdd -> prepare($req_ajuf);
        $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
        $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
        $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
        $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
        $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
        $req_aj_uf ->bindParam(':id_user', $untr["id_user"]);
        $req_aj_uf ->bindParam(':id_forfaitStream', $untr['id_forfaitStream']);
        $req_aj_uf ->execute();
    }


    $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
    $req_maj_don = $bdd -> prepare($req_majd);

    $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
    $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
    $req_maj_don ->execute();

  } else {
    $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
    $un = $bdd -> query($re);
    $untr = $un->fetch();
    $libTrans = $untr['reference_transaction'];
    $idTrans = $untr['id_transaction'];

    $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
    $req_maj_don = $bdd -> prepare($req_majd);
    //$req_maj_don ->bindParam(':montant_transaction', $amount);
    $req_maj_don ->bindParam(':statut_transaction', $statutt);
    $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
    $req_maj_don ->execute();


    if ($statutt == "SUCCESS") {
      if (isset($untr['id_user']) AND $untr['montant_transaction'] >= 2000 AND end($lib) !== "don") {
        $re = "SELECT * FROM walletB WHERE id_user = ".$untr['id_user'];
        $rq = $bdd -> query($re);
        $wall = $rq -> fetch();

        $solde = $wall['solde_walletB'] + $untr['montant_transaction']*0.05;

        $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
        $req_msold = $bdd -> prepare($req_msol);
        $req_msold ->bindParam(':sold', $solde);
        $req_msold ->bindParam(':id', $wall['id_walletB']);
        $req_msold -> execute();
      }
      if (isset($untr['id_user'])) {
        $requet = $bdd -> query("SELECT * FROM user WHERE user.id_user =".$untr['id_user']);
        $colon = $requet -> fetch();
      } else {
        $requet = $bdd -> query("SELECT * FROM user WHERE user.telephone_user LIKE '%".$untr['telephone_transaction']."'");
        $colon = $requet -> fetch();
      }

      if (isset($colon['id_user'])) {
        //echo "l'id est".$colon['id_user'];
        $reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$colon['id_user']." AND libelle_user_forfaitStream ='promo1'";
        // echo $reqF;
        $for = $bdd -> query($reqF);
        $forfait = $for->rowCount();
        if ($forfait == 0) {
          $nbScd = 86400;
          $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
          $libForf = "promo1";
          // Moyen de paiement 1 mobile mo, 0 Airtime
          $renouv = 0;
          $idForfait = 1;
          $moyenPaie = "MM";

          $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
          $req_aj_uf = $bdd -> prepare($req_ajuf);
          $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
          $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
          $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
          $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
          $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
          $req_aj_uf ->bindParam(':id_user', $colon["id_user"]);
          $req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
          $req_aj_uf ->execute();
        }
      }
    }

  }



  $affich_el[0]=1;
  $affich_e = array_shift($affich_el);

  $affich_el['message'] = "success";
  $affich_el['code'] = 0;
  echo json_encode($affich_el);





}

else {
  if ($_POST == null) {

      $_POST = json_decode(file_get_contents('php://input'), true);
      //$_POST = (isset($_GET)) ? $_GET : $_POST;
      //$err = serialize($_POST);
      // var_dump($_POST);
      $v1 = json_encode($_POST);
      $lib = "NIVEAU 3 --- ".$v1;
      //echo $lib;
      $req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
      $req_aj_tel = $bdd -> prepare($req_ajt);
      $req_aj_tel ->bindParam(':date_log', $ddj);
      $req_aj_tel ->bindParam(':libelle_log', $lib);
      $req_aj_tel ->execute();

      if(isset($_POST['partner_transaction_id'])){
        //Moov Flooz;
        $statutt = ($_POST['status'] == 'SUCCESSFUL') ? 'SUCCESS' : 'FAIL' ;
        //echo $statutt;
        $ref = $_POST['partner_transaction_id'];


        $opt = explode('-',$ref);
        if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

          $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
          $un = $bdd -> query($re);
          $untr = $un -> fetch();

          $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
          $un2 = $bdd -> query($re);
          $wal = $un2 -> fetch();
          $solde = (isset($wal['solde_walletP'])) ? $wal['solde_walletP'] : 0;

          $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
          $req_maj_don = $bdd -> prepare($req_majd);

          $req_maj_don ->bindParam(':statut_rechargement', $statutt);
          $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
          $req_maj_don ->execute();

          if ($statutt == "SUCCESS") {
            $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
            $req_maj_wal = $bdd -> prepare($req_majw);
            $amountAj = $solde + $untr['montant_rechargement'];

            $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
            $req_maj_wal ->bindParam(':id_walletP', $wal['id_walletP']);
            $req_maj_wal ->execute();
          }

        } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

          $dateAuj =  date ('Y-m-d H:i:s');

          $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$ref."'";
          $un = $bdd -> query($re);
          $untr = $un -> fetch();
          if ($statutt == "SUCCESS") {
            $reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$untr['id_forfaitStream'];
        		// echo $reqF;
        		$for = $bdd -> query($reqF);
        		$forfait = $for->fetch();
        		$nbScd = $forfait['temps_forfaitStream']*86400;
        		$datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
            $libForf = "souscription";
        		// Moyen de paiement 1 mobile mo, 0 Airtime
        		$renouv = 0;
        		$moyenPaie = "MM";

            $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
              $req_aj_uf = $bdd -> prepare($req_ajuf);
              $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
              $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
              $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
              $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
              $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
        			$req_aj_uf ->bindParam(':id_user', $untr["id_user"]);
        			$req_aj_uf ->bindParam(':id_forfaitStream', $untr['id_forfaitStream']);
        			$req_aj_uf ->execute();
          }


          $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
          $req_maj_don = $bdd -> prepare($req_majd);

          $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
          $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
          $req_maj_don ->execute();

        } else {
          $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
          $un = $bdd -> query($re);
          $untr = $un->fetch();
          $libTrans = $untr['reference_transaction'];
          $idTrans = $untr['id_transaction'];

          $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
          $req_maj_don = $bdd -> prepare($req_majd);
          //$req_maj_don ->bindParam(':montant_transaction', $amount);
          $req_maj_don ->bindParam(':statut_transaction', $statutt);
          $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
          $req_maj_don ->execute();

          if ($statutt == "SUCCESS") {
            if (isset($untr['id_user']) AND $untr['montant_transaction'] >= 2000 AND end($lib) !== "don") {
              $re = "SELECT * FROM walletB WHERE id_user = ".$untr['id_user'];
              $rq = $bdd -> query($re);
              $wall = $rq -> fetch();

              $solde = $wall['solde_walletB'] + $untr['montant_transaction']*0.05;

              $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
              $req_msold = $bdd -> prepare($req_msol);
              $req_msold ->bindParam(':sold', $solde);
              $req_msold ->bindParam(':id', $wall['id_walletB']);
              $req_msold -> execute();
            }

            if (isset($untr['id_user'])) {
              $requet = $bdd -> query("SELECT * FROM user WHERE user.id_user =".$untr['id_user']);
              $colon = $requet -> fetch();
            } else {
              $requet = $bdd -> query("SELECT * FROM user WHERE user.telephone_user LIKE '%".$untr['telephone_transaction']."'");
              $colon = $requet -> fetch();
            }

            if (isset($colon['id_user'])) {
              //echo "l'id est".$colon['id_user'];
              $reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$colon['id_user']." AND libelle_user_forfaitStream ='promo1'";
              // echo $reqF;
              $for = $bdd -> query($reqF);
              $forfait = $for->rowCount();
              if ($forfait == 0) {
                $nbScd = 86400;
                $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
                $libForf = "promo1";
                // Moyen de paiement 1 mobile mo, 0 Airtime
                $renouv = 0;
                $idForfait = 1;
                $moyenPaie = "MM";

                $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
                $req_aj_uf = $bdd -> prepare($req_ajuf);
                $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
                $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
                $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
                $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
                $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
                $req_aj_uf ->bindParam(':id_user', $colon["id_user"]);
                $req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
                $req_aj_uf ->execute();
              }
            }

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
        //
        // }

      }
      else if (isset($_POST['transactionId'])) {
        //kkiapay
        $public_key = "b9874f90c5fa11eab47ae11769a4e527";

        $private_key = "pk_b98776a1c5fa11eab47ae11769a4e527";

        $secret = "sk_b98776a2c5fa11eab47ae11769a4e527";


        $transaction_id = $_POST['transactionId'];

        $kkiapay = new Kkiapay\Kkiapay($public_key, $private_key, $secret);
        $verify = $kkiapay->verifyTransaction($transaction_id);


        if(isset($_POST['transactionId']) && !empty($_POST['transactionId'])){


          $statutt = $verify  -> status;
          $amount = $verify  -> amount;
          $ref = $verify  -> state;

          $opt = explode('-',$ref);
          if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

            $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
            $un = $bdd -> query($re);
            $untr = $un -> fetch();

            $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
            $un2 = $bdd -> query($re);
            $wal = $un2 -> fetch();
            $solde = (isset($wal['solde_walletP'])) ? $wal['solde_walletP'] : 0;

            $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
            $req_maj_don = $bdd -> prepare($req_majd);

            $req_maj_don ->bindParam(':statut_rechargement', $statutt);
            $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
            $req_maj_don ->execute();

            if ($statutt == "SUCCESS") {
              $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
              $req_maj_wal = $bdd -> prepare($req_majw);
              $amountAj = $solde + $untr['montant_rechargement'];

              $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
              $req_maj_wal ->bindParam(':id_walletP', $wal['id_walletP']);
              $req_maj_wal ->execute();
            }

          } else if (isset($opt[0]) AND $opt[0]=='AFPFOR') {

            $dateAuj =  date ('Y-m-d H:i:s');

            $re = "SELECT * FROM forfaitTrsx WHERE reference_forfaitTrsx = '".$ref."'";
            $un = $bdd -> query($re);
            $untr = $un -> fetch();
            if ($statutt == "SUCCESS") {
              $reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$untr['id_forfaitStream'];
          		// echo $reqF;
          		$for = $bdd -> query($reqF);
          		$forfait = $for->fetch();
          		$nbScd = $forfait['temps_forfaitStream']*86400;
          		$datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
              $libForf = "souscription";
          		// Moyen de paiement 1 mobile mo, 0 Airtime
          		$renouv = 0;
          		$moyenPaie = "MM";

              $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
                $req_aj_uf = $bdd -> prepare($req_ajuf);
                $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
                $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
                $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
                $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
                $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
          			$req_aj_uf ->bindParam(':id_user', $untr["id_user"]);
          			$req_aj_uf ->bindParam(':id_forfaitStream', $untr['id_forfaitStream']);
          			$req_aj_uf ->execute();
            }


            $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
            $req_maj_don = $bdd -> prepare($req_majd);

            $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
            $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
            $req_maj_don ->execute();

          } else {
            $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
            $un = $bdd -> query($re);
            $untr = $un->fetch();
            $libTrans = $untr['reference_transaction'];
            $idTrans = $untr['id_transaction'];

            $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
            $req_maj_don = $bdd -> prepare($req_majd);
            //$req_maj_don ->bindParam(':montant_transaction', $amount);
            $req_maj_don ->bindParam(':statut_transaction', $statutt);
            $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
            $req_maj_don ->execute();

            if ($statutt == "SUCCESS") {
              if (isset($untr['id_user']) AND $untr['montant_transaction'] >= 2000 AND end($lib) !== "don") {
                $re = "SELECT * FROM walletB WHERE id_user = ".$untr['id_user'];
                $rq = $bdd -> query($re);
                $wall = $rq -> fetch();

                $solde = $wall['solde_walletB'] + $untr['montant_transaction']*0.05;

                $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
                $req_msold = $bdd -> prepare($req_msol);
                $req_msold ->bindParam(':sold', $solde);
                $req_msold ->bindParam(':id', $wall['id_walletB']);
                $req_msold -> execute();
              }

              if (isset($untr['id_user'])) {
                $requet = $bdd -> query("SELECT * FROM user WHERE user.id_user =".$untr['id_user']);
                $colon = $requet -> fetch();
              } else {
                $requet = $bdd -> query("SELECT * FROM user WHERE user.telephone_user LIKE '%".$untr['telephone_transaction']."'");
                $colon = $requet -> fetch();
              }

              if (isset($colon['id_user'])) {
                //echo "l'id est".$colon['id_user'];
                $reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$colon['id_user']." AND libelle_user_forfaitStream ='promo1'";
                // echo $reqF;
                $for = $bdd -> query($reqF);
                $forfait = $for->rowCount();
                if ($forfait == 0) {
                  $nbScd = 86400;
                  $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
                  $libForf = "promo1";
                  // Moyen de paiement 1 mobile mo, 0 Airtime
                  $renouv = 0;
                  $idForfait = 1;
                  $moyenPaie = "MM";

                  $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
                  $req_aj_uf = $bdd -> prepare($req_ajuf);
                  $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
                  $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
                  $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
                  $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
                  $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
                  $req_aj_uf ->bindParam(':id_user', $colon["id_user"]);
                  $req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
                  $req_aj_uf ->execute();
                }
              }

            }


          }




          // $opt = explode('-',$ref);
          if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
            $rep['status'] = $statutt;
            echo json_encode($rep);
          } else {
            header("Location:https://afreekaplay.com/callback?ref=".$ref);
          }
        }
      }
      else if(isset($_POST['notif_token'])){
        // OM GUINEE

        $v1 = json_encode($_POST);
        $lib = "NIVEAU OMGN CBack --- ".$v1;
        //echo $lib;
        $req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
        $req_aj_tel = $bdd -> prepare($req_ajt);
        $req_aj_tel ->bindParam(':date_log', $ddj);
        $req_aj_tel ->bindParam(':libelle_log', $lib);
        $req_aj_tel ->execute();

        $re1 = "SELECT * FROM transactionOM WHERE notifToken_transactionOM = '".$_POST['notif_token']."'";
        $un = $bdd -> query($re1);
        //$nbuser = $un->rowCount();
        $eltOM = $un->fetch();

        if (isset($eltOM['libelle_transactionOM']) AND $eltOM['libelle_transactionOM']=='rechargement') {

            // var_dump($eltOM[6]);
          $re = "SELECT * FROM rechargement WHERE id_rechargement = '".$eltOM['id_transaction']."'";
          $un = $bdd -> query($re);
          $elt = $un -> fetch();

          $re = "SELECT * FROM walletP WHERE id_walletP = '".$elt['id_walletP']."'";
          $un2 = $bdd -> query($re);
          $wal = $un2 -> fetch();
          $solde = (isset($wal['solde_walletP'])) ? $wal['solde_walletP'] : 0;

        } else if (isset($eltOM['libelle_transactionOM']) AND $eltOM['libelle_transactionOM']=='Forfait') {

          $dateAuj =  date ('Y-m-d H:i:s');

          $re3 = "SELECT * FROM forfaitTrsx WHERE id_forfaitTrsx = '".$eltOM['id_transaction']."'";
          $un3 = $bdd -> query($re3);
          $elt = $un3 -> fetch();


        } else {
          $re2 = "SELECT * FROM transaction WHERE id_transaction = '".$eltOM['id_transaction']."'";
      		$un2 = $bdd -> query($re2);
      		//$nbuser = $un->rowCount();
      		$elt = $un2->fetch();
          $libTrans = $elt['reference_transaction'];
          $idTrans = $elt['id_transaction'];
        }


        // var_dump($elt);
        if ($_POST['status'] == 'SUCCESS') {


          $statutt = 'SUCCESS';
          $reP = "SELECT * FROM pays INNER JOIN devise
        	 													ON devise.id_devise = pays.id_devise
        	 													WHERE pays.id_pays = ".$elt['id_pays'];
        	$lpays = $bdd -> query($reP);
        	$lpays = $lpays->fetch();



            if ($eltOM['libelle_transactionOM']=='rechargement') {

              if ($lpays['id_devise'] == $wal['id_devise']) {
            		$amount = $elt['montant_rechargement'];
            	} else {
            		$reT = "SELECT * FROM taux WHERE from_taux = ".$lpays['id_devise']." AND to_taux = ".$wal['id_devise'];
            		$Tx = $bdd -> query($reT);
            		$taux = $Tx->fetch();
            		$amount = $elt['montant_rechargement']*$taux['taux_taux'];
            	}

              $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
              $req_maj_don = $bdd -> prepare($req_majd);

              $req_maj_don ->bindParam(':statut_rechargement', $statutt);
              $req_maj_don ->bindParam(':id_rechargement', $elt['id_rechargement']);
              $req_maj_don ->execute();

              $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
              $req_maj_wal = $bdd -> prepare($req_majw);
              $amountAj = $solde + $amount;

              $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
              $req_maj_wal ->bindParam(':id_walletP', $wal['id_walletP']);
              $req_maj_wal ->execute();
              // echo "recha";

            } else if ($eltOM['libelle_transactionOM'] == "Forfait") {


                $reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$elt['id_forfaitStream'];
            		// echo $reqF;
            		$for = $bdd -> query($reqF);
            		$forfait = $for->fetch();
            		$nbScd = $forfait['temps_forfaitStream']*86400;
            		$datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
                $libForf = "souscription";
            		// Moyen de paiement 1 mobile mo, 0 Airtime
            		$renouv = 0;
            		$moyenPaie = "MM";

                $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
                  $req_aj_uf = $bdd -> prepare($req_ajuf);
                  $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
                  $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
                  $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
                  $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
                  $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
            			$req_aj_uf ->bindParam(':id_user', $elt["id_user"]);
            			$req_aj_uf ->bindParam(':id_forfaitStream', $elt['id_forfaitStream']);
            			$req_aj_uf ->execute();



              $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
              $req_maj_don = $bdd -> prepare($req_majd);

              $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
              $req_maj_don ->bindParam(':id_forfaitTrsx', $elt['id_forfaitTrsx']);
              $req_maj_don ->execute();

            } else {
              if ($lpays['id_devise'] == 2) {
            		$limW = 20000;
            	} else if ($lpays['id_devise'] == 4) {
            		$limW = 2500;
            	} else {
                $limW = 2000;
              }

              $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
              $req_maj_don = $bdd -> prepare($req_majd);
              //$req_maj_don ->bindParam(':montant_transaction', $amount);
              $req_maj_don ->bindParam(':statut_transaction', $statutt);
              $req_maj_don ->bindParam(':id_transaction', $elt['id_transaction']);
              $req_maj_don ->execute();

              if (isset($elt['id_user']) AND $elt['montant_transaction'] >= $limW AND end($lib) !== "don" AND $statutt == "SUCCESS") {
                $re = "SELECT * FROM walletB WHERE id_user = ".$elt['id_user'];
                $rq = $bdd -> query($re);
                $wall = $rq -> fetch();

                $solde = $wall['solde_walletB'] + $elt['montant_transaction']*0.05;

                $req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
                $req_msold = $bdd -> prepare($req_msol);
                $req_msold ->bindParam(':solde_walletB', $solde);
                $req_msold ->bindParam(':id', $wall['id_walletB']);
                $req_msold -> execute();
              }
              if (isset($untr['id_user'])) {
                $requet = $bdd -> query("SELECT * FROM user WHERE user.id_user =".$elt['id_user']);
                $colon = $requet -> fetch();
              } else {
                $requet = $bdd -> query("SELECT * FROM user WHERE user.telephone_user LIKE '%".$elt['telephone_transaction']."'");
                $colon = $requet -> fetch();
              }

              if (isset($colon['id_user'])) {
                //echo "l'id est".$colon['id_user'];
                $reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$colon['id_user']." AND libelle_user_forfaitStream ='promo1'";
                // echo $reqF;
                $for = $bdd -> query($reqF);
                $forfait = $for->rowCount();
                if ($forfait == 0) {
                  $nbScd = 86400;
                  $datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
                  $libForf = "promo1";
                  // Moyen de paiement 1 mobile mo, 0 Airtime
                  $renouv = 0;
                  $idForfait = 1;
                  $moyenPaie = "MM";

                  $req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
                  $req_aj_uf = $bdd -> prepare($req_ajuf);
                  $req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
                  $req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
                  $req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
                  $req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
                  $req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
                  $req_aj_uf ->bindParam(':id_user', $colon["id_user"]);
                  $req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
                  $req_aj_uf ->execute();
                }
              }
            }

            $affich_el[0]=1;
            $affich_el['rep']=0;
            $affich_e = array_shift($affich_el);
            //var_dump($affich_e);
            //var_dump($affich_el);

        } else {
          $statutt = 'FAIL';

          //$amount = intval($_POST['amount']);
          if ($eltOM['libelle_transactionOM']=='rechargement') {

            $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
            $req_maj_don = $bdd -> prepare($req_majd);

            $req_maj_don ->bindParam(':statut_rechargement', $statutt);
            $req_maj_don ->bindParam(':id_rechargement', $elt['id_rechargement']);
            $req_maj_don ->execute();

            // $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
            // $req_maj_wal = $bdd -> prepare($req_majw);
            // $amountAj = $solde + $elt['montant_rechargement'];
            //
            // $req_maj_wal ->bindParam(':solde_walletP', $amountAj);
            // $req_maj_wal ->bindParam(':id_walletP', $wal['id_walletP']);
            // $req_maj_wal ->execute();

          } else if ($eltOM['libelle_transactionOM'] == "Forfait") {

            $req_majd = "UPDATE forfaitTrsx SET statut_forfaitTrsx=:statut_forfaitTrsx WHERE id_forfaitTrsx=:id_forfaitTrsx";
            $req_maj_don = $bdd -> prepare($req_majd);

            $req_maj_don ->bindParam(':statut_forfaitTrsx', $statutt);
            $req_maj_don ->bindParam(':id_forfaitTrsx', $untr['id_forfaitTrsx']);
            $req_maj_don ->execute();

          } else {

            $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
            $req_maj_don = $bdd -> prepare($req_majd);
            //$req_maj_don ->bindParam(':montant_transaction', $amount);
            $req_maj_don ->bindParam(':statut_transaction', $statutt);
            $req_maj_don ->bindParam(':id_transaction', $elt['id_transaction']);
            $req_maj_don ->execute();
          }

            $affich_el[0]=1;
            $affich_el['rep']=0;
            $affich_e = array_shift($affich_el);
            //var_dump($affich_e);
            //var_dump($affich_el);

        }

      }
      //echo $err;
      // $err1 = 1;
      // $req_ajt = "INSERT INTO erreur_telechargement (numero_erreur_telechargement, id_transaction) VALUES (:numero_erreur_telechargement, :id_transaction)";
      //   $req_aj_tel = $bdd -> prepare($req_ajt);
      //   $req_aj_tel ->bindParam(':numero_erreur_telechargement', $err);
      //   $req_aj_tel ->bindParam(':id_transaction', $err1);
      //
      //   $req_aj_tel ->execute();


    }
}

if(preg_match("#-don#", $libTrans)){


  $reDon = "SELECT * FROM don INNER JOIN artiste
                          ON don.id_artiste = artiste.id_artiste
                          WHERE don.id_transaction = ".$idTrans;

  $infoT = explode('-',$libTrans);
  $limTab = sizeof($infoT);
  $idSA = $infoT[$limTab-2];
  $tableee = $infoT[0];

  $un2 = $bdd -> query($reDon);
  $untr2 = $un2->fetch();


}



?>
