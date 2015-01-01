<?php

// include configuration file
include('config.php');

// Include SimpleImage library (https://github.com/claviska/SimpleImage)
include('abeautifulsite/SimpleImage.php');
	
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

// If the form has been submitted, update user information
if(isset($_POST['submit']))
{
	// Create an error array
	$error = array();
	
	// Check for a nickname
	if(empty($_POST['nickname']))
	{
		$error['nickname'] = 'Pflichtfeld';
	} else {
		$nickname = $_POST['nickname'];
	}
	
	// Check for a email
	if(empty($_POST['email']))
	{
		$error['email'] = 'Pflichtfeld';
	} else {
	
		// Check to see if email address is unique
		$query = "select user_id from users where email = '{$_POST['email']}'";
		$result = mysqli_query($db, $query);
		$row = mysqli_fetch_assoc($result);
		if(mysqli_num_rows($result) > 0)
		{
			// Check to see if this email address is owned by this user
			if($row['user_id'] != $_SESSION['user_id'])
			{
				$error['email'] = 'Diese E-Mail wird bereits genutzt';
			}
		}
		
		$email = $_POST['email'];
	}
	
	// check for an image
	if($_FILES['file']['tmp_name'])
	{
		// check for a general error
		if ($_FILES['file']['error'] > 0)
		{
			$error['file'] = 'Ein Fehler ist aufgetaucht';
		}
	
		// check for valid file type
		if (($_FILES["file"]["type"] != "image/gif")   &&
			($_FILES["file"]["type"] != "image/jpeg")  &&
			($_FILES["file"]["type"] != "image/pjpeg") &&
			($_FILES["file"]["type"] != "image/png"))
		{
			$error['file'] = 'Keine gültige Bilddatei';
		}
	}
	
	// if there are no errors
	if(sizeof($error) == 0)
	{
		// edit user information in the users table
		$query = "update users set 
						nickname = '{$_POST['nickname']}', 
						email = '{$_POST['email']}'
				 	where
				 		user_id = '{$_SESSION['user_id']}'";
		$result = mysqli_query($db, $query);
		
		// update the photo is applicable
		if($_FILES['file']['tmp_name'])
		{
			// upload photo (https://github.com/claviska/SimpleImage)
			try
			{
				// initialize simpleImage
				$img = new abeautifulsite\SimpleImage($_FILES['file']['tmp_name']);
				
				// create a small photo
				$img->fit_to_width(250)->save('photos/' . $_SESSION['user_id'] . '.jpg');
				
				// create a large photo
				$img->fit_to_width(800)->save('photos/large_' . $_SESSION['user_id'] . '.jpg');    
			
			} catch(Exception $e) {
				echo 'Error: ' . $e->getMessage();
			}
		}
		
		// Redirect user to profile page (with a confirmation)
		header("Location: profile.php?confirmation=profile");
		exit();
				
	} 

// If the form has not been submitted, get user information so that we can fill in the default form values
} else {

	// Get user information
	$query = "SELECT nickname, email FROM users WHERE user_id = '{$_SESSION['user_id']}'";
	$result = mysqli_query($db, $query);
	$row = mysqli_fetch_assoc($result);
	
	// Assign user information to template
	$nickname = $row['nickname'];
	$email = $row['email'];
	$biography = $row['biography'];
	
}

?>
<!-- HTML -->
		
		<!-- top navigation -->
		<?php include('header.php'); ?>
		
		<!-- content -->	
		<div class="container" style="margin-top: 65px">

			<h2 class="text-primary"><?php echo "{$_SESSION['nickname']}"; ?></h2>
			
			<?php
				
				// display a confirmation message if applicable
				if($_GET['confirmation'] == 'profile')
				{
					echo "<div class=\"alert alert-success\">Die Änderungen wurden gespeichert</div>";
				}
			
			?>
			
			<!-- bootstrap row -->
			<div class="row">
			
				<!-- left column -->
				<div class="col-md-3">
				
					<?php
					
						// Check if the user has a profile image on file 
						if(file_exists('photos/' . $_SESSION['user_id'] . '.jpg'))
						{
							// Assign time to prevent image caching
							$timestamp = time();
							
							// If the user has a profile image on file, display the user's profile image
							echo "<img src=\"photos/{$_SESSION['user_id']}.jpg?time={$timestamp}\" class=\"img-rounded profileimage\"  />";
							
						} else {
						
							// If the user does not have a profile image on file, display a default profile image
							echo "<img src=\"photos/large_noimage.png\" class=\"img-rounded profileimage\" />";
							
						}
					?>
				
				</div>
				
				<!-- right column -->
				<div class="col-md-9">
					
					<!-- edit profile form -->
					<form method="post" enctype="multipart/form-data" action="profile.php">
						
						<!-- first name -->
						<div class="form-group">
							<label>Nickname</label>
							<input name="nickname" type="text" value="<?php echo $nickname; ?>" autocomplete="off" class="form-control" />
							<span class="text-danger"><?php echo $error['nickname']; ?></span>
						</div>

						<!-- email -->
						<div class="form-group">
							<label>E-Mail</label>
							<input name="email" type="text" value="<?php echo $email; ?>" autocomplete="off" class="form-control" />
							<span class="text-danger"><?php echo $error['email']; ?></span>
						</div>
						
						<!-- profile photo -->
						<div class="form-group">
							<label for="file">Profil-Foto</label>
							<input id="file" name="file" type="file" />
							<span class="text-danger"><?php echo $error['file']; ?></span>
						</div>
						
						<!-- submit button -->
						<input name="submit" type="submit" value="Speichern" class="btn btn-primary" />
						
					</form>
	
				</div>
			
			</div>
						
		</div>
	
	</body>
</html>