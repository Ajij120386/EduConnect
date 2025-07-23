<?php
session_start();
include '../conn.php';

$teacher_name = $_SESSION['name'] ?? 'Unknown';
$selected_semester = isset($_POST['semester']) ? $_POST['semester'] : "";
$class_date = isset($_POST['class_date']) ? $_POST['class_date'] : "";

if (isset($_POST['submit_schedule'])) {
    foreach ($_POST['courses'] as $course) {
        if (!isset($course['selected'])) continue;

        $name = $conn->real_escape_string($course['name']);
        $start = $conn->real_escape_string($course['start'] . ':' . $course['start_min']);
        $end = $conn->real_escape_string($course['end'] . ':' . $course['end_min']);
        $venue = $conn->real_escape_string($course['venue']);

        $sql = "INSERT INTO teacher_class_schedule (teacher_name, course_name, semester, date, start_time, end_time, venue)
                VALUES ('$teacher_name', '$name', '$selected_semester', '$class_date', '$start', '$end', '$venue')";

        $conn->query($sql);
    }
     

     echo "<script>alert('Schedule Updated Successfully!'); window.location.href='teacher_world.php';</script>";
    exit;
   
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Class Schedule</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #fce4ec);
            padding: 40px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
        }
        .card {
            border: 1px solid #f57c00;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }
        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        select, input[type=date], input[type=text] {
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin: 8px 0;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeIn 0.6s ease-in-out;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
             background: #ff6f00;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f1f8e9;
        }
        tr:hover {
            background-color: #e2e8f0;
            transform: scale(1.01);
            transition: all 0.3s ease-in-out;
        }
        .btn {
            padding: 12px 25px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s;
        }
        .btn:hover {
            background: green;
            transform: translateY(-2px);
        }
        .notification.success {
            background: #c8e6c9;
            padding: 15px;
            margin: 15px 0;
            border-left: 6px solid #388e3c;
            color: #2e7d32;
            border-radius: 5px;
            font-weight: 500;
        }
        .emoji {
            font-size: 20px;
            margin-right: 10px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container mt-4">
  <a href="javascript:history.back()" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left-circle"></i> Back
  </a>
</div>

<div class="container">
    <div class="card">
        <h2><i class="fas fa-calendar-plus"></i> Update Class Schedule</h2>
        
        <form method="post">
            <label><i class="fas fa-layer-group"></i> Select Semester:</label>
            <select name="semester" required onchange="this.form.submit()">
                <option value="">--Select Semester--</option>
                <?php
                $result = $conn->query("SELECT DISTINCT semester FROM course");
                while ($row = $result->fetch_assoc()) {
                    $s = $row['semester'];
                    echo "<option value='$s'" . ($selected_semester == $s ? " selected" : "") . ">$s</option>";
                }
                ?>
            </select>

            <label><i class="fas fa-calendar-day"></i> Select Date:</label>
            <input type="date" name="class_date" value="<?php echo $class_date; ?>" required>
        </form>

     <?php if ($selected_semester && $class_date): ?>

        <form method="post">
            <input type="hidden" name="semester" value="<?php echo $selected_semester; ?>">
            <input type="hidden" name="class_date" value="<?php echo $class_date; ?>">

            <table>
                <thead>
                    <tr>
                        <th>📚 Course</th>
                        <th>🕒 From</th>
                        <th>🕓 To</th>
                        <th>📍 Venue</th>
                        <th>✅ Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $courseRes = $conn->query("SELECT * FROM course WHERE semester = '$selected_semester'");
                    if ($courseRes->num_rows > 0) {
                        $row = $courseRes->fetch_assoc();
                        for ($i = 1; $i <= 10; $i++) {
                            $col = 'course' . $i;
                            if (!empty($row[$col])) {
                                echo "<tr>
                                    <td>{$row[$col]}<input type='hidden' name='courses[$i][name]' value='{$row[$col]}'></td>
                                    <td>
                                        <select name='courses[$i][start]'>";
                                            for ($h = 0; $h < 24; $h++) echo "<option value='$h'>$h</option>";
                                echo "</select>:
                                        <select name='courses[$i][start_min]'>";
                                            for ($m = 0; $m < 60; $m+=5) echo "<option value='$m'>$m</option>";
                                echo "</select>
                                    </td>
                                    <td>
                                        <select name='courses[$i][end]'>";
                                            for ($h = 0; $h < 24; $h++) echo "<option value='$h'>$h</option>";
                                echo "</select>:
                                        <select name='courses[$i][end_min]'>";
                                            for ($m = 0; $m < 60; $m+=5) echo "<option value='$m'>$m</option>";
                                echo "</select>
                                    </td>
                                    <td><input type='text' name='courses[$i][venue]' placeholder='Room / Online'></td>
                                    <td><input type='checkbox' name='courses[$i][selected]' value='1'></td>
                                </tr>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='5'>⚠️ No courses found for selected semester.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <button type="submit" class="btn" name="submit_schedule"><i class="fas fa-save"></i> Submit</button>
        </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
