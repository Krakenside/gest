<?php
/* class PersonnagesManager
{
  private $_db; // Instance de PDO

  public function __construct($db)
  {
    $this->setDb($db);
  }

  public function add(Personnage $perso)
  {
    $q = $this->_db->prepare('INSERT INTO personnages(nom, forcePerso, degats, niveau, experience) VALUES(:nom, :forcePerso, :degats, :niveau, :experience)');

    $q->bindValue(':nom', $perso->nom());
    $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);

    $q->execute();
  }

  public function delete(Personnage $perso)
  {
    $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id());
  }

  public function get($id)
  {
    $id = (int) $id;

    $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM personnages WHERE id = '.$id);
    $donnees = $q->fetch(PDO::FETCH_ASSOC);

    return new Personnage($donnees);
  }

  public function getList()
  {
    $persos = [];

    $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM personnages ORDER BY nom');

    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      $persos[] = new Personnage($donnees);
    }

    return $persos;
  }

  public function update(Personnage $perso)
  {
    $q = $this->_db->prepare('UPDATE personnages SET forcePerso = :forcePerso, degats = :degats, niveau = :niveau, experience = :experience WHERE id = :id');

    $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);

    $q->execute();
  }

  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
} */
	header("Access-Control-Allow-Origin: *");
	echo 'rrrrr';
	session_start();
 var_dump ($_GET);
if (isset($_SESSION['connect']))//On vérifie que le variable existe.
{
        $connect=$_SESSION['connect'];//On récupère la valeur de la variable de session.
}
else
{
        $connect=0;//Si $_SESSION['connect'] n'existe pas, on donne la valeur "0".
}
$connect = "1";
if ($connect == "1") // Si le visiteur s'est identifié.
{

 if (isset($_GET["table"])){
	 $_POST = $_GET;
	 $_POST["table"] = $_GET["table"];
	 $_POST["nom_fct"] = $_GET["nom_fct"];


 } else{}
	 var_dump ($_POST);

	 $dte = date('d-m-y');
	 $dtime = date("Y-m-d H:i:s");
	require 'connexion.php';
	if(isset($_POST["table"])){
		// echo $_POST["table"];
		/* $tabl = $_POST["table"];
		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
		$i=0;
		while($champ = $req->fetch(PDO::FETCH_ASSOC))
 {	//if(!preg_match("#id#", $champ["Field"])){
	 $table[$i] = $champ["Field"];
	 $valeur[$i] = $_POST[$champ["Field"]];
	 $i++;
 //}
 } */
 function genererChaineAleatoire()
{
	$listeCar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$listeNum = '0123456789';
 $chaine = '';
 $max = mb_strlen($listeCar, '8bit') - 1;
 $max2 = mb_strlen($listeNum, '8bit') - 1;
 for ($i = 0; $i < 4; $i++) {
 $chaine .= $listeCar[random_int(0, $max)];
 }
 $chaine .= '-';
 for ($u = 0; $u < 4; $u++) {
 $chaine .= $listeNum[random_int(0, $max2)];
 }
 return $chaine;
}

	function ajouter(){

		require 'connexion.php';
		$tabl = $_POST["table"];
 	 $dtime = date("Y-m-d H:i:s");
		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
		$i=0;
		while($champ = $req->fetch(PDO::FETCH_ASSOC))
	 {	//if(!preg_match("#id#", $champ["Field"])){
		 $table[$i] = $champ["Field"];
		 if ($champ["Field"] == "is_active") {
		 	$_POST["is_active"] = 1;
		 }
		 //echo $_POST[$champ["Field"]];

		 $i++;
	 //}
	 }
	 $_POST["dte_enr"] = $dtime;
	 if($tabl == 'user'){
		 $_POST["date_verif_user"] = $dtime;
		 // $_POST["dte_enr"] = $dtime;
		 $_POST["password_user"] = md5($_POST["password_user"]);
	 }


	 if($tabl == 'admin'){
		 $_POST["password_admin"] = md5($_POST["password_admin"]);
	 }

	 if($tabl == 'client'){
		 $_POST["date_verif"] = $dtime;
	 $_POST["is_active"] = 1;
		 $_POST["password_client"] = md5($_POST["password_client"]);
		 //$req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE '".$tabl."' "); //==========================================================================
		 $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE '".$tabl."' "); //==========================================================================
			$clt = $req->fetch();
	 }

	 if($tabl == 'maison' OR $tabl == "artiste" OR $tabl == "auteurS" OR $tabl == "apporteur"){
		 $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'client' "); //==========================================================================
		 	 // $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'client' ");// ==========================================================================
			$clt = $req->fetch();
			$req1 = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE '".$tabl."' "); //==========================================================================
			// $req1 = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE '".$tabl."' "); //==========================================================================
			 $ta = $req1->fetch();
			 if ($tabl == "artiste") {

 	 		 $_POST["lien_artiste"] = str_replace(" ", "_", $_POST["nom_artiste"]);
			 }
			$_POST["password_client"] = md5($_POST["password_client"]);
 		 	$_POST["is_active"] = 1;
	 	}

	 if($tabl == 'son'){

		 $chaine = genererChaineAleatoire();
		 $_POST["reference_son"] = 'AFPs'.$chaine;
		 $_POST["visible_son"] = 1;
		 $_POST["is_active"] = 1;
		 $_POST["dte_enr_son"] = $dtime;
		 $_POST["lien_son"] = str_replace(" ", "_", $_POST["titre_son"]);
		 $_POST["id_album"] = ($_POST["id_album"] !== "") ? $_POST["id_album"] : NULL ;
		 $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE '".$tabl."' "); //==========================================================================
		// $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE '".$tabl."' "); //==========================================================================
		 	$sonpi = $req->fetch();
	 }

	 if($tabl == 'album'){

		 $chaine = genererChaineAleatoire();
		 $_POST["reference_album"] = 'AFPa'.$chaine;
		 $_POST["lien_album"] = str_replace(" ", "_", $_POST["titre_album"]);
		 $_POST["is_active"] = 1;
		 $_POST["visible_album"] = 1;
		 $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE '".$tabl."' "); //==========================================================================
		// $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE '".$tabl."' "); //==========================================================================
		 	$albpi = $req->fetch();
	 }



	 $i=1;
	 $indxfich=0;
	 	while($i < sizeof($table))
	 {
		if(preg_match("#image#", $table[$i])){

		 $ch_image = $table[$i];
		 //echo $_FILES[$ch_image];
		 $valeur[$i] = $_FILES[$ch_image]['name'];

	 } else if(preg_match("#fichier#", $table[$i]) OR preg_match("#cover#", $table[$i])){

		 $ch_fich[$indxfich] = $table[$i];
		 $cover = (preg_match("#cover#", $table[$i])) ? 'cover-' : '';
		 //var_dump($_FILES[$ch_fich[$indxfich]]);
		 //echo $cover;
		 //$valeur[$i] = $_FILES[$ch_fich]['name'];
		 $infosfichier = pathinfo($_FILES[$ch_fich[$indxfich]]['name']);
			 $extension_upload = $infosfichier['extension'];
		$valeur[$i] = $cover.$_POST[$table[1]].'.'.$extension_upload;
		if ($cover == '') {
			if($tabl == 'son'){
 			 $_POST["url_son"] = 'https://afreekaplay.com/son/'.$valeur[$i];

 		 }

 		 if($tabl == 'album'){
 			 $_POST["url_album"] = 'https://afreekaplay.com/album/'.$valeur[$i];
 			 $chaine = genererChaineAleatoire();
 			 $_POST["reference_album"] = 'AFPa'.$chaine;
 		 }
	 } else {
		 $valeur[$i] = "https://afreekaplay.com/site/assets/images/cover/large/".$valeur[$i];
	 }
	 if (preg_match("#fichier#", $table[$i])) {
	 	$valeur[$i] = basename(str_replace(" ", "_", $_FILES[$ch_fich[$indxfich]]['name']));
	 }


		$indxfich++;

	 }
	  else{
			//echo $_POST[$table[$i]].'-';
		 $valeur[$i] = $_POST[$table[$i]];
		}
	 $i++;
	 }

	 //--- Fichier soutient et precommande
	 if ($tabl == "album" OR $tabl == "son") {
		 $fichier_sout = 0;
		 $fichier_prec = 0;
	 	if (isset($_POST['fichier_soutCommande'])) {


			if ($_POST['fichier_soutCommande'] == '1') {
				$fichier_sout = basename(str_replace(" ", "_", $_FILES['fichier_'.$tabl]['name']));
			} else if($_POST['fichier_soutCommande'] == '2') {
				if (isset($_FILES['fichier_soutCommand'])) {
					$fichier_sout = basename(str_replace(" ", "_", $_FILES['fichier_soutCommand']['name']));
					$ch_fich[$indxfich] = 'fichier_soutCommand';
					$indxfich++;
				}

			}


			if ($tabl == "son") {
				$minimSout = (isset($_POST['minim_soutCommande']) AND$_POST['minim_soutCommande'] !== '') ? $_POST['minim_soutCommande'] : 500;
				$idLib = $sonpi["Auto_increment"];

			} else {
				$minimSout = (isset($_POST['minim_soutCommande']) AND$_POST['minim_soutCommande'] !== '') ? $_POST['minim_soutCommande'] : 3000;
				$idLib = $albpi["Auto_increment"];
			}


	 	} else {
			if ($tabl == "son") {
				$minimSout = 500;
				$idLib = $sonpi["Auto_increment"];

			} else {
				$minimSout = 3000;
				$idLib = $albpi["Auto_increment"];
			}
	 	}
		if (isset($_POST['type_soutCommande'])) {
			if($_POST['type_soutCommande'] !== '0') {
				if (isset($_FILES['fichierpre_soutCommande'])) {
					$fichier_prec = basename(str_replace(" ", "_", $_FILES['fichierpre_soutCommande']['name']));
					$ch_fich[$indxfich] = 'fichierpre_soutCommande';
					$indxfich++;
				}

			}
		} else {
			$_POST['type_soutCommande'] = 0;
		}

		$libsoutPre = $idLib.'-'.$tabl;

		$req_ajsout = "INSERT INTO soutCommande (minim_soutCommande, fichier_soutCommande, type_soutCommande, fichierpre_soutCommande, libelle_soutCommande) VALUES (:minim_soutCommande, :fichier_soutCommande, :type_soutCommande, :fichierpre_soutCommande, :libelle_soutCommande)";
		$req_aj_soupre = $bdd -> prepare($req_ajsout);
		$req_aj_soupre ->bindParam(':minim_soutCommande', $minimSout);
		$req_aj_soupre ->bindParam(':fichier_soutCommande', $fichier_sout);
		$req_aj_soupre ->bindParam(':type_soutCommande', $_POST["type_soutCommande"]);
		$req_aj_soupre ->bindParam(':fichierpre_soutCommande', $fichier_prec);
		$req_aj_soupre ->bindParam(':libelle_soutCommande', $libsoutPre);
		$req_aj_soupre ->execute();
	 }

	 //var_dump($_POST);
	 //var_dump($_FILES);

	 $taille = sizeof($table);
		/*		$i = 1;
			while($i<$taille){
				$valeur[$i] = $_POST[$table[$i]];
				$i++;
			}*/

			$req_ajou = ' INSERT INTO '.$tabl.' (';  //INSERT INTO geek (image_geek, texte_geek, date_geek, titre_geek, id_auteur) VALUES ( :image_geek, :texte_geek, :date_geek, :titre_geek, :id_auteur)

			$u = 1;
		while($u<$taille){
			$v = $taille - 1;
			$req_ajou .= $table[$u];
			if($u !== $v){
				$req_ajou .= ", ";
			} else {
				$req_ajou .= ") ";
			}
			$u++;
		}
		$req_ajou .= "VALUES (";
				$w = 1;
		while($w<$taille){
			$y = $taille - 1;
			$req_ajou .= ':'.$table[$w];
			if($w !== $y){
				$req_ajou .= ", ";
			} else {
				$req_ajou .= ") ";
			}
			$w++;
		}
		//echo $req_ajou;
	 $req_ajou_donne = $bdd -> prepare($req_ajou);

			$i=1;
			//echo $taille;
			while($i<$taille){
				$value = ":".$table[$i];
				//echo $value;
										$req_ajou_donne ->bindParam($value, $valeur[$i]);

	  $i++;
	 }
						$req_ajou_donne -> execute();


		if (($tabl == "maison" OR $tabl == "artiste" OR $tabl == "auteurS") AND $_POST['email_client'] !== "") {


			$_POST["id_typeClient"] = ($tabl == "artiste") ? 4 : 3 ;
			$req_ajd = "INSERT INTO client (nom_client, email_client, password_client) VALUES (:nom_client, :email_client, :password_client)";
			$req_aj_don = $bdd -> prepare($req_ajd);
			$req_aj_don ->bindParam(':nom_client', $_POST["nom_client"]);
			$req_aj_don ->bindParam(':email_client', $_POST["email_client"]);
			$req_aj_don ->bindParam(':password_client', $_POST["password_client"]);
			$req_aj_don ->execute();

			$req_ajd1 = "INSERT INTO ".$tabl."_client (id_client, id_".$tabl.") VALUES (:id_client, :id_".$tabl.")";
			$req_aj_don1 = $bdd -> prepare($req_ajd1);
			$req_aj_don1 ->bindParam(':id_client', $clt['Auto_increment']);
			$req_aj_don1 ->bindParam(':id_'.$tabl, $ta["Auto_increment"]);
			$req_aj_don1 ->execute();
			$req_ajd2 = "INSERT INTO client_typeClient (id_client, id_typeClient) VALUES (:id_client, :id_typeClient)";
			$req_aj_don2 = $bdd -> prepare($req_ajd2);
			$req_aj_don2 ->bindParam(':id_client', $clt['Auto_increment']);
			$req_aj_don2 ->bindParam(':id_typeClient', $_POST["id_typeClient"]);
			$req_aj_don2 ->execute();

		}
		if (($tabl == "client") AND $_POST['email_client'] !== "") {

			$req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE '".$tabl."' "); //==========================================================================
			//$req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE '".$tabl."' "); //==========================================================================
			 $ta = $req->fetch();



			// $req_ajd = "INSERT INTO client (nom_client, email_client, password_client) VALUES (:nom_client, :email_client, :password_client)";
			// $req_aj_don = $bdd -> prepare($req_ajd);
			// $req_aj_don ->bindParam(':nom_client', $_POST["nom_client"]);
			// $req_aj_don ->bindParam(':email_client', $_POST["email_client"]);
			// $req_aj_don ->bindParam(':password_client', $_POST["password_client"]);
			// $req_aj_don ->execute();
			$tabl1 = ($_POST['id_typeClient'] == 4) ? "artiste" : "maison" ;
			if ($_POST['id_typeClient'] == 4) {
				echo ($_POST['id_artiste'].'- arituse') ;
				$tab_li = explode(',', $_POST['id_artiste']);
				$uu=0;
				while ($uu < sizeof($tab_li)) {
					$req_ajd = "INSERT INTO ".$tabl1."_client (id_client, id_".$tabl1.") VALUES (:id_client, :id_".$tabl1.")";
					$req_aj_don = $bdd -> prepare($req_ajd);
					$req_aj_don ->bindParam(':id_client', $clt['Auto_increment']);
					$req_aj_don ->bindParam(':id_'.$tabl1, $tab_li[$uu]);
					$req_aj_don ->execute();
					$uu++;
				}
			} else {
				$req_ajd = "INSERT INTO ".$tabl1."_client (id_client, id_".$tabl1.") VALUES (:id_client, :id_".$tabl1.")";
				$req_aj_don = $bdd -> prepare($req_ajd);
				$req_aj_don ->bindParam(':id_client', $clt['Auto_increment']);
				$req_aj_don ->bindParam(':id_'.$tabl1, $_POST["id_maison"]);
				$req_aj_don ->execute();
				//$_POST["id_typeClient"] = 3;
				echo($_POST["id_maison"].'- maison');
			}
			$req_ajd2 = "INSERT INTO client_typeClient (id_client, id_typeClient) VALUES (:id_client, :id_typeClient)";
			$req_aj_don2 = $bdd -> prepare($req_ajd2);
			$req_aj_don2 ->bindParam(':id_client', $clt['Auto_increment']);
			$req_aj_don2 ->bindParam(':id_typeClient', $_POST["id_typeClient"]);
			$req_aj_don2 ->execute();



		}

		// if ($tabl == "artiste") {
		// 	$req_ajd = "INSERT INTO client (nom_client, email_client, password_client) VALUES (:nom_client, :email_client, :password_client)";
		// 	$req_aj_don = $bdd -> prepare($req_ajd);
		// 	$req_aj_don ->bindParam(':nom_client', $idPaj[$indc]);
		// 	$req_aj_don ->bindParam(':email_client', $idCle);
		// 	$req_aj_don ->bindParam(':password_client', $titrcm);
		// 	$req_aj_don ->execute();
		// }
		if ($tabl == "son" AND $_POST["texte_lyric"]) {
			$req_ajd = "INSERT INTO lyric (texte_lyric, id_son) VALUES (:texte_lyric, :id_son)";
			$req_aj_don = $bdd -> prepare($req_ajd);
			$req_aj_don ->bindParam(':texte_lyric', $_POST["texte_lyric"]);
			$req_aj_don ->bindParam(':id_son', $sonpi["Auto_increment"]);
			$req_aj_don ->execute();
		}


						// Testons si l'image a bien été envoyé et s'il n'y a pas d'erreur

		if (isset($ch_image))
			{
			//ini_set('memory_limit', 800000000); // en octets
			//set_time_limit(6000000000);
		if (isset($_FILES[$ch_image]) AND $_FILES[$ch_image]['error'] == 0)
			{
			// Testons si le fichier n'est pas trop gros
				if ($_FILES[$ch_image]['size'] <= 1000000)
					{
			// Testons si l'extension est autorisée
				$infosfichier = pathinfo($_FILES[$ch_image]['name']);
					$extension_upload = $infosfichier['extension'];
					$extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
					if (in_array($extension_upload, $extensions_autorisees))
						{
			// On peut valider le fichier et le stockerdéfinitivement

				move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'site/assets/images/' .basename($_FILES[$ch_image]['name']));

				echo "L'image a bien été envoyé ! <br />";
						}
					}
			} else {echo "pas de fichier image";} }

			for ($o=0; $o < $indxfich; $o++) {
						ini_set('post_max_size', 50000000000); // en octets
						set_time_limit(0);

				// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
				if (isset($ch_fich[$o]))
				{
				if (isset($_FILES[$ch_fich[$o]]) AND $_FILES[$ch_fich[$o]]['error'] == 0)
				{
				//var_dump($_FILES);
				// Testons si le fichier n'est pas trop gros
					if ($_FILES[$ch_fich[$o]]['size'] <= 50000000000)
						{
				// Testons si l'extension est autorisée
					$infosfichier = pathinfo($_FILES[$ch_fich[$o]]['name']);
						$extension_upload = $infosfichier['extension'];
						$extensions_autorisees = array('jpg', 'jpeg', 'gif','png','pdf','mp3','rar','zip','iso','dmg');
						if (in_array($extension_upload, $extensions_autorisees))
							{
				// On peut valider le fichier et le stockerdéfinitivement
	 		 $cover = (preg_match("#cover#", $ch_fich[$o])) ? 'cover-' : '';
			 	if($cover !== ''){
					move_uploaded_file($_FILES[$ch_fich[$o]]['tmp_name'], '../site/assets/images/cover/large/' .$cover.$_POST[$table[1]].'.'.$infosfichier['extension']);
				} else {
					if($tabl == "son"){
						move_uploaded_file($_FILES[$ch_fich[$o]]['tmp_name'], '../../file/'.basename(str_replace(" ", "_", $_FILES[$ch_fich[$o]]['name'])));
						$target_path = '../../file/' .basename(str_replace(" ", "_", $_FILES[$ch_fich[$o]]['name']));
						$target_path_2 = '../site/file/' .basename(str_replace(" ", "_", $_FILES[$ch_fich[$o]]['name']));
    				copy($target_path, $target_path_2);
					} else if($tabl == "album"){
						move_uploaded_file($_FILES[$ch_fich[$o]]['tmp_name'], '../../file/'.basename(str_replace(" ", "_", $_FILES[$ch_fich[$o]]['name'])));
						$target_path = '../../file/' .basename(str_replace(" ", "_", $_FILES[$ch_fich[$o]]['name']));
						$target_path_2 = '../site/file/' .basename(str_replace(" ", "_", $_FILES[$ch_fich[$o]]['name']));
    				//copy($target_path, $target_path_2);
					}
				}
					echo "Le fichier a bien été envoyé ! <br />";
							}
						}
					} else {echo "pas de fichier fichier";}
					}
				}

				if ($tabl == "son" OR $tabl == "album") {
					$reart = "SELECT * FROM artiste WHERE id_artiste =".$_POST['id_artiste'];
						$art = $bdd -> query($reart);
				      $arti = $art->fetch();
						$exten = ($tabl == "son") ? "_son" : "";

					$curl = curl_init("http://beinevent.net/ext/PHPMailer-master/examples/envMailAFP.php");

 				$headers = ["Content-Type: application/x-www-form-urlencoded"];
 				$rdfXML = "art=".$arti['nom_artiste']."&titre=".$_POST['titre_'.$tabl]."&dds=".$_POST['dtdte_enr'.$exten]."&ddmel=".$_POST['date_verif']."&type=".$tabl."&mail=".$_SESSION['email']."&inscr=1";


 				 echo $rdfXML;

 				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
 				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
 				curl_setopt($curl, CURLOPT_TIMEOUT, 130);
 				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
 				curl_setopt($curl, CURLOPT_HEADER, 1);
 				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
 				curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
 				curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
 				 $first_response2 = curl_exec($curl);
 				 $err = curl_error($curl);
 				 if ($err) {
 					  echo "cURL Error #:" . $err;
 					} else {
 					  echo $first_response2;

 					}
				} else {
					// code...
				}




				if($tabl == 'playlist' OR $tabl == 'userplaylist'){
					echo $doncm['Auto_increment'];
				} else if($tabl == 'maison_client' OR $tabl == 'artiste_client' OR $tabl == 'client'){
					header("location: ajoutusr.php?t=".$tabl."&m=1");
				} else {
				header("location: ajout.php?t=".$tabl."&m=1");
						//echo '<br> cest bon';
				}
	}


	/* ------------- FONCTION AFFICHER -----------------*/


	function afficher(){

		require 'connexion.php';
 	 $dtime = date("Y-m-d H:i:s");
		$tabl = $_POST["table"]; // recuperer la valeur de notre input table

		  if($_POST['table'] == "admin"){
			$e=1;

			} else if($_POST['table'] == "maison"){
				$e=2;
			} else if($_POST['table'] == "artiste"){
				$e=3;

		  }
		  else if($_POST['table'] == "album"){
			$e=4;
		  }
		  else if($_POST['table'] == "son"){
			$e=5;
		  }
		  else if($_POST['table'] == "telechargement"){
			$e=6;
		  }
		  else if($_POST['table'] == "transaction"){
			$e=7;
		  }
		  else if($_POST['table'] == "playlist"){
			$e=8;

		  }
		  else if($_POST['table'] == "user"){
			$e=9;

		  }
		  else if($_POST['table'] == "userplaylist"){
			$e=10;

		  }

		  else if($_POST['table'] == "client"){
			$e=11;

		  }

		  else if($_POST['table'] == "auteurS"){
			$e=12;

		  }

		  else if($_POST['table'] == "sonnerie"){
			$e=13;

		  }

		  else if($_POST['table'] == "adminMv"){
			$e=14;

		  }
		  /*
		  else if($_POST['table'] == "point2"){
			$e=11;
			$tabl = 'point';
		  }
		  else if($_POST['table'] == "credit"){
			$e=12;
			$tabl = 'credit';
		  }
		  else if($_POST['table'] == "cotisem"){
			$e=13;
			$tabl = 'membre_typm';
		  }
		  */
		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl); // requete pour recuperer les colums de notre table
		$a=0;
		/* while($champ = $req->fetch(PDO::FETCH_ASSOC)){

	 $table[$i] = $champ["Field"]; // recuperer le nom des colums de la table
		var_dump($champ);

		} */
		$w = 0;
		while($champ = $req->fetch(PDO::FETCH_ASSOC)){

			 $table[$a] = $champ["Field"]; // recuperer le nom des colums de la table
			 if ($champ['Key'] == 'MUL'){
				 $ind[$w] = $a;
				 $w++;
			 }

			 $a++;
		 //}
		 }



		 if(isset($ind)){
			 $tail = sizeof($ind);
			 $i=0;
			while($i<$tail){

			if($table[$ind[$i]] == 'id_maison'){
				$etrang[$i] = 'maison';
			}
			if($table[$ind[$i]] == 'id_album'){
				$etrang[$i] = 'album';
			}
			if($table[$ind[$i]] == 'id_artiste'){
				$etrang[$i] = 'artiste';
			}
			if($table[$ind[$i]] == 'id_son'){
				$etrang[$i] = 'son';
			}
			if($table[$ind[$i]] == 'id_userplaylist'){
				$etrang[$i] = 'userplaylist';
			}
			if($table[$ind[$i]] == 'id_playlist'){
				$etrang[$i] = 'playlist';
			}
			if($table[$ind[$i]] == 'indx'){
				$etrang[$i] = $_POST['lindx'];
			}
			if($table[$ind[$i]] == 'id_user'){
				$etrang[$i] = 'user';
			}
			if($table[$ind[$i]] == 'id_genre'){
				$etrang[$i] = 'genre';
			}
			if($table[$ind[$i]] == 'id_pays'){
				$etrang[$i] = 'pays';
			}
			if($table[$ind[$i]] == 'id_transaction'){
				$etrang[$i] = 'transaction';
			}
			/*
			if($table[$ind[$i]] == 'id_activite'){
				$etrang[$i] = 'activite';
			}
			if($table[$ind[$i]] == 'id_activite'){
				$etrang[$i] = 'activite';
			}
			*/




		 $i++;
		 }
		 $in = $i;
		 $i=0;
		 $ttt = sizeof($etrang);
		 while($i < $ttt){
		 $req=$bdd->query("SHOW COLUMNS FROM ".$etrang[$i]); // requete pour recuperer les colums de notre table

		while($cham = $req->fetch(PDO::FETCH_ASSOC)){

			 $table[$a] = $cham["Field"]; // recuperer le nom des colums de la table
			 $ind[$w] = $a;
			 $etrang[$in] = $etrang[$i];
			 $in++;
			 $a++;
			 $w++;
		 }
		 $i++;
		 }
		 }
			 // var_dump($table);
		 /* creqtion de la requete d'affichage avec les clés etreangere*/
		 /* 'SELECT music.idMusic, music.titreMusic, music.lienMusic, music.idAlbum, artiste.idArtiste, artiste.nomArtiste, album.idAlbum, album.titreAlbum, album.idArtiste FROM music
										INNER JOIN album
											ON music.idAlbum = album.idAlbum
										INNER JOIN artiste
											ON album.idArtiste = artiste.idArtiste' */
		  $taille = sizeof($table);
		//	echo "taille : ".$taille;
			$req_aff = 'SELECT';

			$i = 1;
		$u = 0;

		while($u<$taille){
			$v = $taille - 1; // declare le dernier indice de notre table
			if (preg_match("#password#", $table[$u])){
				$i++;
			} else {
				if(isset($ind[$i])){

				if ($u == $ind[$i]){
					$tablee = $etrang[$i];
					$i++;
				} else {
					$tablee = $tabl;
				}
				} else {
					$tablee = $tabl;
				}
			$req_aff .= ' '.$tablee.'.'.$table[$u]; // ajoute a la requete  les dif champs de la table

				if($u !== $v){
					$req_aff .= ","; // apres un champs ajouter ajouter on mets une ","
				}
			}
		//	echo " u :".$u;
			 if($u == $v) {
				/*
				if($e==7) {
					$req_aff .= ", classe_matiere.id_classe_matiere, classe_matiere.id_classe, classe_matiere.id_matiere, classe_matiere.titre_classe_matiere";
				}
				*/
				if($req_aff[-1] == ','){
					$req_aff = substr($req_aff, 0, -1);
				}
				$req_aff .= " FROM ".$tabl; // lorsqu'on arrive au dernier elemt on ferme la parenthese
			}
			$u++;
		}


		if(isset($ind)){
		$i = 0;
			$u = 0;
		while($u<$tail){
			if($tabl == "artiste" OR $tabl == "son" OR $tabl == "transaction"){
				$req_aff .= ' LEFT JOIN '.$etrang[$u];

		  	$req_aff .= ' ON '.$tabl.'.'.$table[$ind[$u]].' = '.$etrang[$u].'.'.$table[$ind[$u]]; // ajoute a la requete  les dif champs de la table pour la jointure

			} else {


				$req_aff .= ' INNER JOIN '.$etrang[$u];

		  	$req_aff .= ' ON '.$tabl.'.'.$table[$ind[$u]].' = '.$etrang[$u].'.'.$table[$ind[$u]]; // ajoute a la requete  les dif champs de la table pour la jointure

			}



			$u++;
		}
		/*
		if ($e == 3) {
	      	$req_aff .= ' WHERE classe_matiere.id_classe_matiere = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 5) {
	      	$req_aff .= ' WHERE classe_matiere.id_classe = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 6) {
	      	$req_aff .= ' WHERE chapitre.id_chapitre = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 8) {
	      	$req_aff .= ' WHERE lecon.id_lecon = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 10) {
	      	$req_aff .= ' WHERE examen.id_examen = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 11) {
	      	$req_aff .= ' WHERE quizz.id_quizz = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 12) {
	      	$req_aff .= ' WHERE classe_matiere.id_classe = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		/*
		if ($e == 4) {
			$ddj = $_GET['m'];
						$d = explode('-',$ddj);
			$req_aff .= ' INNER JOIN cotisem
						ON membre_typm.id_membre = cotisem.id_membre
						WHERE cotisem.date_cotisem BETWEEN "'.$d[0].'-'.$d[1].'-01" AND "'.$d[0].'-'.$d[1].'-31"';
	      	$req_aff .= ' AND '.$tabl.'.id_section = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 8) {
			$req_aff.=' INNER JOIN membre_typm
						ON membre.id_membre = membre_typm.id_membre
						INNER JOIN section
						ON section.id_section = membre_typm.id_section
						WHERE '.$tabl.'.id_activite = '.$_GET['a']; // ajoute a la requete  les dif champs de la table pour la jointure
			if (isset($_GET['s'])) {
				$req_aff .= ' AND section.id_section = '.$_GET['s'];
			}
	    }
		if ($e == 9) {
	      	$req_aff .= ' WHERE section.id_section = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 10) {
	      	$req_aff .= " WHERE point.mois_point BETWEEN '".$_GET['a']."-01' AND '".$_GET['a']."-31'"; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 1 OR $e == 3) {
			//if($e == 1){
				$ddj = $_GET['a'];
				$d = explode('-',$ddj);
				$req_aff .= ' INNER JOIN cotisem
						ON membre_typm.id_membre = cotisem.id_membre
						WHERE cotisem.date_cotisem BETWEEN "'.$d[0].'-'.$d[1].'-01" AND "'.$d[0].'-'.$d[1].'-31"';
					if(isset($_GET['s'])){
						$req_aff .= ' AND '.$tabl.'.id_section = '.$_GET['s'];
					}
			/*} else if(isset($_GET['s'])){
				$req_aff .= ' WHERE section.id_section = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
			}* /
	    }
		if ($e == 13) {
	      	/*$req_aff.=' INNER JOIN membre_typm
						ON membre.id_membre = membre_typm.id_membre
						INNER JOIN section
						ON section.id_section = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
			* /
	    } */
		}
		/*
		if ($e == 7) {
	      	$req_aff .= ' INNER JOIN classe_matiere ON classe_matiere.id_matiere = matiere.id_matiere WHERE classe_matiere.id_classe = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }*/
			/*else if($_POST['table'] == "utilisateur"){
	    $e=5;
	  	}*/
			//echo $req_aff.'<br>';

		if ($e == 11) {
			$req_aff.=' INNER JOIN client_typeClient
						ON client.id_client = client_typeClient.id_client
						WHERE client_typeClient.id_typeClient = '.$_GET['tp']; // ajoute a la requete  les dif champs de la table pour la jointure

			}

		if ($e == 7) {
			if (isset($_GET['dte']) AND $_GET['dte'] !== "") {
				$_GET['dte'] = str_replace(" ", "", $_GET['dte']);
				$dterange = explode('-',$_GET['dte']);
				$dte1 = explode("/", $dterange[0]);
				$dte2 = explode("/",$dterange[1]);

				$req_aff.=' WHERE transaction.date_transaction >= "'.$dte1[2].'-'.$dte1[1].'-'.$dte1[0].'" AND transaction.date_transaction <= "'.$dte2[2].'-'.$dte2[1].'-'.$dte2[0].'"';

			} else {

	      $lastmonth = date('Y-m-d', strtotime('-2 month'));
				$req_aff.=' WHERE transaction.date_transaction >= "'.$lastmonth.'"';
			}
			if (isset($_GET['stat']) AND $_GET['stat'] !== "") {
				// echo $_GET['stat'];
				if ($_GET['stat'] == "success") {
					$stat = "SUCCESS";
				} else if ($_GET['stat'] == "attente"){
					$stat = "ATTENTE";
				} else if ($_GET['stat'] == "echec") {
					$stat = "FAIL";
				}


				$req_aff.=' AND transaction.statut_transaction = "'.$stat.'"';

			}
			// echo $req_aff;
			}
				$i = 1;
				$j = 0;
				$affich_el[] = '';
				$affich_el[0] = 1;
				$tabindic[]='';

				switch ($e){
				case '1' :
				$tabindic = array(1,2,4);
				break;

				case '2' :
				$tabindic = array(1);
				break;

				case '3' :
				$tabindic = array(1,4);
				break;

				case '4' :
				$tabindic = array(1,2,5,3);
				break;

				case '5' :
				$tabindic = array(1,2,5,6,45,33,19,3);
				break;

				case '6' :
				$tabindic = array(2,1,7,3);
				break;

				case '7' :
				$tabindic = array(5,4,3,1,2,6,23);
				break;

				case '8' :
				$tabindic = array(1);
				break;

				case '9' :
				$tabindic = array(1,2,3,5,6);
				break;

				case '10' :
				$tabindic = array(2,1);
				break;


				case '11' :
				$tabindic = array(1,2);
				break;

				case '12' :
				$tabindic = array(1,3);
				break;

				case '13' :
				$tabindic = array(1,2,3);
				break;


			}



		$ttab = sizeof($tabindic);

			 // echo $req_aff;
		$aff = $bdd -> query($req_aff);
		while($affich = $aff->fetch()){

			 // var_dump($affich);
			$j=0;
			while($j < $ttab){
				if (!isset($affich[$tabindic[$j]])) {
					$affich_el[$i][$j] = "";
				} else {

					$affich_el[$i][$j] = $affich[$tabindic[$j]];
				}

					$j++;

			}
			if($j == $ttab){
				if($e == 1){


					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=admin' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 2){


					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=maison' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}

				if($e == 3){


					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=artiste' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 4){

					// var_dump($affich);
					$re2ar = "SELECT * FROM artiste WHERE id_artiste = ".$affich[10];
					$reqart = $bdd -> query($re2ar);
					$art = $reqart->fetch();

					$affich_el[$i][$j] = $art['nom_artiste']."(".$art['nationalite_artiste'].")";
					$j++;
					// $affich_el[$i][$j] = $art['nationalite_artiste'];
					// $j++;
					$affich_el[$i][$j] = "https://www.afreekaplay.com/album/".$affich[9]."-".$affich[0]."/details";
					$j++;

					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=album' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 5){
					   var_dump($affich);
					// $re2ar = "SELECT * FROM artiste WHERE id_artiste = ".$affich[12];
					// $reqart = $bdd -> query($re2ar);
					// $art = $reqart->fetch();
					//
					// $affich_el[$i][5] = $art['nom_artiste']."(".$art['nationalite_artiste'].")";
					// $j++;
					// $affich_el[$i][$j] = $art['nationalite_artiste'];
					// $j++;
					$affich_el[$i][$j] = "https://www.afreekaplay.com/song/".$affich[10]."-".$affich[0]."/details";
					$j++;
					/*$affich_el[$i][$j] = "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Questions</button><div class='dropdown-menu'><a class='dropdown-item' href='ajoutqurep.php?t=examen&indx=".$affich[0]."'>Ajouter</a><a class='dropdown-item' href='liste.php?dest=question_ex&s=".$affich[0]."'>Liste</a></div>";
					$j++;*/
					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=son' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 6){
					/*
					$re2 = "SELECT * FROM quizz WHERE id_lecon=".$affich[0];
					$nbq = $bdd -> query($re2);
					$nbqu = $nbq->rowCount();
					$affich_el[$i][$j] = $nbqu;
					$j++;
					$affich_el[$i][$j] = "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Quizz</button><div class='dropdown-menu'><a class='dropdown-item' href='ajouteq.php?t=quizz&indx=".$affich[0]."&s2=".$_GET['s']."'>Ajouter</a><a class='dropdown-item' href='liste.php?dest=quizz&s=".$affich[0]."'>Liste</a></div>";
					$j++;*/
					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=telechargement' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 7){
				 // var_dump($affich);

					if((isset($_GET['s']) AND $_GET['s'] == 11) OR (isset($_GET['xs']))){


							$libl = explode('-',$affich[7]);
							$taill = sizeof($libl)-1;
							 // var_dump($libl);

							$re2 = "SELECT * FROM integrateur_transaction
																		INNER JOIN integrateur
																		ON integrateur.id_integrateur = integrateur_transaction.id_integrateur
																		WHERE integrateur_transaction.id_transaction = ".$affich[0];
							$int = $bdd -> query($re2);
							$intgr = $int->fetch();
							$integ = $intgr['nom_integrateur'];

							$libelTrsx = ($libl[$taill] == "don") ? "Soutien" : "Achat";
							if ($libl[$taill] == "don" OR $libl[0] == "art" OR $libl[0] == "artiste") {
								if ($libl[$taill] == "don") {
									$idx = $libl[$taill-1];
								} else {
									$idx = $libl[1];
									// echo $idx;
								}
									$titre = "-";
									$re1 = "SELECT * FROM artiste WHERE id_artiste = ".$idx;
									$art = $bdd -> query($re1);
									$artist = $art->fetch();
									$artisNm = $artist['nom_artiste'];
	 						} else {


									// $libl[1] = intval($libl[1]);
									if ($libl[$taill] == "offert") {
										$idx = $libl[$taill-1];
									} else {
										$idx = $libl[$taill];
										// echo $idx;
									}




									$re1 = "SELECT * FROM ".$libl[0]."
																				INNER JOIN artiste
																				ON artiste.id_artiste = ".$libl[0].".id_artiste WHERE ".$libl[0].".id_".$libl[0]." = ".$idx;
																				// echo $re1;
									$art = $bdd -> query($re1);
									$artist = $art->fetch();
									$artisNm = $artist['nom_artiste'];
									$titre = $artist['titre_'.$libl[0]];

							}


							if($affich[6] == "SUCCESS"){
								$hide1 = 'hide';
								$hide = '';
							} else {
								$hide1 = '';
								$hide = 'hide';
							}
							$affich_el[$i][$j] = $artisNm;
							$j++;
							$affich_el[$i][$j] = $titre;
							$j++;
							$affich_el[$i][$j] = $integ;
							$j++;
							$affich_el[$i][$j] = $libelTrsx;
							$j++;


						$affich_el[$i][$j] = "<div id='success-".$affich[0]."' class='".$hide1."' ><button type='button' data-id='".$affich[0]."' data-name='success' class='btn btn-block btn-success btn_stat' style=' '>SUCCES</button></div><div id='failed-".$affich[0]."' class='".$hide."'><button type='button' data-id='".$affich[0]."' data-name='failed' class='btn btn-block btn-danger btn_stat'>ECHEC</button></div>";
					} else {
						$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=transaction' class='btn btn-block btn-default' style=' '>Modifier</a>";

					}
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 8){

					$re2 = "SELECT * FROM playlist_son WHERE id_playlist=".$affich[0];
					$nbl = $bdd -> query($re2);
					$nbson = $nbl->rowCount();
					$affich_el[$i][$j] = $nblec;
					$j++;
					$affich_el[$i][$j] = "<a type='button' href='modifierpl.php?indx=".$affich[0]."&t=playlist' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

				}
				if($e == 9){

					/*
					$affich_el[$i][$j] = "<a type='button' href='statistique_user.php?indx=".$affich[0]."' class='btn btn-block btn-default' style=' '>Afficher</a>";
					$j++;*/
					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=user' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 10){

					/*
					$affich_el[$i][$j] = ($affich[3] == 0) ? "Texte" : "Image";
					$j++;
					$affich_el[$i][$j] = ($affich[4] == 0) ? "Choix unique" : "Choix multiple";
					$j++;
					$affich_el[$i][$j] = $affich[7];
					$j++;*/
					$affich_el[$i][$j] = "<a type='button' href='modifierpl.php?indx=".$affich[0]."&t=userplaylist' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 11){

					/*
					$affich_el[$i][$j] = ($affich[3] == 0) ? "Texte" : "Image";
					$j++;
					$affich_el[$i][$j] = ($affich[4] == 0) ? "Choix unique" : "Choix multiple";
					$j++;
					$affich_el[$i][$j] = $affich[7];
					$j++;*/
					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=client' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 12){

					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=playlist' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

				}
				if($e == 13){

					$re2 = "SELECT * FROM auteurS WHERE id_auteurS=".$affich[0];
					$aut = $bdd -> query($re2);
					$auteur = $aut->fetch();
					$affich_el[$i][$j] = $auteur['nom_auteurS'];
					$j++;
					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=playlist' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info btn_sup' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

				}
			}
			$i++;
		}
		//var_dump($affich_el);
		$affich_e = array_shift($affich_el);

		echo json_encode($affich_el);

	}

/* ------------- FONCTION SUPPRIMER -----------------*/
	function supprimer(){


		var_dump($_POST);
		require 'connexion.php';
 	 $dtime = date("Y-m-d H:i:s");
		$tabl = $_POST["table"]; // recuperer la valeur de notre input table
		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
		$i=0;
		while($champ = $req->fetch(PDO::FETCH_ASSOC))
		 {	//if(!preg_match("#id#", $champ["Field"])){
			 $table[$i] = $champ["Field"];
			 //echo $_POST[$champ["Field"]];

			 $i++;
		 //}
		 }

				/*$req = $bdd->query("DELETE FROM inscription_sf WHERE id_inscSF = '".$_POST['id']."'");
				echo "succes";*/

		$req_del = " DELETE FROM ".$tabl." WHERE ".$table[0]." = '".$_POST['id']."'";

		//echo $req_ajou;
	 $req_del_donne = $bdd -> prepare($req_del);

	            $req_del_donne -> execute();


		echo "succes";
	}


	/* ------------------ MISE A JOUR ------------- */
	function maj(){

		require 'connexion.php';
		$dte = date('d-m-y hms');
 	 $dtime = date("Y-m-d H:i:s");
		$tabl = $_POST["table"];
		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
		$i=0;
		var_dump ($_FILES);
		while($champ = $req->fetch(PDO::FETCH_ASSOC))
		 {	//if(!preg_match("#id#", $champ["Field"])){
			 $table[$i] = $champ["Field"];
			 //;

			 $i++;
		 //}
		 }
	 $i=0;
	 	while($i < sizeof($table))
	 {
		if(preg_match("#image#", $table[$i])){

		 $ch_image = $table[$i];
		 //echo $_FILES[$ch_image];
		 //$valeur[$i] = $_FILES[$ch_image]['name'];
		 $valeur[$i] = $_FILES[$ch_image]['name'].'-'.$dte.'.png';

	 } else if(preg_match("#fichier#", $table[$i])){

		 $ch_fich = $table[$i];
		 $valeur[$i] = $_FILES[$ch_fich]['name'];

	 } else if(preg_match("#cour#", $table[$i])){

		 $ch_fich = $table[$i];
		 $valeur[$i] = $_POST['titre_lecon'].".pdf";

	 } else{
		 $valeur[$i] = $_POST[$table[$i]];
		}
	 $i++;
	 }

 		$taille = sizeof($table);


		$req_ajou = ' UPDATE '.$tabl.' SET ';  //UPDATE client SET prenomClient = :prenomClient, dateNaisClient = :dateNaisClient, email = :email, 	telephone = :telephone, residence = :residence WHERE idClient = :idClient

		$u = 1;
		while($u<$taille){
			if((preg_match("#fichier#", $table[$u]) AND $_FILES[$ch_fich]['error'] !== 0) OR (preg_match("#image#", $table[$u]) AND $_FILES[$ch_image]['error'] !== 0) OR (preg_match("#cour#", $table[$u]) AND $_FILES[$ch_fich]['error'] !== 0)){

			} else {
				$v = $taille - 1;
			//if(!preg_match("#id#", $table[$u])) {
			$req_ajou .= $table[$u]."= :".$table[$u];
				if($u !== $v){
					$req_ajou .= ", ";
				} else {
					$req_ajou .= " WHERE ";
				$req_ajou .= $table[0]."= :".$table[0];
				$idCle = $_POST[$table[0]];
				//$req_ajou .= ") ";
				}
			/* } else {

			} */
			}

			$u++;
		}

		//echo $req_ajou;
	 $req_ajou_donne = $bdd -> prepare($req_ajou);

		$i=0;
		//echo $taille;
		while($i<$taille){
			if((preg_match("#fichier#", $table[$i]) AND $_FILES[$ch_fich]['error'] !== 0) OR (preg_match("#image#", $table[$i]) AND $_FILES[$ch_image]['error'] !== 0) OR (preg_match("#cour#", $table[$i]) AND $_FILES[$ch_fich]['error'] !== 0)){

		} else {
			$value = ":".$table[$i];
			//echo $valeur[$i];
			$req_ajou_donne ->bindParam($value, $valeur[$i]);
		}

	  $i++;
	 }
						$req_ajou_donne -> execute();

                    if (isset($ch_image))
                      {
                    if (isset($_FILES[$ch_image]) AND $_FILES[$ch_image]['error'] == 0)
                      {
                      // Testons si le fichier n'est pas trop gros
                        if ($_FILES[$ch_image]['size'] <= 1000000)
                          {
                      // Testons si l'extension est autorisée
                        $infosfichier = pathinfo($_FILES[$ch_image]['name']);
                          $extension_upload = $infosfichier['extension'];
                          $extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
                          if (in_array($extension_upload, $extensions_autorisees))
                            {
                      // On peut valider le fichier et le stockerdéfinitivement

                        move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'images/' .basename($_FILES[$ch_image]['name']));

                        echo "L'image a bien été envoyé ! <br />";
                            }
                          }
                      } else {echo "pas de fichier";} }

                    if (isset($ch_fich))
                      {
                    if (isset($_FILES[$ch_fich]) AND $_FILES[$ch_fich]['error'] == 0)
                      {
                      // Testons si le fichier n'est pas trop gros
                        if ($_FILES[$ch_fich]['size'] <= 50000000)
                          {
                      // Testons si l'extension est autorisée
                        $infosfichier = pathinfo($_FILES[$ch_fich]['name']);
                          $extension_upload = $infosfichier['extension'];
                          $extensions_autorisees = array('jpg', 'jpeg', 'gif','png', 'pdf');
                          if (in_array($extension_upload, $extensions_autorisees))
                            {
                      // On peut valider le fichier et le stockerdéfinitivement

                        if($tabl == "lecon"){
							move_uploaded_file($_FILES[$ch_fich]['tmp_name'], 'pdf/' .$_POST['titre_lecon'].'.pdf');
						} else if($tabl == "corrige"){
							move_uploaded_file($_FILES[$ch_fich]['tmp_name'], 'corriges/' .$_POST['titre_corrige'].'.pdf');
						}

                        echo "Le fichier a bien été envoyé ! <br />";
                            }
                          }
                      } else {echo "pas de fichier";} }

			if($tabl == 'matiere') {
				//var_dump($_POST);
				$idClass = explode(',',$_POST['id_classe']);
				$nbClass = sizeof($idClass);
				$in =0;
				$on =0;
				$an =0;
				$jn =0;
				$idPaj=[];
				$idPs=[];
				//var_dump ($idClass);

				$re2 = "SELECT * FROM classe_matiere WHERE id_matiere = ".$idCle;
							$clam1 = $bdd -> query($re2);
							while($clasm1 = $clam1->fetch()){
								$idClass1[$on] = $clasm1['id_classe'];
								$on++;
							}

				//var_dump ($idClass1);
				while($in < $nbClass-1){
					$an =0;
					while($an < $on){
						if($idClass1[$an] == $idClass[$in]){
							$conti = true;
							$an = $on;
						} else {
							$conti = false;
							$an++;
						}
					}
					if($conti == false){
						$idPaj[$jn]= $idClass[$in];
						$jn++;
					}
					$in++;
				}

				$in =0;
				$an =0;
				$js =0;
				while($an < $on){
					$in=0;
					while($in < $nbClass-1){
						$tn = $in;
						if($idClass1[$an] == $idClass[$in]){
							$conti = true;
							$in = $nbClass-1;
						} else {
							$conti = false;
							$in++;
						}
					}
					if($conti == false){
						$idPs[$js]= $idClass1[$an];
						$js++;
					}
					$an++;
				}

				$nbPaj = sizeof($idPaj);
				$nbPs = sizeof($idPs);
				//var_dump($idPaj);
				//var_dump($idPs);
				$indc=0;
				while($indc < $nbPaj){

					$re = "SELECT * FROM classe WHERE id_classe = ".$idPaj[$indc];
					$cla = $bdd -> query($re);
					$clas = $cla->fetch();

					$titrcm = $_POST['titre_matiere'].' - '.$clas['titre_classe'];
					$req_ajd = "INSERT INTO classe_matiere (id_classe, id_matiere, titre_classe_matiere) VALUES (:id_classe, :id_matiere, :titre_classe_matiere)";
					$req_aj_don = $bdd -> prepare($req_ajd);
					$req_aj_don ->bindParam(':id_classe', $idPaj[$indc]);
					$req_aj_don ->bindParam(':id_matiere', $idCle);
					$req_aj_don ->bindParam(':titre_classe_matiere', $titrcm);
					$req_aj_don ->execute();

					$indc++;
				}
				$indc=0;
				while($indc < $nbPs){

					$re = "SELECT * FROM classe_matiere WHERE id_classe = ".$idPs[$indc]." AND id_matiere = ".$idCle;
					$cla = $bdd -> query($re);
					$clas = $cla->fetch();

					$req_ajd = "DELETE FROM classe_matiere WHERE id_classe_matiere = '".$clas['id_classe_matiere']."'";
					$req_aj_don = $bdd -> prepare($req_ajd);
					$req_aj_don ->execute();

					$indc++;
				}

			}
						if($tabl == "quizz"){
							$re = "SELECT * FROM quizz WHERE id_quizz = ".$idCle;
							$qz = $bdd -> query($re);
							$qzz = $qz->fetch();
							header("location: liste.php?dest=quizz&s=".$qzz['id_lecon']);
						} else {
							header("location: modifierSimpl.php?t=".$tabl."&indx=".$idCle);
						}

	}


/* ------------------ MISE A JOUR UN ------------- */
	function maju(){

		require 'connexion.php';
		$dte = date('d-m-y hms');
 	 $dtime = date("Y-m-d H:i:s");
		$tabl = $_POST["table"];
		$id = $_POST['id'];
		$name = $_POST['name'];
		$val = (isset($_POST['val'])) ? $_POST['val'] : '';


		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
		$i=0;
		while($champ = $req->fetch(PDO::FETCH_ASSOC))
		 {	//if(!preg_match("#id#", $champ["Field"])){
			 $table[$i] = $champ["Field"];
			 //echo $_POST[$champ["Field"]];

			 $i++;
		 //}
		 }
	 $i=1;
	 $ch_mj = '';
	 	/*while($i < sizeof($table))
	 {
		if(preg_match("#image#", $table[$i])){

		 $ch_image = $table[$i];
		 //echo $_FILES[$ch_image];
		 //$valeur[$i] = $_FILES[$ch_image]['name'];
		 $valeur[$i] = $_FILES[$ch_image]['name'].'-'.$dte.'.png';

	 } else{
		 $valeur[$i] = $_POST[$table[$i]];
		}
	 $i++;
	 }*/



		if(preg_match("#image#", $name)){

		 $ch_image = $name;
		 $ch_mj = $name;
		 //echo $_FILES[$ch_image];
		 //$valeur[$i] = $_FILES[$ch_image]['name'];
		 $valeur[$i] = $_FILES[$ch_image]['name'].'-'.$dte.'.png';
		 $i++;
		} else {
		 $valeur[$i] = $val;
		 $ch_mj = $name;
		 $i++;
		}


 		//$taille = sizeof($table);


		$req_majun = ' UPDATE '.$tabl.' SET '.$ch_mj.'=:'.$ch_mj.' WHERE '.$table[0].' = '.$id;  //UPDATE client SET prenomClient = :prenomClient, dateNaisClient = :dateNaisClient, email = :email, 	telephone = :telephone, residence = :residence WHERE idClient = :idClient

		/*$u = 1;
		while($u<$taille){

			$v = $taille - 1;
			//if(!preg_match("#id#", $table[$u])) {
			$req_ajou .= $table[$u]."= :".$table[$u];
				if($u !== $v){
					$req_ajou .= ", ";
				} else {
					$req_ajou .= "WHERE ";
				$req_ajou .= $table[$u]."= :".$table[$u];
				$req_ajou .= ") ";
				}
			/* } else {

			} * /
			$u++;
		}*/

		//echo $req_majou;
	 $req_majun_donne = $bdd -> prepare($req_majun);

		$i=1;
		//echo $taille;

			$valu = ":".$ch_mj;
			//echo $value;
									$req_majun_donne ->bindParam($valu, $valeur[$i]);


						$req_majun_donne -> execute();

                    if (isset($ch_image))
                      {
                    if (isset($_FILES[$ch_image]) AND $_FILES[$ch_image]['error'] == 0)
                      {
                      // Testons si le fichier n'est pas trop gros
                        if ($_FILES[$ch_image]['size'] <= 1000000)
                          {
                      // Testons si l'extension est autorisée
                        $infosfichier = pathinfo($_FILES[$ch_image]['name']);
                          $extension_upload = $infosfichier['extension'];
                          $extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
                          if (in_array($extension_upload, $extensions_autorisees))
                            {
                      // On peut valider le fichier et le stockerdéfinitivement

                        move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'images/up/lta/' .$_FILES[$ch_image]['name'].'-'.$dte.'.png');

                        //echo "L'image a bien été envoyé ! <br />";
                            }
                          }
                      } else { //echo "pas de fichier";
						}
					}
						//header("location: acceuil.php");
						echo 'ok';
	}

/* ------------------ MISE A JOUR UN ------------- */
function majun(){

	require 'connexion.php';
	$dte = date('d-m-y hms');
	$dtime = date("Y-m-d H:i:s");
	$tabl = $_POST["table"];
	$indx = $_POST["id_".$tabl];
	// var_dump($_POST);


	$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
	$i=0;
	while($champ = $req->fetch(PDO::FETCH_ASSOC))
	 {	//if(!preg_match("#id#", $champ["Field"])){
		 if(isset($_POST[$champ["Field"]]) OR $i < 1){
		 $table[$i] = $champ["Field"];
		 //echo $_POST[$champ["Field"]];
		 $i++;
		 }

	 //}
	 }
	 if($tabl == 'admin'){
			$_POST["password_admin"] = md5($_POST["password_admin"]);
		}

	if($tabl == 'client'){

		$_POST["password_client"] = md5($_POST["password_client"]);

	}
	$re2 = "SELECT * FROM ".$tabl." WHERE ".$table[0]."=".$indx;
	echo $re2;
				$am = $bdd -> query($re2);
				$amod = $am->fetch();

	$i=1;
	$valeur[0] = $indx;
	while($i < sizeof($table))
	{
	if(preg_match("#image#", $table[$i])){

	 $ch_image = $table[$i];
	 //echo $_FILES[$ch_image];
	 $valeur[$i] = $amod[$ch_image];
	 //$valeur[$i] = $_POST[$ch_image];

	}  else {
	 $valeur[$i] = $_POST[$table[$i]];
	}
	$i++;
	}


	/*
	if(preg_match("#image#", $name)){

	 $ch_image = $name;
	 $ch_mj = $name;
	 //echo $_FILES[$ch_image];
	 //$valeur[$i] = $_FILES[$ch_image]['name'];
	 $valeur[$i] = $_FILES[$ch_image]['name'].'-'.$dte.'.png';
	 $i++;
	} else{
	 $valeur[$i] = $val;
	 $ch_mj = $name;
	 $i++;
	}
	*/

	$taille = sizeof($table);


		//$req_majun = ' UPDATE '.$tabl.' SET '.$ch_mj.'=:'.$ch_mj.' WHERE '.$table[0].' = '.$id;  //UPDATE client SET prenomClient = :prenomClient, dateNaisClient = :dateNaisClient, email = :email, 	telephone = :telephone, residence = :residence WHERE idClient = :idClient

		$req_majun = ' UPDATE '.$tabl.' SET ';

		$u = 1;
	while($u<$taille){

		$v = $taille - 1;

			if($valeur[$u] !==''){
				$req_majun .= $table[$u]."= :".$table[$u];
			}
			if($u !== $v){
				if($valeur[$u] !==''){
					$req_majun .= ", ";
				}
			} else {
				$req_majun .= " WHERE ";
			$req_majun .= $table[0]."= :".$table[0];
			//$req_majun .= ") ";
			}
		/* } else {
			 */

		$u++;
	}

	//  echo $req_majun;
	$req_majun_donne = $bdd -> prepare($req_majun);

		$i=0 	;
		//echo $taille;
			$u = 0;
	while($u<$taille){
		if($valeur[$u] !=='' ){
			$valu = ":".$table[$u];

		//	echo $valu;
									$req_majun_donne ->bindParam($valu, $valeur[$i]);
		}

		$u++;
		$i++;
	}
	//var_dump($req_majun_donne);
						$req_majun_donne -> execute();

		//echo $_FILES[$ch_image]['name']." et ".$amod[$ch_image];
					if(isset($ch_image) AND $_POST[$ch_image] !== ''){
										if (isset($ch_image) AND ($amod[$ch_image] !== $_FILES[$ch_image]['name']))
											{
										if (isset($_FILES[$ch_image]) AND $_FILES[$ch_image]['error'] == 0)
											{
											// Testons si le fichier n'est pas trop gros
												if ($_FILES[$ch_image]['size'] <= 1000000)
													{
											// Testons si l'extension est autorisée
												$infosfichier = pathinfo($_FILES[$ch_image]['name']);
													$extension_upload = $infosfichier['extension'];
													$extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
													if (in_array($extension_upload, $extensions_autorisees))
														{
											// On peut valider le fichier et le stockerdéfinitivement

												move_uploaded_file($_FILES[$ch_image]['tmp_name'], '../img/'.$amod[$ch_image]);

												//echo "L'image a bien été envoyé ! <br />";
														}
													}
											} else { //echo "pas de fichier";
						}
					}
					}

						header("location: modifierSimpl.php?t=".$tabl."&indx=".$indx."&m=1");

					echo 'ok';
}

/* ------------------ MISE A JOUR UN ------------- */
function majunu(){

	require 'connexion.php';
	$datee = date('Y-m-d H:i:s');
	$tabl = $_POST["table"];
	$indx = $_POST["id"];
	// var_dump($_POST);



	$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
	$i=0;
	while($champ = $req->fetch(PDO::FETCH_ASSOC))
	 {	//if(!preg_match("#id#", $champ["Field"])){
		 if(isset($_POST[$champ["Field"]]) OR $i < 1){
		 $table[$i] = $champ["Field"];
		 //echo $_POST[$champ["Field"]];
		 $i++;
		 }

	 //}
	 }

	 	$re2 = "SELECT * FROM ".$tabl." WHERE ".$table[0]."=".$indx;

	 				$am = $bdd -> query($re2);
	 				$amod = $am->fetch();

	 			if (isset($_POST["password_".$tabl])) {
					if ($_POST["nmdp"] == 1) {
						$_POST["password_".$tabl] =  md5($_POST["password_".$tabl]);
						$exec = 1;
						$_SESSION["nmdp"] = 0;
					} else {
						$exec = (md5($_POST["password_".$tabl]) == $amod['password_'.$tabl]) ? 1 : 0;
		 				$_POST["password_".$tabl] = md5($_POST["password_".$tabl."2"]);
					}

	 			} else {
	 				$exec = 1;
	 			}
	if ($exec == 1) {

		$i=1;
		$valeur[0] = $indx;
		while($i < sizeof($table))
		{
			if(preg_match("#image#", $table[$i])){

			 $ch_image = $table[$i];
			 //echo $_FILES[$ch_image];
			 $valeur[$i] = $amod[$ch_image];
			 //$valeur[$i] = $_POST[$ch_image];

			}  else {
			 $valeur[$i] = $_POST[$table[$i]];
			}
			$i++;
		}


		/*
		if(preg_match("#image#", $name)){

		 $ch_image = $name;
		 $ch_mj = $name;
		 //echo $_FILES[$ch_image];
		 //$valeur[$i] = $_FILES[$ch_image]['name'];
		 $valeur[$i] = $_FILES[$ch_image]['name'].'-'.$dte.'.png';
		 $i++;
		} else{
		 $valeur[$i] = $val;
		 $ch_mj = $name;
		 $i++;
		}
		*/

		$taille = sizeof($table);


			//$req_majun = ' UPDATE '.$tabl.' SET '.$ch_mj.'=:'.$ch_mj.' WHERE '.$table[0].' = '.$id;  //UPDATE client SET prenomClient = :prenomClient, dateNaisClient = :dateNaisClient, email = :email, 	telephone = :telephone, residence = :residence WHERE idClient = :idClient

			$req_majun = ' UPDATE '.$tabl.' SET ';

			$u = 1;
		while($u<$taille){

			$v = $taille - 1;

				if($valeur[$u] !==''){
					$req_majun .= $table[$u]."= :".$table[$u];
				}
				if($u !== $v){
					if($valeur[$u] !==''){
						$req_majun .= ", ";
					}
				} else {
					$req_majun .= " WHERE ";
				$req_majun .= $table[0]."= :".$table[0];
				//$req_majun .= ") ";
				}
			/* } else {
				 */

			$u++;
		}

		//  echo $req_majun;
		$req_majun_donne = $bdd -> prepare($req_majun);

			$i=0 	;
			//echo $taille;
				$u = 0;
		while($u<$taille){
			if($valeur[$u] !=='' ){
				$valu = ":".$table[$u];

			//	echo $valu;
										$req_majun_donne ->bindParam($valu, $valeur[$i]);
			}

			$u++;
			$i++;
		}
		//var_dump($req_majun_donne);
							$req_majun_donne -> execute();

			//echo $_FILES[$ch_image]['name']." et ".$amod[$ch_image];
						if(isset($ch_image) AND $_POST[$ch_image] !== ''){
											if (isset($ch_image) AND ($amod[$ch_image] !== $_FILES[$ch_image]['name']))
												{
											if (isset($_FILES[$ch_image]) AND $_FILES[$ch_image]['error'] == 0)
												{
												// Testons si le fichier n'est pas trop gros
													if ($_FILES[$ch_image]['size'] <= 1000000)
														{
												// Testons si l'extension est autorisée
													$infosfichier = pathinfo($_FILES[$ch_image]['name']);
														$extension_upload = $infosfichier['extension'];
														$extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
														if (in_array($extension_upload, $extensions_autorisees))
															{
												// On peut valider le fichier et le stockerdéfinitivement

													move_uploaded_file($_FILES[$ch_image]['tmp_name'], '../img/'.$amod[$ch_image]);

													//echo "L'image a bien été envoyé ! <br />";
															}
														}
												} else { //echo "pas de fichier";
							}
						}
						}
						echo 'ok';
	} else {
		echo 'err1';
	}

						// header("location: modifier.php?t=".$tabl."&indx=".$indx."&m=1");


}

/* ------------------ COTISER------------- */
	function cotiser(){
		require 'connexion.php';
		//var_dump ($_POST);
		if(isset($_POST['donnee'])){
			$ddj = date ('Y-m-d');
	 	 $dtime = date("Y-m-d H:i:s");
			$donnee = explode(",", $_POST['donnee']);
			$nb = sizeof($donnee);
			$i=0;
			while ($i < $nb){
				$donneeU = explode("_", $donnee[$i]);
				$d[$i][0] = $donneeU[0];
				$d[$i][1] = $donneeU[1];
				$re = "SELECT * FROM jour WHERE date_jour = '".$d[$i][1]."'";
				$mois = $bdd -> query($re);
				$lmois = $mois->fetch();
				$re2 = "SELECT * FROM membre WHERE id_membre = '".$d[$i][0]."'";

				$mB = $bdd -> query($re2);
				$lmb = $mB->fetch();

				$da = explode("-",$d[$i][1]);
				$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$d[$i][0]." AND date_membre_cot = '".$da[0]."-".$da[1]."-01'";
						$mco = $bdd -> query($re2);
						$mcot = $mco->fetch();


				$req_ajd = "INSERT INTO cotisation (som_cotisation, id_jour, id_membre, date_cotisation) VALUES (:som_cotisation, :id_jour, :id_membre, :date_cotisation)";
				$req_aj_don = $bdd -> prepare($req_ajd);
				$req_aj_don ->bindParam(':som_cotisation', $mcot['som_membre_cot']);
				$req_aj_don ->bindParam(':id_jour', $lmois['id_jour']);
				$req_aj_don ->bindParam(':id_membre', $d[$i][0]);
				$req_aj_don ->bindParam(':date_cotisation', $ddj);
				$req_aj_don ->execute();
				$i++;
			}


		} else {
			echo 'failed';
		}
	}

	function pointsom(){
		require 'connexion.php';
		//var_dump ($_POST);
		$re = "SELECT *  FROM point
					INNER JOIN membre
					ON point.id_membre = membre.id_membre
					INNER JOIN membre_typm
					ON membre_typm.id_membre = membre.id_membre
					INNER JOIN membre_cot
					ON membre_cot.id_membre = membre.id_membre
					WHERE membre_typm.id_section = ".$_POST['s'];
		if($_POST['table'] == 'point'){
			$re .=" AND point.mois_point BETWEEN '".$_POST['ann']."-01' AND '".$_POST['ann']."-31'";
			$re .=" AND membre_cot.date_membre_cot BETWEEN '".$_POST['ann']."-01' AND '".$_POST['ann']."-31'";
		}
		$som = $bdd -> query($re);
		$scot =0;
		$ssort =0;
		$savanc =0;
		$ssig =0;
		while ($affich = $som->fetch()){
			$scot +=$affich['som_cot_point'];
			$ssort +=$affich['som_sorti_point'];
			$savanc +=$affich['som_du_point'];
			$ssig +=$affich['som_membre_cot'];
		//var_dump ($affich);
		}
		$affich_el[0]=$scot;
		$affich_el[1]=$ssort;
		$affich_el[2]=$savanc;
		$affich_el[3]=$ssig;

		echo json_encode($affich_el);




	}

	function pointpret(){
		require 'connexion.php';
		//var_dump ($_POST);
		$re = "SELECT * FROM credit";

		$som = $bdd -> query($re);
		$spret =0;
		while ($affich = $som->fetch()){
			$spret +=$affich['som_credit'];

		}
		$affich_el[0]=$spret;

		echo json_encode($affich_el);




	}

	function actif(){
		require 'connexion.php';
		//var_dump ($_POST);


		$donnee = explode(",", $_POST['donnee']);
		$nb = sizeof($donnee);
		$i=0;
		while ($i < $nb){
			$donneeU = explode("_", $donnee[$i]);
			$d[$i][0] = $donneeU[0];
			$d[$i][1] = $donneeU[1];
			$req_ajd = "INSERT INTO cotisem (date_cotisem, id_membre) VALUES (:date_cotisem, :id_membre)";
			$req_aj_don = $bdd -> prepare($req_ajd);
			$req_aj_don ->bindParam(':date_cotisem', $d[$i][1]);
			$req_aj_don ->bindParam(':id_membre', $d[$i][0]);
			$req_aj_don ->execute();

			$re2 = "SELECT * FROM membre WHERE id_membre = ".$d[$i][0];
				$mB = $bdd -> query($re2);
				$lmb = $mB->fetch();

			$re2 = "SELECT * FROM membre_cot WHERE id_membre=".d[$i][0]." AND date_membre_cot = '".$d[$i][1]."'";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
			if(isset($mcot['id_membre'])){
				$req_mcot = ' UPDATE membre_cot SET som_membre_cot=:som_membre_cot WHERE id_membre_cot = :id';
				$req_mcoti = $bdd -> prepare($req_mcot);
				$dda = $_POST['date_membre_cot'];
				$req_mcoti ->bindParam(':som_membre_cot', $_POST['commi_membre']);
				$req_mcoti ->bindParam(':id', $mcot['id_membre_cot']);
				$req_mcoti -> execute();
			} else {
			$req_ajd = "INSERT INTO membre_cot (som_membre_cot, date_membre_cot, id_membre) VALUES (:som_membre_cot, :date_membre_cot, :id_membre)";
				$req_aj_don = $bdd -> prepare($req_ajd);
				//$dda = $dd[0]."-".$dd[1]."-01";
				$req_aj_don ->bindParam(':som_membre_cot', $lmb['commi_membre']);
				$req_aj_don ->bindParam(':date_membre_cot', $d[$i][1]);
				$req_aj_don ->bindParam(':id_membre', $d[$i][0]);
				$req_aj_don ->execute();
			}
			$i++;
		}


	}


	function participer(){
		require 'connexion.php';
		//var_dump ($_POST);
		$ddj = date ('Y-m-d');
		$donnee = explode(",", $_POST['donnee']);
		$nb = sizeof($donnee);
		$i=0;
		while ($i < $nb){
			$donneeU = explode("_", $donnee[$i]);
			$d[$i][0] = $donneeU[0];
			$d[$i][1] = $donneeU[1];
			$re = "SELECT * FROM activite WHERE id_activite = '".$d[$i][1]."'";
			$act = $bdd -> query($re);
			$actm = $act->fetch();
			$req_ajd = "INSERT INTO acti_membre (som_acti_membre, date_acti_membre, id_activite, id_membre) VALUES (:som_acti_membre, :date_acti_membre, :id_activite, :id_membre)";
			$req_aj_don = $bdd -> prepare($req_ajd);
			$req_aj_don ->bindParam(':som_acti_membre', $actm['prix_activite']);
			$req_aj_don ->bindParam(':date_acti_membre', $ddj);
			$req_aj_don ->bindParam(':id_activite', $d[$i][1]);
			$req_aj_don ->bindParam(':id_membre', $d[$i][0]);
			$req_aj_don ->execute();
			$i++;
		}


	}

	function point(){
		require 'connexion.php';
		//var_dump ($_POST);
		$ddj = date ('Y-m-d');
		$ddjj = explode("-", $ddj);

		if($_POST['r']=='cot'){
			$donnee = explode(",", $_POST['donnee']);
			$nb = sizeof($donnee);
			$i=0;
			while ($i < $nb){
				$donneeU = explode("_", $donnee[$i]);
				$d[$i][0] = $donneeU[0];
				$d[$i][1] = $donneeU[1];
				$ddjj = explode("-", $d[$i][1]);
				//echo $d[$i][0];
				$re = "SELECT * FROM jour WHERE date_jour = '".$d[$i][1]."'";
				$mois = $bdd -> query($re);
				$lmois = $mois->fetch();

				$re2 = "SELECT * FROM membre WHERE id_membre = ".$d[$i][0];
				$mB = $bdd -> query($re2);
				$lmb = $mB->fetch();

				$da = explode("-",$d[$i][1]);
				$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$d[$i][0]." AND date_membre_cot = '".$da[0]."-".$da[1]."-01'";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
				$lmb['commi_membre'] = $mcot['som_membre_cot'];

				$re3 = "SELECT * FROM point WHERE id_membre = ".$d[$i][0]." AND mois_point = '".$ddjj[0]."-".$ddjj[1]."-01'";
				$PmB = $bdd -> query($re3);
				$Pdmb = $PmB->fetch();

				if(isset($Pdmb['id_point'])){
					$cotis = intval($Pdmb['som_cot_point']) + intval($lmb['commi_membre']);
					$du = intval($Pdmb['som_du_point']) + intval($lmb['commi_membre']);
					$req_majd = "UPDATE point SET som_cot_point=:som_cot_point, som_sorti_point=:som_sorti_point, som_du_point=:som_du_point, date_point=:date_point WHERE id_membre=:id_membre AND id_point=:id_point";
					$req_maj_don = $bdd -> prepare($req_majd);
					$req_maj_don ->bindParam(':som_cot_point', $cotis);
					$req_maj_don ->bindParam(':som_du_point', $du);
					$req_maj_don ->bindParam(':som_sorti_point', $Pdmb['som_sorti_point']);
					$req_maj_don ->bindParam(':date_point', $ddj);
					$req_maj_don ->bindParam(':id_membre', $d[$i][0]);
					$req_maj_don ->bindParam(':id_point', $Pdmb['id_point']);
					$req_maj_don ->execute();
						echo 'ok aj';
					$i++;
				} else {
					$sorti = 0;
					$cotis = 0;
					$moisp = $ddjj[0]."-".$ddjj[1]."-01";
					$req_ajd = "INSERT INTO point (som_cot_point, som_sorti_point, som_du_point, mois_point, date_point, id_membre) VALUES (:som_cot_point, :som_sorti_point, :som_du_point, :mois_point, :date_point, :id_membre)";

					$req_aj_don = $bdd -> prepare($req_ajd);
					$req_aj_don ->bindParam(':som_cot_point', $cotis);
					$req_aj_don ->bindParam(':som_du_point', $cotis);
					$req_aj_don ->bindParam(':som_sorti_point', $sorti);
					$req_aj_don ->bindParam(':mois_point', $moisp);
					$req_aj_don ->bindParam(':date_point', $ddj);
					$req_aj_don ->bindParam(':id_membre', $d[$i][0]);
					$req_aj_don ->execute();
					$i++;
				}
			}
		} else if($_POST['r']=='avanc'){
			$ddjj = explode("-", $_POST['mois_sorti']);
			$re2 = "SELECT * FROM membre WHERE id_membre = ".$_POST['id_membre'];
				$mB = $bdd -> query($re2);
				$lmb = $mB->fetch();

				$re3 = "SELECT * FROM point WHERE id_membre = ".$_POST['id_membre']." AND mois_point BETWEEN '".$ddjj[0]."-".$ddjj[1]."-01' AND '".$ddjj[0]."-".$ddjj[1]."-31'";
				$PmB = $bdd -> query($re3);
				$Pdmb = $PmB->fetch();

				if(isset($Pdmb['id_point']) AND (intval($Pdmb['som_du_point']) >= intval($_POST['som_sorti']))){
					$cotis = intval($Pdmb['som_cot_point']);
					$du = intval($Pdmb['som_du_point']) - intval($_POST['som_sorti']);
					$sorti = intval($Pdmb['som_sorti_point']) + intval($_POST['som_sorti']);
					$req_majd = "UPDATE point SET som_cot_point=:som_cot_point, som_du_point=:som_du_point, som_sorti_point=:som_sorti_point, date_point=:date_point WHERE id_membre=:id_membre AND id_point=:id_point";
					$req_maj_don = $bdd -> prepare($req_majd);
					$req_maj_don ->bindParam(':som_cot_point', $cotis);
					$req_maj_don ->bindParam(':som_du_point', $du);
					$req_maj_don ->bindParam(':som_sorti_point', $sorti);
					$req_maj_don ->bindParam(':date_point', $ddj);
					$req_maj_don ->bindParam(':id_membre', $_POST['id_membre']);
					$req_maj_don ->bindParam(':id_point', $Pdmb['id_point']);
					$req_maj_don ->execute();
					//$i++;
					echo 'OK';
				} else {
					echo 'failed';
				}
		}


	}

	function supcot(){
		require 'connexion.php';
		//var_dump ($_POST);
		$ddj = date ('Y-m-d');
		$ddjj = explode("-", $ddj);


		$donnee = explode(",", $_POST['donnee']);
		$nb = sizeof($donnee);
		$i=0;
		while ($i < $nb){
			$donneeU = explode("_", $donnee[$i]);
			$d[$i][0] = $donneeU[0];
			$d[$i][1] = $donneeU[1];
			$dco = explode("-", $d[$i][1]);
			$re = "SELECT * FROM jour WHERE date_jour = '".$d[$i][1]."'";
			$mois = $bdd -> query($re);
			$lmois = $mois->fetch();
			$re2 = "SELECT * FROM membre WHERE id_membre = '".$d[$i][0]."'";

			$mB = $bdd -> query($re2);
			$lmb = $mB->fetch();
			$req_sjd = "DELETE FROM cotisation WHERE id_jour = :id_jour AND id_membre = :id_membre";
			$req_sj_don = $bdd -> prepare($req_sjd);
			$req_sj_don ->bindParam(':id_jour', $lmois['id_jour']);
			$req_sj_don ->bindParam(':id_membre', $d[$i][0]);
			$req_sj_don ->execute();


			$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$d[$i][0]." AND date_membre_cot = '".$dco[0]."-".$dco[1]."-01'";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
			$re3 = "SELECT * FROM point WHERE id_membre = ".$d[$i][0]." AND mois_point = '".$dco[0]."-".$dco[1]."-01'";
			//echo $re3;
				$PmB = $bdd -> query($re3);
				$Pdmb = $PmB->fetch();

				if(isset($Pdmb['id_point'])){
					$cotis = intval($Pdmb['som_cot_point']) - intval($mcot['som_membre_cot']);
					$du = intval($Pdmb['som_du_point']) - intval($mcot['som_membre_cot']);
					$sorti = intval($Pdmb['som_sorti_point']);
					if($du >= 0) {
						$req_majd = "UPDATE point SET som_cot_point=:som_cot_point, som_sorti_point=:som_sorti_point, som_du_point=:som_du_point, date_point=:date_point WHERE id_membre=:id_membre AND id_point=:id_point";
						$req_maj_don = $bdd -> prepare($req_majd);
						$req_maj_don ->bindParam(':som_cot_point', $cotis);
						$req_maj_don ->bindParam(':som_du_point', $du);
						$req_maj_don ->bindParam(':som_sorti_point', $sorti);
						$req_maj_don ->bindParam(':date_point', $ddj);
						$req_maj_don ->bindParam(':id_membre', $d[$i][0]);
						$req_maj_don ->bindParam(':id_point', $Pdmb['id_point']);
						$req_maj_don ->execute();
						echo 'ok supp';
					} else {
						echo 'error1';
					}
				} else {
					echo 'failed';
				}
			$i++;
		}



	}

	function supact(){
		require 'connexion.php';
		//var_dump ($_POST);
		$ddj = date ('Y-m-d');
		$ddjj = explode("-", $ddj);

		$donnee = explode(",", $_POST['donnee']);
		$nb = sizeof($donnee);

		$donneeU = explode("_", $donnee[0]);
		$d[0][0] = $donneeU[0];
		$d[0][1] = $donneeU[1];
		$ddiv = explode("-", $d[0][1]);;
		$re = "SELECT * FROM jour WHERE date_jour BETWEEN '".$ddiv[0]."-".$ddiv[1]."-01' AND '".$ddiv[0]."-".$ddiv[1]."-31'";
		$mois = $bdd -> query($re);


		$re2 = "SELECT * FROM membre WHERE id_membre = '".$d[0][0]."'";
		$mB = $bdd -> query($re2);
		$lmb = $mB->fetch();

		$req_sjd = "DELETE FROM cotisem WHERE date_cotisem = :date_cotisem AND id_membre = :id_membre";
		$req_sj_don = $bdd -> prepare($req_sjd);
		$req_sj_don ->bindParam(':date_cotisem', $d[0][1]);
		$req_sj_don ->bindParam(':id_membre', $d[0][0]);
		$req_sj_don ->execute();

		$req_sjd = "DELETE FROM sorti WHERE date_sorti BETWEEN '".$ddiv[0]."-".$ddiv[1]."-01' AND '".$ddiv[0]."-".$ddiv[1]."-31' AND id_membre = :id_membre";
		$req_sj_don = $bdd -> prepare($req_sjd);
		$req_sj_don ->bindParam(':id_membre', $d[0][0]);
		$req_sj_don ->execute();

		$req_sjp = "DELETE FROM point WHERE mois_point = :mois_point AND id_membre = :id_membre";
		$req_sp_don = $bdd -> prepare($req_sjp);
		$req_sp_don ->bindParam(':mois_point', $d[0][1]);
		$req_sp_don ->bindParam(':id_membre', $d[0][0]);
		$req_sp_don ->execute();

		$i=0;
		while ($lmois = $mois->fetch()){

			$re3 = "SELECT * FROM cotisation WHERE id_jour = ".$lmois['id_jour']." AND id_membre = '".$d[0][0]."'";
			$coT = $bdd -> query($re3);
			$coti = $coT->fetch();
			if(isset($coti['id_cotisation'])){
				$req_sjd = "DELETE FROM cotisation WHERE id_cotisation = :id_cotisation";
				$req_sj_don = $bdd -> prepare($req_sjd);
				$req_sj_don ->bindParam(':id_cotisation', $coti['id_cotisation']);
				$req_sj_don ->execute();
				$i++;
			}
		}

		//$som_cot = $i*$lmb["commi_membre"];

	}

	function majQuestionRep(){
		require 'connexion.php';
		//var_dump ($_POST);
		$tabl = $_POST["table"];
		$idCle=0;

		if($tabl == "question_ex"){

			$idCle=$_POST["id_question_ex"];
			$req_ajqex = "UPDATE question_ex SET questionu_question_ex = :questionu_question_ex, numero_question_ex = :numero_question_ex, type_question_ex = :type_question_ex, multiple_question_ex = :multiple_question_ex, id_examen = :id_examen WHERE id_question_ex = :id_question_ex";

				$req_aj_qex = $bdd -> prepare($req_ajqex);
				$req_aj_qex ->bindParam(':questionu_question_ex', $_POST["questionu_question_ex"]);
				$req_aj_qex ->bindParam(':numero_question_ex', $_POST["numero_question_ex"]);
				$req_aj_qex ->bindParam(':type_question_ex', $_POST["type_question_ex"]);
				$req_aj_qex ->bindParam(':multiple_question_ex', $_POST["multiple_question_ex"]);
				$req_aj_qex ->bindParam(':id_examen', $_POST["id_examen"]);
				$req_aj_qex ->bindParam(':id_question_ex', $_POST["id_question_ex"]);
				$req_aj_qex ->execute();


			$reqrep = "SELECT * FROM reponse_ex where id_question_ex = ".$_POST["id_question_ex"]." ORDER BY numero_reponse_ex ASC";
						$rep = $bdd -> query($reqrep);

			$i=1;
			while($i<=4){
				$srep = $rep -> fetch();
				$req_ajrex = "UPDATE reponse_ex SET reponseu_reponse_ex = :reponseu_reponse_ex, numero_reponse_ex =:numero_reponse_ex, type_reponse_ex =:type_reponse_ex, id_question_ex =:id_question_ex WHERE id_reponse_ex = :id_reponse_ex";
				$req_aj_rex = $bdd -> prepare($req_ajrex);
				$nom1 = "reponseu_reponse_ex-".$i;
				$nom2 = "numero_reponse_ex-".$i;
				$nom3 = "type_reponse_ex-".$i;
				$nom4 = "id_reponse_ex-".$i;
				//echo $_POST[$nom2];
				$req_aj_rex ->bindParam(':reponseu_reponse_ex', $_POST[$nom1]);
				$req_aj_rex ->bindParam(':numero_reponse_ex', $_POST[$nom2]);
				$req_aj_rex ->bindParam(':type_reponse_ex', $_POST[$nom3]);
				$req_aj_rex ->bindParam(':id_question_ex', $_POST["id_question_ex"]);
				$req_aj_rex ->bindParam(':id_reponse_ex', $srep['id_reponse_ex']);
				$req_aj_rex ->execute();
				$i++;
			}

		} else if ($tabl == "question_q"){

		$idCle=$_POST["id_question_q"];
			$req_ajqex = "UPDATE question_q SET numero_question_q =:numero_question_q, questionu_question_q =:questionu_question_q, type_question_q =:type_question_q, multiple_question_q =:multiple_question_q, id_quizz =:id_quizz WHERE id_question_q = :id_question_q";
				$req_aj_qex = $bdd -> prepare($req_ajqex);
				$req_aj_qex ->bindParam(':numero_question_q', $_POST["numero_question_q"]);
				$req_aj_qex ->bindParam(':questionu_question_q', $_POST["questionu_question_q"]);
				$req_aj_qex ->bindParam(':type_question_q', $_POST["type_question_q"]);
				$req_aj_qex ->bindParam(':multiple_question_q', $_POST["multiple_question_q"]);
				$req_aj_qex ->bindParam(':id_quizz', $_POST["id_quizz"]);
				$req_aj_qex ->bindParam(':id_question_q', $_POST["id_question_q"]);
				$req_aj_qex ->execute();

			$reqrep = "SELECT * FROM reponse_q where id_question_q = ".$_POST["id_question_q"]." ORDER BY numero_reponse_q ASC";
						$rep = $bdd -> query($reqrep);

			$i=1;
			while($i<=4){
				$srep = $rep -> fetch();
				$req_ajrq = "UPDATE reponse_q SET reponseu_reponse_q =:reponseu_reponse_q, numero_reponse_q =:numero_reponse_q, explication_reponse_q =:explication_reponse_q, type_reponse_q =:type_reponse_q, id_question_q =:id_question_q WHERE id_reponse_q = :id_reponse_q";
				$req_aj_rq = $bdd -> prepare($req_ajrq);
				$nom1 = "reponseu_reponse_q-".$i;
				$nom2 = "numero_reponse_q-".$i;
				$nom3 = "explication_reponse_q-".$i;
				$nom4 = "type_reponse_q-".$i;
				$nom5 = "id_reponse_q-".$i;
				//echo $_POST[$nom2];
				$req_aj_rq ->bindParam(':reponseu_reponse_q', $_POST[$nom1]);
				$req_aj_rq ->bindParam(':numero_reponse_q', $_POST[$nom2]);
				$req_aj_rq ->bindParam(':explication_reponse_q', $_POST[$nom3]);
				$req_aj_rq ->bindParam(':type_reponse_q', $_POST[$nom4]);
				$req_aj_rq ->bindParam(':id_question_q', $_POST["id_question_q"]);
				$req_aj_rq ->bindParam(':id_reponse_q', $srep['id_reponse_q']);
				$req_aj_rq ->execute();
				$i++;
			}
		}

		 header("location: modifierqurep.php?t=".$tabl."&indx=".$idCle);

	}

	function questionRep(){
		require 'connexion.php';
		var_dump ($_POST);
		$tabl = $_POST["table"];
		$idCle=0;

		if($tabl == "examen"){
			$req = $bdd->query("SHOW TABLE STATUS FROM evallesse LIKE 'question_ex' ");
			$doneq = $req->fetch();
			$idCle=$_POST["id_examen"];
			$req_ajqex = "INSERT INTO question_ex (questionu_question_ex, numero_question_ex, type_question_ex, multiple_question_ex, id_examen) VALUES (:questionu_question_ex, :numero_question_ex, :type_question_ex, :multiple_question_ex, :id_examen)";
				$req_aj_qex = $bdd -> prepare($req_ajqex);
				$req_aj_qex ->bindParam(':questionu_question_ex', $_POST["questionu_question_ex"]);
				$req_aj_qex ->bindParam(':numero_question_ex', $_POST["numero_question_ex"]);
				$req_aj_qex ->bindParam(':type_question_ex', $_POST["type_question_ex"]);
				$req_aj_qex ->bindParam(':multiple_question_ex', $_POST["multiple_question_ex"]);
				$req_aj_qex ->bindParam(':id_examen', $_POST["id_examen"]);
				$req_aj_qex ->execute();

			$i=1;
			while($i<=4){
				$req_ajrex = "INSERT INTO reponse_ex (reponseu_reponse_ex, numero_reponse_ex, type_reponse_ex, id_question_ex) VALUES (:reponseu_reponse_ex, :numero_reponse_ex, :type_reponse_ex, :id_question_ex)";
				$req_aj_rex = $bdd -> prepare($req_ajrex);
				$nom1 = "reponseu_reponse_ex-".$i;
				$nom2 = "numero_reponse_ex-".$i;
				$nom3 = "type_reponse_ex-".$i;
				echo $_POST[$nom3];
				$req_aj_rex ->bindParam(':reponseu_reponse_ex', $_POST[$nom1]);
				$req_aj_rex ->bindParam(':numero_reponse_ex', $_POST[$nom2]);
				$req_aj_rex ->bindParam(':type_reponse_ex', $_POST[$nom3]);
				$req_aj_rex ->bindParam(':id_question_ex', $doneq['Auto_increment']);
				$req_aj_rex ->execute();
				$i++;
			}

		} else if ($tabl == "quizz"){
			$req = $bdd->query("SHOW TABLE STATUS FROM evallesse LIKE 'question_q' ");
			$doneq = $req->fetch();
		$idCle=$_POST["id_quizz"];
			$req_ajqex = "INSERT INTO question_q (numero_question_q, questionu_question_q, type_question_q, multiple_question_q, id_quizz) VALUES (:numero_question_q, :questionu_question_q, :type_question_q, :multiple_question_q, :id_quizz)";
				$req_aj_qex = $bdd -> prepare($req_ajqex);
				$req_aj_qex ->bindParam(':numero_question_q', $_POST["numero_question_q"]);
				$req_aj_qex ->bindParam(':questionu_question_q', $_POST["questionu_question_q"]);
				$req_aj_qex ->bindParam(':type_question_q', $_POST["type_question_q"]);
				$req_aj_qex ->bindParam(':multiple_question_q', $_POST["multiple_question_q"]);
				$req_aj_qex ->bindParam(':id_quizz', $_POST["id_quizz"]);
				$req_aj_qex ->execute();

			$i=1;
			while($i<=4){
				$req_ajrq = "INSERT INTO reponse_q (reponseu_reponse_q, numero_reponse_q, explication_reponse_q, type_reponse_q, id_question_q) VALUES (:reponseu_reponse_q, :numero_reponse_q, :explication_reponse_q, :type_reponse_q, :id_question_q)";
				$req_aj_rq = $bdd -> prepare($req_ajrq);
				$nom1 = "reponseu_reponse_q-".$i;
				$nom2 = "numero_reponse_q-".$i;
				$nom3 = "explication_reponse_q-".$i;
				$nom4 = "type_reponse_q-".$i;
				//echo $_POST[$nom2];
				$req_aj_rq ->bindParam(':reponseu_reponse_q', $_POST[$nom1]);
				$req_aj_rq ->bindParam(':numero_reponse_q', $_POST[$nom2]);
				$req_aj_rq ->bindParam(':explication_reponse_q', $_POST[$nom3]);
				$req_aj_rq ->bindParam(':type_reponse_q', $_POST[$nom4]);
				$req_aj_rq ->bindParam(':id_question_q', $doneq['Auto_increment']);
				$req_aj_rq ->execute();
				$i++;
			}
	}
}


	function majStatTrsx(){
		require 'connexion.php';
		$datee = date("Y-m-d H:i:s");
		$tabl = $_POST["table"];
		$indx = $_POST["id"];
		$name = $_POST["name"];
		$initt = ($_POST["mvafp"] == 1) ? "Moov Africa Play" : "afreekaplay";


		$statut = ($name == 'success') ? 'SUCCESS' : 'FAILED';
		$libl = "Mise a jour vers ".$statut;

		$reqmaj = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
	  $req_maj_stat = $bdd -> prepare($reqmaj);
	  $req_maj_stat ->bindParam(':statut_transaction', $statut);
	  $req_maj_stat ->bindParam(':id_transaction', $indx);
	  $req_maj_stat ->execute();

		$req_ajd = "INSERT INTO logmajstat (date_logmajstat, libelle_logmajstat, id_transaction, initiateur_logmajstat) VALUES (:date_logmajstat, :libelle_logmajstat, :id_transaction, :initiateur_logmajstat)";
		$req_aj_don = $bdd -> prepare($req_ajd);
		$req_aj_don ->bindParam(':date_logmajstat', $datee);
		$req_aj_don ->bindParam(':libelle_logmajstat', $libl);
		$req_aj_don ->bindParam(':id_transaction', $indx);
		$req_aj_don ->bindParam(':initiateur_logmajstat', $initt);
		$req_aj_don ->execute();

	}


	function connect(){
	  require 'connexion.php';

	  $ddj = date ('Y-m-d H:i:s');
	  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	          $ip = $_SERVER['HTTP_CLIENT_IP'];
	      } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	      } else {
	          $ip = $_SERVER['REMOTE_ADDR'];
	      }
		$_POST['password_adminMv'] = md5($_POST['password_adminMv']);
		// var_dump($_POST);
		$re10 = "SELECT * FROM adminMv WHERE password_adminMv ='".$_POST['password_adminMv']."' AND email_adminMv = '".$_POST['email_adminMv']."'";
			$u = $bdd -> query($re10);
			$nbuser = $u->rowCount();

	    if ($nbuser == 1) {
	      $user = $u->fetch();


				$rep['error'] = 0;


	    } else {
	      $rep['error'] = 1;
	    }

	    echo json_encode($rep);

	}

	function logmodif(){
		require 'connexion.php';

	  $ddj = date ('Y-m-d H:i:s');
		$tabl = $_POST["table"];

		$re2 = "SELECT * FROM logmajstat";
		if (isset($_GET['mvafp']) AND $_GET['mvafp'] == 1) {
			$re2 .= " WHERE logmajstat.initiateur_logmajstat LIKE '%adminMv'";
		}
		$lg = $bdd -> query($re2);
		$affich_el[0] = 1;
		$i=1;

		while ($logch = $lg->fetch()) {
			$re4 = "SELECT * FROM transaction INNER JOIN integrateur_transaction
																				ON transaction.id_transaction = integrateur_transaction.id_transaction
																				INNER JOIN integrateur
																				ON integrateur.id_integrateur = integrateur_transaction.id_integrateur WHERE transaction.id_transaction =".$logch['id_transaction'];
			$tr = $bdd -> query($re4);
			$trsx = $tr->fetch();

			$Adm = explode("-", $logch['initiateur_logmajstat']);
			$re3 = "SELECT * FROM admin WHERE id_admin = ".$Adm[0];
			$ad = $bdd -> query($re3);
			$admin = $ad->fetch();

			$affich_el[$i][0] = $trsx['reference_transaction'];
			$affich_el[$i][1] = $admin['nom_admin'];
			$affich_el[$i][2] = $logch['libelle_logmajstat'];
			$affich_el[$i][3] = "Afreekaplay.com";
			$affich_el[$i][4] = $trsx['nom_integrateur']."(".$trsx['detail_integrateur_transaction'].")";



			$i++;
		}
		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);

	}



switch ($_POST["nom_fct"]){

		case 0 : //cas de lajout

		ajouter();
		break;

		case 1 : //cas de la maj
		maj();
		break;

		case 2 : //cas de laffichage
		afficher();
		break;

		case 3 : //cas de la supprission
		supprimer();
		break;

		case 4 : //cas de la maj d'une colonne d'un elmt
		majun();
		break;

		case 5 : //cas de l'ajout dune cotisation
		cotiser();
		break;

		case 6 : //cas de l'ajout dune cotisation
		participer();
		break;

		case 7 : //cas de l'ajout dune cotisation
		 point();
		break;

		case 8 : //cas de l'ajout dune cotisation
		 actif();
		break;

		case 9 : //cas de l'ajout dune cotisation
		 pointsom();
		break;

		case 10 : //cas de l'ajout dune cotisation
		 supcot();
		break;

		case 11 : //cas de l'ajout dune cotisation
		 supact();
		break;

		case 12 : //cas de l'ajout dune cotisation
		 pointpret();
		break;

		case 13 : //cas de l'ajout dune cotisation
		 questionRep();
		break;

		case 14 : //cas de l'ajout dune cotisation
		 majQuestionRep();
		break;

		case 15 : //cas de l'ajout dune cotisation
		 majStatTrsx();
		break;

		case 16 : //cas de l'ajout dune cotisation
		 connect();
		break;

		case 17 : //cas de l'ajout dune cotisation
		 logmodif();
		break;

		case 18 : //cas de l'ajout dune cotisation
		 majunu();
		break;

	}
}}





/* code interessant
$fichier = strtr($fichier,
     'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
     'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
//On remplace les lettres accentutées par les non accentuées dans $fichier.
//Et on récupère le résultat dans fichier

//En dessous, il y a l'expression régulière qui remplace tout ce qui n'est pas une lettre non accentuées ou un chiffre
//dans $fichier par un tiret "-" et qui place le résultat dans $fichier.
$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

*/
?>
