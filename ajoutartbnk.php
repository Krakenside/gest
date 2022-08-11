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

  // On affiche la page cachée.
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

    <title>afreekaplay Admin - Ajouter un paiement </title>

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap/dist/css/bootstrap.min.css">

    <!-- Bootstrap 4.0-->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap/dist/css/bootstrap-extend.css">

    <!-- font awesome -->
    <link rel="stylesheet" href="assets/vendor_components/font-awesome/css/font-awesome.css">

    <!-- ionicons -->
    <link rel="stylesheet" href="assets/vendor_components/Ionicons/css/ionicons.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="css/master_style.css">

    <!-- Popup CSS -->
    <link href="assets/vendor_components/Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet">

    <!-- Bootstrap Markdown -->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap-markdown-master/css/bootstrap-markdown.css">

    <!-- Glyphicons -->
    <link rel="stylesheet" href="assets/vendor_components/glyphicons/glyphicon.css">
    <!-- xeditable css -->
    <link href="assets/vendor_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="assets/vendor_plugins/iCheck/all.css">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="assets/vendor_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">

    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="assets/vendor_plugins/timepicker/bootstrap-timepicker.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="assets/vendor_components/select2/dist/css/select2.min.css">

    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml51.css">

    <!-- Quill JS - text editor -->
    <!-- Include stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- apro_admin Skins. Choose a skin from the css/skins
	   folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="css/skins/_all-skins.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <style>
      .form-group {
        margin-top: 5%;
        /* margin-bottom: 50%; */
        margin-left: 30%;

      }
    </style>

  </head>

  <body class="hold-transition sidebar-mini skin-purple-light">
    <div class="wrapper">

      <?php include("header.php");
      include("menu.php");
      ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Ajouter <?php echo 'Paiement' ?>
          </h1>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="acceuil.php"><i class="fa fa-dashboard"></i> Acceuil</a></li>

            <li class="breadcrumb-item active">Ajout</li>
          </ol>
        </section>

        <!-- Main content -->
        <?php
        if (isset($_GET['natart'])) {

          $fg  = $_GET['natart'];
        }
        if (!isset($_GET['chart'])) {


        ?> <section class="content">
            <form action="ajoutartbnk.php" method="GET" class="" id="form_ajou">
              <input type="hidden" name="fct" value="1">
              <div class="form-group">

                <label for="natart">Nationalité Artiste</label>
                <select name="natart" id="">
                  <?php
                  $reqnat = "SELECT * FROM pays ";
                  $resnat = $bdd->query($reqnat);
                  foreach ($resnat->fetchAll() as $nat) {
                  ?>

                    <option value="<?php echo $nat['code_pays'] ?>" <?php if (isset($_GET['natart'])) {

                                                                      // $fg  = $_GET['natart'];

                                                                      if ($nat['code_pays'] == $fg) {
                                                                        echo 'selected';
                                                                      }
                                                                    } ?>><?php echo $nat['nom_pays'] ?></option>
                  <?php
                  }


                  ?>

                </select>
                <div class="col-md-12 col-lg-2 row" style="margin-top:3%; margin-left: 15%">
                  <input type="submit" class="btn btn-block btn-primary" id="envoi" value="Valider">
                </div>
              </div>

            </form>
            <?php if (isset($_GET['natart'])) { ?>

              <table class="table table-bordered" id="mydata">
                <thead>
                  <tr>
                    <th>Artiste</th>
                    <th>Montant disponible en banque </th>
                    <th>Montant déja réglé</th>
                    <th>Action </th>

                </thead>
                <tbody>


                </tbody>
              </table>

            <?php        }
          } else {
            if (isset($_SESSION['infos'])) {

              //   var_dump($_SESSION['infos']);
              //  $nmart = $_SESSION['infos'][0][0];
              //  $mtnarge = $_SESSION['infos'][1];

              //  var_dump($nmart[0]);
              $bv = $_GET['chart'];
              $reqnm = 'SELECT * FROM artiste where artiste.id_artiste = "' . $bv . '" ';
              $rest = $bdd->query($reqnm);
              $res1 = $rest->fetch();
              // var_dump($res1);
              $reqbnq = 'SELECT * FROM banque INNER JOIN artiste ON artiste.nom_artiste = banque.Nom_artiste_banque where banque.reference_banque ="' . $bv . '"';
              $rsbnqst = $bdd->query($reqbnq);
              $res2 = $rsbnqst->fetch();
              // var_dump($res2);
              $nmart = $res2["Nom_artiste_banque"];
              $mtndispo =  $res2["Montant_disponible_banque"];
              $mtdej = $res2["Montant_deja_reglé_banque"];
              $idart = $res2['reference_banque'];

              $rsbnqst->closeCursor();
            ?>
              <div class="form-group" style="margin-top: 15%">
                <form action="trtbnk.php">

                  <table>
                    <!-- <input type="hidden" name="fct" value="2"> -->
                    <input type="hidden" name="limite" value="<?php echo $mtndispo ?>">
                    <input type="hidden" name="fct" value="3">
                    <input type="hidden" name="error" value="false">
                    <!-- <input type="text" name="py" id="">  -->
                    <input type="hidden" name="nmart" value="<?php echo $nmart ?>">
                    <tr>
                      <td><label for="">Nom artiste</label></td>
                      <td><input type="text" name="Nmart" readonly value="<?php echo $nmart   ?>" style="text-align:center ;"></td>
                    </tr>
                    <tr>
                      <td> <label for="">Montant Disponible en banque </label> </td>
                      <td><input type="text" name="disp" id="" readonly value="<?php echo $mtndispo; ?>" style="text-align:center"></td>

                    </tr>
                    <tr>
                      <td>Montant Déja reglé</td>
                      <td><input type="text" name="dj" id="" value="<?php (is_null($mtdej)) ? ($mtdej = 0) : ($mtdej);
                                                                    echo $mtdej; ?>" style="text-align: center;" readonly></td>
                    </tr>
                    <tr>
                      <td>Montant a payer</td>
                      <td><input type="text" name="apy" id=""></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input type="submit" value="Valider" style="margin-left: 40% ;"></td>
                    </tr>
                  </table>






                </form>
              </div>


          <?php }
          }     ?>
          </section>
          <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <?php
      include("footer.php");
      ?>



      <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="assets/vendor_components/jquery/dist/jquery.min.js"></script>

    <!-- popper -->
    <script src="assets/vendor_components/popper/dist/popper.min.js"></script>

    <!-- Bootstrap 4.0-->
    <script src="assets/vendor_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- SlimScroll -->
    <script src="assets/vendor_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

    <!-- FastClick -->
    <script src="assets/vendor_components/fastclick/lib/fastclick.js"></script>

    <!-- Bootstrap markdown -->
    <script src="assets/vendor_components/bootstrap-markdown-master/js/bootstrap-markdown.js"></script>

    <!-- marked-->
    <script src="assets/vendor_components/marked/marked.js"></script>

    <!-- to markdown -->
    <script src="assets/vendor_components/to-markdown/to-markdown.js"></script>

    <!-- Magnific popup JavaScript -->
    <script src="assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>
    <script src="assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js"></script>

    <!-- Select2 -->
    <script src="assets/vendor_components/select2/dist/js/select2.full.js"></script>

    <!-- InputMask -->
    <script src="assets/vendor_plugins/input-mask/jquery.inputmask.js"></script>
    <script src="assets/vendor_plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="assets/vendor_plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- date-range-picker -->
    <script src="assets/vendor_components/moment/min/moment.min.js"></script>
    <script src="assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- bootstrap datepicker -->
    <script src="assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

    <!-- bootstrap color picker -->
    <script src="assets/vendor_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>

    <!-- bootstrap time picker -->
    <script src="assets/vendor_plugins/timepicker/bootstrap-timepicker.min.js"></script>

    <!-- iCheck 1.0.1 -->
    <script src="assets/vendor_plugins/iCheck/icheck.min.js"></script>

    <!-- apro_admin App -->
    <script src="js/template.js"></script>

    <!-- apro_admin for demo purposes -->
    <script src="js/demo.js"></script>

    <!-- apro_admin for advanced form element -->
    <script src="js/pages/advanced-form-element.js"></script>

    <!-- CK Editor
	<script src="assets/vendor_components/ckeditor/ckeditor2.js"></script>-->
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

    <!-- Bootstrap WYSIHTML5 -->
    <script src="assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml51.all.js"></script>
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


    <!-- apro_admin for editor
	<!-- <script src="js/pages/editor3.js"></script> -->

    <script>
      function init_DataTables() {

        console.log('run_datatables');

        if (typeof($.fn.DataTable) === 'undefined') {
          return;
        }
        console.log('init_DataTables');





        var handleDataTableliste = function() {
          if ($("#mydata").length) {
            $("#mydata").DataTable({
              dom: "lBfrtip",
              ajax: {
                "url": "trtbnk.php?fct=<?php echo $_GET['fct'] ?>&natart=<?php echo $_GET['natart'] ?>",
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

  </html>