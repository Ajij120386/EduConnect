<?php
session_start();
include '../conn.php';

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $folder = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_POST['folder']);

    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $upload_dir = "uploads/$folder/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $filename = time() . '_' . basename($_FILES['pdf']['name']);
        $target = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['pdf']['tmp_name'], $target)) {
            $db_path = "$folder/$filename";
            mysqli_query($conn, "INSERT INTO note (title, filename) VALUES ('$title', '$db_path')");
            $success = "PDF uploaded successfully to folder: $folder.";
        } else {
            $error = "Failed to upload file.";
        }
    } else {
        $error = "No file uploaded or upload error.";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = mysqli_query($conn, "SELECT filename FROM note WHERE id=$id");
    if ($row = mysqli_fetch_assoc($res)) {
        unlink("uploads/" . $row['filename']);
        mysqli_query($conn, "DELETE FROM note WHERE id=$id");
        $success = "Note deleted.";
    }
}

// Handle edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
    $id = (int)$_POST['note_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    mysqli_query($conn, "UPDATE note SET title='$title' WHERE id=$id");
    $success = "Note updated.";
}

// Filter search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where = $search ? "WHERE title LIKE '%$search%'" : '';
$all_notes = mysqli_query($conn, "SELECT * FROM note $where ORDER BY uploaded_at DESC");

// Group notes by folder
$note_map = [];
while ($row = mysqli_fetch_assoc($all_notes)) {
    $parts = explode('/', $row['filename']);
    $folder = $parts[0];
    $note_map[$folder][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Note Library</title>
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
            background: linear-gradient(to right, #f0f2f5, #dfe9f3);
        }
        .container {
            max-width: 900px;
        }
        .card{

            border: 1px solid #f57c00;
        }
        .card-header h5 {
            
            margin: 0;
        }
        .form-label {
            font-weight: 600;
        }

         .top-bar {
            background: linear-gradient(90deg, #4f46e5, #3b82f6);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 10px;
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
            max-width: 1274px;
            margin: auto;
        }


    </style>
</head>
<body>
<div class="container py-5">

     <div class="contain mt-4 dashboard-wrapper">

   <div class="d-flex justify-content-between align-items-center top-bar">
        <div class="fs-5 fw-bold">Personal Note Library</div>
        <a href="dashboard.php" class="btn btn-back px-3">← Back to Main Site</a>
        
    </div>
</div>


    <?php if (isset($success)): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <!-- Upload Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-semibold">Upload a New Note</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload">
                <div class="mb-3">
                    <label class="form-label">Note Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Folder Name</label>
                    <input type="text" name="folder" class="form-control" required placeholder="e.g., CSE101, Math, PersonalNotes">
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload PDF</label>
                    <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
                </div>
                <button type="submit" class="btn btn-success w-100">📤 Upload PDF</button>
            </form>
        </div>
    </div>

    <!-- Search -->
    <form method="GET" class="mb-4" id="searchForm">
        <div class="input-group">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="🔍 Search notes..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <!-- Folder-wise Note Display -->
    <h4 class="mb-3 text-primary">📁 Uploaded Notes by Folder</h4>
    <?php if (empty($note_map)): ?>
        <p class="text-muted text-center">No notes uploaded yet.</p>
    <?php else: ?>
        <?php foreach ($note_map as $folder => $notes): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📂 <?= htmlspecialchars($folder) ?></h5>
                    <a href="uploads/<?= urlencode($folder) ?>" target="_blank" class="btn btn-sm btn-light">Open Folder</a>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($notes as $row): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <form method="POST" class="d-flex flex-grow-1 me-2">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="note_id" value="<?= $row['id'] ?>">
                                <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control me-2">
                                <button class="btn btn-sm btn-outline-success" type="submit">Save</button>
                            </form>
                            <a href="uploads/<?= $row['filename'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this note?')" class="btn btn-sm btn-outline-danger ms-2">Delete</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
// Auto-submit form on search input typing
const searchInput = document.getElementById('searchInput');
const searchForm = document.getElementById('searchForm');

searchInput.addEventListener('input', () => {
    clearTimeout(searchInput._timeout);
    searchInput._timeout = setTimeout(() => {
        searchForm.submit();
    }, 500); // delay to avoid spamming
});
</script>
</body>
</html>
