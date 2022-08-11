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
    $source = isset($_GET["src"]) ? $_GET["src"] : '';
    if (isset($_GET["dest"])) {
        $dest = $_GET["dest"];
    } else {

        $dest = "0";
    }
    if (isset($_GET["s"])) {
        $s = $_GET["s"];
    } else {

        $s = "";
    }

    if (isset($_GET["dte"])) {
        $dterange = $_GET["dte"];
    } else {

        $dterange = "";
    }

    if (isset($_GET["stat"])) {
        $stat = $_GET["stat"];
    } else {

        $stat = "";
    }

    if (isset($_GET["tp"])) {
        $tp = $_GET["tp"];
    } else {

        $tp = "";
    }
    // on initialise la variable du choix 
    $chM = NULL;
    //On verifie si le choix de maison a eté fait

    if (isset($_GET["chM"]) && ($_GET["chM"] != "")) {
        $chM = $_GET["chM"];
    }

    //seletionner la maison de production
    $reqM = "SELECT id_maison,nom_maison FROM maison ";

    if(isset($_GET["indx"])){

    }



?>
    <!DOCTYPE html>
    <html lang="fr">

    <script>
        var table = '<?php echo $dest; ?>';
    </script>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="images/favicon.ico">

        <title>afreekaplay Admin - <?php echo $commentC; ?> </title>

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

        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml51.css">
        <!-- daterange picker -->

        <link rel="stylesheet" href="assets/vendor_components/bootstrap-daterangepicker/daterangepicker.css">

        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="assets/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

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
            .formContainer {
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
        Tableau de Bord
        <small>Statistiques de ventes par Maison de production</small>
      </h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Acceuil</a></li>
        <li class="breadcrumb-item active">Statistiques de ventes</li>
      </ol>
    </section>
                <section class="content-header">
                    <h1>
                        <?php //echo $commentC;
                        ?>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="acceuil.php"><i class="fa fa-dashboard"></i> Acceuil</a></li>

                        <li class="breadcrumb-item active"><?php echo $source; ?></li>
                    </ol>
                </section>
                
                <section class="content">


                    <table class="table table-bordered" id="mydata">
                        <thead>
                            <tr>
                               
                               <!-- <th>Artiste</th> -->
                                <th>Reference</th>
                                <th>Titre  </th>
                                <th>Prix</th>
                                <th>Nombre de Ventes</th>
                                <th>Somme Générée</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>




                </section>
            </div>


            <?php
            include("footer.php");
            ?>
            <!-- <div class="control-sidebar-bg"></div> -->

    </body>

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
                            "url":"stmsapi.php?table=transaction&a=1&idMsn=<?php echo $_GET["chM"]?>&p=3&indx=<?php echo $_GET["indx"] ?>",     
                            //"url": "stmsapi.php?table=transaction&a=1&idMsn=<?php //echo $_GET["chM"]?>&p=3&indx=<?php //echo $_GET["indx"] ?>",
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

<?php
} else {

    header("location: index.php?t=1");
}
?>

    </html>