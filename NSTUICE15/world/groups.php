<?php
session_start();
include '../conn.php';


$user_id = $_SESSION['id'];
$result = mysqli_query($conn, "SELECT * FROM groups");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Groups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            zoom: 1.12;
            margin: 0;
            padding: 0;
            height: 100%;
            background: linear-gradient(120deg, #d1e3ff, #f0f4fb);
            font-family: 'Segoe UI', sans-serif;
        }
        .group-card {
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: 0.3s;
            overflow: hidden;
            transform: scale(1.05); /* replaces zoom */
            transform-origin: top left;
        }
        .group-card:hover {
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .cover-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">📘 All Groups</h2>
        <div>
            <a href="create_group.php" class="btn btn-success">➕ Create Group</a>
            <a href="group_admin_panel.php" class="btn btn-warning">🛠 Admin Panel</a>
            <a href="my_groups.php" class="btn btn-info">📋 My Groups</a>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search groups by name...">
    </div>

    <div class="row" id="groupContainer">
        <?php while ($row = mysqli_fetch_assoc($result)) {
            $gid = $row['id'];
            $group_name = htmlspecialchars($row['name']);
            $is_member = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id=$gid AND user_id=$user_id");
            $has_requested = mysqli_query($conn, "SELECT * FROM group_requests WHERE group_id=$gid AND user_id=$user_id");

            $cover_url = !empty($row['cover_image']) && file_exists("../" . $row['cover_image'])
                ? "../" . $row['cover_image']
                : "https://source.unsplash.com/random/300x150?sig=$gid&group";
        ?>
        <div class="col-md-6 col-lg-4 group-card-item">
            <div class="card group-card p-0 bg-white">
                <img src="<?= $cover_url ?>" class="cover-img" alt="Group Cover">
                <div class="p-3">
                    <h5 class="card-title group-title"><?= $group_name ?></h5>
                    <p class="card-text small text-muted">Group ID: <?= $gid ?></p>

                    <?php if (mysqli_num_rows($is_member)) { ?>
                        <span class="badge bg-success mb-2">✅ You are a member</span>
                    <?php } elseif (mysqli_num_rows($has_requested)) { ?>
                        <span class="badge bg-warning text-dark mb-2">⏳ Request Sent</span>
                    <?php } else { ?>
                        <button type="button"
                            class="btn btn-primary btn-sm w-100 mb-2"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmModal"
                            data-group-id="<?= $gid ?>">
                            Request to Join
                        </button>
                    <?php } ?>

                    <a href="group_page.php?group_id=<?= $gid ?>" class="btn btn-outline-secondary btn-sm w-100">👥 View Group</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    

    <div>
           <a href="world.php" class="btn btn-sm btn-secondary mt-4">← Back to Dashboard</a>
    <div>

 
</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="group_actions.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModalLabel">Confirm Join Request</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to request to join this group?
          <input type="hidden" name="action" value="request_join">
          <input type="hidden" name="group_id" id="modalGroupId">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Yes, Request</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal + Search JS -->
<script>
window.addEventListener("load", function () {
    const confirmModal = document.getElementById('confirmModal');
    if (confirmModal) {
        confirmModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const groupId = button.getAttribute('data-group-id');
            document.getElementById('modalGroupId').value = groupId;
        });
    }

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('.group-card-item').forEach(function (card) {
                const title = card.querySelector('.group-title').textContent.toLowerCase();
                card.style.display = title.includes(filter) ? '' : 'none';
            });
        });
    }
});
</script>
</body>
</html>
