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


  //lister les nationnalité 

//   $reqpays = 'select nom_pays,code_pays from pays ';
//   $reqpaysst = $bdd->query($reqpays);
//   $res1 = $reqpaysst->fetchAll();
//   print_r($res1);

if(isset($_GET['dt2'])){
    $dt2 = $_GET['dt2'];
}
if(isset($_GET['dt'])){
    $dt1 = $_GET['dt'];
    if($dt1>$dt2){
        ?> <script> alert('La seconde date doit etre anterieure a la premiere !') </script>
        <?php
    }
}

//var_dump($_GET['dt2']);
//requete pour selectionner les artistes pour une nationnalité donné 
if(isset($_GET['dest2'])){
    // print_r($_GET['dest2']);

    $nompays = NULL;
    $nat = $_GET['dest2'];
    $ch = $_GET['dest2'];
    $reqNart = 'select * from artiste where nationalite_artiste =:nat';
    $reqNartSt = $bdd->prepare($reqNart);
    $reqNartSt->bindParam('nat',$nat);
    $reqNartSt->execute();
    $resNat = $reqNartSt->fetchAll();
    //var_dump($resNat);
// print_r($ch);
    $reqsel = 'SELECT nom_pays 
               from pays_mm 
               where code_pays = :cd';
    $statement = $bdd->prepare($reqsel);
    $statement->bindParam('cd', $ch);
    $statement->execute();
    $rest = $statement->fetch();
    foreach ($rest as $row) {
      $nompays = $row;
    }
}

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
  </head>

<body class="hold-transition sidebar-mini skin-purple-light">
    <div class="wrapper">

      <?php
      include("menu.php");
      include("header.php");

      ?>
       <div class="content-wrapper">

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

        <section class="content">
          <!-- <div class="row">
          </div> -->

          <center>
            <div>

              <!-- <form action="statpays.php" method="get"> -->
              <form action="startpays.php" method="get">

                <select name="dest2" id="" required>
                  <option value=""> --------------Effectuer votre choix ----------</option>

                  <?php
                  $querypays = 'SELECT nom_pays,code_pays,id_pays FROM pays_mm';
                  $respays = $bdd->prepare($querypays);
                  $respays->execute();
                  $resstatement = $respays->fetchAll();
                  unset($resstatement[9]);
                  

                  foreach ($resstatement as $ok) {
                  ?>
                    
                      <option value="<?php echo $ok['code_pays']; ?>" <?php if (isset($nompays)) {
                                                                      if ($ok['nom_pays'] == $nompays) {
                                                                        echo 'selected';
                                                                      }
                                                                    } ?>>
                        <?php echo $ok['nom_pays']; ?></option>




                    <?php
                  }

                 ?>


                </select>

                
                  <?php
                 // on verifie que la varible de date est set et on pré-rempli le champ si possible
                //  if (isset($_GET['dest2'])) { ?>
                    <!-- <input type="date" name="dt" id="" value="<?php // echo $_GET['dt']; ?>"> -->
                   <!--  <input type="date" name="dt2" id="" value="<?php // echo $_GET['dt2']; ?>"> -->
                   <?php
                //   }
                //   ?>
                
                <input type="submit" value="Valider">


              </form>
            </div>
          </center>
        </section>

<section class="content">


            <table class="table table-bordered" id="mydata">
              <thead>
                <tr>
                  <th>Artiste</th>
                  <th>Nombre de ventes de singles </th>
                  <th>Nombre de ventes d'albums</th>
                  <th>Montant Ventes singles CFA </th>
                  <th>Montant Ventes singles GNF </th>
                  <th>Montant Ventes Albums GNF </th>
                  <th>Montant Ventes Albums CFA</th>
                 <th> Montant des soutiens CFA </th>
                 <th> Montant des soutiens GNF </th>
              </thead>
              <tbody>


              </tbody>
            </table>




          </section>
    </div>
    </div>
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
              "url": "startpaysapi.php?dest2=<?php echo $_GET["dest2"];?>",
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
  <?php
} else {

  header("location: index.php?t=1");
}
?>