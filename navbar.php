  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
        <img src="images/user/<?php echo (file_exists("images/user/" . strtolower(substr($_SESSION['hryS_name'], 0, 1)) . ".png")) ? strtolower(substr($_SESSION['hryS_name'], 0, 1)) : 'def' ?>.png" style="width:30px; box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.75);" class="img-circle" />
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <img src="images/user/<?php echo (file_exists("images/user/" . strtolower(substr($_SESSION['hryS_name'], 0, 1)) . ".png")) ? strtolower(substr($_SESSION['hryS_name'], 0, 1)) : 'def' ?>.png" style="width:25%; box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.75); margin-left: 100px; margin-top: 10px;" class="img-circle" />
          <span class="dropdown-item text-center">
            <p><b><?php echo $_SESSION['hryS_name']; ?></b></p>
            <p><?php echo $_SESSION['hryS_user_type']; ?></p>
          </span>
          <span class="d-flex justify-content-between">
            <a href="profile_new.php" class="btn btn-sm btn-info w-100 m-2">Profile</a>
            <a href="modify_password.php" class="btn btn-sm btn-warning w-100 m-2">Change Password</a>
          </span>
          <span class="d-flex justify-content-center">
            <a href="logout.php" class="btn btn-sm btn-danger w-100 m-2" style="width:cover;">Log Out</a>
          </span>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->