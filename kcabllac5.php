<?php
header("Access-Control-Allow-Origin: *");

 // var_dump ($_POST);
 set_time_limit(0);
 ini_set('memory_limit', '512M');

 require_once __DIR__.'/../vendor/autoload.php';
   require 'connexion.php';

// var_dump($_POST);
$ddj = date ('Y-m-d H:i:s');


var_dump($_GET);


if(isset($_GET)) {




} else {
  echo "nada";
  //header("Location:https://afreekaplay.com/artists");

}





?>
