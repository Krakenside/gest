
<?php
session_start();// À placer obligatoirement avant tout code HTML.

 require 'connexion.php';

$_SESSION['connect']=0; //Initialise la variable 'connect'.

if (isset($_POST['password']) AND isset($_POST['email'])) // Si les variables existent.
{
		//On récupère les données envoyées par la méthode POST du formulaire d'identification.
        $mot_de_passe = md5(htmlentities($_POST['password'], ENT_QUOTES, "ISO-8859-1")); // le htmlentities() passera les guillemets en entités HTML, ce qui empêchera les injections SQL
        $login = htmlentities($_POST['email'], ENT_QUOTES, "ISO-8859-1");



		$ip = $_SERVER['REMOTE_ADDR'];

		//$bdd = new Cbdd('localhost', 'cmsbdd', 'root', '');
		//$bdd->connexion('localhost', 'cmsbdd', 'root', '');
		$requet = $bdd -> query('SELECT * FROM admin WHERE email_admin =\''.$login.'\' AND password_admin =\''.$mot_de_passe.'\'');

				$colon = $requet -> rowCount();
               if( $colon == 1) {

// Si le mot de passe et le login sont bons (valable pour 1 utilisateur ou plus). J'ai mis plusieurs identifiants et mots de passe.


				$_SESSION['connect']=1; // Change la valeur de la variable connect. C'est elle qui nous permettra de savoir s'il y a eu identification.
				$_SESSION['email']=$login;// Permet de récupérer le login afin de personnaliser la navigation.
				// $_SESSION['password'] = $mot_de_passe;
                $_SESSION['ip_addr'] = $ip;
				$clientI = $requet -> fetch();
				$_SESSION["user"] = $clientI["nom_admin"];
				$_SESSION["userID"] = $clientI["id_admin"];
				$_SESSION["user_mail"] = $clientI["email_admin"];
        $_SESSION["userCompte"] = 'ADMINAFP20';
				header("location: acceuil.php");

} else {
	?>

	 <script>
	 alert ('Verifiez vos informations');
	 </script>
	 <?php
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

    <title>afreekaplay Admin - Connexion </title>

	<!-- Bootstrap 4.0-->
	<link rel="stylesheet" href="assets/vendor_components/bootstrap/dist/css/bootstrap.min.css">

	<!-- Bootstrap 4.0-->
	<link rel="stylesheet" href="assets/vendor_components/bootstrap/dist/css/bootstrap-extend.css">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="assets/vendor_components/font-awesome/css/font-awesome.min.css">

	<!-- Ionicons -->
	<link rel="stylesheet" href="assets/vendor_components/Ionicons/css/ionicons.min.css">

	<!--alerts CSS -->
    <link href="assets/vendor_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">


	<!-- Theme style -->
	<link rel="stylesheet" href="css/master_style.css">

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
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php">Afreekaplay</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p id="loginMsg" class="login-box-msg"></p>

    <form action="index.php" method="post" class="form-element" novalidate>
      <div class="form-group has-feedback">
	  <div class="controls">
        <input type="email" name="email" class="form-control" placeholder="Email" required data-validation-required-message="Ce champs est obligatoire"></div>
        <span class="ion ion-email form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
	  <div class="controls">
        <input type="password" name="password" class="form-control" placeholder="Mot de Passe" required data-validation-required-message="Ce champs est obligatoire"></div>
        <span class="ion ion-locked form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="checkbox">
            <input type="checkbox" id="basic_checkbox_1" >
			<label for="basic_checkbox_1">S'en souvenir</label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-6">
         <div class="fog-pwd">
          	<a href="javascript:void(0)"><i class="ion ion-locked"></i> Pwd oublié?</a><br>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-12 text-center">
          <button type="submit" class="btn btn-block btn-flat margin-top-10 btn-primary">CONNEXION</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <div class="social-auth-links text-center">
      <p>- OU -</p>
      <a href="#" class="btn btn-social-icon btn-circle btn-facebook"><i class="fa fa-facebook"></i></a>
      <a href="#" class="btn btn-social-icon btn-circle btn-google"><i class="fa fa-google-plus"></i></a>
    </div>
    <!-- /.social-auth-links -->

    <div class="margin-top-30 text-center">
    	<p>Vous n'avez pas de compte? <a href="register.html" class="text-info m-l-5">Inscription</a></p>
    </div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

	<!-- jQuery 3 -->
	<script src="assets/vendor_components/jquery/dist/jquery.min.js"></script>

	<!-- popper -->
	<script src="assets/vendor_components/popper/dist/popper.min.js"></script>

	<!-- Bootstrap 4.0-->
	<script src="assets/vendor_components/bootstrap/dist/js/bootstrap.min.js"></script>

	<!-- Form validator JavaScript -->
    <script src="js/pages/validation.js"></script>
    <script>
    ! function(window, document, $) {
        "use strict";
        $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(), $(".skin-square input").iCheck({
            checkboxClass: "icheckbox_square-green",
            radioClass: "iradio_square-green"
        }), $(".touchspin").TouchSpin(), $(".switchBootstrap").bootstrapSwitch();
    }(window, document, jQuery);
    </script>



    <!-- Sweet-Alert  -->
    <script src="assets/vendor_components/sweetalert/sweetalert.min.js"></script>
    <script src="assets/vendor_components/sweetalert/jquery.sweet-alert.custom.js"></script>
	<?php

	if (isset($_GET['t']) AND $_GET['t'] == 1) {
		?>
		<script>

        swal("Veuillez vous connecter!");

		</script>
		<?php

	}

if (isset($_GET['t']) AND $_GET['t'] == 2) {
	$_SESSION = array();

session_destroy();
}
?>
</body>

</html>
