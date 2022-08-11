<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

$statut = "erreur";

$filer = "../../file/".$_GET['f'];
//echo $filer;

//localhost:8888/afreekaplay/gest/downl.php?f=1.INSHALLAH.mp3&ref=AFPTX-fJgWUKh3025&fer=AFPsYrko-6679
require 'connexion.php';
var_dump($_GET);
$re = "SELECT * FROM transaction WHERE reference_transaction = '".$_GET['ref']."' AND statut_transaction = 'SUCCESS'";
$un = $bdd -> query($re);
$nbtrx = $un->rowCount();
$untr = $un->fetch();
var_dump($untr);
//echo $nbtrx;
if($nbtrx !== 0 ){
  if (file_exists($filer)) {

    //$tabl = $_POST['table'];
    $datee = date ('Y-m-d H:i:s');




    $tem = substr($_GET['fer'], 0, 4);
    echo $tem;
    if($tem == "AFPs"){
      $table = "telechargements";
      $re2 = "SELECT * FROM telechargements WHERE refSon_telechargements = '".$_GET['fer']."' AND id_transaction = ".$untr['id_transaction'];
      $un2 = $bdd -> query($re2);
      $nbtr2 = $un2->rowCount();
      $untr2 = $un2->fetch();
      $nbtel = $untr2['nombre_telechargements'];
      $refSA = "refSon_";
      $tableee = "son";

    } elseif ($tem == "AFPa"){
      $table = "telechargement";
      $re2 = "SELECT * FROM telechargement WHERE refAlb_telechargement = '".$_GET['fer']."' AND id_transaction = ".$untr['id_transaction'];
      $un2 = $bdd -> query($re2);
      $nbtr2 = $un2->rowCount();
      $untr2 = $un2->fetch();
      $nbtel = $untr2['nombre_telechargement'];
      $refSA = "refAlb_";
      $tableee = "album";

    }
  //  var_dump($untr2);
    if (($nbtr2 !== 0 AND $nbtel < 2) OR $nbtr2 == 0) {
      echo $nbtr2.'<br>-'.$nbtel;
      // header('Content-Description: File Transfer');
      // header('Content-Type: application/octet-stream');
      // header('Content-Disposition: attachment; filename='.basename($filer));
      // header('Content-Transfer-Encoding: binary');
      // header('Expires: 0');
      // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      // header('Pragma: public');
      // header('Content-Length: ' . filesize($filer));
      // if (ob_get_level()) {
      //   ob_end_clean();
      // }
      //
      // // ob_end_clean();
      // // flush();
      // readfile($filer);

      $statut = "SUCCESS";


      if(preg_match("#-don#", $untr['libelle_transaction'])){

        $infoT = explode('-',$untr['libelle_transaction']);
        $limTab = sizeof($infoT);
        $idSA = $infoT[$limTab-2];

      } else {
        $infoT = explode('-',$untr['libelle_transaction']);
        $limTab = sizeof($infoT);
        $idSA = $infoT[$limTab-1];
      }
      //var_dump($idSA );
      // $re2 = "SELECT * FROM ".$tableee." WHERE id_".$tableee." = '".$idSA."'";
      // $un2 = $bdd -> query($re2);
      // $untr2 = $un2->fetch();



      if ($nbtr2 !== 0) {
        $nbtel =$nbtel+1;
        $req_majt = "UPDATE ".$table." SET date_".$table."=:date_".$table.", nombre_".$table."=:nombre_".$table." WHERE id_".$table."=:id_".$table;
        echo $req_majt;
        $req_maj_t = $bdd -> prepare($req_majt);
        //$req_maj_don ->bindParam(':montant_transaction', $amount);
        $req_maj_t ->bindParam(":date_".$table, $datee);
        $req_maj_t ->bindParam(":nombre_".$table, $nbtel);
        $req_maj_t ->bindParam(":id_".$table, $untr2['id_'.$table]);
        $req_maj_t ->execute();
      } else {
        $nbtel = 1;
        $req_ajt = "INSERT INTO ".$table." (date_".$table.", statut_".$table.", libelle_".$table.", nombre_".$table.", ".$refSA.$table.", id_transaction, id_".$tableee.") VALUES (:date_".$table.", :statut_".$table.", :libelle_".$table.", :nombre_".$table.", :".$refSA.$table.", :id_transaction, :id_".$tableee.")";
        $value[0] = ":date_".$table;
        $value[1] = ":statut_".$table;
        $value[2] = ":libelle_".$table;
        $value[3] = ":nombre_".$table;
        $value[4] = ":".$refSA.$table;
        $value[5] = ":id_".$tableee;
        $req_aj_t = $bdd -> prepare($req_ajt);
        $req_aj_t ->bindParam($value[0], $datee);
        $req_aj_t ->bindParam($value[1], $statut);
        $req_aj_t ->bindParam($value[2], $infoT[1]);
        $req_aj_t ->bindParam($value[3], $nbtel);
        $req_aj_t ->bindParam($value[4], $_GET['fer']);
        $req_aj_t ->bindParam(":id_transaction", $untr['id_transaction']);
        $req_aj_t ->bindParam($value[5], $idSA);
          // $req_aj_t ->bindParam(":date_".$table, $datee);
          // $req_aj_t ->bindParam(":statut_".$table, $statut);
          // $req_aj_t ->bindParam(":libelle_".$table, $infoT[1]);
          // $req_aj_t ->bindParam(":nombre_".$table, $nbtel);
          // $req_aj_t ->bindParam(":".$refSA.$table, $_GET['fer']);
          // $req_aj_t ->bindParam(":id_transaction", $untr['id_transaction']);
          // $req_aj_t ->bindParam(":id_".$tableee, $idSA);
        // var_dump ($req_aj_t);
          $req_aj_t ->execute();
      }




    } else {
      echo "Nombre maximum de telechargement atteint";
      //header("Location:https://afreekaplay.com/");

    }

  }
  }
else {
  //echo "Pas de fichier!";
  //header("Location:https://afreekaplay.com/");

}


//$response = 'Téléchargement terminé.';
//exit;

 ?>
