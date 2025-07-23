<?php
include '../conn.php';
session_start();

if (!isset($_SESSION['id'])) {

    die("User not logged in.");
}


$user_id = $_SESSION['id'];

$user_batch = $_SESSION['batch_no'] ?? '';  // avoid undefined warning


$offset = intval($_GET['offset'] ?? 0);
$limit = intval($_GET['limit'] ?? 5);

$posts = mysqli_query($conn, "
  SELECT * FROM user_posts 
  WHERE 
    user_id = $user_id
    OR privacy = 'public'
    OR privacy = 'friends'
    OR privacy = '$user_batch'
    OR (privacy = 'private' AND user_id = $user_id)
  ORDER BY created_at DESC 
  LIMIT $limit OFFSET $offset
");



while ($post = mysqli_fetch_assoc($posts)):
    $post_id = $post['id'];
    $post_user_id = $post['user_id'];

    $post_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT(first_name, ' ', last_name) as full_name, image FROM user_data WHERE id = $post_user_id"));
    $post_author_name = $post_user['full_name'] ?? 'Unknown';
    $post_author_image = '../user_image/' . ($post_user['image'] ?? 'blank_pp.png');
    $is_owner = ($post_user_id == $user_id);

    $file_path = $post['file_path'] ?? '';
    $file_ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif']);

    $likes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM user_post_likes WHERE post_id=$post_id AND type='like'"))['c'];
    $dislikes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM user_post_likes WHERE post_id=$post_id AND type='dislike'"))['c'];
    $comments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM user_post_comments WHERE post_id=$post_id"))['c'];
?>

<br>
<br>

<div class="post-card" id="post-<?= $post_id ?>">
  <div class="d-flex justify-content-between align-items-start mb-2">
    <div class="d-flex align-items-center">
      <img src="<?= htmlspecialchars($post_author_image) ?>" class="user-avatar">
      <div>
        <strong><?= htmlspecialchars($post_author_name) ?></strong><br>
        <small class="text-muted"><?= date('h:i A | d F Y', strtotime($post['created_at'])) ?></small>
      </div>
    </div>
    <?php if ($is_owner): ?>
      <button class="btn btn-sm btn-outline-danger" onclick="deletePost(<?= $post_id ?>)">❌</button>
    <?php endif; ?>
  </div>

  <br>
  
  <p class="fs-6 mb-3"><?= nl2br(htmlspecialchars($post['content'])) ?></p>



  <?php if ($file_path): ?>
    <?php if ($is_image): ?>
      <img src="../uploads/<?= htmlspecialchars($file_path) ?>" class="img-fluid rounded mb-3">
    <?php else: ?>
      <a href="../uploads/<?= htmlspecialchars($file_path) ?>" class="btn btn-sm btn-outline-secondary mb-3" target="_blank">📎 Download File</a>
    <?php endif; ?>
  <?php endif; ?>

  <br>

  <div class="d-flex justify-content-around border-top border-bottom py-2">
    <button class="btn btn-sm btn-outline-primary" onclick="react(<?= $post_id ?>, 'like')">
      👍 Like <span id="like-<?= $post_id ?>"><?= $likes ?></span>
    </button>
    <button class="btn btn-sm btn-outline-danger" onclick="react(<?= $post_id ?>, 'dislike')">
      👎 Dislike <span id="dislike-<?= $post_id ?>"><?= $dislikes ?></span>
    </button>
    <button class="btn btn-sm btn-outline-info" onclick="toggleComments(<?= $post_id ?>)">
      💬 Comments <span id="cmt-<?= $post_id ?>"><?= $comments ?></span>
    </button>
  </div>
 <br>
  <div class="comment-section" id="comments-<?= $post_id ?>" style="display:none;">
    <div id="comment-list-<?= $post_id ?>" class="mb-2">Loading comments...</div>
    <textarea id="cmt-in-<?= $post_id ?>" class="form-control mb-2" placeholder="Write a comment..."></textarea>
    <button class="btn btn-sm btn-success" onclick="submitComment(<?= $post_id ?>)">Send</button>
  </div>
</div>
<?php endwhile; ?>
