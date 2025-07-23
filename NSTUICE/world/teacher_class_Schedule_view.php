<?php


if (!isset($_SESSION['id']) || !isset($_SESSION['name'])) {
    header("Location: ../index.php");
    exit();
}

include '../conn.php';
date_default_timezone_set("Asia/Dhaka");

$class_date = date('Y-m-d');
$teacher_name = $_SESSION['name'];

$classQuery = $conn->query("SELECT * FROM teacher_class_schedule WHERE date = '$class_date' AND teacher_name = '$teacher_name'");
?>

<div class="schedule-card">
    <div class="schedule-header">📅 Your Classes for <?php echo date('d-M-Y'); ?> :</div>
    <div class="white-gap"></div>
    <table>
        <thead>
            <tr>
                <th>Course</th>
                <th>Semester</th>
                <th>From</th>
                <th>To</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($classQuery->num_rows > 0) {
                while ($row = $classQuery->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['course_name']}</td>
                        <td>{$row['semester']}</td>
                        <td>" . substr($row['start_time'], 0, 5) . "</td>
                        <td>" . substr($row['end_time'], 0, 5) . "</td>

                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center; color: #777;'>📭 No classes scheduled for today.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    
    <div class="button-group">
        <button class="btn btn-green" onclick="window.location.href='teacher_class_schedule.php'">Update</button>
    </div>
    
</div>
