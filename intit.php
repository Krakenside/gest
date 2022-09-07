<?php






// $table = $_GET['table'];



require 'connexion.php';


// inserons les id et noms des artistes dans la table banque 

$reqArt  = $bdd->query("select * from artiste");

$res = $reqArt->fetchAll();
$reqIns = " INSERT INTO banque(Id_artiste_banque,Nom_artiste_banque) VALUES(:id_art,:nm) ";
$st1 = $bdd->prepare($reqIns);
// var_dump($res);
foreach ($res as $elmt) {

    // echo $elmt["id_artiste"] . " ";
    // echo $elmt["nom_artiste"] . " <br>";

    // 
    $st1->execute(
        array(
            'id_art' => $elmt["id_artiste"],
            'nm' => $elmt["nom_artiste"]
        )
    );
};
