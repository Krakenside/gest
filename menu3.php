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
          <a href="producteur.php">
            <i class="fa fa-dashboard"></i> <span>Tableau de Bord</span>

          </a>
        </li>
		    
		    <li class="treeview">
          <a href="#">
            <i class="fa fa-id-card"></i>
            <span>Stats Artiste</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="extraliste.php?dest=Artistes">Liste</a></li>
          </ul>
        </li>
		
		    <li class="treeview">
          <a href="#">
            <i class="fa fa-database"></i>
            <span>Stats Album</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="extraliste.php?dest=Albums">Liste</a></li>
          </ul>
        </li>
		    <li class="treeview">
          <a href="#">
            <i class="fa fa-file-audio-o"></i>
            <span>Stats Sons</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="extraliste.php?dest=Sons">Liste</a></li>

          </ul>
        </li>

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
