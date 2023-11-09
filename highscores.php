<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Walk About Mini Golf Highscores</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon-16x16.png">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">WAMG High Scores</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->
  </header><!-- End Header -->
  <!-- ======= Sidebar ======= -->
  <?php include "inc/sidebar.php" ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Course Highscores</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Highscores</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<?php
  include "inc/db.php";
    // Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT coursename, courseid FROM courses";
$result = $conn->query($sql);
?>
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Select a Course</h5>
              <select name="courseSelect" id="courseSelect">
                <option>Choose a Course</option>
              <?php if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {  ?>
                <option value="<?=$row['courseid']."-".$row['coursename']?>"><?=$row['coursename']?></option>
              <?php }} ?>
              </select>
              </div>
          </div>

        </div>
      </div>
    </section>

    <div id="scoreDisplay">

    </div>


  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="credits">
      <div class="credits">
      Designed & Built by Aran77<br>HAIL TO THE ALE<br><img src="assets/img/htta.png" alt="HTTA"> 
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      console.log("Executing");
      const el = document.getElementById('courseSelect');
      console.log(el)
      if(el){
      el.addEventListener('change', function() {
          var selectedCourse = this.value;
          console.log("Selected Course: " + selectedCourse);
          
          // Make an AJAX request to the server-side PHP script
          var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  document.getElementById('scoreDisplay').innerHTML = xhr.responseText;
              }
          };

          xhr.open('GET', 'inc/getCourseHighscores.php?course=' + selectedCourse, true);
          xhr.send();
      });}
    });
</script>
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>