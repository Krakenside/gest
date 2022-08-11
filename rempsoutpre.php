<?php
require 'connexion.php';

$re = "SELECT * FROM son";
$son = $bdd -> query($re);
// echo $re;


  while ($sons = $son->fetch()) {
    if ($sons[0] == 307 OR $sons[0] == 436 OR $sons[0] == 308 OR $sons[0] == 134 OR $sons[0] == 135 OR $sons[0] == 136 OR $sons[0] == 264 OR $sons[0] == 297 OR $sons[0] == 298 OR $sons[0] == 162 OR $sons[0] == 303) {

    } else {
      $mini = 500;
      $fich = 0;
      $typ = 0;
      $fich1 = 0;
      $lib = $sons[0]."-son";

      $req_ajt = "INSERT INTO soutCommande (minim_soutCommande, fichier_soutCommande, type_soutCommande, fichierpre_soutCommande, libelle_soutCommande) VALUES (:minim_soutCommande, :fichier_soutCommande, :type_soutCommande, :fichierpre_soutCommande, :libelle_soutCommande)";
      $req_aj_tel = $bdd -> prepare($req_ajt);
      $req_aj_tel ->bindParam(':minim_soutCommande', $mini);
      $req_aj_tel ->bindParam(':fichier_soutCommande', $fich);
      $req_aj_tel ->bindParam(':type_soutCommande', $typ);
      $req_aj_tel ->bindParam(':fichierpre_soutCommande', $fich1);
      $req_aj_tel ->bindParam(':libelle_soutCommande', $lib);
      $req_aj_tel ->execute();
    }



  }




?>
