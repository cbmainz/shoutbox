<?php

// include configuration file
include('config.php');
	
// connect to the database
$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

// continue session
session_start();
	
// check for a user_id
if(!$_SESSION['user_id'])
{
	// redirect user to homepage if they are not signed in
	header("Location: /");	
}

?>
<!-- HTML -->
		
		<!-- top navigation -->
		<?php include('header.php'); ?>
		
		<!-- content -->	
		<div class="container" style="margin-top: 65px">

			<h2 class="text-primary">Hi <?php echo "{$_SESSION['nickname']}"; ?>!</h2>
			<?php
			
			// check for shout removal
			if($_GET['action'] == 'remove')
			{
				
				$sql = "SELECT user_id FROM shouts2 WHERE shout_id = '{$_GET['id']}' LIMIT 1";
				$result = mysqli_query($db, $sql) or die('Query failed: ' . mysqli_error($db));
				$row = mysqli_fetch_assoc($result);
				
				// check ownership
				if($row['user_id'] == $_SESSION['user_id'])
				{
					// delete shout
					$sql = "DELETE FROM shouts2 WHERE shout_id = '{$_GET['id']}' LIMIT 1";
					$result = mysqli_query($db, $sql) or die('Query failed: ' . mysqli_error($db));
				
					// display confirmation
					echo "<div class=\"alert alert-success\">Nachricht wurde erfolgreich gelöscht!<a href=\"activity.php\" class=\"alert-link pull-right close\">&times;</a></div>";
				}
			}
			
			// check for shout submission
			if(isset($_POST['submit']))
			{
				// empty error array
				$error = array();
				
				// check for a shout
				if(empty($_POST['shout']))
				{
					$error[] = 'Beitrag fehlt';
				}
				
				// if there are no errors, insert shout into the database.
				// otherwise, display errors.
				if(sizeof($error) == 0)
				{
					// insert shout
					$sql = "INSERT INTO shouts2 (shout_id, user_id, shout, shout_date) VALUES (null, '{$_SESSION['user_id']}', '{$_POST['shout']}', NOW())";
					$result = mysqli_query($db, $sql) or die('Query failed: ' . mysqli_error($db));
					
					// display confirmation
					echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Schliessen\"><span aria-hidden=\"true\">&times;</span></button>Nachricht wurde erfolgreich hinzugefügt!</div>";
					
				} else {
					
					// display error message
					foreach($error as $value)
					{
						echo "<div class=\"text-error\">{$value}</div>";
					}
					
				}
			}

			
			?>
			
			<!-- shoutbox form -->
			<form method="post" action="activity.php" style="margin-bottom: 25px">
				<div class="form-group">
					<textarea name="shout" placeholder="Was willst du schreiben?" class="form-control" rows="5"></textarea>
				</div>
				<input name="submit" type="submit" value="Senden" class="btn btn-primary" />
			</form>
			
			<!-- show messages -->
			<div class="messages">
			<?php include('messages.php'); ?>
			</div>

	</body>
</html>