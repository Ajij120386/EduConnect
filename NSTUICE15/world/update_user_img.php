<?php
session_start();
ini_set('upload_max_filesize', '15M');
ini_set('post_max_size', '15M');
ini_set('max_input_time', 600);
ini_set('max_execution_time', 600);
include '../conn.php';

if (isset($_POST['img_update_up_btn']) && !empty($_FILES['uploadimage'])) {
    $reg = $_POST['tmp_reg_update'];
    $directory = "../user_image/";
    $target_file = $directory . basename($_FILES['uploadimage']['name']);
    $name = $_FILES["uploadimage"]["name"];
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $check = getimagesize($_FILES["uploadimage"]["tmp_name"]);

    if ($check === false) {
        $uploadOk = 0;
    }

    if (
        strtolower($imageFileType) == "jpg" ||
        strtolower($imageFileType) == "png" ||
        strtolower($imageFileType) == "jpeg" ||
        strtolower($imageFileType) == "gif"
    ) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["uploadimage"]["tmp_name"], $target_file)) {
            $sql = "UPDATE `user_data` SET `image`='$name' WHERE `reg_no` = $reg";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Database update failed.";
            }
        } else {
            echo "Image upload failed.";
        }
    } else {
        echo "Invalid image file.";
    }
}

// Optional: Edit name handling
if (isset($_POST['fn_edit']) && isset($_POST['ln_edit']) && isset($_POST['un_edit'])) {
    $fn_edit = $_POST['fn_edit'];
    $ln_edit = $_POST['ln_edit'];
    $un_edit = $_POST['un_edit'];
    $id = $_SESSION['id'];
    echo "$fn_edit $ln_edit $un_edit $id";
}
?>
