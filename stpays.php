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

if ($connect == "1" AND $_SESSION["userCompte"] == 'ADMINAFP20') // Si le visiteur s'est identifié.
{
	require 'connexion.php';
// On affiche la page cachée.

$table1 = $_GET['dest2'];

$source = isset($_GET["src"]) ? $_GET["src"] : '';
if(isset($_GET["dest"])){
	$dest = $_GET["dest"];
  // die($dest);
	} else {

		$dest = "0";
	}
if(isset($_GET["s"])){
	$s = $_GET["s"];
	} else {

		$s = "";
	}

  if(isset($_GET["dte"])){
  	$dterange = $_GET["dte"];
  	} else {

  		$dterange = "";
  	}

  if(isset($_GET["stat"])){
  	$stat = $_GET["stat"];
  	} else {

  		$stat = "";
  	}

if(isset($_GET["tp"])){
	$tp = $_GET["tp"];
	} else {

		$tp = "";
	}


	$id_clt = $_SESSION["userID"];
	$idtabl = ($dest == "logmajstat") ? "datatable-liste2" : "datatable-liste";
  $query = "SHOW FULL COLUMNS FROM ".$dest;
	 $q = $bdd -> query($query);
   $row = $q ->fetch();
   $commentC = $row['Comment'];
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

    <title>afreekaplay Admin -   <?php echo $commentC; ?> </title>

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
        <?php echo $commentC;?>
      </h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="acceuil.php"><i class="fa fa-dashboard"></i> Acceuil</a></li>

        <li class="breadcrumb-item active"><?php echo $source; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<?php
  							if ($_GET['dest'] == "admin"){

  						?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>

                      <tr>
                        <th>Nom et Prenoms</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                      </tr>
                    </thead>
                  <tbody id="tableau">



              <?php

                }
                else if ($_GET['dest'] == "maison"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					<th>Nom</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "artiste"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					<th>Nom artiste</th>
					<th>Maison de production</th>
          <th>Modifier</th>
          <th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "auteurS"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					<th>Nom auteur</th>
					<th>Nationalite</th>
          <th>Modifier</th>
          <th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "album"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					  <th>Reference</th>
					  <th>Titre album</th>
					  <th>Prix</th>
					  <th>fichier</th>
					  <th>Artiste</th>
					  <th>Lien</th>
            <th>Modifier</th>
            <th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "son"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					<th>Reference</th>
					<th>Titre du son</th>
					<th>Prix</th>
					<th>Duree</th>
					<th>Genre</th>
					<th>Artiste</th>
					<th>Album</th>
					<th>Fichier</th>
          <th>Lien</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "sonnerie"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					<th>Titre du son</th>
					<th>Prix</th>
					<th>Fichier</th>
					<th>Auteur</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "telechargement"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					<th>Statut</th>
					<th>Date</th>
					<th>Utilisateur</th>
					<th>Telecharge</th>
					<th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "transaction"){

              ?>

              <!-- Date range -->
              <div class="form-group">
                <label> Plage de date:</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right col-sm-4 col-12" id="reservation">
                </div>
                <!-- /.input group -->
              </div>
              <!-- /.form group -->
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
            <th>Reference</th>
  					<th>Montant</th>
  					<th>Numero de paiement</th>
  					<th>Date</th>
  					<th>Nom et prenoms</th>
  					<th>Statut trsx</th>
  					<th>Pays</th>
  					<th>Artiste</th>
  					<th>Titre</th>
  					<th>Integrateur</th>
  					<th>Libelle Trsx</th>
            <th>Maj Statut</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "playlist"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
  					<th>Nom de la playlist</th>
  					<th>Nb de son</th>
  					<th>Modifier</th>
  					<th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "user"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					<th>Nom et prenoms</th>
					<th>Téléphone</th>
					<th>Email</th>
					<th>Sexe</th>
					<th>Pays</th>
					<th>Username</th>
					<th>Date d'inscription</th>
					<th>Statut</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "client"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
					<th>Nom et prenoms</th>
					<th>Email</th>
					<th>Modifier</th>
					<th>Supprimer</th>
				  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "userplaylist"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
            <th>Nom de la playlist</th>
            <th>Nb de son</th>
            <th>Utilisateur</th>
            <th>Supprimer</th>
			  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "pays"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
            <th>Nom du Pays</th>
            <th>Code</th>
            <th>Indicatif</th>
            <th>Modifier</th>
            <th>Supprimer</th>
			  </tr>
				</thead>
          <tbody id="tableau">
              <?php
                }
                else if ($_GET['dest'] == "logmajstat"){

              ?>
              <table class="table table-bordered" id='<?php echo $idtabl; ?>' style="background-color:white">
                <thead>
				  <tr>
            <th>Transaction</th>
            <th>Initiateur</th>
            <th>Libelle</th>
            <th>Site</th>
            <th>Integrateur</th>
			  </tr>
				</thead>
          <tbody id="tableau">
              <?php
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
	<script >
	var s = '<?php echo $s; ?>';
	var tp = '<?php echo $tp; ?>';
	var dterange = '<?php echo $dterange; ?>';
	var stat = '<?php echo $stat; ?>';
		/* DATA TABLES */

			function init_DataTables() {

				console.log('run_datatables');

				if( typeof ($.fn.DataTable) === 'undefined'){ return; }
				console.log('init_DataTables');

				var handleDataTableButtons = function() {
				  if ($("#datatable-buttons").length) {
					$("#datatable-buttons").DataTable({
					  dom: "Bfrtip",
					  buttons: [
						{
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
					'columnDefs': [
					{ orderable: false, targets: [2,3,4],
							 targets: '_all',
							 defaultContent: '' }
				  ],
					'fixedColumns':   {
						leftColumns: 2
					},
				   scrollX: true,
					  responsive: false
					});
				  }
				};

				TableManageButtons = function() {
				  "use strict";
				  return {
					init: function() {
					  handleDataTableButtons();
					}
				  };
				}();


				$('#datatable-responsive').DataTable({
					"bJQueryUI": true,
					"sDom": '<""l><"F"f>tp',
					"ajax": {
						"url": "gestionbdd.php?table="+table+"&nom_fct="+2,
						"dataSrc":"",
						"type": "GET"
					},
					'columnDefs': [
					{ orderable: false, targets: [2,3,4],
							 targets: '_all',
							 defaultContent: '' }
				  ]
					});

				$('#datatable-part').DataTable({
					"bJQueryUI": true,
					"sDom": '<""l><"F"f>tp',
					"ajax": {
						"url": "gestionbdd.php?table="+table+"&nom_fct="+2,
						"dataSrc":"",
						"type": "GET"
					},
					'columnDefs': [
					{ orderable: false, targets: [2,3,4],
							 targets: '_all',
							 defaultContent: '' }
				  ],
				   scrollX: true,
					  responsive: false
					});

				var handleDataTableliste = function() {
				  if ($("#datatable-liste").length) {
					$("#datatable-liste").DataTable({
					  dom: "lBfrtip",
					  ajax: {
						"url": "gestionbdd.php?table="+table+"&dte="+dterange+"&stat="+stat+"&s="+s+"&tp="+tp+"&xs=1&nom_fct="+2,
						"dataSrc":"",
						"type": "GET"
					},
					  buttons: [
						{
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
					  'columnDefs': [
					{ orderable: true, targets: [1,2],
							 targets: '_all',
							 defaultContent: '' }
				  ],
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
						"url": "gestionbdd.php?table="+table+"&nom_fct=17",
						"dataSrc":"",
						"type": "GET"
					},
					  buttons: [
						{
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
					  columnDefs: [
						  {
							 targets: '_all',
							 defaultContent: ''
						  }
					  ],
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

	$(document).ready(function() {
    console.log(dterange);
    dte = dterange.split('-');
    //Date range picker
    //Date range picker
    $('#reservation').daterangepicker({
      startDate: (dterange == "") ? moment().subtract(1, 'month') : dte[0],
      endDate  : (dterange == "") ? moment() : dte[1]
    }

    );
		$('.widget-user-header').hover( function(){
		$('.trash').each( function(){
			$(this).toggleClass('hide', true);
		});

		$(this).find('.trash').toggleClass('hide', false);

	});
	$('.trash').on('click', function(e){
			e.preventDefault()
			//console.log("trash");
		var id = $(this).attr('data-id');
		var table = "articlew";
		var pare = $(this).parents('.cadre');
		//console.log(pare.attr('class'));
						$.ajax({
								url : 'gestionbdd.php',
								type : 'POST', // Le type de la requête HTTP, ici devenu POST
								data : 'id=' + id + '&table='+table+'&nom_fct='+3, //  On fait passer nos variables, exactement comme en GET, au script more_com.php
								dataType : 'text',
								success : function(reponse, statut){ // code_html contient le HTML renvoyé
								if (reponse !== "failed") {
										//$("#"+id).addClass("hide");
						alert("Une ligne Supprimée");
						pare.toggleClass('hide', true);

											//document.location.href="afficher.php?e=<php echo $_GET['a']; ?>";
									//console.log(reponse);
								} else{
									alert("Erreur signalée aucune ligne Supprimée");
								}
								},
								error : function(reponse, statut, erreur){

								}
							});

	});
	$('#tableau').on('click','.btn-info', function(){

              id= $(this).data('id');
              $("#"+id+"r2").removeClass("hide");
              $("#"+id).addClass("hide");
              console.log("supprim "+id);

            });

            $('#tableau').on('click','.valid_sup', function(){
              id= $(this).data('id');

              $.ajax({
                  url : 'gestionbdd.php',
                  type : 'POST', // Le type de la requête HTTP, ici devenu POST
                  data : 'id=' + id + '&table='+table+'&nom_fct='+3, //  On fait passer nos variables, exactement comme en GET, au script more_com.php
                  dataType : 'text',
                  success : function(reponse, statut){ // code_html contient le HTML renvoyé
                  if (reponse !== "failed") {
                      $("#"+id).addClass("hide");
						alert("Une ligne Supprimée");
                        document.location.href="liste.php?dest="+table+"&dte="+dterange+"&s="+s;
                    //console.log(reponse);
                  } else{
                    alert("Erreur signalée aucune ligne Supprimée");
                  }
                  },
                  error : function(reponse, statut, erreur){

                  }
                });
            });

            $('#tableau').on('click','.btn_stat', function(){
              id= $(this).data('id');
              name= $(this).data('name');
              name2 = (name == 'success') ? 'failed' : 'success';

              $.ajax({
                  // url : 'https://afreekaplay.com/gest/gestionbdd.php',
                  url : 'gestionbdd.php',
                  type : 'POST', // Le type de la requête HTTP, ici devenu POST
                  data : 'id=' + id + '&table=transaction&name='+ name +'&nom_fct='+15, //  On fait passer nos variables, exactement comme en GET, au script more_com.php
                  dataType : 'text',
                  success : function(reponse, statut){ // code_html contient le HTML renvoyé
                  if (reponse !== "failed") {
                      $("#"+name2+"-"+id).removeClass("hide");
                      $("#"+name+"-"+id).addClass("hide");
						            alert("MAJ effectuee");
                        document.location.href="liste.php?dest="+table+"&dte="+dterange+"&s="+s;
                  } else{
                    alert("Erreur signalée aucune ligne Supprimée");
                  }
                  },
                  error : function(reponse, statut, erreur){

                  }
                });
            });

			init_DataTables();

  $('#reservation').on('change', function(e){
    if (dterange = "") {

      dterange = $(this).val();
    } else {
      console.log($(this).val());
      dterange = $(this).val();
      // TableManageButtons.init();
      document.location.href="liste.php?dest="+table+"&dte="+dterange+"&s="+s;

    }
  });

  });
  </script>


</body>

<?php
} else {

	header("location: index.php?t=1");
}
?>
</html>
