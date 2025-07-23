<?php
session_start();
include '../conn.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Insert / Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $si_no = $_POST['si_no'];
    $course_code = $_POST['course_code'];
    $course_title = $_POST['course_title'];
    $credit = floatval($_POST['credit']);
    $course_type = $_POST['course_type'];
    $year_term = $_POST['year_term'];
    if (!empty($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $stmt = $conn->prepare("UPDATE syllabus SET si_no=?, course_code=?, course_title=?, credit=?, course_type=?, year_term=? WHERE id=?");
        $stmt->bind_param("issdssi", $si_no, $course_code, $course_title, $credit, $course_type, $year_term, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO syllabus (si_no, course_code, course_title, credit, course_type, year_term) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdss", $si_no, $course_code, $course_title, $credit, $course_type, $year_term);
    }
    $stmt->execute();
    header("Location: syllabus.php");
    exit();
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM syllabus WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: syllabus.php");
    exit();
}

// Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $res = mysqli_query($conn, "SELECT * FROM syllabus WHERE id=" . $_GET['edit']);
    $edit_data = mysqli_fetch_assoc($res);
}

$syllabus = mysqli_query($conn, "SELECT * FROM syllabus ORDER BY year_term, si_no ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Syllabus Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
        }
        .syllabus-panel {
            border: 1px solid #f57c00;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        .back-button {
            margin-bottom: 20px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="section-header mb-4">
        <h3 class="text-primary">📘 Academic Syllabus Management</h3>
        <a href="admin_dashboard.php" class="btn btn-outline-primary back-button">← Back to Dashboard</a>
    </div>

    <div class="syllabus-panel mb-4">
        <!-- Form -->
        <form method="POST" class="row g-3">
            <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?? '' ?>">
            <div class="col-md-2">
                <label class="form-label">SI No.</label>
                <input type="number" name="si_no" class="form-control" value="<?= $edit_data['si_no'] ?? '' ?>" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Course Code</label>
                <input type="text" name="course_code" class="form-control" value="<?= $edit_data['course_code'] ?? '' ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Course Title</label>
                <input type="text" name="course_title" class="form-control" value="<?= $edit_data['course_title'] ?? '' ?>" required>
            </div>
            <div class="col-md-1">
                <label class="form-label">Credit</label>
                <input type="number" step="0.01" name="credit" class="form-control" value="<?= $edit_data['credit'] ?? '' ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Course Type</label>
                <select name="course_type" class="form-select" required>
                    <option value="">-- Select --</option>
                    <option value="Compulsory" <?= ($edit_data['course_type'] ?? '') == 'Compulsory' ? 'selected' : '' ?>>Compulsory</option>
                    <option value="Optional" <?= ($edit_data['course_type'] ?? '') == 'Optional' ? 'selected' : '' ?>>Optional</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Year-Term</label>
                <select name="year_term" class="form-select" required>
                    <?php
                    $options = ["Year-1 Term-I", "Year-1 Term-II", "Year-2 Term-I", "Year-2 Term-II", "Year-3 Term-I", "Year-3 Term-II", "Year-4 Term-I", "Year-4 Term-II", "Year-5 Term-I", "Year-5 Term-II"];
                    foreach ($options as $opt) {
                        $selected = ($edit_data['year_term'] ?? '') == $opt ? 'selected' : '';
                        echo "<option value='$opt' $selected>$opt</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-12 text-end mt-2">
                <button type="submit" class="btn btn-success"><?= $edit_data ? 'Update' : 'Add' ?> Syllabus</button>
            </div>
        </form>
    </div>

    <!-- Filter + Export -->
    <div class="row mb-3">
        <div class="col-md-3">
            <select id="filterYearTerm" class="form-select">
                <option value="">🔍 Filter by Year-Term</option>
                <?php foreach ($options as $opt) echo "<option value='$opt'>$opt</option>"; ?>
            </select>
        </div>
        <div class="col-md-9 text-end">
            <div id="exportButtons"></div>
        </div>
    </div>

    <!-- Table -->
    <div class="syllabus-panel">
        <table class="table table-bordered table-striped table-hover" id="syllabusTable">
            <thead class="table-dark text-center">
                <tr>
                    <th>SI No.</th>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Credit</th>
                    <th>Type</th>
                    <th>Year-Term</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($syllabus)) { ?>
                <tr>
                    <td><?= $row['si_no'] ?></td>
                    <td><?= htmlspecialchars($row['course_code']) ?></td>
                    <td><?= htmlspecialchars($row['course_title']) ?></td>
                    <td><?= $row['credit'] ?></td>
                    <td><?= $row['course_type'] ?></td>
                    <td><?= $row['year_term'] ?></td>
                    <td class="text-center">
                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/pdfmake.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfmake@0.2.7/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
   var table = $('#syllabusTable').DataTable({
    dom: 'Bfrtip',
    buttons: ['csv', 'print', 'pdf'],
    paging: true,
     pageLength: 3, 
 // 👈 Add this line
    info: true,
    ordering: true,
    searching: true,
    initComplete: function () {
        $("#syllabusTable_filter").hide();
        $(".dt-buttons").appendTo("#exportButtons");
    }
});


    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var selected = $('#filterYearTerm').val();
        var yearTerm = data[5];
        return selected === "" || selected === yearTerm;
    });

    $('#filterYearTerm').on('change', function () {
        table.draw();
    });
});
</script>

</body>
</html>
