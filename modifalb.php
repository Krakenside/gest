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
    
     if(isset(($_GET['ex']))&&($_GET['ex']==1)){
        // var_dump($_GET['ex']);
        ?>
        <script> alert("son deja existant!");  </script>
        
    <?php }
    // else{
    //     $_GET['ex'] = 0;
    // }
    if(isset($_GET['t'])){
        ?>
       <script> alert(" Son(s) Ajoutés avec succes ! ");</script>
    <?php };
   
    $reqart = $bdd->query("SELECT * FROM artiste  ");
    // $reqart->execute();

    // if(isset());

    // var_dump($reqAlb->fetchAll());

    // $nbr_sons =  $_GET['nbr'];
    // // var_dump($_GET["nm"]);

    // $nref_al = $_GET["nm"];
    // // var_dump($nref_al);
    // $req_al = "SELECT * FROM `album` WHERE album.`titre_album`=:ref";
    // $st = $bdd->prepare($req_al);
    // $st->execute(
    //     array(
    //         'ref' => $nref_al

    //     )
    // );
    // //  var_dump($ex->fetch());
    // $res = $st->fetch();
    // var_dump($res);



?>


    <!DOCTYPE html>

    <html lang="fr">





    <head>

        <meta http-equiv="Content-Type" content="text/html" charset="utf-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">

        <meta name="author" content="">

        <link rel="icon" href="images/favicon.ico">



        <title>afreekaplay Admin - Ajouter <?php   ?> </title>



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



    </head>



    <body class="hold-transition sidebar-mini skin-purple-light">

        <div class="wrapper">



            <?php include("header.php");

            include("menu.php");

            $indx = 0;

            ?>



            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- Content Header (Page header) -->

                <section class="content-header">

                    <h1>

                        Ajouter les sons pour l'album :

                    </h1>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="acceuil.php"><i class="fa fa-dashboard"></i> Acceuil</a></li>



                        <li class="breadcrumb-item active">Ajout</li>

                    </ol>

                </section>



                <!-- Main content -->
                <section class="content">


                    <div class="form-group">
                        <?php if (!(isset($_GET["artiste"]))) { ?>
                            <form action="modifalb.php" method="get">
                                <label style="margin-top:15px;">Artiste</label>

                                <select name="artiste" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">

                                    <?php
                                    $i = 0;
                                    while ($elt2 = $reqart->fetch()) {
                                        // foreach ($reqart->fetchAll() as $elt2) { <?php echo $elt2["nom_artiste"]  
                                    ?>
                                        <option value="<?php echo $elt2["id_artiste"] . " " . $elt2["nom_artiste"] ?>"><?php echo $elt2["nom_artiste"];  ?></option>
                                    <?php
                                        $i++;
                                    } ?>



                                </select>


                    </div>
                    <div class="col-md-12 col-lg-2 row" style="margin: top 15px;">

                        <input type="submit" class="btn btn-block btn-primary" id="envoi" value="Valider">

                    </div>
                    </form>
                <?php } ?>

                <?php if (isset($_GET["artiste"]) && !(isset($_GET['nbr_sons']))) {
                    $vr_art = explode(" ", $_GET["artiste"]);
                    // var_dump($vr_art);
                ?>
                    <form action="modifalb.php" method="get">
                        <div class="form-group">

                            <label style="margin-top:15px;">Artiste</label>
                            <input type="text" name="artiste" class="form-control" value="<?php echo $vr_art[1];  ?>" >
                            <input type="hidden" value="<?php echo $vr_art[0];  ?>">

                            <label style="margin-top:15px;">Titre de l'album</label>
                            <select name="id_album" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">

                                <?php
                                $reqAlb = $bdd->prepare("select * from album where album.id_artiste = :id_art");
                                $reqAlb->execute(array(
                                    'id_art' => $_GET["artiste"]
                                ));
                                // var_dump($reqAlb);
                                foreach ($reqAlb->fetchAll() as $elt) {
                                ?>
                                    <option value="<?php echo $elt["id_album"] . '*' . $elt["titre_album"]  ?>"><?php echo $elt["titre_album"]  ?></option>
                                <?php } ?>



                            </select>
                            <input type="hidden" name="infos" value="<?php echo $vr_art[0]  ?>">
                            <label style="margin-top:15px;">Nombre de sons a ajouter</label>


                            <input type="number" name="nbr_sons" class="form-control number" style="width: 100%;">


                        </div>
                        <div class="col-md-12 col-lg-2 row" style="">

                            <input type="submit" class="btn btn-block btn-primary" id="envoi" value="Enregistrer">

                        </div>
                    </form>
                <?php } ?>


                <?php

                if (isset($_GET['nbr_sons'])) {
                    // var_dump()
                    $inf = explode('*', $_GET["id_album"]);
                    $vr_art = explode(" ", $_GET["artiste"]);
                    $nbr_sons = $_GET['nbr_sons'];
                    // $inf = explode(' ', $_GET['infos']);
                    var_dump($inf);
                    $reqAlb2 = "SELECT *  FROM album WHERE album.id_album = :idalb";
                    $stAl = $bdd->prepare($reqAlb2);
                    $stAl->execute(
                        array(
                            'idalb' => $inf[0]
                        )
                    );
                    $resAl = $stAl->fetch();
                    $reqPrice = 'SELECT son.prix_son FROM son WHERE son.id_album =:idalb ';
                    $stPrice = $bdd->prepare($reqPrice);
                    $stPrice->execute(
                        array(
                            'idalb' => $inf[0]
                        )
                    );
                    $resP = $stPrice->fetch();
                    // var_dump($resP);
                    // var_dump($inf[0]);
                    // var_dump($stPrice);
                    
                     $price_song = (is_bool($resP ) || $resP['prix_son']==NULL ) ? 200 : $resP['prix_son'];
                    
                   $dt_enr = date("Y-m-d H:i:s");
                    // var_dump($dt_enr);
                    // var_dump( $price_song);
                ?>

                    <form action="trtbnk.php" method="post" class="" id="form_ajou" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="hidden" name="fct" value="5">
                            <input type="hidden" name="nbr" value="<?php echo $nbr_sons ?>">
                            <input type="hidden" name="id_alb" value="<?php echo $inf[0]; ?>">
                            <input type="hidden" name="cover" value="<?php echo $resAl["cover_album"]; ?>">
                            <input type="hidden" name="id_art" value="<?php echo $resAl["id_artiste"]; ?>">
                            <input type="hidden" name="id_genre" value="<?php echo $resAl["id_genre"]; ?>">
                            <input type="hidden" name="prx_son" value="<?php echo $price_song; ?>">
                            <input name="date_verif" type="hidden"  value"<?php echo $dt_enr ; ?>" hidden>
                            <input type="hidden" name="tbl" value="ajson" >


                            <label style="margin-top:15px;">Artiste</label>
                            <input type="text" name="artiste" class="form-control" value="<?php echo $_GET['artiste'];  ?>" disabled>
                            <input type="hidden" value="<?php echo $inf[0];  ?>">
                            <label style="margin-top:15px;">Titre de l'album</label>
                            <input type="text" name="album" class="form-control" value="<?php echo $inf[1];  ?>" disabled>

                            <?php

                            for ($i = 0; $i < $nbr_sons; $i++) {
                            ?>
                                <label style="margin-top:15px;">Titre du son n° <?php echo $i + 1 ?></label>
                                <input class="form-control" name="titre_son<?php echo $i ?>" type="text" placeholder="Titre" required>
                                <label style="margin-top:15px;">Ficher du son</label>
                                <input class="form-control" name="fichier_son<?php echo $i ?>" type="file" placeholder="Fichier" required>
                                <!--<label style="margin-top:15px;">Prix du son</label>-->
                                <input class="form-control" name="prx_son<?php echo $i ?>" type="text" placeholder="prix du son" value="<?php echo $price_song  ?>" hidden>
                                <!--<label style="margin-top:15px;">Statut sur le site</label>-->
                                <input class="form-control" name="is_active" type="text" placeholder="" value="1" hidden>
                                
                                
                            <?php }

                            ?>
<label style="margin-top:15px;">Date d’affichage sur le site:</label>
<div class="input-group">

                  <div class="input-group-addon">

                    <i class="fa fa-calendar"></i>

                  </div>

                  <input name="date_srt" type="text" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask="">

                </div>

                        </div>
                        <div class="col-md-12 col-lg-2 row" style="">

                            <input type="submit" class="btn btn-block btn-primary" id="envoi" value="Enregistrer">

                        </div>
                    </form>
                <?php   } ?>



                </section>

                <!-- /.content -->

            </div>

            <!-- /.content-wrapper -->



            <?php

            include("footer.php");

            ?>

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

                $('.textarea').each(function() {

                    var id = $(this).attr('id');

                    CKEDITOR.replace(id);



                });

                /* var editor = new Quill('.textarea', {

                    modules: { toolbar: [

                    ['bold', 'italic', 'underline', 'strike'],

                    ['link'],

                    ['blockquote'],

                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],

                    [{ 'script': 'sub'}, { 'script': 'super' }],

                    ['align', { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }]

                ] },

                    theme: 'snow'

                  }); */

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

                $('.check').on('click', function() {

                    var lClas = $('#id_classe').val();

                    var clas = '';

                    if ($(this).attr('checked')) {

                        $(this).removeAttr('checked');

                    } else {

                        $(this).attr('checked', 'checked')

                    }

                    $('.check').each(function() {

                        if ($(this).attr('checked')) {

                            clas += $(this).attr('data-id') + ',';

                        }

                    });

                    //$('#id_classe').val(clas);

                    console.log(clas);

                    document.getElementById("id_classe").value = clas;

                });

                $('#fichsout').on('change', function() {

                    if ($(this).val() == 2) {

                        $('#fichier_soutCommande').removeAttr('hidden');

                    } else {

                        $('#fichier_soutCommande').attr('hidden', 'hidden');



                    }

                });

                $('#type_soutCommande').on('change', function() {

                    console.log($(this).val());

                    if ($(this).val() !== '0') {

                        $('#pre').css('display', 'block');

                    } else {

                        $('#pre').css('display', 'none');



                    }



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
            </script>

    </body>



<?php


} else {



    header("location: index.php?t=1");
}

?>