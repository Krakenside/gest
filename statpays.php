<?php
session_start();
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

  // $pourc_tel = ($nb_son !== 0) ? round(($nb_telec * 100) / $nb_son, 2) : 0;
  // $pourc_tran = ($nb_tran !== 0) ? round(($nb_tranOk1 * 100) / $nb_tran, 2) : 0;

?>

  <!DOCTYPE html>
  <html lang="fr">


  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.ico">

    <title>afreekaplay Admin </title>




    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap/dist/css/bootstrap.css">

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap/dist/css/bootstrap-extend.css">

    <!-- font awesome -->
    <link rel="stylesheet" href="assets/vendor_components/font-awesome/css/font-awesome.css">

    <!-- ionicons -->
    <link rel="stylesheet" href="assets/vendor_components/Ionicons/css/ionicons.css">

    <!-- theme style -->
    <link rel="stylesheet" href="css/master_style.css">

    <!-- apro_admin skins. choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="css/skins/_all-skins.css">

    <!-- weather weather -->
    <link rel="stylesheet" href="assets/vendor_components/weather-icons/weather-icons.css">

    <!-- jvectormap -->
    <link rel="stylesheet" href="assets/vendor_components/jvectormap/jquery-jvectormap.css">

    <!-- date picker -->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">

    <!-- daterange picker -->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap-daterangepicker/daterangepicker.css">

    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css">

    <!-- Morris charts -->
    <link rel="stylesheet" href="assets/vendor_components/morris.js/morris.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <style>
      .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        font-weight: bold;

      }

      .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
      }

      .styled-table th,
      .styled-table td {
        padding: 12px 15px;
      }

      .styled-table tbody tr {
        border-bottom: thin solid #dddddd;
      }

      .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
      }

      .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
      }

      .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #009879;
      }

      .cont {
        display: flex;
        flex-direction: row;
        margin-top: 5%;
      }

      @media (max-width: 800px) {
        .cont {

          flex-direction: column;
        }
      }


      .divA {
        flex: 1;
      }

      .divB {
        margin-right: 5%;
      }
    </style>


  </head>

  <body class="hold-transition sidebar-mini skin-purple-light">
    <div class="wrapper">

      <?php
      include("menu.php");
      include("header.php");

      ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Tableau de Bord
            <small>Statistiques des operations par pays</small>
          </h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Acceuil</a></li>
            <li class="breadcrumb-item active">Tableau de bord</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xl-3 col-md-6 col-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3><?php echo $res1; ?></h3>

                  <p>Transactions Reussies</p>
                  <p> du <?php if (isset($_GET['dt'])) {
                            echo $_GET['dt'];
                          } else {
                            echo $dtj;
                          } ?> à aujourd'hui</p>
                </div>
                <div class="icon">
                  <i class="fa fa-user"></i>
                </div>
                <a href="statmaison.php" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>
            <!-- ./col -->

            <!-- ./col -->


            <div class="col-xl-3 col-md-6 col-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo $res3 ?></h3>

                  <p>Nombre de transaction echouées </p>
                </div>
                <div class="icon">
                  <i class="fa fa-times" aria-hidden="true"></i>
                </div>
                <a href="sites.php?dest=matiere" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>
            <!-- ./col -->

            <div class="col-xl-3 col-md-6 col-6">
              <!-- small box -->
              <div class="small-box bg-gray">
                <div class="inner">
                  <h3><?php echo $res2; ?></h3>

                  <p>Transactions en attente</p>
                </div>
                <div class="icon">
                  <i class="fa fa-clock-o" aria-hidden="true"></i>

                </div>
                <a href="sites.php?dest=examen" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>
            <!-- ./col -->


            <!-- ./col -->
            <div class="col-xl-3 col-md-6 col-6">

              <!-- small box -->
              <div class="small-box bg-blue">
                <div class="inner">
                  <h3><?php echo $res_c ?> <h4><?php
                                                echo $devise;

                                                ?></h4>
                  </h3>

                  <p>Gains sur la période </p>
                </div>
                <div class="icon">
                  <i class="fa fa-money" aria-hidden="true"></i>

                </div>
                <a href="sites.php?dest=matiere" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>
            <!-- ./col -->



          </div>

          <center>
            <div>

              <!-- <form action="statpays.php" method="get"> -->
              <form action="statpays.php" method="get">

                <select name="dest2" id="">
                  <option value=""> --------------Effectuer votre choix ----------</option>

                  <?php
                  $querypays = 'SELECT nom_pays,code_pays,id_pays FROM pays';
                  $respays = $bdd->prepare($querypays);
                  $respays->execute();
                  $resstatement = $respays->fetchAll();

                  foreach ($resstatement as $ok) {
                  ?>
                    <div>
                      <option value="<?php echo $ok['id_pays']; ?>" <?php if (isset($nompays)) {
                                                                      if ($ok['nom_pays'] == $nompays) {
                                                                        echo 'selected';
                                                                      }
                                                                    } ?>>
                        <?php echo $ok['nom_pays']; ?></option>




                    <?php
                  }

                  // on verifie que la varible de date est set et on pré-rempli le champ si possible
                  if (isset($_GET['dest2'])) { ?>
                      <input type="date" name="dt" id="" value="<?php echo $_GET['dt']; ?>">
                    <?php
                  }
                    ?>


                </select>
                <input type="submit" value="Valider">


              </form>
          </center>
          <div class="cont">
            <div class="divA">
              <?php
              if ((isset($_GET['dest2'])) && isset($_GET['dt'])) {

                //On transforme la chaine de date en objet date
                $time = strtotime($dtj);
                //   $final = date("Y-m-d", strtotime("+1 month", $time));
                $tab_mois = array();
                $tab_mois_final = array();
                $tab_ees = array();
                $tab_ees = array_reverse($tab_mois);
                // $essai = array();
                //recuperer les 6 derniers mois
                for ($i = 0; $i < 6; $i++) {
                  $tab_mois[$i] = date("Y-m", strtotime("-" . $i . "month", $time));
                  $tab_mois_final[$i] = date("m", strtotime($tab_mois[$i]));
                }
                // print_r($tab_mois);
                //  print_r($tab_mois_final);

                $tab_ees = array_reverse($tab_mois);
                $tab_ff = array_reverse($tab_mois_final);
                // $tab_mois= $tab_ees;
                // print_r($tab_ees);
                // $res_su = $bdd->prepare($cntsucc);
                // $res_su->execute(array('pays' => $ch, 'dt' => $dtjr));
                // $res1 = $res_su->rowCount();
                $tab_tr_ok = array();
                $req_graphe_pays = 'SELECT  DISTINCT * FROM pays,transaction 
              WHERE pays.id_pays = transaction.id_pays
              AND pays.id_pays = :pays 
              AND statut_transaction = :statut
              AND transaction.date_transaction like :dt
              ORDER BY date_transaction desc';

                $tab_tr_fail = array();
                $tab_tr_att = array();
                //On boucle sur les 6 mois precedents pour resortir les stats(réussies,echouées,en attente)
                for ($i = 0; $i < 6; $i++) {

                  $varTemp = $tab_ees[$i];
                  $varTemp .= "%";


                  $res_su_par_pays = $bdd->prepare($req_graphe_pays);
                  $res_su_par_pays->execute(array(
                    'pays' => $ch,
                    'dt' => $varTemp,
                    'statut' => "SUCCESS"
                  ));
                  $res_t = $res_su_par_pays->rowCount();
                  $tab_tr_ok[$i] = $res_t;
                  $res_su_par_pays->closeCursor();

                  $res_att_par_pays = $bdd->prepare($req_graphe_pays);
                  $res_att_par_pays->execute(array(
                    'pays' => $ch,
                    'dt' => $varTemp,
                    'statut' => "ATTENTE"
                  ));
                  $res_t_att = $res_att_par_pays->rowCount();
                  $tab_tr_att[$i] = $res_t_att;
                  $res_att_par_pays->closeCursor();

                  $res_fail_par_pays = $bdd->prepare($req_graphe_pays);
                  $res_fail_par_pays->execute(array(
                    'pays' => $ch,
                    'dt' => $varTemp,
                    'statut' => "FAIL"
                  ));
                  $res_t_f = $res_fail_par_pays->rowCount();
                  $tab_tr_fail[$i] = $res_t_f;
                  $res_fail_par_pays->closeCursor();
                }
                // print_r($tab_tr_ok);
                // print_r($tab_tr_att);
                // print_r($tab_tr_fail);

              ?>

                <caption> Cliquer sur la legende pour modifier l'affichage</caption>
                <canvas id="myChart" style="width:100%;max-width:600px;margin-top:30px"></canvas>

                <script>
                  //    alert(dt);
                  var month_values = <?php echo json_encode($tab_ff); ?>
                  // for(var i = 0; i < 6; i++)
                  // {

                  //     alert(month_values[i]);
                  // }
                  var res_values = <?php echo json_encode($tab_tr_ok); ?>;
                  var res_val_att = <?php echo json_encode($tab_tr_att); ?>;
                  var res_val_fail = <?php echo json_encode($tab_tr_fail); ?>;
                  // var mth_values_final = 
                  var xValues = month_values;
                  // for( var i = 0; i<6;i++){
                  //   // document.write(xValues[i]);
                  //   document.write(month_values[i])
                  // }

                  new Chart("myChart", {
                    type: "line",
                    data: {
                      labels: xValues,
                      datasets: [{
                        data: res_values,
                        borderColor: "blue",
                        fill: true,
                        label: 'Réussis'
                      }, {
                        data: res_val_att,
                        borderColor: "orange",
                        fill: false,
                        label: 'En attente'
                      }, {
                        data: res_val_fail,
                        borderColor: "red",
                        fill: false,
                        label: 'Echecs'
                      }]
                    },
                    options: {
                      legend: {
                        display: true

                      },
                      title: {
                        display: true,
                        text: "Transactions sur les 6  mois précedents "
                      }
                    }
                  });
                </script>
            </div>
            <?php


                $respays2 = $bdd->prepare($querypays);
                $respays2->execute();
                $resstatement2 = $respays2->fetchAll();

                $cnt = 'select count(id_transaction) from transaction,pays where transaction.id_pays = pays.id_pays 
                            AND pays.nom_pays = :sp
                AND transaction.statut_transaction = :st
                AND transaction.date_transaction BETWEEN :dt AND CURDATE() ';

                $cnt_sm = 'select SUM(montant_transaction) from transaction,pays where transaction.id_pays = pays.id_pays 
                            AND pays.nom_pays = :sp
                AND transaction.statut_transaction = :st
                AND transaction.date_transaction BETWEEN :dt AND CURDATE() ';
                $devise = 'SELECT signe_devise from pays,devise where pays.id_devise = devise.id_devise and nom_pays =  :pays';
                $devise_statement = $bdd->prepare($devise);
                $res_su2 = $bdd->prepare($cnt);
                $res_attente = $bdd->prepare($cnt);
                $res_fail = $bdd->prepare($cnt);
                $res_cnt_sm = $bdd->prepare($cnt_sm);

            ?>
            <div class="divB">
              <table class="styled-table">
                <thead>
                  <tr>
                    <th>Pays</th>
                    <th>Réussis</th>
                    <th>En Attente</th>
                    <th>Echoué</th>
                    <th>Gains</th>
                  </tr>

                </thead>
                <tr>
                  <?php
                  $tab_arr = array();
                  foreach ($resstatement as $ok) {
                    if ($ok['nom_pays'] == $nompays) {
                      continue;
                    } ?>
                    <td><?php echo $ok['nom_pays'];
                        $pay_Boucle = $ok['nom_pays'];
                        $tab_arr[] .= $pay_Boucle;
                        // die($dtj);
                        ?></td>
                    <td><?php $res_su2->execute(array(
                          'sp' => $pay_Boucle,
                          'st' => "SUCCESS",
                          'dt' => $dtjr
                        ));
                        foreach ($res_su2->fetchAll() as $ok2) {
                          echo $ok2['count(id_transaction)'];
                          $res_su2->closeCursor();
                        } ?></td>
                    <td> <?php $res_attente->execute(array(
                            'sp' => $pay_Boucle,
                            'st' => "ATTENTE",
                            'dt' => $dtjr
                          ));
                          foreach ($res_attente->fetchAll() as $ok3) {
                            echo $ok3['count(id_transaction)'];
                            $res_attente->closeCursor();
                          } ?></td>
                    <td> <?php $res_fail->execute(array(
                            'sp' => $pay_Boucle,
                            'st' => "FAIL",
                            'dt' => $dtjr
                          ));
                          foreach ($res_fail->fetchAll() as $ok4) {
                            echo $ok4['count(id_transaction)'];
                            $res_fail->closeCursor();
                          } ?></td>

                    <td>
                      <?php $res_cnt_sm->execute(array(
                        'sp' => $pay_Boucle,
                        'st' => "SUCCESS",
                        'dt' => $dtjr
                      ));
                      $devise_statement->execute(array('pays' => $pay_Boucle));
                      foreach ($res_cnt_sm->fetchAll() as $ok4) {
                        echo $ok4['SUM(montant_transaction)'];
                        $res_fail->closeCursor();
                      }
                      foreach ($devise_statement->fetchAll() as $bclvar) {
                        echo ' ' . $bclvar['signe_devise'];
                      }



                      ?>

                    </td>




                </tr>
              <?php
                  }

              ?>
              </table>
            </div>
          </div>

        <?php
              } else {

                $respays2 = $bdd->prepare($querypays);
                $respays2->execute();
                $resstatement2 = $respays2->fetchAll();

                $cnt = 'select count(id_transaction) from transaction,pays where transaction.id_pays = pays.id_pays 
                            AND pays.nom_pays = :sp
                AND transaction.statut_transaction = :st';
                $res_su2 = $bdd->prepare($cnt);
                $res_attente = $bdd->prepare($cnt);
                $res_fail = $bdd->prepare($cnt);

        ?>

          <table class="styled-table" style="margin: 0 auto">
            <thead>
              <tr>
                <th>Pays</th>
                <th>Réussis</th>
                <th>En Attente</th>
                <th>Echoué</th>
              </tr>

            </thead>
            <tr>
              <?php
                $tab_arr = array();
                foreach ($resstatement as $ok) { ?>
                <td><?php echo $ok['nom_pays'];
                    $pay_Boucle = $ok['nom_pays'];
                    $tab_arr[] .= $pay_Boucle;
                    // die($dtj);



                    ?></td>
                <td><?php $res_su2->execute(array(
                      'sp' => $pay_Boucle,
                      'st' => "SUCCESS"
                    ));
                    foreach ($res_su2->fetchAll() as $ok2) {
                      echo $ok2['count(id_transaction)'];
                      $res_su2->closeCursor();
                    } ?></td>
                <td> <?php $res_attente->execute(array(
                        'sp' => $pay_Boucle,
                        'st' => "ATTENTE"
                      ));
                      foreach ($res_attente->fetchAll() as $ok3) {
                        echo $ok3['count(id_transaction)'];
                        $res_attente->closeCursor();
                      } ?></td>
                <td> <?php $res_fail->execute(array(
                        'sp' => $pay_Boucle,
                        'st' => "FAIL"
                      ));
                      foreach ($res_fail->fetchAll() as $ok4) {
                        echo $ok4['count(id_transaction)'];
                        $res_fail->closeCursor();
                      } ?></td>
                


            </tr>
          <?php
                }

          ?>
          </table>


        <?php
              }

              // $future_timestamp = strtotime("-1 year");
              // $data = date('Y-m-d', $future_timestamp);
              // $month = date("m",strtotime($data));
              //  echo $data;
              //  echo $month;

        ?>

        <?php
        if (isset($_GET['dest2'])) {

          $u = 0;
          $resjoin = $bdd->prepare($reqjoin);
          $resjoin->execute(array(
            'pays' => $ch,
            'dt' => $dtjr
          ));

        ?>

          <section class="content">


            <table class="table table-bordered" id="mydata">
              <thead>
                <tr>
                  <th>Pays</th>
                  <th>Noms et Prenoms</th>
                  <th>Telephone</th>
                  <th>Date </th>
                  <th>Montant</th>

                  <th>Statut</th>
              </thead>
              <tbody>


              </tbody>
            </table>




          </section>

      </div>

      <!-- /.row -->
      <!-- </div> -->

    </div>


  <?php
        }

  ?>

  </div>

 


  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

  </div>
  <!-- ./wrapper -->
  <?php
  include("footer.php");
  ?>


  <!-- jQuery 3 -->
  <script src="assets/vendor_components/jquery/dist/jquery.min.js"></script>

  <!-- popper -->
  <script src="assets/vendor_components/popper/dist/popper.min.js"></script>

  <!-- Bootstrap 4.0-->
  <script src="assets/vendor_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- DataTables -->
  <script src="assets/vendor_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="assets/vendor_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

  <!-- SlimScroll -->
  <script src="assets/vendor_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

  <!-- FastClick -->
  <script src="assets/vendor_components/fastclick/lib/fastclick.js"></script>

  <!-- Magnific popup JavaScript -->
  <script src="assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
  <script src="assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>

  <!-- This is data table -->
  <script src="assets/vendor_plugins/DataTables-1.10.15/media/js/jquery.dataTables.min.js"></script>

  <!-- start - This is for export functionality only -->
  <script src="assets/vendor_plugins/DataTables-1.10.15/extensions/Buttons/js/dataTables.buttons.min.js"></script>
  <script src="assets/vendor_plugins/DataTables-1.10.15/extensions/Buttons/js/buttons.flash.min.js"></script>
  <script src="assets/vendor_plugins/DataTables-1.10.15/ex-js/jszip.min.js"></script>
  <script src="assets/vendor_plugins/DataTables-1.10.15/ex-js/pdfmake.min.js"></script>
  <script src="assets/vendor_plugins/DataTables-1.10.15/ex-js/vfs_fonts.js"></script>
  <script src="assets/vendor_plugins/DataTables-1.10.15/extensions/Buttons/js/buttons.html5.min.js"></script>
  <script src="assets/vendor_plugins/DataTables-1.10.15/extensions/Buttons/js/buttons.print.min.js"></script>
  <!-- end - This is for export functionality only -->

  <!-- date-range-picker -->
  <script src="assets/vendor_components/moment/moment1.js"></script>
  <script src="assets/vendor_components/bootstrap-daterangepicker/daterangepicker1.js"></script>

  <!-- bootstrap datepicker -->
  <script src="assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>


  <!-- apro_admin App -->
  <script src="js/template.js"></script>

  <!-- apro_admin for demo purposes -->
  <script src="js/demo.js"></script>

  <!-- apro_admin for advanced form element -->
  <script src="js/pages/advanced-form-element2.js"></script>

  <!-- apro_admin for advanced form element -->
  <script src="js/pages/advanced-form-element2.js"></script>


  <script>
    function init_DataTables() {

      console.log('run_datatables');

      if (typeof($.fn.DataTable) === 'undefined') {
        return;
      }
      console.log('init_DataTables');




      // $('#datatable-responsive').DataTable({
      //   "bJQueryUI": true,
      //   "sDom": '<""l><"F"f>tp',
      //   "ajax": {
      //     "url": "gestionbdd.php?table=" + table + "&nom_fct=" + 2,
      //     "dataSrc": "",
      //     "type": "GET"
      //   },
      //   'columnDefs': [{
      //     orderable: false,
      //     targets: [2, 3, 4],
      //     targets: '_all',
      //     defaultContent: ''
      //   }]
      // });

      // $('#datatable-part').DataTable({
      //   "bJQueryUI": true,
      //   "sDom": '<""l><"F"f>tp',
      //   "ajax": {
      //     "url": "gestionbdd.php?table=" + table + "&nom_fct=" + 2,
      //     "dataSrc": "",
      //     "type": "GET"
      //   },
      //   'columnDefs': [{
      //     orderable: false,
      //     targets: [2, 3, 4],
      //     targets: '_all',
      //     defaultContent: ''
      //   }],
      //   scrollX: true,
      //   responsive: false
      // });

      var handleDataTableliste = function() {
        if ($("#mydata").length) {
          $("#mydata").DataTable({
            dom: "lBfrtip",
            ajax: {
              "url": "tab.php?dest2=<?php echo $_GET["dest2"]; ?>&dt=<?php echo $_GET["dt"]; ?>",
              "dataSrc": "",
              "type": "GET"
            },
            buttons: [{
                extend: "copy",
                className: "btn-sm"
              },
              {
                extend: "csv",
                className: "btn-sm"
              },
              {
                extend: "excel",
                className: "btn-sm"
              },
              {
                extend: "pdfHtml5",
                className: "btn-sm"
              },
              {
                extend: "print",
                className: "btn-sm"
              },
            ],
            'columnDefs': [{
              orderable: true,
              targets: [1, 2],
              targets: '_all',
              defaultContent: ''
            }],
            /* scrollX: true,
              responsive: false
            });*/
            //scrollX: true,
            //responsive: false
          });
        }
      };

      TableManageButtons = function() {
        "use strict";
        return {
          init: function() {
            handleDataTableliste();
          }
        };
      }();


      var handleDataTablelist = function() {
        if ($("#datatable-liste2").length) {
          $("#datatable-liste2").DataTable({
            dom: "lBfrtip",
            ajax: {
              "url": "gestionbdd.php?table=" + table + "&nom_fct=17",
              "dataSrc": "",
              "type": "GET"
            },
            buttons: [{
                extend: "copy",
                className: "btn-sm"
              },
              {
                extend: "csv",
                className: "btn-sm"
              },
              {
                extend: "excel",
                className: "btn-sm"
              },
              {
                extend: "pdfHtml5",
                className: "btn-sm"
              },
              {
                extend: "print",
                className: "btn-sm"
              },
            ],
            columnDefs: [{
              targets: '_all',
              defaultContent: ''
            }],
            // scrollX: true,
            // responsive: false
          });
        }
      };

      TableManageButton = function() {
        "use strict";
        return {
          init: function() {
            handleDataTablelist();
          }
        };
      }();

      TableManageButton.init();
      TableManageButtons.init();

    };
  </script>
  <script>
    $(document).ready(function() {
      init_DataTables();
    });
  </script>


  </body>


  </html>
<?php
} else {

  header("location: index.php?t=1");
}
?>