<head><?php require 'include.php' ?></head>

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


	$sql = "SELECT * FROM organization WHERE org_import=0";
	
	$result = $connect2->query($sql) or die($connect2->error);

	echo '<h1>Import from Online Form Entries';
	echo '<table border="1"><tr>';
	echo '<th>ID</th><th>Organization Name</th><th>Address</th><th>Contact Phone & E-mail</th><th>Notes</th><th>Start Date</th><th>End Date</th><th>Action</th></tr>';
	
	while ($result2 = $result->fetch_assoc()) {

  echo '<tr><td>';
  
  $startdate = new DateTime($result2['org_startdate']);
  $enddate = new DateTime($result2['org_enddate']);
  
  echo $result2['org_id'] . '</td><td>' . $result2['org_name'] . '</td><td>' . $result2['org_street'] . '<br>' . $result2['org_city'] . ', ' . $result2['org_state'] . ' ' . $result2['org_zip'] . '</td><td>' . $result2['org_contact'] . '<br>' . $result2['org_phone'] . '<p><a href="mailto:' . $result2['org_email'] . '">' . $result2['org_email'] . '</a></td><td>' . nl2br($result2['org_notes']) . '</td><td>' . $startdate->format('l, F jS, Y') . '</td><td>' . $enddate->format('l, F jS, Y') . '</td><td><form>';
  
  echo '<input type="hidden" name="orgid" value="' . $result2['org_id'] . '">';
  
  echo '<button type="submit" formmethod="post" formaction="importorg.php">Import</button>';
  
  echo '<br><button type="submit" formmethod="post" formaction="importdelete.php">Delete Without Entry</button>';
  
  echo '</form></td></tr>';

	}
	
  echo '</table>';

	

/*

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

*/

?>
		

