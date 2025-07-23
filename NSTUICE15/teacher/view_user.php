<?php
session_start();



if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/NSTUICE15/index.php");
    exit();
}


include '../conn.php';



// Check if user ID is provided
if (!isset($_GET['id'])) {
    die("User ID not specified.");
}

$user_id = intval($_GET['id']);

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM user_data WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-box {
            max-width: 600px;
            margin: 40px auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-box img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .profile-label {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="profile-box text-center">
       <img src="../user_image/<?= !empty($user['image']) ? htmlspecialchars($user['image']) : 'blank_pp.png' ?>" alt="Profile" width="100">

        <h4 class="mt-2"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h4>
        <p class="text-muted">@<?= htmlspecialchars($user['user_name']) ?></p>

        <hr>
      <p><span class="profile-label">Email:</span> 
   <a href="mailto:<?= htmlspecialchars($user['email']) ?>" class="text-decoration-none">
       <?= htmlspecialchars($user['email']) ?>
   </a>
</p>

<p><span class="profile-label">Phone:</span> 
   <a href="tel:<?= htmlspecialchars($user['phone']) ?>" class="text-decoration-none">
       <?= htmlspecialchars($user['phone']) ?>
   </a>
</p>

        <p><span class="profile-label">Reg No:</span> <?= htmlspecialchars($user['reg_no']) ?></p>
        <p><span class="profile-label">Batch No:</span> <?= htmlspecialchars($user['batch_no']) ?></p>
        <p><span class="profile-label">Blood Group:</span> <?= htmlspecialchars($user['blood_group']) ?></p>
        <p><span class="profile-label">Location:</span> <?= htmlspecialchars($user['location']) ?></p>
     

        <a href="javascript:history.back()" class="btn btn-secondary mt-3">← Go Back</a>
    </div>
</div>
</body>
</html>
