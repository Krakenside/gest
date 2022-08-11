<?php
header("Access-Control-Allow-Origin: *");

 //var_dump ($_POST);


 if (isset($_GET["mob"])){
	$_POST = $_GET;
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
	 if($tabl == 'user'){
		 $_POST["date_verif_user"] = null;
	 }
	 if($tabl == 'examen' OR $tabl == 'quizz'){
		 $req = $bdd->query("SHOW TABLE STATUS FROM evallesse LIKE '".$tabl."' ");
			$doncm = $req->fetch();

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

		$req = $bdd->query("SHOW TABLE STATUS FROM evallesse LIKE 'matiere' ");
			$donnees = $req->fetch();

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





							// Testons si l'image petite a bien été envoyé et s'il n'y a pas d'erreur
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

					move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'images/up/lta/' .basename($_FILES[$ch_image]['name']));

					echo "L'image a bien été envoyé ! <br />";
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

		echo $req_ajou;
	 $req_ajou_donne = $bdd -> prepare($req_ajou);

			$i=0;
			echo $taille;
			while($i<$taille){
				$value = ":".$table[$i];
				echo $valeur[$i];
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
                      // Testons si l'extension est autorisée
                        $infosfichier = pathinfo($_FILES[$ch_image]['name']);
                          $extension_upload = $infosfichier['extension'];
                          $extensions_autorisees = array('jpg', 'jpeg', 'gif','png');
                          if (in_array($extension_upload, $extensions_autorisees))
                            {
                      // On peut valider le fichier et le stockerdéfinitivement

                        move_uploaded_file($_FILES[$ch_image]['tmp_name'], 'images/up/lta/' .$_FILES[$ch_image]['name'].'-'.$dte.'.png');

                        echo "L'image a bien été envoyé ! <br />";
                            }
                          }
                      } else {echo "pas de fichier";} }

			if($tabl == 'matiere') {
				var_dump($_POST);
				$idClass = explode(',',$_POST['id_classe']);
				$nbClass = sizeof($idClass);
				$in =0;
				$on =0;
				$an =0;
				$jn =0;
				$idPaj=[];
				$idPs=[];
				var_dump ($idClass);

				$re2 = "SELECT * FROM classe_matiere WHERE id_matiere = ".$idCle;
							$clam1 = $bdd -> query($re2);
							while($clasm1 = $clam1->fetch()){
								$idClass1[$on] = $clasm1['id_classe'];
								$on++;
							}

				var_dump ($idClass1);
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
				var_dump($idPaj);
				var_dump($idPs);
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

				echo $valu;
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
					if($tabl = 'membre'){
						header("location: modifier.php?t=".$tabl."&indx=".$indx."&m=1&d=".$dda);
					} else{
						header("location: modifier.php?t=".$tabl."&indx=".$indx."&m=1");
					}
					echo 'ok';
	}

	function cotiser(){
		require 'connexion.php';
		//var_dump ($_POST);
		if(isset($_POST['donnee'])){
			$ddj = date ('Y-m-d');
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

		header("location: ajoutqurep.php?t=".$tabl."&indx=".$idCle);

	}

	function connexion(){
		require 'connexion.php';
		//var_dump ($_GET);

		$_SESSION['connect']=0; //Initialise la variable 'connect'.

		if (isset($_GET['login']) ) // Si les variables existent.
		{
				//On récupère les données envoyées par la méthode POST du formulaire d'identification.
				// le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
				$login = htmlentities($_GET['login'], ENT_QUOTES, "ISO-8859-1");



				$ip = $_SERVER['REMOTE_ADDR'];

				//$bdd = new Cbdd('localhost', 'cmsbdd', 'root', '');
				//$bdd->connexion('localhost', 'cmsbdd', 'root', '');
				$requet = $bdd -> query('SELECT * FROM user WHERE telephone_user =\''.$login.'\'');

						$colon = $requet -> rowCount();
					   if( $colon == 1) {

		// Si le mot de passe et le login sont bons (valable pour 1 utilisateur ou plus). J'ai mis plusieurs identifiants et mots de passe.


							$_SESSION['connect']='1'; // Change la valeur de la variable connect. C'est elle qui nous permettra de savoir s'il y a eu identification.
							$_SESSION['telephone_user']=$login;// Permet de récupérer le login afin de personnaliser la navigation.
							$clientI = $requet -> fetch();
							$_SESSION["user"] = $clientI["nom_user"];
							$_SESSION["userID"] = $clientI["id_user"];
							$_SESSION["user_mail"] = $clientI["email_user"];
							$_SESSION["avatar"] = $clientI["avatar_user"];
							$_SESSION["sexe"] = $clientI["sexe_user"];

							echo json_encode($_SESSION);

						} else {
							$rep['connect']='0';
							echo json_encode($rep);
						}
		}
	}

	function listAAS(){
		require 'connexion.php';
		$i =1;
		$affich_el[] = '';
		$tx = 1;
    if (isset($_POST['ip']) AND $_POST['ip'] !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$_POST['ip'],
          CURLOPT_RETURNTRANSFER => true,
        //  CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      // var_dump($response);

      $reqP = "SELECT * FROM pays WHERE code_pays ='".$response -> countryCode."'";
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
        } else {
          $pay['id_devise'] = 1;
        }
      //var_dump($tx);
    } else {
      //echo "lllllll";
      $pay['id_devise'] = 1;

    }
    $reqD = "SELECT * FROM devise WHERE id_devise =".$pay['id_devise'];
    $dv = $bdd -> query($reqD);
    $dvse = $dv->fetch();
    $devise = $dvse['signe_devise'];


	 if($_GET['table'] == 'son'){

			$re = "SELECT * FROM son LEFT JOIN album
										  ON son.id_album = album.id_album
										  LEFT JOIN artiste
										  ON artiste.id_artiste = son.id_artiste
										  LEFT JOIN lyric
										  ON lyric.id_son = son.id_son";
      if (isset($_GET['free']) AND $_GET['free'] == 1) {
        $re .= " WHERE son.prix_son = 0";
      }
      if (isset($_GET['nouv']) AND $_GET['nouv'] == 1) {
        $re .= "  ORDER BY son.id_son DESC";
      } else {
         $re .= " ORDER BY son.titre_son";
      }
			$son = $bdd -> query($re);
			$nbson = $son -> rowCount();
			// echo $re;
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
			} else {

			while ($sons = $son->fetch() ){
				 // var_dump ($sons);


				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
				$nb = $bdd -> query($re1);
				$nbs = $nb->rowCount();
				//$j = 0;
				$affich_el[$i]['id'] = $sons['0'];
				$affich_el[$i]['name'] = $sons['2'];
				$affich_el[$i]['artist'] = utf8_decode($sons['nom_artiste']);
				$affich_el[$i]['album'] = $sons['titre_album'];
				$affich_el[$i]['url'] = $sons['fichier_son'];
				// $affich_el[$i]['url'] = utf8_decode($sons['url_son']);
				$affich_el[$i]['cover_art_url'] = utf8_decode($sons['cover_artiste']);
				$affich_el[$i]['cover_url'] = utf8_decode($sons['cover_son']);
				$affich_el[$i]['price'] = $sons['prix_son']*$tx;
				$affich_el[$i]['downloads'] = $nbs;
				$affich_el[$i]['lyrics'] = utf8_decode($sons['texte_lyric']);
				$affich_el[$i]['reference'] = $sons['reference_son'];
				$affich_el[$i]['dateSortie'] = $sons['dateSortie_son'];
				$affich_el[$i]['lien'] = $sons['lien_son'];
				$affich_el[$i]['devise'] = $devise;

				$i++;

			}
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			// var_dump($affich_el);

			// echo json_encode($affich_el);
			echo json_encode($affich_el);
      // echo json_last_error_msg();

	   }
     else if($_GET['table'] == 'artiste'){

			$re = "SELECT * FROM artiste ORDER BY nom_artiste";
			$ar = $bdd -> query($re);
			$nbar = $ar -> rowCount();
			//echo $re;
			$affich_el[0]=1;
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
				$affich_el[$i]['natio'] = $nat['nom_pays'];
				$affich_el[$i]['lien'] = $artis['lien_artiste'];

				$i++;

			}
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);

		}
		else if ($_GET['table'] == 'album'){

			$re = "SELECT * FROM album INNER JOIN artiste
										  ON artiste.id_artiste = album.id_artiste ORDER BY album.titre_album";
			$al = $bdd -> query($re);
			$nba = $al -> rowCount();
			//echo $re;
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

			} else {

			while ($alb = $al->fetch() ){

				$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
				$nb = $bdd -> query($re1);
				$nba = $nb->rowCount();
				//$j = 0;
  			//var_dump($alb);
				$affich_el[$i]['id'] = $alb['id_album'];
				$affich_el[$i]['name'] = utf8_decode($alb['titre_album']);
				$affich_el[$i]['artist'] = utf8_decode($alb['nom_artiste']);
				$affich_el[$i]['cover_art_url'] = utf8_decode($alb['cover_artiste']);
				$affich_el[$i]['cover_url'] = utf8_decode($alb['cover_album']);
				$affich_el[$i]['url'] = utf8_decode($alb['url_album']);
				$affich_el[$i]['price'] = $alb['prix_album']*$tx;
				$affich_el[$i]['downloads'] = $nba;
				$affich_el[$i]['reference'] = $alb['reference_album'];
				$affich_el[$i]['dateSortie'] = $alb['dateSortie_album'];
				$affich_el[$i]['lien'] = $alb['lien_album'];
        $affich_el[$i]['devise'] = $devise;

				$i++;

			}
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
				$affich_el[$i]['name'] = utf8_decode($genr['titre_genre']);
				$affich_el[$i]['cover_url'] = utf8_decode($genr['cover_genre']);
				//$affich_el[$i]['cover_url'] = utf8_decode($genr['lien_genre']);

				$i++;

			}
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
				$affich_el[$i]['name'] = utf8_decode($event['nom_event']);
				$affich_el[$i]['image'] = utf8_decode($event['cover_event']);
				$affich_el[$i]['date'] = $event['date_event'];
				$affich_el[$i]['address'] = $event['lieu_event'];
				$affich_el[$i]['phone'] = $event['tel_event'];
				$affich_el[$i]['email'] = $event['mail_event'];
				$affich_el[$i]['description'] = $event['description_event'];
				//$affich_el[$i]['cover_url'] = utf8_decode($genr['lien_genre']);

				$i++;

			}
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);



		}

		/*
		 else if ($_GET['table'] == 'matiere'){
			$re = "SELECT * FROM matiere INNER JOIN classe_matiere
										  ON classe_matiere.id_matiere = matiere.id_matiere
										  INNER JOIN classe
										  ON classe.id_classe = classe_matiere.id_classe
										  WHERE classe.titre_classe='".$_GET['classe']."'";
			$cha = $bdd -> query($re);
			//echo $re;
			while ($chap = $cha->fetch()){

				//$j = 0;
				$affich_el[$i][0] = $chap['titre_matiere'];
				$affich_el[$i][1] = $chap['id_classe_matiere'];

				$i++;
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);


		} else if ($_GET['table'] == 'matiere_prof'){
			$re2 = "SELECT * FROM user_classe WHERE id_user=".$_GET['id_user'];
			$uc = $bdd -> query($re2);
			 $usc = $uc->fetch();
			$re = "SELECT * FROM matiere INNER JOIN classe_matiere
										  ON classe_matiere.id_matiere = matiere.id_matiere
										  INNER JOIN classe
										  ON classe.id_classe = classe_matiere.id_classe
										  WHERE classe.id_classe='".$usc['id_classe']."'";
			$mat = $bdd -> query($re);
			$affich_el[0]=1;
			while ($matt = $mat->fetch()){
				//echo $matt['id_classe_matiere'];
				$re1 = "SELECT * FROM user_matiere WHERE id_user=".$_GET['id_user']." AND id_classe_matiere = ".$matt['id_classe_matiere'];
				$matu = $bdd -> query($re1);
				//$mattu = $mat->fetch();
				$mattu = $matu->rowCount();
				//echo $mattu;
				//$j = 0;
				$affich_el[$i][0] = $matt['titre_matiere'];
				$affich_el[$i][1] = $matt['id_classe_matiere'];
				if($mattu == 1){
					$affich_el[$i][2] = 1;
				} else {
					$affich_el[$i][2] = 0;
				}

				$i++;
			//var_dump($matt);
			}
			//$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);


		} else if ($_GET['table'] == 'user_matiere'){
			$re = "SELECT * FROM matiere INNER JOIN classe_matiere
										  ON classe_matiere.id_matiere = matiere.id_matiere
										  INNER JOIN user_matiere
										  ON user_matiere.id_classe_matiere = classe_matiere.id_classe_matiere
										  WHERE user_matiere.id_user=".$_GET['id']." ORDER BY user_matiere.id_user_matiere ASC";
			$cha = $bdd -> query($re);
			$affich_el[0]=1;
			while ($mat = $cha->fetch() AND $i<4){
				$re1 = "SELECT * FROM chapitre WHERE id_classe_matiere=".$mat['id_classe_matiere'];
				$nb = $bdd -> query($re1);
				$nbm = $nb->rowCount();
				//$j = 0;
				$affich_el[$i]['titre_matiere'] = $mat['titre_matiere'];
				$affich_el[$i]['id_classe_matiere'] = $mat['id_classe_matiere'];
				$affich_el[$i]['nb_chap'] = $nbm;
			//echo $nbm;

				$i++;
			}
			//$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);


		}  else if ($_GET['table'] == 'quizz'){
			$i=2;
			$re = "SELECT * FROM lecon
										  INNER JOIN chapitre
										  ON chapitre.id_chapitre = lecon.id_chapitre
										  WHERE chapitre.id_chapitre=".$_GET['s']." ORDER BY lecon.id_lecon ASC";
			$lec = $bdd -> query($re);
			$affich_el[0]=1;
				$nb_totq=0;
			while ($lecn = $lec->fetch()){
				$affich_el[$i]['titre_lecon']=$lecn['titre_lecon'];
				$re1 = "SELECT * FROM quizz INNER JOIN difficulte
										  ON difficulte.id_difficulte = quizz.id_difficulte
										  WHERE quizz.id_lecon=".$lecn['id_lecon']." ORDER BY quizz.id_difficulte ASC";

				$qz = $bdd -> query($re1);
				$u=0;
				while ($qzz = $qz->fetch()){

					$re2 = "SELECT * FROM resultat_q WHERE id_quizz=".$qzz['id_quizz']." AND id_user = ".$_GET['id_user'];
					$q = $bdd -> query($re2);
					$rqz = $q->fetch();
					$re3 = "SELECT * FROM question_q WHERE id_quizz=".$qzz['id_quizz'];
					$qq = $bdd -> query($re3);
					$nbqq = $q->rowCount();

					//$j = 0;
					if($nbqq !== 0){
						$affich_el[$i]['quizz'][$u][0] = $qzz['id_quizz'];
						$affich_el[$i]['quizz'][$u][1] = $qzz['titre_quizz'];
						$affich_el[$i]['quizz'][$u][2] = $qzz['nom_difficulte'];
						$affich_el[$i]['quizz'][$u][3] = $rqz['score_resultat_q'];
						$nb_totq += $nbqq;
						$u++;
					}
				//echo $nbm;

				}
				//$j = 0;

			//echo $nbm;

				$i++;
			}
			//$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			$affich_el[1] = $nb_totq;
			echo json_encode($affich_el);


		}  else if ($_GET['table'] == 'examen'){
			$i=2;

			$affich_el['reponse']=1;
			$nb_totex=0;

				$re1 = "SELECT * FROM examen WHERE id_classe_matiere=".$_GET['s']." ORDER BY id_examen ASC";

				$ex = $bdd -> query($re1);
				$u=0;
				while ($exa = $ex->fetch()){

					$re2 = "SELECT * FROM resultat_ex WHERE id_examen=".$exa['id_examen']." AND id_user = ".$_GET['id_user'];
					$q = $bdd -> query($re2);
					$rqex = $q->fetch();

					$re3 = "SELECT * FROM question_ex WHERE id_examen=".$exa['id_examen'];
					$qex = $bdd -> query($re3);
					$nbqex = $qex->rowCount();

					//$j = 0;
					if($nbqex !== 0){
					//echo $exa['id_examen'];
						$affich_el['examen'][$u][0] = $exa['id_examen'];
						$affich_el['examen'][$u][1] = $exa['titre_examen'];
						$affich_el['examen'][$u][2] = $rqex['note_resultat_ex'];
						$nb_totex += $nbqex;
						$u++;
					}
				//echo $nbm;
					//$u++;
				}
				//$j = 0;

			//echo $nbm;

			//$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			$affich_el['nb'] = $nb_totex;
			echo json_encode($affich_el);


		}  else if ($_GET['table'] == 'corrige'){
			$i=2;

			$affich_el['reponse']=1;
			$nb_totex=0;

				$re1 = "SELECT * FROM corrige INNER JOIN classe_matiere
												ON corrige.id_classe_matiere = classe_matiere.id_classe_matiere
												WHERE classe_matiere.id_classe_matiere=".$_GET['s'];

				$ex = $bdd -> query($re1);
				$u=0;
				while ($exa = $ex->fetch()){

						$affich_el['corrige'][$u][0] = $exa['id_corrige'];
						$affich_el['corrige'][$u][1] = $exa['titre_corrige'];
						$affich_el['corrige'][$u][2] = $exa['fichier_corrige'];
						$affich_el['corrige'][$u][3] = $exa['id_classe_matiere'];

						$u++;

				//echo $nbm;
				}
				//$j = 0;
			echo json_encode($affich_el);


		} else if ($_GET['table'] == 'user_lecon'){
			//var_dump($_GET);
			$re = "SELECT * FROM user_lecon INNER JOIN lecon
										  ON lecon.id_lecon = user_lecon.id_lecon
										  WHERE user_lecon.id_user=".$_GET['id']." ORDER BY user_lecon.id_user_lecon DESC";
			$lv = $bdd -> query($re);
			$nblv = $lv->rowCount();
			//echo $nblv;
			if($nblv > 0){
				$affich_el[0]=1;
				while ($chap = $lv->fetch() AND $i<4){
					$re1 = "SELECT * FROM quizz WHERE id_lecon=".$chap['id_lecon'];
						$nb = $bdd -> query($re1);
						$nbq = $nb->rowCount();

					//$j = 0;
					$affich_el[$i]['titre_lecon'] = $chap['titre_lecon'];
					$affich_el[$i]['id_lecon'] = $chap['id_lecon'];
					$affich_el[$i]['temp_lecon'] = $chap['temps_lecon'];
					$affich_el[$i]['cours_lecon'] = $chap['cours_lecon'];
					$affich_el[$i]['nb_quizz'] = $nbq;

					$i++;
				}
			} else {
					$re1 = "SELECT * FROM lecon INNER JOIN chapitre
												ON lecon.id_chapitre = chapitre.id_chapitre
												INNER JOIN classe_matiere
											  ON classe_matiere.id_classe_matiere = chapitre.id_classe_matiere
											  INNER JOIN user_matiere
											  ON user_matiere.id_classe_matiere = classe_matiere.id_classe_matiere
											  WHERE user_matiere.id_user=".$_GET['id']." AND classe_matiere.id_classe_matiere =".$_GET['id_classe_matiere']." ORDER BY lecon.id_lecon DESC";
					$lv = $bdd -> query($re1);
					$nblu = $lv -> rowCount();

					$affich_el[0]=1;
					while ($chap = $lv->fetch() AND $i<4){
						$re1 = "SELECT * FROM quizz WHERE id_lecon=".$chap['id_lecon'];
							$nb = $bdd -> query($re1);
							$nbq = $nb->rowCount();
						//$j = 0;
						$affich_el[$i]['titre_lecon'] = $chap['titre_lecon'];
						$affich_el[$i]['id_lecon'] = $chap['id_lecon'];
						$affich_el[$i]['temp_lecon'] = $chap['temps_lecon'];
						$affich_el[$i]['cours_lecon'] = $chap['cours_lecon'];
						$affich_el[$i]['nb_quizz'] = $nbq;

						$i++;

					}
				if($nblu == 0){
					$affich_el[$i]['titre_lecon'] = "Aucune leçon";
						$affich_el[$i]['id_lecon'] = 0;
				}
				//$affich_e = array_shift($affich_el);
				//var_dump($affich_e);
				//var_dump($affich_el);
				echo json_encode($affich_el);
			}
		} else if($_GET['table'] == 'user'){
			$re = "SELECT * FROM user WHERE id_user = ".$_GET['id_user'];
			$us = $bdd -> query($re);
			$user = $us->fetch();

			/*
			$re1 = "SELECT * FROM resultat_ex WHERE id_user = ".$_GET['id_user'];
			$rex = $bdd -> query($re1);
			$rqex = $rex->rowCount();

			$re2 = "SELECT * FROM resultat_q WHERE id_user = ".$_GET['id_user'];
			$rq = $bdd -> query($re2);
			$rqz = $rq->rowCount();

			$re3 = "SELECT * FROM user_matiere WHERE id_user = ".$_GET['id_user'];
			$usm = $bdd -> query($re3);
			$userm = $usm->rowCount();


			$re4 = "SELECT * FROM examen INNER JOIN classe_matiere
										ON classe_matiere.id_classe_matiere = examen.id_classe_matiere
										INNER JOIN user_matiere
										ON user_matiere.id_classe_matiere = classe_matiere.id_classe_matiere
										WHERE user_matiere.id_user=".$_GET['id_user'];
			$ex = $bdd -> query($re4);
			$nbex = $ex->rowCount();

			$re5 = "SELECT * FROM quizz INNER JOIN lecon
										ON lecon.id_lecon = quizz.id_lecon
										INNER JOIN chapitre
										ON lecon.id_chapitre = chapitre.id_chapitre
										INNER JOIN classe_matiere
										ON classe_matiere.id_classe_matiere = chapitre.id_classe_matiere
										INNER JOIN user_matiere
										ON user_matiere.id_classe_matiere = classe_matiere.id_classe_matiere
										WHERE user_matiere.id_user=".$_GET['id_user'];
			$q = $bdd -> query($re5);
			$nbqz = $q->rowCount();

			$re6 = "SELECT * FROM matiere INNER JOIN classe_matiere
										  ON classe_matiere.id_matiere = matiere.id_matiere
										  INNER JOIN classe
										  ON classe.id_classe = classe_matiere.id_classe
										  INNER JOIN user_classe
										  ON classe.id_classe = user_classe.id_classe
										  WHERE user_classe.id_user = ".$_GET['id_user'];
			$m = $bdd -> query($re6);
			$nbma = $m->rowCount();
			* /
			$re4 = "SELECT * FROM examen INNER JOIN classe_matiere
										ON classe_matiere.id_classe_matiere = examen.id_classe_matiere
										INNER JOIN user_matiere
										ON user_matiere.id_classe_matiere = classe_matiere.id_classe_matiere
										WHERE user_matiere.id_user=".$_GET['id_user'];
			$ex = $bdd -> query($re4);
			$nbex = $ex->rowCount();

			$re5 = "SELECT * FROM quizz INNER JOIN lecon
										ON lecon.id_lecon = quizz.id_lecon
										INNER JOIN chapitre
										ON lecon.id_chapitre = chapitre.id_chapitre
										INNER JOIN classe_matiere
										ON classe_matiere.id_classe_matiere = chapitre.id_classe_matiere
										INNER JOIN user_matiere
										ON user_matiere.id_classe_matiere = classe_matiere.id_classe_matiere
										WHERE user_matiere.id_user=".$_GET['id_user'];
			$q = $bdd -> query($re5);
			$nbqz = $q->rowCount();

			$re6 = "SELECT * FROM classe_matiere INNER JOIN matiere
										ON classe_matiere.id_matiere = matiere.id_matiere
										INNER JOIN user_matiere
										ON user_matiere.id_classe_matiere = classe_matiere.id_classe_matiere
										WHERE user_matiere.id_user=".$_GET['id_user'];
			$m = $bdd -> query($re6);

			$i=0;
			while($mat = $m->fetch()){
				$affich_el['cours'][$i]['id']=$mat['id_classe_matiere'];
				$affich_el['cours'][$i]['mat']=$mat['titre_matiere'];

				$re7 = "SELECT * FROM lecon INNER JOIN chapitre
										ON lecon.id_chapitre = chapitre.id_chapitre
										INNER JOIN classe_matiere
										ON classe_matiere.id_classe_matiere = chapitre.id_classe_matiere
										WHERE classe_matiere.id_classe_matiere = ".$mat['id_classe_matiere'];
				$lec = $bdd -> query($re7);
				$nblec = $lec->rowCount();

				$affich_el['cours'][$i]['nblec']=$nblec;

				$i++;
			}

			$u=0;
			$moy1ex =0;
			$nbrsex =0;
			while($exam = $ex->fetch()){

				$re8 = "SELECT * FROM question_ex WHERE id_examen = ".$exam['id_examen'];
				$qex = $bdd -> query($re8);
				$nbqex = $qex->rowCount();
				$re1 = "SELECT * FROM resultat_ex WHERE id_user= ".$_GET['id_user']." AND id_examen = ".$exam['id_examen'];
				$rex = $bdd -> query($re1);
				$rqex = $rex->fetch();
				$nbrsex += $rex->rowCount();

				$moy1ex += ($nbqex !== 0) ? ($rqex['note_resultat_ex']*20) / $nbqex : 0;

				$u++;
			}
				$moyex = ($nbex !== 0) ? $moy1ex/$nbex : -1;

				$affich_el['moyex']= ($nbrsex > 0) ? round($moyex, 2) : -1;

			$o=0;
			$moy1q =0;
			$nbrsq =0;
			while($qz = $q->fetch()){

				$re9 = "SELECT * FROM question_q WHERE id_quizz = ".$qz['id_quizz'];
				$qq = $bdd -> query($re9);
				$nbqq = $qq->rowCount();
				$re2 = "SELECT * FROM resultat_q WHERE id_user= ".$_GET['id_user']." AND id_quizz = ".$qz['id_quizz'];
				$rq = $bdd -> query($re2);
				$rqq = $rq->fetch();
				$nbrsq = $rq->rowCount();

				$moy1q += ($nbqq !== 0) ? ($rqq['score_resultat_q']*20) / $nbqq : 0;
				//echo $moy1q." - score: ".$rqq['score_resultat_q']."<br>";
				$o++;
			}
				$moyq = ($nbqz !== 0) ? $moy1q/$nbqz : -1;
				$affich_el['moyq']= ($nbrsq > 0) ? round($moyq,2) : -1;



			$affich_el['reponse']=1;
			$affich_el['user']=$user;


			echo json_encode($affich_el);


		} else if($_GET['table'] == 'user1'){

				$re4 = "SELECT * FROM examen INNER JOIN classe_matiere
											ON classe_matiere.id_classe_matiere = examen.id_classe_matiere
											WHERE classe_matiere.id_classe_matiere=".$_GET['id_mat'];
				$ex = $bdd -> query($re4);
				$nbex = $ex->rowCount();

				$affich_el['exam'][0][0] = "Nombre d'examen";
				$affich_el['exam'][0][1] = $nbex;

				$re10 = "SELECT * FROM resultat_ex INNER JOIN examen
													ON examen.id_examen = resultat_ex.id_examen
													INNER JOIN classe_matiere
													ON classe_matiere.id_classe_matiere = examen.id_classe_matiere
													WHERE classe_matiere.id_classe_matiere = ".$_GET['id_mat']." ORDER BY resultat_ex.id_resultat_ex DESC";
					$rex1 = $bdd -> query($re10);
					$rqex1 = $rex1->fetch();
					$affich_el['exam'][1][0] = "Note du dernier examen";
					$affich_el['exam'][1][1] = ($rqex1['note_resultat_ex'] !== null) ? $rqex1['note_resultat_ex'] : -1;

				$re5 = "SELECT * FROM quizz INNER JOIN lecon
											ON lecon.id_lecon = quizz.id_lecon
											INNER JOIN chapitre
											ON lecon.id_chapitre = chapitre.id_chapitre
											INNER JOIN classe_matiere
											ON classe_matiere.id_classe_matiere = chapitre.id_classe_matiere
											WHERE classe_matiere.id_classe_matiere=".$_GET['id_mat'];
				$q = $bdd -> query($re5);
				$nbqz = $q->rowCount();
				$affich_el['quizz'][0][0] = "Nombre de quizz";
				$affich_el['quizz'][0][1] = $nbqz;

				$re11 = "SELECT * FROM resultat_q INNER JOIN quizz
													ON quizz.id_quizz = resultat_q.id_quizz
													INNER JOIN lecon
													ON lecon.id_lecon = quizz.id_lecon
													INNER JOIN chapitre
													ON lecon.id_chapitre = chapitre.id_chapitre
													INNER JOIN classe_matiere
													ON classe_matiere.id_classe_matiere = chapitre.id_classe_matiere
													WHERE classe_matiere.id_classe_matiere = ".$_GET['id_mat']." ORDER BY resultat_q.id_resultat_q DESC";
					$rq1 = $bdd -> query($re11);
					$rqq1 = $rq1->fetch();
					$affich_el['quizz'][1][0] = "Note du dernier quizz";
					$affich_el['quizz'][1][1] = ($rqq1['score_resultat_q'] !== null) ? $rqq1['score_resultat_q'] : -1;

				$re6 = "SELECT * FROM classe_matiere INNER JOIN matiere
											ON classe_matiere.id_matiere = matiere.id_matiere
											INNER JOIN user_matiere
											ON user_matiere.id_classe_matiere = classe_matiere.id_classe_matiere
											WHERE user_matiere.id_user=".$_GET['id_user'];
				$m = $bdd -> query($re6);


				$u=0;
				$moy1ex =0;
				while($exam = $ex->fetch()){

					$re8 = "SELECT * FROM question_ex WHERE id_examen = ".$exam['id_examen'];
					$qex = $bdd -> query($re8);
					$nbqex = $qex->rowCount();
					$re1 = "SELECT * FROM resultat_ex WHERE id_examen = ".$exam['id_examen'];
					$rex = $bdd -> query($re1);
					$rqex = $rex->fetch();
					$nbrsex = $rex->rowCount();

					$moy1ex += ($rqex['note_resultat_ex']*20) / $nbqex;

					$u++;
				}
					$moyex = $moy1ex/$nbex;

					$affich_el['exam'][2][0] = "Moyenne";
					$affich_el['exam'][2][1] = ($nbrsex > 0) ? $moyex : -1;

				$o=0;
				$moy1q =0;
				while($qz = $q->fetch()){

					$re9 = "SELECT * FROM question_q WHERE id_quizz = ".$qz['id_quizz'];
					$qq = $bdd -> query($re9);
					$nbqq = $qq->rowCount();
					$re2 = "SELECT * FROM resultat_q WHERE id_quizz = ".$qz['id_quizz'];
					$rq = $bdd -> query($re2);
					$rqq = $rq->fetch();
					$nbrsq = $rq->rowCount();

					$moy1q += ($rqq['score_resultat_q']*20) / $nbqq;

					$o++;
				}
					$moyq = $moy1q/$nbqz;
					$affich_el['quizz'][2][0] = "Moyenne";
					$affich_el['quizz'][2][1] = ($nbrsq > 0) ? $moyq : -1;



				$affich_el['reponse']=1;


				echo json_encode($affich_el);

			}
			*/
	}

	function listAASfiltre(){
		require 'connexion.php';
		$i =0;
		$nbs =0;
		$nba =0;
		$nbt =0;
		$affich_el[] = '';
    $tx = 1;
    if (isset($_POST['ip']) AND $_POST['ip'] !== "") {


      $curl = curl_init();
      $opts = [
          CURLOPT_URL => 'http://ip-api.com/json/'.$_POST['ip'],
          CURLOPT_RETURNTRANSFER => true,
        //  CURLOPT_HTTPHEADER => $headers,

      ];

      curl_setopt_array($curl, $opts);

      $response = json_decode(curl_exec($curl));
      curl_close($curl);
      //var_dump($response);

      $reqP = "SELECT * FROM pays WHERE code_pays ='".$response -> countryCode."'";
      $pa = $bdd -> query($reqP);
      $pay = $pa->fetch();
      $nbpay = $pa->rowCount();

      //var_dump($pay);
      if($nbpay !== 0 AND $pay['id_devise'] !== 1) {
        $reqT = "SELECT * FROM taux WHERE from_taux = 1 AND to_taux =".$pay['id_devise'];
        $ta = $bdd -> query($reqT);
        $taux = $ta->fetch();
        $tx = $taux['taux_taux'];
      }
      //var_dump($tx);
    }

		if($_GET['table'] == 'son'){


      if(isset($_GET['alb']) AND $_GET['alb'] == 1){
        $re = "SELECT * FROM son LEFT JOIN album
  										  ON son.id_album = album.id_album
  										  INNER JOIN artiste
  										  ON artiste.id_artiste = son.id_artiste
  										  LEFT JOIN lyric
  										  ON lyric.id_son = son.id_son WHERE son.id_album = '".$_GET['indx']."' ORDER BY son.titre_son";

      } else if(isset($_GET['art']) AND $_GET['art'] == 1) {
        // $re2 = "SELECT * FROM son WHERE son.id_son = '".$_GET['indx']."'";
        // $son1 = $bdd -> query($re2);
        // $sons1 = $son1->fetch();

        $re = "SELECT * FROM son LEFT JOIN album
  										  ON son.id_album = album.id_album
  										  LEFT JOIN artiste
                        ON artiste.id_artiste = son.id_artiste
                        LEFT JOIN lyric
                        ON lyric.id_son = son.id_son WHERE son.id_artiste = '".$_GET['indx']."'";

        $reA = "SELECT * FROM  album LEFT JOIN artiste
                        ON artiste.id_artiste = album.id_artiste
                        WHERE album.id_artiste = '".$_GET['indx']."'";
                  			$al = $bdd -> query($reA);
                  			$nba = $al -> rowCount();

                        $re3 =  "SELECT * FROM son LEFT JOIN album
                  										  ON son.id_album = album.id_album
                  										  LEFT JOIN artiste
                                        ON artiste.id_artiste = son.id_artiste
                                        LEFT JOIN lyric
                                        ON lyric.id_son = son.id_son WHERE son.id_artiste = '".$_GET['indx']."'";
                        $son3 = $bdd -> query($re3);
                        $nbson3 = $son3 -> rowCount();
                        if($nbson3 !== 0){
                          $sons3 = $son3->fetch();
                          //var_dump($sons3);
                          $re4 =  "SELECT DISTINCT(son.id_artiste), artiste.nom_artiste, artiste.cover_artiste, artiste.lien_artiste, artiste.biographie_artiste FROM son
                                          LEFT JOIN artiste
                    										  ON artiste.id_artiste = son.id_artiste
                                          WHERE son.id_genre = '".$sons3[13]."' AND artiste.id_artiste !='".$_GET['indx']."' ORDER BY rand()";


                          $art = $bdd -> query($re4);
                           $nbart = $art -> rowCount();

                          if ($nbart == 0) {
                            $re4 =  "SELECT DISTINCT(son.id_artiste), artiste.nom_artiste, artiste.cover_artiste, artiste.lien_artiste, artiste.biographie_artiste FROM son
                                            LEFT JOIN artiste
                                            ON artiste.id_artiste = son.id_artiste
                                            WHERE artiste.id_artiste !='".$_GET['indx']."' ORDER BY rand()";
                                            $art = $bdd -> query($re4);
                          }

                          $i=0;
                          while ($artist = $art->fetch() AND $i < 5){
                            //var_dump ($sons);



                            $affich_el['artistes'][$i]['id'] = $artist[0];
                    				$affich_el['artistes'][$i]['name'] = utf8_decode($artist['nom_artiste']);
                    				$affich_el['artistes'][$i]['cover_url'] = $artist['cover_artiste'];
                    				//$affich_el['artistes'][$i]['bio'] = utf8_decode($artist['biographie_artiste']);
                    				$affich_el['artistes'][$i]['lien'] = utf8_decode($artist['lien_artiste']);

                            $i++;

                          }

                        } else {

                            $re4 =  "SELECT DISTINCT(son.id_artiste), artiste.nom_artiste, artiste.cover_artiste, artiste.lien_artiste, artiste.biographie_artiste FROM son
                                            LEFT JOIN artiste
                                            ON artiste.id_artiste = son.id_artiste
                                            WHERE artiste.id_artiste !='".$_GET['indx']."' ORDER BY rand()";
                                            $art = $bdd -> query($re4);
                            $i=0;
                            while ($artist = $art->fetch() AND $i < 5){
                              //var_dump ($sons);



                              $affich_el['artistes'][$i]['id'] = $artist[0];
                      				$affich_el['artistes'][$i]['name'] = utf8_decode($artist['nom_artiste']);
                      				$affich_el['artistes'][$i]['cover_url'] = $artist['cover_artiste'];
                      				//$affich_el['artistes'][$i]['bio'] = utf8_decode($artist['biographie_artiste']);
                      				$affich_el['artistes'][$i]['lien'] = utf8_decode($artist['lien_artiste']);

                              $i++;

                            }

                        }


      } else {
        $re2 = "SELECT * FROM son WHERE son.id_son = '".$_GET['indx']."'";
        $son1 = $bdd -> query($re2);
        $sons1 = $son1->fetch();

        $re = "SELECT * FROM son LEFT JOIN album
  										  ON son.id_album = album.id_album
  										  LEFT JOIN artiste
                        ON artiste.id_artiste = son.id_artiste
                        LEFT JOIN lyric
                        ON lyric.id_son = son.id_son WHERE son.id_artiste = '".$sons1['id_artiste']."'";


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
			} else if($nba == 0) {
        while ($sons = $son->fetch() ){
  				//var_dump ($sons);


  				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
  				$nb = $bdd -> query($re1);
  				$nbd = $nb->rowCount();
  				//$j = 0;
  				$affich_el['asons'][$i]['id'] = $sons[0];
  				$affich_el['asons'][$i]['name'] = utf8_decode($sons['titre_son']);
  				$affich_el['asons'][$i]['artist'] = utf8_decode($sons['nom_artiste']);
  				$affich_el['asons'][$i]['album'] = utf8_decode($sons['titre_album']);
  				$affich_el['asons'][$i]['url'] = utf8_decode($sons['url_son']);
  				$affich_el['asons'][$i]['cover_art_url'] = utf8_decode($sons['cover_artiste']);
  				$affich_el['asons'][$i]['cover_url'] = $sons['cover_son'];
  				$affich_el['asons'][$i]['price'] = $sons['prix_son']*$tx;
  				$affich_el['asons'][$i]['downloads'] = $nbd;
  				$affich_el['asons'][$i]['lyrics'] = utf8_decode($sons['texte_lyric']);
  				$affich_el['asons'][$i]['reference'] = $sons['reference_son'];
  				$affich_el['asons'][$i]['lien'] = $sons['lien_son'];

  				$i++;

  			}
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
          //   $affich_el['albums'][$i]['name'] = utf8_decode($alb['titre_album']);
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

      } else if ($nbs == 0) {
          $affich_el['asons']=0;
          if(isset($_GET['art'])){
            $reA = "SELECT * FROM album INNER JOIN artiste
                            ON artiste.id_artiste = album.id_artiste WHERE  album.id_artiste = '".$_GET['indx']."' ORDER BY album.titre_album";
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
            while ($alb2 = $al->fetch() ){

              $re12 = "SELECT * FROM telechargement WHERE id_album=".$alb2['id_album'];
              $nb2 = $bdd -> query($re12);
              $nbd2 = $nb2->rowCount();
              //$j = 0;
              //var_dump($alb);
              $affich_el['albums'][$i]['id'] = $alb2[0];
              $affich_el['albums'][$i]['name'] = utf8_decode($alb2['titre_album']);
              $affich_el['albums'][$i]['artist'] = utf8_decode($alb2['nom_artiste']);
              $affich_el['albums'][$i]['cover_art_url'] = utf8_decode($alb2['cover_artiste']);
              $affich_el['albums'][$i]['cover_url'] = utf8_decode($alb2['cover_album']);
              $affich_el['albums'][$i]['url'] = utf8_decode($alb2['url_album']);
              $affich_el['albums'][$i]['price'] = $alb2['prix_album']*$tx;
              $affich_el['albums'][$i]['downloads'] = $nbd2;
              $affich_el['albums'][$i]['reference'] = $alb2['reference_album'];
              $affich_el['albums'][$i]['lien'] = $alb2['lien_album'];

              $i++;

            }
          }
      } else {

			while ($sons = $son->fetch() ){
        //echo "son <br>";
				//var_dump ($sons);


				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
				$nb = $bdd -> query($re1);
				$nbs = $nb->rowCount();
				//$j = 0;
				$affich_el['asons'][$i]['id'] = $sons[0];
				$affich_el['asons'][$i]['name'] = utf8_decode($sons['titre_son']);
				$affich_el['asons'][$i]['artist'] = utf8_decode($sons['nom_artiste']);
				$affich_el['asons'][$i]['album'] = utf8_decode($sons['titre_album']);
				$affich_el['asons'][$i]['url'] = utf8_decode($sons['url_son']);
				$affich_el['asons'][$i]['cover_art_url'] = utf8_decode($sons['cover_artiste']);
				$affich_el['asons'][$i]['cover_url'] = $sons['cover_son'];
				$affich_el['asons'][$i]['price'] = $sons['prix_son']*$tx;
				$affich_el['asons'][$i]['downloads'] = $nbs;
				$affich_el['asons'][$i]['lyrics'] = utf8_decode($sons['texte_lyric']);
				$affich_el['asons'][$i]['reference'] = $sons['reference_son'];
				$affich_el['asons'][$i]['lien'] = $sons['lien_son'];

				$i++;

			}
      if(isset($_GET['art'])){
        // $reA = "SELECT * FROM  album LEFT JOIN artiste
        //                 ON artiste.id_artiste = album.id_artiste
        //                 WHERE album.id_artiste = '".$_GET['indx']."'";
        //           			$al = $bdd -> query($reA);
        $reA = "SELECT * FROM album INNER JOIN artiste
                        ON artiste.id_artiste = album.id_artiste WHERE  album.id_artiste = '".$_GET['indx']."' ORDER BY album.titre_album";
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
        while ($alb2 = $al->fetch() ){

          $re12 = "SELECT * FROM telechargement WHERE id_album=".$alb2['id_album'];
          $nb2 = $bdd -> query($re12);
          $nbd2 = $nb2->rowCount();
          //$j = 0;
          //echo "album <br>";

          //var_dump($alb2);
          $affich_el['albums'][$i]['id'] = $alb2[0];
          $affich_el['albums'][$i]['name'] = utf8_decode($alb2['titre_album']);
          $affich_el['albums'][$i]['artist'] = utf8_decode($alb2['nom_artiste']);
          $affich_el['albums'][$i]['cover_art_url'] = utf8_decode($alb2['cover_artiste']);
          $affich_el['albums'][$i]['cover_url'] = utf8_decode($alb2['cover_album']);
          $affich_el['albums'][$i]['url'] = utf8_decode($alb2['url_album']);
          $affich_el['albums'][$i]['price'] = $alb2['prix_album']*$tx;
          $affich_el['albums'][$i]['downloads'] = $nbd2;
          $affich_el['albums'][$i]['reference'] = $alb2['reference_album'];
          $affich_el['albums'][$i]['lien'] = $alb2['lien_album'];

          $i++;

        }
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
 				$affich_el[$i]['name'] = utf8_decode($tick['nom_ticket']);
 				$affich_el[$i]['image'] = utf8_decode($tick['image_ticket']);
 				$affich_el[$i]['price'] = $tick['prix_ticket'];

 				$i++;

 			}
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
				$affich_el['name'] = utf8_decode($event['nom_event']);
				$affich_el['image'] = utf8_decode($event['cover_event']);
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

			$re = "SELECT * FROM artiste ORDER BY nom_artiste";
			$ar = $bdd -> query($re);
			$nbar = $ar -> rowCount();
			//echo $re;
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
				$affich_el[$i]['name'] = utf8_decode($artis['nom_artiste']);
				$affich_el[$i]['dob'] = $artis['dob_artiste'];
				$affich_el[$i]['cover_url'] = $artis['cover_artiste'];
				$affich_el[$i]['bio'] = utf8_decode($artis['biographie_artiste']);
				$affich_el[$i]['lien'] = utf8_decode($artis['lien_artiste']);

				$i++;

			}
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);

		}
		else if($_GET['table'] == 'album'){

			$re = "SELECT * FROM album INNER JOIN artiste
										  ON artiste.id_artiste = album.id_artiste ORDER BY album.titre_album";
			$al = $bdd -> query($re);
			$nba = $al -> rowCount();
			//echo $re;
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

				$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
				$nb = $bdd -> query($re1);
				$nba = $nb->rowCount();
				//$j = 0;
  			//var_dump($alb);
				$affich_el[$i]['id'] = $alb[0];
				$affich_el[$i]['name'] = $alb['titre_album'];
				$affich_el[$i]['artist'] = $alb['nom_artiste'];
				$affich_el[$i]['cover_art_url'] = $alb['cover_artiste'];
				$affich_el[$i]['cover_url'] = $alb['cover_album'];
				$affich_el[$i]['url'] = $alb['url_album'];
				$affich_el[$i]['price'] = $alb['prix_album']*$tx;
				$affich_el[$i]['downloads'] = $nba;
				$affich_el[$i]['reference'] = $alb['reference_album'];
				$affich_el[$i]['lien'] = $alb['lien_album'];

				$i++;

			}
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
			}
			$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);



		}
	}


	function listPT(){
		require 'connexion.php';
		$i =2;
		$affich_el[] = '';
    $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_POST['ref']."' AND statut_transaction = 'SUCCESS'";
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
		$elt = $un->fetch();

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


  				$re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
  				$nb = $bdd -> query($re1);
  				$nbs = $nb->rowCount();
  				//$j = 0;
  				$affich_el[$i]['id'] = $sons['0'];
  				$affich_el[$i]['name'] = utf8_decode($sons['titre_son']);
  				$affich_el[$i]['artist'] = utf8_decode($sons['nom_artiste']);
  				$affich_el[$i]['album'] = utf8_decode($sons['titre_album']);
  				$affich_el[$i]['url'] = utf8_decode($sons['url_son']);
  				$affich_el[$i]['cover_art_url'] = utf8_decode($sons['cover_artiste']);
  				$affich_el[$i]['cover_url'] = utf8_decode($sons['cover_son']);
  				$affich_el[$i]['price'] = $sons['prix_son'];
  				$affich_el[$i]['f'] = utf8_decode($sons['fichier_son']);
  				$affich_el[$i]['downloads'] = $nbs;
  				$affich_el[$i]['lyrics'] = utf8_decode($sons['texte_lyric']);
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

				$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
				$nb = $bdd -> query($re1);
				$nba = $nb->rowCount();
				//$j = 0;
  			//var_dump($alb);
				$affich_el[$i]['id'] = $alb['id_album'];
				$affich_el[$i]['name'] = utf8_decode($alb['titre_album'])." (l'album entier)";
				$affich_el[$i]['artist'] = utf8_decode($alb['nom_artiste']);
				$affich_el[$i]['cover_art_url'] = utf8_decode($alb['cover_artiste']);
				$affich_el[$i]['cover_url'] = utf8_decode($alb['cover_album']);
				$affich_el[$i]['url'] = utf8_decode($alb['url_album']);
				$affich_el[$i]['f'] = utf8_decode($alb['fichier_album']);
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
  				$affich_el[$i]['name'] = utf8_decode($sons['titre_son']);
  				$affich_el[$i]['artist'] = utf8_decode($sons['nom_artiste']);
  				$affich_el[$i]['album'] = utf8_decode($sons['titre_album']);
  				$affich_el[$i]['url'] = utf8_decode($sons['url_son']);
  				$affich_el[$i]['cover_art_url'] = utf8_decode($sons['cover_artiste']);
  				$affich_el[$i]['cover_url'] = utf8_decode($sons['cover_son']);
  				$affich_el[$i]['price'] = $sons['prix_son'];
  				$affich_el[$i]['f'] = utf8_decode($sons['fichier_son']);
  				$affich_el[$i]['downloads'] = $nbs;
  				$affich_el[$i]['lyrics'] = utf8_decode($sons['texte_lyric']);
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
      $re = "SELECT * FROM son LEFT JOIN album
                      ON son.id_album = album.id_album
                      LEFT JOIN artiste
                      ON artiste.id_artiste = son.id_artiste
                      LEFT JOIN lyric
                      ON lyric.id_son = son.id_son
                      WHERE artiste.id_artiste = ".$id;
      $son = $bdd -> query($re);
      $nbson = $son -> rowCount();
      //echo $re;

      $sons = $son->fetch();
        //var_dump ($sons);



        //$j = 0;
        $affich_el['s'][$i]['id'] = $sons['0'];
        $affich_el['s'][$i]['name'] = utf8_decode($sons['titre_son']);
        $affich_el['s'][$i]['artist'] = utf8_decode($sons['nom_artiste']);
        $affich_el['s'][$i]['album'] = utf8_decode($sons['titre_album']);
        $affich_el['s'][$i]['url'] = utf8_decode($sons['url_son']);
        $affich_el['s'][$i]['cover_art_url'] = utf8_decode($sons['cover_artiste']);
        $affich_el['s'][$i]['cover_url'] = utf8_decode($sons['cover_son']);
        $affich_el['s'][$i]['price'] = $sons['prix_son'];
        $affich_el['s'][$i]['f'] = utf8_decode($sons['fichier_son']);
        $affich_el['s'][$i]['lyrics'] = utf8_decode($sons['texte_lyric']);
        $affich_el['s'][$i]['reference'] = $sons['reference_son'];

        $i++;



      // $affich_e = array_shift($affich_el);


      $affich_el['don']="don";
      $affich_e = array_shift($affich_el);
      echo json_encode($affich_el);

    }
    } else {
      $affich_el['error']=1;
      $affich_e = array_shift($affich_el);
      echo json_encode($affich_el);
    }


	}


  function referenceTrsx(){
    require 'connexion.php';
    $tabl = $_POST['table'];
    $datee = $ddj = date ('Y-m-d H:i:s');
    $ref = "AFPTX-";
    $ref .= genererChaineAleatoire();
    $statutt = "ATTENTE";
    $affich_el[0]=1;
    $d = (isset($_GET['don'])) ? "-don" : "" ;
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


    if (isset($_GET['art'])) {
      $montant = $_POST['montant'];
      $lib = $tabl."-".$_POST['id'].$d;
      $idEtr = $_POST['id'];
    } else {
      $re = "SELECT * FROM ".$tabl." WHERE id_".$tabl." = ".$_POST['id'];
  		$un = $bdd -> query($re);
  		//$nbuser = $un->rowCount();
  		$elt = $un->fetch();
      //var_dump($re);
      $montant = ($lpays['taux_taux'] == 0.1) ? $elt['prix_'.$tabl] : $elt['prix_'.$tabl]*$lpays['taux_taux'];
      $idEtr = ($d == "") ? $elt['id_'.$tabl] : $elt['id_artiste'] ;
      $lib = $tabl."-".$elt['titre_'.$tabl]."-".$idEtr.$d;
    }
    if(isset($_GET['don'])){
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
        //$don = "&don=don";

    }


    $req_ajc = "INSERT INTO transaction (date_transaction, nom_transaction, telephone_transaction, montant_transaction, reference_transaction, statut_transaction, libelle_transaction, ip_transaction, id_pays) VALUES (:date_transaction, :nom_transaction, :telephone_transaction, :montant_transaction, :reference_transaction, :statut_transaction, :libelle_transaction, :ip_transaction, :id_pays)";
			$req_aj_cod = $bdd -> prepare($req_ajc);
			$req_aj_cod ->bindParam(':date_transaction', $datee);
			$req_aj_cod ->bindParam(':nom_transaction', $_POST['nom']);
			$req_aj_cod ->bindParam(':telephone_transaction', $_POST['numero']);
			$req_aj_cod ->bindParam(':montant_transaction', $montant);
			$req_aj_cod ->bindParam(':reference_transaction', $ref);
			$req_aj_cod ->bindParam(':statut_transaction', $statutt);
			$req_aj_cod ->bindParam(':libelle_transaction', $lib);
			$req_aj_cod ->bindParam(':ip_transaction', $ip);
			$req_aj_cod ->bindParam(':id_pays', $lpays['id_pays']);
			$req_aj_cod ->execute();



      $affich_e = array_shift($affich_el);
      $affich_el['reference']=$ref;
      //$affich_el['reference']=$_POST['numero'];
      echo json_encode($affich_el);
  }

  function checkStat(){
    require 'connexion.php';
		//$i =2;
    //var_dump($_POST);
		$affich_el[] = '';
    $re = "SELECT * FROM transaction WHERE reference_transaction = '".$_POST['ref']."'";
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
		$elt = $un->fetch();
    //var_dump($elt);
    $affich_e = array_shift($affich_el);
    $affich_el['statut']=$elt['statut_transaction'];
    //$affich_el['reference']=$_POST['numero'];
    echo json_encode($affich_el);
  }

  function lpays(){
    require 'connexion.php';
		//$i =2;
    //var_dump($_POST);
		$affich_el[] = '';
    $re = "SELECT * FROM pays INNER JOIN devise
                              ON devise.id_devise = pays.id_devise
                              INNER JOIN taux
                              ON taux.to_taux = devise.id_devise
                              ORDER BY pays.nom_pays";
		$un = $bdd -> query($re);
		//$nbuser = $un->rowCount();
    $affich_el[0]=0;
    $i=1;
    while ($elt = $un->fetch()) {
      $affich_el[$i]['name']=$elt['nom_pays'];
      $affich_el[$i]['indicatif']=$elt['indicatif_pays'];
      //$affich_el[$i]['drapeau']=$elt['image_pays'];
      $affich_el[$i]['devise']=$elt['signe_devise'];
      $affich_el[$i]['taux']=$elt['taux_taux'];
      $i++;
      //var_dump($elt);

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
    $ddj = date ('Y-m-d H:i:s');
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

		$re9 = "SELECT * FROM user WHERE telephone_user = ".$_GET['telephone'];
			$u = $bdd -> query($re9);
			$nbuser = $u->rowCount();
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

  		$req_ajd = "INSERT INTO user (nom_user, telephone_user, email_user, dob_user, trancheAge_user, sexe_user, datenreg_user, statut_user) VALUES (:nom_user, :telephone_user, :email_user, :dob_user, :trancheAge_user, :sexe_user, :datenreg_user, :statut_user)";
  		$req_aj_don = $bdd -> prepare($req_ajd);
  		$req_aj_don ->bindParam(':nom_user', $_GET['nom']);
  		$req_aj_don ->bindParam(':telephone_user', $_GET['telephone']);
  		$req_aj_don ->bindParam(':email_user', $_GET['mail']);
  		$req_aj_don ->bindParam(':dob_user', $jrMoisNaiss);
  		$req_aj_don ->bindParam(':trancheAge_user', $_GET['tranche']);
  		$req_aj_don ->bindParam(':sexe_user', $_GET['sexe']);
  		$req_aj_don ->bindParam(':datenreg_user', $ddj);
  		$req_aj_don ->bindParam(':statut_user', $statut);
  		$req_aj_don ->execute();

      $req_ajco = "INSERT INTO connexion (date_connexion, ip_connexion, active_connexion, id_user) VALUES (:date_connexion, :ip_connexion, :active_connexion, :id_user)";
  		$req_ajcon = $bdd -> prepare($req_ajco);
  		$req_ajcon ->bindParam(':date_connexion', $ddj);
  		$req_ajcon ->bindParam(':ip_connexion', $ip);
      $req_ajcon ->bindParam(':active_connexion', $act);
  		$req_ajcon ->bindParam(':id_user', $donnees['Auto_increment']);
      $req_ajcon ->execute();

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

		$re10 = "SELECT * FROM user WHERE telephone_user = ".$_GET['login'];
			$u = $bdd -> query($re10);
			$nbuser = $u->rowCount();


      if ($nbuser == 1) {
        $user = $u->fetch();

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
        $rep['error'] = 0;
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

      $numero1 = $_POST['n'];

      if(substr($_POST['n'], 0, 2) == "00"){
        $numero1 = substr($_POST['n'], 2);

      }
      if(substr($_POST['n'], 0, 2) == "22"){
        $numero1 = substr($_POST['n'], 3);

      }
      $lastmonth = date('Y-m-d', strtotime('-1 month'));
      //echo $lastmonth;
      $re = "SELECT DISTINCT libelle_transaction, telephone_transaction, statut_transaction, reference_transaction, id_transaction FROM transaction WHERE telephone_transaction LIKE '%".$numero1."' AND statut_transaction = 'SUCCESS' AND date_transaction >= '".$lastmonth."' ORDER BY id_transaction DESC";
  		$un = $bdd -> query($re);
  		//$nbuser = $un->rowCount();
  		while($elt = $un->fetch()){


        $lib = explode('-', $elt['libelle_transaction']);
        //$affich_el[1]=$lib[1];
        $affich_el[0]=1;
         // var_dump($lib);
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
      			//echo $re;

      			$sons = $son->fetch();
      				// var_dump ($sons);


      				// $re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
      				// $nb = $bdd -> query($re1);
      				// $nbs = $nb->rowCount();
      				//$j = 0;
      				$affich_el[$i]['id'] = $sons['0'];
      				$affich_el[$i]['name'] = utf8_decode($sons['titre_son']);
      				$affich_el[$i]['artist'] = utf8_decode($sons['nom_artiste']);
      				$affich_el[$i]['album'] = utf8_decode($sons['titre_album']);
      				$affich_el[$i]['url'] = utf8_decode($sons['url_son']);
      				$affich_el[$i]['cover_art_url'] = utf8_decode($sons['cover_artiste']);
      				$affich_el[$i]['cover_url'] = utf8_decode($sons['cover_son']);
      				$affich_el[$i]['price'] = $sons['prix_son'];
      				$affich_el[$i]['f'] = utf8_decode($sons['fichier_son']);
      				// $affich_el[$i]['downloads'] = $nbs;
      				$affich_el[$i]['lyrics'] = utf8_decode($sons['texte_lyric']);
      				$affich_el[$i]['reference'] = $sons['reference_son'];
      				$affich_el[$i]['referenceT'] = $elt['reference_transaction'];

      				$i++;



      			// $affich_e = array_shift($affich_el);
      			//var_dump($affich_e);
      			//var_dump($affich_el);
      		  //$affich_el = convert_from_latin1_to_utf8_recursively($affich_el);
      			// echo json_encode($affich_el);
            //echo json_last_error_msg();

      	   }
      		else if($lib[0] == 'album'){

    			$re = "SELECT * FROM album INNER JOIN artiste
    										  ON artiste.id_artiste = album.id_artiste
                          WHERE album.id_album = ".$id;
    			$al = $bdd -> query($re);
    			$nba = $al -> rowCount();
    			// echo $re;


    			$alb = $al->fetch();

    				$re1 = "SELECT * FROM telechargement WHERE id_album=".$alb['id_album'];
            // echo $re1;
    				$nb = $bdd -> query($re1);
    				$nba = $nb->rowCount();
    				//$j = 0;
      			// var_dump($alb);
    				$affich_el[$i]['id'] = $alb['id_album'];
    				$affich_el[$i]['name'] = utf8_decode($alb['titre_album'])." (l'album entier)";
    				$affich_el[$i]['artist'] = utf8_decode($alb['nom_artiste']);
    				$affich_el[$i]['cover_art_url'] = utf8_decode($alb['cover_artiste']);
    				$affich_el[$i]['cover_url'] = utf8_decode($alb['cover_album']);
    				$affich_el[$i]['url'] = utf8_decode($alb['url_album']);
    				$affich_el[$i]['f'] = utf8_decode($alb['fichier_album']);
    				$affich_el[$i]['price'] = $alb['prix_album'];
    				$affich_el[$i]['downloads'] = $nba;
    				$affich_el[$i]['reference'] = $alb['reference_album'];
            $affich_el[$i]['referenceT'] = $elt['reference_transaction'];

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
      				// var_dump ($sons);


      				// $re1 = "SELECT * FROM telechargements WHERE id_son =".$sons[0];
      				// $nb = $bdd -> query($re1);
      				// $nbs = $nb->rowCount();
      				//$j = 0;
      				$affich_el[$i]['id'] = $sons['0'];
      				$affich_el[$i]['name'] = utf8_decode($sons['titre_son']);
      				$affich_el[$i]['artist'] = utf8_decode($sons['nom_artiste']);
      				$affich_el[$i]['album'] = utf8_decode($sons['titre_album']);
      				$affich_el[$i]['url'] = utf8_decode($sons['url_son']);
      				$affich_el[$i]['cover_art_url'] = utf8_decode($sons['cover_artiste']);
      				$affich_el[$i]['cover_url'] = utf8_decode($sons['cover_son']);
      				$affich_el[$i]['price'] = $sons['prix_son'];
      				$affich_el[$i]['f'] = utf8_decode($sons['fichier_son']);
      				// $affich_el[$i]['downloads'] = $nbs;
      				$affich_el[$i]['lyrics'] = utf8_decode($sons['texte_lyric']);
      				$affich_el[$i]['reference'] = $sons['reference_son'];
      				$affich_el[$i]['referenceT'] = $elt['reference_transaction'];

      				$i++;

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
		if($_GET['table'] == 'lecon'){

			$re = "SELECT * FROM lecon WHERE id_lecon=".$_GET['id_lecon'];
			$lc = $bdd -> query($re);
			$lecon = $lc -> fetch();
			$re1 = "SELECT * FROM quizz WHERE id_lecon=".$_GET['id_lecon'];
			$qz = $bdd -> query($re1);
			$nbqz = $qz -> rowCount();
			//echo $re;
			$affich_el['reponse']=1;
			$affich_el['id_lecon']=$lecon['id_lecon'];
			$affich_el['titre_lecon']=$lecon['titre_lecon'];
			$affich_el['temps_lecon']=$lecon['temps_lecon'];
			//$affich_el['cours_lecon']=$lecon['cours_lecon'];
			$affich_el['nb_quizz']=$nbqz;

			//$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			echo json_encode($affich_el);

		} else if($_GET['table'] == 'quizz'){

			$re = "SELECT * FROM quizz WHERE id_quizz=".$_GET['id_quizz'];
			$qz = $bdd -> query($re);
			$quizz = $qz -> fetch();
			$re1 = "SELECT * FROM question_q WHERE id_quizz=".$_GET['id_quizz']." ORDER BY numero_question_q ASC";
			$qq = $bdd -> query($re1);
			$nbqq = $qq -> rowCount();
			$affich_el['reponse']=1;
			/*$affich_el['id_lecon']=$quizz['id_lecon'];
			$affich_el['titre_lecon']=$quizz['titre_lecon'];
			$affich_el['temps_lecon']=$quizz['temps_lecon'];
			$affich_el['cours_lecon']=$lecon['cours_lecon'];
			$affich_el['nb_quizz']=$nbqz;*/
			$i=0;
			while($questq = $qq -> fetch()){
				$questq_el[$i]['id']=$questq['id_question_q'];
				$questq_el[$i]['numero']=$questq['numero_question_q'];
				$questq_el[$i]['questionu']=$questq['questionu_question_q'];
				$questq_el[$i]['type']=$questq['type_question_q'];
				$questq_el[$i]['multiple']=($questq['multiple_question_q'] == 0) ? false : true;

				$re = "SELECT * FROM reponse_q WHERE id_question_q=".$questq['id_question_q']." ORDER BY numero_reponse_q ASC";
				$rqq = $bdd -> query($re);
				$ii=0;
				while($repqq = $rqq -> fetch()){
					$repqq_el[$ii]['id'] = $repqq['id_reponse_q'];
					$repqq_el[$ii]['reponseu'] = $repqq['reponseu_reponse_q'];
					$repqq_el[$ii]['numero'] = $repqq['numero_reponse_q'];
					$repqq_el[$ii]['explication'] = $repqq['explication_reponse_q'];
					$repqq_el[$ii]['type'] = ($repqq['type_reponse_q'] == 0) ? false : true;
					$ii++;
				}
				$questq_el[$i]['reponseq']= $repqq_el;
				$i++;
			}
			$affich_el['questions']=$questq_el;
			//echo $re;

			//$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			//echo json_encode($questq_el);
			echo json_encode($affich_el);

		}  else if($_GET['table'] == 'examen'){

			$re = "SELECT * FROM examen WHERE id_examen=".$_GET['id_examen'];
			$ex = $bdd -> query($re);
			$quizz = $ex -> fetch();
			$re1 = "SELECT * FROM question_ex WHERE id_examen=".$_GET['id_examen']." ORDER BY numero_question_ex ASC";
			$qex = $bdd -> query($re1);
			$nbqex = $qex -> rowCount();
			$affich_el['reponse']=1;
			/*$affich_el['id_lecon']=$quizz['id_lecon'];
			$affich_el['titre_lecon']=$quizz['titre_lecon'];
			$affich_el['temps_lecon']=$quizz['temps_lecon'];
			$affich_el['cours_lecon']=$lecon['cours_lecon'];
			$affich_el['nb_quizz']=$nbqz;*/
			$i=0;
			while($questex = $qex -> fetch()){
				$questex_el[$i]['id']=$questex['id_question_ex'];
				$questex_el[$i]['numero']=$questex['numero_question_ex'];
				$questex_el[$i]['questionu']=$questex['questionu_question_ex'];
				$questex_el[$i]['type']=$questex['type_question_ex'];
				$questex_el[$i]['multiple']=($questex['multiple_question_ex'] == 0) ? false : true;

				$re = "SELECT * FROM reponse_ex WHERE id_question_ex=".$questex['id_question_ex']." ORDER BY numero_reponse_ex ASC";
				$rqex = $bdd -> query($re);
				$ii=0;
				while($repqex = $rqex -> fetch()){
					$repqex_el[$ii]['id'] = $repqex['id_reponse_ex'];
					$repqex_el[$ii]['reponseu'] = $repqex['reponseu_reponse_ex'];
					$repqex_el[$ii]['numero'] = $repqex['numero_reponse_ex'];
					$repqex_el[$ii]['type'] = ($repqex['type_reponse_ex'] == 0) ? false : true;
					$ii++;
				}
				$questex_el[$i]['reponsex']= $repqex_el;
				$i++;
			}
			$affich_el['questions']=$questex_el;
			//echo $re;

			//$affich_e = array_shift($affich_el);
			//var_dump($affich_e);
			//var_dump($affich_el);
			//echo json_encode($questq_el);
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


switch ($_GET["nom_fct"]){

		case 0 : //cas de lajout

		ajouter();
		break;

		case 1 : //cas de la maj
		maj();
		break;

		case 2 : //cas de laffichage
		afficher();
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

		case 6 : //cas du listage des elmt (lecon, matiere, etc...)
		listAAS();
		break;

		case 7 : //cas de l'inscription
		 referenceTrsx();
		break;

		case 8 : //cas de la verification du code sms
		 listPT();
		break;

		case 9 : //cas de la maj de l'utilisateur
		 majuser();
		break;

		case 10 : //cas de l'affichage d'un elmt
		 aff_un();
		break;

		case 11 : //cas de l'ajout dune cotisation
		 listAASfiltre();
		break;

		case 12 : //cas de l'ajout dune cotisation
		 checkStat();
		break;

		case 13 : //cas de l'ajout dune cotisation
		 lpays();
		break;

		case 14 : //cas de l'ajout dune cotisation
		 inscr();
		break;

		case 15 : //cas de l'ajout dune cotisation
		 connect();
		break;

		case 16 : //cas de l'ajout dune cotisation
		 deco();
		break;

		case 17 : //cas de l'ajout dune cotisation
		 histo();
		break;


	}
}
?>
