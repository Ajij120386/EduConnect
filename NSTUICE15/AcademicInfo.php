<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #dbeafe, #f8fafc);
            font-family: 'Segoe UI', sans-serif;
        }
        .dashboard-wrapper {
            max-width: 1200px;
            margin: auto;
        }
        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 30px;
        }
        .dashboard-header {
            background: linear-gradient(90deg, #0d6efd, #0a58ca);
            color: white;
            padding: 20px 30px;
            font-size: 28px;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
            box-shadow: inset 0 -1px 0 rgba(255,255,255,0.2);
        }
        .dashboard-tile {
            display: block;
            color: white;
            padding: 30px 20px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }
        .dashboard-tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }
        .dashboard-icon {
            font-size: 42px;
            margin-bottom: 10px;
        }
        .tile-text {
            font-size: 18px;
            font-weight: 600;
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
</head>
<body>

<div class="container mt-4 dashboard-wrapper">

    <!-- 🔙 Back + Title -->
    <div class="d-flex justify-content-between align-items-center top-bar">
        <div class="fs-5 fw-bold">NSTU ICE Student Portal</div>
       <a href="index.php" class="btn btn-back px-3">← Back to Main Site</a>
        
    </div>

    <!-- 📘 Dashboard -->
    <div class="glass-card">
        <div class="dashboard-header">📘 Visitor Dashboard</div>
        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <a href="notice.php" class="dashboard-tile bg-primary">
                    <div class="dashboard-icon">🔔</div>
                    <div class="tile-text">Notice</div>
                    <div class="badge bg-light text-dark mt-2">New: 37</div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="syllabus.php" class="dashboard-tile bg-success">
                    <div class="dashboard-icon">📘</div>
                    <div class="tile-text">Syllabus</div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="result.php" class="dashboard-tile bg-warning text-dark">
                    <div class="dashboard-icon">📊</div>
                    <div class="tile-text">Result</div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="https://sportal.nstu.ac.bd/public/admin/files/notice/20250106125616.pdf" class="dashboard-tile bg-info">
                    <div class="dashboard-icon">📅</div>
                    <div class="tile-text">Class Routine</div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="https://sportal.nstu.ac.bd/public/admin/files/notice/20250318125214.pdf" class="dashboard-tile bg-danger">
                    <div class="dashboard-icon">🗓️</div>
                    <div class="tile-text">Term Final Exam Routine</div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="seat_plan.php" class="dashboard-tile bg-secondary">
                    <div class="dashboard-icon">🪑</div>
                    <div class="tile-text">Seat Plan of Exam</div>
                </a>
            </div>
            
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
