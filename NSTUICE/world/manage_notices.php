<?php
session_start();
include '../conn.php';

// Handle notice deletion
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $get_file = mysqli_fetch_assoc(mysqli_query($conn, "SELECT file_url FROM notices WHERE id = $id"));
    if ($get_file && file_exists("../" . $get_file['file_url'])) {
        unlink("../" . $get_file['file_url']);
    }
    mysqli_query($conn, "DELETE FROM notices WHERE id = $id");
    header("Location: manage_notices.php");
    exit();
}

// Handle notice upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'], $_POST['title'])) {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $is_new = isset($_POST['is_new']) ? 1 : 0;
    $uploaded_date = date('Y-m-d');

    $file_url = '';
    if (!empty($_FILES['notice_file']['name'])) {
        $upload_dir = '../uploads/notices/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['notice_file']['name']);
        $target_path = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['notice_file']['tmp_name'], $target_path)) {
            $file_url = 'uploads/notices/' . $file_name;
        }
    }

    $query = "INSERT INTO notices (type, title, file_url, uploaded_date, is_new)
              VALUES ('$type', '$title', '$file_url', '$uploaded_date', $is_new)";
    mysqli_query($conn, $query);
    header("Location: manage_notices.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM notices ORDER BY uploaded_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Notices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .panel-wrapper {
            max-width: 1100px;
            margin: auto;
            padding: 30px;
            border: 1px solid #f57c00;
        }
        .panel-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgb(0, 245, 110);
        }
        .back-btn {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container mt-4 panel-wrapper">

    <!-- 🔙 Back to Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary mb-0">📢 Upload New Notice</h3>
        <a href="admin_dashboard.php" class="btn btn-outline-primary back-btn">← Back to Dashboard</a>
    </div>

    <!-- Upload Form -->
    <div class="panel-card mb-5">
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <input type="text" name="type" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Upload PDF</label>
                    <input type="file" name="notice_file" accept=".pdf" class="form-control">
                </div>
                <div class="col-md-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">Upload Notice</button>
                </div>
            </div>
        </form>
    </div>

    <!-- All Notices Table -->
    <h4 class="text-secondary mb-3">📄 All Uploaded Notices</h4>
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Uploaded Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$result || mysqli_num_rows($result) == 0) {
                        echo "<tr><td colspan='5'><div class='alert alert-warning m-0'>No notices found.</div></td></tr>";
                    } else {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>$i</td>";
                            echo "<td>{$row['type']}</td>";
                            echo "<td>{$row['title']}</td>";
                            echo "<td>{$row['uploaded_date']}</td>";
                            echo "<td>
                                <a class='btn btn-info btn-sm' href='../{$row['file_url']}' target='_blank'>View</a>
                                <a class='btn btn-success btn-sm' href='../{$row['file_url']}' download>Save</a>
                                <a class='btn btn-danger btn-sm' href='?delete_id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this notice?')\">Delete</a>
                            </td>";
                            echo "</tr>";
                            $i++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>





 <script>

  let zoomLevel = 100; // start at 100%

  function applyZoom() {
    document.body.style.transform = `scale(${zoomLevel / 100})`;
    document.body.style.transformOrigin = 'top center';
  }

  function zoomIn() {
    if (zoomLevel < 200) { // max limit
      zoomLevel += 2;
      applyZoom();
    }
  }

  function zoomOut() {
    if (zoomLevel > 50) { // min limit
      zoomLevel -= 2;
      applyZoom();
    }
  }

  </script>
</body>
</html>
