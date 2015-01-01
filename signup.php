<?php

// include configuration file
include('config.php');
	
// connect to the database
$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

// continue session
session_start();

// if the form has been submitted
if(isset($_POST['submit']))
{
	// create an empty error array
	$error = array();

	// check for a nickname
	if(empty($_POST['nickname']))
	{
		$error['nickname'] = 'Pflichtfeld';
	} 

	
	// check for a email
	if(empty($_POST['email']))
	{
		$error['email'] = 'Pflichtfeld';
	} else {
	
		// check to see if email address is unique
		$sql = "SELECT user_id FROM users WHERE email = '{$_POST['email']}'";
		$result = mysqli_query($db, $sql);
		if(mysqli_num_rows($result) > 0)
		{
			$error['email'] = 'Es gibt bereits einen Benutzer mit dieser E-Mail!';
		}
	}
	
	// check for a password
	if(empty($_POST['userpass']))
	{
		$error['userpass'] = 'Pflichtfeld';
	} 
	
	// if there are no errors
	if(sizeof($error) == 0)
	{
		// insert user into the users table
		$sql = "INSERT INTO users (
					user_id, 
					nickname, 
					email, 
					userpass,
					signupdate
				) VALUES (
					null,
					'{$_POST['nickname']}',
					'{$_POST['email']}',
					sha1('{$_POST['userpass']}'),
					NOW()
					)";
		$result = mysqli_query($db, $sql);
		
		// obtain user_id from table
		$user_id = mysqli_insert_id($db);
		
		// send a signup e-mail to user
		$webmaster = 'name <user@yourdomain.com>';   // from email
		$message = "Hallo {$_POST['nickname']},\n";
		$message = $message . "die Registrierung bei yourdomain.com war erfolgreich!\n"; // change domain
		$headers = "Content-type: text/plain\n";
		mail($_POST['email'], 'Registrierung erfolgreich', $message, "From: $webmaster");
		mail($webmaster, 'Neue Registrierung', "{$_POST['nickname']} hat sich mit der E-Mailadresse {$_POST['email']} bei yourdomain.com regestriert.", "From: {$_POST['nickname']} <{$_POST['email']}>"); // change domain
		
		// append user_id to session array
		$_SESSION['user_id'] = $user_id;
		$_SESSION['nickname'] = $_POST['nickname'];
		
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
		
			<h2 class="text-primary">Registrierung</h2>
			<p class="text-muted">Mit Nickname, E-Mail und Passwort regestrieren:</p>

			<!-- signup form -->
			<form method="post" action="signup.php">
				
				<!-- first name -->
				<div class="form-group">
					<input name="nickname" placeholder="Nickname" type="text" value="<?php echo $_POST['nickname']; ?>" class="form-control" />
					<span class="text-danger"><?php echo $error['nickname']; ?></span>
				</div>
				
				<!-- e-mail -->
				<div class="form-group">
					<input name="email" type="text" placeholder="E-Mail" value="<?php echo $_POST['email']; ?>" class="form-control" />
					<span class="text-danger"><?php echo $error['email']; ?></span>
				</div>
				
				<!-- password -->
				<div class="form-group">
					<input name="userpass" placeholder="Passwort" type="password" class="form-control" />
					<span class="text-danger"><?php echo $error['userpass']; ?></span>
				</div>
				
				<!-- submit button -->
				<div class="form-group">
					<input name="submit" type="submit" value="Registrieren" class="btn btn-primary" />
				</div>
				
			</form>
			
			<!-- sign in link -->
			<p>Hast du schon einen Account? Dann hier <a href="signin.php"><i class="fa fa-sign-in"></i>anmelden</a>!</p>
			
		</div>
	
	</body>
</html>