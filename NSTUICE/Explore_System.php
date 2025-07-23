<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <title>
   About Us | EduConnect
  </title>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <style>
   /* --- Updated Variables --- */
:root {
  --primary-color: #ff3d00;
  --background-dark: #0b0b1e;
  --card-bg: #17122d;
  --text-color: #f9f9f9;
  --shadow-color: rgba(255, 61, 0, 0.15);
  --glass-bg: rgba(255, 255, 255, 0.05);
}

/* --- Reset + Box Model --- */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* --- Body & Typography --- */
body {
  font-family: 'Segoe UI', sans-serif;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: var(--background-dark);
  color: var(--text-color);
  line-height: 1.6;
  overflow-x: hidden;
}

/* --- Header Section --- */
header {
  background: #2c3e50;;
  padding: 60px 20px;
  text-align: center;
  color: white;
  text-shadow: 1px 1px 4px #000;
}

header h1 {
  font-size: 3rem;
  margin-bottom: 10px;
  text-transform: uppercase;
}

header p {
  font-size: 1.1rem;
  letter-spacing: 0.5px;
}

/* --- Container --- */
.container {
  max-width: 1200px;
  margin: 40px auto;
  padding: 20px;
}

/* --- Intro Text --- */
.intro {
  text-align: center;
  font-size: 1rem;
  margin-bottom: 40px;
  color: #ddd;
  background: var(--glass-bg);
  padding: 20px;
  border-radius: 10px;
  border: 1px solid #333;
}

/* --- Grid System --- */
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
  gap: 35px;
}

/* --- Cards --- */
.card {
  background: var(--card-bg);
  padding: 25px;
  border-radius: 16px;
  border: 1px solid #f57c00;
  box-shadow: 0 10px 30px var(--shadow-color);
  text-align: center;
  position: relative;
  min-height: 270px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: center;
  animation: fadeInUp 0.5s ease forwards;
}

.card:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 16px 40px rgba(255, 61, 0, 0.25);
}

/* --- Card Titles --- */
.card h3 {
  font-size: 20px;
  color: #64b5f6;
  margin-bottom: 12px;
  text-transform: capitalize;
}

/* --- Card Text --- */
.card p {
  font-size: 15px;
  color: #ccc;
}

/* --- Preview Container --- */
.scroll-preview {
  position: absolute;
  bottom: 110%;
  left: 50%;
  transform: translateX(-50%);
  width: 420px;
  max-height: 360px;
  overflow-y: auto;
  background: #1a1a2e;
  border-radius: 10px;
  box-shadow: 0 6px 20px rgba(255, 61, 0, 0.25);
  display: none;
  z-index: 20;
}

.scroll-preview img {
  width: 100%;
  height: auto;
  border-radius: 10px;
}

.card:hover .scroll-preview {
  display: block;
  animation: fadeInPreview 0.3s ease-in-out;
}

/* --- Zoom Button --- */
.zoom-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(255, 61, 0, 0.9);
  color: #fff;
  border: none;
  padding: 6px 12px;
  font-size: 14px;
  border-radius: 5px;
  cursor: pointer;
  z-index: 25;
  transition: background 0.3s;
}

.zoom-btn:hover {
  background: #ff5722;
}

.card:hover .zoom-btn {
  display: block;
}

/* --- Zoom Modal --- */
#zoomModal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0;
  top: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.85);
  justify-content: center;
  align-items: center;
}

#zoomModal img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 10px;
  box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
}

/* --- Close Button --- */
#zoomModal .close {
  position: absolute;
  top: 20px;
  right: 30px;
  font-size: 30px;
  color: #f44336;
  cursor: pointer;
  transition: color 0.3s ease;
}

#zoomModal .close:hover {
  color: var(--primary-color);
}

/* --- Contact & Footer --- */
.contact {
  text-align: center;
  margin-top: 60px;
  border-top: 2px solid #333;
  padding-top: 30px;
}

.contact h2 {
  font-size: 22px;
  color: var(--primary-color);
}

.contact p {
  margin: 5px 0;
  color: #bbb;
}

footer {
  text-align: center;
  margin-top: 30px;
  padding: 20px;
  background-color: #0b0724;
  color: #aaa;
}

/* --- Animations --- */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInPreview {
  from { opacity: 0; transform: translate(-50%, 10px); }
  to { opacity: 1; transform: translate(-50%, 0); }
}

/* --- Responsive --- */
@media (max-width: 768px) {
  header h1 {
    font-size: 32px;
  }
 /* Header Styling */
/* Formal Header Style */
header {
  background-color: #1e2a38;
  padding: 50px 20px;
  text-align: center;
  color: #f4f4f4;
  border-bottom: 3px solid #ccc;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

header h1 {
  font-size: 36px;
  font-weight: 600;
  margin-bottom: 10px;
  color: #2c3e50;
}

header p {
  font-size: 18px;
  color: #d0d0d0;
  font-weight: 400;
  margin: 0 auto;
  max-width: 600px;
}

/* Professional Intro Style */
.intro {
  max-width: 800px;
  margin: 50px auto;
  padding: 30px;
  background-color: #f9f9f9;
  color: #333;
  border-left: 5px solid #1e2a38;
  border-radius: 8px;
  font-size: 16.5px;
  line-height: 1.8;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.intro strong {
  color: #1e2a38;
  font-weight: 600;
}


  </style>
 </head>
 <body>
 <div>

  <a href="index.php" class="btn btn-back px-3">← Back to Main Site</a>
 </div>
  
  <header>
  
   <h1>Explore EduConnect</h1>

<p>All Your Academic Tools. One Seamless Platform.</p>

  </header>
  <div class="container">
 
  <div class="intro">
  <p>
     <strong>EduConnect</strong> is your all-in-one academic hub — built for ICE students at NSTU to manage classes, notes, tasks, and more with ease.
  </p>
</div>


   <div class="grid">
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Schedule Preview" src="images/view_ClassShedule.png"/>
     </div>
     <h3>
      Class  Schedule
     </h3>
     <p>
      Stay updated with daily class .
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Notes Preview" src="images/shn33.png"/>
     </div>
     <h3>
      Note Sharing
     </h3>
     <p>
      Upload and explore academic notes organized by semester and type.
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
      <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="AI Preview" src="images/ai1111.png"/>
     </div>
     <h3>
      AI Assistant
     </h3>
     <p>
      Ask questions and receive instant AI-generated academic support.
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Feed Preview" src="images/Post333.png"/>
     </div>
     <h3>
      Discussion Feed
     </h3>
     <p>
      Post updates, share ideas, and engage through likes and comments.
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Group Preview" src="images/GroupShows000.png"/>
     </div>
     <h3>
      Group Collaboration
     </h3>
     <p>
      Join or create academic groups for focused discussions.
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Profile Preview" src="images/mdas.png"/>
     </div>
     <h3>
      Profile &amp; Task Manager
     </h3>
     <p>
      Manage personal info and keep track of your academic tasks.
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Exam Icon" src="images/examsview.png"/>
     </div>
     <h3>
      Exam Schedule
     </h3>
     <p>
      View upcoming exam dates, times, and seat plans semester-wise.
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Profile Preview" src="images/searchifynew.png"/>
     </div>
     <h3>
      User Finder
     </h3>
     <p>
      Filter users by batch, location, or blood group to connect and collaborate.
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Profile Preview" src="images/noti.png"/>
     </div>
     <h3>
      Notification System
     </h3>
     <p>
      Stay up to date with alerts for notes, schedules, and group activity.
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="Notes Preview" src="images/upl2.png"/>
     </div>
     <h3>
      Personal Note Library
     </h3>
     <p>
      Access your private PDF collection
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="To-Do Icon" src="images/to do new (1).png"/>
     </div>
     <h3>
      My To-Do List
     </h3>
     <p>
      Track and organize your daily tasks
     </p>
    </div>
    <div class="card">
     <div class="scroll-preview">
       <button class="zoom-btn" onclick="zoomImage(this)">🔍 Zoom</button>
      <img alt="PDF Icon" src="images/studash.png"/>
     </div>
     <h3>
      Student Dashboard
     </h3>
     <p>
      Access your academic tools, track tasks, and stay updated—all in one place.
     </p>
    </div>
   </div>
  <!-- Zoom Modal -->
<div id="zoomModal">
  <span class="close" onclick="closeZoom()">&times;</span>
  <img id="zoomedImg" src="" alt="Zoomed Image">
</div>

 </body>

 <script>
  function zoomImage(btn) {
    const imgSrc = btn.nextElementSibling.src;
    document.getElementById("zoomedImg").src = imgSrc;
    document.getElementById("zoomModal").style.display = "flex";
  }

  function closeZoom() {
    document.getElementById("zoomModal").style.display = "none";
  }
</script>

</html>