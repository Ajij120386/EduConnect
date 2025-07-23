<?php
session_start();
include 'conn.php';

// FIX: Use the correct column name 'uploaded_date'
$notices = mysqli_query($conn, "SELECT * FROM notices ORDER BY uploaded_date DESC");

// Optional: check if query failed
if (!$notices) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Notices</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        <div class="fs-5 fw-bold">📑 All Notices</div>
        <a href="AcademicInfo.php" class="btn btn-back px-3">← Back to Dashboard</a>
    </div>
  

 

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Title</th>
                <th>Uploaded</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while ($row = mysqli_fetch_assoc($notices)) { ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= date("d M Y", strtotime($row['uploaded_date'])) ?></td>
                    <td>
                        <a href="<?= $row['file_url'] ?>" target="_blank" class="btn btn-sm btn-info">View</a>
                        <a href="<?= $row['file_url'] ?>" download class="btn btn-sm btn-success">Save</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
