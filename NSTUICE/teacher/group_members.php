<?php
session_start();
include '../conn.php';

if (!isset($_SESSION['id'])) {
    die("Login required");
}

$user_id = $_SESSION['id'];
$group_id = $_GET['group_id'] ?? 0;

$group = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM groups WHERE id=$group_id"));
$creator_id = $group['created_by'];

$members = mysqli_query($conn, "SELECT user_data.id, user_data.first_name, user_data.last_name, user_data.image 
                                FROM group_members 
                                JOIN user_data ON group_members.user_id = user_data.id 
                                WHERE group_members.group_id = $group_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Group Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }
        .search-bar {
            max-width: 300px;
        }

        .btn-warning {
    background-color: #ffc107;
    border: none;
}
.btn-warning:hover {
    background-color: #e0a800;
}

    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">📘 Group: <span class="text-primary"><?= htmlspecialchars($group['name']) ?></span></h3>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <input type="text" id="searchInput" class="form-control search-bar" placeholder="🔍 Search members..." onkeyup="filterMembers()">
        <a href="groups.php" class="btn btn-secondary">← Back</a>
    </div>

    <table class="table table-bordered bg-white shadow-sm" id="membersTable">
        <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Member</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1;
        while ($member = mysqli_fetch_assoc($members)) {
            $role = ($member['id'] == $creator_id) ? 'Admin' : 'Member';
            $is_admin = ($user_id == $creator_id);
            $img = !empty($member['image']) 
    ? '../user_image/' . htmlspecialchars($member['image']) 
    : 'https://via.placeholder.com/40';

        ?>
            <tr>
                <td><?= $i++ ?></td>
                <td>
                    <div class="d-flex align-items-center">
                       <a href="view_user.php?id=<?= $member['id'] ?>" class="d-flex align-items-center text-decoration-none text-dark">
    <img src="<?= $img ?>" alt="Profile" class="profile-img">
    <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
  </a>
                    </div>
                </td>
                <td>
                    <span class="badge <?= $role == 'Admin' ? 'bg-primary' : 'bg-secondary' ?>">
                        <?= $role ?>
                    </span>
                </td>
                <td>
                    <form action="remove_member.php" method="POST" onsubmit="return confirm('Remove this member?');" style="display:inline;">
                        <input type="hidden" name="group_id" value="<?= $group_id ?>">
                        <input type="hidden" name="user_id" value="<?= $member['id'] ?>">
                       


                        <?php
$can_remove = ($is_admin || $user_id == $member['id']); // admin or self
$disabled = $can_remove ? '' : 'disabled';
$btn_text = ($user_id == $member['id']) ? 'Leave' : 'Remove';
?>
<button type="submit" class="btn btn-<?= $user_id == $member['id'] ? 'warning' : 'danger' ?> btn-sm" <?= $disabled ?>>
    <?= $btn_text ?>
</button>


                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
function filterMembers() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#membersTable tbody tr");

    rows.forEach(row => {
        let nameCell = row.cells[1].innerText.toLowerCase();
        row.style.display = nameCell.includes(input) ? "" : "none";
    });
}
</script>
</body>
</html>
