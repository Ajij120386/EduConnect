<?php
session_start();
include '../conn.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'create_group_with_members') {
        $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
        $created_by = $_SESSION['id'];
        $members = isset($_POST['members']) ? $_POST['members'] : [];

        // Handle Cover Image Upload
        $cover_path = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $filename = time() . '_' . basename($_FILES['cover_image']['name']);
            $target_file = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                $cover_path = 'uploads/' . $filename;
            }
        }

        // Save group
        $query = "INSERT INTO groups (name, created_by, cover_image) VALUES ('$group_name', $created_by, '$cover_path')";
        mysqli_query($conn, $query);
        $group_id = mysqli_insert_id($conn);

        // Add members
        mysqli_query($conn, "INSERT INTO group_members (group_id, user_id) VALUES ($group_id, $created_by)");
        foreach ($members as $uid) {
            $uid = (int)$uid;
            mysqli_query($conn, "INSERT INTO group_members (group_id, user_id) VALUES ($group_id, $uid)");
        }

        header("Location: my_groups.php");
    }

    elseif ($action == 'request_join') {
        $gid = $_POST['group_id'];
        $uid = $_SESSION['id'];
        
       $check = mysqli_query($conn, "SELECT * FROM group_requests WHERE group_id=$gid AND user_id=$uid");

if ($check && mysqli_num_rows($check) === 0) {
    mysqli_query($conn, "INSERT INTO group_requests (group_id, user_id) VALUES ($gid, $uid)");
}

        header("Location: groups.php");
    }

    elseif ($action == 'accept_request') {
        $rid = $_POST['request_id'];
        $req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM group_requests WHERE id=$rid"));
        mysqli_query($conn, "UPDATE group_requests SET status='accepted' WHERE id=$rid");
        mysqli_query($conn, "INSERT INTO group_members (group_id, user_id) VALUES ({$req['group_id']}, {$req['user_id']})");
        header("Location: group_admin_panel.php");
    }

    elseif ($action == 'add_member') {
        $gid = $_POST['group_id'];
        $uid = $_POST['user_id'];
        $exists = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id=$gid AND user_id=$uid");
        if (!mysqli_num_rows($exists)) {
            mysqli_query($conn, "INSERT INTO group_members (group_id, user_id) VALUES ($gid, $uid)");
        }
        header("Location: group_admin_panel.php");
    }

    elseif ($action == 'add_multiple_members') {
        $gid = $_POST['group_id'];
        $user_ids = $_POST['user_ids'] ?? [];
        foreach ($user_ids as $uid) {
            $uid = (int)$uid;
            $exists = mysqli_query($conn, "SELECT * FROM group_members WHERE group_id=$gid AND user_id=$uid");
            if (!mysqli_num_rows($exists)) {
                mysqli_query($conn, "INSERT INTO group_members (group_id, user_id) VALUES ($gid, $uid)");
            }
        }
        header("Location: group_admin_panel.php");
    }

    elseif ($action == 'decline_request') {
    $rid = intval($_POST['request_id']);
    // Either delete the request OR mark as declined
    mysqli_query($conn, "UPDATE group_requests SET status='declined' WHERE id = $rid");

    // Optional: delete instead of updating
    // mysqli_query($conn, "DELETE FROM group_requests WHERE id = $rid");

    header("Location: group_admin_panel.php");
}


} else {
    echo "No action received.";
}
