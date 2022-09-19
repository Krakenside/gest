<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="image float-left">
          <img src="images/user.png" class="rounded-circle" alt="User Image">
        </div>
        <div class="info float-left" style="color:black">
          <p><?php echo $_SESSION["user"]; ?></p>
          <a href="#" style="color:black"><i class="fa fa-circle text-success"></i> Connecté</a>
        </div>
		  <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Recherche...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

        <li class="">
          <a href="acceuil.php">
            <i class="fa fa-dashboard"></i> <span>Tableau de Bord</span>

          </a>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i>
            <span>Administrateur</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=admin">Ajouter</a></li>
            <li><a href="liste.php?dest=admin">Liste</a></li>
          </ul>
        </li>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i>
            <span>Comptes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajoutusr.php?t=client">Ajouter</a></li>
            <li class="treeview">
                  <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Liste</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#">Responsable Pays</a></li><!--liste.php?dest=client&tp=1    liste.php?dest=client&tp=2 -->
                    <li><a href="#">Apporteur d'aff</a></li>
                    <li><a href="liste.php?dest=client&tp=3">Maison Prod</a></li>
                    <li><a href="liste.php?dest=client&tp=4">Artiste</a></li>
                  </ul>
              </li>
          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-home"></i>
            <span>Maison de Prod</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=maison">Ajouter</a></li>
            <li><a href="liste.php?dest=maison">Liste</a></li>
            <li><a href="ajoutusr.php?t=maison_client">Ajouter un utilisateur</a></li>
          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-id-card"></i>
            <span>Artiste</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=artiste">Ajouter</a></li>
            <li><a href="liste.php?dest=artiste">Liste</a></li>
            <li><a href="ajoutusr.php?t=artiste_client">Ajouter un utilisateur</a></li>
            <li><a href="https://afreekaplay.com/gest/ajoutartbnk.php">Ajouter un paiement</a></li>
          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-database"></i>
            <span>Genres</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=genre">Ajouter</a></li>
            <li><a href="liste.php?dest=genre">Liste</a></li>
          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-database"></i>
            <span>Album</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=album">Ajouter</a></li>
            <li><a href="albtest.php?t=album">Ajouter(test)</a></li>
            <li><a href="modifalb.php">Ajouter sons</a></li>
            <li><a href="liste.php?dest=album">Liste</a></li>
            
          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-file-audio-o"></i>
            <span>Sons</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=son">Ajouter</a></li>
            <li><a href="liste.php?dest=son">Liste</a></li>

          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-fire"></i>
            <span>Hot</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=hotson">Ajouter Hot Single</a></li>
            <li><a href="ajout.php?t=hotalbum">Ajouter Hot album</a></li>
            <li><a href="liste.php?dest=hotson">Liste</a></li>
            <li><a href="liste.php?dest=hotalbum">Liste</a></li>

          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-fire"></i>
            <span>Nouveaux</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=nouveaux">Ajouter nouveau</a></li>
            <li><a href="liste.php?dest=nouveaux">Liste</a></li>

          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-file-audio-o"></i>
            <span>Sonneries</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=sonnerie">Ajouter</a></li>
            <li><a href="liste.php?dest=sonnerie">Liste</a></li>
            <li class="treeview">
                  <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Auteur</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="ajout.php?t=auteurS">Ajouter</a></li>
                    <li><a href="liste.php?dest=auteurS">Liste</a></li>
                  </ul>
              </li>
          </ul>
        </li>
        <li class="">
          <a href="liste.php?dest=telechargement">
            <i class="fa fa-download"></i> <span>Telechargement</span>

          </a>
        </li>
		<li class="treeview">
			<a href="#">
			<i class="fa fa-file-pdf-o"></i>
			<span>Transaction</span>
			<span class="pull-right-container">
			  <i class="fa fa-angle-left pull-right"></i>
			</span>
			</a>
			<ul class="treeview-menu">
				<li><a href="ajout.php?t=transaction">Ajouter</a></li>
        <li class="treeview">
    			<a href="#">
    			<i class="fa fa-file-pdf-o"></i>
    			<span>Liste</span>
    			<span class="pull-right-container">
    			  <i class="fa fa-angle-left pull-right"></i>
    			</span>
    			</a>
    			<ul class="treeview-menu">
            <li><a href="statpays.php">Journalier</a></li>
    				<li><a href="liste.php?dest=transaction">General</a></li>
    				<li><a href="liste.php?dest=transaction&stat=success">Succes</a></li>
    				<li><a href="liste.php?dest=transaction&stat=attente">En attente</a></li>
    				<li><a href="liste.php?dest=transaction&stat=echec">Echec</a></li>
    			</ul>
    			<li><a href="ajoutartbnk.php">Payer un artiste</a></li>


    		</li>
			</ul>
		</li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-group"></i>
            <span>Utilisateurs</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=user">Ajouter</a></li>
            <li><a href="liste.php?dest=user">Liste</a></li>
            <li><a href="ajoutusr.php?t=client">Lier utilisat/maison/artiste</a></li>
          </ul>
        </li>
		<li class="treeview">
          <a href="#">
            <i class="fa fa-group"></i>
            <span>Pays</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=pays">Ajouter</a></li>
            <li><a href="liste.php?dest=pays">Liste</a></li>
          </ul>
        </li>
		</li>
    <li class="treeview">
          <a href="#">
            <i class="fa fa-file-audio-o"></i>
            <span>Devises</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajout.php?t=devise">Ajouter</a></li>
            <li><a href="liste.php?dest=devise">Liste</a></li>
            <li class="treeview">
                  <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Taux</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="ajout.php?t=taux">Ajouter</a></li>
                    <li><a href="liste.php?dest=taux">Liste</a></li>
                  </ul>
              </li>
          </ul>
        </li>
		<!--li class="treeview">
          <a href="#">
            <i class="fa fa-bars"></i>
            <span>Playlist</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ajoutpl.php?t=playlist">Ajouter</a></li>
            <li><a href="liste.php?dest=playlist">Liste</a></li>
          </ul>
        </li>
        <li class="">
          <a href="liste.php?dest=userplaylist">
            <i class="fa fa-bars"></i> <span>Playlist Utilisateur</span>

          </a>
        </li>
        <li class="">
          <a href="liste.php?dest=userplaylist">
            <i class="fa fa-bars"></i> <span>Pays</span>

          </a>
        </li-->

      </ul>
    </section>
    <!-- /.sidebar -->
    <div class="sidebar-footer">
		<!-- item-->
		<a href="#" class="link" data-toggle="tooltip" title="" data-original-title="Parametres"><i class="fa fa-cog fa-spin"></i></a>
		<!-- item-->
		<a href="#" class="link" data-toggle="tooltip" title="" data-original-title="Email"><i class="fa fa-envelope"></i></a>
		<!-- item-->
		<a href="index.php?t=2" class="link" data-toggle="tooltip" title="" data-original-title="Deconnexion"><i class="fa fa-power-off"></i></a>
	</div>
  </aside>
