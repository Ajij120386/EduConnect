<?php
session_start();
include '../conn.php';

$admin_id = $_SESSION['id'];

$requests = mysqli_query($conn, "
    SELECT 
        group_requests.id, 
        user_data.id AS user_id, 
        user_data.first_name, 
        user_data.last_name, 
        user_data.image, 
        groups.id AS group_id,
        groups.name AS group_name, 
        groups.cover_image 
    FROM group_requests 
    JOIN user_data ON group_requests.user_id = user_data.id 
    JOIN groups ON group_requests.group_id = groups.id 
    WHERE group_requests.status='pending' AND groups.created_by = $admin_id
");


$groups = mysqli_query($conn, "SELECT * FROM groups WHERE created_by = $admin_id");
$users = mysqli_query($conn, "SELECT * FROM user_data");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #eef2f3, #dff3ff);
            color: #333;
            font-size: 16px;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        .panel {
            border: 1px solid #f57c00;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
            transition: box-shadow 0.3s;
        }
        .panel:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        .panel h3 {
            font-size: 22px;
            font-weight: 700;
            color: #0056b3;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }
        .request-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 20px;
            margin-bottom: 12px;
            background-color: #fafafa;
        }
        .request-row:hover {
            background-color: #f0f8ff;
        }
        .form-control-scroll {
            max-height: 300px;
            overflow-y: auto;
            padding: 12px;
            border: 1px solid #ccc;
            background-color: #fff;
            border-radius: 8px;
        }
        .btn-action {
            min-width: 100px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .btn-action:hover {
            transform: scale(1.03);
            transition: transform 0.2s;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Pending Join Requests -->
        <div class="panel">
            <h3>📥 Pending Join Requests</h3>
            <?php while ($r = mysqli_fetch_assoc($requests)) { ?>
                <div class="request-row">
                  
                <div class="d-flex align-items-center gap-2">
  <!-- User link -->
  <a href="view_user.php?id=<?= $r['user_id'] ?>" class="d-flex align-items-center text-decoration-none text-dark">
    <img src="../user_image/<?= htmlspecialchars($r['image'] ?: 'blank_pp.png') ?>" alt="Profile" width="40" height="40" style="border-radius:50%; object-fit:cover; margin-right:10px;">
    <strong><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></strong>
  </a>

  <!-- Visual indicator -->
  <span class="mx-2" style="font-size: 18px;">     ---------->      </span>

  <!-- Group link -->
  <?php
  $cover = ($r['cover_image'] && file_exists("../" . $r['cover_image']))
      ? "../" . $r['cover_image']
      : "https://source.unsplash.com/40x40/?group&sig=" . $r['group_id'];
  ?>
  <a href="group_page.php?group_id=<?= $r['group_id'] ?>" class="d-flex align-items-center text-decoration-none text-muted">
    <img src="<?= $cover ?>" alt="Group" width="35" height="35" style="border-radius: 8px; object-fit: cover; margin-right: 8px;">
    <em><?= htmlspecialchars($r['group_name']) ?></em>
  </a>
</div>



<div class="d-flex gap-2">
  <!-- Accept -->
  <form action='group_actions.php' method='POST' class="mb-0">
      <input type='hidden' name='action' value='accept_request'>
      <input type='hidden' name='request_id' value='<?= $r['id'] ?>'>
      <button type='submit' class="btn btn-success btn-sm btn-action">✅ Accept</button>
  </form>

  <!-- Decline -->
  <form action='group_actions.php' method='POST' class="mb-0" onsubmit="return confirm('Are you sure you want to decline this request?');">
      <input type='hidden' name='action' value='decline_request'>
      <input type='hidden' name='request_id' value='<?= $r['id'] ?>'>
      <button type='submit' class="btn btn-danger btn-sm btn-action">❌ Decline</button>
  </form>
</div>

                </div>
            <?php } ?>
        </div>

        <!-- Manually Add Users -->
        <div class="panel">
            <h3>👥 Add Users to Groups</h3>
            <form action="group_actions.php" method="POST">
                <input type="hidden" name="action" value="add_multiple_members">
                <div class="mb-3">
                    <label class="form-label">Select Group</label>
                    <select name="group_id" class="form-select">
                        <?php while ($g = mysqli_fetch_assoc($groups)) echo "<option value='{$g['id']}'>{$g['name']}</option>"; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Users</label>
                    <div class="form-control-scroll">
                        <?php while ($u = mysqli_fetch_assoc($users)) {
                            echo "<div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='user_ids[]' value='{$u['id']}' id='user{$u['id']}'>
                                <label class='form-check-label' for='user{$u['id']}'>{$u['user_name']}</label>
                            </div>";
                        } ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-action">Add Selected Users</button>
            </form>
        </div>

        <div class="text-center">
            <a href="groups.php" class="btn btn-outline-secondary">← Back to Groups</a>
        </div>
    </div>
</body>
</html>
