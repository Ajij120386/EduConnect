<?php
// session_start();
include '../conn.php';

// include 'world_header1.php';

session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['name'])) {
    die("User not logged in.");
}


$user_id = $_SESSION['id'];
$username = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'add_post') {
        $content = mysqli_real_escape_string($conn, $_POST['content']);


       // ✅ Handle privacy
    $privacy = $_POST['privacy'] ?? 'public';

    // ✅ If user selected batch dropdown
    if ($privacy === 'batch_trigger') {
        $privacy = $_POST['batch_privacy'] ?? 'public';
    }


        $filename = '';

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $basename = basename($_FILES['file']['name']);
            $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $basename);
            $targetFile = $uploadDir . $filename;

            if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                $filename = '';
            }
        }

       mysqli_query($conn, "INSERT INTO user_posts (user_id, content, file_path, privacy) VALUES ($user_id, '$content', '$filename', '$privacy')");

       $post_id = mysqli_insert_id($conn); // Get ID of new post
$user_id = $_SESSION['id'];         // Poster ID
$poster_name = mysqli_real_escape_string($conn, $_SESSION['name']); // Safely escape the name

// Notify all other users about the new post
$users = mysqli_query($conn, "SELECT id FROM user_data WHERE id != $user_id");
while ($row = mysqli_fetch_assoc($users)) {
    $uid = $row['id'];
    $message = "A new post has been shared by $poster_name.";
    mysqli_query($conn, "
        INSERT INTO notifications (user_id, type, reference_id, message)
        VALUES ($uid, 'new_post', $post_id, '$message');
    ");
}




        
                exit("Posted");
    }

    if ($action == 'react') {
        $post_id = intval($_POST['post_id']);
        $type = $_POST['type'];
        mysqli_query($conn, "DELETE FROM user_post_likes WHERE post_id=$post_id AND user_id=$user_id");
        mysqli_query($conn, "INSERT INTO user_post_likes (post_id, user_id, type) VALUES ($post_id, $user_id, '$type')");
        $likes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM user_post_likes WHERE post_id=$post_id AND type='like'"))['c'];
        $dislikes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM user_post_likes WHERE post_id=$post_id AND type='dislike'"))['c'];
        echo json_encode(['likes' => $likes, 'dislikes' => $dislikes]);
        exit;
    }

    if ($action == 'comment') {
        $post_id = intval($_POST['post_id']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        mysqli_query($conn, "INSERT INTO user_post_comments (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment')");
        exit("Commented");
    }

    if ($action == 'delete_post') {
        $post_id = intval($_POST['post_id']);
        $post_author = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_id FROM user_posts WHERE id=$post_id"))['user_id'];
        if ($post_author == $user_id) {
            mysqli_query($conn, "DELETE FROM user_posts WHERE id=$post_id");
            mysqli_query($conn, "DELETE FROM user_post_likes WHERE post_id=$post_id");
            mysqli_query($conn, "DELETE FROM user_post_comments WHERE post_id=$post_id");
        }
        exit;
    }
}

if (isset($_GET['fetch_comments'])) {
    $post_id = intval($_GET['fetch_comments']);
    $q = mysqli_query($conn, "SELECT c.comment, CONCAT(u.first_name, ' ', u.last_name) as name , u.image
                              FROM user_post_comments c 
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
  <title>Group Feed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Inter', sans-serif;
      padding: 30px 15px;
        
    }
    .post-box, .post-card {
      
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      padding: 24px;
      border-radius: 20px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.05);
      margin-bottom: 25px;
       border: 1px solid #f57c00;
    }
    .user-avatar {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
      margin-right: 15px;
    }
    .comment-section {
      background: #f0f4f8;
      border-radius: 16px;
      padding: 16px;
      margin-top: 12px;
    }
    #search-input {
      border-radius: 16px;
      padding: 14px 20px;
      font-size: 1rem;
      margin-bottom: 25px;
      border: none;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    

    .post-card {
  background: #fff;
  border: 1px solid red;
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  transition: 0.3s;
    border: 1px solid #f57c00;
}
.post-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}

  .fb-comment {
  display: flex;
  align-items: flex-start;
  margin-bottom: 6px;
  font-size: 11px;
  position: relative;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.fb-comment:hover {
  transform: scale(1.02);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.fb-avatar {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  margin-right: 6px;
  object-fit: cover;
  flex-shrink: 0;
}

.fb-comment-body {

   background: #fff9db;

/* light cyan shades */
 /* soft lemon to amber */
/* soft lemon to amber */
 /* light cyan shades */
  /* nice light blue gradient */
  border-radius: 12px;
  padding: 6px 10px;
  max-width: 80%;
  position: relative;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

/* Bubble Tail (on the left) */
.fb-comment-body::after {
  content: '';
  position: absolute;
  left: -5px;
  bottom: 5px;
  width: 0;
  height: 0;
  border-top: 6px solid #e0f7fa;
  border-right: 6px solid transparent;
}

.fb-comment-name {
  font-weight: 600;
  font-size: 11px;
  margin-bottom: 2px;
}

.fb-comment-text {
  font-size: 11px;
  margin-bottom: 2px;
}

.fb-comment-footer {
  font-size: 10px;
  color: #888;
}

  </style>
</head>


<body class="container">

  <!-- Post Form -->
  <!-- Post Form -->
        <form id="post-form" enctype="multipart/form-data">
          <div class="post-box">
            
            <div class="d-flex align-items-center mb-3">
              <img src="../user_image/<?= htmlspecialchars($_SESSION['image'] ?? 'blank_pp.png') ?>" class="user-avatar" width="50">
            
             <div class="ms-2">
    <div class="fw-semibold" style="font-size: 15px;">
      <?= htmlspecialchars($_SESSION['name']) ?>
    </div>

   <!-- Styled Privacy Dropdown -->
    <select name="privacy" id="main-privacy" class="form-select form-select-sm privacy-select mt-1">
      <option value="public">🌍 Public</option>
     

      <option value="private">🔒 Only Me</option>
      <option value="batch_trigger">👥 Batch</option>

</select>
      <?php
// Fetch batch list from database
$batch_query = mysqli_query($conn, "SELECT DISTINCT batch_no FROM user_data WHERE batch_no IS NOT NULL AND batch_no != '' ORDER BY batch_no ASC");
?>
          <!-- Hidden batch options container -->
    <select id="batch-list" name="batch_privacy" class="form-select form-select-sm mt-2 d-none" style="width: 120px; font-size: 8px; border-radius: 15px;">
      <?php while ($row = mysqli_fetch_assoc($batch_query)): ?>
        <option value="<?= htmlspecialchars($row['batch_no']) ?>">👥 <?= htmlspecialchars($row['batch_no']) ?></option>
      <?php endwhile; ?>
    
    </select>
  </div>
              
            </div>
            <textarea name="content" class="form-control mb-2" rows="3" placeholder="What's on your mind?"></textarea>
            <input type="file" name="file" class="form-control mb-2" accept="image/*,.pdf,.doc,.docx">
            <input type="hidden" name="action" value="add_post">
            <button type="submit" class="btn btn-primary">➕ Post</button>
          </div>
        </form>


  <!-- Search -->
  <input type="text" id="search-input" class="form-control" placeholder="🔍 Search posts..." onkeyup="filterPosts()">

  <!-- Posts container -->
  <div id="posts"></div>

  <!-- Posts container -->
<div id="posts"></div>

<div id="load-more-wrapper" class="text-center mt-4">
  <button id="load-more" class="btn btn-outline-primary">⬇️ Load More</button>
</div>



  <script>
    

let offset = 0;
const limit = 5;

function fetchPosts(reset = false) {
  if (reset) {
    offset = 0;
    document.getElementById('posts').innerHTML = '';
    document.getElementById('load-more-wrapper').style.display = 'block';
  }

  fetch(`fetch_posts.php?offset=${offset}&limit=${limit}`)
    .then(res => res.text())
    .then(html => {
      if (html.trim()) {
        document.getElementById('posts').insertAdjacentHTML('beforeend', html);
        offset += limit;
      } else {
        document.getElementById('load-more-wrapper').style.display = 'none';
      }
    });
}



    document.addEventListener('DOMContentLoaded', () => {
      fetchPosts();
      document.getElementById('load-more').addEventListener('click', fetchPosts);
    });

    document.getElementById("post-form").addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch('', {
        method: 'POST',
        body: formData
      }).then(() => {
        this.reset();
        offset = 0;
        document.getElementById('posts').innerHTML = '';
        fetchPosts();
      });
    });

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
      section.style.display = section.style.display === 'none' ? 'block' : 'none';
      if (section.style.display === 'block') loadComments(postId);
    }

    function loadComments(postId) {
      fetch(`?fetch_comments=${postId}`)
        .then(res => res.json())
        .then(data => {
          let out = '';
          data.forEach(c => {


          out += `
  <div class="fb-comment">
    <img src="../user_image/${c.image || 'blank_pp.png'}" class="fb-avatar" alt="">
    <div class="fb-comment-body">
      <div class="fb-comment-name">${c.name}</div>
      <div class="fb-comment-text">${c.comment}</div>
      
    </div>
  </div>
`;


          });
          document.getElementById(`comment-list-${postId}`).innerHTML = out;
          document.getElementById(`cmt-${postId}`).innerText = data.length;
        });
    }

    function submitComment(postId) {
      let comment = document.getElementById(`cmt-in-${postId}`).value.trim();
      if (!comment) return;
      fetch('', {
        method: 'POST',
        body: new URLSearchParams({ action: 'comment', post_id: postId, comment })
      }).then(() => {
        document.getElementById(`cmt-in-${postId}`).value = '';
        loadComments(postId);
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
      
      document.getElementById("load-more-wrapper").style.display = val ? "none" : "block";

      let val = document.getElementById("search-input").value.toLowerCase();
      document.querySelectorAll(".post-card").forEach(post => {
        const text = post.innerText.toLowerCase();
        post.style.display = text.includes(val) ? '' : 'none';
      });
    }



    //  JavaScript to Toggle Batch List
 document.getElementById('main-privacy').addEventListener('change', function() {
  const batchDropdown = document.getElementById('batch-list');
  batchDropdown.classList.toggle('d-none', this.value !== 'batch_trigger');
});



  </script>
</body>
</html>
