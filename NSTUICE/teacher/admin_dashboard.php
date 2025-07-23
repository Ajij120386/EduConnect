
<?php
session_start();
include '../conn.php';
include 'world_header.php';
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #dbeafe, #f8fafc);
            font-family: 'Segoe UI', sans-serif;
        }
        .dashboard-wrapper {
            max-width: 1100px;
            margin: auto;
        }
        .glass-card {
             border: 1px solid #f57c00;
             margin-bottom= 12px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 35px 30px;
        }
        .dashboard-header {
            
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            padding: 20px 10px;
            margin-bottom: 20px;
            color: white;
            border-radius: 12px;
            background: linear-gradient(to right, #3b82f6, #1e3a8a);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .dashboard-tile {
            display: block;
            color: white;
            padding: 30px 20px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            transition: 0.3s ease-in-out;
        }
        .dashboard-tile:hover {
            text-decoration: none;
            transform: translateY(-6px);
            box-shadow: 0 16px 30px rgba(0, 0, 0, 0.2);
        }
        .dashboard-icon {
            font-size: 42px;
            margin-bottom: 12px;
        }
        .tile-text {
            font-size: 19px;
            font-weight: 600;

            text-decoration: none;
        }
        .top-bar {
            margin-bottom: 25px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .btn-back {
            background-color: white;
            color: #3b82f6;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            padding: 10px 20px;
            transition: 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .btn-back:hover {
            text-decoration: none;
            background-color: #e0e7ff;
        }
    </style>
</head>
<body>

<div class="container mt-5 dashboard-wrapper">

 
    <!-- 🛠️ Admin Dashboard -->
    <div class="glass-card">
        <div class="dashboard-header">🛠️ Admin Dashboard</div>
        <div class="row g-4">
            <div class="col-md-6">
                <a href="admin_approve_user.php" class="dashboard-tile bg-primary">
                    <div class="dashboard-icon">✅</div>
                    <div class="tile-text">Approve New Users</div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="manage_notices.php" class="dashboard-tile bg-success">
                    <div class="dashboard-icon">📝</div>
                    <div class="tile-text">Manage Notices</div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="syllabus.php" class="dashboard-tile bg-warning text-dark">
                    <div class="dashboard-icon">📘</div>
                    <div class="tile-text">Syllabus Management</div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="all_students.php" class="dashboard-tile bg-secondary">
                    <div class="dashboard-icon">👥</div>
                    <div class="tile-text">Manage Students</div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
