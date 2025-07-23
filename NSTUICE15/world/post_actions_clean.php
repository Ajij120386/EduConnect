<?php
include '../conn.php';
session_start();

$user_id = $_SESSION['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'add_post') {
        $content = mysqli_real_escape_string($conn, $_POST['content']);
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

        mysqli_query($conn, "INSERT INTO user_posts (user_id, content, file_path) VALUES ($user_id, '$content', '$filename')");
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
    $q = mysqli_query($conn, "SELECT c.comment, CONCAT(u.first_name, ' ', u.last_name) as name, u.image

     

                              FROM user_post_comments c 
                              JOIN user_data u ON u.id=c.user_id 
                              WHERE post_id=$post_id 
                              ORDER BY c.created_at ASC");
    $data = [];
    while ($r = mysqli_fetch_assoc($q)) $data[] = $r;
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>
