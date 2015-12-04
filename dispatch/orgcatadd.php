<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adding a Food Drive Category</title>
<?php require 'include.php' ?>
</head>

<body>


<?php

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

if (isset($_POST['orgcatid'])) {
	$os1 = $connect->real_escape_string($_POST["orgcatid"]);

	$sql2 = "SELECT * FROM orgcategory WHERE orgcat_id=$os1";
	
	$result3 = $connect->query($sql2) or die($connect->error);
	
	$result4 = $result3->fetch_assoc();

  echo '<h1>Editing an organization category</h1>';
  echo '<form action="orgcat.php" method="post">';
  echo '<input type="hidden" name="orgcatid" value="' . $os1 . '">';
  echo '<p>Category Name: <input type="text" name="orgcatname" required maxlength="20" value="' . $result4["orgcat_name"] . '">';
  echo '<p>Category Description:<br> <textarea name="orgcatnotes" cols="100" rows="10">' . $result4["orgcat_notes"] . '</textarea>';

}

else {

echo '<h1>Adding a new food drive category</h1>';
echo '<form action="orgcat.php" method="post">';
echo '<p>Category Name: <input type="text" name="orgcatname" required maxlength="20" />';
echo '<p>Category Description:<br> <textarea name="orgcatnotes"  cols="100"  rows="10"></textarea>';

}

?>

<p>
<input type="submit" />
<input type="reset" />
<p>
</form>

</body>
</html>
