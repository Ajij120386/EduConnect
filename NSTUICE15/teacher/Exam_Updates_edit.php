<?php
session_start();
include '../conn.php';

$teacher_id = $_SESSION['id'] ?? 0;

// ✅ Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM teacher_exam_update WHERE id = $delete_id AND teacher_id = $teacher_id");
    echo "<script>alert('Exam Deleted Successfully'); window.location.href='world.php';</script>";
    exit;
}

// ✅ Handle update request
if (isset($_POST['update_exams'])) {
    $dates = $_POST['date'];
    $courses = $_POST['course'];
    $batches = $_POST['batch'];
    $ids = $_POST['exam_id'];

    foreach ($ids as $i => $id) {
        $date = mysqli_real_escape_string($conn, $dates[$i]);
        $course = mysqli_real_escape_string($conn, $courses[$i]);
        $batch = mysqli_real_escape_string($conn, $batches[$i]);

        if (!empty($date) && !empty($course)) {
            $conn->query("UPDATE teacher_exam_update 
                          SET date='$date', exam_name='$course', batch='$batch' 
                          WHERE id=$id AND teacher_id=$teacher_id");
        }
    }

    echo "<script>alert('Exams Updated Successfully!'); window.location.href='world.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit My Exam Schedule</title>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e0f2ff, #f8fafc);
      font-family: 'Segoe UI', sans-serif;
    }
    .exam-form-card {
      max-width: 950px;
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
    .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 0.2rem rgba(59,130,246,.2);
    }
    .btn-success {
      background: #10b981;
      border: none;
    }
    .btn-outline-secondary {
      font-weight: 500;
      border-radius: 8px;
    }
    .btn-outline-secondary:hover {
      background-color: #cbd5e1;
      color: #1e293b;
    }
    .row-labels {
      font-weight: bold;
      color: #334155;
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 2px solid #f97316;
    }
    .action-btn {
      white-space: nowrap;
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
  <div class="card-header">✏️ Edit My Exam Schedule</div>

  <form method="POST">
    <input type="hidden" name="update_exams" value="1">
    <div class="card-body">
      <div class="row row-labels">
        <div class="col-md-2">📅 Date</div>
        <div class="col-md-3">📝 Exam Name</div>
        <div class="col-md-2">🎓 Batch</div>
        <div class="col-md-2">🆔 Exam ID</div>
        <div class="col-md-3">⚙️ Action</div>
      </div>

      <?php
      $today = date('Y-m-d');
      $exams = mysqli_query($conn, "SELECT * FROM teacher_exam_update WHERE teacher_id = '$teacher_id' AND date >= '$today' ORDER BY date ASC");

      if (mysqli_num_rows($exams) > 0):
          while ($row = mysqli_fetch_assoc($exams)):
      ?>
      <div class="row mb-3 align-items-center">
        <div class="col-md-2">
          <input type="date" name="date[]" class="form-control" value="<?php echo $row['date']; ?>" required>
        </div>
        <div class="col-md-3">
          <input type="text" name="course[]" class="form-control" value="<?php echo $row['exam_name']; ?>" required>
        </div>
        <div class="col-md-2">
          <select name="batch[]" class="form-control" required>
            <option value="">-- Select --</option>
            <?php
            $batch_query = mysqli_query($conn, "SELECT DISTINCT batch_no FROM user_data WHERE batch_no IS NOT NULL AND batch_no != '' ORDER BY batch_no ASC");
            while ($batch = mysqli_fetch_assoc($batch_query)) {
              $selected = ($batch['batch_no'] == $row['batch']) ? "selected" : "";
              echo "<option value='{$batch['batch_no']}' $selected>{$batch['batch_no']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-2">
          <input type="hidden" name="exam_id[]" value="<?php echo $row['id']; ?>">
          <input type="text" class="form-control text-muted" value="<?php echo $row['id']; ?>" readonly>
        </div>
        <div class="col-md-3 action-btn">
          <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this exam?');">
            <i class="bi bi-trash3-fill"></i> Delete
          </a>
        </div>
      </div>
      <?php endwhile; else: ?>
        <div class="alert alert-warning mt-3">No upcoming exams found for you.</div>
      <?php endif; ?>
    </div>

    <div class="card-footer d-flex justify-content-end">
      <button type="submit" class="btn btn-success">
        <i class="bi bi-pencil-square"></i> Update All
      </button>
    </div>
  </form>
</div>

</body>
</html>
