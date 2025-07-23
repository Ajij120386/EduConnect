
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/NSTUICE15/index.php");
    exit();
}

include '../conn.php';
include 'world_header.php';



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EduConnect</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
     
          
      
      margin: 0;
      padding: 0;
      height: 100%;
      background: linear-gradient(120deg, #d1e3ff, #f0f4fb);
      font-family: 'Segoe UI', sans-serif;
    }
    .full-page-layout {
      
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .layout-body {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 20px;
      height: 100%;
      padding: 20px;
    }
    .sidebar, .main-content {
      background: #ffffff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.08);
      height: calc(100vh - 100px);
      overflow-y: auto;
    }
    .main-content {
      zoom: 1.30;
      background: linear-gradient(to bottom right, #fdfdfd, #e0f2ff);
      border: 2px solid #cde0ff;
    }
    .box {
      background: #ffffff;
 
      padding: 20px;
      margin-bottom: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .box:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
    }
    h6 {
      font-weight: 700;
      color: #333;
      margin-bottom: 12px;
    }
    p {
      color: #555;
    }
    .btn-sm {
      font-size: 0.85rem;
      padding: 6px 14px;
      border-radius: 20px;
      font-weight: 500;
    }
    .btn-outline-primary {
      border-color: #007bff;
      color: #007bff;
    }
    .btn-outline-primary:hover {
      background-color: #007bff;
      color: white;
    }
    .btn-outline-secondary {
      border-color: #6c757d;
      color: #6c757d;
    }
    .btn-outline-secondary:hover {
      background-color: #6c757d;
      color: white;
    }
    .btn-success {
      background-color: #20c997;
      border: none;
      color: white;
    }
    .btn-success:hover {
      background-color: #17a589;
    }
    .main-content::-webkit-scrollbar, .sidebar::-webkit-scrollbar {
      width: 10px;
    }
    .main-content::-webkit-scrollbar-track, .sidebar::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 8px;
    }
    .main-content::-webkit-scrollbar-thumb, .sidebar::-webkit-scrollbar-thumb {
      background: #bbb;
      border-radius: 8px;
    }
    .main-content::-webkit-scrollbar-thumb:hover, .sidebar::-webkit-scrollbar-thumb:hover {
      background: #999;
    }

    .user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 10px;
}


/* //view schedule css */

 .schedule-card {
            background: linear-gradient(145deg, #ffffff, #f3f3f3);
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
            max-width: 300px;
            width: 100%;
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
            border: 1px solid #f57c00;
        }
       .schedule-card .schedule-header {
            background: #ff6f00;
            color: white;
            padding: 20px;
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            border-bottom: 4px solid #ffa726;
        }
        
    .white-gap {
      height: 10px;
      background-color: white;
    }

    
       .schedule-card table {
            width: 100%;
            border-collapse: collapse;
        }
       .schedule-card th {
            background: #263238;
            color: #fff;
            padding: 12px 10px;
            font-size: 13px;
        }
        .schedule-card td {
           font-weight: 500;
   
            padding: 12px 10px;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid rgb(24, 4, 4);
            transition: background 0.3s ease;
        }
        .schedule-card tr:hover td {
            background: #e2e8f0;
        }
      .schedule-card  tr:last-child td {
            border-bottom: none;
        }
        .schedule-card .button-group {
            display: flex;
            justify-content: space-around;
            padding: 20px 15px;
            background: #fafafa;
            border-top: 1px solid #eee;
        }
       .schedule-card .btn {
         background-color: #28a745; /* Green */
  color: white;
            padding: 10px 22px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
      .schedule-card  .btn:hover {
           background-color: #145c29; /* Even Darker Green on Hover */
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
  


        
/* //view Note share  css */




  .note-box-wrapper {
     background: linear-gradient(145deg, #ffffff, #f3f3f3);
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
            max-width: 300px;
            width: 100%;
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
            border: 1px solid #f57c00;
}

.note-box-header {
   

      background-color: #f97316;
    color: white;
    padding: 10px 15px;
    font-weight: 700;
    font-size: 15px;
    
      
            text-align: center;
            border-bottom: 4px solid #ffa726;
}

.note-box-body {
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    background-color: #fff;
}

.note-btn {
    display: block;
    padding: 12px 15px;
    border-left: 6px solid #1e293b;
    background-color: #fafafa;
    text-align: center;
    font-weight: 500;
    font-size: 17px;
    border-radius: 6px;
    text-decoration: none;
    color: #111827;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.note-btn:hover {
    background-color: #e2e8f0;
    transform: translateY(-1px);
      text-decoration: none !important;
}

/* //view exam Update style */


.exam-box {

      background: linear-gradient(145deg, #ffffff, #f3f3f3);
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
            max-width: 300px;
            width: 100%;
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
            border: 1px solid #f57c00;
            
    }

    .exam-box h5 {
      background: #f97316;
      color: white;
      font-weight: 600;
      font-size: 15px;
      padding: 12px;
      margin: 0;
      border-radius: 10px 10px 0 0;
      text-align: center;
    }

    .exam-entry {
      display: flex;
      align-items: center;
      padding: 12px;
      border-bottom: 1px solid black;
    }

      .exam-entry:hover {
    background-color: #e2e8f0;
    transform: translateY(-1px);
      text-decoration: none !important;
}

    .exam-entry:last-child {
      border-bottom: none;
    }

    .date-box {
      width: 52px;
      height: 65px;
      border: 2px solid #f97316;
      border-radius: 12px;
      text-align: center;
      line-height: 1.2;
      margin-right: 12px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .date-box span {
      font-weight: bold;
      font-size: 15px;
      color: #1e293b;
    }

    .date-box small {
      font-size: 13px;
      color: #3b82f6;
      text-transform: uppercase;
    }

   

    .course-name {
      font-weight: 500;
      font-size: 16px;
      color: #111827;
    }

    .exam-footer {
      display: flex;
      justify-content: space-around;
      padding: 12px;
    }

    .exam-footer .btn {

        background-color: #28a745; /* Green */
  color: white;
           
     padding: 10px 22px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

.exam-footer .btn:hover {
            background-color: #145c29; /* Even Darker Green on Hover */
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
     


        /* image  */

        
      .image-card {
  background: linear-gradient(145deg, #ffffff, #f3f3f3);
  border-radius: 20px;
  box-shadow: 0 12px 30px rgba(0,0,0,0.1);
  width: 100%;
  height:200px;
  max-width: 300px;
  overflow: hidden;
  animation: fadeIn 0.5s ease-in-out;
  border: 1px solid #f57c00;
}

    

    .image-card-header {
      background: #ff6600;
      color: white;
      padding: 10px 16px;
        font-weight: 600;
      font-size: 13px;
    }

    .slider-container {
  position: relative;
  width: 100%;
  aspect-ratio: 3 / 2; /* Ensures consistent height based on width */
}

.slider-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  transition: opacity 1s ease-in-out, transform 0.4s ease;
  border-radius: 0 0 16px 16px;
}

.slider-container img.active {
  opacity: 1;
  z-index: 1;
}

    /* Hover effect */
    .slider-container img:hover {
      transform: scale(1.03);
      filter: brightness(0.95);
    }

    /* Optional flip effect (can be removed if not needed) */
    .slider-container img.flip {
      transform: rotateY(180deg);
      transition: transform 0.6s;
    }

    @media (max-width: 768px) {
      .slider-container {
        height: 250px;
      }

    }

    
  </style>
</head>
<body>
<div class="full-page-layout">
  <div class="layout-body">

    <!-- Left Sidebar -->
    <div class="sidebar">
      <div class="box">
          <?php include 'teacher_class_Schedule_view.php'; ?>
      </div>


      <div class="box">

        <div class="note-box-wrapper">
    <div class="note-box-header">
        <strong>Class Lectures & Assignments :</strong>
    </div>
    <div class="note-box-body">
        <a href="share_note_form_view.php" class="note-btn">Browse </a>
        <a href="share_note_form.php" class="note-btn">Share </a>
    </div>
</div>
     
    </div>
      </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Post Form -->
        <form id="post-form" enctype="multipart/form-data">
          <div class="post-box">
            
            <div class="d-flex align-items-center mb-3">
              <img src="../user_image/<?= htmlspecialchars($_SESSION['image'] ?? 'blank_pp.png') ?>" class="user-avatar" width="50">
            
             <div class="ms-2">
              
    <div class="fw-semibold" style="font-size: 15px;">
      <?= htmlspecialchars($_SESSION['name']) ?>
    </div>

    <!-- Styled Privacy Dropdown -->
    <select name="privacy" id="main-privacy" class="form-select form-select-sm privacy-select mt-1">
      <option value="public">🌍 Public</option>
     

      <option value="private">🔒 Only Me</option>
      <option value="batch_trigger">👥 Batch</option>

</select>

      <?php
// Fetch batch list from database
$batch_query = mysqli_query($conn, "SELECT DISTINCT batch_no FROM user_data WHERE batch_no IS NOT NULL AND batch_no != '' ORDER BY batch_no ASC");
?>
          <!-- Hidden batch options container -->
   <select id="batch-list" name="batch_privacy" class="form-select form-select-sm mt-2 d-none" style="width: 120px; font-size: 8px; border-radius: 15px;">
      <?php while ($row = mysqli_fetch_assoc($batch_query)): ?>
        <option value="<?= htmlspecialchars($row['batch_no']) ?>">👥 <?= htmlspecialchars($row['batch_no']) ?></option>
      <?php endwhile; ?>
    
    </select>
  </div>
              
            </div>
            <textarea name="content" class="form-control mb-2" rows="3" placeholder="What's on your mind?"></textarea>
            <input type="file" name="file" class="form-control mb-2" accept="image/*,.pdf,.doc,.docx">
            <input type="hidden" name="action" value="add_post">
            <button type="submit" class="btn btn-primary">➕ Post</button>
          </div>
        </form>


        <input type="text" id="search-input" class="form-control mt-3" placeholder="🔍 Search posts..." onkeyup="filterPosts()">
        <div id="posts"></div>
        <div id="load-more-wrapper" class="text-center mt-4">
          <button id="load-more" class="btn btn-outline-primary">⬇️ Load More</button>
        </div>



    </div>

    <!-- Right Sidebar -->
    <div class="sidebar">
      <div class="box">
         <?php include 'teacher_exam_update_view.php'; ?>
      </div>


      <div class="box">
        <?php include 'campus_view.php'; ?>
      </div>
    </div>

  </div>
</div>

<script>
 let offset = 0;
    const limit = 5;

    function fetchPosts(reset = false) {
      if (reset) {
        offset = 0;
        document.getElementById('posts').innerHTML = '';
        document.getElementById('load-more-wrapper').style.display = 'block';
      }

      fetch(`fetch_posts.php?offset=${offset}&limit=${limit}`)
        .then(res => res.text())
        .then(html => {
          if (html.trim()) {
            document.getElementById('posts').insertAdjacentHTML('beforeend', html);
            offset += limit;
          } else {
            document.getElementById('load-more-wrapper').style.display = 'none';
          }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
      fetchPosts();
      document.getElementById('load-more').addEventListener('click', () => fetchPosts());
    });

    document.getElementById("post-form").addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch('post.php', {
        method: 'POST',
        body: formData
      }).then(() => {
        this.reset();
        fetchPosts(true);
      });
    });

    function filterPosts() {
      let val = document.getElementById("search-input").value.toLowerCase();
      document.querySelectorAll(".post-card").forEach(post => {
        const text = post.innerText.toLowerCase();
        post.style.display = text.includes(val) ? '' : 'none';
      });
      document.getElementById("load-more-wrapper").style.display = val ? "none" : "block";
    }


function toggleComments(postId) {
  const section = document.getElementById(`comments-${postId}`);
  section.style.display = section.style.display === "none" ? "block" : "none";

  if (!section.dataset.loaded) {
    fetch(`post_actions_clean.php?fetch_comments=${postId}`)
      .then(res => res.json())
      .then(parsed => {
        let out = '';
        parsed.forEach(c => {
          out += `<p><strong>${c.name}</strong>: ${c.comment}</p>`;
        });
        document.getElementById(`comment-list-${postId}`).innerHTML = out;
        section.dataset.loaded = "true";
        document.getElementById(`cmt-${postId}`).innerText = parsed.length;
      })
      .catch(e => console.error("❌ Error loading comments:", e));
  }
}

function react(postId, type) {
  fetch('post_actions_clean.php', {
    method: 'POST',
    body: new URLSearchParams({
      action: 'react',
      post_id: postId,
      type: type
    })
  })
  .then(res => res.json())
  .then(data => {
    document.getElementById(`like-${postId}`).innerText = data.likes;
    document.getElementById(`dislike-${postId}`).innerText = data.dislikes;
  })
  .catch(e => console.error("❌ React failed:", e));
}

function submitComment(postId) {
  let comment = document.getElementById(`cmt-in-${postId}`).value.trim();
  if (!comment) return;

  fetch('post_actions_clean.php', {
    method: 'POST',
    body: new URLSearchParams({
      action: 'comment',
      post_id: postId,
      comment: comment
    })
  })
  .then(() => {
    document.getElementById(`cmt-in-${postId}`).value = '';
    document.getElementById(`comments-${postId}`).dataset.loaded = '';
    toggleComments(postId); // force reload comments
  })
  .catch(e => console.error("❌ Comment failed:", e));
}

function deletePost(postId) {
  if (!confirm("Delete this post?")) return;

  fetch('post_actions_clean.php', {
    method: 'POST',
    body: new URLSearchParams({
      action: 'delete_post',
      post_id: postId
    })
  })
  .then(() => {
    document.getElementById(`post-${postId}`).remove();
  })
  .catch(e => console.error("❌ Delete failed:", e));
}



function filterPosts() {
  const val = document.getElementById("search-input").value.toLowerCase();
  const posts = document.querySelectorAll(".post-card");

  let visible = 0;
  posts.forEach(post => {
    const text = post.innerText.toLowerCase();
    if (text.includes(val)) {
      post.style.display = "";
      visible++;
    } else {
      post.style.display = "none";
    }
  });

  // Hide load more button when searching
  const loadMoreWrapper = document.getElementById("load-more-wrapper");
  if (loadMoreWrapper) {
    loadMoreWrapper.style.display = val ? "none" : "block";
  }




  
}


//  JavaScript to Toggle Batch List

document.getElementById('main-privacy').addEventListener('change', function() {
  const batchDropdown = document.getElementById('batch-list');
  batchDropdown.classList.toggle('d-none', this.value !== 'batch_trigger');
});



</script>


</body>
</html>