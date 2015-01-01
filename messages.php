
<?php	
// include configuration file
include('config.php');

// connect to the database
$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

// continue session
session_start();

// select all shouts from the database		
$sql = "SELECT shout_id, user_id, shout, DATE_FORMAT(shout_date,'%d.%m.%Y, %H:%i Uhr') AS formatted_date FROM shouts2 ORDER BY shout_date DESC";
$result = mysqli_query($db, $sql) or die('Query failed: ' . mysqli_error($db));
while ($row = mysqli_fetch_assoc($result)) 
{
// get user information
$sql2 = "SELECT user_id, nickname FROM users WHERE user_id = '{$row['user_id']}'";
$result2 = mysqli_query($db, $sql2);
$row2 = mysqli_fetch_assoc($result2);

// display shout (two columns - left column display the image; right column displays the text)
echo "<div class=\"well\">";
echo "<div class=\"row\">";

echo "<div class=\"col-md-1\">";

// check for a profile image
if(file_exists('photos/' . $row['user_id'] . '.jpg'))
{

// If the user has a profile image on file, display the user's profile image
echo "<img src=\"photos/{$row['user_id']}.jpg\" class=\"img-rounded hidden-xs profileimage\" />";

} else {

// If the user does not have a profile image on file, display a default profile image
echo "<img src=\"photos/noimage.png\" class=\"img-rounded hidden-xs profileimage\" />";

}

echo "</div>";
echo "<div class=\"col-md-11\">";

// check ownership
if($row['user_id'] == $_SESSION['user_id'])
{	
echo "<a href=\"activity.php?action=remove&id={$row['shout_id']}\" class=\"pull-right btn btn-danger\">&times</a>";
}

// display name and shout
echo "<p><strong>{$row2['nickname']}</strong> schreibt:</p>";
echo "<p>{$row['shout']}</p>";
echo "<span class=\"text-muted\">{$row['formatted_date']}<span>";
echo "</div>";
echo "</div>";
echo "</div>";
}
?>

</div>
<script>
function refresh_div() {
	jQuery.ajax({
		url:'messages.php',
		type:'POST',
		success:function(results) {
			jQuery(".messages").html(results);
		}
	});
}

t = setInterval(refresh_div,3600);
</script>