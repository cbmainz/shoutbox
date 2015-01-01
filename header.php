<!DOCTYPE html>
<html>
	<head>
		<title>CHANGE</title> <!-- change title -->
		<meta charset="utf-8">
		<meta name="robots" content="noindex, nofollow"> <!-- change if needed -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- jQuery -->
		<script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script>

		<!-- bootstrap -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">	
		
		<!-- fontawesome -->
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		
		<style type="text/css">
			.profileimage {
				border: 1px solid #ccc; 
				width: 100%;
			}
			.fa {
				margin-right: 4px; 
			}
		</style>				
	</head>
	<body>

		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		  <div class="container">
			  
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle toggle-menu menu-left push-body" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="#"><span class="text-primary"><i class="fa fa-bullhorn fa-lg"></i>LINK</span></a> <!-- change link -->
			</div>
		
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">
						<?php
							// If the user is signed in
							if($_SESSION['user_id'])
							{
								echo "<li><a href=\"activity.php\"><i class=\"fa fa-comments-o fa-lg\"></i>Nachrichten</a></li>";
								echo "<li><a href=\"profile.php\"><i class=\"fa fa-user\"></i>Profil</a></li>";
								echo "<li><a href=\"signout.php\"><i class=\"fa fa-sign-out\"></i>Logout</a></li>";
							} else {
								echo "<li><a href=\"/\"><i class=\"fa fa-comments-o fa-lg \"></i>Nachrichten</a></li>";
								echo "<li><a href=\"signin.php\"><i class=\"fa fa-sign-in\"></i>Anmelden</a></li>";
								echo "<li><a href=\"signup.php\"><i class=\"fa fa-pencil-square-o\"></i>Registrieren</a></li>";
							}
						?>
				  </ul>
				</li>
			  </ul>
			</div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>