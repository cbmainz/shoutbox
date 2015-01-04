<?php

// include configuration file
include('config.php');
	
// connect to the database
$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

?>
<!-- HTML -->
	
		<!-- top navigation -->
		<?php include('header.php'); ?>
		
		<!-- content -->	
		<div class="container" style="margin-top: 65px">
		
			<h2 class="text-primary">Nachrichten</h2>
	
			<!-- sign in link -->
			<p class="text-muted">Um Nachrichten zu schreiben, musst du angemeldet sein.</p>
			<div class="form-group">
			<p><a class="btn btn-primary" href="signin.php">Anmelden</a></p>
			</div>
					
			<!-- show messages -->
			<div class="messages">
			<?php include('messages.php'); ?>
			</div>
			
		</div>
	
	</body>
</html>