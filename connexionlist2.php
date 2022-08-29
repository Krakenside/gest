<?php

try{
              //$bdd = new PDO ('mysql:host=localhost; dbname=afreekaplay;charset=utf8', 'root', 'roo', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));
              $bdd = new PDO ('mysql:host=localhost; dbname=afrekply;charset=utf8', 'afplistfilAAS', 'projetafricain', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => false));
		}
           catch (Exception $e) {
             //$errorCod = $e->errorCode();
             //var_dump($e);
             //$er1 = $e->errorInfo[0];
             //$er2 =  $e->errorInfo[1];
             //echo $er2;
           echo "Erreur de connexion à la base de données. <br />";
           die( 'Erreur : '.$e->getmessage());

           }


		   ?>
