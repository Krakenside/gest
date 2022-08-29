<?php

try{
              //$bdd = new PDO ('mysql:host=localhost; dbname=afreekaplay;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));
              $bdd =    new PDO ('mysql:host=localhost; dbname=afrekply;charset=utf8', 'RuT', 'projetafricain', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => false));
		}
           catch (Exception $e) {
             //$errorCod = $e->errorCode();
             //echo $e->errorInfo[0];
             //echo $e->errorInfo[1];
          /*   //if ( $e->errorInfo[0] == 42000 AND $e->errorInfo[1] == 1226) {
               try{
                            $bdd = new PDO ('mysql:host=localhost; dbname=afrekply;charset=utf8', 'afp', 'projetafricain', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));
               		}
                          catch (Exception $e) {
                            //$errorCod = $e->geterrorInfo();
                          //  if ( $e->errorInfo[0] == 42000 AND $e->errorInfo[1] == 1226) {
                              try{
                                            $bdd = new PDO ('mysql:host=localhost; dbname=afrekply;charset=utf8', 'afpl', 'projetafricain', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true));
                              		}
                                         catch (Exception $e) {
                                           // $errorCod = $e->errorInfo();
                                           // if ( $errorCod[0] == 42000 AND $errorCod[1] == 1226) {
                                           //
                                           // }
                                         echo "Erreur de connexion à la base de données. <br />";
                                         die( 'Erreur : '.$e->getmessage());

                                         }
                          //  }
                          //echo "Erreur de connexion à la base de données. <br />";
                          //die( 'Erreur : '.$e->getmessage());

                          }
             //} */
           //echo "Erreur de connexion à la base de données. <br />";
           //die( 'Erreur : '.$e->getmessage());

           }



		   ?>
