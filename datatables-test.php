
              <?php

require 'connexion.php';
include 'test.php';
$reqjoin = 'SELECT nom_transaction,telephone_transaction,date_transaction,montant_transaction,statut_transaction,nom_pays 
FROM pays,transaction 
WHERE pays.id_pays = transaction.id_pays
AND pays.id_pays = :pays 
AND transaction.date_transaction BETWEEN :dt AND CURDATE() 
ORDER BY date_transaction desc';
             
             if (isset($_GET['dest2'])) {

              $u = 0;
              $resjoin = $bdd->prepare($reqjoin);
              $resjoin->execute(array(
                'pays' => $ch,
                'dt' => $dtjr
              ));
      
            ?>
      
      
              <div class="row">
      
      
      
              <div class="box box-info">
              <div class="box-header with-border">
      
                          
                  <div >
                    
                    <br/>
      
      
      
      
                    <table class="table table-striped table-bordered" id="myTable">
                      <thead>
                        <tr>
                          <th>Pays</th>
                          <th>Noms et Prenoms</th>
                          <th>Telephone</th>
                          <th>Date </th>
                          <th>Montant</th>
      
                          <th>Statut</th>
                      </thead>
                      <tbody>
      
                        <?php
                        if (isset($ch)) {
                          // $us  = $bdd->query($req);
                          while ($usr = $resjoin->fetch()) {
      
                        ?>
                            <tr>
                              <td><?php echo $usr['nom_pays']; ?></td>
                              <td><a href="#"><?php echo $usr['nom_transaction']; ?></a></td>
                              <td><?php echo $usr['telephone_transaction']; ?></td>
                              <td><?php echo $usr['date_transaction']; ?></td>
                              <td><?php echo $usr['montant_transaction']; ?></td>
      
                              <td>
                                <?php echo $usr['statut_transaction']; ?>
                              </td>
                            </tr>
                        <?php
                            $u++;
                          }
                        }
                        ?>
      
                      </tbody>
                    </table>
      
      
      
      
      
                  </div>
<?php  }
              ?>
             