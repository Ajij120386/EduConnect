<?php


if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

include '../conn.php';

$teacher_id = $_SESSION['id'];
$today = date('Y-m-d');

$query = "SELECT * FROM teacher_exam_update 
          WHERE teacher_id = '$teacher_id' AND date >= '$today' 
          ORDER BY date ASC";

$result = mysqli_query($conn, $query);
?>

<div class="exam-box">
  <h5>📅 Your Upcoming Exams:</h5>

  <?php
  if (mysqli_num_rows($result) === 0) {
    echo "<div class='p-3 text-center text-muted'>You have no upcoming exams.</div>";
  } else {
    while ($row = mysqli_fetch_assoc($result)) {
      $dateNum = date('d', strtotime($row['date']));
      $monthText = strtoupper(date('F', strtotime($row['date'])));
      $exam_name = htmlspecialchars($row['exam_name']);
      $batch = htmlspecialchars($row['batch']);

      echo "
        <div class='exam-entry'>
          <div class='date-box'>
            <span>$dateNum</span>
            <small>$monthText</small>
          </div>
          <div class='course-name'>
            <strong>$exam_name</strong><br>
            <small class='text-muted'>Batch: $batch</small>
          </div>
        </div>
      ";
    }
  }
  ?>




      
     <div class="exam-footer">
    <a href="teacher_Exam_Update.php" class="btn btn-success">Update</a>
    
  </div>
    
</div>
