<?php 
header("Access-Control-Allow-Origin: *");
        include 'connexion.php';
        $ch = $_GET['dest2'];
        $dtjr = $_GET['dt'];
        $tabres = array();
        $reqjoin = 'SELECT nom_transaction,telephone_transaction,date_transaction,montant_transaction,statut_transaction,nom_pays 
        FROM pays,transaction 
        WHERE pays.id_pays = transaction.id_pays
        AND pays.id_pays = :pays 
        AND transaction.date_transaction BETWEEN :dt AND CURDATE() 
        ORDER BY date_transaction desc';
         $u = 0;
         $resjoin = $bdd->prepare($reqjoin);
         $resjoin->execute(array(
           'pays' => $ch,
           'dt' => $dtjr
         ));
while ($usr = $resjoin->fetch()) {

    $tabres[$u][0] = $usr['nom_pays'];
    $tabres[$u][1] = $usr['nom_transaction'];
    $tabres[$u][2] = $usr['telephone_transaction'];
    $tabres[$u][3] = $usr['date_transaction'];
    $tabres[$u][4] = $usr['montant_transaction'];
    $tabres[$u][5] = $usr['statut_transaction'];;
$u++;
}

 echo json_encode($tabres);
