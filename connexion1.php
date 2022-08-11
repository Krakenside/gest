<?php

try{
              $bdd =    new PDO ('mysql:host=localhost; dbname=afreekaplay', 'afreekaplay', '*_VF_G9-e*Dq', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            //  $bdd =    new PDO ('mysql:host=localhost; dbname=afrekply', 'RuT', 'projetafricain', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
           catch (Exception $e) {
           echo "Erreur de connexion à la base de données. <br />";
           die( 'Erreur : '.$e->getmessage());

           }



		   ?>
