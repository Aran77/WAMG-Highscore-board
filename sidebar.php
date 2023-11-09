<!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-grid"></i>
          <span>Home</span>
        </a>
      </li><!-- End Dashboard Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="highscores.php">
          <i class="bi bi-list-stars"></i>
          <span>High Scores</span>
        </a>
      </li><!-- End Profile Page Nav -->   
      <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) { ?>
        <li class="nav-item">
        <a class="nav-link collapsed" href="submitscore.php">
          <i class="bi bi-pencil-square"></i>
          <span>Submit New Score</span>
        </a>
      </li><!-- End Profile Page Nav -->   
      <li class="nav-item">
        <a class="nav-link collapsed" href="logout.php">
          <i class="bi bi-box-arrow-in-left"></i>
          <span>Log Out</span>
        </a>
      </li><!-- End Profile Page Nav -->  
     <?php }?>
      <?php if (!isset($_SESSION['loggedin'])) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="register.php">
          <i class="bi bi-card-list"></i>
          <span>Register</span>
        </a>
      </li><!-- End Register Page Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="login.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li>
    <?php } ?>
      <!-- End Login Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->