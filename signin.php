<?php

// include configuration file
include('config.php');
	
// connect to the database
$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

// continue session
session_start();

// if the submit button has been pressed
if(isset($_POST['submit']))
{
	// create an empty error array
	$error = array();
	
	// check for a nickname
	if(empty($_POST['nickname']))
	{
		$error['nickname'] = 'Pflichtfeld';
	} 
	
	// check for a password
	if(empty($_POST['userpass']))
	{
		$error['userpass'] = 'Pflichtfeld';
	} 
	
	// check signin credentials
	if(!empty($_POST['nickname']) && !empty($_POST['userpass']))
	{
		// get user_id from the users table
		$sql = "SELECT 
					user_id, 
					nickname
				FROM 
					users 
				WHERE 
					nickname = '{$_POST['nickname']}' AND userpass = sha1('{$_POST['userpass']}') 
				LIMIT 1";
		$result = mysqli_query($db, $sql);
		$row = mysqli_fetch_assoc($result);
		
		// if the user is not found
		if(!$row['user_id'])
		{
			$error['user'] = 'Falsche E-Mail und/oder Passwort';
		}
	}
	
	// if there are no errors
	if(sizeof($error) == 0)
	{
		// append user variables to session
		$_SESSION['user_id'] = $row['user_id'];
		$_SESSION['nickname'] = $row['nickname'];
		
		// redirect user to profile page
		header("Location: activity.php");
		exit();

	} 
}

?>
<!-- HTML -->
	
		<!-- top navigation -->
		<?php include('header.php'); ?>
		
		<!-- content -->	
		<div class="container" style="margin-top: 65px">
		
			<h2 class="text-primary">Anmeldung</h2>
			<p class="text-muted">Mit Nickname und Passwort angemelden:</p>
			
			<?php
				// check for a user error
				if($error['user'])
				{
					echo "<div class=\"text-danger\">{$error['user']}</div>";
				}
			?>
						
			<!-- signin form -->
			<form method="post" action="signin.php">
				
				<!-- nickname -->
				<div class="form-group">
					<input name="nickname" placeholder="Nickname" type="text" value="<?php echo $_POST['nickname']; ?>" class="form-control" />
					<span class="text-danger"><?php echo $error['nickname']; ?></span>
				</div>
				
				<!-- password -->
				<div class="form-group">
					<input id="password" name="userpass" placeholder="Passwort" type="password" class="form-control" value="<?php echo $error['userpass']; ?>" />
					<span class="text-danger"><?php echo $error['userpass']; ?></span>
				</div>
				
				<!-- submit button -->
				<div class="form-group">
					<input name="submit" type="submit" value="Anmelden" class="btn btn-primary" />
				</div>

			</form>
			
			<!-- sign up link -->
			<p>Noch keinen Account? Dann hier <a href="signup.php"><i class="fa fa-pencil-square-o"></i>registrieren</a>!</p>
			
		</div>
	
	</body>
</html>