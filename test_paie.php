<?php

 ini_set("soap.wsdl_cache_enabled", 0);
 $url="https://www.paiementpro.net/webservice/OnlineServicePayment_v2.php?wsdl";
 $client = new SoapClient($url,array('cache_wsdl' => WSDL_CACHE_NONE));
 $array=array( 'merchantId'=>'PP-F122',
 'countryCurrencyCode'=>'952',
 'amount'=>1000,
 'customerId'=>1,
 'channel'=>'OMML',
 'customerEmail'=>'t@t.ci',
 'customerFirstName'=>'Thierry',
 'customerLastname'=>'Narcisse',
 'customerPhoneNumber'=>'22607021312',
 'referenceNumber'=>'878AABCDEFZ'.time(),
 'notificationURL'=>'http://test.ci/notification/',
 'returnURL'=>'http://test.ci/return/',
 'description'=>'achat en ligne',
 'returnContext'=>'test=2&ok=1&oui=2',
  );
 try{
 $response=$client->initTransact($array);
if($response->Code==0){

//var_dump($response->Sessionid);die();

header("Location:https://www.paiementpro.net/webservice/onlinepayment/processing_v2.php?sessionid=".$response->Sessionid);

}


  }
   catch(Exception $e)
  {
  echo $e->getMessage();
   }
?>
