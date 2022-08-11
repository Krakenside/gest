<?php
header("Access-Control-Allow-Origin: *");

 //var_dump ($_POST);
 set_time_limit(0);
 ini_set('memory_limit', '512M');

 require_once __DIR__.'/../vendor/autoload.php';
   require 'connexion.php';

var_dump($_POST);
$ddj = date ('Y-m-d H:i:s');

$v1 = json_encode($_POST);
$lib = "NIVEAU 1 --- ".$v1;
//echo $lib;
$req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
$req_aj_tel = $bdd -> prepare($req_ajt);
$req_aj_tel ->bindParam(':date_log', $ddj);
$req_aj_tel ->bindParam(':libelle_log', $lib);
$req_aj_tel ->execute();

 if(isset($_POST['cpm_trans_id'])) {

  $ddj = date ('Y-m-d H:i:s');

  $lib = "NIVEAU 2";
  //echo $lib;
  $req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
  $req_aj_tel = $bdd -> prepare($req_ajt);
  $req_aj_tel ->bindParam(':date_log', $ddj);
  $req_aj_tel ->bindParam(':libelle_log', $lib);
  $req_aj_tel ->execute();


    // SDK PHP de CinetPay
    require_once __DIR__ . '/cinetpay.php';
    //require_once __DIR__ . '/commande.php';

    //La classe commande correspond à votre colonne qui gère les transactions dans votre base de données
    //$commande = new Commande();
    try {
        // Initialisation de CinetPay et Identification du paiement
        $id_transaction = $_POST['cpm_trans_id'];
        $apiKey = "16711883825fc40fa94905c8.76481156";
        $site_id = 103571;
        $plateform = "PROD"; // Valorisé à PROD si vous êtes en production
        $CinetPay = new CinetPay($site_id, $apiKey, $plateform);
        // Reprise exacte des bonnes données chez CinetPay
        $CinetPay->setTransId($id_transaction)->getPayStatus();
        $cpm_site_id = $CinetPay->_cpm_site_id;
        $signature = $CinetPay->_signature;
        $cpm_amount = $CinetPay->_cpm_amount;
        $cpm_trans_id = $CinetPay->_cpm_trans_id;
        $cpm_custom = $CinetPay->_cpm_custom;
        $cpm_currency = $CinetPay->_cpm_currency;
        $cpm_payid = $CinetPay->_cpm_payid;
        $cpm_payment_date = $CinetPay->_cpm_payment_date;
        $cpm_payment_time = $CinetPay->_cpm_payment_time;
        $cpm_error_message = $CinetPay->_cpm_error_message;
        $payment_method = $CinetPay->_payment_method;
        $cpm_phone_prefixe = $CinetPay->_cpm_phone_prefixe;
        $cel_phone_num = $CinetPay->_cel_phone_num;
        $cpm_ipn_ack = $CinetPay->_cpm_ipn_ack;
        $created_at = $CinetPay->_created_at;
        $updated_at = $CinetPay->_updated_at;
        $cpm_result = $CinetPay->_cpm_result;
        $cpm_trans_status = $CinetPay->_cpm_trans_status;
        $cpm_designation = $CinetPay->_cpm_designation;
        $buyer_name = $CinetPay->_buyer_name;

        $ref = $cpm_trans_id;

        $opt = explode('-',$ref);
        if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
          $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$ref."'";
          $un = $bdd -> query($re);
          $untr = $un -> fetch();
        } else {
          $re = "SELECT * FROM transaction WHERE reference_transaction = '".$ref."'";
          $un = $bdd -> query($re);
          $untr = $un->fetch();
        }

        //var_dump($re);
        // var_dump($untr);
        $lib = $cpm_site_id.",".$signature.",".$cpm_amount.",".$cpm_trans_id.",".$cpm_currency.",".$cpm_payment_date.",error:".$cpm_error_message.",".$payment_method.",".$cel_phone_num.",result:".$cpm_result.",".$cpm_trans_status.",".$buyer_name;
        // echo $lib;
        $req_ajt = "INSERT INTO log (date_log,libelle_log) VALUES (:date_log,:libelle_log)";
    		$req_aj_tel = $bdd -> prepare($req_ajt);
    		$req_aj_tel ->bindParam(':date_log', $ddj);
    		$req_aj_tel ->bindParam(':libelle_log', $lib);

        		$req_aj_tel ->execute();
        // Verification de l'etat du traitement de la commande
        if ($untr['statut_transaction'] == 'SUCCESS') {
            // La commande a été déjà traité
            // Arret du script
            die();
        }


        //Le paiement est bon

        // On verifie que le montant payé chez CinetPay correspond à notre montant en base de données pour cette transaction
        if ($untr['montant_transaction'] == $cpm_amount) {
          echo "<br>montant pareil";
            // C'est OK : On continue le remplissage des nouvelles données

            if ($cpm_result == '00') {


              $statutt = 'SUCCESS';

              //$amount = intval($_POST['amount']);
              if (isset($opt[0]) AND $opt[0]=='AFPRCH') {

                $re = "SELECT * FROM walletP WHERE id_walletP = '".$untr['id_walletP']."'";
                $un2 = $bdd -> query($re);
                $wal = $un2 -> fetch();
                $solde = $wal['solde_walletP'];

                $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
                $req_maj_don = $bdd -> prepare($req_majd);

                $req_maj_don ->bindParam(':statut_rechargement', $statutt);
                $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
                $req_maj_don ->execute();

                $req_majw = "UPDATE walletP SET solde_walletP=:solde_walletP WHERE id_walletP=:id_walletP";
                $req_maj_wal = $bdd -> prepare($req_majw);
                $amountAj = $solde + $untr['montant_rechargement'];

                $req_maj_don ->bindParam(':solde_walletP', $amountAj);
                $req_maj_don ->bindParam(':id_walletP', $req_maj_wal['id_walletP']);
                $req_maj_don ->execute();

              } else {
                $req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
                $req_maj_don = $bdd -> prepare($req_majd);
                // $req_maj_don ->bindParam(':montant_transaction', $cpm_amount);
                $req_maj_don ->bindParam(':statut_transaction', $statutt);
                $req_maj_don ->bindParam(':id_transaction', $untr['id_transaction']);
                $req_maj_don ->execute();

                $lib = explode('-', $untr['libelle_transaction']);

                if (isset($untr['id_user']) AND $untr['montant_transaction'] >= 2000 AND end($lib) !== "don") {
                  $re = "SELECT * FROM walletB WHERE id_user = ".$untr['id_user'];
              		$rq = $bdd -> query($re);
              		$wall = $rq -> fetch();

                  $solde = $wall['solde_walletB'] + $untr['montant_transaction']*0.05;

            			$req_msol = 'UPDATE walletB SET solde_walletB=:sold WHERE id_walletB = :id';
            			$req_msold = $bdd -> prepare($req_msol);
            			$req_msold ->bindParam(':solde_walletB', $solde);
            			$req_msold ->bindParam(':id', $wall['id_walletB']);
            			$req_msold -> execute();
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
              if (isset($opt[0]) AND $opt[0]=='AFPRCH') {


                $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
                $req_maj_don = $bdd -> prepare($req_majd);

                $req_maj_don ->bindParam(':statut_rechargement', $statutt);
                $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
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
                $affich_el['rep']=1;
                $affich_e = array_shift($affich_el);
                //var_dump($affich_e);
                //var_dump($affich_el);

            }

            echo json_encode($affich_el);
        } else {
            //Fraude : montant payé ' . $cpm_amount . ' ne correspond pas au montant de la commande
            $statutt = 'FAIL';

            //$amount = intval($_POST['amount']);
            if (isset($opt[0]) AND $opt[0]=='AFPRCH') {


              $req_majd = "UPDATE rechargement SET statut_rechargement=:statut_rechargement WHERE id_rechargement=:id_rechargement";
              $req_maj_don = $bdd -> prepare($req_majd);

              $req_maj_don ->bindParam(':statut_rechargement', $statutt);
              $req_maj_don ->bindParam(':id_rechargement', $untr['id_rechargement']);
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
            $affich_el['rep']=1;
            $affich_e = array_shift($affich_el);
        }
    } catch (Exception $e) {
        echo "Erreur :" . $e->getMessage();
        // Une erreur s'est produite
    }
} else {
  echo "nada";
}





?>
