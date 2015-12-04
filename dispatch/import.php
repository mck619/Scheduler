<?php
require 'conn.php';

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

	$sql = "SELECT * FROM import";
	
	$result = $connect->query($sql) or die($connect->error);
	
	while ($result2 = $result->fetch_assoc()) {

  echo '<h1>Import an organization</h1>';
  echo '<form action="organization.php" method="post">';
  echo '<p>Organization Name: <input type="text" name="orgname" required maxlength="100" value="' . $result2["name"] . '">';
  echo '<p>Street: <input type="text" name="orgstreet" maxlength="100" value="' . $result2["street"] . '">';
  echo '<p>City: <input type="text" name="orgcity" maxlength="60" value="' . $result2["city"] . '">';
  echo 'State: <input type="text" name="orgstate" maxlength="2" value="' . $result2["state"] . '">';
  echo 'ZIP: <input type="text" name="orgzip" maxlength="10" value="' . $result2["zip"] . '">';
  echo '<p>Phone: <input type="text" name="orgphone" maxlength="30" value="' . $result2["phone"] . '">';
  echo '<p>E-mail: <input type="text" name="orgemail" maxlength="100" value="' . $result2["email"] . '">';
  echo '<p>Contact Person: <input type="text" name="orgcontact" maxlength="60" value="' . $result2["contact"] . '">';
  echo '<p><a href="categorydisplay.php" target="_new">Category</a>: ';

  echo '<select name="orgcategory">';

// retrieve organization categories from table orgcategory

$sql2 = "SELECT orgcat_id, orgcat_name FROM orgcategory ORDER BY orgcat_name";
$result3 = $connect->query($sql2) or die($connect->error);

// make each category a value in the dropdown box

while ($result4 = $result3->fetch_assoc()) {

  if ($result4["orgcat_id"] == $result2["category"]) {
  echo '<option value="' . $result4["orgcat_id"] . '" selected>**' . $result4["orgcat_name"] . '**</option>';	  
  }
  else {
	  echo '<option value="' . $result4["orgcat_id"] . '">' . $result4["orgcat_name"] . '</option>';
  }
}

echo '</select>';

echo '<p>Notes: <br />';

echo '<textarea name="orgnotes" cols="100" rows="10">' . $result2["notes"] . '</textarea>';


echo '</p>';

echo '<p><input type="submit" /> <input type="reset" /><p></form>';

	}

?>
		

