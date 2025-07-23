<?php
$uploadDir = "uploads/";
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $course = $_POST['course'];
    $semester = $_POST['semester'];

    $file = $_FILES['note'];
    $filename = basename($file['name']);
    $targetFile = $uploadDir . $filename;

    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if ($fileType != "pdf") {
        $error = "Only PDF files are allowed.";
    } elseif (move_uploaded_file($file["tmp_name"], $targetFile)) {
        // Updated: Use your correct DB name and table
        $conn = new mysqli("localhost", "root", "", "ice_15_batch"); // Change credentials if needed
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO teacher_notes (title, course, semester, filename) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $course, $semester, $filename);
        $stmt->execute();

        $success = "Note uploaded successfully!";
        $stmt->close();
        $conn->close();
    } else {
        $error = "Failed to upload file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Upload Notes | Teacher Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root {
      --primary-color: #ff3d00;
      --background-dark: #0b0b1e;
      --card-bg: #17122d;
      --text-color: #f9f9f9;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--background-dark);
      color: var(--text-color);
      padding: 40px;
    }

    h2 {
      text-align: center;
      color: #64b5f6;
    }

    .form-container {
      max-width: 600px;
      margin: auto;
      background: var(--card-bg);
      padding: 30px;
      border-radius: 10px;
      border: 1px solid var(--primary-color);
      box-shadow: 0 8px 30px rgba(255, 61, 0, 0.1);
    }

    label {
      display: block;
      margin-bottom: 8px;
      margin-top: 15px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: none;
      margin-bottom: 10px;
    }

    input[type="submit"] {
      background: var(--primary-color);
      color: #fff;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background: #ff5722;
    }

    .message {
      text-align: center;
      margin-top: 20px;
      color: #66ff99;
    }

    .error {
      color: #ff6666;
    }
  </style>
</head>
<body>

  <h2>Upload Notes & Assignments</h2>

  <div class="form-container">
    <form method="post" enctype="multipart/form-data">
      <label>Title</label>
      <input type="text" name="title" required>

      <label>Course</label>
      <input type="text" name="course" required>

      <label>Semester</label>
      <select name="semester" required>
        <option value="">Select</option>
        <option value="1st">1st Semester</option>
        <option value="2nd">2nd Semester</option>
        <option value="3rd">3rd Semester</option>
        <option value="4th">4th Semester</option>
        <!-- Add more as needed -->
      </select>

      <label>Upload PDF</label>
      <input type="file" name="note" accept="application/pdf" required>

      <input type="submit" value="Upload Note">
    </form>

    <?php if ($success): ?>
      <div class="message"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="message error"><?= $error ?></div>
    <?php endif; ?>
  </div>

</body>
</html>
