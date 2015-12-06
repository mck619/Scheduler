
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adding a food drive staff person</title>
<?php require 'include.php' ?>	
</head>

<body>

<?php 

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

if (isset($_POST['staffid'])) {
	$os1 = $connect->real_escape_string($_POST["staffid"]);
	$sql = "SELECT * FROM staff WHERE staff_id=$os1";
	
	$result = $connect->query($sql) or die($connect->error);
	
	$result2 = $result->fetch_assoc();
	echo '<h1>Editing a staff member</h1>';
	echo '<form action="staff.php" method="post">';
	echo '<input type="hidden" name="staffid" value="' . $os1 . '">';
	echo '<p>Staff Name: <input type="text" name="staffname" value="' . $result2["staff_name"] . '" required maxlength="30" />';
	echo '<p>Is staff member active? <label for="yes"><input type="radio" name="staffactive" id="yes" value="1" ';
	if ($result2["staff_active"] == 1){
		echo 'checked';
	}
	echo '>Yes</label><label for="no"><input type="radio" name="staffactive" id="no" value="0" ';
	if ($result2["staff_active"] == 0){
		echo 'checked';
	}
	echo '>No</label>';
}


else {
	echo '<h1>Adding a new food drive staff person</h1>';
	echo '<form action="staff.php" method="post">';
	echo '<p>Staff Name: <input type="text" name="staffname" required maxlength="30" />';
	echo '<p>Is staff member active? <label for="yes"><input type="radio" name="staffactive" id="yes" value="1" checked>Yes</label><label for="no"><input type="radio" name="staffactive" id="no" value="0">No</label>';
}
?>
<p>
<input type="submit" />
<input type="reset" />
<p>
</form>

</body>
</html>
