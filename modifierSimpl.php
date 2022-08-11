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
	$indx = $_GET["indx"];

	$tabl = $_GET["t"];
		//$tabl = "textew";
	/*$req='SELECT * FROM sitew where id_site='.$indx;
	   $si  = $bdd ->query($req);
		$sit = $si->fetch(PDO::FETCH_ASSOC);*/

	$req=$bdd->query("SHOW COLUMNS FROM ".$tabl);
	$lign = $req -> rowCount();
	$query = "SHOW FULL COLUMNS FROM ".$tabl;
	 $q = $bdd -> query($query);
	$i=0;
while($champ = $req->fetch(PDO::FETCH_ASSOC))
 {
 // var_dump ($donnees );

  //$i++;
  $chaine = $champ['Field'];
  $souchaine1 = "image";
  $souchaine2 = "date";
  $souchaine3 = "mail";
  $souchaine4 = "id";
  $souchaine5 = "password";
  $souchaine6 = "url";
  $souchaine7 = "texte";
  $souchaine8 = "description";
  $souchaine9 = "cour";
  $souchaine10 = "temps";
  $souchaine11 = "fichier";
  $souchaine12 = "cover";

  if(strpos($chaine, $souchaine1) !== FALSE){

	  $tpe = "file";
	  //$valeur ="image";
  } else if (strpos($chaine, $souchaine2) !== FALSE){
	  //$valeur ="2017-2-2";
	  $tpe = "date";
  }  else if (strpos($chaine, $souchaine3) !== FALSE){
	 // $valeur ="1";
	  $tpe = "email";
  }  else if (strpos($chaine, $souchaine4) !== FALSE){
	 // $valeur ="1";
	  $tpe = "id";
  }  else if (strpos($chaine, $souchaine5) !== FALSE){
	 // $valeur ="1";
	  $tpe = "password";
  }  else if (strpos($chaine, $souchaine6) !== FALSE){
	 // $valeur ="1";
	  $tpe = "url";
  }
    else if ((strpos($chaine, $souchaine7) !== FALSE) OR (strpos($chaine, $souchaine8) !== FALSE)){
	 // $valeur ="1";
	  $tpe = "textearea";
  }
    else if ((strpos($chaine, $souchaine10) !== FALSE) ){
	 // $valeur ="1";
	  $tpe = "time";
  }
    else if ((strpos($chaine, $souchaine11) !== FALSE) OR (strpos($chaine, $souchaine9) !== FALSE) OR (strpos($chaine, $souchaine12) !== FALSE)){
	 // $valeur ="1";
	  $tpe = "file";
  } else {
	  //$valeur ="text";
	  $tpe = "text";
  }

  $row = $q ->fetch();
  $commentC[$i] = $row['Comment'];
  $hidden[$i] = "";
  $hold[$i] = "";

  if($row['Comment'] == "Avatar"){
	  $hold[$i] = "M ou F";
  }
  if($chaine == "datenreg_user"){
	  $hidden[$i] = "hidden";
  }
  $typ_tabl[$i] = $tpe;
  $table[$i] = $chaine;
  if($chaine == "visible_son"){ $hidden[$i] = "hidden"; }
  // if($chaine == "date_verif"){ $hidden[$i] = "hidden"; }
  //echo $typ_tabl[$i]." de la table ".$table[$i]."<br>" ;
 //echo $tabl."<br>";
 $i++;
 }
$req->closeCursor();

					if(isset($_GET['m'])){
    if($_GET['m'] == 1){
      echo "<script>alert('".$_GET['t']." modifié');</script>";
    }
    }

	$reqmod = "SELECT * FROM ".$tabl." where id_".$tabl." = ".$indx;
		$dam = $bdd -> query($reqmod);
		$sdam = $dam -> fetch();
    //var_dump($sdam);
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

    <title>afreekaplay Admin - Modifer  <?php echo '';?> </title>

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
    label{ margin-top: 1%;}
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
        Modifiation - <?php echo $commentC[0]; ?>
      </h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="acceuil.php"><i class="fa fa-dashboard"></i> Acceuil</a></li>

        <li class="breadcrumb-item active">Modifiation</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<form action="gestionbdd.php" method="post" class="" id="form_ajou" enctype="multipart/form-data" novalidate>
	<div class="form-group">
	<input name="table" type="text" value="<?php echo $tabl;?>" hidden>
	<input name="nom_fct" type="text" value=4 hidden>
	<input name="id_<?php echo $tabl;?>" type="text" value=<?php echo $indx;?> hidden>


		<?php
		$i=1;
			while ($i < $lign){
				//echo $typ_tabl[$i];
        if ($typ_tabl[$i] == "id") {
				/*	$t = strstr($tabl[$i], "_", false);
					$tt = substr($t,1) ;*/

					if($tabl == 'artiste'){

						?>
			<label><?php echo $commentC[$i];?></label>
			    <select name="<?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">
            <option value="0" >Aucune</option>
          
				<?php
				$req='SELECT * FROM maison';

									$ca  = $bdd ->query($req);
									while($cat = $ca->fetch(PDO::FETCH_ASSOC))
								{
										if($sdam["id_maison"] == $cat['id_maison']){
											$isSel="selected";
										} else{$isSel="";}
			?>
                  <option value="<?php echo $cat['id_maison'];?>" <?php echo $isSel; ?>><?php echo  $cat['nom_maison'];?></option>
                <?php
								}
								?>
                </select>

		<?php
  }  else if($tabl == 'album'){

              if ($table[$i]=="id_artiste") {
                ?>
                <label ><?php echo $commentC[$i];?></label>
                <select name="<?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">
                <option value="" selected>Aucun</option>
                <?php
                $req3='SELECT * FROM artiste';

                      $ca3  = $bdd ->query($req3);
                      while($cat3 = $ca3->fetch(PDO::FETCH_ASSOC))
                    {
                        if($sdam["id_artiste"] == $cat3['id_artiste']){
                          $isSel="selected";
                        } else{$isSel="";}
                ?>
                      <option value="<?php echo $cat3['id_artiste'];?>"  <?php echo $isSel; ?>><?php echo  $cat3['nom_artiste'];?></option>
                    <?php
                    }
                    ?>
                    </select>

                <?php
              }
              elseif ($table[$i]=="id_genre") {
                ?>
                <label ><?php echo $commentC[$i];?></label>
                <select name="<?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">
                <?php
                $req4='SELECT * FROM genre';

                      $ca4  = $bdd ->query($req4);
                      while($cat4 = $ca4->fetch(PDO::FETCH_ASSOC))
                    {
                        if($sdam["id_genre"] == $cat4['id_genre']){
                          $isSel="selected";
                        } else{$isSel="";}
                  ?>
                        <option value="<?php echo $cat4['id_genre'];?>"  <?php echo $isSel; ?>><?php echo  $cat4['titre_genre'];?></option>
                      <?php
                      }
                      ?>
                      </select>

                  <?php
                }


					}
					else if($tabl == 'son'){
            if ($table[$i] == "id_album") {

						?>
			<label><?php echo $commentC[$i];?></label>
			    <select name="<?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">
            <option value="" >Aucun</option>
          <?php
				$req='SELECT * FROM album';

									$ca  = $bdd ->query($req);
									while($cat = $ca->fetch(PDO::FETCH_ASSOC))
								{
										if($sdam["id_album"] == $cat['id_album']){
											$isSel="selected";
										} else{$isSel="";}
			?>
                  <option value="<?php echo $cat['id_album'];?>"  <?php echo $isSel; ?>><?php echo  $cat['titre_album'];?></option>
                <?php
								}
								?>
                </select>

		<?php

            } else if ($table[$i] == "id_artiste") {
    ?>
    <label><?php echo $commentC[$i];?></label>
        <select name="<?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">
      <?php


      $req='SELECT * FROM artiste';

                $cha  = $bdd ->query($req);
                while($chap = $cha->fetch(PDO::FETCH_ASSOC))
              {
                  if($sdam["id_artiste"] == $chap['id_artiste']){
                    $isSel="selected";
                  } else{$isSel="";}

    ?>
                <option value="<?php echo $chap['id_artiste'];?>" <?php echo $isSel; ?>><?php echo  $chap['nom_artiste'];?></option>
              <?php
              }
              ?>
              </select>
              <?php
              } elseif ($table[$i]=="id_genre") {
                ?>
                <label ><?php echo $commentC[$i];?></label>
                <select name="<?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">
                <?php
                $req4='SELECT * FROM genre';

                      $ca4  = $bdd ->query($req4);
                      while($cat4 = $ca4->fetch(PDO::FETCH_ASSOC))
                    {
                        if($sdam["id_genre"] == $cat4['id_genre']){
                          $isSel="selected";
                        } else{$isSel="";}
                ?>
                      <option value="<?php echo $cat4['id_genre'];?>"  <?php echo $isSel; ?>><?php echo  $cat4['titre_genre'];?></option>
                    <?php
                    }
                    ?>
                    </select>

                <?php
                }
					}
					else if($tabl == 'lyric'){
							?>
			<label><?php echo $commentC[$i];?></label>
			    <select name="<?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">
				<?php
				$req='SELECT * FROM son';

									$cla  = $bdd ->query($req);
									while($clam = $cla->fetch(PDO::FETCH_ASSOC))
								{
										if($sdam[$table[$i]] == $clam['id_son']){
											$isSel="selected";
										} else{$isSel="";}
			?>
                  <option value="<?php echo $clam['id_son'];?>" <?php echo $isSel; ?>><?php echo  $clam['titre_son'];?></option>
                <?php
								}
								?>
                </select>

		<?php

					}
					else {
		?>
			<label><?php echo $commentC[$i];?></label>
                <select name="<?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">
                  <option selected="selected" value="<?php echo $indx;?>"><?php echo $indx;?></option>

                </select>

		<?php
					}
			} else if($typ_tabl[$i] == "date") {
					if($hidden[$i] !== "hidden"){
				?>
				<label><?php echo $commentC[$i];?>:</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name="<?php echo $table[$i];?>" type="text" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask  value="<?php echo $sdam[$table[$i]];?>">
                </div>

				<?php
			}
			} else if($typ_tabl[$i] == "textearea") {

				?>
				<label><?php echo $commentC[$i];?>:</label>

                <textarea class="form-control textarea" id="<?php echo $table[$i];?>" name="<?php echo $table[$i];?>" rows="20" placeholder="Entrez ..."><?php echo $sdam[$table[$i]];?></textarea>

				<?php
			} else {

				?>

				  <label <?php echo $hidden[$i];?>><?php echo $commentC[$i];?></label>
					<?php if($table[$i] == "lien_vers_im" and $indx=15) { ?>
					<input class="form-control" name="<?php echo $table[$i];?>" value="<?php echo $sit['lien_site'].'/gest/images/up/lta/';?>"  type="<?php echo $typ_tabl[$i];?>" placeholder="" >
					<?php } else { ?>
					<input class="form-control" name="<?php echo $table[$i];?>"  type="<?php echo $typ_tabl[$i];?>" placeholder="<?php echo $hold[$i];?>" value="<?php echo $sdam[$table[$i]];?>" <?php echo $hidden[$i];?> >



				<?php
					}
			}
			$i++;}
			 if($tabl == 'matiere'){
				 $reqa='SELECT * FROM classe_matiere INNER JOIN matiere ON matiere.id_matiere = classe_matiere.id_matiere WHERE matiere.id_matiere = '.$sdam["id_matiere"];
									$o=0;
									$cm  = $bdd ->query($reqa);
									while($clasm = $cm->fetch(PDO::FETCH_ASSOC))
									{
										$listC[$o] = $clasm['id_classe'];
										$o++;
									}

							?>
							<input name="id_classe" type="text" value='' hidden id="id_classe">
			<label class="col-md-2" style="margin-top:2%;">Classe : </label>
			<!--
			    <select name="< ?php echo $table[$i];?>" class="form-control select2" style="width: 100%;">-->
				<?php
				$req='SELECT * FROM classe';

									$ca  = $bdd ->query($req);
									while($clas = $ca->fetch(PDO::FETCH_ASSOC))
								{	$oi=0;
									while($oi < $o){

										if($listC[$oi] == $clas['id_classe']){
											$isSel="checked";
											$oi = $o;
										} else{$isSel="";}
										$oi++;
									}
			?>
					<input type="checkbox" id="<?php echo $clas['id_classe'];?>" data-id="<?php echo $clas['id_classe'];?>" class="filled-in check" value="<?php echo $clas['id_classe'];?>" <?php echo $isSel; ?> />
					<label class="col-md-2 " for="<?php echo $clas['id_classe'];?>"><?php echo $clas['titre_classe'];?></label>
                  <!--<option value="< ?php echo $clas['id_classe'];?>">< ?php echo $clas['titre_classe'];?></option>-->
                <?php
								}
								?>
                <!--</select>-->

		<?php
					}
		?>

		</div><div class="col-md-12 col-lg-2 row" style="">
				<input type="submit" class="btn btn-block btn-primary" value="Enregistrer" id="envoi" name="btnenv">
		  </div>
		</form>
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

	<!-- apro_admin for editor
	<script src="js/pages/editor3.js"></script>

	<!-- Dropzone Plugin JavaScript -->
    <!-- jQuery x-editable -->
    <script src="assets/vendor_components/moment/src/moment2.js"></script>
    <script type="text/javascript" src="assets/vendor_components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js"></script>
    <script type="text/javascript">
	//$('.textarea').wysihtml5();
	$('.textarea').each( function(){
		var id = $(this).attr('id');
		CKEDITOR.replace(id);

	});
		var reponseO = <?php echo json_encode($sdam); ?>;
    $(function() {
        //editables
        $('#username').editable({
            type: 'text',
            pk: 1,
            name: 'username',
            title: 'Enter username'
        });

        $('#firstname').editable({
            validate: function(value) {
                if ($.trim(value) == '') return 'This field is required';
            }
        });

        $('#sex').editable({
            prepend: "not selected",
            source: [{
                value: 1,
                text: 'Male'
            }, {
                value: 2,
                text: 'Female'
            }],
            display: function(value, sourceData) {
                var colors = {
                        "": "#98a6ad",
                        1: "#5fbeaa",
                        2: "#5d9cec"
                    },
                    elem = $.grep(sourceData, function(o) {
                        return o.value == value;
                    });

                if (elem.length) {
                    $(this).text(elem[0].text).css("color", colors[value]);
                } else {
                    $(this).empty();
                }
            }
        });

        $('#status').editable();

        $('#group').editable({
            showbuttons: false
        });

        $('#dob').editable();

        $('#comments').editable({
            showbuttons: 'bottom'
        });

        //inline


        $('#inline-username').editable({
            type: 'text',
            pk: 1,
            name: 'username',
            title: 'Enter username',
            mode: 'inline'
        });

        $('#inline-firstname').editable({
            validate: function(value) {
                if ($.trim(value) == '') return 'This field is required';
            },
            mode: 'inline'
        });

        $('#inline-sex').editable({
            prepend: "not selected",
            mode: 'inline',
            source: [{
                value: 1,
                text: 'Male'
            }, {
                value: 2,
                text: 'Female'
            }],
            display: function(value, sourceData) {
                var colors = {
                        "": "#98a6ad",
                        1: "#5fbeaa",
                        2: "#5d9cec"
                    },
                    elem = $.grep(sourceData, function(o) {
                        return o.value == value;
                    });

                if (elem.length) {
                    $(this).text(elem[0].text).css("color", colors[value]);
                } else {
                    $(this).empty();
                }
            }
        });

        $('#inline-status').editable({
            mode: 'inline'
        });

        $('#inline-group').editable({
            showbuttons: false,
            mode: 'inline'
        });

        $('#inline-dob').editable({
            mode: 'inline'
        });

        $('#inline-comments').editable({
            showbuttons: 'bottom',
            mode: 'inline'
        });

    });
	$('.check').on('click', function(){
		var lClas = $('#id_classe').val();
		var clas = '';
		if($(this).attr('checked')){
				$(this).removeAttr('checked');
			} else {
				$(this).attr('checked','checked')
			}
		$('.check').each( function(){
			if($(this).attr('checked')){
				clas += $(this).attr('data-id')+',';
			}
		});
		//$('#id_classe').val(clas);
		console.log(clas);
		document.getElementById("id_classe").value = clas;
	});
    </script>
	<script>
	/* $('#accept').submit(function(e){

		var emailConn = $("#emailConn").val();
var mdpConn = $("#mdpConn").val();
		if (emailConn !== '', mdpConn !== '') {
		e.preventDefault();

	$.ajax({
url : 'http://localhost:80/aproadmin-light/gestionbdd.php',
type : 'POST',
data : 'emailConn=' + emailConn + '&mdpConn=' + mdpConn + '&nom_fct='+2,
dataType : 'text',
success : function(reponse, statut){
		if (reponse !== "failed") {

	$('#player-bar').addClass('navbar-fixed-bottom');

		$('#nom1').html(reponse[0]);

	//console.log(reponse);
},
error : function(reponse, statut, erreur){

console.log(reponse);

}
}
	});
}
	});
 */
	$('#form_ajou input').each( function(){

          var nam = $(this).attr('name');
          if (nam == "table"){

          } else if (nam == "nom_fct"){

		  } else if (nam == "btnenv"){

		  } else {
            $(this).val(reponseO[nam]);
          }
        });
		$('#form_ajou select').each( function(index){

          var nam = $(this).attr('name');
          var divprec = $(this).prev();

          $('#demo-form2 select:eq('+index+') option').each( function(){
            var sel = $(this).attr('value');

            if(sel == reponseO[nam]){
              console.log(divprec.children('a').text());
              $(this).attr('selected', 'selected');
              divprec.children('a').text($(this).text());

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
index.php?t=1");
}
?>
</html>
