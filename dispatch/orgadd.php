<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adding/Editing an Organization</title>

<?php require 'include.php' ?>
</head>

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 
		
?>

<body>

<?php
if (isset($_POST['orgid'])) {
	$os1 = $connect->real_escape_string($_POST["orgid"]);

	$sql2 = "SELECT * FROM organization WHERE org_id=$os1";
	
	$result3 = $connect->query($sql2) or die($connect->error);
	
	$result4 = $result3->fetch_assoc();
	
	$os2 = $result4["org_category"];
	
  echo '<h1>Editing an organization</h1>';
  echo '<form action="organization.php" method="post">';
  echo '<input type="hidden" name="orgid" value="' . $os1 . '">';
  echo '<p>Organization Name: <input type="text" name="orgname" required maxlength="100" value="' . $result4["org_name"] . '">';
  echo '<p>Street: <input type="text" name="orgstreet" maxlength="100" value="' . $result4["org_street"] . '">';
  echo '<p>City: <input type="text" name="orgcity" maxlength="60" value="' . $result4["org_city"] . '">';
  echo 'State: <input type="text" name="orgstate" maxlength="2" value="' . $result4["org_state"] . '">';
  echo 'ZIP: <input type="text" name="orgzip" maxlength="10" value="' . $result4["org_zip"] . '">';
  echo '<p>Phone: <input type="text" name="orgphone" maxlength="30" value="' . $result4["org_phone"] . '">';
  echo '<p>E-mail: <input type="text" name="orgemail" maxlength="100" value="' . $result4["org_email"] . '">';
  echo '<p>Contact Person: <input type="text" name="orgcontact" maxlength="60" value="' . $result4["org_contact"] . '">';
  echo '<p><a href="categorydisplay.php" target="_new">Category</a>: ';

}

else {
  echo '<h1>Adding a new organization</h1>';
  echo '<form action="organization.php" method="post">';
  echo '<p>Organization Name: <input type="text" name="orgname" required maxlength="100" />';
  echo '<p>Street: <input type="text" name="orgstreet" maxlength="100"/>';
  echo '<p>City: <input type="text" name="orgcity" maxlength="60"/>';
  echo 'State: <input type="text" name="orgstate" maxlength="2"/>';
  echo 'ZIP: <input type="text" name="orgzip" maxlength="10"/>';
  echo '<p>Phone: <input type="text" name="orgphone" maxlength="30"/>';
  echo '<p>E-mail: <input type="text" name="orgemail" maxlength="100"/>';
  echo '<p>Contact Person: <input type="text" name="orgcontact" maxlength="60"/>';
  echo '<p><a href="orgcatdisplay.php" target="_new">Category</a>: ';

}
?>

<select name="orgcategory">

<?php

// retrieve organization categories from table orgcategory

$sql = "SELECT orgcat_id, orgcat_name FROM orgcategory ORDER BY orgcat_name";
$result = $connect->query($sql) or die($connect->error);

// make each category a value in the dropdown box

while ($result2 = $result->fetch_assoc()) {
  if ($os2 == $result2["orgcat_id"]) {
  echo '<option value="' . $result2["orgcat_id"] . '" selected>**' . $result2["orgcat_name"] . '**</option>';	  
  }
  else {
	  echo '<option value="' . $result2["orgcat_id"] . '">' . $result2["orgcat_name"] . '</option>';
  }
}




echo '</select>';

echo '<p>Notes: <br />';

if (isset($_POST['orgid'])) {
  echo '<textarea name="orgnotes" cols="100" rows="10">' . $result4["org_notes"] . '</textarea>';
}

else {
  echo '<textarea name="orgnotes"  cols="100"  rows="10"></textarea> ';
}
echo '</p>';
?>
<p>
<input type="submit" />
<input type="reset" />
<p>
</form>

</body>
</html>
