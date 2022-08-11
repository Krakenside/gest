<?php

session_start();

if (isset($_SESSION['connect']))//On vérifie que le variable existe.
{
  $connect=$_SESSION['connect'];//On récupère la valeur de la variable de session.
}
else
{
  $connect=0;//Si $_SESSION['connect'] n'existe pas, on donne la valeur "0".
}

if (isset($_SESSION['prodID']))//On vérifie que le variable existe.
{
  $prodID=$_SESSION['prodID'];//On récupère la valeur de la variable de session.
}

if (isset($_SESSION['artistID']))//On vérifie que le variable existe.
{
  $artistID=$_SESSION['artistID'];//On récupère la valeur de la variable de session.
}


if ($connect == "1") // Si le visiteur s'est identifié.
{
	require 'connexion.php';

  $req4 = 'SELECT * FROM artiste WHERE id_maison =\''.$prodID.'\'';

	$art  = $bdd ->query($req4);
	$nb_artis = $art -> rowCount();


	$req5='SELECT * FROM album INNER JOIN artiste ON album.id_artiste = artiste.id_artiste WHERE artiste.id_artiste = album.id_artiste AND artiste.id_maison =\''.$prodID.'\'' ;

	$alb  = $bdd ->query($req5);
	$nb_albu = $alb -> rowCount();


	$req9='SELECT * FROM son INNER JOIN artiste ON son.id_artiste = artiste.id_artiste WHERE son.id_artiste = artiste.id_artiste AND artiste.id_maison =\''.$prodID.'\'' ;

	$sn  = $bdd ->query($req9);
	$nb_son = $sn -> rowCount();


	$req6='SELECT * FROM telechargement 

  INNER JOIN album 
  INNER JOIN artiste 

  ON album.id_artiste = artiste.id_artiste

  WHERE album.id_album = telechargement.id_album 
    AND artiste.id_maison = \''.$prodID.'\' GROUP BY telechargement.id_telechargement';

	$te  = $bdd ->query($req6);
	$nb_telec = $te -> rowCount();

  
  $req2='SELECT * FROM telechargements INNER JOIN son INNER JOIN artiste ON son.id_artiste = artiste.id_artiste WHERE son.id_son = telechargements.id_son AND artiste.id_maison = \''.$prodID.'\' GROUP BY telechargements.id_telechargements';

  $te  = $bdd ->query($req2);
  $nb_telec2 = $te -> rowCount();


// 	$req7='SELECT * FROM transaction INNER JOIN telechargement INNER JOIN telechargements INNER JOIN son INNER JOIN album INNER JOIN artiste ON son.id_artiste = artiste.id_artiste AND album.id_artiste = artiste.id_artiste WHERE transaction.id_transaction = telechargements.id_transaction AND son.id_son = telechargements.id_son OR transaction.id_transaction = telechargement.id_transaction AND album.id_album = telechargement.id_album AND artiste.id_maison = \''.$prodID.'\' GROUP BY transaction.id_transaction';

// 	$tr  = $bdd ->query($req7);
// 	$nb_tran = $tr -> rowCount();

// 	$req8 ='SELECT * FROM transaction INNER JOIN telechargement INNER JOIN telechargements INNER JOIN son INNER JOIN album INNER JOIN artiste ON son.id_artiste = artiste.id_artiste AND album.id_artiste = artiste.id_artiste WHERE statut_transaction = 1 AND transaction.id_transaction = telechargements.id_transaction AND son.id_son = telechargements.id_son OR transaction.id_transaction = telechargement.id_transaction AND album.id_album = telechargement.id_album AND artiste.id_maison = \''.$prodID.'\' GROUP BY transaction.id_transaction';

// 	$trOk  = $bdd ->query($req8);
// 	$nb_tranOk = $trOk -> rowCount();

  $req7='SELECT *, SUBSTRING_INDEX(libelle_transaction, "-", 1) AS typeOfItem, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) AS titleOfSong, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) AS itemId  FROM transaction 
    
    INNER JOIN son
    INNER JOIN artiste 

     ON son.id_artiste = artiste.id_artiste

    WHERE
      SUBSTRING_INDEX(libelle_transaction, "-", 1) = "son"
    AND 
      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) = son.id_son
    AND 
      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) = son.titre_son
    AND 
      artiste.id_maison = \''.$prodID.'\'
    
    AND
      transaction.statut_transaction ="SUCCESS" 

    GROUP BY transaction.id_transaction';


  $tr  = $bdd ->query($req7);
  $nb_tranSon = $tr -> rowCount();

  $req8='SELECT *, SUBSTRING_INDEX(libelle_transaction, "-", 1) AS typeOfItem, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) AS titreOfAlbum, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) AS itemId  FROM transaction 
    
    INNER JOIN album
    INNER JOIN artiste 

     ON album.id_artiste = artiste.id_artiste

    WHERE
      SUBSTRING_INDEX(libelle_transaction, "-", 1) = "album"
    AND 
      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) = album.id_album
    AND 
      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) = album.titre_album
    AND 
      artiste.id_maison = \''.$prodID.'\'
    
    AND
      transaction.statut_transaction ="SUCCESS" 

    GROUP BY transaction.id_transaction';

  $trOk  = $bdd ->query($req8);
  $nb_tranAlbum = $trOk -> rowCount();

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
 include("menu3.php");
 include("header3.php");

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
      <div class="row">
	  <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              <h3><?php echo $nb_artis; ?></h3>

              <p>Nbre Artiste(s)</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <!-- <a href="liste.php?dest=admin" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $nb_albu; ?></h3>

              <p>Album(s)</p>
            </div>
            <div class="icon">
              <i class="fa fa-laptop"></i>
            </div>
            <!-- <a href="liste.php?dest=user" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $nb_son; ?><!--<sup style="font-size: 20px">%</sup>--></h3>

              <p>Son(s)</p>
            </div>
            <div class="icon">
              <i class="fa fa-group"></i>
            </div>
            <!-- <a href="liste.php?dest=classe" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->

        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $nb_tranAlbum; ?></h3>

              <p>Telechargement Album</p>
            </div>
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <!-- <a href="sites.php?dest=matiere" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->

        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-gray">
            <div class="inner">
              <h3><?php echo $nb_tranSon; ?></h3>

              <p>Telechargement Son</p>
            </div>
            <div class="icon">
              <i class="fa fa-graduation-cap"></i>
            </div>
            <!-- <a href="sites.php?dest=examen" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->



      </div>

      <!-- /.row -->
      <div class="row">
        
        <div class="col-xl-6">
		<!-- TABLE: LATEST ORDERS -->
          
          </div>
		  </div>
		<!-- /.col -->
        <div class="col-xl-6 connectedSortable">
        	<div class="row">
        
			     </div>
			<!-- /.row -->
        </div>
        <!-- /.col -->
       </div>
      <!-- /.row ->
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
