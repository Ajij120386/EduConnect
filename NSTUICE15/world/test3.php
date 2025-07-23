<?php
session_start();
include("../conn.php");

$user_id = $_SESSION['id'] ?? 8;
$sql = "SELECT * FROM user_data WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);




$count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = $user_id AND completed = 0");
$row = mysqli_fetch_assoc($count_result);
$pending_tasks = $row['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Dashboard</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background-color: #ecf0f3;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background: linear-gradient(to right, #1f4037, #99f2c8);
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }

    .container {

      
      display: flex;
      flex-wrap: wrap;
      padding: 20px;
      gap: 20px;
      justify-content: center;
      align-items: stretch;
    }

    .left-column, .right-column {
       border: 1px solid #f57c00;
      flex: 1;
      min-width: 320px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .panel, .profile-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 16px;
    }

    .dashboard_heading {
      background-color: #e67e22;
      color: white;
      padding: 10px 16px;
      font-size: 18px;
      font-weight: 600;
      border-radius: 8px;
      margin-bottom: 10px;
      text-align: center;
    }

    .card-body {
      padding: 8px;
      text-align: center;
    }

    .btn {
      padding: 6px 14px;
      font-size: 13px;
      border-radius: 5px;
      font-weight: 600;
      text-decoration: none;
      color: white;
      background: #4267B2;
      display: inline-block;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn:hover {
      background: #2c4c96;
    }

    .badge {
      padding: 4px 10px;
      font-size: 12px;
      border-radius: 10px;
      background-color: #ffc107;
      color: #000;
      font-weight: bold;
    }

    .profile-card {
      padding: 20px;
      flex-grow: 1;
    }

    .profile-img {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      transition: transform 0.3s ease;
      margin: 0 auto 10px;
      display: block;
    }

    .profile-img:hover {
      transform: scale(1.05);
    }

    .section-title {
      background: #f67200;
      color: white;
      padding: 10px;
      border-radius: 8px;
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 16px;
      text-align: center;
    }

    .info-field {
      background: #f4f4f4;
      margin-bottom: 8px;
      padding: 10px 16px;
      border-radius: 8px;
      font-size: 15px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .label {
      font-weight: 600;
      color: #333;
      min-width: 120px;
      flex-shrink: 0;
    }

    .value {
      color: #111;
      flex: 1;
      text-align: left;
    }

     .dashboard-header {
            
            text-align: center;
            font-size: 30px;
            font-weight: bold;
           
            margin-bottom: 4px;
            color: white;
            border-radius: 12px;
            background: linear-gradient(to right, #3b82f6, #1e3a8a);
            box-shadow: 0 6px 7px rgba(0, 0, 0, 0.1);
        }


    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        padding: 15px;
      }
    }

     .top-bar {
            background: linear-gradient(90deg, #4f46e5, #3b82f6);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 3px;
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

        .dashboard-wrapper {
            max-width: 1200px;
            margin: auto;
        }

  </style>
</head>
<body>


<div class="contain mt-4 dashboard-wrapper">

   <div class="d-flex justify-content-between align-items-center top-bar">
        <div class="fs-5 fw-bold">Your Dashboard</div>
        <a href="http://localhost/NSTUICE15" class="btn btn-back px-3">← Back to Main Site</a>
        
    </div>


<div class="container">
   <!-- Right Column -->
  <div class="right-column">
    <div class="profile-card">
      <img src="../user_image/<?= htmlspecialchars($user['image']) ?>" alt="Profile Image" class="profile-img">
      <div class="section-title">Your Profile</div>
      <div class="info-field"><span class="label">First Name:</span><span class="value"><?= $user['first_name'] ?></span></div>
      <div class="info-field"><span class="label">Last Name:</span><span class="value"><?= $user['last_name'] ?></span></div>
      <div class="info-field"><span class="label">User Name:</span><span class="value"><?= $user['user_name'] ?></span></div>
      <div class="info-field"><span class="label">Registration No:</span><span class="value"><?= $user['reg_no'] ?></span></div>
      <div class="info-field"><span class="label">Email ID:</span><span class="value"><?= $user['email'] ?></span></div>
      <div class="info-field"><span class="label">Phone No:</span><span class="value"><?= $user['phone'] ?></span></div>
      <div class="info-field"><span class="label">Blood Group:</span><span class="value"><?= $user['blood_group'] ?></span></div>
      <div class="info-field"><span class="label">Location:</span><span class="value"><?= $user['location'] ?></span></div>
      <div class="info-field"><span class="label">Batch No:</span><span class="value"><?= $user['batch_no'] ?></span></div>
    </div>
  </div>
  
  <div class="left-column">
    <!-- Edit Account -->
    <div class="panel">
      <div class="dashboard_heading">Edit Your Account</div>
      <div class="card-body">
        <a href="edit_account.php" class="btn">⚙️ Update Profile</a>
        <p style="font-size: 0.95rem; margin-top: 6px;">Change your info, image, or password</p>
      </div>
    </div>
    
    <br>
    <br>


    <!-- Personal Note Library -->
    <div class="panel">
      <div class="dashboard_heading">Personal Note Library</div>
      <div class="card-body">
        <a href="note_library.php" class="btn">📚 View / Upload Notes</a>
        <p style="font-size: 0.95rem; margin-top: 6px;">Access your private PDF collection</p>
      </div>
    </div>


    
    <br>
    <br>

    <!-- To-Do List -->
    <div class="panel">
      <div class="dashboard_heading">My To-Do List</div>
      <div class="card-body">
        <a href="todo.php" class="btn d-flex justify-content-between align-items-center">
           <span>✅ Manage To-Do List</span>
                    <span class="badge bg-warning text-dark"><?= $pending_tasks ?> Pending</span>
        </a>
        <p style="font-size: 0.95rem; margin-top: 6px;">Track and organize your daily tasks</p>
      </div>
    </div>
  </div>

 
</div>

</body>
</html>
