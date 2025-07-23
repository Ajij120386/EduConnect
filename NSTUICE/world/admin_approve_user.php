<?php
session_start();
include '../conn.php';

// Check if logged in and is the admin
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['id'];
$admin_check = mysqli_query($conn, "SELECT user_name FROM user_data WHERE id = $admin_id");
$admin_data = mysqli_fetch_assoc($admin_check);

if ($admin_data['user_name'] !== 'Ajij') {
    echo "<div style='color:red; font-weight:bold;'>Access Denied. Only Admin can access this panel.</div>";
    exit();
}

// Approve or Reject
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve_id'])) {
        $uid = intval($_POST['approve_id']);
        mysqli_query($conn, "UPDATE user_data SET is_approved = 1 WHERE id = $uid");
    } elseif (isset($_POST['reject_id'])) {
        $uid = intval($_POST['reject_id']);
        mysqli_query($conn, "DELETE FROM user_data WHERE id = $uid");
    }
    header("Location: admin_approve_user.php");
    exit();
}

// Fetch unapproved users
$pending_users = mysqli_query($conn, "SELECT * FROM user_data WHERE is_approved = 0");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Approve Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f3f4f6;
            font-family: 'Segoe UI', sans-serif;
        }
        .panel-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            padding: 30px;
             border: 1px solid #f57c00;
        }
        .btn-action {
            min-width: 90px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="panel-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">🛡️ Approve New Users</h2>
            <a href="admin_dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
        </div>

        <?php if (mysqli_num_rows($pending_users) > 0) { ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th class="text-center" colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($user = mysqli_fetch_assoc($pending_users)) { ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['user_name']) ?></td>
                           
                      <td>
  <a href="view_user.php?id=<?= $user['id'] ?>" class="d-flex align-items-center text-decoration-none text-dark">
    <img src="../user_image/<?= htmlspecialchars($user['image'] ?? 'default.jpg') ?>" 
         alt="Profile" 
         class="me-2" 
         style="width:30px; height:30px; object-fit:cover; border-radius:50%;">
    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
  </a>
</td>



                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td class="text-center">
                                <form method="POST">
                                    <input type="hidden" name="approve_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm btn-action">✅ Approve</button>
                                </form>
                            </td>
                            <td class="text-center">
                                <form method="POST" onsubmit="return confirm('Are you sure you want to reject this user?');">
                                    <input type="hidden" name="reject_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm btn-action">❌ Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="alert alert-info">✅ All users are approved.</div>
        <?php } ?>
    </div>
</div>
</body>
</html>
