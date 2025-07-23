<?php
session_start();
include '../conn.php';

$teacher_id = $_SESSION['id'] ?? 0;

// ✅ Delete schedule
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM teacher_class_schedule WHERE id = $delete_id AND teacher_id = $teacher_id");
    echo "<script>alert('Schedule Deleted Successfully'); window.location.href='world.php';</script>";
    exit;
}

// ✅ Update schedules
if (isset($_POST['update_schedule'])) {
    $ids = $_POST['schedule_id'];
    $dates = $_POST['date'];
    $starts = $_POST['start_time'];
    $ends = $_POST['end_time'];
    $venues = $_POST['venue'];

    foreach ($ids as $i => $id) {
        $id = intval($id);
        $date = mysqli_real_escape_string($conn, $dates[$i]);
        $start = mysqli_real_escape_string($conn, $starts[$i]);
        $end = mysqli_real_escape_string($conn, $ends[$i]);
        $venue = mysqli_real_escape_string($conn, $venues[$i]);

        $sql = "UPDATE teacher_class_schedule SET 
                date = '$date', start_time = '$start', end_time = '$end', venue = '$venue' 
                WHERE id = $id AND teacher_id = $teacher_id";
        mysqli_query($conn, $sql);
    }

    echo "<script>alert('Schedules Updated Successfully'); window.location.href='world.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Class Schedule</title>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f8fafc;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 1000px;
      margin-top: 40px;
    }
    .card {
      border-radius: 14px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }
    .card-header {
      background: #f59e0b;
      color: white;
      font-weight: bold;
      font-size: 18px;
      border-radius: 14px 14px 0 0;
      text-align: center;
      padding: 16px 0;
    }
    .btn-success {
      background-color: #10b981;
      border: none;
    }
    .btn-danger {
      background-color: #ef4444;
      border: none;
    }
    .btn-outline-secondary:hover {
      background: #d1d5db;
    }
  </style>
</head>
<body>

<div class="container">
  <a href="javascript:history.back()" class="btn btn-outline-secondary mb-3">
    <i class="bi bi-arrow-left-circle"></i> Back
  </a>

  <div class="card">
    <div class="card-header">📝 Edit My Class Schedules</div>
    <div class="card-body">
      <form method="POST">
        <input type="hidden" name="update_schedule" value="1">
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead class="table-warning">
              <tr>
                <th>📚 Course</th>
                <th>📅 Date</th>
                <th>🕒 Start</th>
                <th>🕓 End</th>
                <th>📍 Venue</th>
                <th>🆔 ID</th>
                <th>⚙️ Action</th>
              </tr>
            </thead>
            <tbody>
              <?php

             $today = date('Y-m-d');
$schedules = mysqli_query($conn, "SELECT * FROM teacher_class_schedule WHERE teacher_id = '$teacher_id' AND date = '$today' ORDER BY start_time ASC");


              if (mysqli_num_rows($schedules) > 0):
                  while ($row = mysqli_fetch_assoc($schedules)):
              ?>
              <tr>
                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                <td><input type="date" name="date[]" class="form-control" value="<?php echo $row['date']; ?>" required></td>
                <td><input type="time" name="start_time[]" class="form-control" value="<?php echo $row['start_time']; ?>" required></td>
                <td><input type="time" name="end_time[]" class="form-control" value="<?php echo $row['end_time']; ?>" required></td>
                <td><input type="text" name="venue[]" class="form-control" value="<?php echo $row['venue']; ?>" required></td>
                <td>
                  <input type="hidden" name="schedule_id[]" value="<?php echo $row['id']; ?>">
                  <span class="text-muted"><?php echo $row['id']; ?></span>
                </td>
                <td>
                  <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this schedule?')" class="btn btn-sm btn-danger">
                    <i class="bi bi-trash"></i> Delete
                  </a>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr>
                <td colspan="7" class="text-center text-muted">No class schedules found.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <?php if (mysqli_num_rows($schedules) > 0): ?>
        <div class="text-end mt-3">
          <button type="submit" class="btn btn-success px-4">
            <i class="bi bi-check-circle"></i> Update All
          </button>
        </div>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

</body>
</html>
