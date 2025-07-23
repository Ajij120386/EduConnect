<?php
session_start();
include '../conn.php';


$uid = $_SESSION['id'];

$manage = mysqli_query($conn, "SELECT id, name, cover_image FROM groups WHERE created_by = $uid");
$joined = mysqli_query($conn, "SELECT g.id, g.name, g.cover_image FROM group_members gm JOIN groups g ON gm.group_id = g.id WHERE gm.user_id = $uid AND g.created_by != $uid");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Groups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(120deg, #d1e3ff, #f0f4fb);
            font-family: 'Segoe UI', sans-serif;
        }
        .container-custom {
            max-width: 900px;
            margin: auto;
            padding-top: 40px;
        }
        .group-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        .group-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .group-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            text-decoration: none;
        }
        .group-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            background-color: #ddd;
        }
        .group-info {
            flex-grow: 1;
        }
        .group-name {
            font-weight: 600;
            font-size: 16px;
        }
        .group-meta {
            font-size: 13px;
            color: #666;
        }
        .section-title {
            margin-top: 40px;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="container container-custom">
    <h4 class="fw-bold mb-3">📘 My Groups</h4>
    <input type="text" class="form-control mb-4" id="searchInput" placeholder="🔍 Search groups by name...">

    <div>
        <div class="section-title">Groups you manage</div>
        <div class="group-grid" id="manageList">
            <?php while ($row = mysqli_fetch_assoc($manage)) {
                $gid = $row['id'];
                $name = htmlspecialchars($row['name']);
                $cover = $row['cover_image'] && file_exists("../" . $row['cover_image']) ? "../" . $row['cover_image'] : "https://source.unsplash.com/40x40/?group&sig=$gid";
                echo "<a href='group_page.php?group_id=$gid' class='group-card group-item' data-name='$name'>
                    <img src='$cover' class='group-img' alt='Group'>
                    <div class='group-info'>
                        <div class='group-name'>$name</div>
                        <div class='group-meta'>You are admin</div>
                    </div>
                </a>";
            } ?>
        </div>
    </div>

    <div>
        <div class="section-title">Groups you've joined</div>
        <div class="group-grid" id="joinedList">
            <?php while ($row = mysqli_fetch_assoc($joined)) {
                $gid = $row['id'];
                $name = htmlspecialchars($row['name']);
                $cover = $row['cover_image'] && file_exists("../" . $row['cover_image']) ? "../" . $row['cover_image'] : "https://source.unsplash.com/40x40/?group&sig=$gid";
                echo "<a href='group_page.php?group_id=$gid' class='group-card group-item' data-name='$name'>
                    <img src='$cover' class='group-img' alt='Group'>
                    <div class='group-info'>
                        <div class='group-name'>$name</div>
                        <div class='group-meta'>Member</div>
                    </div>
                </a>";
            } ?>
        </div>
    </div>

    <a href="groups.php" class="btn btn-secondary mt-5">← Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    const filter = this.value.toLowerCase();
    document.querySelectorAll(".group-item").forEach(item => {
        const name = item.getAttribute("data-name").toLowerCase();
        item.style.display = name.includes(filter) ? "" : "none";
    });
});
</script>
</body>
</html>
