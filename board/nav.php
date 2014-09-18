<header>
  <nav class="navbar navbar-default" role="navigation">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Team Nitro</a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <form class="navbar-form navbar-left" role="search" action="search.php" method="GET">
          <div class="input-group">
            <input type="text" name="search_terms" class="form-control" placeholder="Search">
            <div class="input-group-btn"> <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button></div>
          </div>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li data-toggle="tooltip" data-placement="bottom" title="Topic List"><a href="../board/topiclist.php"><span class="glyphicon glyphicon-list fa-fw desktop-only"></span><span class="mobile-only">Topic List</span></a></li>
          <li data-toggle="tooltip" data-placement="bottom" title="User List"><a href="../board/userlist.php"><i class="fa fa-users fa-fw desktop-only"></i><span class="mobile-only">User List</span></a></li>
          <li class="mobile-only"><a href="#">User Panel</a></li>
          <div class="btn-group navbar-btn navbar-right desktop-only">
      <a id="username" class="btn btn-default desktop-only" href="#"><i class="fa fa-user fa-fw"></i><?php global $self; echo $self->name; ?></a>
      <a class="btn btn-default dropdown-toggle desktop-only" data-toggle="dropdown" href="#">
        <span class="fa fa-caret-down"></span></a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="#"><i class="fa fa-envelope fa-fw"></i> Messages</a></li>
        <li class="divider"></li>
        <li><a href="#"><i class="fa fa-pencil fa-fw"></i> Edit Profile</a></li>
        <li><a href="#"><i class="fa fa-cog fa-fw"></i> Site Settings</a></li>
        <li class="divider"></li>
        <li><a href="../logout.php"><i class="fa fa-sign-out fa-fw"></i> Log Out </a></li>
      </ul>
       </div>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
</header>