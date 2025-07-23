<?php
session_start();
include '../conn.php';



if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['id'];

// Fetch users
$users = mysqli_query($conn, "SELECT id, user_name, first_name, last_name, batch_no FROM user_data WHERE id != $current_user");

// Fetch distinct batch numbers
$batches_result = mysqli_query($conn, "SELECT DISTINCT batch_no FROM user_data WHERE id != $current_user AND batch_no IS NOT NULL AND batch_no != ''");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {

            zoom: 1.21;
      margin: 0;
      padding: 0;
      height: 100%;
      background: linear-gradient(120deg, #d1e3ff, #f0f4fb);
      font-family: 'Segoe UI', sans-serif
      background-color: #f0f2f5; 
      font-size: 16px;

             }
        .card { 
               border: 1px solid #f57c00;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            border-radius: 8px;
         }
        .member-box { 
            display: flex; align-items: center; gap: 10px; padding: 10px; background-color: #fff; border: 1px solid #e1e1e1; border-radius: 6px; margin-bottom: 10px; }
        .member-list { max-height: 300px; overflow-y: auto; }
        .batch-checkbox { margin-right: 10px; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4">
      <h3 class="mb-4 text-center text-primary fw-bold">
    <span class="bg-light px-3 py-2 rounded-pill shadow-sm d-inline-block">
        ➕ Create Group
    </span>
</h3>

        <form action="group_actions.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="create_group_with_members">

            <!-- Group Name -->
            <div class="mb-3">
                <label class="form-label">Group Name</label>
                <input type="text" name="group_name" class="form-control" required>
            </div>

            <!-- Cover Image -->
            <div class="mb-3">
                <label class="form-label">Group Cover Image</label>
                <input type="file" name="cover_image" accept="image/*" class="form-control">
            </div>

            <!-- Batch Multi Select -->
            <div class="mb-3">
                <label class="form-label">Select Batches (Multi)</label><br>
                <?php
                mysqli_data_seek($batches_result, 0);
                while ($batch = mysqli_fetch_assoc($batches_result)) {
                    $batch_no = htmlspecialchars($batch['batch_no']);
                    echo "<label class='form-check-inline'><input type='checkbox' class='form-check-input batch-checkbox' value='$batch_no'> Batch $batch_no</label>";
                }
                ?>
            </div>

            <!-- Action Buttons -->
            <div class="mb-3 d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm" id="selectAllBtn">Select All</button>
                <button type="button" class="btn btn-danger btn-sm" id="clearAllBtn">Clear All</button>
            </div>

            <!-- Member Selection -->
            <div class="mb-3">
                <label class="form-label">Select Members</label>
                <div class="member-list" id="memberList">
                    <?php while ($user = mysqli_fetch_assoc($users)) {
                        $uid = $user['id'];
                        $name = htmlspecialchars("{$user['first_name']} {$user['last_name']}");
                        $username = htmlspecialchars($user['user_name']);
                        $batch_no = htmlspecialchars($user['batch_no']);
                        echo "
                        <div class='member-box' data-batch='$batch_no'>
                            <input type='checkbox' name='members[]' value='$uid' class='member-checkbox' style='transform: scale(1.3); margin-right: 10px;'>
                            <div>
                                <strong>$name</strong><br>
                                <small class='text-muted'>$username (Batch: $batch_no)</small>
                            </div>
                        </div>
                        ";
                    } ?>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100">Create Group</button>
        </form>

        <!-- Back Link -->
        <div class="mt-3">
            <a href="groups.php" class="btn btn-secondary">← Back to Groups</a>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    const batchCheckboxes = document.querySelectorAll(".batch-checkbox");
    const memberBoxes = document.querySelectorAll(".member-box");

    batchCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", () => {
            filterAndSelect();
        });
    });

    function filterAndSelect() {
        const selectedBatches = Array.from(batchCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        memberBoxes.forEach(box => {
            const batch = box.getAttribute("data-batch");
            const checkbox = box.querySelector(".member-checkbox");

            if (selectedBatches.includes(batch)) {
                box.style.display = "flex";
                checkbox.checked = true;
            } else if (selectedBatches.length === 0) {
                box.style.display = "flex";
                checkbox.checked = false;
            } else {
                box.style.display = "none";
                checkbox.checked = false;
            }
        });
    }

    document.getElementById("selectAllBtn").addEventListener("click", () => {
        document.querySelectorAll(".member-checkbox").forEach(cb => cb.checked = true);
    });

    document.getElementById("clearAllBtn").addEventListener("click", () => {
        document.querySelectorAll(".member-checkbox").forEach(cb => cb.checked = false);
    });
</script>
</body>
</html>
