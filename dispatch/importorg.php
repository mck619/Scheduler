<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Import Organization</title>
<?php require 'include.php' ?>
</head>
<body>
<?php

require '../schedule/conn.php';

// Connect to MySQL

$connect = $conn;
$connect2 = $conn2;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

if($connect2->connect_error){
    die('Connection error: ('.$connect2->connect_errno.'): '.$connect2->connect_error);
        } 

if (!isset($_POST['orgid'])) {
echo '<p>Sorry, a organization must be specified.</p>';

echo '<form><button method="post" type="submit" formaction="importform.php">Return to Import</button></form>';
die;
	
}

else {

echo '<h1>Import an Organization</h1>';

echo '<p><strong>Please note that each site of an organization should be added as a separate organization.  Use the category field to group together organization sites if needed.</p>';

echo '<table><tr><td>';

$orgid = $_POST['orgid'];

$sql = "SELECT * FROM organization WHERE org_id=$orgid";

$result = $connect2->query($sql) or die($connect2->error);

while ($result2 = $result->fetch_assoc()) {

$orgname = stripslashes(htmlentities($result2["org_name"]));
$orgstreet = stripslashes(htmlentities($result2["org_street"]));

  echo '<form action="importbridge.php" method="post">';
  echo '<input type="hidden" name="orgid" value="' . $result2['org_id'] . '">';
  echo '<input type="hidden" name="startdate" value="' . $result2['org_startdate'] . '">';
  echo '<input type="hidden" name="enddate" value="' . $result2['org_enddate'] . '">';
  echo 'Organization Name: <input type="text" name="orgname" required maxlength="100" value="' . $orgname . '">';
  echo '<p>Street: <input type="text" name="orgstreet" maxlength="100" value="' . $orgstreet . '">';
  echo '<p>City: <input type="text" name="orgcity" maxlength="60" value="' . stripslashes(htmlentities($result2["org_city"])) . '">';
  echo 'State: <input type="text" name="orgstate" maxlength="2" value="' . stripslashes(htmlentities($result2["org_state"])) . '">';
  echo 'ZIP: <input type="text" name="orgzip" maxlength="10" value="' . stripslashes(htmlentities($result2["org_zip"])) . '">';
  echo '<p>Phone: <input type="text" name="orgphone" maxlength="30" value="' . stripslashes(htmlentities($result2["org_phone"])) . '">';
  echo '<p>E-mail: <input type="text" name="orgemail" maxlength="100" value="' . stripslashes(htmlentities($result2["org_email"])) . '">';
  echo '<p>Contact Person: <input type="text" name="orgcontact" maxlength="60" value="' . stripslashes(htmlentities($result2["org_contact"])) . '">';
  echo '<p><a href="categorydisplay.php" target="_new">Category</a>: ';

  echo '<select name="orgcategory">';

// retrieve organization categories from table orgcategory

$sql2 = "SELECT orgcat_id, orgcat_name FROM orgcategory ORDER BY orgcat_name";
$result3 = $connect->query($sql2) or die($connect->error);

// make each category a value in the dropdown box

while ($result4 = $result3->fetch_assoc()) {

  echo '<option value="' . $result4["orgcat_id"] . '"';
  
  if ($result4["orgcat_id"] == 1) {
	echo ' selected';  
  }
  
  echo '>' . $result4["orgcat_name"] . '</option>';

}

echo '</select>';

echo '<p>Notes: <br />';

echo '<textarea name="orgnotes" cols="100" rows="8">' . stripslashes(htmlentities($result2["org_notes"])) . '</textarea>';


echo '</p>';

echo '<p><button name="action" value="new" type="submit" formmethod="post" formaction="importbridge.php">Add New Organization to Database</button><input type="reset" /><p>';

}

echo '</td></tr>';

echo '<tr><td>';

echo '<h1>Possible Matches in Existing Database</h1>';

// perform address search

$match = 0;

$searchaddress = explode(" ", $orgstreet);

$searchaddresskeyword = $searchaddress[0] . ' ' . $searchaddress[1];

$sql4 = "SELECT COUNT(*) FROM organization WHERE MATCH(org_street) AGAINST ('$searchaddresskeyword')";

$result7 = $connect->query($sql4) or die($connect->error);

$result8 = $result7->fetch_array(MYSQLI_NUM);

if ($result8[0] == 0) {
	
	// perform organization name search
	
	$sql6 = "SELECT COUNT(*) FROM organization WHERE MATCH(org_name) AGAINST ('$orgname')";
	
	$result11 = $connect->query($sql6) or die($connect->error);
	
	$result12 = $result11->fetch_array(MYSQLI_NUM);
	
	if ($result12[0] == 0) {
		  echo '<p>Sorry, no matches were found</p>';
		  		  
	}
	
	else {
	
		$sql7 = "SELECT * FROM organization WHERE MATCH(org_name) AGAINST ('$orgname')";
		
		$match++;
		
		$result9 = $connect->query($sql7) or die($connect->error);
		
	}
	
	
}

else {

$sql5 = "SELECT * FROM organization WHERE MATCH(org_street) AGAINST ('$searchaddresskeyword')";

$match++;

$result9 = $connect->query($sql5) or die($connect->error);

}

if ($match != 0) {

echo '<select name="orgid2">';

while ($result10 = $result9->fetch_assoc()) {

echo '<option value="' . $result10['org_id'] . '">' . $result10['org_name'] . ' - ' . $result10['org_street'] . ', ' . $result10['org_city'] . ', ' . $result10['org_state'] . ' ' . $result10['org_zip'] . '</option>';
	
}

echo '</select><p><button name="action" value="match" type="submit" formmethod="post" formaction="importbridge.php">Assign to Existing Organization</button><input type="reset"></p>';

}

echo '<tr><td valign="top"><h1>Select from Existing Organization</h1>';

$sql3 = "SELECT * FROM organization ORDER BY org_name";
$result5 = $connect->query($sql3) or die($connect->error);

echo '<select name="orgid3">';

while ($result6 = $result5->fetch_assoc()) {

echo '<option value="' . $result6['org_id'] . '">' . $result6['org_name'] . ' - ' . $result6['org_street'] . ', ' . $result6['org_city'] . ', ' . $result6['org_state'] . ' ' . $result6['org_zip'] . '</option>';
	
}

echo '</select><p><button name="action" value="list" type="submit" formmethod="post" formaction="importbridge.php">Assign to Existing Organization</button><input type="reset"></p></form></td>';


echo '</td></tr>';

echo '</tr></table>';
	
}

?>

</body>
</html>