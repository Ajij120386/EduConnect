<?php


if (!isset($_SESSION['id'])) {
    header("../index.php");
    exit();
}

date_default_timezone_set("Asia/Dhaka");
include '../conn.php';

$class_date = date('Y-m-d');
$user_id = $_SESSION['id'];
$user_result = $conn->query("SELECT batch_no FROM user_data WHERE id = $user_id");
$user_data = $user_result->fetch_assoc();
$batch_no = $user_data['batch_no'];

$classQuery = $conn->query("SELECT * FROM class_update WHERE date = '$class_date' AND batch = '$batch_no'");

?>

<div class="schedule-card">
    <div class="schedule-header">📅 Classes of <?php echo date('d-M-Y'); ?> :</div>
    <div class="white-gap"></div>
    <table>
        <thead>
            <tr>
                <th>Course</th>
                <th>From</th>
                <th>To</th>
                <th>At</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if ($classQuery->num_rows > 0) {
        while ($row = $classQuery->fetch_assoc()) {
            echo "<tr>
                <td>{$row['course_name']}</td>
                <td>" . date('H:i', strtotime($row['start_time'])) . "</td>
                <td>" . date('H:i', strtotime($row['end_time'])) . "</td>
                <td>{$row['venue']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4' style='text-align:center; color: #777;'>📭 No class updates today.</td></tr>";
    }
    ?>
</tbody>

    </table>

     <?php
     if ($_SESSION['id'] == 18):
    ?>

   <div class="schedule-footer-buttons">
  <button class="btn btn-edit" onclick="window.location.href='class_schedule_edit.php'">Edit</button>
  <button class="btn btn-update" onclick="window.location.href='class_schedule.php'">Update</button>
</div>


    <?php endif; ?>

     
    
</div>
