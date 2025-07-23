




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="NSTU ICE 15 Batch">
    <meta name="keywords" content="NSTU ICE 15 Batch">
    <meta name="author" content="Mohammad Ajij">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSTU ICE 15 Batch - Official</title>
    <style type="text/css">
       
    </style>
</head>
<body>


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
					<li><a href="#our_works_div">Explore Us</a></li>
					<li><a href="#we_belongs_div">We Belongs</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right" style="margin-right:2%">
					<?php
						if(isset($_SESSION['id']))
						{
							echo "
								<form method='post'>
									<a type='submit' href='logout.php' class='btn btn-danger'>Log Out</a>
									
									<a type='submit' href='world/world.php' class='btn btn-default' name='world_btn'>EduConnect</a>


								</form>							
							";
						}
						else{
							echo "

							<form method='post'>
									<a type='submit' href='login.php' class='btn btn-danger'>Log In</a>
									
									<a type='submit' href='registration_modal.php' class='btn btn-default' name='world_btn'>Register</a>


								</form>			

							
							
								";
						} 
					?>
				</ul>				
			</div>
		</div>
	</nav>

	

    <?php include 'about_us.php'; ?>
    <?php include 'contact_me.php'; ?>

</body>
</html>
