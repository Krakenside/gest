<?php
var_dump($_GET);
     /* You may need these ini settings too * /


set_time_limit(0);
ini_set('memory_limit', '512M');
        require_once __DIR__. '/vendor/autoload.php';

        // Importing DBConfig.php file.
        include 'DBconfig.php';

        // Creating connection.
        $con = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);

        mysqli_set_charset($con,"utf8mb4");

        $public_key = "b9874f90c5fa11eab47ae11769a4e527";

        $private_key = "pk_b98776a1c5fa11eab47ae11769a4e527";

        $secret = "sk_b98776a2c5fa11eab47ae11769a4e527";

        //$public_key = "ba2e3df0c5fa11ea84cb097ce4c306b7";

        //$private_key = "tpk_ba2e6501c5fa11ea84cb097ce4c306b7";

        //$secret = "tsk_ba2e6502c5fa11ea84cb097ce4c306b7";

        $kkiapay = new Kkiapay\Kkiapay($public_key, $private_key, $secret);

        $transaction_id = $_GET['transaction_id'];


            // echo "App = ".$_GET['app'];
            $responseHeader = 'Merci';
            $transaction_id = $_GET['transaction_id'];

            $verify = $kkiapay->verifyTransaction($transaction_id);
            var_dump($verify);*/


/*
require 'connexion1.php';
$a=0;
$trs = "aaaaaaaa";
$suc = "SUCCESS";
$item = "22010061";
$prix = "200";
  while ($a < 250) {
    $trsx = "aaaaaaaa".$a;

    $req_ajd = "INSERT INTO transaction_table (transactionId, itemId, amount, statut) VALUES (:transactionId, :itemId, :amount, :statut)";
    $req_aj_don = $bdd -> prepare($req_ajd);
    $req_aj_don ->bindParam(':transactionId', $trsx);
    $req_aj_don ->bindParam(':itemId', $item);
    $req_aj_don ->bindParam(':amount', $prix);
    $req_aj_don ->bindParam(':statut', $suc);
    $req_aj_don ->execute();
    echo $a;
    $a++;
  }
*/


?>
