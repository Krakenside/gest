<?php
header("Access-Control-Allow-Origin: *");

  // var_dump ($_POST)
	 // echo $_GET['nom_fct'];
	if(isset($_POST) AND isset($_POST["mob"]) AND ($_POST["table"] == 'recherche' OR $_POST["table"] == 'user')){
		$_GET['mob'] = $_POST['mob'];
		$_GET['nom_fct'] = $_POST['nom_fct'];
		$_GET['table'] = $_POST['table'];
		 // echo $_GET['nom_fct'];
	} else {
		$_POST = $_GET;
	};


 if (isset($_GET["mob"])){
	 // var_dump ($_GET);
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
     // public static function convert_from_latin1_to_utf8_recursively($dat){
     //      if (is_string($dat)) {
     //         return utf8_encode($dat);
     //      } elseif (is_array($dat)) {
     //         $ret = [];
     //         foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);
     //
     //         return $ret;
     //      } elseif (is_object($dat)) {
     //         foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);
     //
     //         return $dat;
     //      } else {
     //         return $dat;
     //      }
     //  }


	function ajouter(){

		require 'connexion.php';
		$tabl = $_POST["table"];
		//var_dump($_POST);
		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
		$i=0;
		while($champ = $req->fetch(PDO::FETCH_ASSOC))
	 {	//if(!preg_match("#id#", $champ["Field"])){
		 $table[$i] = $champ["Field"];
		 //echo $_POST[$champ["Field"]];

		 $i++;
	 //}
	 }
	 // $req->closeCursor();
	 if($tabl == 'user'){
		 $_POST["date_verif_user"] = null;
	 }
	 if($tabl == 'examen' OR $tabl == 'quizz'){
		 $req = $bdd->query("SHOW TABLE STATUS FROM evallesse LIKE '".$tabl."' ");
			$doncm = $req->fetch();
			// $req->closeCursor();

	 }
	 if($tabl == 'matiere'){
		 var_dump($_POST);
		$idClass = explode(',',$_POST['id_classe']);
		$nbClass = sizeof($idClass);
		$in =0;
		var_dump ($idClass);
		while($in < $nbClass-1){
			$re = "SELECT * FROM classe WHERE id_classe = ".$idClass[$in];
					$cla = $bdd -> query($re);
					$clas = $cla->fetch();
					// $req->closeCursor();

		$req = $bdd->query("SHOW TABLE STATUS FROM evallesse LIKE 'matiere' ");
			$donnees = $req->fetch();
			// $req->closeCursor();

			$titrcm = $_POST['titre_matiere'].' - '.$clas['titre_classe'];
			//echo $donnees['Auto_increment'].' / '.$titrcm;
			$req_ajd = "INSERT INTO classe_matiere (id_classe, id_matiere, titre_classe_matiere) VALUES (:id_classe, :id_matiere, :titre_classe_matiere)";
					$req_aj_don = $bdd -> prepare($req_ajd);
					$req_aj_don ->bindParam(':id_classe', $idClass[$in]);
					$req_aj_don ->bindParam(':id_matiere', $donnees['Auto_increment']);
					$req_aj_don ->bindParam(':titre_classe_matiere', $titrcm);
					$req_aj_don ->execute();
			$in++;
		}

		}


	 $i=1;
	 	while($i < sizeof($table))
	 {
		if(preg_match("#image#", $table[$i])){

		 $ch_image = $table[$i];
		 //echo $_FILES[$ch_image];
		 $valeur[$i] = $_FILES[$ch_image]['name'];

	 } else{
		 $valeur[$i] = $_POST[$table[$i]];
		}
	 $i++;
	 }

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





							// Testons si l'image petite a bien ??t?? envoy?? et s'il n'y a pas d'erreur
			if (isset($ch_image))
				{
			if (isset($_FILES[$ch_image]) AND $_FILES[$ch_image]['error'] == 0)
				{
				// Testons si le fichier n'est pas trop gros
					if ($_FILES[$ch_image]['size'] <= 1000000)
						{
				// Testons si l'extension est autoris??e
					$infosfichier = pathinfo($_FILES[$ch_image]['name']);
						$extension_upload = $infosfichier['extension'];
						$extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
						if (in_array($extension_upload, $extensions_autorisees))
							{
				// On peut valider le fichier et le stockerd??finitivement

					move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'images/up/lta/' .basename($_FILES[$ch_image]['name']));

					echo "L'image a bien ??t?? envoy?? ! <br />";
							}
						}
				} else {echo "pas de fichier";} }

					if($tabl == 'examen' OR $tabl == 'quizz'){
						echo $doncm['Auto_increment'];
					} else {
					header("location: ajout.php?t=".$tabl."&m=1");
							//echo '<br> cest bon';
					}
	}


	/* ------------- FONCTION AFFICHER -----------------*/


	function afficher(){

		require 'connexion.php';
		$tabl = $_POST["table"]; // recuperer la valeur de notre input table

		  if($_POST['table'] == "lecon"){
			$e=1;

		  } else if($_POST['table'] == "chapitre"){
			$e=2;
		  } else if($_POST['table'] == "quizz"){
			$e=3;

		  }
		  else if($_POST['table'] == "difficulte"){
			$e=4;
		  }
		  else if($_POST['table'] == "examen"){
			$e=5;
		  }
		  else if($_POST['table'] == "matiere"){
			$e=7;
		  }
		  else if($_POST['table'] == "user"){
			$e=9;

		  }
		  else if($_POST['table'] == "question_ex"){
			$e=10;

		  }
		  else if($_POST['table'] == "question_q"){
			$e=11;

		  }
		  else if($_POST['table'] == "corrige"){
			$e=12;

		  }

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
		 $req->closeCursor();



		 if(isset($ind)){
			 $tail = sizeof($ind);
			 $i=0;
			while($i<$tail){

			if($table[$ind[$i]] == 'id_chapitre'){
				$etrang[$i] = 'chapitre';
			}

			if($table[$ind[$i]] == 'id_classe'){
				$etrang[$i] = 'classe';
			}
			if($table[$ind[$i]] == 'id_classe_matiere'){
				$etrang[$i] = 'classe_matiere';
			}
			if($table[$ind[$i]] == 'id_difficulte'){
				$etrang[$i] = 'difficulte';
			}
			if($table[$ind[$i]] == 'id_examen'){
				$etrang[$i] = 'examen';
			}
			if($table[$ind[$i]] == 'id_lecon'){
				$etrang[$i] = 'lecon';
			}
			if($table[$ind[$i]] == 'id_matiere'){
				$etrang[$i] = 'matiere';
			}
			if($table[$ind[$i]] == 'id_quizz'){
				$etrang[$i] = 'quizz';
			}
			if($table[$ind[$i]] == 'id_user'){
				$etrang[$i] = 'user';
			}





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
		 $req->closeCursor();
		 $i++;
		 }
		 }
		$taille = sizeof($table);
			$req_aff = 'SELECT ';

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
			$req_aff .= $tablee.'.'.$table[$u]; // ajoute a la requete  les dif champs de la table

			if($u !== $v){
				$req_aff .= ", "; // apres un champs ajouter ajouter on mets une ","
			} else {
				if($e==7) {
					$req_aff .= ", classe_matiere.id_classe_matiere, classe_matiere.id_classe, classe_matiere.id_matiere, classe_matiere.titre_classe_matiere";
				}
				$req_aff .= " FROM ".$tabl; // lorsqu'on arrive au dernier elemt on ferme la parenthese
			}
			}
			$u++;
		}


		if(isset($ind)){
		$i = 0;
			$u = 0;
		while($u<$tail){
		$req_aff .= ' INNER JOIN '.$etrang[$u];

	  	$req_aff .= ' ON '.$tabl.'.'.$table[$ind[$u]].' = '.$etrang[$u].'.'.$table[$ind[$u]]; // ajoute a la requete  les dif champs de la table pour la jointure





			$u++;
		}
		if ($e == 1) {
	      	$req_aff .= ' WHERE chapitre.id_chapitre = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 2) {
	      	$req_aff .= ' WHERE classe_matiere.id_classe_matiere = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 3) {
	      	$req_aff .= ' WHERE lecon.id_lecon = '.$_GET['s'].' ORDER BY difficulte.id_difficulte ASC'; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 5) {
	      	$req_aff .= ' WHERE classe_matiere.id_classe = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 10) {
	      	$req_aff .= ' WHERE examen.id_examen = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
		if ($e == 11) {
	      	$req_aff .= ' WHERE quizz.id_quizz = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
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
		if ($e == 7) {
	      	$req_aff .= ' INNER JOIN classe_matiere ON classe_matiere.id_matiere = matiere.id_matiere WHERE classe_matiere.id_classe = '.$_GET['s']; // ajoute a la requete  les dif champs de la table pour la jointure
	    }
			/*else if($_POST['table'] == "utilisateur"){
	    $e=5;
	  }*/
		//echo $req_aff.'<br>';
			$i = 1;
			$j = 0;
			$affich_el[] = '';
			$tabindic[]='';

			switch ($e){
			case '1' :
			$tabindic = array(0,1,2,3,7);
			break;

			case '2' :
			$tabindic = array(0,1,2,7);
			break;

			case '3' :
			$tabindic = array(0,1,2,3,5,7);
			break;

			case '4' :
			$tabindic = array(1);
			break;

			case '5' :
			$tabindic = array(1,2,3,8);
			break;

			case '6' :
			$tabindic = array(1,2,3,7);
			break;

			case '7' :
			$tabindic = array(1,2,6);
			break;

			case '8' :
			$tabindic = array(1,5,7);
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
			$tabindic = array(1,5,2);
			break;


		}



		$ttab = sizeof($tabindic);

		//echo $req_aff;
		$affich_el[0]=1;
		$aff = $bdd -> query($req_aff);
		while($affich = $aff->fetch()){
		//var_dump($affich);
			$j=0;
			while($j < $ttab){

				$affich_el[$i][$j] = $affich[$tabindic[$j]];
    			$j++;



			}
			if($j == $ttab){
				if($e == 1){
					$re2 = "SELECT * FROM quizz WHERE id_lecon=".$affich[0];
					$nbq = $bdd -> query($re2);
					$nbqu = $nbq->rowCount();
					$affich_el[$i][$j] = $nbqu;
					$j++;
				}
				if($e == 2){

					$re2 = "SELECT * FROM lecon WHERE id_chapitre=".$affich[0];
					$nbl = $bdd -> query($re2);
					$nblec = $nbl->rowCount();
					$affich_el[$i][$j] = $nblec;
					$j++;
				}

				if($e == 3){

					$re2 = "SELECT * FROM resultat_q WHERE id_quizz=".$affich[0]." AND id_user = ".$_GET['id_user'];
					$q = $bdd -> query($re2);
					$qz = $q->fetch();
					$q->closeCursor();
					$affich_el[$i][$j] = $qz['score_resultat_q'];
					$j++;
				}


				if($e == 4){


					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=difficulte' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 5){

					$affich_el[$i][$j] = "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Questions</button><div class='dropdown-menu'><a class='dropdown-item' href='ajoutqurep.php?t=examen&indx=".$affich[0]."'>Ajouter</a><a class='dropdown-item' href='liste.php?dest=question_ex&s=".$affich[0]."'>Liste</a></div>";
					$j++;
					$affich_el[$i][$j] = "<a type='button' href='modifiereq.php?indx=".$affich[0]."&t=examen' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 6){

					$re2 = "SELECT * FROM quizz WHERE id_lecon=".$affich[0];
					$nbq = $bdd -> query($re2);
					$nbqu = $nbq->rowCount();
					$affich_el[$i][$j] = $nbqu;
					$j++;
					$affich_el[$i][$j] = "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Quizz</button><div class='dropdown-menu'><a class='dropdown-item' href='ajouteq.php?t=quizz&indx=".$affich[0]."&s2=".$_GET['s']."'>Ajouter</a><a class='dropdown-item' href='liste.php?dest=quizz&s=".$affich[0]."'>Liste</a></div>";
					$j++;
					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=lecon' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 7){
					//$re3 = "SELECT * FROM classe_matiereWHERE id_classe_matiere=".$affich[3];
					$re2 = "SELECT * FROM chapitre WHERE id_classe_matiere=".$affich[3];

					$nbcha = $bdd -> query($re2);
					$nbchap = $nbcha->rowCount();
					$affich_el[$i][$j] = $nbchap;
					$j++;
					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=matiere' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 9){


					$affich_el[$i][$j] = "<a type='button' href='statistique_user.php?indx=".$affich[0]."' class='btn btn-block btn-default' style=' '>Afficher</a>";
					$j++;
					$affich_el[$i][$j] = "<a type='button' href='modifierSimpl.php?indx=".$affich[0]."&t=user' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 10){

					$affich_el[$i][$j] = ($affich[3] == 0) ? "Texte" : "Image";
					$j++;
					$affich_el[$i][$j] = ($affich[4] == 0) ? "Choix unique" : "Choix multiple";
					$j++;
					$affich_el[$i][$j] = $affich[7];
					$j++;
					$affich_el[$i][$j] = "<a type='button' href='modifierqurep.php?indx=".$affich[0]."&t=question_ex' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}
				if($e == 11){

					$affich_el[$i][$j] = ($affich[3] == 0) ? "Texte" : "Image";
					$j++;
					$affich_el[$i][$j] = ($affich[4] == 0) ? "Choix unique" : "Choix multiple";
					$j++;
					$affich_el[$i][$j] = $affich[7];
					$j++;
					$affich_el[$i][$j] = "<a type='button' href='modifierqurep.php?indx=".$affich[0]."&t=question_q' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";*/
				}

				/*
				if($e == 13){
					$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$affich[1]." AND date_membre_cot = '".$_GET['m']."' ORDER BY id_membre_cot DESC";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
					if($mcot['som_membre_cot'] !== null){
						$affich_el[$i][1] = $mcot['som_membre_cot'];
					}
					$ddj = $_GET['m'];
					$les_d = explode('-',$ddj);

					$re = "SELECT * FROM cotisem WHERE id_membre = '".$affich[1]."'
									AND cotisem.date_cotisem BETWEEN '".$les_d['0']."-01-01' AND '".$les_d['0']."-12-31'";
					$co = $bdd -> query($re);
					$z=0;
					$li=1;
					$les_dc = [];
					$les_dc[0] = '*';
					while($lcot = $co->fetch()){
						//var_dump($lcot);
						if (isset($lcot[0])){
							$le_dc = explode('-',$lcot[1]);
							$les_dc[$z]= intval($le_dc[1]);
							$li = sizeof($les_dc);
							$z++;
						}
					}
					//echo $li;
					//var_dump ($les_dc);
					//$l = intval($les_d[1]+1);



					$h=1;
					//$jan = strtotime($les_d[0]'-01-01');
					while($h <= 12){
						$z=0;

						while($z < $li){
							//if($les_dc[0] !== '*'){

								if ($h == $les_dc[$z]){
									//echo $h.' == '.$les_dc[$z];
									//$affich_el[$i][$j] = "<input style='margin-left:5%;' type='checkbox' class='moisc' name='s".$h."' value='off' data-mem='".$affich[4]."' data-m='s".$h."' disabled>";
									$affich_el[$i][$j] = "OK";
									$z = $li;
							//	}
							} else {
								$affich_el[$i][$j] = "<input style='margin-left:5%;' type='checkbox' class='moisc' name='s".$h."' value='off' data-mem='".$affich[1]."' data-m='s_".$h."'>";
								$z++;
							}
						}
					$j++;
					$h++;

					}
					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";* /
					$affich_el[$i][$j] = "<a type='button' href='profile.php?indx=".$affich[1]."' class='btn btn-block btn-default' style=' '>Modifier</a>";
					$j++;
					$affich_el[$i][$j] = "<div id='".$affich[1]."' ><button type='button' data-id='".$affich[1]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[1]."r2' class='hide'><button type='button' data-id='".$affich[1]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";
				}
				if($e == 2){


						$re = "SELECT * FROM membre
								INNER JOIN membre_typm
									ON membre_typm.id_membre = membre.id_membre
									WHERE id_section = '".$affich[0]."'";
						$nbm = $bdd -> query($re);
						$nbmem = $nbm -> rowCount();
						$affich_el[$i][$j] = $nbmem;
							$j++;

					$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";


				}
				if($e == 1){
					$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$affich[1]." AND date_membre_cot = '".$_GET['a']."-01'";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
					$affich_el[$i][2] = $mcot['som_membre_cot'];

						$affich_el[$i][$j] = "<a type='button' href='profile.php?indx=".$affich[1]."' class='btn btn-block btn-default' style=' '>Modifier</a>";
							$j++;
						$affich_el[$i][$j] = "<div id='".$affich[1]."' ><button type='button' data-id='".$affich[1]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[1]."r2' class='hide'><button type='button' data-id='".$affich[1]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

				}
				if($e == 7){


						$affich_el[$i][$j] = "<div id='".$affich[0]."' ><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-info' style=' '>Supprimer</button></div><div id='".$affich[0]."r2' class='hide'><button type='button' data-id='".$affich[0]."' class='btn btn-block btn-danger valid_sup'>Valider</button></div>";

				}
				if($e == 3){
					if(!isset($_GET['a'])){
						$ddj = date ('Y-m-d');
						$d = explode('-',$ddj);
						$_GET['a'] = $d[0].'-'.$d[1];

					}

					$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$affich[1]." AND date_membre_cot = '".$_GET['a']."-01'";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
					$affich_el[$i][1] = $mcot['som_membre_cot'];

					$re = "SELECT * FROM cotisation
								INNER JOIN jour
									ON jour.id_jour = cotisation.id_jour
									WHERE id_membre = '".$affich[1]."'
									AND jour.date_jour BETWEEN '".$_GET['a']."-01' AND '".$_GET['a']."-31'";

					$co = $bdd -> query($re);
					$z=0;
					$li=1;
					$les_dc = [];
					$les_dc[0] = '*';
					while($lcot = $co->fetch()){


						if (isset($lcot[0])){
							$le_dc = explode('-',$lcot[6]);
							$les_dc[$z]= intval($le_dc[2]);
							$li = sizeof($les_dc);
							$z++;
						}
					}
					//echo $li;
					/*$ddj = $_GET['m'];
					$les_d = explode('-',$ddj);* /

					$h=1;
					//$l = intval($les_d[1]+1);
					while($h <= 31){
						$z=0;
						while($z < $li){
							//if($les_dc[0] !== '*'){

								if ($h == $les_dc[$z]){
									//$affich_el[$i][$j] = "<input style='margin-left:5%;' type='checkbox' class='moisc' name='s".$h."' value='off' data-mem='".$affich[4]."' data-m='s".$h."' disabled>";
									$affich_el[$i][$j] = "OK";
									$z = $li;
							//	}
							} else {
								$affich_el[$i][$j] = "-";
								$z++;
							}
						}
					$j++;
					$h++;

					}
					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";* /
				}
				if($e == 8){
					$affich_el[$i][$j] = intval($affich[16]) - intval($affich[1]);
				}
				if($e == 10){
					$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$affich['id_membre']." AND date_membre_cot = '".$_GET['a']."-01'";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
					$affich_el[$i][1] = $mcot['som_membre_cot'];
				}
				if($e == 11){
					$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$affich['id_membre']." AND date_membre_cot = '".$affich['mois_point']."'";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
					$affich_el[$i][1] = $mcot['som_membre_cot'];
				}
				if($e == 9){
					$ddj = date ('Y-m-d');
							$d = explode('-',$ddj);
					$re = "SELECT * FROM acti_membre
								INNER JOIN activite
									ON activite.id_activite = acti_membre.id_activite
									WHERE acti_membre.id_membre = '".$affich['id_membre']."'  ";
					$co = $bdd -> query($re);
					$re1 = "SELECT * FROM activite
							WHERE date_activite >= '".$ddj."' ";

							$co1 = $bdd -> query($re1);
							$act = $co1 -> rowCount();
					$z=0;
					$li=1;
					$les_dc = [];
					$les_dc[0] = '*';

					while($lcot = $co->fetch()){

						if (isset($lcot['id_activite'])){
							$les_dc[$z]= $lcot['id_activite'];
							$li = sizeof($les_dc);
							$z++;
					//var_dump($les_dc);
						}
					}
					//echo $li;
					$h=0;
					while($act = $co1 -> fetch()){
						$z=0;
						while($z < $li){
							//if($les_dc[0] !== '*'){

								if ($act['id_activite'] == $les_dc[$z]){
									//$affich_el[$i][$j] = "<input style='margin-left:5%;' type='checkbox' class='moisc' name='s".$h."' value='off' data-mem='".$affich[4]."' data-m='s".$h."' disabled>";
									$affich_el[$i][$j] = "<i class='fa fa-check'></i>";
									$z = $li;
							//	}
							} else {
								$affich_el[$i][$j] = "<input style='margin-left:5%;' type='checkbox' data-mem='".$affich['id_membre']."' class='actc' data-a='".$act['id_activite']."' >";
								$z++;
							}
						}
					$j++;
					$h++;

					}
					/*$affich_el[$i][$j] = "<a type='button' href='modifier.php?id=".$affich[0]."&table=".$tabl."' class='btn btn-block btn-default' style=' '>Modifier</a>";* /
				} */
			}
			$i++;
		}
		$aff->closeCursor();
		//var_dump($affich_el);

		echo json_encode($affich_el);

	}

/* ------------- FONCTION SUPPRIMER -----------------*/

	function supprimer(){


		var_dump($_POST);
		require 'connexion.php';
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
		 $req->closeCursor();

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
		$tabl = $_POST["table"];
		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
		$i=0;
		while($champ = $req->fetch(PDO::FETCH_ASSOC))
		 {	//if(!preg_match("#id#", $champ["Field"])){
			 $table[$i] = $champ["Field"];
			 //echo $_POST[$champ["Field"]];

			 $i++;
		 //}
		 }
		 // $req->closeCursor();
		 $i=0;
		 	while($i < sizeof($table))
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
		 }

 	 $taille = sizeof($table);


		$req_ajou = ' UPDATE '.$tabl.' SET ';  //UPDATE client SET prenomClient = :prenomClient, dateNaisClient = :dateNaisClient, email = :email, 	telephone = :telephone, residence = :residence WHERE idClient = :idClient

		$u = 1;
		while($u<$taille){

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
			$u++;
		}

		// echo $req_ajou;
	 $req_ajou_donne = $bdd -> prepare($req_ajou);

			$i=0;
			// echo $taille;
			while($i<$taille){
				$value = ":".$table[$i];
				// echo $valeur[$i];
										$req_ajou_donne ->bindParam($value, $valeur[$i]);

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
                      // Testons si l'extension est autoris??e
                        $infosfichier = pathinfo($_FILES[$ch_image]['name']);
                          $extension_upload = $infosfichier['extension'];
                          $extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
                          if (in_array($extension_upload, $extensions_autorisees))
                            {
                      // On peut valider le fichier et le stockerd??finitivement

                        move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'images/up/lta/' .$_FILES[$ch_image]['name'].'-'.$dte.'.png');

                        echo "L'image a bien ??t?? envoy?? ! <br />";
                            }
                          }
                      } else {echo "pas de fichier";} }



							header("location: modifierSimpl.php?t=".$tabl."&indx=".$idCle);


	}



	function maju(){

		require 'connexion.php';
		$dte = date('d-m-y hms');
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
		 // $req->closeCursor();
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
		} else{
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
	                      // Testons si l'extension est autoris??e
	                        $infosfichier = pathinfo($_FILES[$ch_image]['name']);
	                          $extension_upload = $infosfichier['extension'];
	                          $extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
	                          if (in_array($extension_upload, $extensions_autorisees))
	                            {
	                      // On peut valider le fichier et le stockerd??finitivement

	                        move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'images/up/lta/' .$_FILES[$ch_image]['name'].'-'.$dte.'.png');

	                        //echo "L'image a bien ??t?? envoy?? ! <br />";
	                            }
	                          }
	                      } else { //echo "pas de fichier";
							}
						}
							//header("location: acceuil.php");
							echo 'ok';
	}


	function majun(){

		require 'connexion.php';
		$dte = date('d-m-y hms');
		$tabl = $_POST["table"];
		$indx = $_POST["id_membre"];
		var_dump($_POST);


		$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
		$i=0;
		while($champ = $req->fetch(PDO::FETCH_ASSOC))
		 {	//if(!preg_match("#id#", $champ["Field"])){
			 $table[$i] = $champ["Field"];
			 //echo $_POST[$champ["Field"]];

			 $i++;
		 //}
		 }
		 // $req->closeCursor();
	 $i=0;

	 	while($i < sizeof($table))
	 {
		if(preg_match("#image#", $table[$i])){

		 $ch_image = $table[$i];
		 //echo $_FILES[$ch_image];
		 //$valeur[$i] = $_FILES[$ch_image]['name'];
		 $valeur[$i] = $_FILES[$ch_image]['name'].'-'.$dte.'.png';

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
			if($table[$u] !== 'commi_membre') {
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
			}
			$u++;
		}

		//  echo $req_majun;
	 $req_majun_donne = $bdd -> prepare($req_majun);

			$i=0;
			//echo $taille;
				$u = 0;
		while($u<$taille){
			if($valeur[$i] !=='' AND $table[$u] !== 'commi_membre'){
				$valu = ":".$table[$u];

				// echo $valu;
										$req_majun_donne ->bindParam($valu, $valeur[$i]);
			}

			$u++;
			$i++;
		}
		//var_dump($req_majun_donne);
						$req_majun_donne -> execute();

		if($tabl = 'membre'){


			$re2 = "SELECT * FROM membre_cot WHERE id_membre=".$indx." AND date_membre_cot = '".$_POST['date_membre_cot']."'";
					$mco = $bdd -> query($re2);
					$mcot = $mco->fetch();
					// $mco->closeCursor();
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
				$dda = $_POST['date_membre_cot'];
				$req_aj_don ->bindParam(':som_membre_cot', $_POST['commi_membre']);
				$req_aj_don ->bindParam(':date_membre_cot', $dda);
				$req_aj_don ->bindParam(':id_membre', $indx);
				$req_aj_don ->execute();
			}
		}



                    if (isset($ch_image))
                      {
                    if (isset($_FILES[$ch_image]) AND $_FILES[$ch_image]['error'] == 0)
                      {
                      // Testons si le fichier n'est pas trop gros
                        if ($_FILES[$ch_image]['size'] <= 1000000)
                          {
                      // Testons si l'extension est autoris??e
                        $infosfichier = pathinfo($_FILES[$ch_image]['name']);
                          $extension_upload = $infosfichier['extension'];
                          $extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
                          if (in_array($extension_upload, $extensions_autorisees))
                            {
                      // On peut valider le fichier et le stockerd??finitivement

                        move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'images/up/lta/' .$_FILES[$ch_image]['name'].'-'.$dte.'.png');

                        //echo "L'image a bien ??t?? envoy?? ! <br />";
                            }
                          }
                      } else { //echo "pas de fichier";
						}
					}
					if($tabl = 'membre'){
						header("location: modifier.php?t=".$tabl."&indx=".$indx."&m=1&d=".$dda);
					} else{
						header("location: modifier.php?t=".$tabl."&indx=".$indx."&m=1");
					}
					echo 'ok';
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
		$act->closeCursor();


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
		//var_dump ($_POST);
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
				// echo $_POST[$nom3];
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

		header("location: ajoutqurep.php?t=".$tabl."&indx=".$idCle);

	}

	function connexion(){
		require 'connexion.php';
		//var_dump ($_GET);

		$_SESSION['connect']=0; //Initialise la variable 'connect'.

		if (isset($_GET['login']) ) // Si les variables existent.
		{
				//On r??cup??re les donn??es envoy??es par la m??thode POST du formulaire d'identification.
				// le htmlentities() passera les guillemets en entit??s HTML, ce qui emp??chera les injections SQL
				$login = htmlentities($_GET['login'], ENT_QUOTES, "ISO-8859-1");



				$ip = $_SERVER['REMOTE_ADDR'];

				//$bdd = new Cbdd('localhost', 'cmsbdd', 'root', '');
				//$bdd->connexion('localhost', 'cmsbdd', 'root', '');
				$requet = $bdd -> query("SELECT * FROM user LEFT JOIN pays
																							ON user.id_pays = pays.id_pays
																							WHERE user.telephone_user ='".$login."' OR user.email_user ='".$login);

						$colon = $requet -> rowCount();
					   if( $colon == 1) {

 							$clientI = $requet -> fetch();


							$_SESSION['connect']='1'; // Change la valeur de la variable connect. C'est elle qui nous permettra de savoir s'il y a eu identification.
							$_SESSION['telephone_user']=$login;// Permet de r??cup??rer le login afin de personnaliser la navigation.
							$_SESSION["user"] = $clientI["nom_user"];
							$_SESSION["userID"] = $clientI["id_user"];
							$_SESSION["user_mail"] = $clientI["email_user"];
							$_SESSION["avatar"] = $clientI["avatar_user"];
							$_SESSION["sexe"] = $clientI["sexe_user"];
							$_SESSION["codePays"] = $clientI["code_pays"];
							$_SESSION["indicatifPays"] = $clientI["indicatif_pays"];

							$requetStr = $bdd -> query('SELECT * FROM user_forfaitStream
																					INNER JOIN forfaitStream
																					ON user_forfaitStream.id_forfaitStream = forfaitStream.id_forfaitStream
																					WHERE user_forfaitStream.id_user =\''.$clientI["id_user"].'\' AND dateFin_user_forfaitStream <= \''.$dateAuj.'\'');
							$Stre = $requetStr -> fetch();
							if ($Stre['id_user_forfaitStream'] !== NULL) {
								$_SESSION["forfait"]["id"] = $Stre["id_user_forfaitStream"];
								$_SESSION["forfait"]["debut"] = $Stre["dateDeb_user_forfaitStream"];
								$_SESSION["forfait"]["fin"] = $Stre["dateFin_user_forfaitStream"];
								$_SESSION["forfait"]["moyen"] = $Stre["moyenPaie_user_forfaitStream"];
								$_SESSION["forfait"]["moyen"] = $Stre["renouvellement_user_forfaitStream"];
								$_SESSION["forfait"]["forfaitID"] = $Stre["id_forfaitStream"];
								$_SESSION["forfait"]["forfaitNom"] = $Stre["nom_forfaitStream"];
								$_SESSION["forfait"]["forfaitPrix"] = $Stre["prix_forfaitStream"];
								$_SESSION["forfait"]["forfaitTemps"] = $Stre["temps_forfaitStream"];
							}

							echo json_encode($_SESSION);

						} else {
							$rep['connect']='0';
							echo json_encode($rep);
						}
		}
	}

	function listAAS(){
		require 'connexionlist.php';
		$i =1;
		$affich_el[] = '';
		$affich_el2[] = '';
		$affich_el3[] = '';
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
       // var_dump($response);
			 $payss = (isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";
			 //echo $payss;
			 $reqP = "SELECT * FROM pays
 			 												INNER JOIN devise
 															ON devise.id_devise = pays.id_devise
 															WHERE code_pays ='".$payss."'";
      $pa = $bdd -> query($reqP);
      $pay = $pa->fetch();
			$pa->closeCursor();
	    $devise = $pay['signe_devise'];

      if(isset($pay['id_devise']) AND $pay['id_devise'] !== '1') {
          $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
          $ta = $bdd -> query($reqT);
          $taux = $ta->fetch();
          $nbtx = $ta->rowCount();
					$ta->closeCursor();

          if ($nbtx !== 0) {
            $tx = $taux['taux_taux'];
          }
        } else {
          $pay['id_devise'] = 1;
        }
      //var_dump($tx);
    }
    // $reqD = "SELECT * FROM devise WHERE id_devise =".$pay['id_devise'];
    // $dv = $bdd -> query($reqD);
    // $dvse = $dv->fetch();
		// $dv->closeCursor();


	 if($_GET['table'] == 'son'){

			$re = "SELECT * FROM son LEFT JOIN album
										  ON son.id_album = album.id_album
										  LEFT JOIN artiste
										  ON artiste.id_artiste = son.id_artiste
										  LEFT JOIN lyric
										  ON lyric.id_son = son.id_son
                      WHERE son.date_verif <= '".$dateAuj."' AND son.is_active = 1";
      if (isset($_GET['free']) AND $_GET['free'] == 1) {
        $re .= " AND son.prix_son = 0";
      }
      if (isset($_GET['nouv']) AND $_GET['nouv'] == 1) {
        $re .= "  ORDER BY son.dateSortie_son DESC, son.id_son ASC";
      } else if (isset($_GET['lns']) AND $_GET['lns'] == 1) {
        $re .= "  ORDER BY son.dateSortie_son DESC, son.id_son ASC LIMIT 7";
      } else {
         // $re .= " ORDER BY son.titre_son";
         $re .= " ORDER BY son.dateSortie_son DESC, son.id_son ASC";
      }
			$son = $bdd -> query($re);
			$nbson = $son -> rowCount();
			 // echo $nbson;
			$affich_el[0]=1;
			if($nbson == 0){
				$affich_el[$i]['id'] = 0;
				$affich_el[$i]['name'] = "Aucun son";
				$affich_el[$i]['artist'] = "Aucun";
				$affich_el[$i]['album'] = "Aucun";
				$affich_el[$i]['url'] = "";
				$affich_el[$i]['cover_art_url'] = "";
				$affich_el[$i]['cover_url'] = "";
				$affich_el[$i]['price'] = "0";
				$affich_el[$i]['downloads'] = "0";
				$affich_el[$i]['lyrics'] = "Aucun";
				$affich_el[$i]['reference'] = "";
				$affich_el[$i]['dateSortie'] = "";
				$affich_el[$i]['lien'] = "";
				$affich_el[$i]['genre'] = "";
				$affich_el[$i]['devise'] = $devise;
			} else {

			while ($sons = $son->fetch() ){
				    // var_dump ($sons);

				$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
				$reqspre = $bdd -> query($re2);
				$reqsprec = $reqspre->fetch();
				// $reqspre->closeCursor();

				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
				$nb = $bdd -> query($re1);
				$nbs = $nb->rowCount();
				// $nb->closeCursor();
				//$j = 0;
				// echo $i;
				if ($i < 4) {
					$affich_el3[$i]['id'] = $sons['0'];
					$affich_el3[$i]['name'] = $sons['2'];
					$affich_el3[$i]['artist'] = $sons['nom_artiste'];
					$affich_el3[$i]['album'] = $sons['titre_album'];
					$affich_el3[$i]['url'] = $sons['fichier_son'];
					$affich_el3[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el3[$i]['cover_url'] = $sons['cover_son'];
					$affich_el3[$i]['price'] = $sons['prix_son']*$tx;
					$affich_el3[$i]['downloads'] = $nbs;
					$affich_el3[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el3[$i]['reference'] = $sons['reference_son'];
					$affich_el3[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el3[$i]['lien'] = $sons['lien_son'];
					$affich_el3[$i]['genre'] = $sons['13'];
					$affich_el3[$i]['devise'] = $devise;
					$affich_el3[$i]['idArtist'] = $sons['id_artiste'];
					$affich_el3[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
					$affich_el3[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
				} else {

					$affich_el2[$i]['id'] = $sons['0'];
					$affich_el2[$i]['name'] = $sons['2'];
					$affich_el2[$i]['artist'] = $sons['nom_artiste'];
					$affich_el2[$i]['album'] = $sons['titre_album'];
					$affich_el2[$i]['url'] = $sons['fichier_son'];
					// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
					$affich_el2[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el2[$i]['cover_url'] = $sons['cover_son'];
					$affich_el2[$i]['price'] = $sons['prix_son']*$tx;
					$affich_el2[$i]['downloads'] = $nbs;
					$affich_el2[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el2[$i]['reference'] = $sons['reference_son'];
					$affich_el2[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el2[$i]['lien'] = $sons['lien_son'];
					$affich_el2[$i]['genre'] = $sons['13'];
					$affich_el2[$i]['devise'] = $devise;
					$affich_el2[$i]['idArtist'] = $sons['id_artiste'];
					$affich_el2[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
					$affich_el2[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
				}

				$i++;

			}
			$son->closeCursor();
			}
			$affich_e = array_shift($affich_el2);
			$affich_e = array_shift($affich_el3);
			//var_dump($affich_e);
			// var_dump($affich_el3);
			// var_dump($affich_el2);

			// echo json_encode($affich_el);
			shuffle($affich_el2);
			$affich_el = array_merge($affich_el3, $affich_el2);
			echo json_encode($affich_el);
      // echo json_last_error_msg();

	   }

     else if($_GET['table'] == 'artiste'){

			 $affich_el[0]=1;

			 	$reHot = "SELECT * FROM hotartiste INNER JOIN artiste
				 																		ON hotartiste.id_artiste = artiste.id_artiste
																						WHERE artiste.is_active = 1
																						ORDER BY hotartiste.position_hotartiste ASC";
				$hotar = $bdd -> query($reHot);
				while ($hotartis = $hotar->fetch()){

					$reNH = "SELECT * FROM pays WHERE code_pays ='".$hotartis['nationalite_artiste']."'";
					$naH = $bdd -> query($reNH);
					$natH = $naH->fetch();
					//$j = 0;
					$affich_el[$i]['id'] = $hotartis['id_artiste'];
					$affich_el[$i]['name'] = $hotartis['nom_artiste'];
					$affich_el[$i]['dob'] = $hotartis['dob_artiste'];
					$affich_el[$i]['cover_url'] = $hotartis['cover_artiste'];
					$affich_el[$i]['bio'] = $hotartis['biographie_artiste'];
					$affich_el[$i]['natio'] = $natH['nom_pays'];
					$affich_el[$i]['lien'] = $hotartis['lien_artiste'];

					$i++;

				}


				if (!isset($_GET['lns'])) {
					// AND $_GET['lns'] !== 1
					$re = "SELECT * FROM artiste ORDER BY nom_artiste";

					//$re .= " LIMIT 7";

					$ar = $bdd -> query($re);
					$nbar = $ar -> rowCount();
					//echo $re;

					if($nbar == 0){
						$affich_el[$i]['id'] = 0;
						$affich_el[$i]['name'] = "Aucun artiste";
						$affich_el[$i]['dob'] = "";
						$affich_el[$i]['cover_url'] = "";
						$affich_el[$i]['bio'] = "0";
					} else {

						while ($artis = $ar->fetch() ){

			        $reN = "SELECT * FROM pays WHERE code_pays ='".$artis['nationalite_artiste']."'";
			  			$na = $bdd -> query($reN);
			        $nat = $na->fetch();
							//$j = 0;
							$affich_el[$i]['id'] = $artis['id_artiste'];
							$affich_el[$i]['name'] = $artis['nom_artiste'];
							$affich_el[$i]['dob'] = $artis['dob_artiste'];
							$affich_el[$i]['cover_url'] = $artis['cover_artiste'];
							$affich_el[$i]['bio'] = $artis['biographie_artiste'];
							$affich_el[$i]['natio'] = (isset($nat['nom_pays'])) ? $nat['nom_pays'] : "";
							$affich_el[$i]['lien'] = $artis['lien_artiste'];

							$i++;

						}
						$ar->closeCursor();
						$na->closeCursor();
					}
				}


			$affich_e = array_shift($affich_el);
			// shuffle($affich_el);
			echo json_encode($affich_el);

		}
		else if ($_GET['table'] == 'album'){

			$re = "SELECT * FROM album INNER JOIN artiste
										  ON artiste.id_artiste = album.id_artiste WHERE album.date_verif <= '".$dateAuj."' AND album.is_active = 1";
        if (isset($_GET['free']) AND $_GET['free'] == 1) {
          $re .= " AND album.prix_album = 0";
        }
        if (isset($_GET['nouv']) AND $_GET['nouv'] == 1) {
          $re .= "  ORDER BY album.dateSortie_album DESC, album.id_album ASC";
        } else {
           $re .= " ORDER BY album.dateSortie_album DESC, album.id_album ASC";
        }
			$al = $bdd -> query($re);
			$nba = $al -> rowCount();
			// echo $nba;
			$affich_el[0]=1;
			if($nba == 0){
				$affich_el[$i]['id'] = 0;
				$affich_el[$i]['name'] = "Aucun son";
				$affich_el[$i]['artist'] = "Aucun";
				$affich_el[$i]['cover_art_url'] = "";
				$affich_el[$i]['cover_url'] = "";
				$affich_el[$i]['url'] = "";
				$affich_el[$i]['price'] = "0";
				$affich_el[$i]['downloads'] = "0";
				$affich_el[$i]['reference'] = "";
				$affich_el[$i]['dateSortie'] = "";
        $affich_el[$i]['devise'] = "";
        $affich_el[$i]['genre'] = "";

			} else {

			while ($alb = $al->fetch() ){

				$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
				$reqspre = $bdd -> query($re2);
				$reqsprec = $reqspre->fetch();

				$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
				$nb = $bdd -> query($re1);
				$nba = $nb->rowCount();
				//$j = 0;
  			// var_dump($alb);
				if ($i < 4) {
					$affich_el3[$i]['id'] = $alb['id_album'];
					$affich_el3[$i]['name'] = $alb['titre_album'];
					$affich_el3[$i]['artist'] = $alb['nom_artiste'];
					$affich_el3[$i]['cover_art_url'] = $alb['cover_artiste'];
					$affich_el3[$i]['cover_url'] = $alb['cover_album'];
					$affich_el3[$i]['url'] = $alb['fichier_album'];
					$affich_el3[$i]['price'] = $alb['prix_album']*$tx;
					$affich_el3[$i]['downloads'] = $nba;
					$affich_el3[$i]['reference'] = $alb['reference_album'];
					$affich_el3[$i]['dateSortie'] = $alb['dateSortie_album'];
					$affich_el3[$i]['lien'] = $alb['lien_album'];
	        $affich_el3[$i]['devise'] = $devise;
					$affich_el3[$i]['idArtist'] = $alb['id_artiste'];
	        $affich_el3[$i]['genre'] = $alb['11'];
					$affich_el3[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 3000*$tx;
					$affich_el3[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande'] : 0;
				} else {
					$affich_el2[$i]['id'] = $alb['id_album'];
					$affich_el2[$i]['name'] = $alb['titre_album'];
					$affich_el2[$i]['artist'] = $alb['nom_artiste'];
					$affich_el2[$i]['cover_art_url'] = $alb['cover_artiste'];
					$affich_el2[$i]['cover_url'] = $alb['cover_album'];
					$affich_el2[$i]['url'] = $alb['fichier_album'];
					$affich_el2[$i]['price'] = $alb['prix_album']*$tx;
					$affich_el2[$i]['downloads'] = $nba;
					$affich_el2[$i]['reference'] = $alb['reference_album'];
					$affich_el2[$i]['dateSortie'] = $alb['dateSortie_album'];
					$affich_el2[$i]['lien'] = $alb['lien_album'];
	        $affich_el2[$i]['devise'] = $devise;
					$affich_el2[$i]['idArtist'] = $alb['id_artiste'];
	        $affich_el2[$i]['genre'] = $alb['11'];
					$affich_el2[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 3000*$tx;
					$affich_el2[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
				}

				$i++;

			}
			$al->closeCursor();
			$reqspre->closeCursor();
			}
			$affich_e = array_shift($affich_el2);
			$affich_e = array_shift($affich_el3);
			//var_dump($affich_e);
			// var_dump($affich_el3);
			// var_dump($affich_el2);

			// echo json_encode($affich_el);
			shuffle($affich_el2);
			$affich_el = array_merge($affich_el3, $affich_el2);
			echo json_encode($affich_el);



		}
		else if($_GET['table'] == 'genre'){

			$re = "SELECT * FROM genre ";
			$gr = $bdd -> query($re);
			$nbg = $gr -> rowCount();
			//echo $re;
			$affich_el[0]=1;
			if($nbg == 0){
				$affich_el[$i]['id'] = 0;
				$affich_el[$i]['name'] = "Aucun son";
				$affich_el[$i]['cover_url'] = "";
			} else {

			while ($genr = $gr->fetch() ){

				// $re1 = "SELECT * FROM telechargement WHERE id_album=".$genr['id_album'];
				// $nb = $bdd -> query($re1);
				// $nba = $nb->rowCount();
				//$j = 0;
				$affich_el[$i]['id'] = $genr['id_genre'];
				$affich_el[$i]['name'] = $genr['titre_genre'];
				$affich_el[$i]['cover_url'] = $genr['cover_genre'];
				//$affich_el[$i]['cover_url'] = $genr['lien_genre']);

				$i++;

			}
			$gr->closeCursor();
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);



		}
		else if($_GET['table'] == 'event'){

			$re = "SELECT * FROM event";
			$ev = $bdd -> query($re);
			$nbev = $ev -> rowCount();
			//echo $re;
			$affich_el[0]=1;
			if($nbev == 0){

				$affich_el[$i]['id'] = 0;
				$affich_el[$i]['name'] = "Aucun evenement";
				$affich_el[$i]['image'] = "";
				$affich_el[$i]['date'] = "";
				$affich_el[$i]['address'] = "";
				$affich_el[$i]['phone'] = "";
				$affich_el[$i]['email'] = "";
				$affich_el[$i]['description'] = "";
			} else {

			while ($event = $ev->fetch() ){

				// $re1 = "SELECT * FROM telechargement WHERE id_album=".$genr['id_album'];
				// $nb = $bdd -> query($re1);
				// $nba = $nb->rowCount();
				//$j = 0;
				$affich_el[$i]['id'] = $event['id_event'];
				$affich_el[$i]['name'] = $event['nom_event'];
				$affich_el[$i]['image'] = $event['cover_event'];
				$affich_el[$i]['date'] = $event['date_event'];
				$affich_el[$i]['address'] = $event['lieu_event'];
				$affich_el[$i]['phone'] = $event['tel_event'];
				$affich_el[$i]['email'] = $event['mail_event'];
				$affich_el[$i]['description'] = $event['description_event'];
				//$affich_el[$i]['cover_url'] = $genr['lien_genre']);

				$i++;

			}
			$ev->closeCursor();
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);



		}


	}

  function listNAS(){
    // nouveau son et album
    require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');
		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];


            $curl = curl_init();
            $opts = [
                CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
                CURLOPT_RETURNTRANSFER => true,
              //  CURLOPT_HTTPHEADER => $headers,

            ];

            curl_setopt_array($curl, $opts);

            $response = json_decode(curl_exec($curl));
            $err = curl_error($curl);
            curl_close($curl);
       		 // curl_close($curl1);

       		 if ($err) {
       			  // echo "cURL Error #:" . $err;
              $countryCode = 'CI';
       			} else {
            // var_dump($response);
            $countryCode = $response -> countryCode;

            }
						$reqP = "SELECT * FROM pays
		  			 												INNER JOIN devise
		  															ON devise.id_devise = pays.id_devise
		  															WHERE code_pays ='".$countryCode."'";
		      	$pa = $bdd -> query($reqP);
		      	$pay = $pa->fetch();
			 			$pa->closeCursor();
			 	    $devise = $pay['signe_devise'];
						// $pa->closeCursor();
            // var_dump($pay);


            if(isset($pay['id_devise']) AND $pay['id_devise'] !== '1') {
                $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
                $ta = $bdd -> query($reqT);
                $taux = $ta->fetch();
                $nbtx = $ta->rowCount();
								// $ta->closeCursor();

                if ($nbtx !== 0) {
                  $tx = $taux['taux_taux'];
                }
              } else {
                $pay['id_devise'] = 1;
              }
            //var_dump($tx);
    }
    else {
      $pay['id_devise'] = 1;

    }
    // $reqD = "SELECT * FROM devise WHERE id_devise =".$pay['id_devise'];
    // $dv = $bdd -> query($reqD);
    // $dvse = $dv->fetch();
		// $dv->closeCursor();
    // $devise = $dvse['signe_devise'];

    $re = "SELECT * FROM son LEFT JOIN album
                    ON son.id_album = album.id_album
                    LEFT JOIN artiste
                    ON artiste.id_artiste = son.id_artiste
                    LEFT JOIN lyric
                    ON lyric.id_son = son.id_son
                    WHERE son.date_verif <= '".$dateAuj."' AND son.is_active = 1 ORDER BY son.dateSortie_son DESC, son.id_son ASC";
					// echo $re;
    $son = $bdd -> query($re);
    $nbson = $son -> rowCount();

    $reAlb = "SELECT * FROM album INNER JOIN artiste
                    ON artiste.id_artiste = album.id_artiste WHERE album.date_verif <= '".$dateAuj."' AND album.is_active = 1 ORDER BY album.dateSortie_album DESC, album.id_album DESC";

    // $al = $bdd -> query($reAlb);
    // $nba = $al -> rowCount();
		$nba=1000000000;

    $nbBcle = ($nbson < $nba) ? $nbson : $nba;
    $a = 0;
    $i = 1;
    $affich_el[0]=1;
		$idxpass='0';
    while ($a < $nbBcle AND $a <=15) {
      $sons = $son->fetch();
			// var_dump(is_int($sons['id_artiste']));
			if ($sons['id_artiste'] !== $idxpass) {
			// echo $idxpass."==".$sons['id_artiste']."<br>";
				if (isset($sons['0'])) {
					$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons['0']."-son'";
					$reqspre = $bdd -> query($re2);
					$reqsprec = $reqspre->fetch();

					$affich_el[$i]['id'] = $sons['0'];
					$affich_el[$i]['name'] = $sons['2'];
					$affich_el[$i]['artist'] = $sons['nom_artiste'];
					$affich_el[$i]['album'] = $sons['titre_album'];
					$affich_el[$i]['url'] = $sons['fichier_son'];
					// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
					$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el[$i]['cover_url'] = $sons['cover_son'];
					$affich_el[$i]['price'] = $sons['prix_son']*$tx;
					$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el[$i]['reference'] = $sons['reference_son'];
					$affich_el[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el[$i]['lien'] = $sons['lien_son'];
					$affich_el[$i]['devise'] = $devise;
					$affich_el[$i]['idArtist'] = $sons['id_artiste'];
					$affich_el[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 3000*$tx;
					$affich_el[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;

	        $i++;
					$idxpass = ($a%2 ==0) ? $sons['id_artiste'] : $idxpass;
					// echo 'nouveau :'.$idxpass."==".$sons['id_artiste']."<br>";
	        $a++;
	      }
      } else {
				$idxpass = $sons['id_artiste'];
      }

      // $alb = $al->fetch();
      // if (isset($alb['id_album'])) {
				// $affich_el[$i]['id'] = $alb['id_album'];
				// $affich_el[$i]['name'] = utf8_decode($alb['titre_album']);
				// $affich_el[$i]['artist'] = utf8_decode($alb['nom_artiste']);
				// $affich_el[$i]['cover_art_url'] = utf8_decode($alb['cover_artiste']);
				// $affich_el[$i]['cover_url'] = utf8_decode($alb['cover_album']);
				// $affich_el[$i]['url'] = utf8_decode($alb['url_album']);
				// $affich_el[$i]['price'] = $alb['prix_album']*$tx;
				// $affich_el[$i]['reference'] = $alb['reference_album'];
				// $affich_el[$i]['dateSortie'] = $alb['dateSortie_album'];
				// $affich_el[$i]['lien'] = $alb['lien_album'];
        // $affich_el[$i]['devise'] = $devise;

				// $i++;
      // }

    }
		$son->closeCursor();
		$reqspre->closeCursor();
    $affich_e = array_shift($affich_el);
    shuffle($affich_el);
    echo json_encode($affich_el);
    //echo json_last_error_msg();


  }

	function listAASfiltre(){
    // affichage son dalbum ou album et son dun artiste
		require 'connexionlist2.php';
		$i =0;
		$nbs =0;
		$nba =0;
		$nbt =0;
		$affich_el[] = '';
    $dateAuj = date ('Y-m-d H:i:s');
    $tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);
			$payss =(isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

			$reqP = "SELECT * FROM pays
															INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
			$pa = $bdd -> query($reqP);
			$pay = $pa->fetch();
			$devise = $pay['signe_devise'];
      $nbpay = $pa->rowCount();

      //var_dump($pay['id_devise']);
      if($nbpay !== 0 AND $pay['id_devise'] !== "1") {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
				// $ta->closeCursor();
        $tx = $taux['taux_taux'];
      }
      //var_dump($reqT);
    }
		$pa->closeCursor();

		if($_GET['table'] == 'son'){


      if(isset($_GET['alb']) AND $_GET['alb'] == 1){
        $re = "SELECT * FROM son LEFT JOIN album
  										  ON son.id_album = album.id_album
  										  INNER JOIN artiste
  										  ON artiste.id_artiste = son.id_artiste
  										  LEFT JOIN lyric
  										  ON lyric.id_son = son.id_son WHERE son.id_album = '".$_GET['indx']."' AND son.date_verif <= '".$dateAuj."' AND son.is_active = 1 ORDER BY son.dateSortie_son DESC, son.id_son ASC";

      } else if(isset($_GET['art']) AND $_GET['art'] == 1) {
        // $re2 = "SELECT * FROM son WHERE son.id_son = '".$_GET['indx']."'";
        // $son1 = $bdd -> query($re2);
        // $sons1 = $son1->fetch();

        $re = "SELECT * FROM son LEFT JOIN album
  										  ON son.id_album = album.id_album
  										  LEFT JOIN artiste
                        ON artiste.id_artiste = son.id_artiste
                        LEFT JOIN lyric
                        ON lyric.id_son = son.id_son WHERE son.id_artiste = '".$_GET['indx']."' AND son.date_verif <= '".$dateAuj."' AND son.is_active = 1 ORDER BY  son.dateSortie_son DESC, son.id_son ASC";

        $reA = "SELECT * FROM  album LEFT JOIN artiste
                        ON artiste.id_artiste = album.id_artiste
                        WHERE album.id_artiste = '".$_GET['indx']."' AND album.date_verif <= '".$dateAuj."' AND album.is_active = 1 ORDER BY album.dateSortie_album DESC, album.id_album DESC";
                  			$al = $bdd -> query($reA);
                  			$nba = $al -> rowCount();

                        $re3 =  "SELECT * FROM son LEFT JOIN album
                  										  ON son.id_album = album.id_album
                  										  LEFT JOIN artiste
                                        ON artiste.id_artiste = son.id_artiste
                                        LEFT JOIN lyric
                                        ON lyric.id_son = son.id_son WHERE son.id_artiste = '".$_GET['indx']."' AND son.date_verif <= '".$dateAuj."' AND son.is_active = 1 ORDER BY  son.dateSortie_son DESC, son.id_son DESC";
                        $son3 = $bdd -> query($re3);
                        $nbson3 = $son3 -> rowCount();
                        if($nbson3 !== 0){
                          $sons3 = $son3->fetch();
                          //var_dump($sons3);
                          $re4 =  "SELECT DISTINCT(son.id_artiste), artiste.nom_artiste, artiste.cover_artiste, artiste.lien_artiste, artiste.biographie_artiste, son.date_verif, son.is_active FROM son
                                          LEFT JOIN artiste
                    										  ON artiste.id_artiste = son.id_artiste
                                          WHERE son.id_genre = '".$sons3[13]."' AND artiste.id_artiste !='".$_GET['indx']."'AND son.date_verif <= '".$dateAuj."' AND son.is_active = 1 ORDER BY rand()";


                          $art = $bdd -> query($re4);
                           $nbart = $art -> rowCount();

                          if ($nbart == 0) {
                            $re4 =  "SELECT DISTINCT(son.id_artiste), artiste.nom_artiste, artiste.cover_artiste, artiste.lien_artiste, artiste.biographie_artiste, son.date_verif, son.is_active FROM son
                                            LEFT JOIN artiste
                                            ON artiste.id_artiste = son.id_artiste
                                            WHERE artiste.id_artiste !='".$_GET['indx']."' AND son.date_verif <= '".$dateAuj."' AND son.is_active = 1 ORDER BY rand()";
                                            $art = $bdd -> query($re4);
                          }

                          $i=0;
                          while ($artist = $art->fetch() AND $i < 5){
                            //var_dump ($sons);



                            $affich_el['artistes'][$i]['id'] = $artist[0];
                    				$affich_el['artistes'][$i]['name'] = $artist['nom_artiste'];
                    				$affich_el['artistes'][$i]['cover_url'] = $artist['cover_artiste'];
                    				//$affich_el['artistes'][$i]['bio'] = utf8_decode($artist['biographie_artiste']);
                    				$affich_el['artistes'][$i]['lien'] = $artist['lien_artiste'];

                            $i++;

                          }
													$art->closeCursor();

                        } else {

                            $re4 =  "SELECT DISTINCT(son.id_artiste), artiste.nom_artiste, artiste.cover_artiste, artiste.lien_artiste, artiste.biographie_artiste, son.date_verif,son.is_active FROM son
                                            LEFT JOIN artiste
                                            ON artiste.id_artiste = son.id_artiste
                                            WHERE artiste.id_artiste !='".$_GET['indx']."' AND son.date_verif <= '".$dateAuj."' AND son.is_active = 1 ORDER BY rand()";
                                            $art = $bdd -> query($re4);
                            $i=0;
                            while ($artist = $art->fetch() AND $i < 5){
                              //var_dump ($sons);



                              $affich_el['artistes'][$i]['id'] = $artist[0];
                      				$affich_el['artistes'][$i]['name'] = $artist['nom_artiste'];
                      				$affich_el['artistes'][$i]['cover_url'] = $artist['cover_artiste'];
                      				//$affich_el['artistes'][$i]['bio'] = utf8_decode($artist['biographie_artiste']);
                      				$affich_el['artistes'][$i]['lien'] = $artist['lien_artiste'];

                              $i++;

                            }
														$art->closeCursor();

                        }


      } else {
        $re2 = "SELECT * FROM son WHERE son.id_son = '".$_GET['indx']."' AND son.date_verif <= '".$dateAuj."' AND son.is_active = 1 ";
        $son1 = $bdd -> query($re2);
        $sons1 = $son1->fetch();

        $re = "SELECT * FROM son LEFT JOIN album
  										  ON son.id_album = album.id_album
  										  LEFT JOIN artiste
                        ON artiste.id_artiste = son.id_artiste
                        LEFT JOIN lyric
                        ON lyric.id_son = son.id_son WHERE son.id_artiste = '".$sons1['id_artiste']."' AND son.date_verif <= '".$dateAuj."' AND son.is_active = 1";


      }
      //echo $reA.'<br>';
			$son = $bdd -> query($re);
			$nbs = $son -> rowCount();
			//echo $nba."-".$nbs;
      $nbt = $nba+$nbs;
			$affich_el[0]=1;



			if($nbt == 0){

            $affich_el['asons']=0;
            if(isset($_GET['art'])){
                //$affich_el['asons']=0;
                $affich_el['albums']=0;
              // $reA = "SELECT * FROM album INNER JOIN artiste
        			// 							  ON artiste.id_artiste = album.id_artiste ORDER BY album.titre_album";
        			// $al = $bdd -> query($reA);
        			// $nba = $al -> rowCount();
        			// //echo $re;
        			// $affich_el[0]=1;
              //
              //
        			// while ($alb = $al->fetch() ){
              //
        			// 	$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
        			// 	$nb = $bdd -> query($re1);
        			// 	$nbd = $nb->rowCount();
        			// 	//$j = 0;
          		// 	//var_dump($alb);
        			// 	$affich_el['albums'][$i]['id'] = $alb[0];
        			// 	$affich_el['albums'][$i]['name'] = utf8_decode($alb['titre_album']);
        			// 	$affich_el['albums'][$i]['artist'] = utf8_decode($alb['nom_artiste']);
        			// 	$affich_el['albums'][$i]['cover_art_url'] = utf8_decode($alb['cover_artiste']);
        			// 	$affich_el['albums'][$i]['cover_url'] = utf8_decode($alb['cover_album']);
        			// 	$affich_el['albums'][$i]['url'] = utf8_decode($alb['url_album']);
        			// 	$affich_el['albums'][$i]['price'] = $alb['prix_album']*$tx;
        			// 	$affich_el['albums'][$i]['downloads'] = $nbd;
        			// 	$affich_el['albums'][$i]['reference'] = $alb['reference_album'];
        			// 	$affich_el['albums'][$i]['lien'] = $alb['lien_album'];
              //
        			// 	$i++;
              //
        			// }
            }
			}
			else if($nba == 0) {
				$idxSn = 0;
        while ($sons = $son->fetch() ){
  				//var_dump ($sons);
					$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
					$reqspre = $bdd -> query($re2);
					$reqsprec = $reqspre->fetch();

  				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
  				$nb = $bdd -> query($re1);
  				$nbd = $nb->rowCount();
  				//$j = 0;
  				$affich_el['asons'][$idxSn]['id'] = $sons[0];
  				$affich_el['asons'][$idxSn]['name'] = $sons['titre_son'];
  				$affich_el['asons'][$idxSn]['artist'] = $sons['nom_artiste'];
					$affich_el['asons'][$idxSn]['idArtist'] = $sons['id_artiste'];
  				$affich_el['asons'][$idxSn]['album'] = $sons['titre_album'];
  				$affich_el['asons'][$idxSn]['url'] = $sons['fichier_son'];
  				$affich_el['asons'][$idxSn]['cover_art_url'] = $sons['cover_artiste'];
  				$affich_el['asons'][$idxSn]['cover_url'] = $sons['cover_son'];
  				$affich_el['asons'][$idxSn]['price'] = $sons['prix_son']*$tx;
  				$affich_el['asons'][$idxSn]['downloads'] = $nbd;
  				$affich_el['asons'][$idxSn]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
  				$affich_el['asons'][$idxSn]['reference'] = $sons['reference_son'];
  				$affich_el['asons'][$idxSn]['lien'] = $sons['lien_son'];
  				$affich_el['asons'][$idxSn]['f'] = $sons['fichier_son'];
  				$affich_el['asons'][$idxSn]['devise'] = $devise;
					$affich_el['asons'][$idxSn]['soutientmini'] = isset($reqsprec['minim_soutCommande']) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
					$affich_el['asons'][$idxSn]['precommande'] = isset($reqsprec['type_soutCommande']) ? $reqsprec['type_soutCommande']*$tx : 0;

  				$idxSn++;

  			}
				$son->closeCursor();
        if(isset($_GET['art'])){
            $affich_el['albums']=0;
          // $reA = "SELECT * FROM album INNER JOIN artiste
          //                 ON artiste.id_artiste = album.id_artiste ORDER BY album.titre_album";
          // $al = $bdd -> query($reA);
          // $nba = $al -> rowCount();
          // //echo $re;
          // $affich_el[0]=1;
          //
          //
          // while ($alb = $al->fetch() ){
          //
          //   $re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
          //   $nb = $bdd -> query($re1);
          //   $nbd = $nb->rowCount();
          //   //$j = 0;
          //   //var_dump($alb);
          //   $affich_el['albums'][$i]['id'] = $alb[0];
          //   $affich_el['albums'][$i]['name'] = $alb['titre_album']);
          //   $affich_el['albums'][$i]['artist'] = utf8_decode($alb['nom_artiste']);
          //   $affich_el['albums'][$i]['cover_art_url'] = utf8_decode($alb['cover_artiste']);
          //   $affich_el['albums'][$i]['cover_url'] = utf8_decode($alb['cover_album']);
          //   $affich_el['albums'][$i]['url'] = utf8_decode($alb['url_album']);
          //   $affich_el['albums'][$i]['price'] = $alb['prix_album']*$tx;
          //   $affich_el['albums'][$i]['downloads'] = $nbd;
          //   $affich_el['albums'][$i]['reference'] = $alb['reference_album'];
          //   $affich_el['albums'][$i]['lien'] = $alb['lien_album'];
          //
          //   $i++;
          //
          // }
        }

      }
      else if ($nbs == 0) {
          $affich_el['asons']=0;
          if(isset($_GET['art'])){
            $reA = "SELECT * FROM album INNER JOIN artiste
                            ON artiste.id_artiste = album.id_artiste WHERE  album.id_artiste = '".$_GET['indx']."' AND album.date_verif <= '".$dateAuj."' AND album.is_active = 1 ORDER BY album.titre_album";
            $al = $bdd -> query($reA);
            $nba = $al -> rowCount();
            // $reA2 = "SELECT * FROM album INNER JOIN artiste
            //                 ON artiste.id_artiste = album.id_artiste ORDER BY album.titre_album";
            // $al2 = $bdd -> query($reA2);
            // $nba2 = $al2 -> rowCount();
            //echo "<br>".$reA;
            $affich_el[0]=1;


            // while ($alb = $al->fetch() ){
            //
            //   $re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
            //   $nb = $bdd -> query($re1);
            //   $nbd = $nb->rowCount();
            //   //$j = 0;
            //   //var_dump($alb);
            //   $affich_el['asons'][$i]['id'] = $alb[0];
            //   $affich_el['asons'][$i]['name'] = utf8_decode($alb['titre_album']);
            //   $affich_el['asons'][$i]['artist'] = utf8_decode($alb['nom_artiste']);
            //   $affich_el['asons'][$i]['cover_art_url'] = utf8_decode($alb['cover_artiste']);
            //   $affich_el['asons'][$i]['cover_url'] = $alb['cover_album'];
            //   $affich_el['asons'][$i]['url'] = utf8_decode($alb['url_album']);
            //   $affich_el['asons'][$i]['price'] = $alb['prix_album']*$tx;
            //   $affich_el['asons'][$i]['downloads'] = $nbd;
            //   $affich_el['asons'][$i]['reference'] = $alb['reference_album'];
            //   $affich_el['asons'][$i]['lien'] = $alb['lien_album'];
            //
            //   $i++;
            //
            // }
						$idxAlb = 0;
            while ($alb2 = $al->fetch() ){

							$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb2['id_album']."-album'";
							$reqspre = $bdd -> query($re2);
							$reqsprec = $reqspre->fetch();

              $re12 = "SELECT * FROM telechargement WHERE id_album=".$alb2['id_album'];
              $nb2 = $bdd -> query($re12);
              $nbd2 = $nb2->rowCount();
              //$j = 0;
              // var_dump($alb2);
              $affich_el['albums'][$idxAlb]['id'] = $alb2[0];
              $affich_el['albums'][$idxAlb]['name'] = $alb2['titre_album'];
              $affich_el['albums'][$idxAlb]['artist'] = $alb2['nom_artiste'];
              $affich_el['albums'][$idxAlb]['cover_art_url'] = $alb2['cover_artiste'];
              $affich_el['albums'][$idxAlb]['cover_url'] = $alb2['cover_album'];
              $affich_el['albums'][$idxAlb]['url'] = $alb2['url_album'];
              $affich_el['albums'][$idxAlb]['price'] = $alb2['prix_album']*$tx;
              $affich_el['albums'][$idxAlb]['downloads'] = $nbd2;
              $affich_el['albums'][$idxAlb]['reference'] = $alb2['reference_album'];
              $affich_el['albums'][$idxAlb]['lien'] = $alb2['lien_album'];
              $affich_el['albums'][$idxAlb]['devise'] = $devise;
							$affich_el['albums'][$idxAlb]['soutientmini'] = $reqsprec['minim_soutCommande']*$tx;
							$affich_el['albums'][$idxAlb]['precommande'] = $reqsprec['type_soutCommande']*$tx;

              $idxAlb++;

            }
						$al->closeCursor();
          }
      }
      else {
			$o = 0;
			while ($sons = $son->fetch() ){
        //echo "son <br>";
				//var_dump ($sons);

				$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
				$reqspre = $bdd -> query($re2);
				$reqsprec = $reqspre->fetch();

				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
				$nb = $bdd -> query($re1);
				$nbs = $nb->rowCount();
				//$j = 0;
				$affich_el['asons'][$o]['id'] = $sons[0];
				$affich_el['asons'][$o]['name'] = $sons['titre_son'];
				$affich_el['asons'][$o]['artist'] = $sons['nom_artiste'];
				$affich_el['asons'][$o]['idArtist'] = $sons['id_artiste'];
				$affich_el['asons'][$o]['album'] = $sons['titre_album'];
				$affich_el['asons'][$o]['url'] = $sons['fichier_son'];
				$affich_el['asons'][$o]['cover_art_url'] = $sons['cover_artiste'];
				$affich_el['asons'][$o]['cover_url'] = $sons['cover_son'];
				$affich_el['asons'][$o]['price'] = $sons['prix_son']*$tx;
				$affich_el['asons'][$o]['downloads'] = $nbs;
				$affich_el['asons'][$o]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
				$affich_el['asons'][$o]['reference'] = $sons['reference_son'];
				$affich_el['asons'][$o]['lien'] = $sons['lien_son'];
				$affich_el['asons'][$o]['devise'] = $devise;
				$affich_el['asons'][$o]['soutientmini'] = isset($reqsprec['minim_soutCommande']) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
				$affich_el['asons'][$o]['precommande'] = isset($reqsprec['type_soutCommande']) ? $reqsprec['type_soutCommande']*$tx : 0;

				$o++;

			}
      if(isset($_GET['art'])){
        // $reA = "SELECT * FROM  album LEFT JOIN artiste
        //                 ON artiste.id_artiste = album.id_artiste
        //                 WHERE album.id_artiste = '".$_GET['indx']."'";
        //           			$al = $bdd -> query($reA);
        $reA = "SELECT * FROM album INNER JOIN artiste
                        ON artiste.id_artiste = album.id_artiste WHERE album.id_artiste = '".$_GET['indx']."' AND album.date_verif <= '".$dateAuj."' AND album.is_active = 1  ORDER BY album.titre_album";
        $al = $bdd -> query($reA);
        $nba = $al -> rowCount();
        // $reA2 = "SELECT * FROM album INNER JOIN artiste
        //                 ON artiste.id_artiste = album.id_artiste ORDER BY album.titre_album";
        // $al2 = $bdd -> query($reA2);
        // $nba2 = $al2 -> rowCount();


        //echo $reA;
        $affich_el[0]=1;


        // while ($alb = $al->fetch() ){
        //
        //   $re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
        //   $nb = $bdd -> query($re1);
        //   $nbd = $nb->rowCount();
        //   //$j = 0;
        //   //var_dump($alb);
        //   $affich_el['asons'][$i]['id'] = $alb[0];
        //   $affich_el['asons'][$i]['name'] = $alb['titre_album']);
        //   $affich_el['asons'][$i]['artist'] = utf8_decode($alb['nom_artiste']);
        //   $affich_el['asons'][$i]['cover_art_url'] = utf8_decode($alb['cover_artiste']);
        //   $affich_el['asons'][$i]['cover_url'] = $alb['cover_album'];
        //   $affich_el['asons'][$i]['url'] = utf8_decode($alb['url_album']);
        //   $affich_el['asons'][$i]['price'] = $alb['prix_album']*$tx;
        //   $affich_el['asons'][$i]['downloads'] = $nbd;
        //   $affich_el['asons'][$i]['reference'] = $alb['reference_album'];
        //   $affich_el['asons'][$i]['lien'] = $alb['lien_album'];
        //
        //   $i++;
        //
        // }
				$o = 0;
        while ($alb2 = $al->fetch() ){
					$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb2['id_album']."-album'";
					$reqspre = $bdd -> query($re2);
					$reqsprec = $reqspre->fetch();

          $re12 = "SELECT * FROM telechargement WHERE id_album=".$alb2['id_album'];
          $nb2 = $bdd -> query($re12);
          $nbd2 = $nb2->rowCount();
          //$j = 0;
          //echo "album <br>";

          // var_dump($alb2);
          $affich_el['albums'][$o]['id'] = $alb2[0];
          $affich_el['albums'][$o]['name'] = $alb2['titre_album'];
          $affich_el['albums'][$o]['artist'] = $alb2['nom_artiste'];
          $affich_el['albums'][$o]['idArtist'] = $alb2['id_artiste'];
          $affich_el['albums'][$o]['cover_art_url'] = $alb2['cover_artiste'];
          $affich_el['albums'][$o]['cover_url'] = $alb2['cover_album'];
          $affich_el['albums'][$o]['url'] = $alb2['url_album'];
          $affich_el['albums'][$o]['price'] = $alb2['prix_album']*$tx;
          $affich_el['albums'][$o]['downloads'] = $nbd2;
          $affich_el['albums'][$o]['reference'] = $alb2['reference_album'];
          $affich_el['albums'][$o]['lien'] = $alb2['lien_album'];
          $affich_el['albums'][$o]['devise'] = $devise;
					$affich_el['albums'][$o]['soutientmini'] = $reqsprec['minim_soutCommande']*$tx;
					$affich_el['albums'][$o]['precommande'] = $reqsprec['type_soutCommande']*$tx;

          $o++;

        }
				$al->closeCursor();
				$reqspre->closeCursor();
      }
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);
      //echo json_last_error_msg();


	   }
     else if($_GET['table'] == 'ticket'){

 			$re = "SELECT * FROM ticket WHERE id_event = '".$_GET['indx']."'";
 			$tic = $bdd -> query($re);
 			$nbtic = $tic -> rowCount();
 			//echo $re;
 			$affich_el['0']=1;
 			if($nbtic == 0){

 				$affich_el[$i]['id'] = 0;
 				$affich_el[$i]['name'] = "Aucun ticket";
 				$affich_el[$i]['image'] = "";
 				$affich_el[$i]['price'] = "";
 			} else {
        $i =1;
 			while ($tick = $tic->fetch() ){

 				// $re1 = "SELECT * FROM telechargement WHERE id_album=".$genr['id_album'];
 				// $nb = $bdd -> query($re1);
 				// $nba = $nb->rowCount();
 				//$j = 0;
 				$affich_el[$i]['id'] = $tick['id_ticket'];
 				$affich_el[$i]['name'] = $tick['nom_ticket'];
 				$affich_el[$i]['image'] = $tick['image_ticket'];
 				$affich_el[$i]['price'] = $tick['prix_ticket'];

 				$i++;

 			}
			$tic->closeCursor();
 			}
 			$affich_e = array_shift($affich_el);
 			//var_dump($affich_e);
 			//var_dump($affich_el);
 			echo json_encode($affich_el);

 		}
     else if($_GET['table'] == 'event'){

 			$re = "SELECT * FROM event WHERE id_event = '".$_GET['indx']."'";
 			$tic = $bdd -> query($re);
 			$nbtic = $tic -> rowCount();
 			$event = $tic -> fetch();
			$tic->closeCursor();
 			//echo $re;
 			$affich_el[0]=1;
 			if($nbtic == 0){

 				$affich_el['id'] = 0;
 				$affich_el['name'] = "Aucun evenement";
 				$affich_el['image'] = "";
 				$affich_el['price'] = "";
 			} else {

        //var_dump($event);

        $affich_el['id'] = $event['id_event'];
				$affich_el['name'] = $event['nom_event'];
				$affich_el['image'] = $event['cover_event'];
				$affich_el['date'] = $event['date_event'];
				$affich_el['address'] = $event['lieu_event'];
				$affich_el['phone'] = $event['tel_event'];
				$affich_el['email'] = $event['mail_event'];
				$affich_el['description'] = $event['description_event'];



 			}
 			$affich_e = array_shift($affich_el);
 			//var_dump($affich_e);
 			//var_dump($affich_el);
 			echo json_encode($affich_el);

 		} else if($_GET['table'] == 'artiste'){

			$re = "SELECT * FROM artiste WHERE artiste.is_active = 1 ORDER BY nom_artiste";
			$ar = $bdd -> query($re);
			$nbar = $ar -> rowCount();
			//echo $re;
			$i = 0;
			$affich_el[0]=1;
			if($nbar == 0){
				$affich_el[$i]['id'] = 0;
				$affich_el[$i]['name'] = "Aucun artiste";
				$affich_el[$i]['dob'] = "";
				$affich_el[$i]['cover_url'] = "";
				$affich_el[$i]['bio'] = "0";
			} else {

			while ($artis = $ar->fetch() ){

				//$j = 0;
				$affich_el[$i]['id'] = $artis[0];
				$affich_el[$i]['name'] = $artis['nom_artiste'];
				$affich_el[$i]['dob'] = $artis['dob_artiste'];
				$affich_el[$i]['cover_url'] = $artis['cover_artiste'];
				$affich_el[$i]['bio'] = $artis['biographie_artiste'];
				$affich_el[$i]['lien'] = $artis['lien_artiste'];

				$i++;

			}
			$ar->closeCursor();
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);

		}
		else if($_GET['table'] == 'album'){

			$re = "SELECT * FROM album INNER JOIN artiste
										  ON artiste.id_artiste = album.id_artiste WHERE album.date_verif <= '".$dateAuj."' AND album.is_active = 1 ORDER BY album.titre_album";
			$al = $bdd -> query($re);
			$nba = $al -> rowCount();
			//echo $re;
			$i=0;
			$affich_el[0]=1;
			if($nba == 0){
				$affich_el[$i]['id'] = 0;
				$affich_el[$i]['name'] = "Aucun son";
				$affich_el[$i]['artist'] = "Aucun";
				$affich_el[$i]['cover_art_url'] = "";
				$affich_el[$i]['cover_url'] = "";
				$affich_el[$i]['url'] = "";
				$affich_el[$i]['price'] = "0";
				$affich_el[$i]['downloads'] = "0";
				$affich_el[$i]['reference'] = "";
				$affich_el[$i]['lien'] = "";
			} else {

			while ($alb = $al->fetch() ){
				$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
				$reqspre = $bdd -> query($re2);
				$reqsprec = $reqspre->fetch();

				$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
				$nb = $bdd -> query($re1);
				$nba = $nb->rowCount();
				//$j = 0;
  			//var_dump($alb);
				$affich_el[$i]['id'] = $alb[0];
				$affich_el[$i]['name'] = $alb['titre_album'];
				$affich_el[$i]['artist'] = $alb['nom_artiste'];
				$affich_el[$i]['idArtist'] = $alb['id_artiste'];
				$affich_el[$i]['cover_art_url'] = $alb['cover_artiste'];
				$affich_el[$i]['cover_url'] = $alb['cover_album'];
				$affich_el[$i]['url'] = $alb['url_album'];
				$affich_el[$i]['price'] = $alb['prix_album']*$tx;
				$affich_el[$i]['downloads'] = $nba;
				$affich_el[$i]['reference'] = $alb['reference_album'];
				$affich_el[$i]['lien'] = $alb['lien_album'];
				$affich_el[$i]['devise'] = $devise;
				$affich_el[$i]['soutientmini'] = $reqsprec['minim_soutCommande']*$tx;
				$affich_el[$i]['precommande'] = $reqsprec['type_soutCommande']*$tx;

				$i++;

			}
			$al->closeCursor();
			$reqspre->closeCursor();
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);



		}
		 else if($_GET['table'] == 'genre'){

			$re = "SELECT * FROM genre ";
			$gr = $bdd -> query($re);
			$nbg = $gr -> rowCount();
			//echo $re;
			$i=0;
			$affich_el[0]=1;
			if($nbg == 0){
				$affich_el[$i]['id'] = 0;
				$affich_el[$i]['name'] = "Aucun son";
				$affich_el[$i]['cover_url'] = "";
			} else {

			while ($genr = $gr->fetch() ){

				// $re1 = "SELECT * FROM telechargement WHERE id_album=".$genr['id_album'];
				// $nb = $bdd -> query($re1);
				// $nba = $nb->rowCount();
				//$j = 0;
				$affich_el[$i]['id'] = $genr[0];
				$affich_el[$i]['name'] = $genr['titre_genre'];
				$affich_el[$i]['cover_url'] = $genr['cover_genre'];

				$i++;

			}
			$gr->closeCursor();
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);



		}
	}

	function listPT(){
    // affichage au telechargement
		require 'connexion.php';
		$i =2;
		$affich_el[] = '';

		$ddj = date ('Y-m-d H:i:s');

    $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_POST['ref']."' AND statut_transaction = 'SUCCESS'";
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
		$elt = $un->fetch();
		// $un->closeCursor();

    $lib = explode('-', $elt['libelle_transaction']);
    //$affich_el[1]=$lib[1];
    $affich_el[0]=1;
    //  var_dump($lib);
    $tail = sizeof($lib);

  	if (end($lib) == "don") {
  		$id = $lib[$tail-2];
  	} else {
  		$id = end($lib);
  	}
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
				// echo $ip."=".$elt['ip_transaction'];

    if($ip == $elt['ip_transaction']) {


    if (end($lib) !== "don") {
  		if($lib[0] == 'son'){

  			$re = "SELECT * FROM son LEFT JOIN album
  										  ON son.id_album = album.id_album
  										  LEFT JOIN artiste
  										  ON artiste.id_artiste = son.id_artiste
  										  LEFT JOIN lyric
  										  ON lyric.id_son = son.id_son
                        WHERE son.id_son = ".$id;
  			$son = $bdd -> query($re);
  			$nbson = $son -> rowCount();
  			//echo $re;

  			$sons = $son->fetch();
  				//var_dump ($sons);

				$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
				$reqspre = $bdd -> query($re2);
				$reqsprec = $reqspre->fetch();

					$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
					$nb = $bdd -> query($re1);
					$nbs = $nb->rowCount();
					//$j = 0;

					//$fichier = ($reqsprec['type_soutCommande'] !== 0 AND $sons['dateSortie_son'] >= $ddj) ? $reqsprec['fichierpre_soutCommande'] : $sons['fichier_son'];
					$fichier = $sons['fichier_son'];

					$affich_el[$i]['id'] = $sons['0'];
					$affich_el[$i]['name'] = $sons['titre_son'];
					$affich_el[$i]['artist'] = $sons['nom_artiste'];
					$affich_el[$i]['album'] = $sons['titre_album'];
					$affich_el[$i]['url'] = $sons['url_son'];
					$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el[$i]['cover_url'] = $sons['cover_son'];
					$affich_el[$i]['price'] = $sons['prix_son'];
					// $affich_el[$i]['f'] = $sons['fichier_son'];
					$affich_el[$i]['f'] = $fichier;
					$affich_el[$i]['downloads'] = $nbs;
					$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el[$i]['reference'] = $sons['reference_son'];

					$i++;



	  			$affich_e = array_shift($affich_el);
	  			//var_dump($affich_e);
	  			//var_dump($affich_el);
	  		  //$affich_el = convert_from_latin1_to_utf8_recursively($affich_el);
	  			echo json_encode($affich_el);
	        //echo json_last_error_msg();



  	   }
  		else if($lib[0] == 'album'){

			$re = "SELECT * FROM album INNER JOIN artiste
										  ON artiste.id_artiste = album.id_artiste
                      WHERE album.id_album = ".$id;
			$al = $bdd -> query($re);
			$nba = $al -> rowCount();
			//echo $re;


			$alb = $al->fetch();
			$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
			$reqspre = $bdd -> query($re2);
			$reqsprec = $reqspre->fetch();

			$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
			$nb = $bdd -> query($re1);
			$nba = $nb->rowCount();
				//$j = 0;
  			//var_dump($alb);

				// $fichier = ($reqsprec['type_soutCommande'] !== 0 AND $alb['dateSortie_album'] > $ddj) ? $reqsprec['fichierpre_soutCommande'] : $alb['fichier_album'];
				$fichier = $alb['fichier_album'];

				$affich_el[$i]['id'] = $alb['id_album'];
				$affich_el[$i]['name'] = $alb['titre_album']." (l'album entier)";
				$affich_el[$i]['artist'] = $alb['nom_artiste'];
				$affich_el[$i]['cover_art_url'] = $alb['cover_artiste'];
				$affich_el[$i]['cover_url'] = $alb['cover_album'];
				$affich_el[$i]['url'] = $alb['url_album'];
				$affich_el[$i]['f'] = $fichier;
				$affich_el[$i]['price'] = $alb['prix_album'];
				$affich_el[$i]['downloads'] = $nba;
				$affich_el[$i]['reference'] = $alb['reference_album'];

				$i++;

					$re = "SELECT * FROM son INNER JOIN album
	  										  ON son.id_album = album.id_album
	  										  LEFT JOIN artiste
	  										  ON artiste.id_artiste = son.id_artiste
	  										  LEFT JOIN lyric
	  										  ON lyric.id_son = son.id_son
	                        WHERE album.id_album = ".$id;
	  			$son = $bdd -> query($re);
	        while ($sons = $son->fetch() ){
	  				//var_dump ($sons);


	  				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
	  				$nb = $bdd -> query($re1);
	  				$nbs = $nb->rowCount();
	  				//$j = 0;
	  				$affich_el[$i]['id'] = $sons['0'];
	  				$affich_el[$i]['name'] = $sons['titre_son'];
	  				$affich_el[$i]['artist'] = $sons['nom_artiste'];
	  				$affich_el[$i]['album'] = $sons['titre_album'];
	  				$affich_el[$i]['url'] = $sons['url_son'];
	  				$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
	  				$affich_el[$i]['cover_url'] = $sons['cover_son'];
	  				$affich_el[$i]['price'] = $sons['prix_son'];
	  				$affich_el[$i]['f'] = $sons['fichier_son'];
	  				$affich_el[$i]['downloads'] = $nbs;
	  				$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
	  				$affich_el[$i]['reference'] = $sons['reference_son'];

	  				$i++;



  			}


				$affich_e = array_shift($affich_el);
				//var_dump($affich_e);
				//var_dump($affich_el);
				echo json_encode($affich_el);




		}
    }
    else {

      $i=0;
      if ($lib[0] == 'son') {

				$re = "SELECT * FROM son LEFT JOIN album
                        ON son.id_album = album.id_album
                        LEFT JOIN artiste
                        ON artiste.id_artiste = son.id_artiste
                        LEFT JOIN lyric
                        ON lyric.id_son = son.id_son
                        WHERE artiste.id_artiste = ".$id."
												ORDER BY son.id_son DESC";
        $son = $bdd -> query($re);
        $nbson = $son -> rowCount();
        // echo $re;


        if ($id == 109 or $id == 111) {
          while ($sons = $son->fetch()) {
            // var_dump ($sons);
						$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
						$reqspre = $bdd -> query($re2);
						$reqsprec = $reqspre->fetch();
						if (isset($reqsprec['id_soutCommande']) AND $reqsprec['fichier_soutCommande'] !== 0) {


							$fichier = ($reqsprec['type_soutCommande'] == 0 OR $sons['dateSortie_son'] <= $ddj) ? $reqsprec['fichier_soutCommande'] : $reqsprec['fichierpre_soutCommande'];


	            $affich_el['s'][$i]['id'] = $sons['0'];
	            $affich_el['s'][$i]['name'] = $sons['titre_son'];
	            $affich_el['s'][$i]['artist'] = $sons['nom_artiste'];
	            $affich_el['s'][$i]['album'] = $sons['titre_album'];
	            $affich_el['s'][$i]['url'] = $sons['url_son'];
	            $affich_el['s'][$i]['cover_art_url'] = $sons['cover_artiste'];
	            $affich_el['s'][$i]['cover_url'] = $sons['cover_son'];
	            $affich_el['s'][$i]['price'] = $sons['prix_son'];
	            $affich_el['s'][$i]['f'] = $fichier;
	            $affich_el['s'][$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
	            $affich_el['s'][$i]['reference'] = $sons['reference_son'];

	            $i++;
						}
          }
					$son->closeCursor();
					$reqspre->closeCursor();

        } else {
          $sons = $son->fetch();
            //var_dump ($sons);

						$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
						$reqspre = $bdd -> query($re2);
						$reqsprec = $reqspre->fetch();
						$fichier = ($reqsprec['type_soutCommande'] == 0 OR $sons['dateSortie_son'] <= $ddj) ? $reqsprec['fichier_soutCommande'] : $reqsprec['fichierpre_soutCommande'];

            //$j = 0;
            $affich_el['s'][$i]['id'] = $sons['0'];
            $affich_el['s'][$i]['name'] = $sons['titre_son'];
            $affich_el['s'][$i]['artist'] = $sons['nom_artiste'];
            $affich_el['s'][$i]['album'] = $sons['titre_album'];
            $affich_el['s'][$i]['url'] = $sons['url_son'];
            $affich_el['s'][$i]['cover_art_url'] = $sons['cover_artiste'];
            $affich_el['s'][$i]['cover_url'] = $sons['cover_son'];
            $affich_el['s'][$i]['price'] = $sons['prix_son'];
            $affich_el['s'][$i]['f'] = $fichier;
            $affich_el['s'][$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
            $affich_el['s'][$i]['reference'] = $sons['reference_son'];

            $i++;
        }
      } else if($lib[0] == 'album') {
        $re = "SELECT * FROM album INNER JOIN artiste
  										  ON artiste.id_artiste = album.id_artiste
                        WHERE album.id_artiste = ".$id."
												ORDER BY album.id_album DESC";
  			$al = $bdd -> query($re);
  			$nba = $al -> rowCount();
  			//echo $re;

					$alb = $al->fetch();

					$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
					$reqspre = $bdd -> query($re2);
					$reqsprec = $reqspre->fetch();
					if (isset($reqsprec['id_soutCommande']) AND $reqsprec['fichier_soutCommande'] !== 0) {

					$fichier = ($reqsprec['type_soutCommande'] == 0 OR $alb['dateSortie_album'] <= $ddj) ? $reqsprec['fichier_soutCommande'] : $reqsprec['fichierpre_soutCommande'];
	  				//$j = 0;
	    			//var_dump($alb);
	  				$affich_el["s"][$i]['id'] = $alb['id_album'];
	  				$affich_el["s"][$i]['name'] = $alb['titre_album']." (l'album entier)";
	  				$affich_el["s"][$i]['artist'] = $alb['nom_artiste'];
	  				$affich_el["s"][$i]['cover_art_url'] = $alb['cover_artiste'];
	  				$affich_el["s"][$i]['cover_url'] = $alb['cover_album'];
	  				$affich_el["s"][$i]['url'] = $alb['url_album'];
	  				$affich_el["s"][$i]['f'] = $alb['fichier_album'];
	  				$affich_el["s"][$i]['price'] = $alb['prix_album'];
	  				$affich_el["s"][$i]['reference'] = $alb['reference_album'];
						$i++;

							$re = "SELECT * FROM son INNER JOIN album
			  										  ON son.id_album = album.id_album
			  										  LEFT JOIN artiste
			  										  ON artiste.id_artiste = son.id_artiste
			  										  LEFT JOIN lyric
			  										  ON lyric.id_son = son.id_son
			                        WHERE album.id_album = ".$id;
			  			$son = $bdd -> query($re);
			        while ($sons = $son->fetch() ){
			  				//var_dump ($sons);


			  				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
			  				$nb = $bdd -> query($re1);
			  				$nbs = $nb->rowCount();
			  				//$j = 0;
			  				$affich_el["s"][$i]['id'] = $sons['0'];
			  				$affich_el["s"][$i]['name'] = $sons['titre_son'];
			  				$affich_el["s"][$i]['artist'] = $sons['nom_artiste'];
			  				$affich_el["s"][$i]['album'] = $sons['titre_album'];
			  				$affich_el["s"][$i]['url'] = $sons['url_son'];
			  				$affich_el["s"][$i]['cover_art_url'] = $sons['cover_artiste'];
			  				$affich_el["s"][$i]['cover_url'] = $sons['cover_son'];
			  				$affich_el["s"][$i]['price'] = $sons['prix_son'];
			  				$affich_el["s"][$i]['f'] = $sons['fichier_son'];
			  				$affich_el["s"][$i]['downloads'] = $nbs;
			  				$affich_el["s"][$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
			  				$affich_el["s"][$i]['reference'] = $sons['reference_son'];

			  				$i++;
							}
							$son->closeCursor();

		  			}



      } else if ($lib[0] == 'artiste') {
				$re = "SELECT * FROM son LEFT JOIN album
                        ON son.id_album = album.id_album
                        LEFT JOIN artiste
                        ON artiste.id_artiste = son.id_artiste
                        LEFT JOIN lyric
                        ON lyric.id_son = son.id_son
                        WHERE artiste.id_artiste = ".$id." ORDER BY son.id_son DESC";
        $son = $bdd -> query($re);
				$sons = $son->fetch();

				$re1 = "SELECT * FROM album INNER JOIN artiste
  										  ON artiste.id_artiste = album.id_artiste
                        WHERE album.id_artiste = ".$id." ORDER BY album.id_album DESC";
  			$al = $bdd -> query($re1);
  			$nba = $al -> rowCount();
  			// echo $re1;
				$alb = $al->fetch();
				// var_dump($alb);
				$dteA = (isset($alb[14])) ? $alb[14] : 0;
				$idA = (isset($alb['id_album'])) ? $alb['id_album'] : 'NULL';
				// echo ($sons['id_album'] == $idA);
				if ( (($sons['dte_enr_son'] > $dteA) AND ($sons['id_album'] !== $idA)) AND ($id !== '286' AND $id !== '296')) {
					 //echo $id;

					$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
					$reqspre = $bdd -> query($re2);
					$reqsprec = $reqspre->fetch();

					$fichier = $sons['fichier_son'];
					// $fichier = ($reqsprec['type_soutCommande'] == 0 OR $sons['dateSortie_son'] <= $ddj) ? $reqsprec['fichier_soutCommande'] : $reqsprec['fichierpre_soutCommande'];

					//$j = 0;
					$affich_el['s'][$i]['id'] = $sons['0'];
					$affich_el['s'][$i]['name'] = $sons['titre_son'];
					$affich_el['s'][$i]['artist'] = $sons['nom_artiste'];
					$affich_el['s'][$i]['album'] = $sons['titre_album'];
					$affich_el['s'][$i]['url'] = $sons['url_son'];
					$affich_el['s'][$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el['s'][$i]['cover_url'] = $sons['cover_son'];
					$affich_el['s'][$i]['price'] = $sons['prix_son'];
					$affich_el['s'][$i]['f'] = $fichier;
					$affich_el['s'][$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el['s'][$i]['reference'] = $sons['reference_son'];

				} else {
					// echo "album";
					if ($id == '286' OR $id == '296') {
						$lim = ($id == '286') ? 1 : 0;
						$affich_el["s"][$i]['id'] = $alb['id_album'];
						$affich_el["s"][$i]['name'] = $alb['titre_album'];
						$affich_el["s"][$i]['artist'] = $alb['nom_artiste'];
						$affich_el["s"][$i]['cover_art_url'] = $alb['cover_artiste'];
						$affich_el["s"][$i]['cover_url'] = $alb['cover_album'];
						$affich_el["s"][$i]['url'] = $alb['url_album'];
						$affich_el["s"][$i]['f'] = $alb['fichier_album'];
						$affich_el["s"][$i]['price'] = $alb['prix_album'];
						$affich_el["s"][$i]['reference'] = $alb['reference_album'];
						$i++;
						$o=0;
						while ($o <= $lim) {
							$alb = $al->fetch();

			  				//$j = 0;
			    			//var_dump($alb);
			  				$affich_el["s"][$i]['id'] = $alb['id_album'];
			  				$affich_el["s"][$i]['name'] = $alb['titre_album'];
			  				$affich_el["s"][$i]['artist'] = $alb['nom_artiste'];
			  				$affich_el["s"][$i]['cover_art_url'] = $alb['cover_artiste'];
			  				$affich_el["s"][$i]['cover_url'] = $alb['cover_album'];
			  				$affich_el["s"][$i]['url'] = $alb['url_album'];
			  				$affich_el["s"][$i]['f'] = $alb['fichier_album'];
			  				$affich_el["s"][$i]['price'] = $alb['prix_album'];
			  				$affich_el["s"][$i]['reference'] = $alb['reference_album'];
								$i++;

								$o++;
						}
					} else {
						$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
						$reqspre = $bdd -> query($re2);
						$reqsprec = $reqspre->fetch();
						// var_dump($reqsprec);

						if (isset($reqsprec['id_soutCommande']) AND $reqsprec['fichier_soutCommande'] !== 0) {

							$fichier = $alb['fichier_album'];
							// $fichier = ($reqsprec['type_soutCommande'] == 0 OR $alb['dateSortie_album'] <= $ddj) ? $reqsprec['fichier_soutCommande'] : $reqsprec['fichierpre_soutCommande'];
		  				//$j = 0;
		    			//var_dump($alb);
		  				$affich_el["s"][$i]['id'] = $alb['id_album'];
		  				$affich_el["s"][$i]['name'] = $alb['titre_album']." (l'album entier)";
		  				$affich_el["s"][$i]['artist'] = $alb['nom_artiste'];
		  				$affich_el["s"][$i]['cover_art_url'] = $alb['cover_artiste'];
		  				$affich_el["s"][$i]['cover_url'] = $alb['cover_album'];
		  				$affich_el["s"][$i]['url'] = $alb['url_album'];
		  				$affich_el["s"][$i]['f'] = $fichier;
		  				$affich_el["s"][$i]['price'] = $alb['prix_album'];
		  				$affich_el["s"][$i]['reference'] = $alb['reference_album'];
							$i++;

								$re = "SELECT * FROM son INNER JOIN album
				  										  ON son.id_album = album.id_album
				  										  LEFT JOIN artiste
				  										  ON artiste.id_artiste = son.id_artiste
				  										  LEFT JOIN lyric
				  										  ON lyric.id_son = son.id_son
				                        WHERE album.id_album = ".$alb['id_album'];
				  			$son = $bdd -> query($re);
				        while ($sons = $son->fetch() ){
				  				//var_dump ($sons);


				  				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
				  				$nb = $bdd -> query($re1);
				  				$nbs = $nb->rowCount();
				  				//$j = 0;
				  				$affich_el["s"][$i]['id'] = $sons['0'];
				  				$affich_el["s"][$i]['name'] = $sons['titre_son'];
				  				$affich_el["s"][$i]['artist'] = $sons['nom_artiste'];
				  				$affich_el["s"][$i]['album'] = $sons['titre_album'];
				  				$affich_el["s"][$i]['url'] = $sons['url_son'];
				  				$affich_el["s"][$i]['cover_art_url'] = $sons['cover_artiste'];
				  				$affich_el["s"][$i]['cover_url'] = $sons['cover_son'];
				  				$affich_el["s"][$i]['price'] = $sons['prix_son'];
				  				$affich_el["s"][$i]['f'] = $sons['fichier_son'];
				  				$affich_el["s"][$i]['downloads'] = $nbs;
				  				$affich_el["s"][$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
				  				$affich_el["s"][$i]['reference'] = $sons['reference_son'];

				  				$i++;
								}
								$son->closeCursor();
						}
					}

				}
      }





      // $affich_e = array_shift($affich_el);


      $affich_el['don']="don";
      $affich_e = array_shift($affich_el);
      echo json_encode($affich_el);

    }
    } else {
      $affich_el['error']=1;
      // $affich_el['ip']=$ip.'('.$elt['ip_transaction'].')';
      $affich_e = array_shift($affich_el);
      echo json_encode($affich_el);
    }


	}

  function referenceTrsx(){
    require 'connexion.php';
    $tabl = $_GET['table'];
		$_POST['nom'] = $_GET['nom'];
		$_POST['numero'] = $_GET['numero'];
		$_POST['indicatif'] = $_GET['indicatif'];
		$affich_el[0]=1;
		// $_POST['mail'] = $_GET['mail'];
		// $_POST['message'] = $_GET['message'];
		$_POST['id'] = $_GET['id'];
		$_POST['idU'] = (isset($_GET['idU'])) ? $_GET['idU'] : NULL;

    $_POST['numero'] = str_replace(' ','',$_POST['numero']);
		$libTs = $_POST['id'];
    $datee2Ts = date ('Y-m-d H:i:s', time()-15);
    // $dateAuj =  date ('Y-m-d H:i:s');
		// echo $datee2Ts.'<br>';
		// echo $dateAuj.'<br>';
		$reTs = "SELECT * FROM transaction WHERE telephone_transaction = '".$_POST['numero']."' AND statut_transaction = 'ATTENTE' AND date_transaction	> '".$datee2Ts."' AND libelle_transaction LIKE '%".$libTs."%' ORDER BY id_transaction DESC";

		$unTs = $bdd -> query($reTs);
		$nbTs = $unTs->rowCount();
		$untTs = $unTs->fetch();

		if(isset($untTs['reference_transaction'])){


      $affich_el['reference']=$untTs['reference_transaction'];
		} else {

	    $datee = $ddj = date ('Y-m-d H:i:s');
	    $ref = "AFPTX-";
	    $ref .= genererChaineAleatoire();
	    $statutt = "ATTENTE";
			// echo $ref;
	    $d = (isset($_GET['don'])) ? "-don" : "" ;
			if ($_GET['table'] == "artiste") {
				$_GET['art']=1;
			}

	    $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'transaction' ");
	    // $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'transaction' ");
	     $trsax = $req->fetch();


	    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	            $ip = $_SERVER['HTTP_CLIENT_IP'];
	        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	        } else {
	            $ip = $_SERVER['REMOTE_ADDR'];
	        }
	        //var_dump($ip);
	    $reP = "SELECT * FROM pays INNER JOIN devise
	                              ON devise.id_devise = pays.id_devise
	                              INNER JOIN taux
	                              ON taux.to_taux = devise.id_devise
	                              WHERE pays.indicatif_pays = ".$_GET['indicatif']."
	                              ORDER BY pays.nom_pays";
			$lpays = $bdd -> query($reP);
	    $lpays = $lpays->fetch();
			// $lpays->closeCursor();


	    if (isset($_GET['art'])) {
	      $montant = $_POST['montant'];
	      $lib = $tabl."-".$_POST['id'].$d;
	      $idEtr = $_POST['id'];
	    } else {
	      $re = "SELECT * FROM ".$tabl." WHERE id_".$tabl." = ".$_POST['id'];
	  		$un = $bdd -> query($re);
	  		//$nbuser = $un->rowCount();
	  		$elt = $un->fetch();
				// $un->closeCursor();
	      // var_dump($re);
	      if ($lpays['taux_taux'] == 0.1 OR $lpays['taux_taux'] == 0.4) {
	        $montant = $elt['prix_'.$tabl];
	      } else {
	        $montant = $elt['prix_'.$tabl]*$lpays['taux_taux'];
	      }

	      $idEtr = ($d == "") ? $elt['id_'.$tabl] : $elt['id_artiste'] ;
	      $lib = $tabl."-".$elt['titre_'.$tabl]."-".$idEtr.$d;
		  	$montant = (isset($_POST['pc'])) ? $montant*2 : $montant;
	    }

	    if(isset($_GET['don'])){

				$_POST['nom'] = $_POST['nom'];
				$_POST['don'] = $_GET['don'];
				// $_POST['numero'] = $_GET['numero'];
				$_POST['mail'] = $_GET['mail'];
				$_POST['message'] = $_GET['message'];
				$_POST['id'] = $_GET['id'];
	      $_POST['mail'] = (isset($_POST['mail'])) ? $_POST['mail'] : "Neant" ;
	      $_POST['message'] = (isset($_POST['message'])) ? $_POST['message'] : "Neant" ;
			  $montant = $_POST['montant'];


	      $req_ajt = "INSERT INTO don (nom_don, somme_don, numero_don, email_don, message_don, id_artiste, id_transaction) VALUES (:nom_don, :somme_don, :numero_don, :email_don, :message_don, :id_artiste, :id_transaction)";
	        $req_aj_don = $bdd -> prepare($req_ajt);
	        $req_aj_don ->bindParam(':nom_don', $_POST['nom']);
	        $req_aj_don ->bindParam(':somme_don', $montant);
	        $req_aj_don ->bindParam(':numero_don', $_POST['numero']);
	        $req_aj_don ->bindParam(':email_don', $_POST['mail']);
	        $req_aj_don ->bindParam(':message_don', $_POST['message']);
	        $req_aj_don ->bindParam(':id_artiste', $idEtr);
	        $req_aj_don ->bindParam(':id_transaction', $trsax['Auto_increment']);

	        $req_aj_don ->execute();
					// $req_aj_don->closeCursor();
	        //$don = "&don=don";

	    }


	    $req_ajc = "INSERT INTO transaction (date_transaction, nom_transaction, telephone_transaction, montant_transaction, reference_transaction, statut_transaction, libelle_transaction, ip_transaction, id_user, id_pays) VALUES (:date_transaction, :nom_transaction, :telephone_transaction, :montant_transaction, :reference_transaction, :statut_transaction, :libelle_transaction, :ip_transaction, :id_user, :id_pays)";
				$req_aj_cod = $bdd -> prepare($req_ajc);
				$req_aj_cod ->bindParam(':date_transaction', $datee);
				$req_aj_cod ->bindParam(':nom_transaction', $_POST['nom']);
				$req_aj_cod ->bindParam(':telephone_transaction', $_POST['numero']);
				$req_aj_cod ->bindParam(':montant_transaction', $montant);
				$req_aj_cod ->bindParam(':reference_transaction', $ref);
				$req_aj_cod ->bindParam(':statut_transaction', $statutt);
				$req_aj_cod ->bindParam(':libelle_transaction', $lib);
				$req_aj_cod ->bindParam(':ip_transaction', $ip);
				$req_aj_cod ->bindParam(':id_user', $_POST['idU']);
				$req_aj_cod ->bindParam(':id_pays', $lpays['id_pays']);
				$req_aj_cod ->execute();
				// $req_aj_cod->closeCursor();

	      if (isset($_GET['wa']) AND $_GET['wa'] == 1 ) {
	        $req_ajcw = "INSERT INTO transactionWha (id_transaction, reference_transaction) VALUES (:id_transaction, :reference_transaction)";
	        $req_aj_codw = $bdd -> prepare($req_ajcw);
	  			$req_aj_codw ->bindParam(':id_transaction', $trsax['Auto_increment']);
	  			$req_aj_codw ->bindParam(':reference_transaction', $ref);
	  			$req_aj_codw ->execute();
	      }

				if (isset($_GET['a']) AND $_GET['a'] == 1 ) {
	        $req_ajca = "INSERT INTO transactionApp (id_transaction, reference_transaction) VALUES (:id_transaction, :reference_transaction)";
	        $req_aj_coda = $bdd -> prepare($req_ajca);
	  			$req_aj_coda ->bindParam(':id_transaction', $trsax['Auto_increment']);
	  			$req_aj_coda ->bindParam(':reference_transaction', $ref);
	  			$req_aj_coda ->execute();
	      }

				if (isset($_GET['wal']) AND ($_GET['wal'] == 1 OR $_GET['wal'] == 2) ) {
					$typWal = ($_GET['wal'] == 1) ? 'walletP' : 'walletB';
					$rew = "SELECT * FROM ".$typWal." WHERE id_user = '".$_POST['idU']."'";
					$wa = $bdd -> query($rew);
					$wal = $wa -> fetch();
					$idw = $wal['id_'.$typWal];
	        $req_ajca = "INSERT INTO transactionWallet (id_transaction, reference_transaction, libelle_transactionWallet, id_wallet) VALUES (:id_transaction, :reference_transaction, :libelle_transactionWallet, :id_wallet)";
	        $req_aj_coda = $bdd -> prepare($req_ajca);
	  			$req_aj_coda ->bindParam(':id_transaction', $trsax['Auto_increment']);
	  			$req_aj_coda ->bindParam(':reference_transaction', $ref);
	  			$req_aj_coda ->bindParam(':libelle_transactionWallet', $typWal);
	  			$req_aj_coda ->bindParam(':id_wallet', $idw);
	  			$req_aj_coda ->execute();
					// $req_aj_coda->closeCursor();
	      }



	      $_POST['numero'] = str_replace(" ", "", $_POST['numero']);

	      if(substr($_POST['numero'], 0, 2) == '00'){
	        if (substr($_POST['numero'], 2, 3) == $_GET['indicatif']) {
	          $indRsx = substr($_POST['numero'], 5, 2);
	        } else {
	          $indRsx = substr($_POST['numero'], 2, 2);
	        }
	      } else {
	        if (substr($_POST['numero'], 0, 3) == $_GET['indicatif']) {
	          $indRsx = substr($_POST['numero'], 3, 2);
	        } else {
	          $indRsx = substr($_POST['numero'], 0, 2);
	        }
	      }
	      // echo $indRsx;

	        $detail = "N/A";
					$integ = "N/A";
	      if ($_GET['indicatif'] == '225') {
	        $integ = 2;
	        if ($indRsx == '01') {
	          $detail = "Flooz CI";
	        } else if ($indRsx == '05') {
	          $detail = "MOMO CI";

	        } else if ($indRsx == '07') {
	          $detail = "OM CI";
	        }

	      } else if ($_GET['indicatif'] == '229') {
	        $integ = 8;
	        $detail = "N/A";

	      } else if ($_GET['indicatif'] == '000') {
	        $integ = 1;
	        $detail = "N/A";

	      } else if ($_GET['indicatif'] == '224') {


	        if ($indRsx == '62') {
	          $detail = "OM GN";
	          $integ = 7;
	        } else if ($indRsx == '05') {
	          $detail = "MOMO GN";
	          $integ = 4;

	        }

	      } else if ($_GET['indicatif'] == '241') {

	          $detail = "N/A";
	          $integ = 9;


	      } else if ($_GET['indicatif'] == '227') {

	          $detail = "AIRTEL";
	          $integ = 1;


	      } else if ($_GET['indicatif'] == '228') {

	        if (isset($_GET['mvafp'])) {
	          $detail = $_GET['mvafp'];
	          $integ = 6;
	        } else {
	          $detail = "N/A";
	          $integ = 2;

	        }


	      } else if ($_GET['indicatif'] == '243') {

	          $detail = "N/A";
	          $integ = 2;


	      } else if ($_GET['indicatif'] == '226' OR $_GET['indicatif'] == '223' OR $_GET['indicatif'] == '221' OR $_GET['indicatif'] == '237') {

	          $detail = "N/A";
	          $integ = 4;

	      }

			if(isset($_GET['pc']) AND $_GET['pc'] == '1'){
			  $integ = 1;
	        $detail = "N/A";
		  }


	      $req_ajit = "INSERT INTO integrateur_transaction (id_transaction, id_integrateur, detail_integrateur_transaction) VALUES (:id_transaction, :id_integrateur, :detail_integrateur_transaction)";
	       // echo $detail;
	      $req_aj_it = $bdd -> prepare($req_ajit);
	      $req_aj_it ->bindParam(':id_transaction', $trsax['Auto_increment']);
	      $req_aj_it ->bindParam(':id_integrateur', $integ);
	      $req_aj_it ->bindParam(':detail_integrateur_transaction', $detail);
	      // var_dump($req_aj_it);
	      $req_aj_it ->execute();
				// $req_aj_it->closeCursor();


				$affich_el['reference']=$ref;
			}
      //$affich_el['reference']=$_POST['numero'];
			$affich_e = array_shift($affich_el);
			// echo $ref;
      echo json_encode($affich_el);
  }

  function checkStat(){
    require 'connexion.php';
		//$i =2;
    //var_dump($_POST);
		$affich_el[] = '';
    $re = "SELECT * FROM ".$_POST['table']." WHERE reference_".$_POST['table']." = '".$_POST['ref']."'";
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
		$elt = $un->fetch();
    //var_dump($elt);
    $affich_e = array_shift($affich_el);
    $affich_el['statut']=$elt['statut_'.$_POST['table']];
    //$affich_el['reference']=$_POST['numero'];
		if ($_POST['table'] == 'rechargement') {
			$re1 = "SELECT * FROM walletP WHERE id_walletP = ".$elt['id_walletP'];
			$un1 = $bdd -> query($re1);
			//$nbuser = $un->rowCount();
			$wal = $un1->fetch();
			$affich_el['solde'] = $wal['solde_walletP'];
		}
    echo json_encode($affich_el);
  }

  function lpays(){
    require 'connexion.php';
		//$i =2;
    //var_dump($_POST);
		$affich_el[] = '';
    $re = "SELECT * FROM pays_mm INNER JOIN devise
                              ON devise.id_devise = pays_mm.id_devise
                              ORDER BY pays_mm.nom_pays";
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
    $affich_el[0]=0;
    $i=1;
    while ($elt = $un->fetch()) {
      // var_dump($elt);

      $affich_el[$i]['name']=$elt['nom_pays'];
      $affich_el[$i]['indicatif']=$elt['indicatif_pays'];
      //$affich_el[$i]['drapeau']=$elt['image_pays'];
      $affich_el[$i]['devise']=$elt['signe_devise'];

      $re22 = "SELECT * FROM taux WHERE to_taux = ".$elt['id_devise'];
      $un22 = $bdd -> query($re22);
      $elt2 = $un22->fetch();

      $affich_el[$i]['taux']=$elt2['taux_taux'];

      $i++;

    }

    $affich_e = array_shift($affich_el);
    //$affich_el['statut']=$elt['statut_transaction'];
    //$affich_el['reference']=$_POST['numero'];
    echo json_encode($affich_el);
  }

  function lpaysT(){
    require 'connexion.php';
		//$i =2;
    //var_dump($_POST);
		$affich_el[] = '';
    $re = "SELECT * FROM pays INNER JOIN devise
                              ON devise.id_devise = pays.id_devise
                              ORDER BY pays.nom_pays";
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
    $affich_el[0]=0;
    $i=1;
    while ($elt = $un->fetch()) {
      // var_dump($elt);

      $affich_el[$i]['name']=$elt['nom_pays'];
      $affich_el[$i]['indicatif']=$elt['indicatif_pays'];
      //$affich_el[$i]['drapeau']=$elt['image_pays'];
      $affich_el[$i]['devise']=$elt['signe_devise'];

      $re22 = "SELECT * FROM taux WHERE to_taux = ".$elt['id_devise'];
      $un22 = $bdd -> query($re22);
      $elt2 = $un22->fetch();

      $affich_el[$i]['taux']=$elt2['taux_taux'];

      $i++;

    }

    $affich_e = array_shift($affich_el);
    //$affich_el['statut']=$elt['statut_transaction'];
    //$affich_el['reference']=$_POST['numero'];
    echo json_encode($affich_el);
  }

	function inscr(){
		require 'connexion.php';
		// $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'user' ");
		$req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'user' ");
		$donnees = $req->fetch();

    $_GET['telephone'] = str_replace(" ", "", $_GET['telephone']);
		if (isset($_GET['indicatif'])) {
			$indic = ($_GET['indicatif'] == "autres") ? "000" : $_GET['indicatif'];
		} else {
			$indic = (substr($_GET['telephone'], 0, 2) == '00') ? substr($_GET['telephone'], 2, 3) : substr($_GET['telephone'], 0, 3);

		}


    $re11 = "SELECT * FROM pays WHERE indicatif_pays = '".$indic."'";
		$p = $bdd -> query($re11);
		$pysUser = $p->fetch();

    $idPys = (isset($pysUser['id_pays'])) ? $pysUser['id_pays'] : '';
    $idDevis = (isset($pysUser['id_devise'])) ? $pysUser['id_devise'] : '1';

    $ddj = date ('Y-m-d H:i:s');
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
		if ($indic == "000") {
			$re9 = "SELECT * FROM user WHERE email_user = '".$_GET['mail']."'";
				$u = $bdd -> query($re9);
				$nbuser = $u->rowCount();
		} else {
			$re9 = "SELECT * FROM user WHERE telephone_user LIKE '%".$_GET['telephone']."'";
				$u = $bdd -> query($re9);
				$nbuser = $u->rowCount();
		}

			//$iuser = $vu->fetch();
		if($nbuser <= 0) {

			// function nbAleatoire($length){
			// 	$tab_match = [];
			// 	while(count($tab_match) < $length)
			// 	{
			// 		preg_match_all ('#\d#', hash("sha512",openssl_random_pseudo_bytes("128", $cstrong)), $matches);
			// 		$tab_match = array_merge($tab_match,$matches[0]);
			// 	}
			// 	shuffle($tab_match);
			// 	return implode('',array_slice($tab_match,0,$length));
			// }
			//$code = nbAleatoire(4);
      if(isset($_GET['joursNaiss']) AND isset($_GET['moisNaiss'])){
        $jrMoisNaiss = $_GET['joursNaiss']."-".$_GET['moisNaiss'];
      } else {
        $jrMoisNaiss = "00-00";
      }
      if (!isset($_GET['mail'])) {
        $_GET['mail'] = "Neant";
      }
      $statut = 1;
      $act = 1;

  		$req_ajd = "INSERT INTO user (nom_user, telephone_user, email_user, dob_user, trancheAge_user, sexe_user, datenreg_user, statut_user, id_pays) VALUES (:nom_user, :telephone_user, :email_user, :dob_user, :trancheAge_user, :sexe_user, :datenreg_user, :statut_user, :id_pays)";
  		$req_aj_don = $bdd -> prepare($req_ajd);
  		$req_aj_don ->bindParam(':nom_user', $_GET['nom']);
  		$req_aj_don ->bindParam(':telephone_user', $_GET['telephone']);
  		$req_aj_don ->bindParam(':email_user', $_GET['mail']);
  		$req_aj_don ->bindParam(':dob_user', $jrMoisNaiss);
  		$req_aj_don ->bindParam(':trancheAge_user', $_GET['tranche']);
  		$req_aj_don ->bindParam(':sexe_user', $_GET['sexe']);
  		$req_aj_don ->bindParam(':datenreg_user', $ddj);
  		$req_aj_don ->bindParam(':statut_user', $statut);
  		$req_aj_don ->bindParam(':id_pays', $pysUser['id_pays']);
  		$req_aj_don ->execute();

      $req_ajco = "INSERT INTO connexion (date_connexion, ip_connexion, active_connexion, id_user) VALUES (:date_connexion, :ip_connexion, :active_connexion, :id_user)";
  		$req_ajcon = $bdd -> prepare($req_ajco);
  		$req_ajcon ->bindParam(':date_connexion', $ddj);
  		$req_ajcon ->bindParam(':ip_connexion', $ip);
      $req_ajcon ->bindParam(':active_connexion', $act);
  		$req_ajcon ->bindParam(':id_user', $donnees['Auto_increment']);
      $req_ajcon ->execute();

			$solde = 0;
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


			$nbScd = 86400;
			$datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
			$libForf = "promo1";
			// Moyen de paiement 1 mobile mo, 0 Airtime
			$renouv = 0;
			$idForfait = 1;
			$moyenPaie = "MM";

			$req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, libelle_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :libelle_user_forfaitStream, :id_user, :id_forfaitStream)";
			$req_aj_uf = $bdd -> prepare($req_ajuf);
			$req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $ddj);
			$req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
			$req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
			$req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
			$req_aj_uf ->bindParam(':libelle_user_forfaitStream', $libForf);
			$req_aj_uf ->bindParam(':id_user', $donnees['Auto_increment']);
			$req_aj_uf ->bindParam(':id_forfaitStream', $idForfait);
			$req_aj_uf ->execute();


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




      $rep['reponse'] = "ok";
      $rep['id'] = $donnees['Auto_increment'];
			echo json_encode($rep);
		} else {
			$rep['reponse'] = "erreur1";
			echo json_encode($rep);
		}
	}

  function connect(){
    require 'connexion.php';
		// $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'user' ");
    $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'user' ");
    $donnees = $req->fetch();


    $ddj = date ('Y-m-d H:i:s');
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
				$numero1 = $_GET['login'];
		if(substr($numero1, 0, 2) == "00"){
      $numero1 = substr($_GET['login'], 2);

    }
      //echo $numero1;

    $repays = "SELECT * FROM pays";
    $rqpays = $bdd -> query($repays);
    $idxPys = 0;

    while ($pays = $rqpays -> fetch()) {

      $indiPys[$idxPys] = $pays['indicatif_pays'];

      $idxPys++;
    }


    // if(substr($_POST['n'], 0, 2) == "22" || substr($_POST['n'], 0, 2) == "23"){
    if(in_array(substr($numero1, 0, 3), $indiPys)){
      $numero1 = substr($numero1, 3);
    }
		 //echo "posi 2 ".$numero1;

		$re10 = "SELECT * FROM user LEFT JOIN pays
															ON user.id_pays = pays.id_pays
															WHERE user.email_user = '".$_GET['login']."' OR telephone_user LIKE '%".$numero1."' ";
			$u = $bdd -> query($re10);
			$nbuser = $u->rowCount();


      if ($nbuser == 1) {
        $user = $u->fetch();

				$rew1 = "SELECT * FROM walletP WHERE id_user = ".$user['id_user'];
  			$w1 = $bdd -> query($rew1);
  			$walp = $w1->fetch();
				$rew2 = "SELECT * FROM walletB WHERE id_user = ".$user['id_user'];
  			$w2 = $bdd -> query($rew2);
  			$walb = $w2->fetch();

        // $re12 = "SELECT * FROM connexion WHERE id_user = ".$user['id_user'];
    		// 	$con = $bdd -> query($re12);
    		// 	$connex = $con->fetch();

        // if($connex['active_connexion'])

        $rep['reponse']['id'] = $user['id_user'];
        $rep['reponse']['telephone'] = $user['telephone_user'];
        $rep['reponse']['email'] = $user['email_user'];
        $rep['reponse']['dob'] = $user['dob_user'];
        $rep['reponse']['tranche'] = $user['trancheAge_user'];
        $rep['reponse']['sexe'] = $user['sexe_user'];
        $rep['reponse']['nom'] = $user['nom_user'];
        $rep['reponse']['wallet1'] = $walp['solde_walletP'];
        $rep['reponse']['wallet2'] = $walb['solde_walletB'];
        $rep['reponse']['pays'] = $user['code_pays'];
        $rep['reponse']['indicatif'] = $user['indicatif_pays'];
        $rep['error'] = 0;

				$requetStr = $bdd -> query('SELECT * FROM user_forfaitStream
																		INNER JOIN forfaitStream
																		ON user_forfaitStream.id_forfaitStream = forfaitStream.id_forfaitStream
																		WHERE user_forfaitStream.id_user =\''.$user["id_user"].'\' AND dateFin_user_forfaitStream <= \''.$ddj.'\'');
				$Stre = $requetStr -> fetch();
				if ($Stre['id_user_forfaitStream'] !== NULL) {
					$rep["forfait"]["id"] = $Stre["id_user_forfaitStream"];
					$rep["forfait"]["debut"] = $Stre["dateDeb_user_forfaitStream"];
					$rep["forfait"]["fin"] = $Stre["dateFin_user_forfaitStream"];
					$rep["forfait"]["methode"] = $Stre["moyenPaie_user_forfaitStream"];
					$rep["forfait"]["renouvellementAuto"] = $Stre["renouvellement_user_forfaitStream"];
					$rep["forfait"]["forfaitID"] = $Stre["id_forfaitStream"];
					$rep["forfait"]["forfaitNom"] = $Stre["nom_forfaitStream"];
					$rep["forfait"]["forfaitPrix"] = $Stre["prix_forfaitStream"];
					$rep["forfait"]["forfaitTemps"] = $Stre["temps_forfaitStream"];
				}
  			// echo json_encode($rep);
        $act = 1;

        $req_majc = "UPDATE connexion SET date_connexion=:date_connexion, ip_connexion=:ip_connexion, active_connexion=:active_connexion WHERE id_user=:id_user";
        // echo $req_majt;
        $req_maj_c = $bdd -> prepare($req_majc);
        $req_maj_c ->bindParam(":date_connexion", $ddj);
        $req_maj_c ->bindParam(":ip_connexion", $ip);
        $req_maj_c ->bindParam(":active_connexion", $act);
        $req_maj_c ->bindParam(":id_user", $user['id_user']);
        $req_maj_c ->execute();



      } else {
        $rep['error'] = 1;
      }

      echo json_encode($rep);

  }

  function deco(){
    require 'connexion.php';


        $act = 0;

        $req_majc = "UPDATE connexion SET active_connexion=:active_connexion WHERE id_user=:id_user";
        // echo $req_majt;
        $req_maj_c = $bdd -> prepare($req_majc);
        $req_maj_c ->bindParam(":active_connexion", $act);
        $req_maj_c ->bindParam(":id_user", $_GET['user']);
        $req_maj_c ->execute();

        $rep['reponse'] = "ok";
  			echo json_encode($rep);
  }

	function histo(){
  		require 'connexion.php';
  		$i =1;
  		$affich_el[] = '';
      $_POST['n'] = str_replace(' ','',$_POST['n']);
      $numero1 = $_POST['n'];
			$ddj = date ('Y-m-d H:i:s');
			$listTSon[]='';
			$listTAlb[]='';

      if(substr($_POST['n'], 0, 2) == "00"){
        $numero1 = substr($_POST['n'], 2);

      }
			if(substr($_POST['n'], 0, 1) == "+"){
				$numero1 = substr($_POST['n'], 1);

			}
       //echo $numero1;

      $repays = "SELECT * FROM pays";
      $rqpays = $bdd -> query($repays);
      $idxPys = 0;

      while ($pays = $rqpays -> fetch()) {

        $indiPys[$idxPys] = $pays['indicatif_pays'];

        $idxPys++;
      }
      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
              $ip = $_SERVER['HTTP_CLIENT_IP'];
          } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
              $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
          } else {
              $ip = $_SERVER['REMOTE_ADDR'];
          }

      // if(substr($_POST['n'], 0, 2) == "22" || substr($_POST['n'], 0, 2) == "23"){
      if(in_array(substr($numero1, 0, 3), $indiPys)){
        $numero1 = substr($numero1, 3);
      }
       // echo $numero1;
      // $lastmonth = date('Y-m-d', strtotime('-1 month'));
      // echo $lastmonth;
      // $re = "SELECT DISTINCT libelle_transaction, telephone_transaction, statut_transaction, reference_transaction, id_transaction FROM transaction WHERE telephone_transaction LIKE '%".$numero1."' AND statut_transaction = 'SUCCESS' AND date_transaction >= '".$lastmonth."' ORDER BY id_transaction DESC";
      $re = "SELECT DISTINCT libelle_transaction, telephone_transaction, statut_transaction, reference_transaction, id_transaction FROM transaction WHERE telephone_transaction LIKE '%".$numero1."' AND statut_transaction = 'SUCCESS' ORDER BY id_transaction DESC";
  		$un = $bdd -> query($re);
  		//$nbuser = $un->rowCount();
        // echo $re;
				$idxListSn = 0;
				$idxListAlb = 0;
  		while($elt = $un->fetch()){

        // echo $elt['libelle_transaction']."<br>";
        $lib = explode('-', $elt['libelle_transaction']);
        //$affich_el[1]=$lib[1];
        $affich_el[0]=1;
          // var_dump($elt);
        $tail = sizeof($lib);

      	if (end($lib) == "don" OR end($lib) == "offert") {
      		$id = $lib[$tail-2];
      	} else {
      		$id = $lib[$tail-1];
      	}

        // echo $id."<br>";


        // if($ip == $elt['ip_transaction']) {


        if (end($lib) !== "don") {
      		if($lib[0] == 'son'){

      			$re = "SELECT * FROM son LEFT JOIN album
      										  ON son.id_album = album.id_album
      										  LEFT JOIN artiste
      										  ON artiste.id_artiste = son.id_artiste
      										  LEFT JOIN lyric
      										  ON lyric.id_son = son.id_son
                            WHERE son.id_son = ".$id;
      			$son = $bdd -> query($re);
      			$nbson = $son -> rowCount();
						$sons = $son->fetch();
      			 // echo $re;
						if (!in_array($sons[0], $listTSon)) {
							$listTSon[$idxListSn] = $sons[0];
							$son->closeCursor();
	      				// var_dump ($sons);

								$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";

								//$reqspre = $bdd -> query($re2);
								//$reqsprec = $reqspre->fetch();
								//$reqspre->closeCursor();

								// $fichier = ($reqsprec['type_soutCommande'] !== 0 AND $sons['dateSortie_son'] > $ddj) ? $reqsprec['fichierpre_soutCommande'] : $sons['fichier_son'];
								$fichier = $sons['fichier_son'];
	      				// $re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
	      				// $nb = $bdd -> query($re1);
	      				// $nbs = $nb->rowCount();
	      				//$j = 0;
	      				$affich_el[$i]['id'] = $sons['0'];
	      				$affich_el[$i]['name'] = $sons['titre_son'];
	      				$affich_el[$i]['artist'] = $sons['nom_artiste'];
	      				$affich_el[$i]['album'] = $sons['titre_album'];
	      				$affich_el[$i]['url'] = $sons['url_son'];
	      				$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
	      				$affich_el[$i]['cover_url'] = $sons['cover_son'];
	      				$affich_el[$i]['price'] = $sons['prix_son'];
	      				$affich_el[$i]['f'] = $fichier;
					      $affich_el[$i]['idArtist'] = $sons['id_artiste'];
	      				// $affich_el[$i]['downloads'] = $nbs;
	      				$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
	      				$affich_el[$i]['reference'] = $sons['reference_son'];
	      				$affich_el[$i]['referenceT'] = $elt['reference_transaction'];

	      				$i++;
								$idxListSn++;
						}


      			// $affich_e = array_shift($affich_el);
      			//var_dump($affich_e);
      			//var_dump($affich_el);
      		  //$affich_el = convert_from_latin1_to_utf8_recursively($affich_el);
      			// echo json_encode($affich_el);
            // echo json_last_error_msg();

      	  }
      		else if($lib[0] == 'album'){

      			$re = "SELECT * FROM album INNER JOIN artiste
      										  ON artiste.id_artiste = album.id_artiste
                            WHERE album.id_album = ".$id;
      			$al = $bdd -> query($re);
      			$nba = $al -> rowCount();
      			 // echo $re;


      			$alb = $al->fetch();
						if (!in_array($alb[0], $listTAlb)) {
							$listTAlb[$idxListAlb] = $alb[0];
							$al->closeCursor();
	             // var_dump($alb);

							 $re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
							 $reqspre = $bdd -> query($re2);
							 $reqsprec = $reqspre->fetch();
							 $reqspre->closeCursor();
							 // $fichier = ($reqsprec['type_soutCommande'] !== 0 AND $alb['dateSortie_album'] > $ddj) ? $reqsprec['fichierpre_soutCommande'] : $alb['fichier_album'];
							 $fichier = $alb['fichier_album'];

	      				$affich_el[$i]['id'] = $alb['id_album'];
	      				$affich_el[$i]['name'] = $alb['titre_album']." (l'album entier)";
	      				$affich_el[$i]['artist'] = $alb['nom_artiste'];
					      $affich_el[$i]['idArtist'] = $alb['id_artiste'];
	      				$affich_el[$i]['cover_art_url'] = $alb['cover_artiste'];
	      				$affich_el[$i]['cover_url'] = $alb['cover_album'];
	      				$affich_el[$i]['url'] = $alb['url_album'];
	      				$affich_el[$i]['f'] = $fichier;
	      				$affich_el[$i]['price'] = $alb['prix_album'];
	      				$affich_el[$i]['downloads'] = 0;
	      				$affich_el[$i]['reference'] = $alb['reference_album'];
	              $affich_el[$i]['referenceT'] = $elt['reference_transaction'];

	      				$i++;
								$idxListAlb++;
								if ($reqsprec['type_soutCommande'] == 0 OR $alb['dateSortie_album'] <= $ddj) {
		              $re = "SELECT * FROM son INNER JOIN album
		        										  ON son.id_album = album.id_album
		        										  LEFT JOIN artiste
		        										  ON artiste.id_artiste = son.id_artiste
		        										  LEFT JOIN lyric
		        										  ON lyric.id_son = son.id_son
		                              WHERE album.id_album = ".$id;
		        			$son = $bdd -> query($re);
		              while ($sons = $son->fetch() ){
		        				 // var_dump ($sons);


		        				// $re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
		        				// $nb = $bdd -> query($re1);
		        				// $nbs = $nb->rowCount();
		        				//$j = 0;
										if (!in_array($sons[0], $listTSon)) {
											$listTSon[$idxListSn] = $sons[0];
			        				$affich_el[$i]['id'] = $sons['0'];
			        				$affich_el[$i]['name'] = $sons['titre_son'];
			        				$affich_el[$i]['artist'] = $sons['nom_artiste'];
								      $affich_el[$i]['idArtist'] = $sons['id_artiste'];
			        				$affich_el[$i]['album'] = $sons['titre_album'];
			        				$affich_el[$i]['url'] = $sons['url_son'];
			        				$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
			        				$affich_el[$i]['cover_url'] = $sons['cover_son'];
			        				$affich_el[$i]['price'] = $sons['prix_son'];
			        				$affich_el[$i]['f'] = $sons['fichier_son'];
			        				$affich_el[$i]['downloads'] = 0;
			        				$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
			        				$affich_el[$i]['reference'] = $sons['reference_son'];
			        				$affich_el[$i]['referenceT'] = $elt['reference_transaction'];

			        				$i++;
										}
									}
									$son->closeCursor();
	        			}
						}


    		  }
        }
        // else {
        //   $affich_el['don']="don";
        //   $affich_e = array_shift($affich_el);
        //   echo json_encode($affich_el);
        //
        // }
        // } else {
        //   $affich_el['error']=1;
        //   $affich_e = array_shift($affich_el);
        //   echo json_encode($affich_el);
        // }
      }
			$un->closeCursor();
      $affich_e = array_shift($affich_el);
      //var_dump($affich_e);
       // var_dump($affich_el);

      echo json_encode($affich_el);

  	}

	function verifcod(){
		require 'connexion.php';
		$ddj = date ('Y-m-d');
		$re = "SELECT * FROM verif_user WHERE code_verif_user = '".$_GET['code']."' AND id_user = ".$_GET['id'];
			$vu = $bdd -> query($re);
			$vuser = $vu->rowCount();
			$iuser = $vu->fetch();

			if($vuser == 1 OR $_GET['code'] == "0000"){

				$req_majun = 'UPDATE verif_user SET date_verif_user = :date_verif_user WHERE id_verif_user  = :idc';
				$req_majun_vu = $bdd -> prepare($req_majun);
				$req_majun_vu ->bindParam(':date_verif_user', $ddj);
				$req_majun_vu ->bindParam(':idc', $iuser['id_verif_user']);
				$req_majun_vu -> execute();

				$req_majun = 'UPDATE user SET date_verif_user = :date_verif_user WHERE id_user = :id';
				$req_majun_vu = $bdd -> prepare($req_majun);
				$req_majun_vu ->bindParam(':date_verif_user', $ddj);
				$req_majun_vu ->bindParam(':id', $_GET['id']);
				$req_majun_vu -> execute();

				$rep['reponse'] = 'validee';


			} else {
				$rep['reponse'] = 'erreur';
			}
		echo json_encode($rep);
	}

	function majuser(){
		require 'connexion.php';

		if(isset($_GET['table'])){
		if ($_GET['table'] == 'user2'){
			//echo 'ici';
			if($_GET['mail'] !== ''){
				$_GET['mail']=str_replace(' ','',$_GET['mail']);
			}

			$req_majun = 'UPDATE user SET nom_user = :nom_user, sexe_user = :sexe_user, telephone_user = :telephone_user, avatar_user = :avatar_user, email_user = :email_user WHERE id_user = :id';
			$req_majun_us = $bdd -> prepare($req_majun);
			$req_majun_us ->bindParam(':nom_user', $_GET['nom']);
			$req_majun_us ->bindParam(':sexe_user', $_GET['sexe']);
			$req_majun_us ->bindParam(':telephone_user', $_GET['numero']);
			$req_majun_us ->bindParam(':avatar_user', $_GET['sexe']);
			$req_majun_us ->bindParam(':email_user', $_GET['mail']);
			$req_majun_us ->bindParam(':id', $_GET['id_user']);
			$req_majun_us -> execute();
			$rep['reponse'] = "Success";
				echo json_encode($rep);

		} else if($_GET['table'] == 'user3') {

			$re = "SELECT * FROM user_classe WHERE id_user = '".$_GET['id_user']."'";
			$cl = $bdd -> query($re);
			$class = $cl->fetch();
			$req_sjd = "DELETE FROM user_matiere WHERE id_user = :id_user";
			$req_sj_don = $bdd -> prepare($req_sjd);
			$req_sj_don ->bindParam(':id_user', $_GET['id_user']);
			$req_sj_don ->execute();
			$lmat = explode(',', $_GET['matieres']);
			$i=0;
			while($i < sizeof($lmat)){
				$re = "SELECT * FROM matiere WHERE titre_matiere = '".$lmat[$i]."'";
				$ma = $bdd -> query($re);
				$mat = $ma->fetch();
				//echo $mat['id_matiere'].'/';
				$re2 = "SELECT * FROM classe_matiere WHERE id_matiere = ".$mat['id_matiere']." AND id_classe = ".$class['id_classe'];
				$cl = $bdd -> query($re2);
				$clas = $cl->fetch();
				//echo $clas['titre_classe_matiere'];
				$req_ajd = "INSERT INTO user_matiere (id_user, id_classe_matiere) VALUES (:id_user, :id_classe_matiere)";
				$req_aj_don = $bdd -> prepare($req_ajd);
				$req_aj_don ->bindParam(':id_user', $_GET['id_user']);
				$req_aj_don ->bindParam(':id_classe_matiere', $clas['id_classe_matiere']);
				$req_aj_don ->execute();

				$rep[$lmat[$i]] = $clas['id_classe_matiere'];

				$i++;
			}
			$rep['reponse'] = "Success";
				echo json_encode($rep);

		}
		}else {
			$lmat = explode(',', $_GET['matieres']);
			//echo $_GET['id_user'];
			$re = "SELECT * FROM user WHERE id_user = ".$_GET['id_user'];
				$us = $bdd -> query($re);
				$user = $us->fetch();
			$re = "SELECT * FROM classe WHERE titre_classe = '".$_GET['classe']."'";
				$cl = $bdd -> query($re);
				$class = $cl->fetch();

			if($_GET['mail'] !== ''){
				$_GET['mail']=str_replace(' ','',$_GET['mail']);
			}

			$req_majun = 'UPDATE user SET nom_user = :nom_user, sexe_user = :sexe_user, avatar_user = :avatar_user, email_user = :email_user WHERE id_user = :id';
			$req_majun_us = $bdd -> prepare($req_majun);
			$req_majun_us ->bindParam(':nom_user', $_GET['nom']);
			$req_majun_us ->bindParam(':sexe_user', $_GET['sexe']);
			$req_majun_us ->bindParam(':avatar_user', $_GET['sexe']);
			$req_majun_us ->bindParam(':email_user', $_GET['mail']);
			$req_majun_us ->bindParam(':id', $_GET['id_user']);
			$req_majun_us -> execute();

			$req_ajd = "INSERT INTO user_classe (id_user, id_classe) VALUES (:id_user, :id_classe)";
				$req_aj_don = $bdd -> prepare($req_ajd);
				$req_aj_don ->bindParam(':id_user', $_GET['id_user']);
				$req_aj_don ->bindParam(':id_classe', $class['id_classe']);
				$req_aj_don ->execute();

			$i=0;
			while($i < sizeof($lmat)){
				$re = "SELECT * FROM matiere WHERE titre_matiere = '".$lmat[$i]."'";
				$ma = $bdd -> query($re);
				$mat = $ma->fetch();
				//echo $mat['id_matiere'];
				$re2 = "SELECT * FROM classe_matiere WHERE id_matiere = ".$mat['id_matiere']." AND id_classe = ".$class['id_classe'];
				$cl = $bdd -> query($re2);
				$clas = $cl->fetch();
				//echo $clas['titre_classe_matiere'];
				$req_ajd = "INSERT INTO user_matiere (id_user, id_classe_matiere) VALUES (:id_user, :id_classe_matiere)";
				$req_aj_don = $bdd -> prepare($req_ajd);
				$req_aj_don ->bindParam(':id_user', $_GET['id_user']);
				$req_aj_don ->bindParam(':id_classe_matiere', $clas['id_classe_matiere']);
				$req_aj_don ->execute();

				$rep[$lmat[$i]] = $clas['id_classe_matiere'];

				$i++;
			}
			$rep['reponse'] = "Success";
				echo json_encode($rep);
		}
	}

	function aff_un(){
		require 'connexion.php';
		$i =1;
		//$affich_el[] = '';
		$tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);
			$payss =(isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

			$reqP = "SELECT * FROM pays
															INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
			$pa = $bdd -> query($reqP);
			$pay = $pa->fetch();
			//$pa->closeCursor();
      $nbpay = $pa->rowCount();

      //var_dump($pay);
      if($nbpay !== 0 AND $pay['id_devise'] !== '1') {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
				// $ta->closeCursor();
        $tx = $taux['taux_taux'];
				$devise = $pay['signe_devise'];
      } else {
				$tx =1;
				$devise = 'FCFA';
      }
      //var_dump($tx);
    }
		$pa->closeCursor();

		if($_GET['table'] == 'son'){

      $re = "SELECT * FROM son LEFT JOIN album
										  ON son.id_album = album.id_album
										  LEFT JOIN artiste
										  ON artiste.id_artiste = son.id_artiste
										  LEFT JOIN lyric
										  ON lyric.id_son = son.id_son
                      WHERE son.id_son = ".$_GET['indx'];
			$ls = $bdd -> query($re);
			$sons = $ls -> fetch();
			$affich_el[0]=1;

			// $nbqz = $qz -> rowCount();
			//echo $re;
			// $affich_el['reponse']=1;
      $affich_el['id'] = $sons['id_son'];
      $affich_el['name'] = $sons['titre_son'];
      $affich_el['artist'] = $sons['nom_artiste'];
      $affich_el['album'] = $sons['titre_album'];
      $affich_el['url'] = $sons['fichier_son'];
      // $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
      $affich_el['cover_art_url'] = $sons['cover_artiste'];
      $affich_el['cover_url'] = $sons['cover_son'];
      $affich_el['price'] = $sons['prix_son']*$tx;
      // $affich_el['downloads'] = $nbs;
      $affich_el['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
      $affich_el['reference'] = $sons['reference_son'];
      $affich_el['dateSortie'] = $sons['dateSortie_son'];
      $affich_el['lien'] = $sons['lien_son'];
      $affich_el['devise'] = $devise;
      $affich_el['idArtist'] = $sons['id_artiste'];
			$affich_el['dateSortie'] = $sons['dateSortie_son'];

			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);

		}
    else if($_GET['table'] == 'album'){

			$re = "SELECT * FROM album INNER JOIN artiste
										  ON artiste.id_artiste = album.id_artiste
                      WHERE album.id_album = ".$_GET['indx'];
			$al = $bdd -> query($re);
			//echo $re;


			$alb = $al->fetch();
			$affich_el[0]=1;


				$affich_el['id'] = $alb['id_album'];
				$affich_el['name'] = $alb['titre_album'];
				$affich_el['artist'] = $alb['nom_artiste'];
				$affich_el['cover_art_url'] = $alb['cover_artiste'];
				$affich_el['cover_url'] = $alb['cover_album'];
				$affich_el['url'] = $alb['url_album'];
				$affich_el['price'] = $alb['prix_album']*$tx;
				$affich_el['reference'] = $alb['reference_album'];
				$affich_el['idArtist'] = $alb['id_artiste'];
				$affich_el['devise'] = $devise;
				$affich_el['dateSortie'] = $alb['dateSortie_album'];




				$affich_e = array_shift($affich_el);
				//var_dump($affich_e);
				//var_dump($affich_el);
				echo json_encode($affich_el);

		}else if($_GET['table'] == 'artiste'){

			 $re = "SELECT * FROM artiste
			 											INNER JOIN pays
														ON artiste.nationalite_artiste = pays.code_pays
														WHERE id_artiste = ".$_GET['indx'];

			 $ar = $bdd -> query($re);
			 $nbar = $ar -> rowCount();
			 //echo $re;
			 $affich_el[0]=1;


				$artis = $ar->fetch();

				$re = "SELECT * FROM son LEFT JOIN album
                        ON son.id_album = album.id_album
                        LEFT JOIN artiste
                        ON artiste.id_artiste = son.id_artiste
                        LEFT JOIN lyric
                        ON lyric.id_son = son.id_son
												LEFT JOIN soutCommande
                        ON soutCommande.libelle_soutCommande = CONCAT(son.id_son, '-son')
                        WHERE artiste.id_artiste = ".$artis[0]." AND soutCommande.fichier_soutCommande != '0' ORDER BY son.id_son DESC";
        $son = $bdd -> query($re);
				$sons = $son->fetch();

				$re1 = "SELECT * FROM album INNER JOIN artiste
  										  ON artiste.id_artiste = album.id_artiste
												LEFT JOIN soutCommande
                        ON soutCommande.libelle_soutCommande = CONCAT(album.id_album, '-album')
                        WHERE artiste.id_artiste = ".$artis[0]." AND soutCommande.fichier_soutCommande != '0' ORDER BY album.id_album DESC";
  			$al = $bdd -> query($re1);
  			$nba = $al -> rowCount();
  			// echo $re1;
				$alb = $al->fetch();
				// var_dump($alb);
				if ($sons AND $alb) {
					$dteA = (isset($alb[14])) ? $alb[14] : 0;
					$idA = (isset($alb['id_album'])) ? $alb['id_album'] : 'NULL';
					// echo ($sons['id_album'] == $idA);
					if ( ($sons['dte_enr_son'] > $dteA) AND ($sons['id_album'] !== $idA)) {
						 //echo $id;

						/*$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
						$reqspre = $bdd -> query($re2);
						$reqsprec = $reqspre->fetch();*/

						$soutientmini = $sons['minim_soutCommande']*$tx;

					} else {
						// $re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb[0]."-album'";
						// $reqspre = $bdd -> query($re2);
						// $reqsprec = $reqspre->fetch();
						$soutientmini = $alb['minim_soutCommande']*$tx;
					}
				} else if($sons) {
					$soutientmini = $sons['minim_soutCommande']*$tx;
				} elseif ($alb) {
					$soutientmini = $alb['minim_soutCommande']*$tx;
				} else {
					$soutientmini = 500*$tx;

				}



					 // $reN = "SELECT * FROM pays WHERE code_pays ='".$artis['nationalite_artiste']."'";
					 // $na = $bdd -> query($reN);
					 // $nat = $na->fetch();
					 //$j = 0;

					 $affich_el['id'] = $artis['id_artiste'];
					 $affich_el['name'] = $artis['nom_artiste'];
					 $affich_el['dob'] = $artis['dob_artiste'];
					 $affich_el['cover_url'] = $artis['cover_artiste'];
					 $affich_el['bio'] = $artis['biographie_artiste'];
					 $affich_el['natio'] = $artis['nom_pays'];
					 $affich_el['soutientmini'] = $soutientmini;

					 $i++;


				 $ar->closeCursor();
				 //$na->closeCursor();

		 $affich_e = array_shift($affich_el);
		 // shuffle($affich_el);
		 echo json_encode($affich_el);

	 }

	}

	function resultatqex(){
		require 'connexion.php';
		$ddj = date ('Y-m-d');
		if($_GET['table'] == "resultat_q"){

			$re = "SELECT * FROM resultat_q WHERE id_quizz=".$_GET['id_quizz']." AND id_user = ".$_GET['id_user'];
			$rq = $bdd -> query($re);
			$nbrqz = $rq -> rowCount();

			if($nbrqz == 1){
				$rqz = $rq -> fetch();
				$req_majun = 'UPDATE resultat_q SET score_resultat_q = :score_resultat_q, date_resultat_q = :date_resultat_q WHERE id_resultat_q = :id';
				$req_majun_us = $bdd -> prepare($req_majun);
				$req_majun_us ->bindParam(':score_resultat_q', $_GET['score']);
				$req_majun_us ->bindParam(':date_resultat_q', $ddj);
				$req_majun_us ->bindParam(':id', $rqz['id_resultat_q']);
				$req_majun_us -> execute();
			} else {
				$req_ajd = "INSERT INTO resultat_q (score_resultat_q, date_resultat_q, id_user, id_quizz) VALUES (:score_resultat_q, :date_resultat_q, :id_user, :id_quizz)";
				$req_aj_don = $bdd -> prepare($req_ajd);
				$req_aj_don ->bindParam(':score_resultat_q', $_GET['score']);
				$req_aj_don ->bindParam(':date_resultat_q', $ddj);
				$req_aj_don ->bindParam(':id_user', $_GET['id_user']);
				$req_aj_don ->bindParam(':id_quizz', $_GET['id_quizz']);
				$req_aj_don ->execute();
			}

			$rep['reponse'] = "Success";
			echo json_encode($rep);

		} else if ($_GET['table'] == "resultat_ex"){
			$re = "SELECT * FROM resultat_ex WHERE id_examen=".$_GET['id_examen']." AND id_user = ".$_GET['id_user'];
			$rq = $bdd -> query($re);
			$nbrqz = $rq -> rowCount();

			if($nbrqz == 1){
				$rqz = $rq -> fetch();
				$req_majun = 'UPDATE resultat_ex SET note_resultat_ex = :note_resultat_ex, date_resultat_ex = :date_resultat_ex WHERE id_resultat_ex = :id';
				$req_majun_us = $bdd -> prepare($req_majun);
				$req_majun_us ->bindParam(':note_resultat_ex', $_GET['score']);
				$req_majun_us ->bindParam(':date_resultat_ex', $ddj);
				$req_majun_us ->bindParam(':id', $rqz['id_resultat_ex']);
				$req_majun_us -> execute();
			} else {
				$req_ajd = "INSERT INTO resultat_ex (note_resultat_ex, date_resultat_ex, id_user, id_examen) VALUES (:note_resultat_ex, :date_resultat_ex, :id_user, :id_examen)";
				$req_aj_don = $bdd -> prepare($req_ajd);
				$req_aj_don ->bindParam(':note_resultat_ex', $_GET['score']);
				$req_aj_don ->bindParam(':date_resultat_ex', $ddj);
				$req_aj_don ->bindParam(':id_user', $_GET['id_user']);
				$req_aj_don ->bindParam(':id_examen', $_GET['id_examen']);
				$req_aj_don ->execute();
			}

			$rep['reponse'] = "Success";
			echo json_encode($rep);
		}
	}

  function getToken(){
    require 'connexion.php';

    // $tabl = $_POST['table'];
    $datee = date ('Y-m-d H:i:s');
    // var_dump($datee);
    if ($_GET['t'] == 'tokenOM' ) {
      $re = "SELECT * FROM tokenOM ORDER BY id_tokenOM DESC";
      $rq = $bdd -> query($re);
      $token = $rq -> fetch();
			// $rq->closeCursor();

      if(!isset($token['expire_tokenOM']) OR $datee > $token['expire_tokenOM']){

        // $dateTime = new DateTime($datee);
        // $dateTime->modify('+60 minutes');
        // $affich_el[0]=1;
        $my_date_time=time("Y-m-d H:i:s");

        $my_new_date_time=$my_date_time+3600;
        $dateTime=date("Y-m-d H:i:s",$my_new_date_time);

            // var_dump($dateTime);
            // $dat = $dateTime->date;
            $curl = curl_init("https://api.orange.com/oauth/v3/token");


           $headers = ["Authorization: Basic SUtFOHRvQTVpS2hxREk4ZWNiMTZSaDdrYlNjaGlSejA6cndrMjRtSnppYklJOE1QbQ==",
        "Content-Type: application/x-www-form-urlencoded"];
           $rdfXML = "grant_type=client_credentials";

            // echo $rdfXML;

           curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
           // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
           curl_setopt($curl, CURLOPT_TIMEOUT, 130);
           curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
           //curl_setopt($curl, CURLOPT_HEADER, 1);
           curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
           curl_setopt($curl, CURLOPT_POSTFIELDS, $rdfXML);
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $first_response = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
               echo "cURL Error #:" . $err;
             } else {
               $first_response = json_decode($first_response);
               // var_dump($first_response) ;
             }


        $req_ajt = "INSERT INTO tokenOM (token_tokenOM, expire_tokenOM) VALUES (:token_tokenOM, :expire_tokenOM)";
          $req_aj_tok = $bdd -> prepare($req_ajt);
          $req_aj_tok ->bindParam(':expire_tokenOM', $dateTime);
          $req_aj_tok ->bindParam(':token_tokenOM', $first_response->access_token);
          $req_aj_tok ->execute();
					// $req_aj_tok->closeCursor();

          // $affich_e = array_shift($affich_el);
          // $affich_el['reference']=$ref;
          //$affich_el['reference']=$_POST['numero'];
        } else {
          $first_response['access_token'] = $token['token_tokenOM'];
        }
    } else if($_GET['t'] == 'tokenAIRT'){
      $re = "SELECT * FROM tokenAIRT ORDER BY id_tokenAIRT DESC";
      $rq = $bdd -> query($re);
      $token = $rq -> fetch();
			// $rq->closeCursor();


          $first_response['access_token'] = $token['token_tokenAIRT'];
    }


      echo json_encode($first_response);
  }

  function positionUser(){
    $curl = curl_init();
    $opts = [
        CURLOPT_URL => 'http://ip-api.com/json/'.$_POST['ip'],
        CURLOPT_RETURNTRANSFER => true,
      //  CURLOPT_HTTPHEADER => $headers,

    ];

    curl_setopt_array($curl, $opts);

    $response = json_decode(curl_exec($curl));
    $affich_el['countryCode'] = $response -> countryCode;
    curl_close($curl);
    echo json_encode($affich_el);
  }

  function listASTG(){
    //list album son par pays
    require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');
    $devise = "FCFA";

    $re = "SELECT * FROM son LEFT JOIN album
                    ON son.id_album = album.id_album
                    LEFT JOIN artiste
                    ON artiste.id_artiste = son.id_artiste
                    LEFT JOIN lyric
                    ON lyric.id_son = son.id_son
                    WHERE son.date_verif <= '".$dateAuj."' AND artiste.nationalite_artiste = 'TG' AND son.is_active = 1 ORDER BY son.dateSortie_son DESC, son.id_son DESC";

    $son = $bdd -> query($re);
    $nbson = $son -> rowCount();

    $reAlb = "SELECT * FROM album INNER JOIN artiste
                    ON artiste.id_artiste = album.id_artiste WHERE album.date_verif <= '".$dateAuj."' AND artiste.nationalite_artiste = 'TG' AND album.is_active = 1 ORDER BY album.dateSortie_album DESC, album.id_album DESC";

    $al = $bdd -> query($reAlb);
    $nba = $al -> rowCount();

    $nbBcle = ($nbson > $nba) ? $nbson : $nba;
    $a = 0;
    $i = 1;
    $affich_el[0]=1;
    if (isset($_GET['all']) AND $_GET['all']==1) {
      $b = 1000000000;
    } else {
      $b = 15;
    }
		$idxpass='0';
    while ($a < $nbBcle AND $a <=$b) {
      $sons = $son->fetch();
			if ($sons['id_artiste'] !== $idxpass) {
				if (isset($sons['0'])) {

					$affich_el[$i]['id'] = $sons['0'];
					$affich_el[$i]['name'] = $sons['2'];
					$affich_el[$i]['artist'] = $sons['nom_artiste'];
					$affich_el[$i]['artistID'] = $sons['id_artiste'];
					$affich_el[$i]['album'] = $sons['titre_album'];
					$affich_el[$i]['url'] = $sons['fichier_son'];
					// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
					$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el[$i]['cover_url'] = $sons['cover_son'];
					$affich_el[$i]['price'] = $sons['prix_son']*$tx;
					$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el[$i]['reference'] = $sons['reference_son'];
					$affich_el[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el[$i]['lien'] = $sons['lien_son'];
					$affich_el[$i]['devise'] = $devise;

					$i++;
					$idxpass = ($a%2 ==0) ? $sons['id_artiste'] : $idxpass;
				}
				$a++;
			} else {
				$idxpass = $sons['id_artiste'];
			}

      // $alb = $al->fetch();
      // if (isset($alb['id_album'])) {
			// 	$affich_el[$i]['id'] = $alb['id_album'];
			// 	$affich_el[$i]['name'] = $alb['titre_album'];
			// 	$affich_el[$i]['artist'] = $alb['nom_artiste'];
			// 	$affich_el[$i]['cover_art_url'] = $alb['cover_artiste'];
			// 	$affich_el[$i]['cover_url'] = $alb['cover_album'];
			// 	$affich_el[$i]['url'] = $alb['url_album'];
			// 	$affich_el[$i]['price'] = $alb['prix_album']*$tx;
			// 	$affich_el[$i]['reference'] = $alb['reference_album'];
			// 	$affich_el[$i]['dateSortie'] = $alb['dateSortie_album'];
			// 	$affich_el[$i]['lien'] = $alb['lien_album'];
      //   $affich_el[$i]['devise'] = $devise;
			//
			// 	$i++;
      // }


    }
		$son->closeCursor();
		$al->closeCursor();
    $affich_e = array_shift($affich_el);
    shuffle($affich_el);
    echo json_encode($affich_el);


  }

  function listPPOPU(){
    require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 0;
		//tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');
    //$devise = "FCFA";
		$tabl = $_GET['table'];
		$tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);
			$payss =(isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

			$reqP = "SELECT * FROM pays
															INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
			$pa = $bdd -> query($reqP);
			$pay = $pa->fetch();
			$pa->closeCursor();
			$devise = $pay['signe_devise'];
      $nbpay = $pa->rowCount();
			$pa->closeCursor();

      //var_dump($pay);
      if($nbpay !== 0 AND $pay['id_devise'] !== '1') {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
				// $ta->closeCursor();
        $tx = $taux['taux_taux'];
      }
      //var_dump($tx);
    }

    $req1 ="SELECT COUNT('libelle_transaction') AS 'nb',`libelle_transaction` FROM ( SELECT DISTINCT (`reference_transaction`),`libelle_transaction` FROM transaction where `statut_transaction`='SUCCESS' AND libelle_transaction LIKE '%".$tabl."%') AS T1 GROUP BY `libelle_transaction` ORDER BY `nb` DESC LIMIT 15";

    $pop = $bdd -> query($req1);
    // $nbson = $son -> rowCount();

    while ($popu = $pop->fetch() ){
      $tablp = explode('-',$popu['libelle_transaction']);
      // var_dump ($tablp);
      if ($tabl == 'son') {

        $re = "SELECT * FROM son LEFT JOIN album
  										  ON son.id_album = album.id_album
  										  LEFT JOIN artiste
  										  ON artiste.id_artiste = son.id_artiste
  										  LEFT JOIN lyric
  										  ON lyric.id_son = son.id_son
                        WHERE son.id_son = ".$tablp[2];
  			$son = $bdd -> query($re);
  			$nbson = $son -> rowCount();
  			//echo $re;

  			$sons = $son->fetch();
  				 // var_dump ($sons);

  				//$j = 0;
  				$affich_el[$i]['id'] = $sons['0'];
  				$affich_el[$i]['name'] = $sons['titre_son'];
  				$affich_el[$i]['artist'] = $sons['nom_artiste'];
  				$affich_el[$i]['album'] = $sons['titre_album'];
  				$affich_el[$i]['url'] = $sons['url_son'];
  				$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
  				$affich_el[$i]['cover_url'] = $sons['cover_son'];
  				$affich_el[$i]['price'] = $sons['prix_son']*$tx;
  				$affich_el[$i]['devise'] = $devise;
  				$affich_el[$i]['f'] = $sons['fichier_son'];
  				$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
  				$affich_el[$i]['reference'] = $sons['reference_son'];
  				$affich_el[$i]['lien'] = $sons['lien_son'];
  				$affich_el[$i]['idArtist'] = $sons['id_artiste'];

        $i++;
      } else if ($tablp == 'album') {
        $re = "SELECT * FROM album INNER JOIN artiste
  										  ON artiste.id_artiste = album.id_artiste
                        WHERE album.id_album = ".$tablp[2];
  			$al = $bdd -> query($re);
  			$nba = $al -> rowCount();
  			//echo $re;


  			$alb = $al->fetch();

  				$j = 0;
    			// var_dump($alb);
  				$affich_el[$i]['id'] = $alb['id_album'];
  				$affich_el[$i]['name'] = $alb['titre_album'];
  				$affich_el[$i]['artist'] = $alb['nom_artiste'];
  				$affich_el[$i]['cover_art_url'] = $alb['cover_artiste'];
  				$affich_el[$i]['cover_url'] = $alb['cover_album'];
  				$affich_el[$i]['url'] = $alb['url_album'];
  				$affich_el[$i]['f'] = $alb['fichier_album'];
  				$affich_el[$i]['price'] = $alb['prix_album'];
  				$affich_el[$i]['reference'] = $alb['reference_album'];
  				$affich_el[$i]['devise'] = $devise;

        $i++;
      }

    }
		$pop->closeCursor();
    $affich_e = array_shift($affich_el);
    shuffle($affich_el);
    echo json_encode($affich_el);

  }

	function recommandation(){
		require 'connexion.php';
		$i =0;
		$nbs =0;
		$nba =0;
		$nbt =0;
		$idxgr1 = 0;
		$idxgr2 = 0;
		$affich_el[] = '';
		$affich_el1[] = '';
		$affich_el2[] = '';
		$affich_el3[] = '';
		$devise = "FCFA";
    $dateAuj = date ('Y-m-d H:i:s');
    $tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);
			$payss =(isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

			$reqP = "SELECT * FROM pays
															INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
			$pa = $bdd -> query($reqP);
			$pay = $pa->fetch();
			$pa->closeCursor();
			$devise = $pay['signe_devise'];
      $nbpay = $pa->rowCount();
			$pa->closeCursor();

      //var_dump($pay);
      if($nbpay !== 0 AND $pay['id_devise'] !== '1') {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
				// $ta->closeCursor();
        $tx = $taux['taux_taux'];
      }
      //var_dump($tx);
    }

					if ($_GET['table'] == "artiste") {
						$re1 = "SELECT * FROM artiste INNER JOIN son
																					ON artiste.id_artiste = son.id_artiste
																					WHERE artiste.id_artiste = '".$_GET['indx']."'";
					} else {
						$re1 = "SELECT * FROM ".$_GET['table']." WHERE ".$_GET['table'].".id_".$_GET['table']." = '".$_GET['indx']."'";
						 // echo $re1;
					}

					$obj = $bdd -> query($re1);
					$obj = $obj->fetch();
					// var_dump($obj);
					switch ($obj['id_genre']) {
						case '1':
							$idxgr1 = 6;
							$idxgr2 = 9;
							break;

						case '2':
							$idxgr1 = 6;
							break;

						case '3':
							$idxgr1 = 9;
							$idxgr2 = 6;
							break;

						case '4':
							$idxgr1 = 8;
							break;

						case '5':
							$idxgr1 = 2;
							break;

						case '6':
							$idxgr1 = 9;
							$idxgr2 = 8;
							// echo '6';
							break;

						case '7':
							$idxgr1 = 6;
							$idxgr2 = 8;
							break;

						case '8':
							$idxgr1 = 6;
							$idxgr2 = 4;
							break;

						case '9':
							$idxgr1 = 3;
							$idxgr2 = 6;
							break;

						case '10':
							$idxgr1 = 6;
							break;

						case '11':
							$idxgr1 = 6;
							break;

						default:
							$idxgr1 = 6;
							break;
					}

		if ($_GET['table'] == 'son') {


			$re2 = "SELECT * FROM son LEFT JOIN album
										  ON son.id_album = album.id_album
										  LEFT JOIN artiste
										  ON artiste.id_artiste = son.id_artiste
                      WHERE son.date_verif <= '".$dateAuj."' AND son.id_genre=".$obj['id_genre']." AND son.is_active = 1 AND son.id_son !=".$obj['id_son']." ORDER BY RAND()";
			$son2 = $bdd -> query($re2);
			$nbson = $son2 -> rowCount();

			$re3 = "SELECT * FROM son LEFT JOIN album
										  ON son.id_album = album.id_album
										  LEFT JOIN artiste
										  ON artiste.id_artiste = son.id_artiste
                      WHERE son.date_verif <= '".$dateAuj."' AND son.id_genre=".$idxgr1." AND son.is_active = 1 ORDER BY RAND()";
			$son3 = $bdd -> query($re3);
			$nbson3 = $son3 -> rowCount();

			if ($idxgr2 !== 0) {
				$re4 = "SELECT * FROM son LEFT JOIN album
											  ON son.id_album = album.id_album
											  LEFT JOIN artiste
											  ON artiste.id_artiste = son.id_artiste
	                      WHERE son.date_verif <= '".$dateAuj."' AND son.id_genre=".$idxgr2." AND son.is_active = 1 ORDER BY RAND()";
				$son4 = $bdd -> query($re4);
				// echo $re4;
				$nbson4 = $son4 -> rowCount();
			}

			$affich_el[0]=1;

			$i = 1;
			while ($i < 4) {
				$sons = $son2->fetch();
				// var_dump($sons);
				if (isset($sons[0])) {
					$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
					$reqspre = $bdd -> query($re2);
					$reqsprec = $reqspre->fetch();

					$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
					$nb = $bdd -> query($re1);
					$nbs = $nb->rowCount();

					$affich_el1[$i]['id'] = $sons['0'];
					$affich_el1[$i]['name'] = $sons['2'];
					$affich_el1[$i]['artist'] = $sons['nom_artiste'];
					$affich_el1[$i]['idArtist'] = $sons['id_artiste'];
					$affich_el1[$i]['album'] = $sons['titre_album'];
					$affich_el1[$i]['url'] = $sons['fichier_son'];
					// $affich_el[$i]['url'] = $sons['url_son']);
					$affich_el1[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el1[$i]['cover_url'] = $sons['cover_son'];
					$affich_el1[$i]['price'] = $sons['prix_son']*$tx;
					$affich_el1[$i]['downloads'] = $nbs;
					$affich_el1[$i]['reference'] = $sons['reference_son'];
					$affich_el1[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el1[$i]['lien'] = $sons['lien_son'];
					$affich_el1[$i]['genre'] = $sons['13'];
					$affich_el1[$i]['devise'] = $devise;
					$affich_el1[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
					$affich_el1[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
				}


				$i++;
			}
			while($i > 3 AND $i < 8) {
				if ($i>3 AND $idxgr2 ==0) {
					$sons = $son3->fetch();
					$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
					$reqspre = $bdd -> query($re2);
					$reqsprec = $reqspre->fetch();

					$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
					$nb = $bdd -> query($re1);
					$nbs = $nb->rowCount();

					$affich_el2[$i]['id'] = $sons['0'];
					$affich_el2[$i]['name'] = $sons['2'];
					$affich_el2[$i]['artist'] = $sons['nom_artiste'];
					$affich_el2[$i]['idArtist'] = $sons['id_artiste'];
					$affich_el2[$i]['album'] = $sons['titre_album'];
					$affich_el2[$i]['url'] = $sons['fichier_son'];
					// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
					$affich_el2[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el2[$i]['cover_url'] = $sons['cover_son'];
					$affich_el2[$i]['price'] = $sons['prix_son']*$tx;
					$affich_el2[$i]['downloads'] = $nbs;
					$affich_el2[$i]['reference'] = $sons['reference_son'];
					$affich_el2[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el2[$i]['lien'] = $sons['lien_son'];
					$affich_el2[$i]['genre'] = $sons['13'];
					$affich_el2[$i]['devise'] = $devise;
					$affich_el2[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
					$affich_el2[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
				} else {
					if ($i>3 AND $i<6) {
						$sons = $son3->fetch();
						$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
						$reqspre = $bdd -> query($re2);
						$reqsprec = $reqspre->fetch();

						$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
						$nb = $bdd -> query($re1);
						$nbs = $nb->rowCount();

						$affich_el2[$i]['id'] = $sons['0'];
						$affich_el2[$i]['name'] = $sons['2'];
						$affich_el2[$i]['artist'] = $sons['nom_artiste'];
						$affich_el2[$i]['idArtist'] = $sons['id_artiste'];
						$affich_el2[$i]['album'] = $sons['titre_album'];
						$affich_el2[$i]['url'] = $sons['fichier_son'];
						// $affich_el[$i]['url'] = $sons['url_son']);
						$affich_el2[$i]['cover_art_url'] = $sons['cover_artiste'];
						$affich_el2[$i]['cover_url'] = $sons['cover_son'];
						$affich_el2[$i]['price'] = $sons['prix_son']*$tx;
						$affich_el2[$i]['downloads'] = $nbs;
						$affich_el2[$i]['reference'] = $sons['reference_son'];
						$affich_el2[$i]['dateSortie'] = $sons['dateSortie_son'];
						$affich_el2[$i]['lien'] = $sons['lien_son'];
						$affich_el2[$i]['genre'] = $sons['13'];
						$affich_el2[$i]['devise'] = $devise;
						$affich_el2[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
						$affich_el2[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
					} else {
						$sons = $son4->fetch();
						// var_dump($sons);
						$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
						$reqspre = $bdd -> query($re2);
						$reqsprec = $reqspre->fetch();

						// $re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
						// $nb = $bdd -> query($re1);
						// $nbs = $nb->rowCount();

						$affich_el3[$i]['id'] = $sons['0'];
						$affich_el3[$i]['name'] = $sons['2'];
						$affich_el3[$i]['artist'] = $sons['nom_artiste'];
						$affich_el3[$i]['idArtist'] = $sons['id_artiste'];
						$affich_el3[$i]['album'] = $sons['titre_album'];
						$affich_el3[$i]['url'] = $sons['fichier_son'];
						// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
						$affich_el3[$i]['cover_art_url'] = $sons['cover_artiste'];
						$affich_el3[$i]['cover_url'] = $sons['cover_son'];
						$affich_el3[$i]['price'] = $sons['prix_son']*$tx;
						$affich_el3[$i]['downloads'] = 0;
						$affich_el3[$i]['reference'] = $sons['reference_son'];
						$affich_el3[$i]['dateSortie'] = $sons['dateSortie_son'];
						$affich_el3[$i]['lien'] = $sons['lien_son'];
						$affich_el3[$i]['genre'] = $sons['13'];
						$affich_el3[$i]['devise'] = $devise;
						$affich_el3[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
						$affich_el3[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
					}
				}
				$i++;
			}

			$affich_e = array_shift($affich_el1);
			$affich_e = array_shift($affich_el2);
			$affich_e = array_shift($affich_el3);
			//var_dump($affich_e);
			// var_dump($affich_el3);
			// var_dump($affich_el2);

			// echo json_encode($affich_el);
			shuffle($affich_el2);
			shuffle($affich_el3);
			$affich_el = array_merge($affich_el1, $affich_el2, $affich_el3);
			echo json_encode($affich_el);

		}
		elseif ($_GET['table'] == "album") {

			$re2 = "SELECT * FROM album LEFT JOIN artiste
													  ON artiste.id_artiste = album.id_artiste
			                      WHERE album.date_verif <= '".$dateAuj."' AND album.id_genre=".$obj['id_genre']." AND album.is_active = 1 AND album.id_album !=".$_GET['indx']." ORDER BY RAND()";
						$album2 = $bdd -> query($re2);
						$nbalbum = $album2 -> rowCount();

						$re3 = "SELECT * FROM album LEFT JOIN artiste
													  ON artiste.id_artiste = album.id_artiste
			                      WHERE album.date_verif <= '".$dateAuj."' AND album.id_genre=".$idxgr1." AND album.is_active = 1 ORDER BY RAND()";
						$album3 = $bdd -> query($re3);
						// echo $re3;
						$nbalbum3 = $album3 -> rowCount();

						if ($idxgr2 !== 0) {
							$re4 = "SELECT * FROM album LEFT JOIN artiste
														  ON artiste.id_artiste = album.id_artiste
				                      WHERE album.date_verif <= '".$dateAuj."' AND album.id_genre=".$idxgr2." AND album.is_active = 1 ORDER BY RAND()";
							$album4 = $bdd -> query($re4);
							$nbalbum4 = $album4 -> rowCount();
						}

						$affich_el[0]=1;

						$i = 1;
						while ($i < 4) {
							$alb = $album2->fetch();
							$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
							$reqspre = $bdd -> query($re2);
							$reqsprec = $reqspre->fetch();

							$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
							$nb = $bdd -> query($re1);
							$nba = $nb->rowCount();

							$affich_el1[$i]['id'] = $alb['id_album'];
							$affich_el1[$i]['name'] = $alb['titre_album'];
							$affich_el1[$i]['artist'] = $alb['nom_artiste'];
							$affich_el1[$i]['cover_art_url'] = $alb['cover_artiste'];
							$affich_el1[$i]['idArtist'] = $alb['id_artiste'];
							$affich_el1[$i]['cover_url'] = $alb['cover_album'];
							$affich_el1[$i]['url'] = $alb['fichier_album'];
							$affich_el1[$i]['price'] = $alb['prix_album']*$tx;
							$affich_el1[$i]['downloads'] = $nba;
							$affich_el1[$i]['reference'] = $alb['reference_album'];
							$affich_el1[$i]['dateSortie'] = $alb['dateSortie_album'];
							$affich_el1[$i]['lien'] = $alb['lien_album'];
			        $affich_el1[$i]['devise'] = $devise;
			        $affich_el1[$i]['genre'] = $alb['11'];
							$affich_el1[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 3000*$tx;
							$affich_el1[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;

							$i++;
						}
						while($i > 3 AND $i < 8) {
							if ($i>3 AND $idxgr2 ==0) {
								$alb = $album3->fetch();
								// var_dump($alb);
								if (isset($alb['id_album'])) {
									$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
									$reqspre = $bdd -> query($re2);
									$reqsprec = $reqspre->fetch();

									$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
									$nb = $bdd -> query($re1);
									$nba = $nb->rowCount();
									$affich_el2[$i]['id'] = $alb['id_album'];
									$affich_el2[$i]['name'] = $alb['titre_album'];
									$affich_el2[$i]['artist'] = $alb['nom_artiste'];
									$affich_el2[$i]['idArtist'] = $alb['id_artiste'];
									$affich_el2[$i]['cover_art_url'] = $alb['cover_artiste'];
									$affich_el2[$i]['cover_url'] = $alb['cover_album'];
									$affich_el2[$i]['url'] = $alb['fichier_album'];
									$affich_el2[$i]['price'] = $alb['prix_album']*$tx;
									$affich_el2[$i]['downloads'] = $nba;
									$affich_el2[$i]['reference'] = $alb['reference_album'];
									$affich_el2[$i]['dateSortie'] = $alb['dateSortie_album'];
									$affich_el2[$i]['lien'] = $alb['lien_album'];
					        $affich_el2[$i]['devise'] = $devise;
					        $affich_el2[$i]['genre'] = $alb['11'];
									$affich_el2[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 3000*$tx;
									$affich_el2[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
								}

							} else {
								if ($i>3 AND $i<6) {
									$alb = $album3->fetch();
									$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
									$reqspre = $bdd -> query($re2);
									$reqsprec = $reqspre->fetch();

									$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
									$nb = $bdd -> query($re1);
									$nba = $nb->rowCount();
									$affich_el2[$i]['id'] = $alb['id_album'];
									$affich_el2[$i]['name'] = $alb['titre_album'];
									$affich_el2[$i]['artist'] = $alb['nom_artiste'];
									$affich_el2[$i]['idArtist'] = $alb['id_artiste'];
									$affich_el2[$i]['cover_art_url'] = $alb['cover_artiste'];
									$affich_el2[$i]['cover_url'] = $alb['cover_album'];
									$affich_el2[$i]['url'] = $alb['fichier_album'];
									$affich_el2[$i]['price'] = $alb['prix_album']*$tx;
									$affich_el2[$i]['downloads'] = $nba;
									$affich_el2[$i]['reference'] = $alb['reference_album'];
									$affich_el2[$i]['dateSortie'] = $alb['dateSortie_album'];
									$affich_el2[$i]['lien'] = $alb['lien_album'];
					        $affich_el2[$i]['devise'] = $devise;
					        $affich_el2[$i]['genre'] = $alb['11'];
									$affich_el2[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 3000*$tx;
									$affich_el2[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
								} else {
									$alb = $album4->fetch();
									$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
									$reqspre = $bdd -> query($re2);
									$reqsprec = $reqspre->fetch();

									$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
									$nb = $bdd -> query($re1);
									$nba = $nb->rowCount();
									$affich_el3[$i]['id'] = $alb['id_album'];
									$affich_el3[$i]['name'] = $alb['titre_album'];
									$affich_el3[$i]['artist'] = $alb['nom_artiste'];
									$affich_el3[$i]['idArtist'] = $alb['id_artiste'];
									$affich_el3[$i]['cover_art_url'] = $alb['cover_artiste'];
									$affich_el3[$i]['cover_url'] = $alb['cover_album'];
									$affich_el3[$i]['url'] = $alb['fichier_album'];
									$affich_el3[$i]['price'] = $alb['prix_album']*$tx;
									$affich_el3[$i]['downloads'] = $nba;
									$affich_el3[$i]['reference'] = $alb['reference_album'];
									$affich_el3[$i]['dateSortie'] = $alb['dateSortie_album'];
									$affich_el3[$i]['lien'] = $alb['lien_album'];
					        $affich_el3[$i]['devise'] = $devise;
					        $affich_el3[$i]['genre'] = $alb['11'];
									$affich_el3[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 3000*$tx;
									$affich_el3[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;
								}
							}
							$i++;
						}

						$affich_e = array_shift($affich_el1);
						$affich_e = array_shift($affich_el2);
						$affich_e = array_shift($affich_el3);
						//var_dump($affich_e);
						// var_dump($affich_el3);
						// var_dump($affich_el2);

						// echo json_encode($affich_el);
						shuffle($affich_el2);
						shuffle($affich_el3);
						$affich_el = array_merge($affich_el1, $affich_el2, $affich_el3);
						echo json_encode($affich_el);
		}
		elseif ($_GET['table'] == "artiste") {

			$re2 = "SELECT artiste.id_artiste, artiste.nom_artiste, artiste.dob_artiste, artiste.cover_artiste, artiste.biographie_artiste, artiste.lien_artiste, son.id_genre FROM artiste INNER JOIN son
										  			ON son.id_artiste = son.id_artiste
														INNER JOIN pays
														ON artiste.nationalite_artiste = pays.code_pays
													  WHERE artiste.date_verif <= '".$dateAuj."' AND son.id_genre=".$obj['id_genre']." AND artiste.is_active = 1 AND artiste.id_artiste !=".$obj['id_artiste']." GROUP BY artiste.id_artiste ORDER BY RAND()";
						$artist2 = $bdd -> query($re2);
						$nbartist = $artist2 -> rowCount();

						$re3 = "SELECT artiste.id_artiste, artiste.nom_artiste, artiste.dob_artiste, artiste.cover_artiste, artiste.biographie_artiste, artiste.lien_artiste, son.id_genre FROM artiste INNER JOIN son
													  ON artiste.id_artiste = son.id_artiste
														INNER JOIN pays
														ON artiste.nationalite_artiste = pays.code_pays
			                      WHERE artiste.date_verif <= '".$dateAuj."' AND son.id_genre=".$idxgr1." AND artiste.is_active = 1 GROUP BY artiste.id_artiste ORDER BY RAND()";
						$artist3 = $bdd -> query($re3);
						// echo $re3;
						$nbartist3 = $artist3 -> rowCount();

						if ($idxgr2 !== 0) {
							$re4 = "SELECT artiste.id_artiste, artiste.nom_artiste, artiste.dob_artiste, artiste.cover_artiste, artiste.biographie_artiste, artiste.lien_artiste, son.id_genre FROM artiste INNER JOIN son
														  ON artiste.id_artiste = son.id_artiste
															INNER JOIN pays
															ON artiste.nationalite_artiste = pays.code_pays
				                      WHERE artiste.date_verif <= '".$dateAuj."' AND son.id_genre=".$idxgr2." AND artiste.is_active = 1 GROUP BY artiste.id_artiste ORDER BY RAND()";
							$artist4 = $bdd -> query($re4);
							$nbartist4 = $artist4 -> rowCount();
						}

						$affich_el[0]=1;

						$i = 1;
						while ($i < 4) {
							$artist = $artist2->fetch();
							// $re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
							// $reqspre = $bdd -> query($re2);
							// $reqsprec = $reqspre->fetch();
							// $reN = "SELECT * FROM pays WHERE code_pays ='".$artist['nationalite_artiste']."'";
							// $na = $bdd -> query($reN);
							// $nat = $na->fetch();


							$affich_el1[$i]['id'] = $artist['id_artiste'];
							$affich_el1[$i]['name'] = $artist['nom_artiste'];
							$affich_el1[$i]['dob'] = $artist['dob_artiste'];
							$affich_el1[$i]['cover_url'] = $artist['cover_artiste'];
							$affich_el1[$i]['bio'] = $artist['biographie_artiste'];
							$affich_el1[$i]['natio'] = $artist['nom_pays'];
							$affich_el1[$i]['lien'] = $artist['lien_artiste'];
			        $affich_el1[$i]['genre'] = $artist['id_genre'];
							// $affich_el1[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande'] : 3000;
							// $affich_el1[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande'] : 0;

							$i++;
						}
						while($i > 3 AND $i < 8) {
							if ($i>3 AND $idxgr2 ==0) {
								$artist = $artist3->fetch();
								// $reN = "SELECT * FROM pays WHERE code_pays ='".$artist['nationalite_artiste']."'";
				  			// $na = $bdd -> query($reN);
				        // $nat = $na->fetch();
								// var_dump($alb);
								if (isset($artist['id_artiste'])) {

									$affich_el2[$i]['id'] = $artist['id_artiste'];
									$affich_el2[$i]['name'] = $artist['nom_artiste'];
									$affich_el2[$i]['dob'] = $artist['dob_artiste'];
									$affich_el2[$i]['cover_url'] = $artist['cover_artiste'];
									$affich_el2[$i]['bio'] = $artist['biographie_artiste'];
									$affich_el2[$i]['natio'] = $artist['nom_pays'];
									$affich_el2[$i]['lien'] = $artist['lien_artiste'];
					        $affich_el2[$i]['genre'] = $artist['id_genre'];
								}

							} else {
								if ($i>3 AND $i<6) {
									$artist = $artist3->fetch();
									// $reN = "SELECT * FROM pays WHERE code_pays ='".$artist['nationalite_artiste']."'";
					  			// $na = $bdd -> query($reN);
					        // $nat = $na->fetch();

									$affich_el2[$i]['id'] = $artist['id_artiste'];
									$affich_el2[$i]['name'] = $artist['nom_artiste'];
									$affich_el2[$i]['dob'] = $artist['dob_artiste'];
									$affich_el2[$i]['cover_url'] = $artist['cover_artiste'];
									$affich_el2[$i]['bio'] = $artist['biographie_artiste'];
									$affich_el2[$i]['natio'] = $artist['nom_pays'];
									$affich_el2[$i]['lien'] = $artist['lien_artiste'];
					        $affich_el2[$i]['genre'] = $artist['id_genre'];

								} else {
									$artist = $artist4->fetch();
									// $reN = "SELECT * FROM pays WHERE code_pays ='".$artist['nationalite_artiste']."'";
					  			// $na = $bdd -> query($reN);
					        // $nat = $na->fetch();

									$affich_el3[$i]['id'] = $artist['id_artiste'];
									$affich_el3[$i]['name'] = $artist['nom_artiste'];
									$affich_el3[$i]['dob'] = $artist['dob_artiste'];
									$affich_el3[$i]['cover_url'] = $artist['cover_artiste'];
									$affich_el3[$i]['bio'] = $artist['biographie_artiste'];
									$affich_el3[$i]['natio'] = $artist['nom_pays'];
									$affich_el3[$i]['lien'] = $artist['lien_artiste'];
					        $affich_el3[$i]['genre'] = $artist['id_genre'];

								}
							}
							$i++;
						}

						$affich_e = array_shift($affich_el1);
						$affich_e = array_shift($affich_el3);
						//var_dump($affich_e);
						 // var_dump($affich_el3);
						// var_dump($affich_el2);

						// echo json_encode($affich_el);
						shuffle($affich_el2);
						shuffle($affich_el3);
						$affich_el = array_merge($affich_el1, $affich_el2, $affich_el3);
						echo json_encode($affich_el);
		}



	}

	function achatWal(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$re = "SELECT * FROM transaction INNER JOIN transactionWallet
																		ON transactionWallet.id_transaction = transaction.id_transaction
																		WHERE transaction.reference_transaction = '".$_GET['ref']."'";
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
		$elt = $un->fetch();

		$wal = $elt['libelle_transactionWallet'];

		$re = "SELECT * FROM ".$wal." WHERE id_".$wal." = ".$elt['id_wallet'];
		$rq = $bdd -> query($re);
		$wall = $rq -> fetch();
		if ($elt['statut_transaction'] !== "SUCCESS") {

			if($elt['montant_transaction'] <= $wall['solde_'.$wal]) {

				$statutt = "SUCCESS";
				$solde = $wall['solde_'.$wal] - $elt['montant_transaction'];

				$req_msol = 'UPDATE '.$wal.' SET solde_'.$wal.'=:solde WHERE id_'.$wal.' = :id';
				$req_msold = $bdd -> prepare($req_msol);
				$req_msold ->bindParam(':solde', $solde);
				$req_msold ->bindParam(':id', $wall['id_'.$wal]);
				$req_msold -> execute();
				$rep['error'] = 0;
				$rep['solde'] = $solde;

				$req_majd = "UPDATE transaction SET statut_transaction=:statut_transaction WHERE id_transaction=:id_transaction";
				$req_maj_don = $bdd -> prepare($req_majd);
				//$req_maj_don ->bindParam(':montant_transaction', $amount);
				$req_maj_don ->bindParam(':statut_transaction', $statutt);
				$req_maj_don ->bindParam(':id_transaction', $elt['id_transaction']);
				$req_maj_don ->execute();

			} else {
				$rep['error'] = 1;
				$rep['solde'] = $wall['solde_'.$wal];

			}
		} else {
			$rep['error'] = 1;
			$rep['solde'] = $wall['solde_'.$wal];

		}
		echo json_encode($rep);

	}

  function rechargTrsx(){
    require 'connexion.php';
    $tabl = isset($_GET['table']) ? $_GET['table'] : $_POST['table'];
		$_POST['montant'] = isset($_GET['montant']) ? $_GET['montant'] : $_POST['montant'];
		$_POST['numero'] = isset($_GET['numero']) ? $_GET['numero'] : $_POST['numero'];
		$_POST['indicatif'] = isset($_GET['indicatif']) ? $_GET['indicatif'] : $_POST['indicatif'];
		$_POST['wallet'] = isset($_GET['wallet']) ? $_GET['wallet'] : $_POST['wallet'];
		$wal = $tabl;
		// $wal = ($_POST['wallet'] == 'wa1') ? 'walletP' : 'walletB';
		// $_POST['mail'] = $_GET['mail'];
		// $_POST['message'] = $_GET['message'];
		$_POST['id'] = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

    $_POST['numero'] = str_replace(' ','',$_POST['numero']);
    $datee = $ddj = date ('Y-m-d H:i:s');
    $ref = "AFPRCH-";
    $ref .= genererChaineAleatoire();
    $statutt = "ATTENTE";
    $affich_el[0]=1;
    $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'rechargement' ");
    // $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'rechargement' ");
     $trsax = $req->fetch();

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        //var_dump($ip);
    $reP = "SELECT * FROM pays INNER JOIN devise
                              ON devise.id_devise = pays.id_devise
                              WHERE pays.indicatif_pays = ".$_POST['indicatif']."
                              ORDER BY pays.nom_pays";
		$lpays = $bdd -> query($reP);
    $lpays = $lpays->fetch();

    $re = "SELECT * FROM ".$wal." WHERE id_user = ".$_POST['id'];
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
		$eltw = $un->fetch();
    // var_dump($re);
    // if ($lpays['id_devise'] == $eltw['id_devise']) {
    $montant = $_POST['montant'];
    // } else {
		// 	$reT = "SELECT * FROM taux WHERE from_taux = ".$lpays['id_devise']." AND to_taux = ".$eltw['id_devise'];
		// 	$Tx = $bdd -> query($reT);
	  //   $taux = $Tx->fetch();
    //   $montant = $_POST['montant']*$taux['taux_taux'];
    // }
		$_POST['numero'] = str_replace(" ", "", $_POST['numero']);

    $req_ajc = "INSERT INTO rechargement (date_rechargement, telephone_rechargement, montant_rechargement, reference_rechargement, statut_rechargement, id_pays, id_user, id_walletP) VALUES (:date_rechargement, :telephone_rechargement, :montant_rechargement, :reference_rechargement, :statut_rechargement, :id_pays, :id_user, :id_walletP)";
			$req_aj_cod = $bdd -> prepare($req_ajc);
			$req_aj_cod ->bindParam(':date_rechargement', $datee);
			$req_aj_cod ->bindParam(':telephone_rechargement', $_POST['numero']);
			$req_aj_cod ->bindParam(':montant_rechargement', $montant);
			$req_aj_cod ->bindParam(':reference_rechargement', $ref);
			$req_aj_cod ->bindParam(':statut_rechargement', $statutt);
			$req_aj_cod ->bindParam(':id_pays', $lpays['id_pays']);
			$req_aj_cod ->bindParam(':id_user', $_POST['id']);
			$req_aj_cod ->bindParam(':id_walletP', $eltw['id_'.$wal]);
			$req_aj_cod ->execute();



      if(substr($_POST['numero'], 0, 2) == '00'){
        if (substr($_POST['numero'], 2, 3) == $_GET['indicatif']) {
          $indRsx = substr($_POST['numero'], 5, 2);
        } else {
          $indRsx = substr($_POST['numero'], 2, 2);
        }
      } else {
        if (substr($_POST['numero'], 0, 3) == $_GET['indicatif']) {
          $indRsx = substr($_POST['numero'], 3, 2);
        } else {
          $indRsx = substr($_POST['numero'], 0, 2);
        }
      }
      // echo $indRsx;

        $detail = "N/A";
      if ($_GET['indicatif'] == '225') {
        $integ = 2;
        if ($indRsx == '01') {
          $detail = "Flooz CI";
        } else if ($indRsx == '05') {
          $detail = "MOMO CI";

        } else if ($indRsx == '07') {
          $detail = "OM CI";
        }

      } else if ($_GET['indicatif'] == '229') {
        $integ = 3;
        $detail = "N/A";

      } else if ($_GET['indicatif'] == '000') {
        $integ = 1;
        $detail = "N/A";

      } else if ($_GET['indicatif'] == '224') {


        if ($indRsx == '62') {
          $detail = "OM GN";
          $integ = 7;
        } else if ($indRsx == '05') {
          $detail = "MOMO GN";
          $integ = 4;

        }

      } else if ($_GET['indicatif'] == '241') {

          $detail = "N/A";
          $integ = 5;


      } else if ($_GET['indicatif'] == '228') {

        if (isset($_GET['mvafp'])) {
          $detail = $_GET['mvafp'];
          $integ = 6;
        } else {
          $detail = "N/A";
          $integ = 2;

        }


      } else if ($_GET['indicatif'] == '221' OR $_GET['indicatif'] == '226' OR $_GET['indicatif'] == '223' OR $_GET['indicatif'] == '237' OR $_GET['indicatif'] == '243') {

          $detail = "N/A";
          $integ = 2;

      }
	  if(isset($_GET['pc']) AND $_GET['pc'] == '1'){
		  $integ = 1;
        $detail = "N/A";
	  }


      $req_ajit = "INSERT INTO integrateur_rechargement (id_rechargement, id_integrateur, detail_integrateur_rechargement) VALUES (:id_rechargement, :id_integrateur, :detail_integrateur_rechargement)";
      // echo $detail;
      $req_aj_it = $bdd -> prepare($req_ajit);
      $req_aj_it ->bindParam(':id_rechargement', $trsax['Auto_increment']);
      $req_aj_it ->bindParam(':id_integrateur', $integ);
      $req_aj_it ->bindParam(':detail_integrateur_rechargement', $detail);
      // var_dump($req_aj_it);
      $req_aj_it ->execute();

      $affich_e = array_shift($affich_el);
      $affich_el['reference']=$ref;
      //$affich_el['reference']=$_POST['numero'];
      echo json_encode($affich_el);
  }

	function majsolde($montant, $table, $idW, $src, $idsrc){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$re = "SELECT * FROM ".$table." WHERE id_".$table." = ".$idW;
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
		$wall = $un->fetch();

		if(isset($montant) AND $montant < 0) {
			$solde = $wall['solde_'.$wal] + $montant;

			$req_msol = 'UPDATE '.$wal.' SET solde_'.$wal.'=:sold WHERE id_'.$wal.' = :id';
			$req_msold = $bdd -> prepare($req_msol);
			$req_msold ->bindParam(':solde_'.$wal, $solde);
			$req_msold ->bindParam(':id', $wall['id_'.$wal]);
			$req_msold -> execute();
			$rep['error'] = 0;
			$rep['solde'] = $solde;

			if ($table == "walletB") {
				$req_ajb = "INSERT INTO bonus (montant_bonus, date_bonus, libelle_bonus, id_ext, id_walletB) VALUES (:montant_bonus, :date_bonus, :libelle_bonus, :id_ext, :id_walletB)";
	      // echo $detail;
	      $req_aj_b = $bdd -> prepare($req_ajb);
	      $req_aj_b ->bindParam(':montant_bonus', $montant);
	      $req_aj_b ->bindParam(':date_bonus', $dateAuj);
	      $req_aj_b ->bindParam(':libelle_bonus', $src);
	      $req_aj_b ->bindParam(':id_ext', $idsrc);
	      $req_aj_b ->bindParam(':id_walletB', $idW);
	      // var_dump($req_aj_it);
	      $req_aj_b ->execute();
			}

		} else {
			$rep['error'] = 1;
			$rep['solde'] = $wall['solde_'.$wal];
		}
		echo json_encode($rep);
	}

	function newPlaylist(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');
		$reqAut = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'userplaylist' ");
    // $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'forfaitTrsx' ");
     $trsax = $reqAut->fetch();


		$req_ajpl = "INSERT INTO userplaylist (nom_userplaylist, dte_enr, id_user) VALUES (:nom_userplaylist, :dte_enr, :id_user)";
		// echo $detail;
		$req_ajplt = $bdd -> prepare($req_ajpl);
		$req_ajplt ->bindParam(':nom_userplaylist', $_POST['playlist']);
		$req_ajplt ->bindParam(':dte_enr', $dateAuj);
		$req_ajplt ->bindParam(':id_user', $_POST['idU']);
		// var_dump($req_aj_it);
		$req_ajplt ->execute();

		$affich_el['statut'] = 'success';
		$affich_el['id'] = $trsax['Auto_increment'];
		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);
	}

	function delPlaylist(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$req_dpl = "DELETE FROM userplaylist WHERE id_userplaylist=:id_userplaylist";
		// echo $detail;
		$req_dplt = $bdd -> prepare($req_dpl);
		$req_dplt ->bindParam(':id_userplaylist', $_POST['idplst']);
		// var_dump($req_aj_it);
		$req_dplt ->execute();

		$affich_el['statut'] = 'success';
		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);

	}

	function ajouSonPlaylist(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$reP = "SELECT * FROM userplaylist_son WHERE id_userplaylist = ".$_POST['idplst']." ORDER BY id_userplaylist_son DESC";
		$plst = $bdd -> query($reP);
    $plsts = $plst->fetch();
		$pos = (isset($plsts['position_userplaylist_son'])) ? $plsts['position_userplaylist_son']+1 : 1;

		$req_ajpl = "INSERT INTO userplaylist_son (dte_enr, position_userplaylist_son, id_userplaylist, id_son) VALUES (:dte_enr, :position_userplaylist_son, :id_userplaylist, :id_son)";
		// echo $detail;
		$req_ajplt = $bdd -> prepare($req_ajpl);
		$req_ajplt ->bindParam(':dte_enr', $dateAuj);
		$req_ajplt ->bindParam(':position_userplaylist_son', $pos);
		$req_ajplt ->bindParam(':id_userplaylist', $_POST['idplst']);
		$req_ajplt ->bindParam(':id_son', $_POST['son']);
		// var_dump($req_aj_it);
		$req_ajplt ->execute();

		$affich_el['statut'] = 'success';
		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);
	}

	function ajouSonFavoris(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$reqAut = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'userplaylist' ");
    // $req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'forfaitTrsx' ");
    $trsax = $reqAut->fetch();

		$rePl = "SELECT * FROM userplaylist WHERE id_user = ".$_POST['idU']." AND nom_userplaylist ='Favoris'";
 		$pls = $bdd -> query($rePl);
    $plst = $pls->fetch();

		if (isset($plst['id_user'])) {
			$reP = "SELECT * FROM userplaylist_son WHERE id_userplaylist = ".$plst['id_userplaylist']." ORDER BY id_userplaylist_son DESC";
			$plsts = $bdd -> query($reP);
	    $plstson = $plsts->fetch();
			$pos = (isset($plstson['position_userplaylist_son'])) ? $plstson['position_userplaylist_son']+1 : 1;
			$idplst = $plst['id_userplaylist'];

		} else {
			$nomPl = 'Favoris';
			$req_ajpl = "INSERT INTO userplaylist (nom_userplaylist, dte_enr, id_user) VALUES (:nom_userplaylist, :dte_enr, :id_user)";
			// echo $detail;
			$req_ajplt = $bdd -> prepare($req_ajpl);
			$req_ajplt ->bindParam(':nom_userplaylist', $nomPl);
			$req_ajplt ->bindParam(':dte_enr', $dateAuj);
			$req_ajplt ->bindParam(':id_user', $_POST['idU']);
			// var_dump($req_aj_it);
			$req_ajplt ->execute();
			$idplst = $trsax['Auto_increment'];
			// $affich_el['statut'] = 'success';
			$affich_el['id'] = $idplst;
			$pos = 1;


		}





		$req_ajpl = "INSERT INTO userplaylist_son (dte_enr, position_userplaylist_son, id_userplaylist, id_son) VALUES (:dte_enr, :position_userplaylist_son, :id_userplaylist, :id_son)";
		// echo $detail;
		$req_ajplt = $bdd -> prepare($req_ajpl);
		$req_ajplt ->bindParam(':dte_enr', $dateAuj);
		$req_ajplt ->bindParam(':position_userplaylist_son', $pos);
		$req_ajplt ->bindParam(':id_userplaylist', $idplst);
		$req_ajplt ->bindParam(':id_son', $_POST['son']);
		// var_dump($req_aj_it);
		$req_ajplt ->execute();

		$affich_el['statut'] = 'success';
		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);
	}

	function delSonPlaylist(){
		require 'connexion.php';
		$i =0;
		$affich_el[] = '';
		$sonPos[] = '';
		$affich_el[0]=1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$reP1 = "SELECT * FROM userplaylist_son WHERE id_userplaylist = ".$_POST['idplst']." AND id_son = ".$_POST['son'];
		$plst1 = $bdd -> query($reP1);
    $plsts1 = $plst1->fetch();
		//var_dump($plsts1);

		$reP = "SELECT * FROM userplaylist_son WHERE id_userplaylist = ".$_POST['idplst']." AND position_userplaylist_son > ".$plsts1['position_userplaylist_son']." ORDER BY id_userplaylist_son DESC";
		//var_dump($reP);
		$plst = $bdd -> query($reP);
		// $posplsts = $plst->count();

		$req_dpl = "DELETE FROM userplaylist_son WHERE id_userplaylist_son=:id_userplaylist_son";
		// echo $detail;
		$req_dplt = $bdd -> prepare($req_dpl);
		$req_dplt ->bindParam(':id_userplaylist_son', $plsts1['id_userplaylist_son']);
		// var_dump($req_aj_it);
		$req_dplt ->execute();

		$lim = $posplsts - $plsts1['position_userplaylist_son'];
		while ($plsts = $plst->fetch()) {

			$posi = $plsts['position_userplaylist_son'] - 1;
			$req_msol = 'UPDATE userplaylist_son SET position_userplaylist_son=:position_userplaylist_son WHERE id_userplaylist_son =:id_userplaylist_son';
			$req_msold = $bdd -> prepare($req_msol);
			$req_msold ->bindParam(':position_userplaylist_son', $posi);
			$req_msold ->bindParam(':id_userplaylist_son', $plsts['id_userplaylist_son']);
			$req_msold -> execute();
			$i++;

		}

		$affich_el['statut'] = 'success';
		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);

	}

	function connectClt(){
		require 'connexion.php';
		$i =0;
		$affich_el[] = '';
		$affich_el[0]=1;
    $dateAuj =  date ('Y-m-d H:i:s');

		if (isset($_POST['password']) AND isset($_POST['email'])) // Si les variables existent.
	  {

			//On r??cup??re les donn??es envoy??es par la m??thode POST du formulaire d'identification.
	    $mot_de_passe = md5(htmlentities($_POST['password'], ENT_QUOTES, "ISO-8859-1")); // le htmlentities() passera les guillemets en entit??s HTML, ce qui emp??chera les injections SQL
	    $login = htmlentities($_POST['email'], ENT_QUOTES, "ISO-8859-1");
	    $typeClt = htmlentities($_POST['id_typeClient'], ENT_QUOTES, "ISO-8859-1");
	     // echo $login;
	    $ip = $_SERVER['REMOTE_ADDR'];

	    //$bdd = new Cbdd('localhost', 'cmsbdd', 'root', '');
	    //$bdd->connexion('localhost', 'cmsbdd', 'root', '');
	    // le mots de passe par defaut est '@freek@2020'


	     $requet = $bdd -> query("SELECT * FROM client WHERE client.is_active = 1 AND client.email_client = '".$login."' AND client.password_client = '".$mot_de_passe."'");



	    $colon = $requet -> rowCount();

	    if( $colon == 1) {

	      // Si le mot de passe et le login sont bons (valable pour 1 utilisateur ou plus). J'ai mis plusieurs identifiants et mots de passe.
	      $clientI = $requet -> fetch();
				$requet2 = $bdd -> query("SELECT * FROM client_typeClient WHERE id_client = '".$clientI["id_client"]."'");

	      $typclient = $requet2 -> fetch();
				$typeClt = $typclient['id_typeClient'];
	      // var_dump($clientI);

	      $user1I["id_typeClient"] = $typeClt;
	      $_SESSION['typClt']=$user1I["id_typeClient"];

	      if ($user1I["id_typeClient"] == 3) {
	        // PRODUCTEUR dkdfree@gmail.com

	        $requet2 = $bdd -> query('SELECT * FROM maison_client WHERE id_client =\''.$clientI["id_client"].'\'');
	        $colon2 = $requet2 -> rowCount();

	        $producteurI = $requet2 -> fetch();
	         // var_dump($producteurI);

	        $affich_el['connect']=1; // Change la valeur de la variable connect. C'est elle qui nous permettra de savoir s'il y a eu identification.
	        // $affich_el['email']=$login;// Permet de r??cup??rer le login afin de personnaliser la navigation.
	        // $_SESSION['password'] = $mot_de_passe;
	        $affich_el['ip_addr'] = $ip;
	        $affich_el["user"] = $clientI["nom_client"];
	        // $affich_el["userCompte"] = 'PRODUCTEUR';
	        $affich_el["userID"] = $clientI["id_client"];
	        $affich_el["user_mail"] = $clientI["email_client"];
	        $affich_el["id"] = $producteurI["id_maison"];
					$affich_el['type']="Label/Distributeur";

	      }elseif($user1I["id_typeClient"] == 4) {
	        // ARTISTE

	        $requet3 = $bdd -> query('SELECT * FROM artiste_client WHERE id_client =\''.$clientI["id_client"].'\'');
	        $colon3 = $requet3 -> rowCount();

	        $colon3 = $requet3 -> rowCount();
	        $artisteI = $requet3 -> fetch();

	        $affich_el['connect']=1; // Change la valeur de la variable connect. C'est elle qui nous permettra de savoir s'il y a eu identification.
	        // $affich_el['email']=$login;// Permet de r??cup??rer le login afin de personnaliser la navigation.
	        // $_SESSION['password'] = $mot_de_passe;
	        $affich_el['ip_addr'] = $ip;
	        $affich_el["user"] = $clientI["nom_client"];
	        $affich_el["userID"] = $clientI["id_client"];
	        $affich_el["user_mail"] = $clientI["email_client"];
	        $affich_el["id"] = $artisteI["id_artiste"];

					$affich_el['type']="Artiste";

	      }
			// 	elseif($user1I["id_typeClient"] == 2) {
	    //     // APPORTEUR D'AFFAIRE
			//
	    //     $requet4 = $bdd -> query('SELECT * FROM apporteur_client WHERE id_client =\''.$clientI["id_client"].'\'');
	    //     $colon4 = $requet4 -> rowCount();
			//
	    //     $colon4 = $requet4 -> rowCount();
	    //     $apporteurI = $requet4 -> fetch();
			//
	    //     $affich_el['connect']=1; // Change la valeur de la variable connect. C'est elle qui nous permettra de savoir s'il y a eu identification.
	    //     $affich_el['email']=$login;// Permet de r??cup??rer le login afin de personnaliser la navigation.
	    //     // $_SESSION['password'] = $mot_de_passe;
	    //     $affich_el['ip_addr'] = $ip;
	    //     $affich_el["user"] = $clientI["nom_client"];
	    //     $affich_el["userCompte"] = 'APPORTEUR D\'AFFAIRE';
	    //     $affich_el["userID"] = $clientI["id_client"];
	    //     $affich_el["user_mail"] = $clientI["email_client"];
	    //     $affich_el["apporteurID"] = $apporteurI["id_apporteur"];
			//
			//
	    //   }elseif($user1I["id_typeClient"] == 1) {
	    //     // RESPONSABLE PAYS
			//
	    //     $requet3 = $bdd -> query('SELECT * FROM pays_client WHERE id_client =\''.$clientI["id_client"].'\'');
	    //     $colon3 = $requet3 -> rowCount();
			//
	    //     $colon3 = $requet3 -> rowCount();
	    //     $resppaysI = $requet3 -> fetch();
			//
	    //     $_SESSION['connect']=1; // Change la valeur de la variable connect. C'est elle qui nous permettra de savoir s'il y a eu identification.
	    //     $_SESSION['email']=$login;// Permet de r??cup??rer le login afin de personnaliser la navigation.
	    //     // $_SESSION['password'] = $mot_de_passe;
	    //     $_SESSION['ip_addr'] = $ip;
	    //     $_SESSION["user"] = $clientI["nom_client"];
	    //     $_SESSION["userCompte"] = 'RESPONSABLE PAYS';
	    //     $_SESSION["userID"] = $clientI["id_client"];
	    //     $_SESSION["user_mail"] = $clientI["email_client"];
	    //     $_SESSION["resppaysID"] = $resppaysI["id_pays"];
			//
			//
	    //   } elseif($user1I["id_typeClient"] == 0) {
			//
	    //           $requet4 = $bdd -> query('SELECT * FROM admin WHERE email_admin =\''.$login.'\' AND password_admin =\''.$mot_de_passe.'\'');
	    //           $colon4 = $requet4 -> rowCount();
			//
	    //           if( $colon4 == 1) {
			//
	    //             $_SESSION['connect']=1;
	    //             $_SESSION['email']=$login;
	    //             // $_SESSION['password'] = $mot_de_passe;
	    //             $_SESSION['ip_addr'] = $ip;
	    //             $clientI = $requet4 -> fetch();
	    //             $_SESSION["user"] = $clientI["nom_admin"];
	    //             $_SESSION["userCompte"] = 'ADMINISTRATEUR';
	    //             $_SESSION["userID"] = $clientI["id_admin"];
	    //             $_SESSION["user_mail"] = $clientI["email_admin"];
	    //             $_SESSION["is_admin"] = 1;
			//
	    //             // header("location: acceuil.php");
	    //   				}
			//
	    // }
	    $_SESSION["nmdp"] = ($_POST['password'] == "@freek@2020") ? 1 : 0;
	    $affich_el['nmdp'] = ($_POST['password'] == "@freek@2020") ? 1 : 0;
	    } else {

				$affich_el['error']="2";
				$affich_el['msg']="Informations incorrectes";

	    }
	  } else {

			$affich_el['error']="1";
			$affich_el['msg']="Pas de donn??es recu";
	  }
		$affich_e = array_shift($affich_el);
		// echo json_encode($affich_el);
		echo json_encode($affich_el);
		// echo json_last_error_msg();
	}

	function hotSonAlb(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //  CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
       // var_dump($response);
			 $payss = (isset($response -> countryCode) AND $response -> countryCode !== NULL) ? $response -> countryCode : "CI";

			 $reqP = "SELECT * FROM pays
 															INNER JOIN devise
 															ON devise.id_devise = pays.id_devise
 															WHERE code_pays ='".$payss."'";
 			$pa = $bdd -> query($reqP);
 			$pay = $pa->fetch();
 			$pa->closeCursor();
 			$devise = $pay['signe_devise'];


      if(isset($pay['id_devise']) AND $pay['id_devise'] !== '1') {
          $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
          $ta = $bdd -> query($reqT);
          $taux = $ta->fetch();
          $nbtx = $ta->rowCount();

          if ($nbtx !== 0) {
            $tx = $taux['taux_taux'];
          }
        } else {
          $pay['id_devise'] = 1;
        }
      //var_dump($tx);
    }
    // $reqD = "SELECT * FROM devise WHERE id_devise =".$pay['id_devise'];
    // $dv = $bdd -> query($reqD);
    // $dvse = $dv->fetch();
    // $devise = $dvse['signe_devise'];


		$idxH = 0;
		$i = 1;
		while ($idxH <= 6) {
			//$reqH = "SELECT * FROM ".$_GET['table']." WHERE position_".$_GET['table']." = ".$idxH." AND dateDeb_".$_GET['table']." <= '".$dateAuj."' AND dateFin_".$_GET['table']." >= '".$dateAuj."' ORDER BY id_".$_GET['table']." ASC";
			$reqH = "SELECT * FROM ".$_GET['table']." WHERE position_".$_GET['table']." = ".$idxH." ORDER BY id_".$_GET['table']." DESC";
	    $ht = $bdd -> query($reqH);
	    $hot = $ht->fetch();
			  //echo $reqH;
			 //var_dump($hot);

			if (isset($hot[0])) {
				if($_GET['table'] == 'hotson'){
					$re = "SELECT * FROM son LEFT JOIN album
		 										  ON son.id_album = album.id_album
		 										  LEFT JOIN artiste
		 										  ON artiste.id_artiste = son.id_artiste
		                      WHERE son.date_verif <= '".$dateAuj."' AND son.is_active = 1 AND son.id_son = ".$hot['id_son'];

		 			$son = $bdd -> query($re);

		 			$sons = $son->fetch();
		 				    // var_dump ($sons);

	 				$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
	 				$reqspre = $bdd -> query($re2);
	 				$reqsprec = $reqspre->fetch();

	 				//$j = 0;
	 				 //echo "<br>".$i;

					$affich_el[$i]['id'] = $sons['0'];
					$affich_el[$i]['name'] = $sons['2'];
					$affich_el[$i]['artist'] = $sons['nom_artiste'];
					$affich_el[$i]['album'] = $sons['titre_album'];
					$affich_el[$i]['url'] = $sons['fichier_son'];
					// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
					$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el[$i]['cover_url'] = $sons['cover_son'];
					$affich_el[$i]['price'] = $sons['prix_son']*$tx;
					// $affich_el[$i]['downloads'] = $nbs;
					$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el[$i]['reference'] = $sons['reference_son'];
					$affich_el[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el[$i]['lien'] = $sons['lien_son'];
					$affich_el[$i]['genre'] = $sons['13'];
					$affich_el[$i]['devise'] = $devise;
					$affich_el[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
					$affich_el[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;





	 			}
				elseif ($_GET['table'] == 'hotalbum') {
					$re = "SELECT * FROM album INNER JOIN artiste
												  ON artiste.id_artiste = album.id_artiste WHERE album.date_verif <= '".$dateAuj."' AND album.is_active = 1 AND album.id_album = ".$hot['id_album'];

					$al = $bdd -> query($re);



					$alb = $al->fetch();

					$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$alb['id_album']."-album'";
					$reqspre = $bdd -> query($re2);
					$reqsprec = $reqspre->fetch();

					$affich_el[$i]['id'] = $alb['id_album'];
					$affich_el[$i]['name'] = $alb['titre_album'];
					$affich_el[$i]['artist'] = $alb['nom_artiste'];
					$affich_el[$i]['cover_art_url'] = $alb['cover_artiste'];
					$affich_el[$i]['cover_url'] = $alb['cover_album'];
					$affich_el[$i]['url'] = $alb['fichier_album'];
					$affich_el[$i]['price'] = $alb['prix_album']*$tx;
					// $affich_el[$i]['downloads'] = $nba;
					$affich_el[$i]['reference'] = $alb['reference_album'];
					$affich_el[$i]['dateSortie'] = $alb['dateSortie_album'];
					$affich_el[$i]['lien'] = $alb['lien_album'];
	        $affich_el[$i]['devise'] = $devise;
	        $affich_el[$i]['genre'] = $alb['11'];
					$affich_el[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande'] : 3000;
					$affich_el[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande'] : 0;



				}


				$i++;
			} else {
				// if ($idxH == 0){
				// 	$affich_el[$i]="";
				// }
				//$idxH = 7;
			}
			$idxH++;



		}


		 $affich_e = array_shift($affich_el);
		 // $affich_e = array_shift($affich_el3);
		 //var_dump($affich_e);
		 // var_dump($affich_el3);
		 // var_dump($affich_el2);

		 // echo json_encode($affich_el);
		 // shuffle($affich_el2);
		 // $affich_el = array_merge($affich_el3, $affich_el2);
		 echo json_encode($affich_el);
			// echo json_last_error_msg();
	}

	function forfaitTrsx(){
    require 'connexion.php';
    $tabl = 'forfaitTrsx';
		//$_POST['montant'] = isset($_GET['montant']) ? $_GET['montant'] : $_POST['montant'];
		$_POST['numero'] = isset($_GET['numero']) ? $_GET['numero'] : $_POST['numero'];
		$_POST['indicatif'] = isset($_GET['indicatif']) ? $_GET['indicatif'] : $_POST['indicatif'];
		// $_POST['id'] = isset($_GET['forfait']) ? $_GET['forfait'] : $_POST['forfait'];
		$forf = $tabl;

		$_POST['id'] = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
		$_POST['idU'] = isset($_GET['idU']) ? $_GET['idU'] : $_POST['idU'];

    $_POST['numero'] = str_replace(' ','',$_POST['numero']);
    $datee = $ddj = date ('Y-m-d H:i:s');
    $ref = "AFPFOR-";
    $ref .= genererChaineAleatoire();
    $statutt = "ATTENTE";
    $affich_el[0]=1;
    $req = $bdd->query("SHOW TABLE STATUS FROM afrekply LIKE 'forfaitTrsx' ");
     //$req = $bdd->query("SHOW TABLE STATUS FROM afreekaplay LIKE 'forfaitTrsx' ");
     $trsax = $req->fetch();

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

    $reP = "SELECT * FROM pays INNER JOIN devise
                              ON devise.id_devise = pays.id_devise
                              WHERE pays.indicatif_pays = ".$_POST['indicatif']."
                              ORDER BY pays.nom_pays";
		$lpays = $bdd -> query($reP);
    $lpays = $lpays->fetch();


    $reqf = "SELECT * FROM forfaitStream WHERE id_forfaitStream = ".$_POST['id'];
		$unf = $bdd -> query($reqf);

		$eltf = $unf->fetch();

    //$montant = $eltf['prix_forfaitStream'];
		//echo $lpays['taux_taux'];

		if ($lpays['taux_taux'] == 0.1 OR $lpays['taux_taux'] == 0.4) {
			$montant = $eltf['prix_forfaitStream'];
			//echo $montant;
		} else {
			$montant = $eltf['prix_forfaitStream']*$lpays['taux_taux'];
		}

		$_POST['numero'] = str_replace(" ", "", $_POST['numero']);

    $req_ajc = "INSERT INTO forfaitTrsx (date_forfaitTrsx, telephone_forfaitTrsx, montant_forfaitTrsx, reference_forfaitTrsx, statut_forfaitTrsx, id_pays, id_user, id_forfaitStream) VALUES (:date_forfaitTrsx, :telephone_forfaitTrsx, :montant_forfaitTrsx, :reference_forfaitTrsx, :statut_forfaitTrsx, :id_pays, :id_user, :id_forfaitStream)";
			$req_aj_cod = $bdd -> prepare($req_ajc);
			$req_aj_cod ->bindParam(':date_forfaitTrsx', $datee);
			$req_aj_cod ->bindParam(':telephone_forfaitTrsx', $_POST['numero']);
			$req_aj_cod ->bindParam(':montant_forfaitTrsx', $montant);
			$req_aj_cod ->bindParam(':reference_forfaitTrsx', $ref);
			$req_aj_cod ->bindParam(':statut_forfaitTrsx', $statutt);
			$req_aj_cod ->bindParam(':id_pays', $lpays['id_pays']);
			$req_aj_cod ->bindParam(':id_user', $_POST['idU']);
			$req_aj_cod ->bindParam(':id_forfaitStream', $eltf['id_forfaitStream']);
			$req_aj_cod ->execute();



      if(substr($_POST['numero'], 0, 2) == '00'){
        if (substr($_POST['numero'], 2, 3) == $_GET['indicatif']) {
          $indRsx = substr($_POST['numero'], 5, 2);
        } else {
          $indRsx = substr($_POST['numero'], 2, 2);
        }
      } else {
        if (substr($_POST['numero'], 0, 3) == $_GET['indicatif']) {
          $indRsx = substr($_POST['numero'], 3, 2);
        } else {
          $indRsx = substr($_POST['numero'], 0, 2);
        }
      }
      // echo $indRsx;

        $detail = "N/A";
      if ($_GET['indicatif'] == '225') {
        $integ = 2;
        if ($indRsx == '01') {
          $detail = "Flooz CI";
        } else if ($indRsx == '05') {
          $detail = "MOMO CI";

        } else if ($indRsx == '07') {
          $detail = "OM CI";
        }

      } else if ($_GET['indicatif'] == '229') {
        $integ = 3;
        $detail = "N/A";

      } else if ($_GET['indicatif'] == '000') {
        $integ = 1;
        $detail = "N/A";

      } else if ($_GET['indicatif'] == '224') {


        if ($indRsx == '62') {
          $detail = "OM GN";
          $integ = 7;
        } else if ($indRsx == '05') {
          $detail = "MOMO GN";
          $integ = 4;

        }

      } else if ($_GET['indicatif'] == '241') {

          $detail = "N/A";
          $integ = 5;


      } else if ($_GET['indicatif'] == '228') {

        if (isset($_GET['mvafp'])) {
          $detail = $_GET['mvafp'];
          $integ = 6;
        } else {
          $detail = "N/A";
          $integ = 2;

        }


      } else if ($_GET['indicatif'] == '221' OR $_GET['indicatif'] == '226' OR $_GET['indicatif'] == '223' OR $_GET['indicatif'] == '237' OR $_GET['indicatif'] == '243') {

          $detail = "N/A";
          $integ = 2;

      }
	  if(isset($_GET['pc']) AND $_GET['pc'] == '1'){
		  $integ = 1;
        $detail = "N/A";
	  }


      $req_ajit = "INSERT INTO integrateur_forfaitTrsx (id_forfaitTrsx, id_integrateur, detail_integrateur_forfaitTrsx) VALUES (:id_forfaitTrsx, :id_integrateur, :detail_integrateur_forfaitTrsx)";
      // echo $detail;
      $req_aj_it = $bdd -> prepare($req_ajit);
      $req_aj_it ->bindParam(':id_forfaitTrsx', $trsax['Auto_increment']);
      $req_aj_it ->bindParam(':id_integrateur', $integ);
      $req_aj_it ->bindParam(':detail_integrateur_forfaitTrsx', $detail);
      // var_dump($req_aj_it);
      $req_aj_it ->execute();



      $affich_e = array_shift($affich_el);
      $affich_el['reference']=$ref;
      //$affich_el['reference']=$_POST['numero'];
      echo json_encode($affich_el);
  }


	function souscription_forfait(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$reqF = "SELECT * FROM forfaitStream WHERE id_forfaitStream =".$_POST['forfait'];
		// echo $reqF;
		$for = $bdd -> query($reqF);
		$forfait = $for->fetch();
		$nbScd = $forfait['temps_forfaitStream']*86400;
		$datee2Ts = date ('Y-m-d H:i:s', time()+$nbScd);
		// Moyen de paiement 1 mobile mo, 0 Airtime
		$renouv = ($_POST["moyenPaie"] == 1) ? 0 : 1;
		$moyenPaie = ($_POST["moyenPaie"] == 1) ? "MM" : "AIRT";

		$req_ajuf = "INSERT INTO user_forfaitStream (dateDeb_user_forfaitStream, dateFin_user_forfaitStream, renouvellement_user_forfaitStream, moyenPaie_user_forfaitStream, id_user, id_forfaitStream) VALUES (:dateDeb_user_forfaitStream, :dateFin_user_forfaitStream, :renouvellement_user_forfaitStream, :moyenPaie_user_forfaitStream, :id_user, :id_forfaitStream)";
			$req_aj_uf = $bdd -> prepare($req_ajuf);
			$req_aj_uf ->bindParam(':dateDeb_user_forfaitStream', $dateAuj);
			$req_aj_uf ->bindParam(':dateFin_user_forfaitStream', $datee2Ts);
			$req_aj_uf ->bindParam(':renouvellement_user_forfaitStream', $renouv);
			$req_aj_uf ->bindParam(':moyenPaie_user_forfaitStream', $moyenPaie);
			$req_aj_uf ->bindParam(':id_user', $_POST["id_user"]);
			$req_aj_uf ->bindParam(':id_forfaitStream', $_POST["forfait"]);
			$req_aj_uf ->execute();
			$affich_el['error']=0;
			$affich_e = array_shift($affich_el);
			// echo json_encode($affich_el);
			echo json_encode($affich_el);
	}

	function verif_souscription(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;
		$affich_el['actif'] = 0;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$reqF = "SELECT * FROM user_forfaitStream
															INNER JOIN forfaitStream
															ON forfaitStream.id_forfaitStream = user_forfaitStream.id_forfaitStream
															WHERE user_forfaitStream.id_user =".$_GET['user']." AND dateFin_user_forfaitStream > '".$dateAuj."' ORDER BY user_forfaitStream.id_user_forfaitStream DESC";
		$for = $bdd -> query($reqF);

		$dat0 = $dateAuj;
		while ($forfait = $for->fetch()) {
			if (isset($forfait["id_user_forfaitStream"])) {
				if ($dat0 < $forfait["dateFin_user_forfaitStream"]) {
					$affich_el['actif'] = 1;
					$affich_el['debut'] = $forfait["dateDeb_user_forfaitStream"];
					$affich_el['fin'] = $forfait["dateFin_user_forfaitStream"];
					$affich_el['methode'] = $forfait["moyenPaie_user_forfaitStream"];
					$affich_el['renouvellementAuto'] = $forfait["renouvellement_user_forfaitStream"];
					$affich_el['forfait'] = $forfait["nom_forfaitStream"];
					$affich_el['cout'] = $forfait["prix_forfaitStream"];
					$affich_el['ddj'] = $dateAuj;
					$affich_el['idForfait'] = $forfait["id_forfaitStream"];

					$dat0 = $forfait["dateFin_user_forfaitStream"];
				}

			} else {
				$affich_el['actif'] = 0;
				$reqF2 = "SELECT * FROM user_forfaitStream
																	INNER JOIN forfaitStream
																	ON forfaitStream.id_forfaitStream = user_forfaitStream.id_forfaitStream
																	WHERE user_forfaitStream.id_user =".$_GET['user']." ORDER BY id_user_forfaitStream DESC";
				$for2 = $bdd -> query($reqF2);
				$forfait2 = $for2->fetch();
				if (isset($forfait2["id_user_forfaitStream"])) {
					$affich_el['debut'] = $forfait2["dateDeb_user_forfaitStream"];
					$affich_el['fin'] = $forfait2["dateFin_user_forfaitStream"];
					$affich_el['methode'] = $forfait2["moyenPaie_user_forfaitStream"];
					$affich_el['renouvellementAuto'] = $forfait2["renouvellement_user_forfaitStream"];
					$affich_el['forfait'] = $forfait2["nom_forfaitStream"];
					$affich_el['cout'] = $forfait2["prix_forfaitStream"];
					$affich_el['idForfait'] = $forfait2["id_forfaitStream"];
					$affich_el['ddj'] = $dateAuj;
				}

			}
		}


		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);



	}


	function list_souscription(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$reqF = "SELECT * FROM user_forfaitStream
															INNER JOIN forfaitStream
															ON forfaitStream.id_forfaitStream = user_forfaitStream.id_forfaitStream
															WHERE user_forfaitStream.id_user =".$_GET['user']." AND dateFin_user_forfaitStream >= '".$dateAuj."' ORDER BY user_forfaitStream.id_user_forfaitStream DESC";
		$for = $bdd -> query($reqF);
		$i=1;
			while ($forfait = $for->fetch()) {
				$affich_el[$i]['actif'] = 1;
				$affich_el[$i]['debut'] = $forfait["dateDeb_user_forfaitStream"];
				$affich_el[$i]['fin'] = $forfait["dateFin_user_forfaitStream"];
				$affich_el[$i]['methode'] = $forfait["moyenPaie_user_forfaitStream"];
				$affich_el[$i]['renouvellementAuto'] = $forfait["renouvellement_user_forfaitStream"];
				$affich_el[$i]['forfait'] = $forfait["nom_forfaitStream"];
				$affich_el[$i]['cout'] = $forfait["prix_forfaitStream"];
				$affich_el[$i]['ddj'] = $dateAuj;
				$affich_el[$i]['idForfait'] = $forfait["id_forfaitStream"];
				$i++;
			}



		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);



	}

	function lecture(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');
		$nbScd = 180;
		$datee2Ts = date ('Y-m-d H:i:s', time()-$nbScd);

		$reqF = "SELECT * FROM user_forfaitStream WHERE id_user = ".$_POST['idU']." AND dateFin_user_forfaitStream >= '".$dateAuj."'";
		$for = $bdd -> query($reqF);
		$forfait = $for->fetch();


		if (isset($forfait["id_user_forfaitStream"])) {

			$reqL = "SELECT * FROM lectureSon WHERE id_son =".$_POST['idSon']." AND id_user_forfaitStream =".$forfait['id_user_forfaitStream']." ORDER BY id_lectureSon DESC";
			$le = $bdd -> query($reqL);
			$lect = $le->fetch();

			if ($lect["id_lectureSon"] == null OR ($lect["id_lectureSon"] !== null AND $lect['date_lectureSon'] < $datee2Ts)) {
				$req_ajuf = "INSERT INTO lectureSon (date_lectureSon, id_son, id_user_forfaitStream) VALUES (:date_lectureSon, :id_son, :id_user_forfaitStream)";
					$req_aj_uf = $bdd -> prepare($req_ajuf);
					$req_aj_uf ->bindParam(':date_lectureSon', $dateAuj);
					$req_aj_uf ->bindParam(':id_son', $_POST["idSon"]);
					$req_aj_uf ->bindParam(':id_user_forfaitStream', $forfait['id_user_forfaitStream']);
					$req_aj_uf ->execute();
					$affich_el['error'] = 0;
					$affich_el['fin'] = $forfait["dateFin_user_forfaitStream"];
			} else {
				$affich_el['error'] = 2;
			}

		} else {
			$affich_el['error'] = 1;

		}
		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);
	}

	function genres(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;

		$reqg = "SELECT * FROM genre";
		$genr = $bdd -> query($reqg);

		$i=1;
		while ($genress = $genr->fetch()) {
			$affich_el[$i]['idGenre'] = $genress['id_genre'];
	    $affich_el[$i]["titre"] = $genress["titre_genre"];
      $affich_el[$i]["cover"] = $genress["cover_genre"];
			$i++;
		}

		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);


	}

  function rechApi(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');

    $devise = 'FCFA';
		$mSearch = $_POST['rech'];
		// var_dump($_POST);
		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //  CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
       // var_dump($response);
			 $payss = (isset($response -> countryCode) AND $response -> countryCode !== NULL) ? $response -> countryCode : "CI";

			 $reqP = "SELECT * FROM pays
 															INNER JOIN devise
 															ON devise.id_devise = pays.id_devise
 															WHERE code_pays ='".$payss."'";
 			$pa = $bdd -> query($reqP);
 			$pay = $pa->fetch();
 			$pa->closeCursor();
 			$devise = $pay['signe_devise'];


      if(isset($pay['id_devise']) AND $pay['id_devise'] !== '1') {
          $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
          $ta = $bdd -> query($reqT);
          $taux = $ta->fetch();
          $nbtx = $ta->rowCount();

          if ($nbtx !== 0) {
            $tx = $taux['taux_taux'];
          }
        } else {
          $pay['id_devise'] = 1;
        }
      //var_dump($tx);
    }


			$reSon = "SELECT * FROM son LEFT JOIN album
										  ON son.id_album = album.id_album
										  LEFT JOIN artiste
										  ON artiste.id_artiste = son.id_artiste
                      WHERE son.date_verif <= '".$dateAuj."' AND son.is_active = 1 AND son.titre_son LIKE \"%".$mSearch."%\" ORDER BY son.titre_son";

			$son = $bdd -> query($reSon);
			$nbson = $son -> rowCount();
			// echo $re;
			if($nbson == 0){

			} else {

				while ($sons = $son->fetch() ){
					  // var_dump ($sons);


					// $re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
					// $nb = $bdd -> query($re1);
					// $nbs = $nb->rowCount();
					//$j = 0;
					$affich_el[$i]['id'] = $sons['0'];
					$affich_el[$i]['name'] = $sons['2'];
					$affich_el[$i]['artist'] = $sons['nom_artiste'];
					$affich_el[$i]['album'] = $sons['titre_album'];
					$affich_el[$i]['url'] = $sons['fichier_son'];
					// $affich_el[$i]['url'] = $sons['url_son']);
					$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el[$i]['cover_url'] = $sons['cover_son'];
					$affich_el[$i]['price'] = $sons['prix_son']*$tx;
					// $affich_el[$i]['downloads'] = $nbs;
					$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el[$i]['reference'] = $sons['reference_son'];
					$affich_el[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el[$i]['lien'] = $sons['lien_son'];
					$affich_el[$i]['genre'] = $sons['13'];
					$affich_el[$i]['devise'] = $devise;
					$affich_el[$i]['type'] = 'son';

					$i++;

				}
			}





			$reAr = "SELECT * FROM artiste
															INNER JOIN pays
															ON pays.code_pays = artiste.nationalite_artiste
															 WHERE nom_artiste LIKE \"%".$mSearch."%\" ORDER BY nom_artiste";
			$ar = $bdd -> query($reAr);
			$nbar = $ar -> rowCount();
			//echo $re;
			if($nbar == 0){

			} else {

			while ($artis = $ar->fetch() ){

				// $reN = "SELECT * FROM pays WHERE code_pays ='".$artis['nationalite_artiste']."'";
				// $na = $bdd -> query($reN);
				// $nat = $na->fetch();
				//$j = 0;
				$affich_el[$i]['id'] = $artis['id_artiste'];
				$affich_el[$i]['name'] = $artis['nom_artiste'];
				$affich_el[$i]['dob'] = $artis['dob_artiste'];
				$affich_el[$i]['cover_url'] = $artis['cover_artiste'];
				$affich_el[$i]['bio'] = $artis['biographie_artiste'];
				$affich_el[$i]['natio'] = $artis['nom_pays'];
				$affich_el[$i]['lien'] = $artis['lien_artiste'];
				$affich_el[$i]['type'] = 'artiste';

				$i++;

			}
			}




			$reAl = "SELECT * FROM album INNER JOIN artiste
										  ON artiste.id_artiste = album.id_artiste WHERE album.date_verif <= '".$dateAuj."' AND album.is_active = 1 AND album.titre_album LIKE \"%".$mSearch."%\" ORDER BY album.titre_album";

			$al = $bdd -> query($reAl);
			$nba = $al -> rowCount();
			// echo $nba;
			if($nba == 0){


			} else {

			while ($alb = $al->fetch() ){

				// $re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
				// $nb = $bdd -> query($re1);
				// $nba = $nb->rowCount();
				//$j = 0;
  			// var_dump($alb);
				$affich_el[$i]['id'] = $alb['id_album'];
				$affich_el[$i]['name'] = $alb['titre_album'];
				$affich_el[$i]['artist'] = $alb['nom_artiste'];
				$affich_el[$i]['cover_art_url'] = $alb['cover_artiste'];
				$affich_el[$i]['cover_url'] = $alb['cover_album'];
				$affich_el[$i]['url'] = $alb['fichier_album'];
				$affich_el[$i]['price'] = $alb['prix_album']*$tx;
				//$affich_el[$i]['downloads'] = $nba;
				$affich_el[$i]['reference'] = $alb['reference_album'];
				$affich_el[$i]['dateSortie'] = $alb['dateSortie_album'];
				$affich_el[$i]['lien'] = $alb['lien_album'];
				$affich_el[$i]['devise'] = $devise;
				$affich_el[$i]['genre'] = $alb['11'];
				$affich_el[$i]['type'] = 'album';

				$i++;

			}
			}







		$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			// var_dump($affich_el);

			// echo json_encode($affich_el);
			// shuffle($affich_el);
			echo json_encode($affich_el);
      // echo json_last_error_msg();


  }

	//Affichage des sons d'une playlist
	function affichPlaylist(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);
			$payss =(isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

			$reqP = "SELECT * FROM pays
															INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
			$pa = $bdd -> query($reqP);
			$pay = $pa->fetch();
			$pa->closeCursor();
			$devise = $pay['signe_devise'];
      $nbpay = $pa->rowCount();
			$pa->closeCursor();

      //var_dump($pay);
      if($nbpay !== 0 AND $pay['id_devise'] !== '1') {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
				// $ta->closeCursor();
        $tx = $taux['taux_taux'];
      }
      //var_dump($tx);
    }

		$reP = "SELECT * FROM userplaylist WHERE id_userplaylist = ".$_POST['idplst'];
		$plst = $bdd -> query($reP);
    $plsts = $plst->fetch();

		$reP1 = "SELECT * FROM userplaylist_son
													INNER JOIN son
												 	ON son.id_son = userplaylist_son.id_son
													LEFT JOIN album
												  ON son.id_album = album.id_album
												  LEFT JOIN artiste
												  ON artiste.id_artiste = son.id_artiste
		                      WHERE id_userplaylist = ".$_POST['idplst']." ORDER BY position_userplaylist_son ASC";
		$plstSn = $bdd -> query($reP1);

		$affich_el['id'] = $plsts['id_userplaylist'];
		$affich_el['Titre'] = $plsts['nom_userplaylist'];
		$i=0;
		while ($plstSns = $plstSn->fetch()) {
			// var_dump($plstSns);

			$affich_el['playlist'][$i]['id'] = $plstSns['id_son'];
			$affich_el['playlist'][$i]['name'] = $plstSns['titre_son'];
			$affich_el['playlist'][$i]['artist'] = $plstSns['nom_artiste'];
			$affich_el['playlist'][$i]['album'] = $plstSns['titre_album'];
			$affich_el['playlist'][$i]['url'] = $plstSns['fichier_son'];
			// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
			$affich_el['playlist'][$i]['cover_art_url'] = $plstSns['cover_artiste'];
			$affich_el['playlist'][$i]['cover_url'] = $plstSns['cover_son'];
			$affich_el['playlist'][$i]['price'] = $plstSns['prix_son']*$tx;
			$affich_el['playlist'][$i]['reference'] = $plstSns['reference_son'];
			$affich_el['playlist'][$i]['dateSortie'] = $plstSns['dateSortie_son'];
			$affich_el['playlist'][$i]['lien'] = $plstSns['lien_son'];
			$affich_el['playlist'][$i]['genre'] = $plstSns['13'];
			$affich_el['playlist'][$i]['devise'] = $devise;

			$i++;
		}

		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);


	}

	// affichage de toute les playlists dun user
	function affichUserPlaylist(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$reP = "SELECT * FROM userplaylist WHERE id_user = ".$_POST['idU'];
		$plst = $bdd -> query($reP);

		$i=1;
		// echo $reP;
		while ($plsts = $plst->fetch()) {
			  // var_dump($plsts);
			$affich_el[$i]['id'] = $plsts['0'];
			$affich_el[$i]['name'] = $plsts['nom_userplaylist'];

			$i++;
		}

		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);


	}

	//Affichage des toutes les playlists d'un user avec leur son
	function affichPlaylistSon(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0]=1;
    $dateAuj =  date ('Y-m-d H:i:s');

		$tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);
			$payss =(isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

			$reqP = "SELECT * FROM pays
															INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
			$pa = $bdd -> query($reqP);
			$pay = $pa->fetch();
			$pa->closeCursor();
			$devise = $pay['signe_devise'];
      $nbpay = $pa->rowCount();
			$pa->closeCursor();

      //var_dump($pay);
      if($nbpay !== 0 AND $pay['id_devise'] !== '1') {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
				// $ta->closeCursor();
        $tx = $taux['taux_taux'];
      }
      //var_dump($tx);
    }

		$reP = "SELECT * FROM userplaylist WHERE id_user = ".$_POST['idU']." ORDER BY nom_userplaylist ASC";
		$plst = $bdd -> query($reP);
		$u=1;

		while ($plsts = $plst->fetch()) {

			$reP1 = "SELECT * FROM userplaylist_son
														INNER JOIN son
													 	ON son.id_son = userplaylist_son.id_son
														LEFT JOIN album
													  ON son.id_album = album.id_album
													  LEFT JOIN artiste
													  ON artiste.id_artiste = son.id_artiste
			                      WHERE id_userplaylist = ".$plsts['id_userplaylist']." ORDER BY position_userplaylist_son ASC";
			$plstSn = $bdd -> query($reP1);

			$affich_el[$u]['id'] = $plsts['id_userplaylist'];
			$affich_el[$u]['name'] = $plsts['nom_userplaylist'];
			$i=0;
			while ($plstSns = $plstSn->fetch()) {
				// var_dump($plstSns);

				$affich_el[$u]['sons'][$i]['id'] = $plstSns['0'];
				$affich_el[$u]['sons'][$i]['name'] = $plstSns['titre_son'];
				$affich_el[$u]['sons'][$i]['artist'] = $plstSns['nom_artiste'];
				$affich_el[$u]['sons'][$i]['album'] = $plstSns['titre_album'];
				$affich_el[$u]['sons'][$i]['url'] = $plstSns['fichier_son'];
				// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
				$affich_el[$u]['sons'][$i]['cover_art_url'] = $plstSns['cover_artiste'];
				$affich_el[$u]['sons'][$i]['cover_url'] = $plstSns['cover_son'];
				$affich_el[$u]['sons'][$i]['price'] = $plstSns['prix_son']*$tx;
				$affich_el[$u]['sons'][$i]['reference'] = $plstSns['reference_son'];
				$affich_el[$u]['sons'][$i]['dateSortie'] = $plstSns['dateSortie_son'];
				$affich_el[$u]['sons'][$i]['lien'] = $plstSns['lien_son'];
				$affich_el[$u]['sons'][$i]['genre'] = $plstSns['13'];
				$affich_el[$u]['sons'][$i]['devise'] = $devise;

				$i++;
			}

			$u++;
		}


		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);


	}

	function listGenre(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;

		$tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);
			$payss =(isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

			$reqP = "SELECT * FROM pays
															INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
			$pa = $bdd -> query($reqP);
			$pay = $pa->fetch();
			$pa->closeCursor();
			$devise = $pay['signe_devise'];
      $nbpay = $pa->rowCount();
			$pa->closeCursor();


      //var_dump($pay);
      if($nbpay !== 0 AND $pay['id_devise'] !== '1') {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
				// $ta->closeCursor();
        $tx = $taux['taux_taux'];
      }
      //var_dump($tx);
    }

		$reqg = "SELECT * FROM son INNER JOIN genre
																ON son.id_genre = genre.id_genre
																LEFT JOIN album
															  ON son.id_album = album.id_album
															  LEFT JOIN artiste
															  ON artiste.id_artiste = son.id_artiste
																WHERE son.id_genre = ".$_GET['id'];
		$genr = $bdd -> query($reqg);

		$i=1;
		while ($genre = $genr->fetch()) {
				// var_dump($plstSns);

				$affich_el[$i]['id'] = $genre['0'];
				$affich_el[$i]['name'] = $genre['titre_son'];
				$affich_el[$i]['artist'] = $genre['nom_artiste'];
				$affich_el[$i]['album'] = $genre['titre_album'];
				$affich_el[$i]['url'] = $genre['fichier_son'];
				// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
				$affich_el[$i]['cover_art_url'] = $genre['cover_artiste'];
				$affich_el[$i]['cover_url'] = $genre['cover_son'];
				$affich_el[$i]['price'] = $genre['prix_son']*$tx;
				$affich_el[$i]['reference'] = $genre['reference_son'];
				$affich_el[$i]['dateSortie'] = $genre['dateSortie_son'];
				$affich_el[$i]['lien'] = $genre['lien_son'];
				$affich_el[$i]['genre'] = $genre['13'];
				$affich_el[$i]['devise'] = $devise;

				$i++;

		}

		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);
	}

	function listForf(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;

		$tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);
			$payss =(isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

			$reqP = "SELECT * FROM pays
															INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
			$pa = $bdd -> query($reqP);
			$pay = $pa->fetch();
			$devise = $pay['signe_devise'];
      $nbpay = $pa->rowCount();


      //var_dump($pay);
      if($nbpay !== 0 AND $pay['id_devise'] !== '1') {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
				// $ta->closeCursor();
        $tx = $taux['taux_taux'];
      }
      //var_dump($tx);
    }
		$pa->closeCursor();

		$reqg = "SELECT * FROM forfaitStream";
		$for = $bdd -> query($reqg);

		$i=1;
		while ($forfait = $for->fetch()) {
				// var_dump($plstSns);

				$affich_el[$i]['id'] = $forfait['0'];
				$affich_el[$i]['name'] = $forfait['nom_forfaitStream'];
				$affich_el[$i]['price'] = $forfait['prix_forfaitStream']*$tx;
				$affich_el[$i]['time'] = $forfait['temps_forfaitStream'];
				$affich_el[$i]['devise'] = $devise;


				$i++;

		}

		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);
	}

	function listLastSout(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;

		$reqg = "SELECT * FROM don INNER JOIN transaction
																ON don.id_transaction = transaction.id_transaction
																INNER JOIN pays
																ON pays.id_pays = transaction.id_pays
																INNER JOIN devise
																ON devise.id_devise = pays.id_devise
																WHERE transaction.statut_transaction = 'SUCCESS' AND don.id_artiste = ".$_GET['id']."
																ORDER BY don.id_don DESC LIMIT 7";
		$d = $bdd -> query($reqg);


		$i=1;
		while ($don = $d->fetch()) {
				 // var_dump($don);

				$affich_el[$i]['id'] = $don['0'];
				$affich_el[$i]['name'] = $don['nom_don'];
				$affich_el[$i]['montant'] = $don['somme_don']." ".$don['signe_devise'];
				$affich_el[$i]['date'] = $don['date_transaction'];


				$i++;

		}

		$affich_e = array_shift($affich_el);
		echo json_encode($affich_el);
	}
  // $cond = (isset($_GET["nom_fct"])) ? $_GET["nom_fct"] : $_POST["nom_fct"];

	function nouveaux(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$affich_el[0] = 1;
		$tx = 1;
    $dateAuj =  date ('Y-m-d H:i:s');
		$tx = 1;

		if (isset($_POST['ip']) AND $_POST['ip'] !== "") {
			$ip = $_POST['ip'];
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($ip) AND $ip !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$ip,
          CURLOPT_RETURNTRANSFER => true,
        //CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
       // var_dump($response);
			 $payss = (isset($response -> countryCode) AND $response -> countryCode !== null) ? $response -> countryCode : "CI";

      $reqP = "SELECT * FROM pays
			 												INNER JOIN devise
															ON devise.id_devise = pays.id_devise
															WHERE code_pays ='".$payss."'";
      $pa = $bdd -> query($reqP);
      $pay = $pa->fetch();



      if(isset($pay['id_devise']) AND $pay['id_devise'] !== '1') {
          $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
          $ta = $bdd -> query($reqT);
          $taux = $ta->fetch();
          $nbtx = $ta->rowCount();

          if ($nbtx !== 0) {
            $tx = $taux['taux_taux'];
          }
					$devise = $pay['signe_devise'];
        } else {
          $pay['id_devise'] = 1;
					$devise = 'FCFA';
        }
      //var_dump($tx);
    }
    // $reqD = "SELECT * FROM devise WHERE id_devise =".$pay['id_devise'];
    // $dv = $bdd -> query($reqD);
    // $dvse = $dv->fetch();


		$idxH = 0;
		$i = 1;
		while ($idxH <= 6) {
			//$reqH = "SELECT * FROM ".$_GET['table']." WHERE position_".$_GET['table']." = ".$idxH." AND dateDeb_".$_GET['table']." <= '".$dateAuj."' AND dateFin_".$_GET['table']." >= '".$dateAuj."' ORDER BY id_".$_GET['table']." ASC";
			$reqH = "SELECT * FROM nouveaux WHERE position_nouveaux = ".$idxH." AND dateDeb_nouveaux <= '".$dateAuj."' AND dateFin_nouveaux >= '".$dateAuj."' ORDER BY id_nouveaux DESC";
	    $ht = $bdd -> query($reqH);
	    $hot = $ht->fetch();
			  //echo $reqH;
			 //var_dump($hot);

			if (isset($hot[0])) {
					$re = "SELECT * FROM nouveaux INNER JOIN son
													ON nouveaux.id = son.id_son
													LEFT JOIN album
		 										  ON son.id_album = album.id_album
		 										  LEFT JOIN artiste
		 										  ON artiste.id_artiste = son.id_artiste
													LEFT JOIN genre
		 										  ON genre.id_genre = son.id_genre
		                      WHERE son.date_verif <= '".$dateAuj."' AND son.is_active = 1 AND son.id_son = ".$hot['id'];

		 			$son = $bdd -> query($re);

		 			$sons = $son->fetch();
		 				    // var_dump ($sons);

	 				$re2 = "SELECT * FROM soutCommande WHERE libelle_soutCommande ='".$sons[0]."-son'";
	 				$reqspre = $bdd -> query($re2);
	 				$reqsprec = $reqspre->fetch();

	 				//$j = 0;
	 				 //echo "<br>".$i;

					$affich_el[$i]['id'] = $sons['id_son'];
					$affich_el[$i]['name'] = $sons['titre_son'];
					$affich_el[$i]['artist'] = $sons['nom_artiste'];
					$affich_el[$i]['album'] = $sons['titre_album'];
					$affich_el[$i]['url'] = $sons['fichier_son'];
					// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
					$affich_el[$i]['cover_art_url'] = $sons['cover_artiste'];
					$affich_el[$i]['cover_url'] = $sons['cover_son'];
					$affich_el[$i]['price'] = $sons['prix_son']*$tx;
					// $affich_el[$i]['downloads'] = $nbs;
					$affich_el[$i]['lyrics'] = isset($sons['texte_lyric']) ? $sons['texte_lyric'] : '';
					$affich_el[$i]['reference'] = $sons['reference_son'];
					$affich_el[$i]['dateSortie'] = $sons['dateSortie_son'];
					$affich_el[$i]['lien'] = $sons['lien_son'];
					$affich_el[$i]['genre'] = $sons['titre_genre'];
					$affich_el[$i]['devise'] = $devise;
					$affich_el[$i]['idArtist'] = $sons['id_artiste'];
					$affich_el[$i]['soutientmini'] = (isset($reqsprec['minim_soutCommande'])) ? $reqsprec['minim_soutCommande']*$tx : 500*$tx;
					$affich_el[$i]['precommande'] = (isset($reqsprec['type_soutCommande'])) ? $reqsprec['type_soutCommande']*$tx : 0;

				$i++;
			} else {
				// if ($idxH == 0){
				// 	$affich_el[$i]="";
				// }
				//$idxH = 7;
			}
			$idxH++;



		}


		 $affich_e = array_shift($affich_el);

		 echo json_encode($affich_el);
			// echo json_last_error_msg();
	}





switch ($_GET["nom_fct"]){

		case 0 : //cas de lajout

		ajouter();
		break;

		case 1 : //cas de la maj
		maj();
		break;

		case 2 : //cas de laffichage
		// afficher();
    // error_log()
		break;

		case 3 : //cas de la suppression
		supprimer();
		break;

		case 4 : //cas de la maj d'un elmt
		maj();
		break;

		case 5 : //cas de la connexion
		connexion();
		break;

		case 6 : //cas de liste album son et artiste
		listAAS();
		break;

		case 7 : //cas de demande de reference
		 referenceTrsx();
		break;

		case 8 : //cas de l'affichage apres paiement
		 listPT();
		break;

		case 9 : //cas de la maj de l'utilisateur
		 majuser();
		break;

		case 10 : //cas de l'affichage d'un elmt
		 aff_un();
		break;

		case 11 : //cas de liste album, artiste, son appartenant a ....
		 listAASfiltre();
		break;

		case 12 : //cas check statut
		 checkStat();
		break;

		case 13 : //cas liste des pays
		 lpays();
		break;

		case 14 : //cas de l'inscription
		 inscr();
		break;

		case 15 : //cas de la connexion
		 connect();
		break;

		case 16 : //cas de la deconnexion
		 deco();
		break;

		case 17 : //cas de l'affichage de lhistorique
		 histo();
		break;

		case 18 : //cas de l'ajout dun token
		 getToken();
		break;

		case 19 : //cas position utilisateur
		 positionUser();
		break;

		case 20 : //cas liste nouveau sons et albums
		 // listNAS();
		 nouveaux();
		break;

		case 21 : //cas liste son togolais
		 listASTG();
		break;

		case 22 : //cas liste son populaire
		 listPPOPU();
		break;

		case 23 : //cas de retour de recherche
		 rechApi();
		break;

		case 24 : //cas de la recommandation
		 recommandation();
		break;

		case 25 : //cas de achat avec wallet
		 achatWal();
		break;

		case 26 : //cas de rechargement wallet
		 rechargTrsx();
		break;

		case 27 : //cas de maj wallet
			$montant = (isset($_POST['montant'])) ? $_POST['montant'] : $_GET['montant'];
			$idW = (isset($_POST['idW'])) ? $_POST['idW'] : $_GET['idW'];
			$table = (isset($_POST['table'])) ? $_POST['table'] : $_GET['table'];
			$src = (isset($_POST['src'])) ? $_POST['src'] : $_GET['src'];
			$idsrc = (isset($_POST['idsrc'])) ? $_POST['idsrc'] : $_GET['idsrc'];
		 	majsolde($montant, $table, $idW, $src, $idsrc);
		break;

		case 28 : //cas d'ajout de playlist
			newPlaylist();
		break;

		case 29 : //cas de suppression de playlist
			delPlaylist();
		break;

		case 30 : //cas d'ajout d'un son a une playlist
			ajouSonPlaylist();
		break;

		case 31 : //cas de suppression d'un son dans une playlist
			delSonPlaylist();
		break;

		case 32 : //cas d'affichage de son d'une playlist
			affichPlaylist();
		break;

		case 33 : //cas d'affichage des playlist user
			affichPlaylistSon();
		break;

		case 34 : //cas de lecture de son, compte
			lecture();
		break;

		case 35 : //cas de connexion d'artiste ou maison de prod depuis l'app
			connectClt();
		break;

		case 36 : //cas de connexion d'artiste ou maison de prod depuis l'app
			hotSonAlb();
		break;

		case 37 : //souscription
			hotSonAlb();
		break;

		case 38 : //cas de connexion d'artiste ou maison de prod depuis l'app
			hotSonAlb();
		break;

		case 39 : //cas de connexion d'artiste ou maison de prod depuis l'app
			souscription_forfait();
		break;

		case 40 : //cas de liste des personnes ayant soutenu un artiste
			listSout();
		break;

		case 41 : //cas de liste des genres
			genres();
		break;

		case 42 : //cas de liste des genres
			verif_souscription();
		break;

		case 43 : //cas de reference pour forfait
			forfaitTrsx();
		break;

		case 44 : //cas de l'affichage de toutes les playlist d'un user et de leurs sons
			affichUserPlaylist();
		break;

		case 45 : //cas de l'affichage de son d'un genre
			listGenre();
		break;

		case 46 : //cas de l'affichage des forfaits
			listForf();
		break;

		case 47 : //cas de l'affichage des derniers soutien
			listLastSout();
		break;

		case 48 : //cas de l'affichage des derniers soutien
			list_souscription();
		break;

		case 49 : //cas de l'affichage des derniers soutien
			ajouSonFavoris();
		break;

		case 49 : //cas de l'affichage des derniers soutien
			lpaysT();
		break;



	}
}
?>
