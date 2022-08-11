<?php
$nbb = random_int(0, 10000000);
// $input = $nbb.":vasconexprod:k6BvN5RUb5eNw82C";
$input = "8:vasconexprod:k6BvN5RUb5eNw82C";

$key = hex2bin("746C633132333435746C633132333435746C633132333435746C633132333435");
$encodedEncryptedData = base64_encode(@openssl_encrypt($input, "AES-256-CBC", $key, OPENSSL_RAW_DATA));
echo $encodedEncryptedData;
echo "<br>chiffre aleatoire: 8";
?>
