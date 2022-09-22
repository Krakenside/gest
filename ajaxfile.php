<?php

session_start();

set_time_limit(0);
ini_set('memory_limit', '512M');

ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);


require 'connexionA.php';

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

$chaine = genererChaineAleatoire();





// var_dump($_FILES['cover_album']);

// echo '<br>';

// var_dump($_FILES['cover_album']);

// var_dump($_POST);
// var_dump($_FILES);

// var_dump($ref_alb);

// var_dump($titre_alb);







// Count total files

$countfiles = count($_FILES['cover_album']);
$countfiles = 1;

// var_dump(
// 	$countfiles
// );

// Upload directory

$upload_location = "uploads/";
$upload_location2 = "../../site/assets/images/cover/large/";
$upload_location3 = "../site/file/";




// To store uploaded files path

$files_arr = array();





$ref_alb = 'AFPa' . $chaine;

$titre_alb = $_POST["titre_album"];
// var_dump($_POST["titre_album2"]

// );

$date_srt = $_POST["dateSortie_album"];

$id_genre = $_POST["id_genre"];

$is_active = $_POST["is_active"];

$prix_album = $_POST["prix_album"];

$nbr_sons_alb = $_POST["Nbr_sons_alb"];

$url = "";

$lien_alb = "";

$id_artiste = $_POST['id_artiste'];

$date_verif = date("Y-m-d H:i:s");

$date_ver = $date_srt;

$cover_alb = ' https://afreekaplay.com/site/assets/images/cover/large/' . $ref_alb . '.jpg';


$prix_u = 0;

$prix_u = $prix_album / $nbr_sons_alb;

if ($prix_u < 200) {
	$prix_u = 200;
} else if ($prix_u < 300) {
	$prix_u = 300;
} else {
	$prix_u = 500;
}
var_dump($ref_alb);

echo '<br>';
var_dump($_POST["titre_album2"]);

echo '<br>';
var_dump($titre_alb);

echo '<br>';

var_dump($cover_alb);

echo '<br>';

var_dump($date_srt);

echo '<br>';



var_dump($id_genre);

echo '<br>';



var_dump($is_active);

echo '<br>';



var_dump($prix_album);

echo '<br>';



var_dump($id_artiste);

echo '<br>';



var_dump($date_verif);

echo '<br>';



var_dump($date_ver);

// echo '<br>';



// Loop all files

// for ($index = 0; $index < $countfiles; $index++) {



if (isset($_FILES['cover_album']['name']) && $_FILES['cover_album']['name'] != '') {



	// File name

	$filename = $_POST["titre_album2"];

	$cover_filename = $_FILES['cover_album'];

	$fichier_album = basename(str_replace(" ", "_", $filename));

	$url = "https://afreekaplay.com/album/" . $ref_alb . ".zip";

	$lien_alb = basename(str_replace(" ", "_", $filename));

	var_dump($filename);

	// var_dump($fichier_album);

	// var_dump($url);

	// var_dump($lien_alb);

	$cover_filename = basename($_FILES['cover_album']["name"]);
	// var_dump($cover_filename);



	// Rename file

	// $newfilename = $file_basename. $file_ext;

	// var_dump($cover_filename);

	echo '<br>',

	// 

	//VERIFIER si l'abum existe deja 

	// $file_log = 'upload_sons_log.txt';

	// $reqAlb = "select from album where album.titre_album = :titre and album.id_artiste=:id_art";

	// $st = $bdd->prepare($reqAlb);

	// $st->execute(

	// 	array(

	// 		'titre' => $titre_alb,

	// 		'id_art' => $id_artiste



	// 	)

	// );

	// $res = $st->fetch();

	// var_dump($res);

	// if ($res != NULL) {

	// 	$content  = date("Y-m-d H:i:s") . 'album' . $titre_alb . ' existe deja  ' . PHP_EOL;



	// 	file_put_contents($file_log, $content, FILE_APPEND);

	// 	header('location:modifalb.php?ex=1');

	// } else {

	// Get extension

	$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	$ext2 = strtolower(pathinfo($cover_filename, PATHINFO_EXTENSION));



	$ln = str_replace("." . $ext, "", $lien_alb);
	$ln2 = str_replace("." . $ext2, "", $filename);

	// Valid file extension

	$valid_ext = array("zip", "rar", "jpg");



	// Check extension

	if (in_array($ext2, $valid_ext)) {



		// File path

		// $path = $upload_location . $filename;

		$path2 = $upload_location . $ref_alb . ".jpg";
		$path3 = $upload_location2 . $ref_alb . ".jpg";
		// $path3 = $upload_location3 . $ref_alb . ".jpg";



		// Upload file

		if (move_uploaded_file($_FILES['cover_album']["tmp_name"], $path2)) {
			// echo 'ok';



			move_uploaded_file(

				$_FILES['cover_album']["tmp_name"],

				$path3
			);
			
			// move_uploaded_file(

			// 	$_FILES['cover_album']["tmp_name"],

			// 	$path3
			// );


			// $target_path = '../../file/' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"]));

			// $target_path_2 = '../site/file/' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"]));

			// copy($target_path, $target_path_2);

			// move_uploaded_file(

			// 	$_FILES["fichier_son" . $i . ""]["tmp_name"],

			// 	'../site/file' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"]))

			// );

			// $files_arr[] = $path;

			$files_arr[] = $path2;
		}
	}



	// insertion bd

	$reqins = "INSERT INTO album(reference_album,titre_album,fichier_album,cover_album,prix_album,visible_album,

						dateSortie_album,url_album,lien_album,id_artiste,id_genre,is_active,date_verif,dte_enr)

					VALUES(:reference_album,:titre_album,:fichier_album,:cover_album,:prix_album,:visible_album,

							:dateSortie_album,:url_album,:lien_album,:id_artiste,:id_genre,:is_active,:date_verif,:dte_enr)";

	$insst = $bdd->prepare($reqins);

	$insst->execute(

		array(

			'reference_album' => $ref_alb, 'titre_album' => $titre_alb, 'fichier_album' => $filename, 'cover_album' => $cover_alb, 'prix_album' => $prix_album, 'visible_album' => $is_active,

			'dateSortie_album' => $date_srt, 'url_album' => $url, 'lien_album' => $ln2, 'id_artiste' => $id_artiste, 'id_genre' => $id_genre, 'is_active' => $is_active, 'date_verif' => $date_ver, 'dte_enr' => $date_srt



		)

	);
}


// }

// }

// echo json_encode($fichier_album);

// echo json_encode($url);

// echo json_encode($lien_alb);

// echo json_encode($ln);





// echo json_encode($files_arr);

header('location:ajalb.php?nbr_sons=' . $nbr_sons_alb . '&nm=' . $titre_alb . '&id_art=' . $id_artiste . '&pr=' . $prix_u);

// echo json_encode($titre_alb);

die;
