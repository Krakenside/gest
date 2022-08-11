<?php session_start();
if (isset($_SESSION['connect'])) //On vérifie que le variable existe.

{
  $connect = $_SESSION['connect']; //On récupère la valeur de la variable de session.

} else {
  $connect = 0; //Si $_SESSION['connect'] n'existe pas, on donne la valeur "0".

}

if ($connect == "1" and $_SESSION["userCompte"] == 'ADMINAFP20') // Si le visiteur s'est identifié.

{
  require 'connexion.php';

  // initalisation des variables
  $res1 = 0;
  $res2 = 0;
  $res3 = 0;
  $res_c = 0;
  $nb_tranOk1 = 0;
  $dtj = date('Y-m');
  $devise = '';

  //determiner si un pays a été selectionné dans la liste
  if (isset($_GET['dest2'])) {

    $ch = $_GET['dest2'];

    $nompays = NULL;
    // selectionner le pays dans la base de données en fonction de l'id selctionné
    $reqsel = 'SELECT nom_pays from pays where id_pays = :id';
    $statement = $bdd->prepare($reqsel);
    $statement->bindParam('id', $ch);
    $statement->execute();
    $rest = $statement->fetch();
    foreach ($rest as $row) {
      $nompays = $row;
    }

    // $req22  = 'select nom_pays from pays where id_pays =:idpays';
    //   $reqnm = $bdd->prepare($req22);
    //   $reqnm->execute(array(':idpays' => $ch));
    //   $ress = $reqnm->fetchAll();
    //   foreach ($reqnm as $row) {
    //     $nm = $row['nom_pays'];
    //   }
    // initialisation de la variable  date
    if (isset($_GET['dt'])) {
      $dtj = $_GET['dt'];
    } else {
      $dtj = date("y-m");
    }

    $dtjr = $dtj;
    $dtjr .= "%";

    $req8 = 'SELECT * FROM transaction WHERE statut_transaction = "SUCCESS" 
            AND date_transaction  BETWEEN :dt AND CURDATE() 
            order by date_transaction DESC';

    $trOk1 = $bdd->prepare($req8);
    $trOk1->execute(array(
      ':dt' => $dtjr
    ));
    $nb_tranOk1 = $trOk1->rowCount();

    //compter les gains du jour
    $req9 = 'SELECT SUM(montant_transaction) FROM transaction,pays WHERE statut_transaction = "SUCCESS" 
    AND pays.id_pays = transaction.id_pays
            AND pays.id_pays = :pays 
              AND date_transaction BETWEEN :dt AND CURDATE() ';
    $cnt_trans = $bdd->prepare($req9);
    $cnt_trans->execute(array(
      'dt' => $dtjr,
      'pays' => $ch
    ));

    $res_count = $cnt_trans->fetch();
    $res_c = $res_count['SUM(montant_transaction)'];

    // selectionner les transactions en fonction du pays
    $reqjoin = 'SELECT nom_transaction,telephone_transaction,date_transaction,montant_transaction,statut_transaction,nom_pays 
                FROM pays,transaction 
              WHERE pays.id_pays = transaction.id_pays
              AND pays.id_pays = :pays 
              AND transaction.date_transaction BETWEEN :dt AND CURDATE() 
              ORDER BY date_transaction desc';

    // selectionner la devise du pays correspondant
    $reqDev = ' SELECT signe_devise 
                FROM pays,devise 
                WHERE pays.id_devise = devise.id_devise
                AND pays.id_pays = :pays ';
    $devSta = $bdd->prepare($reqDev);
    $devSta->execute(array(
      'pays' => $ch
    ));
    $resDev = $devSta->fetch();
    $devise = $resDev['signe_devise'];
    $devSta->closeCursor();

    //selectionner les transactions reussies
    $req_stat = 'SELECT * FROM pays,transaction 
            WHERE pays.id_pays = transaction.id_pays
            AND pays.id_pays = :pays 
            AND statut_transaction = :statut
            AND transaction.date_transaction BETWEEN :dt AND CURDATE() 
            ORDER BY date_transaction desc';
    $res_su = $bdd->prepare($req_stat);
    $res_su->execute(array(
      'pays' => $ch,
      'dt' => $dtjr,
      'statut' => "SUCCESS"
    ));
    $res1 = $res_su->rowCount();
    $res_su->closeCursor();

    $res_Att = $bdd->prepare($req_stat);
    $res_Att->execute(array(
      'pays' => $ch,
      'dt' => $dtjr,
      'statut' => "ATTENTE"
    ));
    $res2 = $res_Att->rowCount();
    $res_Att->closeCursor();

    $res_fl = $bdd->prepare($req_stat);
    $res_fl->execute(array(
      'pays' => $ch,
      'dt' => $dtjr,
      'statut' => "FAIL"
    ));
    $res3 = $res_fl->rowCount();
    $res_fl->closeCursor();
  }
}
$querypays = 'SELECT nom_pays,code_pays,id_pays FROM pays';
                  $respays = $bdd->prepare($querypays);
                  $respays->execute();
                  $resstatement = $respays->fetchAll();
$respays2 = $bdd->prepare($querypays);
$respays2->execute();
$resstatement2 = $respays2->fetchAll();
