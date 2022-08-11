<?php



try{

              // $bdd =    new PDO ('mysql:host=localhost; dbname=afreekaplay;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));

             $bdd =    new PDO ('mysql:host=localhost; dbname=afrkplay;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));

		}

           catch (Exception $e) {

           echo "Erreur de connexion à la base de données. <br />";

           die( 'Erreur : '.$e->getmessage());



           }







		   ?>

