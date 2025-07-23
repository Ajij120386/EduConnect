
<?php
include '../conn.php';
if (!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="http://malsup.github.com/jquery.form.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    .top-bar {
      background-color: #000;
      color: white;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 25px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    .left-section, .center-section, .right-section {
      display: flex;
      align-items: center;
    }

    .left-section img {
      height: 45px;
    }

    .center-section a, .right-section a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
      display: flex;
      align-items: center;
      transition: opacity 0.2s ease;
    }

    .center-section a:hover, .right-section a:hover {
      opacity: 0.8;
    }

    .navbar_img {
      width: 25px;
      margin-right: 6px;
    }

    .nav_menu_text {
      font-weight: 600;
      font-size: 20px;
    }

    .search-bar-li {
      padding-left: 15px;
      width: 280px;
    }

    .styled-search {
      position: relative;
      display: flex;
      align-items: center;
      width: 100%;
    }

    .styled-input {
      width: 100%;
      padding: 6px 12px;
      border: none;
      border-radius: 30px;
      background-color: #f0f8ff;
      box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.2);
      color: #000;
    }

    .styled-input:focus {
      box-shadow: 0 0 5px #66afe9;
      outline: none;
    }

    .styled-btn {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #555;
      font-size: 16px;
      cursor: pointer;
    }

    #notifWrapper {
      position: relative;
      display: inline-block;
    }

    #notifBell {
      background: none;
      border: none;
      font-size: 15px;
      cursor: pointer;
      position: relative;
    }

    #notifCount {
      position: absolute;
      top: -6px;
      right: -6px;
      background: red;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      font-size: 8px;
      display: none;
    }

    #notifDropdown {
      display: none;
      position: absolute;
      top: 35px;
      left: 0;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      width: 320px;
      max-height: 300px;
      max-width: 95vw;
      overflow-y: auto;
      z-index: 9999;
      animation: fadeIn 0.3s ease;
      border: 1px solid #f57c00;
      padding: 0;
    }

    #notifDropdown ul, #notifDropdown li {
      list-style: none;
      margin: 0;
      padding: 0;
      font-size: 12px;
    }

    #notifDropdown li {
      padding: 10px 15px;
      border-bottom: 1px solid #eee;
    }

    #notifDropdown li:hover {
      background: #f9f9f9;
    }

    #notifDropdown li a {
      color: #333;
      text-decoration: none;
      display: block;
    }

    #markAll {
      display: block;
      text-align: center;
      padding: 10px;
      background: #1d3557;
      font-weight: bold;
      cursor: pointer;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .searchify-link {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 20px;
      text-decoration: none;
      font-weight: 500;
      color: #e6f7ff;
      transition: all 0.3s ease;
    }

    .searchify-link:hover {
      color: #00eaff;
      transform: scale(1.05);
    }

    .searchify-icon {
      width: 22px;
      height: 22px;
      filter: brightness(2) drop-shadow(0 0 4px #00eaff);
    }
  </style>
</head>
<body>
<div class="top-bar">
  <div class="left-section">
    <img src="images/logo.jpeg" alt="Logo">
  </div>

  <div class="center-section">
    <a href="world.php"><img src="images/home.ico" class="navbar_img"><span class="nav_menu_text">Home</span></a>
    <a href="dashboard.php"><img src="images/user1.png" class="navbar_img"><span class="nav_menu_text">Profile</span></a>
    <a href="groups.php"><img src="images/groups.png" class="navbar_img"><span class="nav_menu_text">Group</span></a>

    <div class="search-bar-li">
      <form id="searchForm" class="styled-search">
        <input type="text" id="searchInput" class="styled-input" placeholder="Search for user..." autocomplete="off">
        <button type="submit" class="styled-btn"><span class="glyphicon glyphicon-search"></span></button>
      </form>
      <ul id="searchResults"></ul>
    </div>
  </div>

  <div class="right-section">
    <div id="notifWrapper">
      <button id="notifBell">
        <img src="images/notification1.png" class="navbar_img" width="24" height="24" alt="🔔" />
        <span id="notifCount">0</span>
      </button>
      <span id="notifLabel" style="color: white; font-weight: 600; cursor: pointer;">Notifications</span>
      <div id="notifDropdown">
        <ul id="notifList"></ul>
        <div id="markAll">Mark all as read</div>
      </div>
    </div>

    <?php if (isset($_SESSION['id']) && $_SESSION['id'] == 18): ?>
      <a href="admin_dashboard.php">
        <img src="images/adminpanel1.png" class="navbar_img"><span class="nav_menu_text">Dashboard</span>
      </a>
    <?php endif; ?>

    <a href="custom_search.php" class="searchify-link">
      <img src="images/searchify_icon.png" alt="Searchify" class="searchify-icon">
      <span>Searchify</span>
    </a>

    <a href="../index.php"><img src="images/leave.png" class="navbar_img"><span class="nav_menu_text">Exit Now</span></a>
  </div>
</div>
</body>
</html>
