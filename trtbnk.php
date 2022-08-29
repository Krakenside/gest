<?php


require 'connexion.php';



// if ($connect == "1" and $_SESSION["userCompte"] == 'ADMINAFP20') // Si le visiteur s'est identifié.

// {

session_start();

require 'connexion.php';

$affichel = [];

if (isset($_GET['fct']) && ($_GET['fct'] == "1")) {

    // var_dump($_GET['fct']);

    $affichel = [];

    // require 'connexion.php';

    $tabinfo =  array();

    if (isset($_GET['natart'])) {

        // echo  $_POST['natart'];

        //recuperons tous les artistes pour la nationalité selectionnée 

        $reqss = 'SELECT * FROM artiste where artiste.nationalite_artiste= :nat';

        $reqsst = $bdd->prepare($reqss);

        $reqsst->execute(array(

            'nat' => $_GET['natart']

        ));

        $elt = $reqsst->fetchAll();

        // var_dump($elt);

        $i = 0;

        $affichel[][] = '';

        foreach ($elt as $key) {

            # selectioner l'artiste concerné dans la table banque 

            $reqartbnk = "SELECT * FROM banque WHERE banque.Nom_artiste_banque =:nmart ";

            $req2st = $bdd->prepare($reqartbnk);

            $req2st->execute(

                array(

                    'nmart' => $key['nom_artiste']

                )

            );

            $res = $req2st->fetch();

            #var_dump($res);

            if ($res) {



                $affichel[$i][0] = $res['Nom_artiste_banque'];

                $affichel[$i][1] = $res['Montant_disponible_banque'];

                $affichel[$i][2] = $res['Montant_deja_reglé_banque'];

                $affichel[$i][3] = "<a href='ajoutartbnk.php?chart=" . $res['reference_banque'] . "' class='btn btn-success' >Payer</a>";

                $affichel[$i][4] = $res['reference_banque'];
            }



            // $affichel[$i][0]= $res['Nom_artiste_banque'];

            // $affichel[$i][2]= $res['Montant_disponible_banque'];

            // $affichel[$i][3]= $res['Montant_deja_reglé_banque'];

            //$affichel[$i][4]= "<a  href='detailsart.php?indx=" . $indx . "&chM=" . $_GET["idMsn"] . "' class='btn btn-success' >Details</a>";





            // var_dump( $req2st->fetchAll());

            $i++;
        }

        // $i++;





    }

    $affich_e = array_shift($affichel);

    $_SESSION['infos'] = $affichel;



    echo  json_encode($affichel);
} else if (isset($_GET['fct']) && ($_GET['fct'] == "2")) {



    //mise a jour des infos 

    if (isset($_GET['apy'])) {

        $mtnpay = $_GET['apy'];

        $artbn = $_GET['idart'];

        // determiner l'artiste concerné 

        $reqsel  = "SELECT * FROM banque where banque.reference_banque= '" . $artbn . "' ";

        $rqst = $bdd->query($reqsel);

        $res3 = $rqst->fetch();

        var_dump($res3);

        //mise a jour 





    }
} else if (isset($_GET['fct']) && ($_GET['fct'] == "3")) {

    // echo'aaaa';

    if (isset($_GET['error'])) {

        $error = $_GET['error'];

        // var_dump($error);

        $payment = (int) $_GET['apy'];

        // var_dump($payment);

        $val = (int)$_GET['limite'];



        if ($payment > $val) {

            $error = 'true';

            // var_dump($val);

            // echo 'Error please ';

            header('ajoutartbnk.php?');

            if (isset($_SERVER["HTTP_REFERER"])) {

                header("Location: " . $_SERVER["HTTP_REFERER"]);
            }
        } else {

            // $id = 1;

            $payment = (int) $_GET['apy'];

            // var_dump($payment);

            $val = (int)$_GET['limite'];

            $nm = $_GET['nmart'];

            // var_dump($id);







            $reqsle = 'SELECT * FROM   banque WHERE Nom_artiste_banque = :nm';

            $st1 = $bdd->prepare($reqsle);

            $st1->execute(array(

                'nm' => $nm

            ));

            $dt = $st1->fetch();

            // var_dump($dt);

            // var_dump($dt['value1']);

            // var_dump($dt['Montant_disponible_banque']);

            $sm =  $dt['Montant_disponible_banque'];

            $mtdj = $dt['Montant_deja_reglé_banque'] + $payment;

            // var_dump($mtdj);



            $nval = ($sm - $payment);

            // var_dump($sm);

            // echo '<br>';

            // var_dump($payment);

            // var_dump($nval);

            $data = [

                'nvmt' => $nval,

                'mtr' => $mtdj,

                'id' => $dt['reference_banque']

            ];

            //mise a jour banque 

            $updt = 'UPDATE banque SET Montant_disponible_banque= :nvmt,Montant_deja_reglé_banque= :mtr where reference_banque=:id ';

            $stmt = $bdd->prepare($updt);

            $stmt->execute($data);

            $stmt->closeCursor();

            // metre a jour la table paiment 

            $dt3 = [

                'id_art' => $dt['reference_banque'],

                'mtn_pay' => $payment,

                'dt_pay' => date('Y-m-d H:i:s')

            ];

            $rqpay = 'INSERT INTO paiement (id_artiste_paiment,montant_paiement,date_paiment) VALUES (:id_art,:mtn_pay,:dt_pay)';

            $stmt3 = $bdd->prepare($rqpay);

            $stmt3->execute($dt3);

            $stmt3->closeCursor();

            // echo 'réussi';

            header("location: ajoutartbnk.php");
        }

        // if(isset($_GET['']))

        // echo'Error please';

    }
} else if (isset($_GET['fct']) && ($_GET['fct'] == "4")) {



    $art = NULL;





    // var_dump($_GET['fct']);

    $affichel = [];

    // require 'connexion.php';

    $tabinfo =  array();

    if (isset($_GET['natart'])) {

        // echo  $_POST['natart'];

        //recuperons tous les artistes pour la nationalité selectionnée 

        $reqss = 'SELECT nom_artiste,id_artiste FROM artiste where artiste.nationalite_artiste= :nat';

        $reqsst = $bdd->prepare($reqss);

        $reqsst->execute(array(

            'nat' => $_GET['natart']

        ));

        $elt = $reqsst->fetchAll();

        //var_dump($elt);

        $i = 0;

        $affichel[][] = '';

        foreach ($elt as $key) {







            $affichel[$i][0] = $elt[$i]['nom_artiste'];

            // $affichel[$i][1] = $res['Montant_disponible_banque'];

            // $affichel[$i][2] = $res['Montant_deja_reglé_banque'];

            $affichel[$i][3] = "<a href='trtbnk.php?fct=4&chart=" . $elt[$i]['id_artiste'] . "' class='btn btn-success' >Details</a>";

            //$affichel[$i][4] = $res['reference_banque'];





            // $affichel[$i][0]= $res['Nom_artiste_banque'];

            // $affichel[$i][2]= $res['Montant_disponible_banque'];

            // $affichel[$i][3]= $res['Montant_deja_reglé_banque'];

            //$affichel[$i][4]= "<a  href='detailsart.php?indx=" . $indx . "&chM=" . $_GET["idMsn"] . "' class='btn btn-success' >Details</a>";





            // var_dump( $req2st->fetchAll());

            $i++;
        }

        // $i++;

        $reqsst->closeCursor();





        //var_dump($res1);

    } else  if (isset($_GET['chart'])) {

        $nbrAchsonGNF  =  0;

        $nbrAchson = 0;

        $songArr = [];

        $smAlbCfa = 0;

        $smSonCfa = 0;

        $smAlbGnf = 0;

        $smSonGnf = 0;

        $art = $_GET['chart'];

        // echo $art;



        //determinons les sons pour un artiste donné 

        $rqArt1 = "SELECT *

                    FROM son

                    INNER JOIN artiste ON artiste.id_artiste = son.id_artiste

                    WHERE artiste.id_artiste = :id ";



        $rqst1 = $bdd->prepare($rqArt1);

        $rqst1->execute(

            array(

                'id' => $art

            )

        );

        $res1 = $rqst1->fetchAll();

        $rqst1->closeCursor();

        // Point sur les sons 

        // var_dump($res1);

        // echo json_encode($res1[0]['id_son']);

        foreach ($res1 as $elmt2) {

            //  var_dump($elmt2['id_son']);

            if ($elmt2) {

                $rqSong = ' SELECT SUM(transaction.montant_transaction)

                            FROM transaction

                            INNER JOIN pays ON pays.id_pays = transaction.id_pays

                            WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2020-01-01%" AND "2022-12-31%"

                            AND pays.code_pays !="GN"

                            AND(transaction.libelle_transaction LIKE "son-' . $elmt2['titre_son'] . '%" AND transaction.libelle_transaction LIKE "%-' . $elmt2['id_son'] . '")';

                $rqSongcnt = ' SELECT transaction.montant_transaction,transaction.id_transaction

                            FROM transaction

                            INNER JOIN pays ON pays.id_pays = transaction.id_pays

                            WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2020-01-01%" AND "2022-12-31%"

                            AND pays.code_pays !="GN"

                            AND(transaction.libelle_transaction LIKE "son-' . $elmt2['titre_son'] . '%" AND transaction.libelle_transaction LIKE "%-' . $elmt2['id_son'] . '")';

                $rqst2 = $bdd->prepare($rqSong);

                $rqst8 = $bdd->prepare($rqSongcnt);



                // $rqst2->execute(array(

                //         'songId'=> $elmt2['id_son']

                // ));

                $rqst2->execute();

                $rqst8->execute();



                $res3 = $rqst2->fetchAll();

                $res8 = $rqst8->fetchAll();

                // var_dump($res8);

                if ($rqst8 != NULL) {

                    $nbrAchson += $rqst8->rowCount();
                }

                if ($rqst2 != NULL) {

                    $smSonCfa += $res3[0]["SUM(transaction.montant_transaction)"];
                }





                // $smSonCfa += $res3[0]['montant_transaction'];







                echo 'Sons CFA <br>';

                // echo json_encode($res3);



                // var_dump($res3);

                var_dump($smSonCfa);

                echo '<br> nbr Achat CFA <br>';

                var_dump($nbrAchson);
            }

            // echo json_encode($nbrAchson);

            // $nbrAchson = 0;

        }

        // echo '<br> Nombre de vente sons en CFA ';

        // echo json_encode($nbrAchson);

        // echo '<br> Somme Générée en CFA ';

        // echo json_encode($smSonCfa);





        //Sons GNF



        foreach ($res1 as $elmt2) {

            //  var_dump($elmt2['id_son']);

            if ($elmt2) {

                $rqSongGNF = 'SELECT SUM(transaction.montant_transaction)

                            FROM transaction

                            INNER JOIN pays ON pays.id_pays = transaction.id_pays

                            WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2020-01-01%" AND "2022-12-31%"

                            AND pays.code_pays ="GN"

                             AND(transaction.libelle_transaction LIKE "son-' . $elmt2['titre_son'] . '%" AND transaction.libelle_transaction LIKE "%-' . $elmt2['id_son'] . '")';

                $rqSongGNFcnt = 'SELECT transaction.montant_transaction,transaction.id_transaction

                              FROM transaction

                              INNER JOIN pays ON pays.id_pays = transaction.id_pays

                              WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2020-01-01%" AND "2022-12-31%"

                              AND pays.code_pays ="GN"

                               AND(transaction.libelle_transaction LIKE "son-' . $elmt2['titre_son'] . '%" AND transaction.libelle_transaction LIKE "%-' . $elmt2['id_son'] . '")';

                $rqst6 = $bdd->prepare($rqSongGNF);

                $rqstcnt = $bdd->prepare($rqSongGNFcnt);



                // $rqst2->execute(array(

                //         'songId'=> $elmt2['id_son']

                // ));

                $rqst6->execute();

                $rqstcnt->execute();

                $res6 = $rqst6->fetchAll();

                $rescnt1 = $rqstcnt->fetchAll();

                // var_dump( $rescnt1);

                if ($rqstcnt != NULL) {

                    $nbrAchsonGNF += $rqstcnt->rowCount();
                }

                if ($rqst6 != NULL) {

                    $smSonGnf += $res6[0]["SUM(transaction.montant_transaction)"];
                }



                // $smSonGnf += $res6['montant_transaction'];

                // echo 'Sons CFA';

                // echo json_encode($res6);

            }

            // echo json_encode($nbrAchson);

            // $nbrAchson = 0;

        }

        echo '<br> Nombre de ventes Sons en GNF ';

        echo json_encode($nbrAchsonGNF);

        echo '<br> Somme Générée en GNF ';

        echo json_encode($smSonGnf);



        //determinons  les albums pour cet artiste



        $nbAlbAch = 0;

        $nbAlbAchGNF = 0;

        $nbAlb = 0;

        $reqAlbAll = " SELECT *

                        FROM album

                        INNER JOIN artiste ON artiste.id_artiste = album.id_artiste

                        WHERE artiste.id_artiste = :id_art";

        $rqst3 = $bdd->prepare($reqAlbAll);

        $rqst3->execute(array(

            'id_art' => $art

        ));

        $res4 = $rqst3->fetchAll();

        // $nbAlb = $rqst3->count();

        $rqst3->closeCursor();



        // var_dump($res4);

        //determinons les transactions sur les albums en CFA

        foreach ($res4 as $elmt3) {

            //   var_dump($elmt3['titre_album']);

            if ($elmt3) {

                $rqAlbCFA = 'SELECT SUM(transaction.montant_transaction)

            FROM transaction

            INNER JOIN pays ON pays.id_pays = transaction.id_pays

            WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2020-01-01%" AND "2022-12-31%"

            AND pays.code_pays !="GN"

             AND(transaction.libelle_transaction LIKE "album-' . $elmt3['titre_album'] . '%" AND transaction.libelle_transaction LIKE "%-' . $elmt3['id_album'] . '")';

                $rqst4 = $bdd->prepare($rqAlbCFA);

                // $rqst2->execute(array(

                //         'songId'=> $elmt2['id_son']

                // ));

                $rqAlbCFAcnt = 'SELECT transaction.montant_transaction

                FROM transaction

                INNER JOIN pays ON pays.id_pays = transaction.id_pays

                WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2020-01-01%" AND "2022-12-31%"

                AND pays.code_pays !="GN"

                 AND(transaction.libelle_transaction LIKE "album-' . $elmt3['titre_album'] . '%" AND transaction.libelle_transaction LIKE "%-' . $elmt3['id_album'] . '")';

                $rqst7 = $bdd->prepare($rqAlbCFAcnt);

                $rqst4->execute();

                $rqst7->execute();

                $resALbTrx = $rqst4->fetchAll();

                if ($resALbTrx != NULL) {

                    $smAlbCfa += $resALbTrx[0]["SUM(transaction.montant_transaction)"];
                }

                if ($rqst7 != NULL) {

                    $nbAlbAch += $rqst7->rowCount();
                }



                //nbrTrxAlb





                // echo $rqst4->rowCount();

                // echo json_encode($resALbTrx);

                // var_dump($elmt3);

                // var_dump($nbAlbAch);

                // var_dump($resALbTrx);

            }
        }

        echo '<br> Nombre de ventes album en CFA';

        echo json_encode($nbAlbAch);

        echo '<br> Somme Générée Albums en CFA';

        echo json_encode($smAlbCfa);









        //determinons les transactions sur les albums en GNF

        foreach ($res4 as $elmt3) {

            //   var_dump($elmt3['titre_album']);

            if ($elmt3) {

                $rqAlbGN = 'SELECT SUM(transaction.montant_transaction)

                        FROM transaction

                        INNER JOIN pays ON pays.id_pays = transaction.id_pays

                        WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2020-01-01%" AND "2022-12-31%"

                        AND pays.code_pays ="GN"

                        AND(transaction.libelle_transaction LIKE "album-' . $elmt3['titre_album'] . '%" AND transaction.libelle_transaction LIKE "%-' . $elmt3['id_album'] . '")';

                $rqst5 = $bdd->prepare($rqAlbGN);

                // $rqst2->execute(array(

                //         'songId'=> $elmt2['id_son']

                // ));



                $rqAlbGNcnt = 'SELECT transaction.montant_transaction

                        FROM transaction

                        INNER JOIN pays ON pays.id_pays = transaction.id_pays

                        WHERE transaction.statut_transaction = "SUCCESS" AND date_transaction BETWEEN "2020-01-01%" AND "2022-12-31%"

                        AND pays.code_pays ="GN"

                        AND(transaction.libelle_transaction LIKE "album-' . $elmt3['titre_album'] . '%" AND transaction.libelle_transaction LIKE "%-' . $elmt3['id_album'] . '")';

                $rqst9 = $bdd->prepare($rqAlbGNcnt);

                $rqst5->execute();

                $rqst9->execute();

                $resALbTrxGNF = $rqst5->fetchAll();

                if ($resALbTrxGNF != NULL) {

                    $smAlbGnf += $resALbTrxGNF[0]["SUM(transaction.montant_transaction)"];
                }

                if ($rqst9 != NULL) {

                    $nbAlbAchGNF += $rqst9->rowCount();
                }



                // $smAlbGnf += $resALbTrxGNF[0]["SUM(transaction.montant_transaction)"];

                //nbrTrxAlb

                // echo $rqst4->rowCount();

                echo 'Trx GNF';

                // var_dump($resALbTrxGNF['0']["montant_transaction"]) ;

                //    var_dump($resALbTrxGNF[0]["SUM(transaction.montant_transaction)"]);

                // echo json_encode($resALbTrxGNF);

            }
        }

        echo '<br> Nombre de ventes Album  en GNF';

        echo json_encode($nbAlbAchGNF);

        echo '<br> Somme Générée Album GNF';

        echo json_encode($smAlbGnf);





        // $nbAlbAch = 0;



        // echo ' <br>ALBUMS' ;

        // echo json_encode($res4);





    }





    $affich_e = array_shift($affichel);

    // $_SESSION['infos'] = $affichel;

    echo json_encode($affichel, JSON_PRETTY_PRINT);

    // if (isset($_POST['id_art'])) {

    //     $art = $_POST['id_art'];

    // }



} else if (isset($_POST['fct']) && ($_POST['fct'] == "5")) {

    function genererChaineAleatoire()



    {



        $listeCar = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';



        $listeNum = '0123456789';



        $chaine = '';



        $max = mb_strlen($listeCar, '8bit') - 1;



        $max2 = mb_strlen($listeNum, '8bit') - 1;



        for ($i = 0; $i < 4; $i++) {



            $chaine .= $listeCar[random_int(0, $max)];
        }



        $chaine .= '-';



        for ($u = 0; $u < 4; $u++) {



            $chaine .= $listeNum[random_int(0, $max2)];
        }



        return $chaine;
    }



    // recuperons l'album

    $id_album = $_POST["id_alb"];

    // $prx = $_POST["prx_son"];
    // var_dump($_GET["pr"]);
    // var_dump($_POST["prx_son"]);

    $order = $_POST["nbr"];



    for ($i = 0; $i < $_POST["nbr"]; $i++) {



        //var_dump($_POST["titre_son".$i.""]);

        //generer la ref 

        // var_dump($_POST["prx_son"]);

        // var_dump($_POST);

        $chaine = genererChaineAleatoire();

        $ref_son = 'AFPs' . $chaine;

        $titre = $_POST["titre_son" . $i . ""];

        $fichier_son = $_POST["titre_son" . $i . ""] . ".mp3";

        $cover = $_POST["cover"];

        $fl = basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"]));

        $fichier_son =  $fl;

        // $prix_son = isset($_POST["prx_son" . $i . ""]) ? $_POST["prx_son" . $i . ""] : $prx;
        $prix_son = $_POST["prx_son"];
        // var_dump($prix_son);

        $fichier_son = $fl;

        $duree = 0;

        $url = "https://afreekaplay.com/son/" . $ref_son . ".mp3";

        $lien_son = str_replace(".mp3", " ", basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"])));

        $id_art = $_POST["id_art"];

        $id_genre = $_POST["id_genre"];

        $is_active = 1;

        $dt_srt = $_POST["date_srt"];

        // $dt_srt = $_POST["date_verif"];

        $dt_ver = date("Y-m-d H:i:s");

        $dt_enr = date("Y-m-d H:i:s");

        // echo $i.'<br>';

        // var_dump($ref_son);

        // var_dump($titre);

        //  var_dump($fichier_son);

        //  var_dump($cover);

        //  var_dump($_POST["prx_son" . $i . ""]);
         

        //  var_dump($url);

        // var_dump($id_genre);

        // // var_dump()

        // var_dump($id_art);

        // var_dump($id_genre);

        // var_dump($lien_son);

        // var_dump($dt_srt);

        // var_dump($dt_ver);

        // var_dump($dt_enr);

        // verifier si le son n'existe pas deja 

        $filename = 'upload_sons_log.txt';

        $reqex = "select * from son where son.titre_son =:titre";

        $st = $bdd->prepare($reqex);

        $st->execute(array('titre' => $titre));

        $resex = $st->fetch();

        if ($resex != NULL) {

            // //   echo 'identique';

            // var_dump($st);

            $content  = date("Y-m-d H:i:s") . ' le son ' . $titre . ' existe deja  ' . PHP_EOL;



            file_put_contents($filename, $content, FILE_APPEND);

            header('location:modifalb.php?ex=1');
        } else {

            //     //verifier l'integrité du fichier 



            $target_path = '../../file/' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]['name']));



            $target_path_2 = '../site/file/' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]['name']));



            $allowedExts = array("mp3", "mp4", "wma");

            $extension = pathinfo($_FILES["fichier_son" . $i . ""]['name'], PATHINFO_EXTENSION);

            // var_dump($extension);



            if ((($_FILES["fichier_son" . $i . ""]["type"] == "audio/mpeg")

                    || ($_FILES["fichier_son" . $i . ""]["type"] == "audio/mp3")

                    || ($_FILES["fichier_son" . $i . ""]["type"] == "audio/wma") || ($_FILES["fichier_son" . $i . ""]["type"] == "audio/mpga") || ($_FILES["fichier_son" . $i . ""]["type"] == "audio/mpeg")

                )



                && ($_FILES["fichier_son" . $i . ""]["size"] < 700000000000)

                && in_array($extension, $allowedExts)

            ) {

                if ($_FILES["fichier_son" . $i . ""]["error"] > 0) {

                    echo "Un erreur est survenu Code: " . $_FILES["fichier_son" . $i . ""]["error"] . "<br />";
                } else {

                    // echo "Upload: " . $_FILES["fichier_son" . $i . ""]["name"] . "<br />";

                    // echo "Type: " . $_FILES["fichier_son" . $i . ""]["type"] . "<br />";

                    // echo "Size: " . ($_FILES["fichier_son" . $i . ""]["size"] / 1024) . " Kb<br />";

                    // echo "Temp file: " . $_FILES["fichier_son" . $i . ""]["tmp_name"] . "<br />";



                    if (file_exists($target_path . $_FILES["fichier_son" . $i . ""]["name"])) {

                        echo $_FILES["fichier_son" . $i . ""]["name"] . " fichier deja existant. ";
                    } else {



                        move_uploaded_file(

                            $_FILES["fichier_son" . $i . ""]["tmp_name"],

                            '../../file/' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"]))

                        );

                        $target_path = '../../file/' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"]));

                        $target_path_2 = '../site/file/' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"]));

                        copy($target_path, $target_path_2);

                        move_uploaded_file(

                            $_FILES["fichier_son" . $i . ""]["tmp_name"],

                            '../site/file' . basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"]))

                        );

                        // move_uploaded_file(

                        //     $_FILES["fichier_son" . $i . ""]["tmp_name"],

                        //     '../site/file/'.basename(str_replace(" ", "_", $_FILES["fichier_son" . $i . ""]["name"])));

                        //insertion.    

                        $reqins = "INSERT INTO son(reference_son,titre_son,fichier_son,cover_son,prix_son,duree_son,url_son,dateSortie_son,visible_son,lien_son,id_album,id_artiste,id_genre,is_active,date_verif,dte_enr_son) VALUES(:ref,:titre,:fichier,:cover,:prix,:duree,:url_son,:dt_srt,:vis,:lien,:id_alb,:id_art,:id_genre,:activ,:dt_ver,:dt_en)";

                        $stins = $bdd->prepare($reqins);

                        $stins->execute(

                            array(

                                'ref' => $ref_son,

                                'titre' => $titre,

                                'fichier' => $fichier_son,

                                'cover' => $cover,

                                'prix' => $prix_son,

                                'duree' => $duree,

                                'url_son' => $url,

                                'dt_srt' => $dt_srt,

                                'vis' => 1,

                                'lien' => $titre,

                                'id_alb' => $id_album,

                                'id_art' => $id_art,

                                'id_genre' => $id_genre,

                                'activ' => 1,

                                'dt_ver' => $dt_ver,

                                'dt_en' => $dt_enr



                            )

                        );

                        $stins->closeCursor();

                        //   echo "Stored in: " . $target_path. $_FILES["fichier_son" . $i . ""]["name"];



                        header("location: modifalb.php?t=album&ex=0");
                        // $dt = date('Y-m-d h-m-s');

                        $content  = date("Y-m-d H:i:s") . 'le son ' . $titre . ' a été uploadé avec success ' . PHP_EOL;

                        file_put_contents($filename, $content, FILE_APPEND);
                    }
                }
            } else {

                echo "Mauvais format de fichier. <br> Revenez en arrière et reesayer avec un fichier de type mp3,mpeg,wma.";

                var_dump($_FILES["fichier_son" . $i . ""]["type"]);
            }
        }
    }
}
