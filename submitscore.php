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

  </header><!-- End Header -->

  <?php include "inc/sidebar.php" ?>
  <?php
include 'inc/db.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $err="";
    $score = 999;
    $course = explode("-",$_POST['courseid']);
    $courseid = $course[0];
    $coursename = $course[1];
    $newscore = trim($_POST['score']);
    $diff = $_POST['diff'];
    if ($diff == 0){ 
      $diffname = "Easy";
    } else {
      $diffname = "Hard";
    }
    $userid = $_SESSION['userid'];
    $conn = new mysqli($servername, $username, $password, $dbname);

    $stmt = $conn->prepare("SELECT IFNULL((SELECT min(score) FROM highscores where userid = ? and courseid = ? and diff = ? limit 1), 'NONE')");
    $stmt->bind_param('iii', $userid, $courseid, $diff);
    $stmt->execute();
    $stmt->bind_result($score);
    $stmt->fetch(); 
    $stmt->close();
    echo "<h1>".$score ."-".$newscore ."</h1>";
    if ($score == "NONE" or $score = 999) {  
        $stmtInsert = $conn->prepare('INSERT INTO highscores (userid, courseid, diff, score) values (?, ?, ?, ?)');
        $stmtInsert->bind_param('iiii', $userid, $courseid, $diff, $newscore);
        if (!$stmtInsert->execute()){
          die('Error executing the query: ' . $stmt->error);
        }
        $last_id = mysqli_insert_id($conn);
        $stmtInsert->close();
        $conn->close();
    } else {
        if ($score > $newscore) {
            $stmtUpdate = $conn->prepare("UPDATE highscores set score = ? where userid = ? and courseid = ? and diff = ?");
            $stmtUpdate->bind_param('iiii', $newscore, $userid, $courseid, $diff);
            $stmtUpdate->execute();
            $stmtUpdate->close();
            $conn->close();
        } else {
            $err = "You Have Already Submitted an Equal or Better Score";
        }
    }
    

    ?>
      <main id="main" class="main">
      <h1>Submit Score</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Submit Scores</li>
          </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">
              <?php if (strlen($err) > 0){ 
                  echo $err ."</h5><div class='col-sm-6'><br><br><a href='submitscore.php'>Submit a New Score</a></div>";
                } else {
                  echo "Score Submitted!</h5>"; ?>
                <div class="row mb-3">
                  <div class="col-sm-12"><?=$coursename?> - <?=$diffname?></div>
                  <div class="col-sm-12">Score  <?=$_POST['score']?></div>
                  <div class="col-sm-12"><br><br><a href="submitscore.php">Submit a New Score</a></div>
                </div>  
                <?php }                   
                ?>  

<?php } else {
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT coursename, courseid FROM courses";
$result = $conn->query($sql);

?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Submit Score</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Submit Scores</li>
          
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Submit Scores</h5>

              <!-- Horizontal Form -->
              <form action="submitscore.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-6">
                  <label for="Course" class="col-sm-2 col-form-label">Course Name</label>
                  <div class="col-sm-6">
                    <select name="courseid" class="form-select">
                        <?php
                        if ($result->num_rows > 0) {
                            // output data of each row
                            while($row = $result->fetch_assoc()) {          
                                echo "<option value='".$row['courseid']."-".$row["coursename"]."'>". $row["coursename"]. "</option>";
                            }            
                        }
                        ?>
                        </select>
                  </div>
                </div>

                
                <fieldset class="row mb-6">
                  <legend class="col-form-label col-sm-2 pt-0">Course Difficulty</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="diff" id="gridRadios1" value="0" checked>
                      <label class="form-check-label" for="gridRadios1">
                        Easy
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="diff" id="gridRadios2" value="1">
                      <label class="form-check-label" for="gridRadios2">
                        Hard
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-6">
                  <label for="score" class="col-sm-2 col-form-label">Score</label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="score" name="score" size="3">
                  </div>
                </div>
              <!--  <div class="row mb-6">
                  <label for="score" class="col-sm-2 col-form-label">Verification Screenshot</label>
                  <div class="col-sm-6">
                   <input type="file" name="image" id="fileToUpload" class="form-control">
                  </div>
                </div>-->
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Horizontal Form -->
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End #main -->
  <!-- ======= Footer ======= -->
  <?php include "inc/footer.php" ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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