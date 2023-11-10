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

  <style>
        /* Add your styles for modal overlay and content here */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            margin: auto;
        }
        /* Style the edit button as an icon */
        .edit-icon-button {
            background: none;
            border: none;
            color: #007bff; /* Edit icon color */
            cursor: pointer;
            font-size: 16px;
        }

        /* Add more styles as needed */
    </style>
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


  </header><!-- End Header -->
  <?php include "inc/sidebar.php" ?>
  <main id="main" class="main">
    <div class="pagetitle">
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) { ?>
      <div class=""><h1 class="card-title">Welcome <?=$_SESSION['name']?></h1></div>
    <?php } ?>
      <h2>Your Highscores</h2>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php">Home</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
    <?php
include 'inc/db.php';

if (isset($_SESSION['userid'])){



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT coursename, min(score), diff FROM courses inner join highscores on courses.courseid = highscores.courseid where highscores.userid = ".$_SESSION['userid']." group by coursename, diff order by coursename";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $groupedCourses = array();
  // output data of each row
  while($row = $result->fetch_assoc()) {
    if ($row["diff"] == "0"){
      $diffname ="Easy";
    } else {
      $diffname = "Hard";
    }
    $coursename = $row['coursename'];
    $score = $row['min(score)'];
    if (!isset($groupedCourses[$coursename])) {
      $groupedCourses[$coursename] = array();
    }
    $groupedCourses[$coursename][] = array(
      'difficulty' => $diffname,
      'min_score' => $score,
    );
  }
  foreach ($groupedCourses as $coursename => $courseDetails) {
    $coursenameimg = str_replace(' ', '', $coursename);
    $coursenameimg = str_replace(',', '', $coursenameimg);
    ?>
            <!-- Sales Card -->
            <div class="col-xxl-2 col-md-3 col-sm-2">
            <div class="card info-card revenue-card"  style="height: 150px">
                <div class="card-body"><img src="img/<?=$coursenameimg?>.png" style="float:right" alt="<?=$coursename?>" width="75" class="courseimg">
                 <!-- <div class="filter"><button class="edit-icon-button" onclick="openModal()"><i class="bi-pencil-square"></i> </button></div>-->
                    <h5 class="card-title" style="height:70px"><?=$coursename. "<br>"; ?></span></h5>
                    
                    <?php foreach ($courseDetails as $courseDetail) { ?>
                      <span><?=$courseDetail['difficulty']?> <?=$courseDetail["min_score"]?></span><br>
                    <?php } ?>
                </div>
            </div>
            </div><!-- End Sales Card -->
<?php   }} ?>
    </div>
</div>
<?php $conn->close();  
} else { ?>

          <div class="col-xxl-12 col-md-12">
            <div class="card info-card revenue-card"  style="height: 150px">
              <div class="card-body">  
                <h5 class="card-title">Log in to see your High Scores</h5>
              </div>
            </div>
          </div>
<?php } ?>
            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                
<?php 
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT diff, username, score, scoredate, coursename FROM highscores as hs left JOIN courses as c ON hs.courseid = c.courseid left JOIN users as u ON hs.userid = u.userid order by scoredate desc limit 20;";
$result = $conn->query($sql);
?>
                <div class="card-body">
                  <h5 class="card-title">Recent Scores</h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">Player</th>
                        <th scope="col">Course</th>
                        <th scope="col">Score</th>
                        <th scope="col">Date</th>
                      </tr>
                    </thead>
                    <tbody>
<?php 
if ($result->num_rows > 0) {
   while($row = $result->fetch_assoc()) { 
    if ($row['diff'] == 0){
          $diff = "Easy";
    }else{
          $diff = "Hard";
    }
    ?>
                      <tr>
                        <td><?=$row['username']?></td>
                        <td><?=$row['coursename']." ".$diff ?></td>
                        <td><?=$row['score']?></td>
                        <td><?=date("d-m-Y",strtotime($row['scoredate']))?></td>
                      </tr>
<?php }} ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div><!-- End Recent Sales -->
          </div>
        </div><!-- End Left side columns -->
      </div>
    </section>
  </main><!-- End #main -->
<!-- The modal overlay and content 
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                
                <h2 class="card-title">Edit Score</h2>
                <form action="process_edit.php" method="post">
                  <div class="form-group" style="padding-bottom:5px">
                    <input type="number" id="EasyeditedScore" name="editedScore" required class="form-control" size="5">
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>                    
                  </div>
                  <div class="form-group" style="padding-bottom:5px">
                    <input type="number" id="EasyeditedScore" name="editedScore" required class="form-control" size="5">
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                  <div class="form-group">
                    <button onclick="closeModal()" class="btn btn-secondary">Close</button>
                  </div>
                </form>  
              </div>
            </div>     
          </div>
        </div>
       </div>
    </div>
  </div>
</div>
  -->
  <!-- ======= Footer ======= -->
  <?php include "inc/footer.php" ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script>
    // JavaScript functions to handle modal
    function openModal() {
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }
  </script>

  <!-- Vendor JS Files -->
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