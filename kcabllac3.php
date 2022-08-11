<?php
header("Access-Control-Allow-Origin: *");

 //var_dump ($_POST);
 set_time_limit(0);
 ini_set('memory_limit', '512M');

 require_once __DIR__.'/../vendor/autoload.php';
   require 'connexion.php';
   //7780
//$don = "";
$_POST = (isset($_GET)) ? $_GET : $_POST;
// var_dump($_POST);
$ddj = date ('Y-m-d H:i:s');

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



  //var_dump($re);
  //var_dump($untr);
  //$amount = intval($_POST['amount']);




  $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
  $req_maj_don = $bdd -> prepare($req_majd);
  //$req_maj_don ->bindParam(':montant_transaction', $amount);
  $req_maj_don ->bindParam(':statut_transaction', $statutt);
  $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
  $req_maj_don ->execute();

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
  $public_key = "b9874f90c5fa11eab47ae11769a4e527";

  $private_key = "pk_b98776a1c5fa11eab47ae11769a4e527";

  $secret = "sk_b98776a2c5fa11eab47ae11769a4e527";


  $transaction_id = $_POST['transaction_id'];

  $kkiapay = new Kkiapay\Kkiapay($public_key, $private_key, $secret);
  $verify = $kkiapay->verifyTransaction($transaction_id);


  if(isset($_POST['transaction_id']) && !empty($_POST['transaction_id'])){


    $statutt = $verify  -> status;
    $amount = $verify  -> amount;
    $ref = $verify  -> state;
      //var_dump($verify);
        $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
        $un = $bdd -> query($re);
        $untr = $un->fetch();
      //  var_dump($re);
        //var_dump($untr);

    //$statutt = ($_POST['status'] == 0) ? 'SUCCESS' : 'FAIL' ;

    //$amount = intval($_POST['amount']);
    $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
    $req_maj_don = $bdd -> prepare($req_majd);
    //$req_maj_don ->bindParam(':montant_transaction', $amount);
    $req_maj_don ->bindParam(':statut_transaction', $statutt);
    $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
    $req_maj_don ->execute();


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


    header("Location:https://afreekaplay.com/callback?ref=".$ref);
  }
}
else if (isset($_POST['referenceID'])) {
  //itouch
  $statutt = ($_POST['status'] == 'SUCCESSFUL') ? 'SUCCESS' : 'FAIL' ;
  //echo $statutt;
  $ref = $_POST['referenceID'];
  $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
  $un = $bdd -> query($re);
  $untr = $un->fetch();
  //  var_dump($re);
    //var_dump($untr);

  //$statutt = ($_POST['status'] == 0) ? 'SUCCESS' : 'FAIL' ;

  //$amount = intval($_POST['amount']);
  $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
  $req_maj_don = $bdd -> prepare($req_majd);
  //$req_maj_don ->bindParam(':montant_transaction', $amount);
  $req_maj_don ->bindParam(':statut_transaction', $statutt);
  $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
  $req_maj_don ->execute();

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
        $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
        $un = $bdd -> query($re);
        $untr = $un->fetch();
        //var_dump($re);
        //var_dump($untr);


        		$req_aj_tel ->execute();
        // Verification de l'etat du traitement de la commande
        if ($untr['statut_transaction'] == 'SUCCESS') {
            // La commande a été déjà traité
            // Arret du script
            die();
        }




            if ($_POST['status'] == 'success') {


              $statutt = 'SUCCESS';

              //$amount = intval($_POST['amount']);
                $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
                $req_maj_don = $bdd -> prepare($req_majd);
                //$req_maj_don ->bindParam(':montant_transaction', $amount);
                $req_maj_don ->bindParam(':statut_transaction', $statutt);
                $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
                $req_maj_don ->execute();

                $affich_el[0]=1;
                $affich_el['rep']=0;
                $affich_e = array_shift($affich_el);
                //var_dump($affich_e);
                //var_dump($affich_el);

            } else {
              $statutt = 'FAIL';

              //$amount = intval($_POST['amount']);
                $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
                $req_maj_don = $bdd -> prepare($req_majd);
                //$req_maj_don ->bindParam(':montant_transaction', $amount);
                $req_maj_don ->bindParam(':statut_transaction', $statutt);
                $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
                $req_maj_don ->execute();

                $affich_el[0]=1;
                $affich_el['rep']=0;
                $affich_e = array_shift($affich_el);
                //var_dump($affich_e);
                //var_dump($affich_el);

            }

            echo json_encode($affich_el);
        } else {
          $statutt = 'FAIL';

          //$amount = intval($_POST['amount']);
            $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
            $req_maj_don = $bdd -> prepare($req_majd);
            //$req_maj_don ->bindParam(':montant_transaction', $amount);
            $req_maj_don ->bindParam(':statut_transaction', $statutt);
            $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
            $req_maj_don ->execute();

            $affich_el[0]=1;
            $affich_el['rep']=0;
            $affich_e = array_shift($affich_el);
            //var_dump($affich_e);
            //var_dump($affich_el);

        }


}
else {
  if ($_POST == null) {
    //Moov Flooz;

      $_POST = json_decode(file_get_contents('php://input'), true);
      //$_POST = (isset($_GET)) ? $_GET : $_POST;
      //$err = serialize($_POST);
      var_dump($_POST);
      $v1 = json_encode($_POST);
      $lib = "NIVEAU 3 --- ".$v1;
      //echo $lib;
      $req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
      $req_aj_tel = $bdd -> prepare($req_ajt);
      $req_aj_tel ->bindParam(':date_log', $ddj);
      $req_aj_tel ->bindParam(':libelle_log', $lib);
      $req_aj_tel ->execute();
      if(isset($_POST['partner_transaction_id'])){
        $statutt = ($_POST['status'] == 'SUCCESSFUL') ? 'SUCCESS' : 'FAIL' ;
        //echo $statutt;
        $ref = $_POST['partner_transaction_id'];
        $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
        $un = $bdd -> query($re);
        $untr = $un->fetch();
      //  var_dump($re);
        //var_dump($untr);

      //$statutt = ($_POST['status'] == 0) ? 'SUCCESS' : 'FAIL' ;

      //$amount = intval($_POST['amount']);
        $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
        $req_maj_don = $bdd -> prepare($req_majd);
        //$req_maj_don ->bindParam(':montant_transaction', $amount);
        $req_maj_don ->bindParam(':statut_transaction', $statutt);
        $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
        $req_maj_don ->execute();


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





?>
