<?php
session_start();
include '../conn.php';
$group_id = $_POST['group_id'];
$user_id = $_POST['user_id'];
if ($_SESSION['id'] == $user_id) {
    echo "You cannot remove yourself!";
    exit;
}
mysqli_query($conn, "DELETE FROM group_members WHERE group_id=$group_id AND user_id=$user_id");
header("Location: group_members.php?group_id=$group_id");
