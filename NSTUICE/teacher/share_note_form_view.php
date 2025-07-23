<?php
session_start();

include '../conn.php';
$current_user = $_SESSION['name'] ?? '';

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'get_courses') {
        $semester = $_POST['semester'];
        $cards = "<div class='col-md-4'><div class='list-group text-left'>";
        $sql = "SELECT * FROM course WHERE semester = '$semester' AND type = 'course_name'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            for ($i = 1; $i <= 10; $i++) {
                $col = "course$i";
                if (!empty($row[$col])) {
                    $course = $row[$col];
                    $cards .= "<a class='list-group-item list-group-item-action course-btn' data-course='$course' style='cursor:pointer;'>
                                <strong>$course</strong></a>";
                }
            }
        }
        $cards .= "</div></div><div class='col-md-8' id='typePanel'></div>";
        echo $cards;
        exit;
    }

    if ($_POST['action'] === 'get_types') {
         $types = ['Lecture', 'Assignment', 'Class Notes', 'Reference Book', 'Previous Year Questions', 'Other'];
        $buttons = "<div class='panel panel-default'><div class='panel-heading'><strong>Select Note Type</strong></div><div class='panel-body text-center'>";
        foreach ($types as $type) {
            $buttons .= "<button class='btn btn-outline-success m-2 type-btn' data-type='$type'>$type</button>";
        }
        $buttons .= "</div></div>";
        $buttons .= "<div id='noteTable'></div></div>";
        echo $buttons;
        exit;
    }

    if ($_POST['action'] === 'get_notes') {
        $course = $_POST['course'];
        $type = $_POST['type'];
        $query = "SELECT * FROM notes WHERE course_name='$course' AND type='$type' ORDER BY id DESC";
        $result = mysqli_query($conn, $query);

        echo "<div class='panel panel-default mt-4'>
                <div class='panel-heading'><h4 class='text-center'>Available Notes</h4></div>";

        if (mysqli_num_rows($result) == 0) {
            echo "<div class='alert alert-warning text-center'>No notes available for this course and type.</div>";
        } else {
            echo "<table class='table table-bordered table-hover'>
                    <thead><tr class='info'>
                        <th>#</th>
                        <th>Author</th>
                        <th>Title</th>
                        <th>Uploaded Date</th>
                        <th>Actions</th>
                    </tr></thead>
                    <tbody>";
            $index = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $filePath = $row['file'] ? 'uploads/' . $row['file'] : ($row['link'] ?? '#');
                $title = htmlspecialchars($row['title'] ?? 'Untitled');
                $uploaded_date = htmlspecialchars($row['uploaded_date'] ?? 'N/A');
                $author = htmlspecialchars($row['author']);
                $noteId = $row['id'];

                echo "<tr data-id='{$noteId}'>
                        <td>{$index}</td>
                        <td><span class='label label-primary'>{$author}</span></td>
                        <td>{$title}</td>
                        <td>{$uploaded_date}</td>
                        <td>
                            <a href='$filePath' target='_blank' class='btn btn-info btn-xs'>View</a>
                            <a href='$filePath' download class='btn btn-success btn-xs'>Save</a>";
                if ($current_user === $author) {
                    echo " <button class='btn btn-danger btn-xs delete-btn' data-id='{$noteId}'>Delete</button>";
                }
                echo "</td></tr>";
                $index++;
            }
            echo "</tbody></table>";
        }

        echo "</div>";
        exit;
    }

if ($_POST['action'] === 'latest_notes') {
    $query = "SELECT * FROM notes ORDER BY uploaded_date DESC LIMIT 5";
    $result = mysqli_query($conn, $query);

    echo "<div class='panel panel-default'>
            <div class='panel-heading'><h4 class='text-center'>📌 Latest Uploaded Notes</h4></div>";

    if (mysqli_num_rows($result) == 0) {
        echo "<div class='alert alert-info text-center'>No recent notes uploaded.</div>";
    } else {
        echo "<table class='table table-bordered table-striped'>
                <thead><tr>
                    <th>#</th>
                   
                    <th>Semester</th>
                     <th>Course</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Uploaded</th>
                    <th>Action</th>
                </tr></thead>
                <tbody>";
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $filePath = $row['file'] ? 'uploads/' . $row['file'] : ($row['link'] ?? '#');
            echo "<tr>
                    <td>{$i}</td>
                    
                    <td>{$row['semester']}</td>
                    <td>{$row['course_name']}</td>
                    <td>{$row['type']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['author']}</td>
                    <td>{$row['uploaded_date']}</td>
                    <td><a href='{$filePath}' class='btn btn-primary btn-xs' target='_blank'>View</a></td>
                </tr>";
            $i++;
        }
        echo "</tbody></table>";
    }
    echo "</div>";
    exit;
}


    if ($_POST['action'] === 'delete_note') {
        $id = intval($_POST['id']);
        $auth_query = "SELECT author FROM notes WHERE id = $id";
        $auth_result = mysqli_query($conn, $auth_query);
        if ($row = mysqli_fetch_assoc($auth_result)) {
            if ($row['author'] === $current_user) {
                $query = "DELETE FROM notes WHERE id = $id";
                mysqli_query($conn, $query);
                echo 'success';
            } else {
                echo 'unauthorized';
            }
        } else {
            echo 'not_found';
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Browse Notes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
    body {
    background: linear-gradient(120deg, #f0f4f8, #f9fafb);
    font-family: 'Segoe UI', sans-serif;
    color: #333;
    margin-bottom: 10px;
}

.container {
    padding: 30px;
    margin-top: 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    border: 1px solid #f57c00;
}

h3 {
    margin-bottom: 25px;
    font-weight: 600;
    color: #334155;
}

#semesterBtns .btn {
    margin: 5px;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: bold;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

#semesterBtns .btn:hover {
    transform: scale(1.05);
    opacity: 0.95;
}

.list-group {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    background-color: #f8fafc;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    padding: 10px;
}

.list-group-item {
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 5px;
    background-color: #ffffff;
    transition: all 0.2s ease;
    font-weight: 500;
    color: #1e293b;
}

.list-group-item:hover {
    background-color: #e0f2fe;
    color: #0c4a6e;
    transform: translateX(4px);
}

.panel {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #cbd5e1;
    box-shadow: 0 3px 10px rgba(0,0,0,0.04);
}

.panel-heading {
    background-color: #e2e8f0;
    color: #1e293b;
    font-weight: 600;
    padding: 12px 15px;
    border-bottom: 1px solid #cbd5e1;
}

.panel-body {
    padding: 15px;
}

.type-btn {
    margin: 6px;
    padding: 8px 18px;
    font-weight: 500;
    border-radius: 25px;
    border: 2px solid #38bdf8;
    background-color: #f0f9ff;
    color: #0369a1;
    transition: all 0.3s ease;
}

.type-btn:hover {
    background-color: #38bdf8;
    color: #fff;
}

.table > thead > tr {
    background-color: #f1f5f9;
    font-weight: 600;
}

.table > tbody > tr:nth-child(odd) {
    background-color: #f9fafb;
}

.table > tbody > tr > td {
    vertical-align: middle;
}

.btn-xs {
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 12px;
    margin: 2px;
}

.label-primary {
    background-color: #0ea5e9;
    padding: 5px 10px;
    border-radius: 12px;
    display: inline-block;
}

hr {
    border-color: #cbd5e1;
}


.btn-default {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #1e293b;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-default:hover {
    background-color: green;
    color: #0f172a;
}
.active-course {
    background-color: #2563eb !important;
    color: white !important;
}

.active-type {
    background-color: #0369a1 !important;
    color: white !important;
    border-color: #0369a1 !important;
}


.active-semester {
    background-color: #f97316 !important;  /* Bright orange */
    color: white !important;
    border-color: #ea580c !important;
}


#latestNotes
{

    animation: fadeIn 0.5s ease-in-out;
  border: 1px solid #000080;
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

<div id="latestNotes"></div>



    <h3 class="text-center">Select Semester to Browse Notes</h3>
    <div class="text-center" id="semesterBtns">
        <?php
        $semesters = ['1/1','1/2','2/1','2/2','3/1','3/2','4/1','4/2'];
        foreach ($semesters as $sem) {
            echo "<button class='btn btn-warning m-2 semester-btn' data-sem='$sem'>$sem</button>";
        }
        ?>
    </div>
    <hr>
    <div class="row" id="courseTypeRow"></div>
    <div id="noteTable"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let selectedCourse = '';

    $(document).on('click', '.semester-btn', function () {
    const sem = $(this).data('sem');

    // Highlight the selected semester
    $('.semester-btn').removeClass('active-semester');
    $(this).addClass('active-semester');

    // Clear and load courses
    $('#courseTypeRow, #noteTable').empty();
    $.post('', { action: 'get_courses', semester: sem }, function (data) {
        $('#courseTypeRow').html(data);
    });
});

   $(document).on('click', '.course-btn', function () {
    selectedCourse = $(this).data('course');
    
    // Remove previous highlight
    $('.course-btn').removeClass('active-course');

    // Highlight current
    $(this).addClass('active-course');

    $('#noteTable').empty();
    $.post('', { action: 'get_types' }, function (data) {
        $('#typePanel').html(data);
    });
});


   $(document).on('click', '.type-btn', function () {
    const type = $(this).data('type');

    // Remove previous highlight
    $('.type-btn').removeClass('active-type');

    // Highlight current
    $(this).addClass('active-type');

    $.post('', { action: 'get_notes', course: selectedCourse, type: type }, function (data) {
        $('#typePanel #noteTable').html(data);
    });
});


    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this note?')) {
            $.post('', { action: 'delete_note', id: id }, function (response) {
                if (response.trim() === 'success') {
                    $("tr[data-id='" + id + "']").fadeOut(500, function() { $(this).remove(); });
                } else if (response.trim() === 'unauthorized') {
                    alert('You can only delete your own notes.');
                } else {
                    alert('Error deleting note.');
                }
            });
        }
    });



    // Load latest notes on page load
$(document).ready(function () {
    $.post('', { action: 'latest_notes' }, function (data) {
        $('#latestNotes').html(data);
    });
});


</script>
</body>
</html>
