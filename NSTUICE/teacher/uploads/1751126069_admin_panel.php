<?php
session_start();
include '../conn.php';

$admin_id = $_SESSION['id'];

// Only fetch join requests for groups created by this admin
$requests = mysqli_query($conn, "SELECT group_requests.id, user_data.user_name, groups.name AS group_name 
    FROM group_requests 
    JOIN user_data ON group_requests.user_id = user_data.id 
    JOIN groups ON group_requests.group_id = groups.id 
    WHERE group_requests.status='pending' AND groups.created_by = $admin_id");

// Only show groups created by this admin
$groups = mysqli_query($conn, "SELECT * FROM groups WHERE created_by = $admin_id");

// Fetch users who are not already members of the admin's groups (optional filter)
$users = mysqli_query($conn, "SELECT * FROM user_data");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
        <h3 class="mb-3 text-primary">📥 Pending Join Requests (Your Groups)</h3>
        <?php while ($r = mysqli_fetch_assoc($requests)) { ?>
            <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                <div><strong><?= $r['user_name'] ?></strong> → <em><?= $r['group_name'] ?></em></div>
                <form action='actions.php' method='POST' class="mb-0">
                    <input type='hidden' name='action' value='accept_request'>
                    <input type='hidden' name='request_id' value='<?= $r['id'] ?>'>
                    <button type='submit' class="btn btn-sm btn-success">Accept</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <div class="card p-4">
        <h3 class="mb-3 text-primary">👥 Manually Add Multiple Users to Your Groups</h3>
        <form action="actions.php" method="POST">
            <input type="hidden" name="action" value="add_multiple_members">
            <div class="mb-3">
                <label class="form-label">Select Group</label>
                <select name="group_id" class="form-select">
                    <?php while ($g = mysqli_fetch_assoc($groups)) echo "<option value='{$g['id']}'>{$g['name']}</option>"; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Select Users</label>
                <div class="form-control p-3" style="max-height: 300px; overflow-y: auto;">
                    <?php while ($u = mysqli_fetch_assoc($users)) {
                        echo "<div class='form-check'>
                            <input class='form-check-input' type='checkbox' name='user_ids[]' value='{$u['id']}' id='user{$u['id']}'>
                            <label class='form-check-label' for='user{$u['id']}'>{$u['user_name']}</label>
                        </div>";
                    } ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Selected Users</button>
        </form>
    </div>

    <div class="mt-4">
        <a href="groups.php" class="btn btn-secondary">← Back to Groups</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
