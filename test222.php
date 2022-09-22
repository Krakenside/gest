<?php

session_start();

set_time_limit(0);
ini_set('memory_limit', '512M');

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





var_dump($_FILES['cover_album']);

// echo '<br>';

// var_dump($_FILES['files']);

// var_dump($_POST);

// var_dump($ref_alb);

// var_dump($titre_alb);









// Count total files

// $countfiles = count($_FILES['files']['name']);
$countfiles = 2;



// Upload directory

$upload_location = "uploads/";



// To store uploaded files path

$files_arr = array();





$ref_alb = 'AFPa' . $chaine;

$titre_alb = $_POST["titre_album"];

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
// var_dump($ref_alb);

// echo '<br>';

// var_dump($titre_alb);

// echo '<br>';

// var_dump($_POST["titre_album2"]);

// echo '<br>';

// var_dump($cover_alb);

// echo '<br>';

// var_dump($date_srt);

// echo '<br>';



// var_dump($id_genre);

// echo '<br>';



// var_dump($is_active);

// echo '<br>';



// var_dump($prix_album);

// echo '<br>';



// var_dump($id_artiste);

// echo '<br>';



// var_dump($date_verif);

// echo '<br>';



// var_dump($date_ver);

// echo '<br>';
echo 'cover';



// Loop all files
// var_dump($_FILES['cover_album']);

for ($index = 0; $index < $countfiles; $index++) {



	if (isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != '') {


		var_dump($_FILES['cover_album']);

		// File name

		// $filename = $_FILES['files']['name'][$index];
		$fileName = $_POST["titre_album2"];

		$cover_filename = $_FILES['cover_album']["name"];
		

		$fichier_album = basename(str_replace(" ", "_", $filename));

		$url = "https://afreekaplay.com/album/" . $ref_alb . ".zip";

		// $lien_alb = basename(str_replace(" ", "_", $filename));
		$lien_alb = $_POST["titre_album2"];

		// var_dump($filename);

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

		// $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));



		// $ln = str_replace("." . $ext, "", $lien_alb);
		$ln = $lien_alb;

		// Valid file extension

		$valid_ext = array("jpeg", "rar","jpg","png");



		// Check extension

		if (in_array($ext, $valid_ext)) {



			// File path

			$path = $upload_location . $filename;

			$path2 = $upload_location . $ref_alb . ".jpg";



			// Upload file

			// if (

			// 	move_uploaded_file($_FILES['files']['tmp_name'][$index], $path)

			// 	&& move_uploaded_file($_FILES['cover_album']["tmp_name"], $path2)

			// ) {

			// 	$files_arr[] = $path;

			// 	$files_arr[] = $path2;

			// }


			if (

				move_uploaded_file($_FILES['cover_album']["tmp_name"], $path2)

			) {

				$files_arr[] = $path;

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

				'dateSortie_album' => $date_srt, 'url_album' => $url, 'lien_album' => $ln, 'id_artiste' => $id_artiste, 'id_genre' => $id_genre, 'is_active' => $is_active, 'date_verif' => $date_ver, 'dte_enr' => $date_srt



			)

		);
	}
}

// }

// echo json_encode($fichier_album);

// echo json_encode($url);

// // echo json_encode($lien_alb);

// // echo json_encode($ln);





// echo json_encode($files_arr);
// var_dump($_FILES);
// // header('location:ajalb.php?nbr_sons='.$nbr_sons_alb.'&nm='.$titre_alb.'&id_art='.$id_artiste.'&pr='.$prix_u);

// echo json_encode($titre_alb);

// die;

// echo '<br>' ;
// var_dump($_FILES);

// (A) HELPER FUNCTION - SERVER RESPONSE
// if(isset($_POST)){
// 	var_dump($_POST);
// 	var_dump( $_FILES);
// }

// else{function verbose($ok = 1, $info = "",$flen = "")
// 	{
// 		if ($ok == 0) {
// 			http_response_code(400);
// 		}
// 		exit(json_encode(["ok" => $ok, "info" => $info,"flen"=>$flen]));
// 	}
	
// 	// (B) INVALID UPLOAD
// 	if (empty($_FILES) || $_FILES["file"]["error"]) {
// 		verbose(0, "Failed to move uploaded file.");
// 	}
	
// 	// (C) UPLOAD DESTINATION - CHANGE FOLDER IF REQUIRED!
// 	$filePath = __DIR__ . DIRECTORY_SEPARATOR . "uploads";
// 	if (!file_exists($filePath)) {
// 		if (!mkdir($filePath, 0777, true)) {
// 			verbose(0, "Failed to create $filePath");
// 		}
// 	}
// 	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
// 	$filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;
	
// 	// (D) DEAL WITH CHUNKS
// 	$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
// 	$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
// 	$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
// 	if ($out) {
// 		$in = @fopen($_FILES["file"]["tmp_name"], "rb");
// 		if ($in) {
// 			while ($buff = fread($in, 4096)) {
// 				fwrite($out, $buff);
// 			}
// 		} else {
// 			verbose(0, "Failed to open input stream");
// 		}
// 		@fclose($in);
// 		@fclose($out);
// 		@unlink($_FILES["file"]["tmp_name"]);
// 	} else {
// 		verbose(0, "Failed to open output stream");
// 	}
	
// 	// (E) CHECK IF FILE HAS BEEN UPLOADED
// 	if (!$chunks || $chunk == $chunks - 1) {
// 		rename("{$filePath}.part", $filePath);
// 	}
// 	// json_encode($filename);
// 	verbose(1, "Upload OK",$fileName);
// 	}