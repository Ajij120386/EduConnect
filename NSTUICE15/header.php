
<?php


	session_start();
	include 'conn.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
		
	  <style>
	  body{
		margin:0px;
		padding:0px;
		overflow-x: hidden;
	  
	  }
		.container-fluid{
			width:100%;
		
		}
		/* Make navbar semi-transparent with backdrop blur */
.navbar {
  background-color: rgba(52, 73, 94, 0.8); /* semi-transparent */
  backdrop-filter: blur(8px); /* glass effect */
  -webkit-backdrop-filter: blur(8px);
  border: none;
  transition: background-color 0.3s ease-in-out;
}

/* Navbar links hover effect */
.navbar-inverse .navbar-nav>li>a {
  color: #ecf0f1;
  font-weight: 500;
  transition: color 0.3s, background-color 0.3s;
}

.navbar-inverse .navbar-nav>li>a:hover {
  background-color: rgba(230, 126, 34, 0.8); /* orange hover */
  color: white !important;
  border-radius: 6px;
}

/* Navbar brand image hover effect */
.navbar-brand img:hover {
  transform: scale(1.05);
  transition: transform 0.3s ease;
}

/* Button styles */
.btn {
  transition: all 0.3s ease;
  font-weight: 600;
}

.btn:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Make buttons look more modern */
.btn-danger {
  background-color: #e74c3c;
  border: none;
  color: white;
}

.btn-danger:hover {
  background-color: #c0392b;
}

.btn-default {
  background-color: white;
  border: 2px solid #3498db;
  color: #3498db;
}

.btn-default:hover {
  background-color: #3498db;
  color: white;
}

/* Smooth scroll */
html {
  scroll-behavior: smooth;
}



.dropdown-hover {
	margin-top: 7px;
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: white;
  min-width: 140px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
  z-index: 1;
  border-radius: 6px;
  overflow: hidden;
}

.dropdown-content a {
  color: #333;
  padding: 10px 16px;
  text-decoration: none;
  display: block;
  font-weight: 500;
}

.dropdown-content a:hover {
  background-color: #3498db;
  color: white;
}

.dropdown-hover:hover .dropdown-content {
  display: block;
}


	  </style>
	  <title> NSTU ICE 15 Batch - Official</title>
	  <link rel="icon" href="images/icon.png">
	</head>	
	
	<body data-spy="scroll" data-target=".navbar" data-offset="50">
	
	<!--Code for Navebar-->
	<nav class="navbar navbar-fixed-top navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">
					<img style="display:inline-block;margin-top: -15px;" src="images/logo.jpeg" width="150px" height="45px">
				</a>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mynav">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			
			<div id="mynav" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Home</a></li>
					<li><a href="AcademicInfo.php">AcademicInfo</a></li>
					<li><a href="api.php">EduBot</a></li>
					<li><a href="Explore_System.php">Explore System</a></li>
					<li><a href="contact_me.php">Contact Me</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right" style="margin-right:2%">
					<?php
						if(isset($_SESSION['id']))
						{
							echo '
  <form method="post">
    <a href="logout.php" class="btn btn-danger" style="margin: 7px;">Log Out</a>
    <a href="world/world.php" class="btn btn-default" name="world_btn" style="margin: 7px;">EduConnect</a>
  </form>
';
						}
						else{

						echo '

  <div class="dropdown-hover" style="display:inline-block;">
    <button class="btn btn-danger dropdown-toggle" type="button">Log In</button>
    <div class="dropdown-content">
      <a href="login.php?role=student">Student</a>
      <a href="login.php?role=teacher">Teacher</a>
    </div>
  </div>

  <a href="registration_modal.php" class="btn btn-default" style="margin-left: 10px;">Register</a>
';


									

							
							
							
						} 
					?>
				</ul>				
			</div>
		</div>
	</nav>

	





</body>
</html>