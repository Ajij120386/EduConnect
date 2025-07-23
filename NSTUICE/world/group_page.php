<?php
session_start();
include '../conn.php';


if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = 1;
    $_SESSION['name'] = "Demo User";
}

$user_id = $_SESSION['id'];
$username = $_SESSION['name'];

if (!isset($_GET['group_id'])) die("Group ID missing in URL.");
$group_id = intval($_GET['group_id']);

$check = mysqli_query($conn, "SELECT id FROM group_members WHERE user_id=$user_id AND group_id=$group_id");
if (mysqli_num_rows($check) == 0) exit("You must be a group member to view this group.");

$group_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, cover_image, created_by FROM groups WHERE id = $group_id"));
$is_group_admin = ($group_info['created_by'] == $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

   if ($action == 'add_post') {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $filename = '';

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $originalName = basename($_FILES['file']['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $safeName = time() . '_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $originalName);
        $targetPath = $uploadDir . $safeName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            $filename = $safeName;
        }
    }

    mysqli_query($conn, "INSERT INTO group_posts (group_id, user_id, content, file_path) VALUES ($group_id, $user_id, '$content', '$filename')");
    exit("Posted");
}

    if ($action == 'react') {
        $post_id = intval($_POST['post_id']);
        $type = $_POST['type'];
        mysqli_query($conn, "DELETE FROM group_post_likes WHERE post_id=$post_id AND user_id=$user_id");
        mysqli_query($conn, "INSERT INTO group_post_likes (post_id, user_id, type) VALUES ($post_id, $user_id, '$type')");
        $likes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM group_post_likes WHERE post_id=$post_id AND type='like'"))['c'];
        $dislikes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM group_post_likes WHERE post_id=$post_id AND type='dislike'"))['c'];
        echo json_encode(['likes' => $likes, 'dislikes' => $dislikes]);
        exit;
    }
    if ($action == 'comment') {
        $post_id = intval($_POST['post_id']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        mysqli_query($conn, "INSERT INTO group_post_comments (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment')");
        exit("Commented");
    }
    if ($action == 'delete_post') {
        $post_id = intval($_POST['post_id']);
        $post_author = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM group_posts WHERE id=$post_id"))['user_id'];
        if ($post_author == $user_id || $is_group_admin) {
            mysqli_query($conn, "DELETE FROM group_posts WHERE id=$post_id");
            mysqli_query($conn, "DELETE FROM group_post_likes WHERE post_id=$post_id");
            mysqli_query($conn, "DELETE FROM group_post_comments WHERE post_id=$post_id");
        }
        exit;
    }
}

if (isset($_GET['fetch_comments'])) {
    $post_id = intval($_GET['fetch_comments']);
    $q = mysqli_query($conn, "SELECT c.comment, CONCAT(u.first_name, ' ', u.last_name) as name 
                              FROM group_post_comments c 
                              JOIN user_data u ON u.id=c.user_id 
                              WHERE post_id=$post_id 
                              ORDER BY c.created_at ASC");
    $data = [];
    while ($r = mysqli_fetch_assoc($q)) $data[] = $r;
    echo json_encode($data);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($group_info['name']) ?> | Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
/* Enhanced Group Post Styles */
<style>
body {
    background-color: #f1f5f9;
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    margin-top: 2px;
}

.container {
    width: 100%;
    max-width: 960px;
    padding: 0 20px;
    box-sizing: border-box;
}

.post-box, .post-card {
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
    padding: 24px;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
        border: 1px solid #f57c00;
}

.post-card:hover {
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
}

.post-card img {
    border-radius: 8px;
}

.post-card p {
    color: #2c3e50;
    font-size: 16px;
    line-height: 1.6;
}

textarea.form-control,
input[type="text"]#search-input {
    border-radius: 12px;
    padding: 12px;
    font-size: 15px;
    border: 1px solid #ced4da;
    transition: border-color 0.2s;
}

textarea.form-control:focus,
input[type="text"]#search-input:focus {
    border-color: #5c9ded;
    outline: none;
    box-shadow: 0 0 0 3px rgba(92, 157, 237, 0.2);
}

.comment-section {
    background-color: #f9fafb;
    border-radius: 12px;
    padding: 15px;
    margin-top: 10px;
    border: 1px solid #e2e8f0;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 6px 14px;
}

.btn:hover {
    opacity: 0.95;
}

.group-nav .btn {
    margin-right: 10px;
    font-weight: 600;
    border-radius: 20px;
    padding: 8px 20px;
    transition: all 0.3s ease;
}

.group-nav .btn:hover {
    background-color: #0d6efd;
    color: #ffffff;
}
</style>

</head>
<body>
    
<br>
<br>


<div class="container">
  
<div class="position-relative mb-4 shadow rounded overflow-hidden" style="height: 280px;">
    <!-- Back Button -->
    <a href="javascript:history.back()" 
       class="btn btn-light position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill shadow-sm fw-semibold">
        🔙 Back
    </a>

    <!-- Group Cover Image -->
    <img src="../<?= $group_info['cover_image'] ?>" 
         class="w-100 h-100 object-fit-cover" 
         style="object-fit: cover;" 
         alt="Group Cover">

    <!-- Overlay Text -->
    <div class="position-absolute bottom-0 start-0 w-100 p-4" 
         style="background: linear-gradient(to top, rgba(0,0,0,0.6), rgba(0,0,0,0));">
        <h2 class="text-white fw-bold"><?= htmlspecialchars($group_info['name']) ?></h2>
        <p class="text-white-50 mb-0">
            Private group • <?= mysqli_num_rows(mysqli_query($conn, "SELECT id FROM group_members WHERE group_id = $group_id")) ?> members
        </p>
    </div>
</div>




    <div class="d-flex justify-content-start mb-4 group-nav">
        <a href="group_page.php?group_id=<?= $group_id ?>" class="btn btn-primary me-2 px-4 py-2">🗨️ Discussion</a>
        <a href="group_members.php?group_id=<?= $group_id ?>" class="btn btn-outline-secondary px-4 py-2">👥 Members</a>
    </div>

    <div class="post-box">
        <div class="d-flex align-items-center mb-3">
            <img src="../user_image/<?php echo htmlspecialchars($_SESSION['image'] ?? 'blank_pp.png'); ?>" class="rounded-circle me-3" width="45" height="45" alt="User">
            <strong class="text-dark fs-5"><?= htmlspecialchars($username) ?></strong>
        </div>

       <textarea id="new-post" class="form-control mb-2" rows="3" placeholder="What's on your mind?"></textarea>
<input type="file" id="file-input" class="form-control mb-2" accept=".jpg,.jpeg,.png,.gif,.pdf">
<button class="btn btn-success px-4" onclick="addPost()">Post</button>


    </div>

    <div class="mb-4">
        <input type="text" id="search-input" class="form-control" placeholder="🔍 Search posts by content or author..." onkeyup="filterPosts()">
    </div>


    <div id="posts">
        <?php
        $posts = mysqli_query($conn, "SELECT * FROM group_posts WHERE group_id=$group_id ORDER BY created_at DESC");
        while ($post = mysqli_fetch_assoc($posts)):
            $post_id = $post['id'];
            $post_user_id = $post['user_id'];
            $post_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT(first_name, ' ', last_name) as full_name, image FROM user_data WHERE id = $post_user_id"));
            $post_author_name = $post_user['full_name'] ?? 'Unknown';
            $post_author_image = '../user_image/' . ($post_user['image'] ?? 'blank_pp.png');
            $is_owner = ($post_user_id == $user_id || $is_group_admin);
        ?>
        <div class="post-card" id="post-<?= $post_id ?>">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center">
                    <img src="<?= htmlspecialchars($post_author_image) ?>" class="rounded-circle me-2" width="50" height="50" alt="User">
                    <div>
                        <strong><?= htmlspecialchars($post_author_name) ?></strong><br>
                        <small class="text-muted"><?= date('h:i A | d F Y', strtotime($post['created_at'])) ?></small>
                    </div>
                </div>
                <?php if ($is_owner): ?>
                <div>
                   <button class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center" 
        style="width: 32px; height: 32px;" 
        onclick="deletePost(<?= $post_id ?>)" 
        title="Delete">
    ❌
</button>

                </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
    <p class="fs-6 mb-0"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

    <?php if (!empty($post['file_path'])): ?>
        <div class="mt-2">
            <?php
                $file_url = '../uploads/' . $post['file_path'];
                $ext = pathinfo($post['file_path'], PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo "<img src='$file_url' alt='Uploaded Image' style='max-width:100%; height:auto; border-radius:8px;'/>";
                } elseif (strtolower($ext) === 'pdf') {
                    echo "<a href='$file_url' target='_blank' class='btn btn-sm btn-outline-dark mt-2'>📄 View PDF</a>";
                }
            ?>
        </div>
    <?php endif; ?>
</div>


            <div class="d-flex justify-content-around py-2 border-top border-bottom">
                <button class="btn btn-sm btn-outline-primary" onclick="react(<?= $post_id ?>, 'like')">
                    👍 Like <span id="like-<?= $post_id ?>">0</span>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="react(<?= $post_id ?>, 'dislike')">
                    👎 Dislike <span id="dislike-<?= $post_id ?>">0</span>
                </button>
                <button class="btn btn-sm btn-outline-info" onclick="toggleComments(<?= $post_id ?>)">
                   <?php
                   
    $comment_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM group_post_comments WHERE post_id = $post_id"))['c'];
?>
💬 Comments <span id="cmt-<?= $post_id ?>"><?= $comment_count ?></span>

                </button>
            </div>

            <div id="comments-<?= $post_id ?>" class="comment-section">
                <div id="comment-list-<?= $post_id ?>" class="mt-3"></div>
                <textarea id="cmt-in-<?= $post_id ?>" class="form-control mt-2" placeholder="Write a comment..."></textarea>
                <button class="btn btn-sm btn-success mt-2" onclick="submitComment(<?= $post_id ?>)">Send</button>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</div>


<script>

function addPost() {
    let content = document.getElementById("new-post").value;
    let fileInput = document.getElementById("file-input");
    let formData = new FormData();

    formData.append("action", "add_post");
    formData.append("content", content);

    if (fileInput.files.length > 0) {
        formData.append("file", fileInput.files[0]);
    }

    fetch('', {
        method: 'POST',
        body: formData
    }).then(() => location.reload());
}


function react(postId, type) {
    fetch('', {
        method: 'POST',
        body: new URLSearchParams({ action: 'react', post_id: postId, type: type })
    }).then(res => res.json()).then(data => {
        document.getElementById(`like-${postId}`).innerText = data.likes;
        document.getElementById(`dislike-${postId}`).innerText = data.dislikes;
    });
}

function toggleComments(postId) {
    const section = document.getElementById(`comments-${postId}`);
    const isHidden = section.style.display === "none" || section.style.display === "";
    section.style.display = isHidden ? "block" : "none";
    if (isHidden) loadComments(postId, true);
}

function loadComments(postId, forceOpen = false) {
    if (forceOpen) {
        document.getElementById(`comments-${postId}`).style.display = "block";
    }
    const currentUrl = new URL(window.location.href);
    const groupId = currentUrl.searchParams.get('group_id');
    fetch(`?group_id=${groupId}&fetch_comments=${postId}`)
        .then(res => res.json())
        .then(data => {
            let output = '';
            data.forEach(c => {
                output += `<p class="ms-2 small"><strong>${c.name}</strong>: ${c.comment}</p>`;
            });
            document.getElementById(`comment-list-${postId}`).innerHTML = output;
            document.getElementById(`cmt-${postId}`).innerText = data.length;
        });
}

function submitComment(postId) {
    let comment = document.getElementById(`cmt-in-${postId}`).value.trim();
    if (comment === "") return;
    fetch('', {
        method: 'POST',
        body: new URLSearchParams({ action: 'comment', post_id: postId, comment: comment })
    }).then(() => {
        document.getElementById(`cmt-in-${postId}`).value = "";
        loadComments(postId, true);
    });
}

function deletePost(postId) {
    if (confirm("Delete this post?")) {
        fetch('', {
            method: 'POST',
            body: new URLSearchParams({ action: 'delete_post', post_id: postId })
        }).then(() => document.getElementById(`post-${postId}`).remove());
    }
}



function filterPosts() {
    let input = document.getElementById("search-input").value.toLowerCase();
    let posts = document.querySelectorAll(".post-card");

    posts.forEach(post => {
        const content = post.querySelector("p.fs-6").innerText.toLowerCase();
        const author = post.querySelector("strong").innerText.toLowerCase();
        post.style.display = (content.includes(input) || author.includes(input)) ? "" : "none";
    });
}


</script>

</body>
</html>
