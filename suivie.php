<?php
session_start();

        $connect=1;//Si $_SESSION['connect'] n'existe pas, on donne la valeur "0".


if ($connect == "1" AND (isset($_SESSION["userCompte"]) AND $_SESSION["userCompte"] == 'ADMINAFP20')) // Si le visiteur s'est identifié.
{
	require 'connexion1.php';


$req ="SELECT DISTINCT transactionId FROM transaction_table WHERE itemId='22010061'";

          $tr  = $bdd ->query($req);
          $nb_tran = $tr -> rowCount();

$req1 ="SELECT DISTINCT transactionId FROM transaction_table WHERE itemId='22010061' AND statut ='SUCCESS'";

          $trOk  = $bdd ->query($req1);
          $nb_tranOk = $trOk -> rowCount();

$pourc_tran = round(($nb_tranOk * 100)/$nb_tran, 2);
	/*
	$req7='SELECT * FROM resultat_q';

						$rqz  = $bdd ->query($req7);
						$nb_rqz = $rqz -> rowCount();
						$nb_rbqz=0;
		while($rtqz = $rqz->fetch()){
			$req9='SELECT * FROM question_q where id_quizz = '.$rtqz['id_quizz'];
				$rqqz  = $bdd ->query($req9);
				$nb_qqz = $rqqz -> rowCount();
			$rsuq = $rtqz['score_resultat_q'];
			if($rtqz['score_resultat_q'] > ($nb_qqz/2)){
				$nb_rbqz++;
			}
		}
		$pourc_qz = ($nb_rqz !== 0) ? round(($nb_rbqz*100)/$nb_rqz, 2) : 0;

	$req8='SELECT * FROM resultat_ex';

						$rex  = $bdd ->query($req8);
						$nb_rex = $rex -> rowCount();
						$nb_rbex=0;
		while($rtex = $rex->fetch()){
			$rsuex = $rtex['note_resultat_ex'];
			if($rsuex > 20){
				$nb_rbex++;
			}
		}
		$pourc_ex = ($nb_rex !== 0) ? round(($nb_rbex*100)/$nb_rex, 2) : 0;


	$pg = $bdd -> query('SELECT * FROM clientw
										INNER JOIN sitew
											ON clientw.id_client = sitew.id_client
										INNER JOIN pagew
											ON sitew.id_site = pagew.id_site
										WHERE clientw.id_client='.$_SESSION["userID"]);
		/ * while($donnees_music = $lect_music->fetch()){

			$list_music[$i] = $donnees_music;
			$i++;
		} * /
		$nb_pg = $pg -> rowCount();


	$im = $bdd -> query('SELECT * FROM clientw
										INNER JOIN sitew
											ON clientw.id_client = sitew.id_client
										INNER JOIN pagew
											ON sitew.id_site = pagew.id_site
										INNER JOIN imagew
											ON imagew.id_page = pagew.id_page
										WHERE clientw.id_client='.$_SESSION["userID"]
										);

		$nb_im = $im -> rowCount();
		*/



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

    <title>afreekaplay Admin - Tableau de Bord</title>

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


  </head>

<body class="hold-transition sidebar-mini skin-purple-light">
<div class="wrapper">

 <?php
 include("menu1.php");
 include("header1.php");

 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tableau de Bord
        <small>Panneau de Controle</small>
      </h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Acceuil</a></li>
        <li class="breadcrumb-item active">Tableau de bord</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- /.row -->
      <div class="row">

        <div class="col-xl-6 connectedSortable">
        	<div class="row">
				<div class="col-xl-6">
				  <!-- box -->
				  <div class="box">
					<div class="box-body">
					  <h5 class="text-center">Statistiques achat</h5>
					  <div class="text-center">
					  	<span class="font-size-40"><?php echo $pourc_tran;?>%</span>
					  </div>
						<div class="progress mb-5 progress-sm">
							<div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pourc_tran;?>%">
							  <span class="sr-only"></span>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
					  <div class="row no-gutters">
						<div class="col-6 col-sm-4">
						  <div class="description-block">
							<h5 class="description-header mb-5"><?php echo $nb_tran - $nb_tranOk;?></h5>
							<span class="description-text">Echecs</span>
						  </div>
						  <!-- /.description-block -->
						</div>
						<!-- /.col -->
						<div class="col-6 col-sm-4">
						  <div class="description-block">
							<h5 class="description-header mb-5"><?php echo $nb_tran;?></h5>
							<span class="description-text">Total</span>
						  </div>
						  <!-- /.description-block -->
						</div>
						<!-- /.col -->
						<div class="col-6 col-sm-4">
						  <div class="description-block">
                   			<h5 class="description-header text-green mb-5"><i class="fa fa-line-chart"></i></h5>
							<span class="description-text">TENDANCE</span>
						  </div>
						  <!-- /.description-block -->
						</div>
						<!-- /.col -->
					  </div>
					  <!-- /.row -->
					</div>
					<!-- /.box-footer -->
				  </div>
				  <!-- /.box -->
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
        </div>
        <!-- /.col -->
       </div>

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
	<script src="assets/vendor_components/jquery/dist/jquery.js"></script>

	<!-- jQuery UI 1.11.4 -->
	<script src="assets/vendor_components/jquery-ui/jquery-ui.js"></script>

	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	  $.widget.bridge('uibutton', $.ui.button);
	</script>

	<!-- popper -->
	<script src="assets/vendor_components/popper/dist/popper.min.js"></script>

	<!-- Bootstrap 4.0-->
	<script src="assets/vendor_components/bootstrap/dist/js/bootstrap.js"></script>

	<!-- ChartJS -->
	<script src="assets/vendor_components/chart-js/chart.js"></script>

	<!-- Sparkline -->
	<script src="assets/vendor_components/jquery-sparkline/dist/jquery.sparkline.js"></script>

	<!-- jvectormap -->
	<script src="assets/vendor_plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="assets/vendor_plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

	<!-- jQuery Knob Chart -->
	<script src="assets/vendor_components/jquery-knob/js/jquery.knob.js"></script>

	<!-- daterangepicker -->
	<script src="assets/vendor_components/moment/min/moment.min.js"></script>
	<script src="assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js"></script>

	<!-- datepicker -->
	<script src="assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>

	<!-- Bootstrap WYSIHTML5 -->
	<script src="assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js"></script>

	<!-- Slimscroll -->
	<script src="assets/vendor_components/jquery-slimscroll/jquery.slimscroll.js"></script>

	<!-- FastClick -->
	<script src="assets/vendor_components/fastclick/lib/fastclick.js"></script>

	<!-- apro_admin App -->
	<script src="js/template.js"></script>

	<!-- apro_admin dashboard demo (This is only for demo purposes) -->
	<script src="js/pages/dashboard.js"></script>

	<!-- apro_admin for demo purposes -->
	<script src="js/demo.js"></script>

	<!-- weather for demo purposes -->
	<script src="assets/vendor_plugins/weather-icons/WeatherIcon.js"></script>

	<script type="text/javascript">

		WeatherIcon.add('icon1'	, WeatherIcon.SLEET , {stroke:false , shadow:false , animated:true } );
		WeatherIcon.add('icon2'	, WeatherIcon.SNOW , {stroke:false , shadow:false , animated:true } );
		WeatherIcon.add('icon3'	, WeatherIcon.LIGHTRAINTHUNDER , {stroke:false , shadow:false , animated:true } );

	</script>


</body>


</html>
<?php
} else {

	header("location: index.php?t=1");
}
?>
