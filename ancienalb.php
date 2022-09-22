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



    require 'connexionA.php';

    //$indx = $_GET["indx"];

    if (isset($_GET["etr"])) {

        $indx = $_GET["etr"];
    }

    $tabl = $_GET["t"];

    //$tabl = "textew";

    /*$req='SELECT * FROM sitew where id_site='.$indx;

	   $si  = $bdd ->query($req);

		$sit = $si->fetch(PDO::FETCH_ASSOC);*/



    //recuper les artistes 



    $rqartst = $bdd->query("SELECT * FROM artiste ");

    $rqgen = $bdd->query("SELECT * FROM genre ");







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



    <title>afreekaplay Admin - Ajouter album </title>



    <script type="text/javascript" src='jquery-3.4.1.min.js'></script>

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

    <link href="assets/vendor_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css"
        rel="stylesheet" />

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

    <style type="text/css">
    #preview img {

        margin: 5px;

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

                    Ajouter Album

                </h1>

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="acceuil.php"><i class="fa fa-dashboard"></i> Acceuil</a></li>



                    <li class="breadcrumb-item active">Ajout</li>

                </ol>

            </section>

            <form method='POST' action='ajaxfile.php' enctype="multipart/form-data" id="fr">



                <label style="margin-top:15px;">Titre de l'album</label>

                <input class="form-control" name="titre_album" type="text" placeholder="" id="titre_album">
                <input class="form-control" name="titre_album2" type="text" placeholder="" id="titre_album2" hidden>

                <label style="margin-top:15px;">Fichier de l'album</label>
                <!-- <input type="text" id="nmfich" value="" hidden> -->
                <!-- (A) UPLOAD BUTTON & FILE LIST -->
                <input type="button" id="pickfiles" value="Upload" />
                <div id="filelist"></div>
              

                <label style="margin-top:15px;">Couverture de l'album</label>

                <input class="form-control" name="cover_album" type="file" id="cover_album">

                <label style="margin-top:15px;">Prix de l'album</label>

                <input class="form-control" name="prix_album" type="text" placeholder="" id="prix_album">
                <label style="margin-top:15px;">Nombre de sons de l'album </label>

                <input class="form-control" name="Nbr_sons_alb" type="text" placeholder="" id="prix_album">

                <label style="margin-top:15px;">Date de sortie:</label>

                <div class="input-group">

                    <div class="input-group-addon">

                        <i class="fa fa-calendar"></i>

                    </div>

                    <input name="dateSortie_album" type="text" class="form-control"
                        data-inputmask="'alias': 'yyyy-mm-dd'" data-mask="">

                </div>

                <label style="margin-top:15px;">Artiste</label>

                <select name="id_artiste" class="form-control select2 select2-hidden-accessible" id="lart"
                    style="width: 100%;" tabindex="-1" aria-hidden="true">

                    <option value="" selected="">Aucun</option>

                    <?php

                        foreach ($rqartst->fetchAll() as $elmt) {

                        ?>

                    <option value="<?php echo $elmt["id_artiste"] ?> " id="artiste_album">

                        <?php echo $elmt["nom_artiste"] ?>

                    </option>



                    <?php    }

                        ?>

                </select>

                <label style="margin-top:15px;">Genre</label>

                <select name="id_genre" class="form-control select2 lgenre select2-hidden-accessible"
                    style="width: 100%;" tabindex="-1" aria-hidden="true">

                    <?php foreach ($rqgen->fetchAll() as $elmt2) { ?>

                    <option value="<?php echo $elmt2["id_genre"]  ?>"><?php echo $elmt2["titre_genre"] ?>

                    </option>

                    <?php

                        } ?>









                </select>

                <label style="margin-top:15px;">Statut sur le site</label>

                <input class="form-control" name="is_active" type="text" placeholder="" id="visible">

                <label style="margin-top:15px;">Date d’affichage sur le site:</label>

                <div class="input-group">

                    <div class="input-group-addon">

                        <i class="fa fa-calendar"></i>

                    </div>

                    <input name="date_verif" type="text" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'"
                        data-mask="" id="dt_srt_alb">

                </div>



                <label>Somme minimum</label>

                <input class="form-control col-md-12" name="minim_soutCommande" type="text"
                    placeholder="Somme minimum du soutient">

                <label style="margin-top:1%">Fichier de soutient</label>

                <select name="fichier_soutCommande" class="form-control select " id="fichsout" style="width: 100%;"
                    required="">

                    <option value="0">Aucun</option>

                    <option value="1">Fichier de l'album</option>

                    <option value="2">Autre fichier</option>

                </select>

                <h3>Precommande</h3>

                <div class="form-control-feedback"><small>L'album sera disponible a la date de sortie mentionnée plus

                        haut</small></div>

                <label>Type de precommande</label>

                <select name="type_soutCommande" class="form-control select " id="type_soutCommande"
                    style="width: 100%;" required="">

                    <option value="0">Sans</option>

                    <option value="1">Sans lecture</option>

                    <option value="2">Avec lecture</option>

                </select>

        </div>

        <!-- <input type="button" id="submit" value='Valider'> -->

        <input type="submit" class="btn btn-block btn-primary" id="submit" value="Enregistrer">

        </form>



    </div>

    <!-- Preview -->

    <div id='preview'></div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.3/plupload.full.min.js"></script>
    <script>
    // (C) INITIALIZE UPLOADER
    window.addEventListener("load", () => {
        // (C1) GET HTML FILE LIST
        var filelist = document.getElementById("filelist");
        var frm = document.getElementById("titre_album2");

        // (C2) INIT PLUPLOAD
        var uploader = new plupload.Uploader({
            runtimes: "html5",
            browse_button: "pickfiles",
            url: "upload.php",
            chunk_size: "10mb",
            filters: {
                max_file_size: "150mb",
                mime_types: [{
                    title: "Image files",
                    extensions: "jpg,gif,zip"
                }]
            },
            init: {
                PostInit: () => {
                    filelist.innerHTML = "<div>pret</div>";
                },
                FilesAdded: (up, files) => {
                    plupload.each(files, (file) => {
                        let row = document.createElement("div");
                        row.id = file.id;
                        row.innerHTML =
                            `${file.name} (${plupload.formatSize(file.size)}) <strong></strong>`;
                        filelist.appendChild(row);
                        // let row2 = document.createElement("input");
                        // row2.id = "test";
                        // row2.value =
                        //     `${file.name} `;
                        //     frm.appendChild(row2);
                        frm.value = `${file.name} `;

                    });
                    uploader.start();
                },
                UploadProgress: (up, file) => {
                    document.querySelector(`#${file.id} strong`).innerHTML = `${file.percent}%`;
                },
                Error: (up, err) => {
                    console.error(err);
                }
            }
        });
        uploader.init();
    });
    </script>

    
<script type="text/javascript">
            $(document).ready(function() {



                $('#submit').click(function() {



                    var form_data = new FormData();



                    // Read selected files

                    var totalfiles = document.getElementById('files').files.length;

                    var titre = $('#titre_album').val();

                    var prix_album = $("#prix_album").val();

                    var visible = $('#is_visible').val();

                    var dt_srt = $('#dt_srt_alb').val();

                    var genre_alb = $("#genre_alb").val();

                    var artiste_alb = $("#artiste_album").val();



                    console.log(titre);

                    for (var index = 0; index < totalfiles; index++) {

                        // form_data.append("files[]", document.getElementById('files').files[

                        //     index]);

                        form_data.append("cover_album", document.getElementById('cover_album').files[

                            index]);



                    }



                    form_data.append("titre_album", titre);

                    form_data.append("prix_alb", prix_album);

                    form_data.append("visible", visible);

                    form_data.append("dt_srt_alb", dt_srt);

                    form_data.append("genre_alb", genre_alb);

                    form_data.append("artiste_album", artiste_alb);

                    console.log(form_data);



                    // AJAX request

                    // $.ajax({

                    //     url: 'https://www.afreekaplay.com/gest/ajaxfile.php',

                    //     type: 'post',

                    //     data: form_data,

                    //     dataType: 'json',

                    //     contentType: false,

                    //     processData: false,

                    //     success: function(response) {



                    //         for (var index = 0; index < response.length; index++) {

                    //             var src = response[index];



                    //             // Add img element in <div id='preview'>

                    //             $('#preview').append('<img src="' + src +

                    //                 '" width="200px;" height="200px">');

                    //         }



                    //     }

                    // });

                });

            });
        </script>


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

    <script type="text/javascript"
        src="assets/vendor_components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js"></script>

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



    $('#table').on('change', function() {

        var val = $(this).val();

        if (val == 'son') {

            $("#son").toggleClass('hide', false);

            $("#album").toggleClass('hide', true);

        } else {

            $("#son").toggleClass('hide', true);

            $("#album").toggleClass('hide', false);

        }

    });



    $(document).ready(function() {

        $("body").on('click', 'li.select2-results__option', function() {

            // $('body').on('change', '#select2-lalb-container', function(){

            console.log($("#larts").select2('val'));

            $("#lalb .dalb").removeAttr('disabled');

            $("#lalb").find('option.dalb[data-art!=' + $("#larts").select2('val') + ']').each(

                function() {

                    $(this).attr('disabled', 'disabled');

                    // var valopt = $(this).val();

                    // $("#lalb").select2({

                    //     templateResult: function(option, container) {

                    //       // console.log('log');

                    //         if ($(option.element).val() == valopt){

                    //           $(container).css("display","none");

                    //           console.log($(container));

                    //         }

                    //

                    //         return option.text;

                    //     }

                    // });



                });

            // $("#lalb").select2('destroy');

            $("#lalb").select2();





            // });

            // console.log(talb);select2-results__option





        });

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



</html>