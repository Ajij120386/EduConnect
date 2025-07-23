<?php
session_start();
include("../conn.php");

// Fake user ID for testing if session not set
$user_id = $_SESSION['id'] ;
$message = "";

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


  $imageName = $_SESSION['image']; // Keep previous image by default

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
    $uploadDir = '../user_image/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $originalName = basename($_FILES['profile_image']['name']);
    $imageName = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", $originalName);
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $imageName);
}


    $id = intval($_POST['id']);
    $first_name   = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name    = mysqli_real_escape_string($conn, $_POST['last_name']);
    $user_name    = mysqli_real_escape_string($conn, $_POST['user_name']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $phone        = mysqli_real_escape_string($conn, $_POST['phone']);
    $reg_no       = mysqli_real_escape_string($conn, $_POST['reg_no']);
    $blood_group  = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $location     = mysqli_real_escape_string($conn, $_POST['location']);
    $batch_no     = mysqli_real_escape_string($conn, $_POST['batch_no']);

    $update = "UPDATE user_data SET
    first_name = '$first_name',
    last_name = '$last_name',
    user_name = '$user_name',
    email = '$email',
    phone = '$phone',
    reg_no = '$reg_no',
    blood_group = '$blood_group',
    location = '$location',
    batch_no = '$batch_no',
    image = '$imageName'
    WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        $message = "✅ Account updated successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

// Fetch user data
$sql = "SELECT * FROM user_data WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Your Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 40px;
        
    }

    .container {
       border: 1px solid #f57c00;
      max-width: 720px;
      margin: 40px auto;
      background: white;
      border-radius: 12px;
      padding: 30px 35px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    h2 {
      background: #f67200;
      color: white;
      padding: 15px 20px;
      margin: -30px -35px 30px;
      font-size: 22px;
      border-radius: 12px 12px 0 0;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
      color: #444;
    }

    .form-group input {
      width: 100%;
      padding: 10px 14px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
    }

    .submit-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 10px;
    }

    .submit-btn:hover {
      background-color: #218838;
    }

    .message {
      text-align: center;
      margin-bottom: 20px;
      color: green;
      font-weight: bold;
    }

    .btn {
            padding: 12px 25px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s;
        }
        .btn:hover {
            background: green;
            transform: translateY(-2px);
        }

         .container {
            max-width: 1100px;
            margin: auto;
        }
  </style>
</head>
<body>


 <a href="javascript:history.back()" 
       class="btn btn-light position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill shadow-sm fw-semibold">
        🔙 Back
    </a>


<div class="container">
  


  <h2>Edit Your Account</h2>



  <?php if ($message): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>
    
  <?php if (!empty($user['image'])): ?>
  <div style="text-align:center; margin-bottom:20px;">
    <img src="../user_image/<?= htmlspecialchars($user['image']) ?>" width="120" height="120" style="border-radius: 50%; border: 2px solid #ccc;">
  </div>
<?php endif; ?>


  <form method="post" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <div class="form-group">
      <label>First Name</label>
      <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>">
    </div>

    <div class="form-group">
      <label>Last Name</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>">
    </div>

    <div class="form-group">
      <label>User Name</label>
      <input type="text" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>">
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
    </div>

    <div class="form-group">
      <label>Phone</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
    </div>

    <div class="form-group">
      <label>Registration No</label>
      <input type="text" name="reg_no" value="<?= htmlspecialchars($user['reg_no']) ?>">
    </div>

    <div class="form-group">
      <label>Blood Group</label>
      <input type="text" name="blood_group" value="<?= htmlspecialchars($user['blood_group']) ?>">
    </div>

    <div class="form-group">
      <label>Location</label>
      <input type="text" name="location" value="<?= htmlspecialchars($user['location']) ?>">
    </div>

    <div class="form-group">
      <label>Batch No</label>
      <input type="text" name="batch_no" value="<?= htmlspecialchars($user['batch_no']) ?>">
    </div>


       <div class="form-group">
  <label>Profile Image</label>
  <input type="file" name="profile_image" class="form-control">
</div>


    <button class="submit-btn" type="submit">Update Account</button>
  </form>

</div>

</body>



</html>
