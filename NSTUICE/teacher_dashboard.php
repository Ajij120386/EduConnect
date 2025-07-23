<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Dashboard | EduConnect</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root {
      --primary-color: #ff3d00;
      --background-dark: #0b0b1e;
      --card-bg: #17122d;
      --text-color: #f9f9f9;
      --shadow-color: rgba(255, 61, 0, 0.15);
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--background-dark);
      color: var(--text-color);
    }

    header {
      background-color: #1e2a38;
      padding: 60px 20px;
      text-align: center;
      color: white;
      border-bottom: 3px solid #333;
    }

    .container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
      gap: 35px;
    }

    .card {
      background: var(--card-bg);
      padding: 25px;
      border-radius: 16px;
      border: 1px solid var(--primary-color);
      box-shadow: 0 10px 30px var(--shadow-color);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(255, 61, 0, 0.25);
    }

    .card h3 {
      color: #64b5f6;
      margin-bottom: 10px;
    }

    .card p {
      color: #ccc;
    }

    .btn-group {
      margin-top: 15px;
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .btn {
      padding: 6px 12px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #ff5722;
    }

    footer {
      text-align: center;
      padding: 20px;
      background: #0b0724;
      color: #aaa;
      margin-top: 50px;
    }
  </style>
</head>
<body>

<header>
  <h1>Teacher Dashboard</h1>
  <p>Manage classes, students, materials and more with ease.</p>
</header>

<div class="container">
  <div class="grid">

    <div class="card">
      <h3>Class Schedule Manager</h3>
      <p>Create and update your weekly class schedule.</p>
      <div class="btn-group">
        <a href="view_schedule.php" class="btn">View</a>
        <a href="add_schedule.php" class="btn">Add</a>
        <a href="edit_schedule.php" class="btn">Edit</a>
      </div>
    </div>

    <div class="card">
      <h3>Upload Notes & Assignments</h3>
      <p>Distribute PDFs, slides, and assignments to your students.</p>
      <div class="btn-group">
        <a href="view_notes.php" class="btn">View</a>
        <a href="upload_notes.php" class="btn">Add</a>
        <a href="edit_notes.php" class="btn">Edit</a>
      </div>
    </div>

    <div class="card">
      <h3>Attendance Tracker</h3>
      <p>Mark daily attendance and view student attendance reports.</p>
      <div class="btn-group">
        <a href="view_attendance.php" class="btn">View</a>
        <a href="add_attendance.php" class="btn">Add</a>
        <a href="edit_attendance.php" class="btn">Edit</a>
      </div>
    </div>

    <div class="card">
      <h3>Grade Submission</h3>
      <p>Enter marks for exams, quizzes, and assignments easily.</p>
      <div class="btn-group">
        <a href="view_grades.php" class="btn">View</a>
        <a href="gradebook.php" class="btn">Add</a>
        <a href="edit_grades.php" class="btn">Edit</a>
      </div>
    </div>

    <div class="card">
      <h3>Student Feedback</h3>
      <p>Collect feedback to improve your teaching methods.</p>
      <div class="btn-group">
        <a href="view_feedback.php" class="btn">View</a>
        <a href="request_feedback.php" class="btn">Add</a>
        <a href="edit_feedback.php" class="btn">Edit</a>
      </div>
    </div>

    <div class="card">
      <h3>Send Announcements</h3>
      <p>Post updates for students to stay informed and organized.</p>
      <div class="btn-group">
        <a href="view_announcements.php" class="btn">View</a>
        <a href="post_announcement.php" class="btn">Add</a>
        <a href="edit_announcement.php" class="btn">Edit</a>
      </div>
    </div>

    <div class="card">
      <h3>Student Group Oversight</h3>
      <p>Manage group projects and monitor progress.</p>
      <div class="btn-group">
        <a href="view_groups.php" class="btn">View</a>
        <a href="create_group.php" class="btn">Add</a>
        <a href="edit_group.php" class="btn">Edit</a>
      </div>
    </div>

    <div class="card">
      <h3>Performance Analytics</h3>
      <p>Analyze student performance trends and take action.</p>
      <div class="btn-group">
        <a href="view_analytics.php" class="btn">View</a>
        <a href="generate_report.php" class="btn">Add</a>
        <a href="edit_report.php" class="btn">Edit</a>
      </div>
    </div>

  </div>
</div>

<footer>
  &copy; 2025 EduConnect – Empowering Teachers.
</footer>

</body>
</html>
