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

$source = isset($_GET["src"]) ? $_GET["src"] : '';

if(isset($_GET["dest"])){
	$dest = $_GET["dest"];

	} else {

		$dest = "0";
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

    <title>afreekaplay Admin -   <?php echo $prodID; ?> </title>

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

</head>

<body class="hold-transition sidebar-mini skin-purple-light">
<div class="wrapper">

  <?php 
  	if (isset($_SESSION['prodID']))//On vérifie que le variable existe.
	{
	  include("menu3.php");
	}

	if (isset($_SESSION['artistID']))//On vérifie que le variable existe.
	{
	  include("menu4.php");
	}

  	include("header3.php");
	
 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $dest;?>
      </h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="acceuil.php"><i class="fa fa-dashboard"></i> Acceuil</a></li>

        <li class="breadcrumb-item active"><?php echo $source; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<?php
  			if ($_GET['dest'] == "Artistes"){
  		?>
			<table class="table table-bordered" style="background-color:white">

				<thead>

					<tr>
						<th>Nom Artiste</th>
						<th>Sons</th>
						<th>Albums</th>
						<th>Achat Son</th>
						<th>Achat Album</th>
					</tr>
				</thead>

			<tbody id="tableau">
				<?php
				
				    $tempTotalSon = 0;
				    $tempTotalAlb = 0;
				    
					$req='SELECT * FROM artiste WHERE id_maison =\''.$prodID.'\'';
						// $u=0;
						$us  = $bdd ->query($req);
						while($usr = $us -> fetch() /*AND $u < 10*/){

				  ?>
                  <tr>
                    <td><?php echo $usr['nom_artiste']; ?></td>
                    <?php
                    	  	$reqExtra1 = 'SELECT * FROM son WHERE id_artiste =\''.$usr['id_artiste'].'\'';

							$art  = $bdd ->query($reqExtra1);
							$nb_sons = $art -> rowCount();
							
							while($nb_so = $art -> fetch()){
							    
							    $reqExtra3='SELECT *, SUBSTRING_INDEX(libelle_transaction, "-", 1) AS typeOfItem, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) AS titreOfSong, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) AS itemId  FROM transaction 
    
    						    INNER JOIN son
    						    INNER JOIN artiste 
    
    						     ON son.id_artiste = \''.$usr['id_artiste'].'\'
    
    						    WHERE
    						      SUBSTRING_INDEX(libelle_transaction, "-", 1) = "son"
    						    AND 
    						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) = \''.$nb_so["id_son"].'\'
    						    AND 
    						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) = \''.$nb_so['titre_son'].'\'
    						    
                                AND
                                  transaction.statut_transaction ="SUCCESS" 
    
    						    GROUP BY transaction.id_transaction';
    						    
        						$art3  = $bdd ->query($reqExtra3);
        						$nb_Telsons = $art3 -> rowCount();
							    
							    $tempTotalSon += $nb_Telsons;
							}
							
							$reqExtra2 = 'SELECT * FROM album WHERE id_artiste =\''.$usr['id_artiste'].'\'';

							$art2  = $bdd ->query($reqExtra2);
							$nb_albums = $art2 -> rowCount();

							while($nb_alb = $art -> fetch()){
							
							    $reqExtra4='SELECT *, SUBSTRING_INDEX(libelle_transaction, "-", 1) AS typeOfItem, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) AS titleOfAlbum, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) AS itemId  FROM transaction 
    
    						    INNER JOIN album
    						    INNER JOIN artiste
    
    						     ON album.id_artiste = \''.$usr['id_artiste'].'\'
    
    						    WHERE
    						      SUBSTRING_INDEX(libelle_transaction, "-", 1) = "album"
    						    AND 
    						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) = \''.$nb_alb["id_album"].'\'
    						    AND 
    						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) = \''.$nb_alb["titre_album"].'\'
    						      
                                AND
                                  transaction.statut_transaction ="SUCCESS" 
    
    						    GROUP BY transaction.id_transaction';
    						
        						$art4  = $bdd ->query($reqExtra4);
        						$nb_Telalbums = $art4 -> rowCount(); 
        						
        						$tempTotalAlb += $nb_Telalbums;
        						
							}
							
    						
                    ?>
                    <td><?php echo $nb_sons; ?></td>
                    <td><?php echo $nb_albums; ?></td>
                    <td><?php echo $tempTotalSon; ?></td>
                    <td><?php echo $tempTotalAlb; ?></span></td>
                  </tr>
				  <?php
				  // $u++;
					}
				 
          	}
            
            else if ($_GET['dest'] == "Albums"){

            ?>
              <table class="table table-bordered" style="background-color:white">
                <thead>
				  <tr>
					<th>Titre</th>
					<th>Artiste</th>
					<th>Prix</th>
					<th>Telechargement</th>
				  </tr>
				</thead>
          	<tbody id="tableau">


              <?php

              if (isset($_SESSION['artistID']))//On vérifie que le variable existe.
				{
				  $req2='SELECT * FROM album INNER JOIN artiste  ON album.id_artiste = artiste.id_artiste WHERE album.id_artiste = \''.$artistID.'\'';
				} elseif (isset($_SESSION['prodID']))//On vérifie que le variable existe.
				{
				  $req2='SELECT * FROM album INNER JOIN artiste  ON album.id_artiste = artiste.id_artiste WHERE artiste.id_artiste = album.id_artiste AND artiste.id_maison =\''.$prodID.'\'';
				}

				
						$us2  = $bdd ->query($req2);
						while($usr2 = $us2 -> fetch() /*AND $u < 10*/){


				  ?>
                  <tr>
                    <td><a href="#"><?php echo $usr2['titre_album']; ?></a></td>
                    <td><?php echo $usr2['nom_artiste']; ?></td>
                    <td><?php echo $usr2['prix_album']; ?></td>
                    <?php

                    	$reqExtra4='SELECT *, SUBSTRING_INDEX(libelle_transaction, "-", 1) AS typeOfItem, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) AS titleOfAlbum, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) AS itemId  FROM transaction 
    
						    INNER JOIN album
						    INNER JOIN artiste

						     ON album.id_artiste = artiste.id_artiste 

						    WHERE
						      SUBSTRING_INDEX(libelle_transaction, "-", 1) = "album"
						    AND 
						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) = \''.$usr2["id_album"].'\'
						    AND 
						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) = \''.$usr2["titre_album"].'\'
						  
                            AND
                              transaction.statut_transaction ="SUCCESS" 

						    GROUP BY transaction.id_transaction';

						
						$art4  = $bdd ->query($reqExtra4);
						$nb_TelExAlbum = $art4 -> rowCount();

                    ?>

                    <td><?php echo $nb_TelExAlbum; ?></td>
                  </tr>
				  <?php
				  // $u++;
					}
				 
          	}
                else if ($_GET['dest'] == "Sons"){

              ?>
              <table class="table table-bordered" style="background-color:white">
                <thead>
				  <tr>
					<th>Titre</th>
					<th>Artiste</th>
					<th>Prix</th>
					<th>Telechargement</th>
				  </tr>
				</thead>
          	<tbody id="tableau">
              <?php

              if (isset($_SESSION['artistID']))//On vérifie que le variable existe.
				{
				  $req3='SELECT * FROM son INNER JOIN artiste ON son.id_artiste = artiste.id_artiste WHERE son.id_artiste = \''.$artistID.'\'';
				} elseif (isset($_SESSION['prodID']))//On vérifie que le variable existe.
				{
				  $req3='SELECT * FROM son INNER JOIN artiste ON son.id_artiste = artiste.id_artiste WHERE artiste.id_maison =\''.$prodID.'\'';
				}

					
						// $u=0;
						$us3  = $bdd ->query($req3);
						while($usr3 = $us3 -> fetch() /*AND $u < 10*/){


				  ?>
                  <tr>
                    <td><a href="#"><?php echo $usr3['titre_son']; ?></a></td>
                    <td><?php echo $usr3['nom_artiste']; ?></td>
                    <td><?php echo $usr3['prix_son']; ?></td>
                    <?php

						$reqExtra5='SELECT *, SUBSTRING_INDEX(libelle_transaction, "-", 1) AS typeOfItem, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) AS titreOfSong, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) AS itemId  FROM transaction 
    
						    INNER JOIN son
						    INNER JOIN artiste 

						     ON son.id_artiste = artiste.id_artiste

						    WHERE
						      SUBSTRING_INDEX(libelle_transaction, "-", 1) = "son"
						    AND 
						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) = \''.$usr3["id_son"].'\'
						    AND 
						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) = \''.$usr3['titre_son'].'\'
						      
                            AND
                              transaction.statut_transaction ="SUCCESS" 

						    GROUP BY transaction.id_transaction';
						$art5  = $bdd ->query($reqExtra5);
						$nb_TelExSon = $art5 -> rowCount();

                    ?>

                    <td><?php echo $nb_TelExSon; ?></td>
                  </tr>
				  <?php
				  // $u++;
					}
				 
          	}
                else if ($_GET['dest'] == "Album"){

              ?>
              <table class="table table-bordered" style="background-color:white">
                <thead>
				  <tr>
					<th>Titre</th>
					<th>Artiste</th>
					<th>Prix</th>
					<th>Telechargement</th>
				  </tr>
				</thead>
          	<tbody id="tableau">
              <?php
					$req4='SELECT * FROM album INNER JOIN artiste ON album.id_artiste = artiste.id_artiste WHERE album.id_artiste =\''.$artistID.'\'';
						// $u=0;
						$us4  = $bdd ->query($req4);
						while($usr4 = $us4 -> fetch() /*AND $u < 10*/){


				  ?>
                  <tr>
                    <td><a href="#"><?php echo $usr4['titre_album']; ?></a></td>
                    <td><?php echo $usr4['nom_artiste']; ?></td>
                    <td><?php echo $usr4['prix_album']; ?></td>
                    <?php

                    	$reqExtra6='SELECT *, SUBSTRING_INDEX(libelle_transaction, "-", 1) AS typeOfItem, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) AS titleOfAlbum, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) AS itemId  FROM transaction 
    
						    INNER JOIN album
						    INNER JOIN artiste 

						     ON album.id_artiste = artiste.id_artiste

						    WHERE
						      SUBSTRING_INDEX(libelle_transaction, "-", 1) = "album"
						    AND 
						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) = \''.$usr4["id_album"].'\'
						    AND 
						      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) = \''.$usr4["titre_album"].'\'
						      
    AND
      transaction.statut_transaction ="SUCCESS" 

						    GROUP BY transaction.id_transaction';

						$art6  = $bdd ->query($reqExtra6);
						$nb_TelExAlbum1 = $art6 -> rowCount();

                    ?>

                    <td><?php echo $nb_TelExAlbum1; ?></td>
                  </tr>
				  <?php
				  // $u++;
					}
				 
          	}
                else if ($_GET['dest'] == "Son"){

              ?>
              <table class="table table-bordered" style="background-color:white">
                <thead>
				  <tr>
					<th>Titre</th>
					<th>Artiste</th>
					<th>Prix</th>
					<th>Telechargement</th>
				  </tr>
				</thead>
          	<tbody id="tableau">
              <?php
					$req5='SELECT * FROM son INNER JOIN artiste  ON son.id_artiste = artiste.id_artiste WHERE son.id_artiste = \''.$artistID.'\'';
						// $u=0;
						$us5  = $bdd ->query($req5);
						while($usr5 = $us5 -> fetch() /*AND $u < 10*/){


				  ?>
                  <tr>
                    <td><a href="#"><?php echo $usr5['titre_son']; ?></a></td>
                    <td><?php echo $usr5['nom_artiste']; ?></td>
                    <td><?php echo $usr5['prix_son']; ?></td>
                    <?php

                    $reqExtra7='SELECT *, SUBSTRING_INDEX(libelle_transaction, "-", 1) AS typeOfItem, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) AS titleOfSong, SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) AS itemId  FROM transaction 
    
				    INNER JOIN son
				    INNER JOIN artiste 

				    ON son.id_artiste = artiste.id_artiste

				    WHERE
				      SUBSTRING_INDEX(libelle_transaction, "-", 1) = "son"
				    AND 
				      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 3), "-", -1) = \''.$usr5["id_son"].'\'
				    AND 
				      SUBSTRING_INDEX(SUBSTRING_INDEX(libelle_transaction, "-", 2), "-", -1) = \''.$usr5["titre_son"].'\'
				      
    AND
      transaction.statut_transaction ="SUCCESS" 

				    GROUP BY transaction.id_transaction';

						$art7  = $bdd ->query($reqExtra7);
						$nb_TelExSon1 = $art7 -> rowCount();

                    ?>

                    <td><?php echo $nb_TelExSon1; ?></td>
                  </tr>
				  <?php
				  // $u++;
					}
				 
          	}
				?>
				
				</tbody>
		</table>

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

	<!-- apro_admin App -->
	<script src="js/template.js"></script>

	<!-- apro_admin for demo purposes -->
	<script src="js/demo.js"></script>


</body>

<?php
} else {

	header("location: index.php?t=1");
}
?>
</html>
