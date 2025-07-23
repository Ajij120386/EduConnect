<?php
// session_start();
include 'conn.php';
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
      text-decoration: none;
    }

    .navbar_img {
      width: 25px;
      margin-right: 6px;
    }

    .nav_menu_text {
      font-weight: 600;
      font size: 20px;
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

    
    .navbar_img2 {
      width: 25px;
      margin-right: 6px;
      color: green;
    }

    .styled-input {
  width: 100%;
  padding: 6px 12px;
  border: none;
  border-radius: 30px;
  background-color: #f0f8ff; /* light blue background */
  box-shadow: inset 0 0 3px rgba(0, 0, 0, 0.2);
  transition: box-shadow 0.2s ease;
  color: #000; /* optional: text color */
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

    .styled-btn:hover {
      color: #007bff;
    }

   #searchResults {
  position: absolute;
  background-color: #ffffff; /* dropdown background */
  color: #000; /* dropdown text color */
  width: 100%;
  z-index: 10;
  display: none;
  border-radius: 5px;
  padding: 5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}


    #searchResults li {
      padding: 8px 12px;
      font-size: 14px;
      cursor: pointer;
      border-bottom: 1px solid #f0f0f0;
    }

    #searchResults li:last-child {
      border-bottom: none;
    }

    #searchResults li:hover {
      background-color: #f5faff;
    }


    .center-section a:hover img.navbar_img,
.center-section a:hover .nav_menu_text,
.right-section a:hover img.navbar_img,
.right-section a:hover .nav_menu_text {
  filter: brightness(1.5) drop-shadow(0 0 3px #00eaff);
  color: #00eaff;
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
    <a href="http://localhost/NSTUICE15/world/world.php"><img src="images/home.ico" class="navbar_img"><span class="nav_menu_text">Home</span></a>
    <a href="http://localhost/NSTUICE15/dashboard.php"><img src="images/user1.png" class="navbar_img"><span class="nav_menu_text">Profile</span></a>

    <a href="http://localhost/NSTUICE15/world/groups.php"><img src="images/groups.png" class="navbar_img"><span class="nav_menu_text">Group</span></a>


    <div class="search-bar-li">
      <form id="searchForm" class="styled-search">
        <input type="text" id="searchInput" class="styled-input" placeholder="Search for user..." autocomplete="off">
        <button type="submit" class="styled-btn"><span class="glyphicon glyphicon-search"></span></button>
      </form>
      <ul id="searchResults"></ul>
    </div>

    
     


   <!-- Notification Bell with Dropdown -->
    
    
    
    
    <?php


$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($_SESSION['id']) ? $_SESSION['id'] : 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['notification_id'])) {
        $notif_id = intval($_POST['notification_id']);
        mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE id = $notif_id");
        exit;
    }

    if (isset($_POST['mark_all'])) {
        mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id");
        exit;
    }
}

if (isset($_GET['fetch']) && $_GET['fetch'] === '1') {
    if ($user_id == 0) {
        echo json_encode([]);
        exit;
    }

    $query = "SELECT * FROM notifications WHERE user_id = $user_id AND is_read = 0 ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    $notifications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
    echo json_encode($notifications);
    exit;
}
?>

<style>
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
}

#notifDropdown ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

#notifDropdown li {
  padding: 10px 15px;
  border-bottom: 1px solid #eee;
  font-size: 12px;
}

#notifDropdown li a {
  color: #333;
  text-decoration: none;
  display: block;
}

#notifDropdown li:hover {
  background: #f9f9f9;
}

#markAll {
  display: block;
  text-align: center;
  padding: 10px;
  background: #1d3557;
  border-top: 1px solid #ddd;
  font-weight: bold;
  cursor: pointer;
}

#notifDropdown ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

#notifDropdown {
  padding: 0;
}

  #notifLabel:hover {
    
    filter: brightness(1.5) drop-shadow(0 0 3px #00eaff);
  color: #00eaff;
  }

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>
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

<?php
    if (!isset($_SESSION['id'])) {
      header("Location: login.php");
      exit();
    }
   

    if ($_SESSION['id'] == 7):
    ?>
    <a href="http://localhost/NSTUICE15/world/admin_dashboard.php">
      <img src="images/adminpanel1.png" class="navbar_img"><span class="nav_menu_text">Dashboard</span>
    </a>
    <?php endif; ?>


<a href="http://localhost/NSTUICE15/world/custom_search.php" class="nav-item searchify-link">
  <img src="images/searchify_icon.png" alt="Searchify" class="searchify-icon">
  <span>Searchify</span>
</a>


  </div>

  <div class="right-section">
    <a href="http://localhost/NSTUICE15"><img src="images/leave.png" class="navbar_img"><span class="nav_menu_text">Exit Now</span></a>
  </div>
</div>

<script>
$(document).ready(function () {
  $('#searchInput').on('input', function () {
    var query = $(this).val();
    if (query.length < 2) {
      $('#searchResults').hide();
      return;
    }

    $.ajax({
      url: 'search_user.php',
      method: 'GET',
      data: { term: query },
      success: function (data) {
        let users = JSON.parse(data);
        let list = '';
        if (users.length > 0) {
          users.forEach(function (user) {
            list += `<li onclick="window.location='view_user.php?id=${user.value}'">${user.label}</li>`;
          });
        } else {
          list = '<li>No users found</li>';
        }
        $('#searchResults').html(list).show();
      }
    });
  });

  $(document).click(function (e) {
    if (!$(e.target).closest('#searchForm').length) {
      $('#searchResults').hide();
    }
  });
});



document.addEventListener("DOMContentLoaded", () => loadNotifCount());

document.getElementById("notifBell").addEventListener("click", function () {
  const dropdown = document.getElementById("notifDropdown");
  const bell = document.getElementById("notifBell");

  dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";

  const bellRect = bell.getBoundingClientRect();
  const dropdownWidth = dropdown.offsetWidth;
  const windowWidth = window.innerWidth;

  let left = bell.offsetLeft;

  if (left + dropdownWidth > windowWidth) {
    left = windowWidth - dropdownWidth - 10;
  }

  dropdown.style.left = left + "px";

  loadNotifications();
});

function loadNotifCount() {
  fetch("notifications_ui.php?fetch=1")
    .then(res => res.json())
    .then(data => updateNotifCount(data.length));
}

function loadNotifications() {
  fetch("notifications_ui.php?fetch=1")
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById("notifList");
      list.innerHTML = "";

      updateNotifCount(data.length);

      if (data.length === 0) {
        list.innerHTML = "<li>No new notifications</li>";
      } else {
        data.forEach(notif => {
          const li = document.createElement("li");
          li.innerHTML = `<a href="#" onclick="markRead(${notif.id})">
                            <strong>${notif.message}</strong><br>
                            <small>${notif.created_at}</small>
                          </a>`;
          list.appendChild(li);
        });
      }
    });
}

function markRead(id) {
  fetch("notifications_ui.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "notification_id=" + id
  }).then(() => {
    document.getElementById("notifDropdown").style.display = "none";
    loadNotifCount();
  });
}

document.getElementById("markAll").addEventListener("click", () => {
  fetch("notifications_ui.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "mark_all=1"
  }).then(() => {
    document.getElementById("notifDropdown").style.display = "none";
    loadNotifCount();
  });
});

function updateNotifCount(count) {
  const notifCount = document.getElementById("notifCount");
  notifCount.innerText = count;
  notifCount.style.display = count > 0 ? "inline-block" : "none";
}

setInterval(loadNotifCount, 30000);
</script>

</body>
</html>
