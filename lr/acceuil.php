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

if ($connect == "1") // Si le visiteur s'est identifié.
{
	require 'connexion.php';


	$req='SELECT * FROM admin';

						$adm  = $bdd ->query($req);
						$nb_adm = $adm -> rowCount();

	$req2='SELECT * FROM user';

						$us  = $bdd ->query($req2);
						$nb_usr = $us -> rowCount();


	$req3='SELECT * FROM maison';

						$mai  = $bdd ->query($req3);
						$nb_mais = $mai -> rowCount();


	$req4='SELECT * FROM artiste';

						$art  = $bdd ->query($req4);
						$nb_artis = $art -> rowCount();


	$req5='SELECT * FROM album';

						$alb  = $bdd ->query($req5);
						$nb_albu = $alb -> rowCount();
						$nb_artis = $art -> rowCount();


	$req9='SELECT * FROM son';

						$sn  = $bdd ->query($req9);
						$nb_son = $sn -> rowCount();


	$req6='SELECT * FROM telechargement';

						$te  = $bdd ->query($req6);
						$nb_telec = $te -> rowCount();

	$req7='SELECT * FROM transaction';

						$tr  = $bdd ->query($req7);
						$nb_tran = $tr -> rowCount();

	$req8 ='SELECT * FROM transaction WHERE statut_transaction = 1';

						$trOk  = $bdd ->query($req8);
						$nb_tranOk = $trOk -> rowCount();

$pourc_tel = ($nb_son !== 0) ? round(($nb_telec*100)/$nb_son, 2) : 0;
$pourc_tran = ($nb_tran !== 0) ? round(($nb_tranOk*100)/$nb_tran, 2) : 0;
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
 include("menu.php");
 include("header.php");

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
              <h3><?php echo $nb_adm; ?></h3>

              <p>Administrateur</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="liste.php?dest=admin" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $nb_usr; ?></h3>

              <p>Utilisateurs</p>
            </div>
            <div class="icon">
              <i class="fa fa-laptop"></i>
            </div>
            <a href="liste.php?dest=user" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $nb_mais; ?><!--<sup style="font-size: 20px">%</sup>--></h3>

              <p>Maison de production</p>
            </div>
            <div class="icon">
              <i class="fa fa-group"></i>
            </div>
            <a href="liste.php?dest=classe" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $nb_artis; ?></h3>

              <p>Artiste</p>
            </div>
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <a href="sites.php?dest=matiere" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-gray">
            <div class="inner">
              <h3><?php echo $nb_albu; ?></h3>

              <p>Album</p>
            </div>
            <div class="icon">
              <i class="fa fa-graduation-cap"></i>
            </div>
            <a href="sites.php?dest=examen" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-xl-3 col-md-6 col-6">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              <h3><?php echo $nb_telec; ?></h3>

              <p>Telechargement</p>
            </div>
            <div class="icon">
              <i class="fa fa-question"></i>
            </div>
            <a href="liste.php?dest=quizz" class="small-box-footer">Y Acceder <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
        <!-- ./col -->


      </div>

      <!-- /.row -->
      <div class="row">
        <!--<div class="col-xl-6 connectedSortable">
          <!-- MAP & BOX PANE ->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Our Visitors</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header ->
            <div class="box-body no-padding">
			  <div class="pad">
				<!-- Map will be created here ->
				<div id="visitfromworld" style="height: 360px;"></div>
			  </div>
            </div>
            <!-- /.box-body ->
          </div>
          <!-- /.box ->
        </div>-->
        <div class="col-xl-6">
		<!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Utilisateurs dernierement inscrits</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>

            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-responsive no-margin">
                  <thead>
                  <tr>
                    <th>Noms et Prenoms</th>
                    <th>Telephone</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Date d'inscr</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
					$req='SELECT * FROM user ORDER BY id_user DESC';
						$u=0;
						$us  = $bdd ->query($req);
						while($usr = $us -> fetch() AND $u < 10){


				  ?>
                  <tr>
                    <td><a href="#"><?php echo $usr['nom_user']; ?></a></td>
                    <td><?php echo $usr['telephone_user']; ?></td>
                    <td><?php echo $usr['email_user']; ?></td>
                    <td><?php echo $usr['username_user']; ?></td>
                    <td><span class="label bg-purple"><?php echo $listmat; ?></span></td>
                    <td>
                      <?php echo $usr['date_verif_user']; ?>
                    </td>
                  </tr>
				  <?php
				  $u++;
						}
				  ?>
                  <!--<tr>
                    <td><a href="#">ODN84845</a></td>
                    <td>Apple TV</td>
                    <td><span class="label bg-yellow">Pending</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f39c12" data-height="20">40,80,-90,80,61,-73,68</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="#">ODN84982</a></td>
                    <td>Samsung TV</td>
                    <td><span class="label bg-green">Delivered</span></td>
                    <td>
                      <div class="sparkbar" data-color="#41b613" data-height="20">60,50,90,-40,91,-53,83</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="#">ODN85452</a></td>
                    <td>Intex Smart Watch</td>
                    <td><span class="label bg-blue">Processing</span></td>
                    <td>
                      <div class="sparkbar" data-color="#45aef1" data-height="20">40,80,-90,80,61,-73,68</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="#">ODN94992</a></td>
                    <td>Onida AC</td>
                    <td><span class="label bg-yellow">Pending</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f39c12" data-height="20">40,80,-90,80,61,-73,68</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="#">ODN98952</a></td>
                    <td>iPhone 7 Plus</td>
                     <td><span class="label bg-green">Delivered</span></td>
                    <td>
                      <div class="sparkbar" data-color="#41b613" data-height="20">60,50,90,-40,91,-53,83</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="#">ODN88989</a></td>
                    <td>Samsung LED</td>
                    <td><span class="label bg-purple">Shipped</span></td>
                    <td>
                      <div class="sparkbar" data-color="#926dde" data-height="20">60,50,90,-40,91,-53,83</div>
                    </td>
                  </tr> -->
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body - ->
            <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info pull-left">Modifier une page</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default pull-right">Voir plus de modif</a>
            </div>
            <!-- /.box-footer -->
          </div>
		  </div>
		<!-- /.col -->
        <div class="col-xl-6 connectedSortable">
        	<div class="row">
        		<div class="col-xl-6">
				  <!-- box -->
				  <div class="box">
					<div class="box-body">
					  <h5 class="text-center">Statistiques telechargement</h5>
					  <div class="text-center">
					  	<span class="font-size-40"><?php echo $pourc_tel;?>%</span>
					  </div>
						<div class="progress mb-5 progress-sm">
							<div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pourc_tel;?>%">
							  <span class="sr-only"></span>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
					  <div class="row no-gutters">
						<div class="col-6 col-sm-4">
						  <div class="description-block">
							<h5 class="description-header mb-5"><?php echo $nb_telec;?></h5>
							<span class="description-text">Telech</span>
						  </div>
						  <!-- /.description-block -->
						</div>
						<!-- /.col -->
						<div class="col-6 col-sm-4">
						  <div class="description-block">
							<h5 class="description-header mb-5"><?php echo $nb_son;?></h5>
							<span class="description-text">Total</span>
						  </div>
						  <!-- /.description-block -->
						</div>
						<!-- /.col -->
						<div class="col-6 col-sm-4">
						  <div class="description-block">
                   			<h5 class="description-header text-green mb-5"><i class="fa fa-line-chart"></i></h5>
							<span class="description-text">Tendance</span>
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
							<h5 class="description-header mb-5"><?php echo $nb_tranOk - $nb_tran;?></h5>
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
      <!-- /.row ->
      <div class="row">

		 <div class="col-xl-4">
          <!-- PRODUCT LIST ->
			  <div class="box box-primary">
				<div class="box-header with-border">
				  <h3 class="box-title">Recently Products</h3>

				  <div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				  </div>
				</div>
				<!-- /.box-header ->
				<div class="box-body">
				  <ul class="products-list product-list-in-box">
					<li class="item">
					  <div class="product-img">
						<img src="images/default-50x50.gif" alt="Product Image">
					  </div>
					  <div class="product-info">
						<a href="javascript:void(0)" class="product-title">iphone 7plus
						  <span class="label label-warning pull-right">$300</span></a>
						<span class="product-description">
							  12MP Wide-angle and telephoto cameras.
							</span>
					  </div>
					</li>
					<!-- /.item ->
					<li class="item">
					  <div class="product-img">
						<img src="images/default-50x50.gif" alt="Product Image">
					  </div>
					  <div class="product-info">
						<a href="javascript:void(0)" class="product-title">Apple Tv
						  <span class="label label-info pull-right">$400</span></a>
						<span class="product-description">
							  Library | For You | Browse | Radio
							</span>
					  </div>
					</li>
					<!-- /.item ->
					<li class="item">
					  <div class="product-img">
						<img src="images/default-50x50.gif" alt="Product Image">
					  </div>
					  <div class="product-info">
						<a href="javascript:void(0)" class="product-title">MacBook Air<span
							class="label label-danger pull-right">$450</span></a>
						<span class="product-description">
							  Make big things happen. All day long.
							</span>
					  </div>
					</li>
					<!-- /.item ->
					<li class="item">
					  <div class="product-img">
						<img src="images/default-50x50.gif" alt="Product Image">
					  </div>
					  <div class="product-info">
						<a href="javascript:void(0)" class="product-title">iPad Pro
						  <span class="label label-success pull-right">$289</span></a>
						<span class="product-description">
							  Anything you can do, you can do better.
							</span>
					  </div>
					</li>
					<!-- /.item ->
				  </ul>
				</div>
				<!-- /.box-body ->
				<div class="box-footer text-center">
				  <a href="javascript:void(0)" class="uppercase">View All Products</a>
				</div>
				<!-- /.box-footer ->
			  </div>
		 </div>
        <div class="col-xl-8 connectedSortable">
		  <!-- bar CHART ->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">User Statistics</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:215px"></canvas>
              </div>
            </div>
            <!-- /.box-body ->

            <div class="box-footer">
              <div class="row">
                <div class="col-6 col-sm-3">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                    <h5 class="description-header">$12,000</h5>
                    <span class="description-text">DAILY SALES</span>
                  </div>
                  <!-- /.description-block ->
                </div>
                <!-- /.col ->
                <div class="col-6 col-sm-3">
                  <div class="description-block border-right">
                    <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 30%</span>
                    <h5 class="description-header">$5,000</h5>
                    <span class="description-text">WEEKLY SALES</span>
                  </div>
                  <!-- /.description-block ->
                </div>
                <!-- /.col ->
                <div class="col-6 col-sm-3">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 60%</span>
                    <h5 class="description-header">$10,000</h5>
                    <span class="description-text">MONTHLY SALES</span>
                  </div>
                  <!-- /.description-block ->
                </div>
                <!-- /.col ->
                <div class="col-6 col-sm-3">
                  <div class="description-block">
                    <span class="description-percentage text-blue"><i class="fa fa-caret-up"></i> 40%</span>
                    <h5 class="description-header">$50,000</h5>
                    <span class="description-text">YEARLY SALES</span>
                  </div>
                  <!-- /.description-block ->
                </div>
                <!-- /.col ->
              </div>
              <!-- /.row ->
            </div>
            <!-- /.box-footer ->
          </div>
          <!-- /.box ->
        </div>
      </div>-->
      <!-- /.row ->
      <div class="row">
      	<div class="col-xl-6">
      		<!-- quick email widget ->
          <div class="box box-info">
            <div class="box-header">
              <i class="fa fa-envelope"></i>

              <h3 class="box-title">Quick Email</h3>
              <!-- tools box ->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-danger btn-sm" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools ->
            </div>
            <div class="box-body">
              <form action="#" method="post">
                <div class="form-group">
                  <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" placeholder="Subject">
                </div>
                <div>
                  <textarea class="textarea" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
              </form>
            </div>
            <div class="box-footer clearfix">
              <button type="button" class="pull-right btn btn-blue" id="sendEmail"> Send <i class="fa fa-paper-plane-o"></i></button>
            </div>
          </div>
      	</div>

      	<div class="col-xl-6">
      		<!-- TABLE: LATEST ORDERS ->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">New Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header ->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table table-responsive no-margin">
                  <thead>
                  <tr>
                    <th>Order No</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Popularity</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td><a href="pages/examples/invoice.html">ODN84952</a></td>
                    <td>Iphone 6s</td>
                    <td><span class="label bg-purple">Shipped</span></td>
                    <td>
                      <div class="sparkbar" data-color="#926dde" data-height="20">60,50,90,-40,91,-53,83</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">ODN84845</a></td>
                    <td>Apple TV</td>
                    <td><span class="label bg-yellow">Pending</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f39c12" data-height="20">40,80,-90,80,61,-73,68</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">ODN84982</a></td>
                    <td>Samsung TV</td>
                    <td><span class="label bg-green">Delivered</span></td>
                    <td>
                      <div class="sparkbar" data-color="#41b613" data-height="20">60,50,90,-40,91,-53,83</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">ODN85452</a></td>
                    <td>Intex Smart Watch</td>
                    <td><span class="label bg-blue">Processing</span></td>
                    <td>
                      <div class="sparkbar" data-color="#45aef1" data-height="20">40,80,-90,80,61,-73,68</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">ODN94992</a></td>
                    <td>Onida AC</td>
                    <td><span class="label bg-yellow">Pending</span></td>
                    <td>
                      <div class="sparkbar" data-color="#f39c12" data-height="20">40,80,-90,80,61,-73,68</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">ODN98952</a></td>
                    <td>iPhone 7 Plus</td>
                     <td><span class="label bg-green">Delivered</span></td>
                    <td>
                      <div class="sparkbar" data-color="#41b613" data-height="20">60,50,90,-40,91,-53,83</div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">ODN88989</a></td>
                    <td>Samsung LED</td>
                    <td><span class="label bg-purple">Shipped</span></td>
                    <td>
                      <div class="sparkbar" data-color="#926dde" data-height="20">60,50,90,-40,91,-53,83</div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive ->
            </div>
            <!-- /.box-body ->
            <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info pull-left">Place New Order</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer ->
          </div>
      	</div>

      </div>-->
      <!-- fin row-->
	</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 <?php
	include("footer.php");
 ?>

  <!-- Control Sidebar ->
  <aside class="control-sidebar control-sidebar-light">
    <!-- Create the tabs ->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="nav-item"><a href="#control-sidebar-home-tab" data-toggle="tab">Home</a></li>
      <li class="nav-item"><a href="#control-sidebar-settings-tab" data-toggle="tab">Settings</a></li>
    </ul>
    <!-- Tab panes ->
    <div class="tab-content">
      <!-- Home tab content ->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Admin Birthday</h4>

                <p>Will be July 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Jhone Updated His Profile</h4>

                <p>New Email : jhone_doe@demo.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Disha Joined Mailing List</h4>

                <p>disha@demo.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Code Change</h4>

                <p>Execution time 15 Days</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu ->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Web Design
                <span class="label label-danger pull-right">40%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 40%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Data
                <span class="label label-success pull-right">75%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 75%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Order Process
                <span class="label label-warning pull-right">89%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 89%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Development
                <span class="label label-primary pull-right">72%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 72%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu ->

      </div>
      <!-- /.tab-pane ->
      <!-- Stats tab content ->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane ->
      <!-- Settings tab content ->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <input type="checkbox" id="report_panel" class="chk-col-grey" >
			<label for="report_panel" class="control-sidebar-subheading ">Report panel usage</label>

            <p>
              general settings information
            </p>
          </div>
          <!-- /.form-group ->

          <div class="form-group">
            <input type="checkbox" id="allow_mail" class="chk-col-grey" >
			<label for="allow_mail" class="control-sidebar-subheading ">Mail redirect</label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group ->

          <div class="form-group">
            <input type="checkbox" id="expose_author" class="chk-col-grey" >
			<label for="expose_author" class="control-sidebar-subheading ">Expose author name</label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group ->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <input type="checkbox" id="show_me_online" class="chk-col-grey" >
			<label for="show_me_online" class="control-sidebar-subheading ">Show me as online</label>
          </div>
          <!-- /.form-group ->

          <div class="form-group">
            <input type="checkbox" id="off_notifications" class="chk-col-grey" >
			<label for="off_notifications" class="control-sidebar-subheading ">Turn off notifications</label>
          </div>
          <!-- /.form-group ->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              <a href="javascript:void(0)" class="text-red margin-r-5"><i class="fa fa-trash-o"></i></a>
              Delete chat history
            </label>
          </div>
          <!-- /.form-group ->
        </form>
      </div>
      <!-- /.tab-pane ->
    </div>
  </aside>
  -->
  <!-- /.control-sidebar -->

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
