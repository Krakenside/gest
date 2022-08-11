<?php

header("Access-Control-Allow-Origin: *");
set_time_limit(0);
 var_dump($_GET);
require 'connexion.php';

$nbb = random_int(0, 10000000);
$input = $nbb.":vasconexprod:k6BvN5RUb5eNw82C";

$key = hex2bin("746C633132333435746C633132333435746C633132333435746C633132333435");
$encodedEncryptedData = base64_encode(openssl_encrypt($input, "AES-256-CBC", $key, OPENSSL_RAW_DATA));
$amount = 2000;
$_GET['ref'] = $nbb;

// $curl = curl_init("https://floozecommerce.moov-africa.tg/floozdebitapi/flooz/debitService");
//  // debitService: https://floozecommerce.moov-africa.tg/floozdebitapi/flooz/debitService
//  // verify: https://floozecommerce.moov-africa.tg/floozdebitapi/flooz/verify
//
$headers = ["Authorization: ".$encodedEncryptedData, "Content-Type: application/json"];
$rdfXML = "{\n\t\"msisdn\":\"".$_GET['n']."\", \n \t\"key\":\"MRCHO\", \n\t\"mrchrefid\":\"".$_GET['ref']."\", \n\t\"mrchname\":\"Moov Africa Play\", \n\t\"amount\":\"".$amount."\", \n\t\"partnermsisdn\":\"22800000492\"\n}";

var_dump($headers);
echo $rdfXML;
//
// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
// curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
// curl_setopt($curl, CURLOPT_TIMEOUT, 130);
// curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
// curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
// curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//
//
//  $first_response = curl_exec($curl);
//     var_dump($first_response);

$curl = curl_init();

$certificate = "./cacert.pem";

curl_setopt_array($curl, [
  CURLOPT_URL => "https://floozecommerce.moov-africa.tg/floozdebitapi/flooz/debitService",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 130,
  // CURLOPT_SSL_VERIFYHOST => false,
  // CURLOPT_SSL_VERIFYPEER => false,
  // CURLOPT_CAINFO => $certificate,
  // CURLOPT_CAPATH => $certificate,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n\t\"msisdn\":\"".$_GET['n']."\", \n \t\"key\":\"MRCHO\", \n\t\"mrchrefid\":\"".$_GET['ref']."\", \n\t\"mrchname\":\"Moov Africa Play\", \n\t\"amount\":\"".$amount."\", \n\t\"partnermsisdn\":\"22800000492\"\n}",
  CURLOPT_HTTPHEADER => [
    "Authorization: ".$encodedEncryptedData,
    "Content-Type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}


?>
