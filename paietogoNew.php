<?php
	header("Access-Control-Allow-Origin: *");
	set_time_limit(0);
 var_dump($_POST);
// require 'connexion.php';

		 // debitService: https://floozecommerce.moov-africa.tg/floozdebitapi/flooz/debitService
		 // verify: https://floozecommerce.moov-africa.tg/floozdebitapi/flooz/verify

 $encodedEncryptedData = $_POST['token'];
 $amount = $_POST['p'];
	$curl = curl_init();
			 curl_setopt_array($curl, [
		 		  CURLOPT_URL => "https://floozecommerce.moov-africa.tg/floozdebitapi/flooz/debitService",
		 		  CURLOPT_RETURNTRANSFER => true,
		 		  CURLOPT_ENCODING => "",
		 		  CURLOPT_MAXREDIRS => 10,
		 		  CURLOPT_TIMEOUT => 130,
				  CURLOPT_SSL_VERIFYHOST => false,
				  CURLOPT_SSL_VERIFYPEER => false,
		 		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		 		  CURLOPT_CUSTOMREQUEST => "POST",
		 		  CURLOPT_POSTFIELDS => "{\n\t\"msisdn\":\"".$_POST['n']."\", \n \t\"key\":\"MRCHO\", \n\t\"mrchrefid\":\"".$_POST['ref']."\", \n\t\"mrchname\":\"Moov Africa Play\", \n\t\"amount\":\"".$amount."\", \n\t\"partnermsisdn\":\"22800000492\"\n}",
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
 				echo "FLOOOOOOOOOOZ";
 		}





 ?>
