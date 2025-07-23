<?php
session_start();
include 'conn.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);



$syllabus = mysqli_query($conn, "SELECT * FROM syllabus ORDER BY year_term, si_no ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Syllabus Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
</head>
<style>
        body {
            background: linear-gradient(to right, #dbeafe, #f8fafc);
            font-family: 'Segoe UI', sans-serif;
        }
        
        .top-bar {
            background: linear-gradient(90deg, #4f46e5, #3b82f6);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-back {
            background-color: white;
            color: #4f46e5;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            transition: 0.3s ease;
        }
        .btn-back:hover {
            background-color: #e0e7ff;
        }
    </style>
<body class="bg-light">
<div class="container mt-5">


   <!-- 🔙 Back + Title -->
    <div class="d-flex justify-content-between align-items-center top-bar">
        <div class="fs-5 fw-bold">📘 Academic Syllabus</div>
        <a href="AcademicInfo.php" class="btn btn-back px-3">← Back to Dashboard</a>
    </div>
  
    
    <!-- Filter + Export -->
      <?php
                    $options = ["Year-1 Term-I", "Year-1 Term-II", "Year-2 Term-I", "Year-2 Term-II", "Year-3 Term-I", "Year-3 Term-II", "Year-4 Term-I", "Year-4 Term-II", "Year-5 Term-I", "Year-5 Term-II"];
                    foreach ($options as $opt) {
                        $selected = ($edit_data['year_term'] ?? '') == $opt ? 'selected' : '';
                        "<option value='$opt' $selected>$opt</option>";
                    }
                    ?>
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
    <table class="table table-bordered table-striped table-hover" id="syllabusTable">
        <thead class="table-dark">
            <tr>
                <th>SI No.</th>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Credit</th>
                <th>Type</th>
                <th>Year-Term</th>
                
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
               
            </tr>
        <?php } ?>
        </tbody>
    </table>
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
    // Initialize DataTable
    var table = $('#syllabusTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['csv', 'print', 'pdf'],
        paging: true,
        info: true,
        ordering: true,
        searching: true,
        initComplete: function () {
            $("#syllabusTable_filter").hide();  // Hide default search
            $(".dt-buttons").appendTo("#exportButtons"); // Export buttons
        }
    });

    // Custom filter by exact Year-Term match (column index 5)
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var selected = $('#filterYearTerm').val();
        var yearTerm = data[5]; // index of "Year-Term" column
        if (selected === "" || selected === yearTerm) {
            return true;
        }
        return false;
    });

    // Trigger filter when dropdown changes
    $('#filterYearTerm').on('change', function () {
        table.draw();
    });
});
</script>

</body>
</html>
