<?php
// Connect to DB

if (!isset($_SESSION['id'])) {
    header("../index.php");
    exit();
}

include '../conn.php';

$user_id = $_SESSION['id'];
$batch_result = mysqli_query($conn, "SELECT batch_no FROM user_data WHERE id = $user_id");
$user = mysqli_fetch_assoc($batch_result);
$batch_no = $user['batch_no'];


?>

<div class="exam-box">
  <h5>📅 Upcoming Exams :</h5>

  <?php
  
 $today = date('Y-m-d');
$query = "SELECT * FROM exam_update WHERE date >= '$today' AND batch = '$batch_no' ORDER BY date ASC";

  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) == 0) {
    echo "<div class='p-3 text-center text-muted'>No upcoming exams.</div>";
  } else {
    while ($row = mysqli_fetch_assoc($result)) {
      $dateNum = date('d', strtotime($row['date']));
      $monthText = strtoupper(date('F', strtotime($row['date'])));
      $course = htmlspecialchars($row['course_name']);

      echo "
        <div class='exam-entry'>
          <div class='date-box'>
            <span>$dateNum</span>
            <small>$monthText</small>
          </div>
          <div class='course-name'>$course</div>
        </div>
      ";
    }
  }
  ?>


        <?php
     if ($_SESSION['id'] == 18):
    ?>



         <div class="schedule-footer-buttons">
  <button class="btn btn-edit" onclick="window.location.href='Exam_Updates_edit.php'">Edit</button>
  <button class="btn btn-update" onclick="window.location.href='Exam_Updates.php'">Update</button>
</div>


    <?php endif; ?>

 

</div>


