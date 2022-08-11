<?php
/*
Orange CI: OMCIV2
MTN CI: MOMO
Moov CI: FLOOZ
Moov benin: FLOOZBJ
Mtn Benin: MOMOBJ
Orange burkina: OMBF
Orange Mali: OMML
carte : CARD

*/
require 'connexion.php';


 // ini_set("soap.wsdl_cache_enabled", 0);
 // $url="https://www.paiementpro.net/webservice/OnlineServicePayment_v2.php?wsdl";
 // $client = new SoapClient($url,array('cache_wsdl' => WSDL_CACHE_NONE));

ini_set("soap.wsdl_cache_enabled", 0);
$context = stream_context_create(array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
));
$url="https://www.paiementpro.net/webservice/OnlineServicePayment_v2.php?wsdl";
$client = new SoapClient($url, array('stream_context' => $context));


 $opt = explode('-',$_GET['ref']);
 // var_dump ($opt);
 if (isset($opt[0]) AND $opt[0]=='AFPRCH') {
   $re = "SELECT * FROM rechargement WHERE reference_rechargement = '".$_GET['ref']."'";
   $un = $bdd -> query($re);
   $elt = $un -> fetch();
   $amount = $elt['montant_rechargement'];
   $optwal = "&wall=1";
 } else {
   $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."'";
   $un = $bdd -> query($re);
   $elt = $un -> fetch();
   $optwal = "";
   // var_dump($elt);

    $lib = explode('-', $elt['libelle_transaction']);
    $tail = sizeof($lib);

    if (end($lib) == "don") {
    	$id = $lib[$tail-2];
    } else {
    	$id = $lib[$tail-1];
    	//var_dump($id);

    }
    if (end($lib) == "don") {
    	$amount = $elt['montant_transaction'] + ($elt['montant_transaction']*0.05);

    } else {

    	$re = "SELECT * FROM ".$lib[0]." WHERE ".$lib[0].".id_".$lib[0]." = ".$id;
    	// echo $re;
    	$son = $bdd -> query($re);
    	//$nbson = $son -> rowCount();
    	$sons = $son->fetch();

     $amount = $sons['prix_'.$lib[0]]+($sons['prix_'.$lib[0]]*0.05);


     $amount = (isset($_GET['pc'])) ? $amount*2 : $amount;
    }
   // echo $amount;
 }

 if ($amount == 0) {
 	$statutt = "SUCCESS";
 	$req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
   $req_maj_don = $bdd -> prepare($req_majd);
   //$req_maj_don ->bindParam(':montant_transaction', $amount);
   $req_maj_don ->bindParam(':statut_transaction', $statutt);
   $req_maj_don ->bindParam(':id_transaction', $elt['id_transaction']);
   $req_maj_don ->execute();
 	header("Location:https://afreekaplay.com/callback?ref=".$_GET['ref']);

 } else {

   $array=array( 'merchantId'=>'PP-F112',
   'countryCurrencyCode'=>'952',
   'amount'=>$amount,
   'customerId'=>1,
   'channel'=>$_GET['rsx'],
   'customerEmail'=>'contact@afreekaplay.com',
   'customerFirstName'=>$_GET['nm'],
   'customerLastname'=>'LN',
   'service'=>'Afreekaplay',
   'customerPhoneNumber'=>$_GET['n'],
   'referenceNumber'=>$_GET['ref'],
   'notificationURL'=>'https://www.afreekaplay.com/gest/kcabllac4.php'.$optwal,
   'returnURL'=>'https://www.afreekaplay.com/gest/kcabllac4.php'.$optwal,
   'description'=>'achat '.$_GET['desc'],
   //'returnContext'=>'test=2&ok=1&oui=2',
    );
   try{
   $response=$client->initTransact($array);
   // var_dump($array);
   // var_dump($response);
  if($response->Code==0){

    //var_dump($response->Sessionid);
    //die();

    header("Location:https://www.paiementpro.net/webservice/onlinepayment/processing_v2.php?sessionid=".$response->Sessionid);

  }


    }
     catch(Exception $e)
    {
    echo $e->getMessage();
     }

 }
?>
