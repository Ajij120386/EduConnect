<?php
session_start();
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'], $_POST['course'])) {
    $dates = $_POST['date'];
    $courses = $_POST['course'];

    foreach ($dates as $i => $exam_date) {
        $exam_name = mysqli_real_escape_string($conn, $courses[$i]);
        if (!empty($exam_date) && !empty($exam_name)) {
            $date = mysqli_real_escape_string($conn, $exam_date);
            $batch = mysqli_real_escape_string($conn, $_POST['batch']);

           $teacher_id = $_SESSION['id'];
mysqli_query($conn, "INSERT INTO teacher_exam_update (teacher_id, exam_name, date, batch) VALUES ('$teacher_id', '$exam_name', '$date', '$batch')");

        }
    }

    echo "<script>alert('Teacher Exams Added Successfully!'); window.location.href='teacher_world.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Update Exams</title>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e0f2ff, #f8fafc);
      font-family: 'Segoe UI', sans-serif;
    }
    .exam-form-card {
      max-width: 750px;
      margin: 60px auto;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      border: 1px solid #f59e0b;
    }
    .exam-form-card .card-header {
      background: #f59e0b;
      color: white;
      font-weight: 600;
      font-size: 18px;
      text-align: center;
      padding: 16px 0;
      border-radius: 16px 16px 0 0;
    }
    .exam-form-card .card-footer {
      background: #fff8e1;
      border-top: 1px solid #fcd34d;
      border-radius: 0 0 16px 16px;
    }
    .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 0.2rem rgba(59,130,246,.2);
    }
    .btn-success {
      background: #10b981;
      border: none;
    }
    .btn-info {
      background: #0ea5e9;
      border: none;
    }
    .btn-danger {
      background: #ef4444;
      border: none;
    }
    .btn i {
      margin-right: 5px;
    }
    .row-labels {
      font-weight: bold;
      color: #334155;
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 2px solid #f97316;
    }

    .btn-outline-secondary {
  font-weight: 500;
  border-radius: 8px;
}
.btn-outline-secondary:hover {
  background-color: #cbd5e1;
  color: #1e293b;
}

  </style>
</head>
<body>

<div class="container mt-4">
  <a href="javascript:history.back()" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left-circle"></i> Back
  </a>
</div>

<div class="card exam-form-card">
  <div class="card-header">📅 Update Upcoming Exams</div>
  <form method="POST">
    <div class="card-body">
      <div class="row row-labels">
        <div class="col-md-4">Date</div>
        <div class="col-md-4">Exam Name</div>

        <div class="col-md-4">Batch No</div>
      </div>

      <div id="examFields">
        <!-- Initial Row -->
      <div class="row mb-3">
  <div class="col-md-4">
    <input type="date" name="date[]" class="form-control" required>
  </div>
  <div class="col-md-4">
    <input type="text" name="course[]" class="form-control" placeholder="Exam Name" required>
  </div>
  <div class="col-md-4">
    <select name="batch" class="form-control" required>
      <option value="">-- Select Batch --</option>
      <?php
      $batch_query = mysqli_query($conn, "SELECT DISTINCT batch_no FROM user_data WHERE batch_no IS NOT NULL AND batch_no != '' ORDER BY batch_no ASC");
      while ($batch = mysqli_fetch_assoc($batch_query)) {
        echo "<option value='{$batch['batch_no']}'>{$batch['batch_no']}</option>";
      }
      ?>
    </select>
  </div>
</div>

        
      </div>
    </div>

    

    <div class="card-footer d-flex justify-content-between">
      <div>
        <button type="submit" class="btn btn-success">
          <i class="bi bi-check-circle"></i>Update
        </button>
        <button type="button" class="btn btn-info text-white" onclick="addMoreExamRow()">
          <i class="bi bi-plus-circle"></i>Add More
        </button>
      </div>
   
    </div>
  </form>
</div>

<script>
function addMoreExamRow() {
  const row = `
    <div class="row mb-3">
  <div class="col-md-4">
    <input type="date" name="date[]" class="form-control" required>
  </div>
  <div class="col-md-4">
    <input type="text" name="course[]" class="form-control" placeholder="Course Name of Exam" required>
  </div>
  <div class="col-md-4">
    <select name="batch" class="form-control" required>
      <option value="">-- Select Batch --</option>
      <?php
      $batch_query = mysqli_query($conn, "SELECT DISTINCT batch_no FROM user_data WHERE batch_no IS NOT NULL AND batch_no != '' ORDER BY batch_no ASC");
      while ($batch = mysqli_fetch_assoc($batch_query)) {
        echo "<option value='{$batch['batch_no']}'>{$batch['batch_no']}</option>";
      }
      ?>
    </select>
  </div>
</div>
`;
  document.getElementById("examFields").insertAdjacentHTML("beforeend", row);
}
</script>

</body>
</html>
