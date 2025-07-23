<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notification UI</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
      padding: 40px;
    }

    .notif-wrapper {
        border: 1px solid #f57c00;
      width: 360px;
      max-height: 500px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      overflow-y: auto;
    }

    .notif-header {
      padding: 15px 20px;
      border-bottom: 1px solid #ddd;
      font-weight: bold;
      font-size: 18px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .notif-tabs {
      display: flex;
      gap: 10px;
    }

    .notif-tab {
      font-size: 14px;
      cursor: pointer;
      padding: 6px 12px;
      border-radius: 20px;
      background: #f0f2f5;
      color: #333;
    }

    .notif-tab.active {
      background: #1877f2;
      color: white;
      font-weight: bold;
    }

    .notif-item {
      display: flex;
      gap: 10px;
      padding: 15px 20px;
      border-bottom: 1px solid #f1f1f1;
      align-items: center;
      transition: background 0.2s;
    }

    .notif-item:hover {
      background: #f6f6f6;
    }

    .notif-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #ccc;
      flex-shrink: 0;
      background-size: cover;
      background-position: center;
    }

    .notif-text {
      flex-grow: 1;
    }

    .notif-text strong {
      display: block;
      margin-bottom: 3px;
    }

    .notif-time {
      font-size: 12px;
      color: gray;
    }

    .notif-dot {
      width: 10px;
      height: 10px;
      background: #1877f2;
      border-radius: 50%;
    }

    .notif-footer {
      text-align: center;
      padding: 10px;
      background: #f9f9f9;
      font-weight: 500;
      color: #1877f2;
      cursor: pointer;
    }

    .notif-footer:hover {
      background: #eee;
    }
  </style>
</head>
<body>

<div class="notif-wrapper">
  <div class="notif-header">
    <span>Notifications</span>
    <div class="notif-tabs">
      <div class="notif-tab active">All</div>
      <div class="notif-tab">Unread</div>
    </div>
  </div>

  <div class="notif-item">
    <div class="notif-avatar" style="background-image: url('user_image/cr.jpeg');"></div>
    <div class="notif-text">
      <strong>A new class schedule has been shared by CR.</strong>
      <div class="notif-time">2025-07-06 17:16:06</div>
    </div>
    <div class="notif-dot"></div>
  </div>

  <div class="notif-item">
    <div class="notif-avatar" style="background-image: url('user_image/Ajij.jpg');"></div>
    <div class="notif-text">
      <strong>A new note has been shared by Ajij.</strong>
      <div class="notif-time">2025-07-06 00:44:07</div>
    </div>
    <div class="notif-dot"></div>
  </div>

  <div class="notif-item">
    <div class="notif-avatar" style="background-image: url('user_image/samapan.jpg');"></div>
    <div class="notif-text">
      <strong>A new post has been shared by Samapan.</strong>
      <div class="notif-time">2025-07-06 00:43:07</div>
    </div>
    <div class="notif-dot"></div>
  </div>

  

  <div class="notif-item">
    <div class="notif-avatar" style="background-image: url('user_image/rony.jpeg');"></div>
    <div class="notif-text">
      <strong>A new post has been shared by Rony.</strong>
      <div class="notif-time">2025-07-06 00:01:08</div>
    </div>
    <div class="notif-dot"></div>
  </div>

  <div class="notif-footer">See previous notifications</div>
</div>

</body>
</html>