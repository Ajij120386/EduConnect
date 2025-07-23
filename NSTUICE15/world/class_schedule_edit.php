<?php
include '../conn.php';

$selected_batch = $_POST['selected_batch'] ?? "";
$class_date = $_POST['class_date'] ?? "";


// ✅ Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM class_update WHERE id = $delete_id");
    echo "<script>alert('Class schedule deleted!'); window.location.href='world.php';</script>";
    exit;
}


// ✅ Handle update
if (isset($_POST['update_schedule'])) {
    foreach ($_POST['courses'] as $id => $course) {
        $name = $conn->real_escape_string($course['name']);
        $start = $conn->real_escape_string($course['start'] . ':' . $course['start_min']);
        $end = $conn->real_escape_string($course['end'] . ':' . $course['end_min']);
        $venue = $conn->real_escape_string($course['venue']);

        $sql = "UPDATE class_update SET 
                    course_name = '$name',
                    start_time = '$start',
                    end_time = '$end',
                    venue = '$venue'
                WHERE id = $id";
        $conn->query($sql);
    }

    echo "<script>alert('Schedule Updated Successfully!'); window.location.href='world.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Class Schedule</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #fce4ec, #e0f7fa);
            margin: 0;
            padding: 40px 15px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            border: 1px solid #f57c00;
        }

        h2 {
            margin-bottom: 25px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        label {
            font-weight: 500;
            margin-top: 10px;
            display: block;
            color: #444;
        }

select, input[type="date"], input[type="text"] {
    padding: 10px;
    width: 100%;
    max-width: 100%;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin: 8px 0 16px;
    background-color: #f9f9f9;
    font-size: 16px;
    box-sizing: border-box; /* ✅ ensures padding doesn't increase box size */
}


        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 16px;
        }

        .schedule-table th, .schedule-table td {
            padding: 12px;
            border: 1px solid #ddd;
            vertical-align: middle;
            text-align: left;
        }

        .schedule-table th {
            background-color: #ff6f00;
            color: white;
            text-align: center;
        }

        .even-row {
            background-color: #fff8e1;
        }

        .odd-row {
            background-color: #e3f2fd;
        }

        .input-text {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            background: #fefefe;
        }

        .input-select {
            padding: 6px;
            margin-right: 4px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .time-select {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .btn {
            padding: 12px 25px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: green;
        }

        .back-btn {
            margin-bottom: 20px;
            display: inline-block;
            background: #2c3e50;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
        }

        .back-btn i {
            margin-right: 6px;
        }
        a.btn-danger,
a.btn-sm {
  text-decoration: none;
}

    </style>
</head>
<body>

<div class="container">
    <a href="javascript:history.back()" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="card">
        <h2><i class="fas fa-edit"></i> Edit Class Schedule</h2>

        <form method="post">
            <label for="batch"><i class="fas fa-users-class"></i> Select Batch:</label>
            <select name="selected_batch" required onchange="this.form.submit()">
                <option value="">--Select Batch--</option>
                <?php
                $batches = $conn->query("SELECT DISTINCT batch_no FROM user_data WHERE batch_no IS NOT NULL ORDER BY batch_no ASC");
                while ($row = $batches->fetch_assoc()) {
                    $b = $row['batch_no'];
                    $selected = ($selected_batch == $b) ? 'selected' : '';
                    echo "<option value='$b' $selected>$b</option>";
                }
                ?>
            </select>

            <label for="date"><i class="fas fa-calendar-day"></i> Select Date:</label>
            <input type="date" name="class_date" value="<?php echo $class_date; ?>" required onchange="this.form.submit()">
        </form>

        <?php if ($selected_batch && $class_date): ?>
            <form method="post">
                <input type="hidden" name="class_date" value="<?php echo $class_date; ?>">
                <input type="hidden" name="selected_batch" value="<?php echo $selected_batch; ?>">

                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>📘 Course</th>
                            <th>🕒 From</th>
                            <th>🕓 To</th>
                            <th>📍 Venue</th>
                            <th>⚙️ Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $entries = $conn->query("SELECT * FROM class_update WHERE date = '$class_date' AND batch = '$selected_batch'");
                        if ($entries->num_rows > 0) {
                            $i = 0;
                            while ($row = $entries->fetch_assoc()) {
                                $id = $row['id'];
                                list($sh, $sm) = explode(':', $row['start_time']);
                                list($eh, $em) = explode(':', $row['end_time']);
                                $rowClass = $i++ % 2 == 0 ? 'even-row' : 'odd-row';

                                echo "<tr class='$rowClass'>
    <td><input type='text' name='courses[$id][name]' value='{$row['course_name']}' class='input-text'></td>
    <td>
        <div class='time-select'>
            <select name='courses[$id][start]' class='input-select'>";
                for ($h = 0; $h < 24; $h++) echo "<option value='$h'" . ($sh == $h ? ' selected' : '') . ">$h</option>";
echo "      </select>:
            <select name='courses[$id][start_min]' class='input-select'>";
                for ($m = 0; $m < 60; $m += 5) echo "<option value='$m'" . ($sm == $m ? ' selected' : '') . ">$m</option>";
echo "      </select>
        </div>
    </td>
    <td>
        <div class='time-select'>
            <select name='courses[$id][end]' class='input-select'>";
                for ($h = 0; $h < 24; $h++) echo "<option value='$h'" . ($eh == $h ? ' selected' : '') . ">$h</option>";
echo "      </select>:
            <select name='courses[$id][end_min]' class='input-select'>";
                for ($m = 0; $m < 60; $m += 5) echo "<option value='$m'" . ($em == $m ? ' selected' : '') . ">$m</option>";
echo "      </select>
        </div>
    </td>
    <td><input type='text' name='courses[$id][venue]' value='{$row['venue']}' class='input-text'></td>
    <td>
        <a href='?delete_id=$id' onclick='return confirm(\"Are you sure you want to delete this schedule?\")' class='btn btn-sm btn-danger'>
            <i class=\"fas fa-trash-alt\"></i> Delete
        </a>
    </td>
</tr>";

                            }
                        } else {
                            echo "<tr><td colspan='4'>⚠️ No schedules found for selected batch and date.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <br>
                <button type="submit" class="btn" name="update_schedule"><i class="fas fa-save"></i> Update Schedule</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
