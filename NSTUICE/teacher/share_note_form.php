<?php
session_start();
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['semester']) && !isset($_FILES['pdf'])) {
    $semester = $_POST['semester'];
    $options = "<option>Select a Course</option>";
    $sql = "SELECT * FROM course WHERE semester = '$semester' AND type = 'course_name'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        for ($i = 1; $i <= 10; $i++) {
            $col = "course$i";
            if (!empty($row[$col])) {
                $options .= "<option value='{$row[$col]}'>{$row[$col]}</option>";
            }
        }
    }
    echo $options;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    $semester = $_POST['semester'];
    $courses = $_POST['course'];
    $title = $_POST['title'];
    $types = $_POST['type'];
    $author = $_SESSION['name'];

    foreach ($courses as $i => $course) {
        $title = mysqli_real_escape_string($conn, $title[$i]);
        $type = mysqli_real_escape_string($conn, $types[$i]);

        $fileName = '';
        if (isset($_FILES['pdf']['name'][$i]) && $_FILES['pdf']['error'][$i] == 0) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir);
            $original = basename($_FILES['pdf']['name'][$i]);
            $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", $original);
            move_uploaded_file($_FILES['pdf']['tmp_name'][$i], $uploadDir . $fileName);
        }

     $sql = "INSERT INTO notes (course_name, semester, author, title , type , file, uploaded_date) 
        VALUES ('$course', '$semester', '$author', '$title', '$type', '$fileName', NOW())";



// Notify all users about the new note
// Notify all users about the new note
$note_id = mysqli_insert_id($conn);
$uploader_id = $_SESSION['id'];
$uploader_name = mysqli_real_escape_string($conn, $_SESSION['name']); // safe escape

$users = mysqli_query($conn, "SELECT id FROM user_data WHERE id != $uploader_id");

while ($row = mysqli_fetch_assoc($users)) {
    $uid = $row['id'];
    $message = "A new note has been shared by $uploader_name.";
    mysqli_query($conn, "
        INSERT INTO notifications (user_id, type, reference_id, message)
        VALUES ($uid, 'new_note', $note_id, '$message');
    ");
}



        if (!mysqli_query($conn, $sql)) {
    echo "Error: " . mysqli_error($conn);
}

    }
    echo "<script>alert('Notes Shared Successfully!'); location.href='world.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Share Notes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #dbeafe, #f0fdf4);
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            margin-top: 40px;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
             border: 1px solid #f57c00;
        }
        .semester-btn {
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 16px;
            margin: 6px;
            border: none;
            font-weight: bold;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .note-form {
            display: none;
            margin-top: 25px;
            border-top: 3px solid #cbd5e1;
            padding-top: 25px;
        }
        .note-header {
            font-weight: bold;
            padding: 10px 0;
            background-color: #e2e8f0;
            border-radius: 6px;
            color: #1e293b;
            margin-bottom: 12px; 
        }
        .note-row {
            margin-bottom: 12px;
        }
        .btn-success {
            background-color: #22c55e;
            border: none;
        }
        .btn-info {
            background-color: #0ea5e9;
            border: none;
        }
        .btn-success:hover, .btn-info:hover {
            opacity: 0.9;
        }
        h3 {
            color: #334155;
            margin-bottom: 20px;
        }
        select.form-control:focus, input.form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }


        .selected-semester {
    box-shadow: 0 0 0 3px red;
    transform: scale(1.05);
    transition: all 0.2s ease-in-out;
}


    </style>
</head>
<body>
<div class="container">
  <div class="text-left" style="margin-bottom: 20px;">
    <a href="world.php" class="btn btn-default">
        ← Back to Dashboard
    </a>
</div>

    <h3 class="text-center">Share Notes</h3>
    <div class="text-center" id="semesterBtns">
        <?php
        $semesters = ['1/1','1/2','2/1','2/2','3/1','3/2','4/1','4/2'];
        $colors = ['btn-success','btn-warning','btn-danger','btn-warning','btn-info','btn-primary','btn-info','btn-success'];
        foreach ($semesters as $index => $sem) {
            echo "<button class='semester-btn btn {$colors[$index]}' onclick=\"loadForm('$sem')\">$sem</button>";
        }
        ?>
    </div>

    <div class="note-form" id="noteForm">
        <div class="note-header row text-center">
            <div class="col-md-2">Course</div>
            <div class="col-md-3">Title</div>
            <div class="col-md-2">Type</div>
            <div class="col-md-3">PDF</div>
        </div>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="semester" id="selectedSemester">
            <div id="noteFields">
                <div class="row note-row">
                    <div class="col-md-2">
                        <select name="course[]" class="form-control" id="courseDropdown"></select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="title[]" placeholder="Write  Your Notes Title Here" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <select name="type[]" class="form-control">
                            <option disabled selected>Select a Type</option>
                            <option value="Lecture">Lecture</option>
                            <option value="Assignment">Assignment</option>
                            <option value="Class Notes">Class Notes</option>
                            <option value="Reff. Book">Reff. Book</option>
                            <option value="Previous Year Ques.">Previous Year Ques.</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="file" name="pdf[]" class="form-control">
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-success">Share Now!!</button>
                <button type="button" class="btn btn-info" onclick="addMoreRow()">Add More</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    function loadForm(sem) {
    $('#selectedSemester').val(sem);
    $('#noteForm').slideDown();

    // POST to load course dropdown
    $.post('', { semester: sem }, function(data) {
        $('#courseDropdown').html(data);
    });

    // Highlight selected button
    $('.semester-btn').removeClass('selected-semester'); // Remove from all
    $('.semester-btn').each(function () {
        if ($(this).text().trim() === sem) {
            $(this).addClass('selected-semester');
        }
    });
}


    function addMoreRow() {
        let row = $('.note-row:first').clone();
        row.find('input').val('');
        row.find('select').val('');
        $('#noteFields').append(row);
    }
</script>
</body>
</html>
