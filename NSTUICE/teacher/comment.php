<?php
session_start();
include '../conn.php';

if (isset($_POST['post_id']) && isset($_POST['value'])) {
    $time = date("Y-m-d H:i:s");
    $date = date("Y-m-d");
    $post_id = trim($_POST['post_id']);
    $comment = trim($_POST['value']);
    $comment_by = $_SESSION['id'];

    // Insert comment
    $comment_insert_sql = "INSERT INTO `comment` (`comment`,`comment_by`,`comment_to`) VALUES ('$comment','$comment_by','$post_id')";
    $comment_insert_result = mysqli_query($conn, $comment_insert_sql);

    // Update post's commented_by
    $comment_by_insert_sql = "UPDATE `posts` SET `commented_by` = IFNULL(concat(`commented_by`,',$comment_by'),',$comment_by') WHERE `id`=$post_id";
    mysqli_query($conn, $comment_by_insert_sql);

    // Get posted_by from posts
    $commented_by_sql = "SELECT `commented_by`, `posted_by` FROM `posts` WHERE `id`='$post_id'";
    $commented_by_result = mysqli_query($conn, $commented_by_sql);
    
    if (!$commented_by_result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    $row = mysqli_fetch_assoc($commented_by_result);
    $posted_by = $row['posted_by'];
    $commented_by = $row['commented_by'];

    // Insert into comment_notification
    $comment_notification_sql = "INSERT INTO `comment_notification` (`commented_to`,`commented_by`,`time`,`date`) VALUES ('$post_id','$comment_by','$time','$date')";
    mysqli_query($conn, $comment_notification_sql);

    // Get last inserted notification id
    $comment_notification_fetch_sql = "SELECT * FROM `comment_notification` ORDER BY `id` DESC LIMIT 1";
    $result = mysqli_query($conn, $comment_notification_fetch_sql);

    if (!$result) {
        echo "Error fetching notification: " . mysqli_error($conn);
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    $last_comment_id = $row['id'];

    // Notify post owner
    if ($posted_by != $comment_by) {
        mysqli_query($conn, "UPDATE `user_data` SET `comment_notification` = IFNULL(concat(`comment_notification`,',$last_comment_id'),',$last_comment_id') WHERE `id`=$posted_by");
        mysqli_query($conn, "UPDATE `user_data` SET `unseen_notification` = IFNULL(concat(`unseen_notification`,',$last_comment_id'),',$last_comment_id') WHERE `id`=$posted_by");
    }

    // Notify other commenters
    $commented_by_array = array_unique(array_filter(explode(',', $commented_by)));
    foreach ($commented_by_array as $user) {
        if ($user != $comment_by && $user != $posted_by) {
            mysqli_query($conn, "UPDATE `user_data` SET `comment_notification` = IFNULL(concat(`comment_notification`,',$last_comment_id'),',$last_comment_id') WHERE `id`=$user");
            mysqli_query($conn, "UPDATE `user_data` SET `unseen_notification` = IFNULL(concat(`unseen_notification`,',$last_comment_id'),',$last_comment_id') WHERE `id`=$user");
        }
    }

    echo "Comment added";
}
?>
