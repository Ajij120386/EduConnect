<?php
session_start();
include 'conn.php';


$role_in_url = isset($_GET['role']) ? $_GET['role'] : '';


if (isset($_POST['user_name']) && isset($_POST['pwd'])) {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $input_password = $_POST['pwd'];

    // Fetch user by username only
    $sql = "SELECT * FROM user_data WHERE user_name = '$user_name'";

    // ✅ Fetch user by username and optional role


    if ($role_in_url === 'teacher' || $role_in_url === 'student') {
        $sql .= " AND role = '$role_in_url'";
    }

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_assoc($result);

        // ✅ Verify hashed password
        if (password_verify($input_password, $row['password'])) {

            // ✅ Check if user is approved
            if ($row['is_approved'] == 1) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['batch_no'] = $row['batch_no'];
                $_SESSION['name'] = $row['first_name'] . ' ' . $row['last_name'];
                $_SESSION['image'] = $row['image'];

                $_SESSION['role'] = $row['role']; // optional: store role in session

                // ✅ Redirect based on role
                if ($role_in_url === 'teacher') {
                    header("Location: teacher/world.php");
                } else {
                     header("Location: world/world.php");
                }
            } else {
                // Not approved
                header("Location: login.php?message=notapproved");
                exit();
            }
        } else {
            // ❌ Wrong password
            header("Location: login.php?message=invalid");
            exit();
        }
    } else {
        // ❌ User not found
        header("Location: login.php?message=invalid");
        exit();
    }
}
?>



<!-- ✅ HTML PART BELOW -->
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .login-card {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 30px;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <h4 class="mb-3 text-center">
            🔐 <?php echo ucfirst($role_in_url) ?: 'User'; ?> Login
        </h4>



        <!-- ✅ SHOW ERROR MESSAGES -->
        <?php if (isset($_GET['message'])) {
            $msg = $_GET['message'];
            if ($msg == 'notapproved') echo "<div class='alert alert-warning'>Your account is not yet approved by admin.</div>";
            elseif ($msg == 'invalid') echo "<div class='alert alert-danger'>Invalid username or password.</div>";
            elseif ($msg == 'awaiting') echo "<div class='alert alert-info'>Registration complete. Await admin approval.</div>";
        } ?>

        <!-- ✅ LOGIN FORM -->
      <form method="POST" action="login.php?role=<?php echo $role_in_url; ?>">
            <div class="mb-3">
                <input type="text" name="user_name" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="pwd" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="registration_modal.php">New user? Register</a>
        </div>
    </div>

</body>

</html>