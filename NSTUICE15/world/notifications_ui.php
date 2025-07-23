<?php
session_start();
include '../conn.php';

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
  font-size: 20px;
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
  font-size: 12px;
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
}

#notifDropdown ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

#notifDropdown li {
  padding: 10px 15px;
  border-bottom: 1px solid #eee;
  font-size: 14px;
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
  background: #f5f5f5;
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


@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>
<div id="notifWrapper">
  <button id="notifBell">
    <img src="https://cdn-icons-png.flaticon.com/512/1827/1827349.png" width="24" height="24" alt="🔔" />
    <span id="notifCount">0</span>
  </button>

  <div id="notifDropdown">
    <ul id="notifList"></ul>
    <div id="markAll">Mark all as read</div>
  </div>
</div>

<script>
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
