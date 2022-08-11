<?php

require 'connexion.php';

function genererChaineAleatoire()
   {
    $listeCar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $listeNum = '0123456789';
    $chaine = '';
    $max = mb_strlen($listeCar, '8bit') - 1;
    $max2 = mb_strlen($listeNum, '8bit') - 1;
    for ($i = 0; $i < 7; $i++) {
    $chaine .= $listeCar[random_int(0, $max)];
    }
    //$chaine .= '-';
    for ($u = 0; $u < 4; $u++) {
    $chaine .= $listeNum[random_int(0, $max2)];
    }
    return $chaine;
   }


$re9 = "SELECT * FROM userO";
  $u = $bdd -> query($re9);
  // $nbuser = $u->rowCount();
  while ($iuser = $u->fetch()) {
    // $iuser['telephone_user'] = str_replace(" ", "", $iuser['telephone_user']);
    $ref = "AFPTXO-";
    $ref .= genererChaineAleatoire();
    $ref = $ref."-offert";
    echo $ref;
    $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'user' ");
    // $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'user' ");
    $donnees = $req->fetch();
    // $numero1 = str_replace(' ','',$iuser['telephone_user']);
    //
    // $req_majc = "UPDATE userO SET telephone_user=:telephone_user WHERE id_user=:id_user";
    // // echo $req_majt;
    // $req_maj_c = $bdd -> prepare($req_majc);
    // $req_maj_c ->bindParam(":telephone_user", $numero1);
    // $req_maj_c ->bindParam(":id_user", $iuser['id_user']);
    // $req_maj_c ->execute();
    $req_ajd = "INSERT INTO user (nom_user, telephone_user, email_user, dob_user, trancheAge_user, sexe_user, datenreg_user, statut_user, id_pays) VALUES (:nom_user, :telephone_user, :email_user, :dob_user, :trancheAge_user, :sexe_user, :datenreg_user, :statut_user, :id_pays)";
    $req_aj_don = $bdd -> prepare($req_ajd);
    $req_aj_don ->bindParam(':nom_user', $iuser['nom_user']);
    $req_aj_don ->bindParam(':telephone_user', $iuser['telephone_user']);
    $req_aj_don ->bindParam(':email_user', $iuser['email_user']);
    $req_aj_don ->bindParam(':dob_user', $iuser['dob_user']);
    $req_aj_don ->bindParam(':trancheAge_user', $iuser['trancheAge_user']);
    $req_aj_don ->bindParam(':sexe_user', $iuser['sexe_user']);
    $req_aj_don ->bindParam(':datenreg_user', $iuser['datenreg_user']);
    $req_aj_don ->bindParam(':statut_user', $iuser['statut_user']);
    $req_aj_don ->bindParam(':id_pays', $iuser['id_pays']);
    $req_aj_don ->execute();

    $solde = 0;
    $idDevis = 2;
    $req_ajw1 = "INSERT INTO walletP (solde_walletP, id_devise, id_user) VALUES (:solde_walletP, :id_devise, :id_user)";
    $req_ajwa1 = $bdd -> prepare($req_ajw1);
    $req_ajwa1 ->bindParam(':solde_walletP', $solde);
    $req_ajwa1 ->bindParam(':id_devise', $idDevis);
    $req_ajwa1 ->bindParam(':id_user', $donnees['Auto_increment']);
    $req_ajwa1 ->execute();

    $req_ajw2 = "INSERT INTO walletB (solde_walletB, id_devise, id_user) VALUES (:solde_walletB, :id_devise, :id_user)";
    $req_ajwa2 = $bdd -> prepare($req_ajw2);
    $req_ajwa2 ->bindParam(':solde_walletB', $solde);
    $req_ajwa2 ->bindParam(':id_devise', $idDevis);
    $req_ajwa2 ->bindParam(':id_user', $donnees['Auto_increment']);
    $req_ajwa2 ->execute();

    $montant = 0;
    $statut = "SUCCESS";
    $lib = "album-Salade de Slam-180";
    $pays = 4;
    $ip = "102.176.175.138";
    $req_aju2 = "INSERT INTO transaction (date_transaction, nom_transaction, telephone_transaction, montant_transaction, reference_transaction, statut_transaction, libelle_transaction, ip_transaction, id_user, id_pays) VALUES (:date_transaction, :nom_transaction, :telephone_transaction, :montant_transaction, :reference_transaction, :statut_transaction, :libelle_transaction, :ip_transaction, :id_user, :id_pays)";
    $req_ajua2 = $bdd -> prepare($req_aju2);
    $req_ajua2 ->bindParam(':date_transaction', $iuser['datenreg_user']);
    $req_ajua2 ->bindParam(':nom_transaction', $iuser['nom_user']);
    $req_ajua2 ->bindParam(':telephone_transaction', $iuser['telephone_user']);
    $req_ajua2 ->bindParam(':montant_transaction', $montant);
    $req_ajua2 ->bindParam(':reference_transaction', $ref);
    $req_ajua2 ->bindParam(':statut_transaction', $statut);
    $req_ajua2 ->bindParam(':libelle_transaction', $lib);
    $req_ajua2 ->bindParam(':ip_transaction', $ip);
    $req_ajua2 ->bindParam(':id_user', $donnees['Auto_increment']);
    $req_ajua2 ->bindParam(':id_pays', $pays);
    var_dump($req_ajua2);
    $req_ajua2 ->execute();



  }


// $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'user' ");
// $donnees = $req->fetch();
//
// $_GET['telephone'] = str_replace(" ", "", $_GET['telephone']);
// $indic = (substr($_GET['telephone'], 0, 2) == '00') ? substr($_GET['telephone'], 2, 3) : substr($_GET['telephone'], 0, 3);
// $re11 = "SELECT * FROM pays WHERE indicatif_pays = '".$indic."'";
// $p = $bdd -> query($re11);
// $pysUser = $p->fetch();
//
// $idPys = (isset($pysUser['id_pays'])) ? $pysUser['id_pays'] : '';
// $idDevis = (isset($pysUser['id_devise'])) ? $pysUser['id_devise'] : '1';
//
// $ddj = date ('Y-m-d H:i:s');
// if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//         $ip = $_SERVER['HTTP_CLIENT_IP'];
//     } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//     } else {
//         $ip = $_SERVER['REMOTE_ADDR'];
//     }
//
// $re9 = "SELECT * FROM user WHERE telephone_user = ".$_GET['telephone'];
//   $u = $bdd -> query($re9);
//   $nbuser = $u->rowCount();
//   //$iuser = $vu->fetch();
// if($nbuser <= 0) {
//
//   // function nbAleatoire($length){
//   // 	$tab_match = [];
//   // 	while(count($tab_match) < $length)
//   // 	{
//   // 		preg_match_all ('#\d#', hash("sha512",openssl_random_pseudo_bytes("128", $cstrong)), $matches);
//   // 		$tab_match = array_merge($tab_match,$matches[0]);
//   // 	}
//   // 	shuffle($tab_match);
//   // 	return implode('',array_slice($tab_match,0,$length));
//   // }
//   //$code = nbAleatoire(4);
//   if(isset($_GET['joursNaiss']) AND isset($_GET['moisNaiss'])){
//     $jrMoisNaiss = $_GET['joursNaiss']."-".$_GET['moisNaiss'];
//   } else {
//     $jrMoisNaiss = "00-00";
//   }
//   if (!isset($_GET['mail'])) {
//     $_GET['mail'] = "Neant";
//   }
//   $statut = 1;
//   $act = 1;
//
//   $req_ajd = "INSERT INTO user (nom_user, telephone_user, email_user, dob_user, trancheAge_user, sexe_user, datenreg_user, statut_user, id_pays) VALUES (:nom_user, :telephone_user, :email_user, :dob_user, :trancheAge_user, :sexe_user, :datenreg_user, :statut_user, :id_pays)";
//   $req_aj_don = $bdd -> prepare($req_ajd);
//   $req_aj_don ->bindParam(':nom_user', $_GET['nom']);
//   $req_aj_don ->bindParam(':telephone_user', $_GET['telephone']);
//   $req_aj_don ->bindParam(':email_user', $_GET['mail']);
//   $req_aj_don ->bindParam(':dob_user', $jrMoisNaiss);
//   $req_aj_don ->bindParam(':trancheAge_user', $_GET['tranche']);
//   $req_aj_don ->bindParam(':sexe_user', $_GET['sexe']);
//   $req_aj_don ->bindParam(':datenreg_user', $ddj);
//   $req_aj_don ->bindParam(':statut_user', $statut);
//   $req_aj_don ->bindParam(':id_pays', $pysUser['id_pays']);
//   $req_aj_don ->execute();
//
//   $req_ajco = "INSERT INTO connexion (date_connexion, ip_connexion, active_connexion, id_user) VALUES (:date_connexion, :ip_connexion, :active_connexion, :id_user)";
//   $req_ajcon = $bdd -> prepare($req_ajco);
//   $req_ajcon ->bindParam(':date_connexion', $ddj);
//   $req_ajcon ->bindParam(':ip_connexion', $ip);
//   $req_ajcon ->bindParam(':active_connexion', $act);
//   $req_ajcon ->bindParam(':id_user', $donnees['Auto_increment']);
//   $req_ajcon ->execute();
//
//   $solde = 0;
//   $req_ajw1 = "INSERT INTO walletP (solde_walletP, id_devise, id_user) VALUES (:solde_walletP, :id_devise, :id_user)";
//   $req_ajwa1 = $bdd -> prepare($req_ajw1);
//   $req_ajwa1 ->bindParam(':solde_walletP', $solde);
//   $req_ajwa1 ->bindParam(':id_devise', $idDevis);
//   $req_ajwa1 ->bindParam(':id_user', $donnees['Auto_increment']);
//   $req_ajwa1 ->execute();
//
//   $req_ajw2 = "INSERT INTO walletB (solde_walletB, id_devise, id_user) VALUES (:solde_walletB, :id_devise, :id_user)";
//   $req_ajwa2 = $bdd -> prepare($req_ajw2);
//   $req_ajwa2 ->bindParam(':solde_walletB', $solde);
//   $req_ajwa2 ->bindParam(':id_devise', $idDevis);
//   $req_ajwa2 ->bindParam(':id_user', $donnees['Auto_increment']);
//   $req_ajwa2 ->execute();

// $req_ajc = "INSERT INTO verif_user (code_verif_user, id_user) VALUES (:code_verif_user, :id_user)";
// 	$req_aj_cod = $bdd -> prepare($req_ajc);
// 	$req_aj_cod ->bindParam(':code_verif_user', $code);
// 	$req_aj_cod ->bindParam(':id_user', $donnees['Auto_increment']);
// 	$req_aj_cod ->execute();

// $msg = "Votre code de verification est ".$code;
// $numero = '225'.$_GET['telephone'];
// $url = 'http://beinevent.net/envoisms.php';
// $sid = 'eVallesse';
// $post_fields = array(
// 	'sender_sms' => $sid,
// 	'numero_sms' => $numero,
// 	'msg_sms' => $msg,
// );
// $get_url = $url . "?" . http_build_query($post_fields);
// //echo $get_url;
//
// $ch = curl_init();
//   curl_setopt($ch, CURLOPT_URL, $get_url);
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   $response_string = curl_exec( $ch );
// 	$curl_info = curl_getinfo( $ch );
//   curl_close($ch);
//   $rep['reponse'] = substr($response_string, 0, 7);
//   //$rep['reponse'] = "Success";
//   $rep['userID'] = $donnees['Auto_increment'];
//   $rep['telephone'] = $_GET['telephone'];
//   $rep['info'] = $response_string;
  // echo json_encode($rep);




//   $rep['reponse'] = "ok";
//   $rep['id'] = $donnees['Auto_increment'];
//   echo json_encode($rep);
// } else {
//   $rep['reponse'] = "erreur1";
//   echo json_encode($rep);
// }
?>
