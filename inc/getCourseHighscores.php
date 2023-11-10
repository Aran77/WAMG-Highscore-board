<?php
include "db.php";
// Get the selected course from the AJAX request
$selectedCourse = $_GET['course'];
$tmpcourseid = explode("-", $selectedCourse);
$courseid = $tmpcourseid[0];
$coursename = $tmpcourseid[1];
// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch scores for the selected course
$sql = "SELECT score, scoredate, diff, users.username FROM highscores inner join users on highscores.userid = users.userid WHERE courseid = '$courseid' group by diff, users.userid order by diff, score asc";
$result = $conn->query($sql);
$diffResults= array();
?>
<div class="row">
<?php
if ($result->num_rows > 0) {
    $ne = 1;
    $nh = 1;
    while ($row = $result->fetch_assoc()) {
        $diff = $row['diff'];
        if (!isset($diffResults[$diff])){
            
            $diffResults[$diff] =array();
        }
        $diffResults[$diff][] = $row;
    }
    foreach ($diffResults as $diff => $rows){ 
        if ($diff == 0){
            $diffname = "Easy";
        } else {
            $diffname = "Hard";
        }
        echo "<div class='col-md-6'><h5 class='card-title'>". $coursename. " - ". $diffname ."</h5>";
        echo "<table class='table datatable'><thead><tr><th scope='col'>#</th><th scope='col'>Name</th><th scope='col'>Score</th><th scope='col'>Date</th></tr></thead><tbody>";
        foreach ($rows as $row)   {
            echo "<tr><th scope='row'>";
            if ($diff == 0){
                echo $ne;
                $ne++;
            } else {
                echo $nh;
                $nh++;
            }
            echo "</th><td>".$row['username']."</td><td>".$row['score']."</td><td>".date("d-m-Y",strtotime($row['scoredate']))."</td></tr>";
        }
        echo ("</tbody></table></div>");
    }  
} else {
    echo " <section class='section'><div class='row'><div class='col-lg-12'><div class='card'><div class='card-body'><div class='col-md-6'><h5 class='card-title'><i class='bi bi-exclamation-triangle'></i>&nbsp;No scores have been logged for this course yet!</h5></div></div></div></div></div></section>";

}
$conn->close();
?></div>