
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Import Organization - Step 2</title>
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

  $os1 = $_POST['orgid'];
  $trimnotes = trim($_POST["orgnotes"]); 

  $completion = 0;
		
if ($_POST['action'] == 'new') {

  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO organization (org_name, org_street, org_city, org_state, org_zip, org_phone, org_email, org_contact, org_category, org_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('ssssssssis', $_POST["orgname"], $_POST["orgstreet"], $_POST["orgcity"], $_POST["orgstate"], $_POST["orgzip"], $_POST["orgphone"], $_POST["orgemail"], $_POST["orgcontact"], $_POST["orgcategory"], $trimnotes);
	$stmt->execute();
	$stmt->close();

	$sql3 = "UPDATE organization SET org_import=1 WHERE org_id=$os1";
	$result6 = $connect2->query($sql3) or die($connect2->error);
	$completion++;
	
  }
  
   // retrieve the id of the record just entered
  
  $result = $connect->insert_id;
  
  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM organization WHERE org_id=$result";
  $result2 = $connect->query($sql2) or die($connect->error);  	
  
}

elseif ($_POST['action'] == 'matched') {
	
	$os1 = $_POST['formid'];
	$os2 = $_POST['orgid'];
	
	$sql6 = "SELECT * FROM organization WHERE org_id=$os1";
	
	// retrieve data from existing organization

	$sql7 = "SELECT * FROM organization WHERE org_id=$os2";
	
	$result11 = $connect2->query($sql6) or die($connect2->error);  

	$result12 = $connect->query($sql7) or die($connect->error);  
	
	$result13 = $result11->fetch_array(MYSQLI_ASSOC);
	$result14 = $result12->fetch_array(MYSQLI_ASSOC);	
	
	echo '<p>';
	
	if ($_POST['orgnameradio'] == NULL) {
		echo '<strong>Error: Must specify Organization Name</strong><br>';
	}
	if ($_POST['orgstreetradio'] == NULL) {
		echo '<strong>Error: Must specify Street</strong><br>';
	}
	if ($_POST['orgcityradio'] == NULL) {
		echo '<strong>Error: Must specify City</strong><br>';
	}
	if ($_POST['orgstateradio'] == NULL) {
		echo '<strong>Error: Must specify State</strong><br>';
	}
	if ($_POST['orgzipradio'] == NULL) {
		echo '<strong>Error: Must specify ZIP code</strong><br>';
	}
	if ($_POST['orgphoneradio'] == NULL) {
		echo '<strong>Error: Must specify Phone number</strong><br>';
	}
	if ($_POST['orgcontactradio'] == NULL) {
		echo '<strong>Error: Must specify Contact Person</strong><br>';
	}
	if ($_POST['orgemailradio'] == NULL) {
		echo '<strong>Error: Must specify E-mail</strong><br>';
	}
	if ($_POST['orgnotesradio'] == NULL) {
		echo '<strong>Error: Must specify Notes</strong><br>';
	}
	
	// organization name 
	
	if ($_POST['orgnameradio'] == 'existing') {
		
		$orgnamedata = $result14['org_name'];
	}
	elseif ($_POST['orgnameradio'] == 'new') {
		$orgnamedata = $result13['org_name'];		
	}
	elseif ($_POST['orgnameradio'] == 'custom') {
		$orgnamedata = $_POST['orgnamecustom'];		
	}

	// street
	
	if ($_POST['orgstreetradio'] == 'existing') {
		
		$orgstreetdata = $result14['org_street'];
	}
	elseif ($_POST['orgstreetradio'] == 'new') {
		$orgstreetdata = $result13['org_street'];		
	}
	elseif ($_POST['orgstreetradio'] == 'custom') {
		$orgstreetdata = $_POST['orgstreetcustom'];		
	}

	// city
	
	if ($_POST['orgcityradio'] == 'existing') {
		
		$orgcitydata = $result14['org_city'];
	}
	elseif ($_POST['orgcityradio'] == 'new') {
		$orgcitydata = $result13['org_city'];		
	}
	elseif ($_POST['orgcityradio'] == 'custom') {
		$orgcitydata = $_POST['orgcitycustom'];		
	}

	// state
	
	if ($_POST['orgstateradio'] == 'existing') {
		
		$orgstatedata = $result14['org_state'];
	}
	elseif ($_POST['orgstateradio'] == 'new') {
		$orgstatedata = $result13['org_state'];		
	}
	elseif ($_POST['orgstateradio'] == 'custom') {
		$orgstatedata = $_POST['orgstatecustom'];		
	}

	// zip
	
	if ($_POST['orgzipradio'] == 'existing') {
		
		$orgzipdata = $result14['org_zip'];
	}
	elseif ($_POST['orgzipradio'] == 'new') {
		$orgzipdata = $result13['org_zip'];		
	}
	elseif ($_POST['orgzipradio'] == 'custom') {
		$orgzipdata = $_POST['orgzipcustom'];		
	}

	// phone
	
	if ($_POST['orgphoneradio'] == 'existing') {
		
		$orgphonedata = $result14['org_phone'];
	}
	elseif ($_POST['orgphoneradio'] == 'new') {
		$orgphonedata = $result13['org_phone'];		
	}
	elseif ($_POST['orgphoneradio'] == 'custom') {
		$orgphonedata = $_POST['orgphonecustom'];		
	}

	// e-mail
	
	if ($_POST['orgemailradio'] == 'existing') {
		
		$orgemaildata = $result14['org_email'];
	}
	elseif ($_POST['orgemailradio'] == 'new') {
		$orgemaildata = $result13['org_email'];		
	}
	elseif ($_POST['orgemailradio'] == 'custom') {
		$orgemaildata = $_POST['orgemailcustom'];		
	}

	// contact
	
	if ($_POST['orgcontactlradio'] == 'existing') {
		
		$orgcontactdata = $result14['org_contact'];
	}
	elseif ($_POST['orgcontactradio'] == 'new') {
		$orgcontactdata = $result13['org_contact'];		
	}
	elseif ($_POST['orgcontactradio'] == 'custom') {
		$orgcontactdata = $_POST['orgcontactcustom'];		
	}

	// notes
	
	if ($_POST['orgnotesradio'] == 'existing') {
		
		$orgnotesdata = $result14['org_notes'];
	}
	elseif ($_POST['orgnotesradio'] == 'new') {
		$orgnotesdata = $result13['org_notes'];		
	}
	elseif ($_POST['orgemailradio'] == 'custom') {
		$orgnotesdata = $_POST['orgnotescustom'];		
	}

  $stmt = $connect->stmt_init();
  
  $sql8 = "UPDATE organization SET org_name=?, org_street=?, org_city=?, org_state=?, org_zip=?, org_phone=?, org_email=?, org_contact=?,  org_notes=? WHERE org_id=$os2";
  
  $trimnotes = trim($orgnotesdata); 
  
  if ($stmt->prepare($sql8)) {
  
	// bind parameters
		
	$stmt->bind_param('sssssssss', $orgnamedata, $orgstreetdata, $orgcitydata, $orgstatedata, $orgzipdata, $orgphonedata, $orgemaildata, $orgcontactdata, $trimnotes);
	$stmt->execute();
	$stmt->close();
	  
  }

	$sql9 = "UPDATE organization SET org_import=1 WHERE org_id=$os1";
	$result15 = $connect2->query($sql9) or die($connect2->error);
	$completion++;

  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM organization WHERE org_id=$os2";
  $result2 = $connect->query($sql2) or die($connect->error);  	
	
}

elseif ($_POST['action'] == 'match') {
	$os2 = $_POST['orgid2'];
}

elseif ($_POST['action'] == 'list') {
	$os2 = $_POST['orgid3'];
}

else {
echo '<p>Sorry, you must provide a valid import ID';	
}

if (($_POST['action'] == 'match') || ($_POST['action'] == 'list')) {
	
      // retrieve form data

	  $sql4 = "SELECT * FROM organization WHERE org_id=$os1";
	  
	  // retrieve data from existing organization

	  $sql5 = "SELECT * FROM organization WHERE org_id=$os2";
      $result7 = $connect2->query($sql4) or die($connect2->error);  
 
      $result8 = $connect->query($sql5) or die($connect->error);  
	  
	  $result9 = $result7->fetch_array(MYSQLI_ASSOC);
	  $result10 = $result8->fetch_array(MYSQLI_ASSOC);
	  
	  // create the table 
	  
	  echo '<h1>Assigning Form to Existing Organization</h1>';
	  
	  echo '<form><table border="1"><tr><th>Field</th><th>Existing Information</th><th>Form Information</th><th>Edit Data</th></tr>';

	  echo '<input type="hidden" name="startdate" value="' . $_POST['startdate'] . '">';
	  echo '<input type="hidden" name="enddate" value="' . $_POST['enddate'] . '">';
	  echo '<input type="hidden" name="orgid" value="' . $os2 . '">';
	  echo '<input type="hidden" name="formid" value="' . $os1 . '">';
	  
	  // compare organization name
	  
	  echo '<tr><th>Organization Name</th>';
	  
	  if (strcasecmp($result10['org_name'], $result9['org_name']) == 0) {
		echo '<td colspan="3"><input type="hidden" name="orgnameradio" value="existing">' . $result10['org_name'] . '</td></tr>';  
	  }
	  else {
		echo '<td><input type="radio" name="orgnameradio" value="existing">' . $result10['org_name'] . '</td><td><input type="radio" name="orgnameradio" value="new">' . $result9['org_name'] . '</td><td><input type="radio" name="orgnameradio" value="custom">' . '<input type="text" name="orgnamecustom" value="' . $result9['org_name'] . '"></td></tr>';
		  
	  }

	  // compare organization address
	  
	  echo '<tr><th>Street</th>';
	  
	  if (strcasecmp($result10['org_street'], $result9['org_street']) == 0) {
		echo '<td colspan="3"><input type="hidden" name="orgstreetradio" value="existing">' . $result10['org_street'] . '</td></tr>';  
	  }
	  else {
		echo '<td><input type="radio" name="orgstreetradio" value="existing">' . $result10['org_street'] . '</td><td><input type="radio" name="orgstreetradio" value="new">' . $result9['org_street'] . '</td><td><input type="radio" name="orgstreetradio" value="custom">' . '<input type="text" name="orgstreetcustom" value="' . $result9['org_street'] . '"></td></tr>';
		  
	  }

	  // compare organization city
	  
	  echo '<tr><th>City</th>';
	  
	  if (strcasecmp($result10['org_city'], $result9['org_city']) == 0) {
		echo '<td colspan="3"><input type="hidden" name="orgcityradio" value="existing">' . $result10['org_city'] . '</td></tr>';  
	  }
	  else {
		echo '<td><input type="radio" name="orgcityradio" value="existing">' . $result10['org_city'] . '</td><td><input type="radio" name="orgcityradio" value="new">' . $result9['org_city'] . '</td><td><input type="radio" name="orgcityradio" value="custom">' . '<input type="text" name="orgcitycustom" value="' . $result9['org_city'] . '"></td></tr>';
		  
	  }

  	  // compare organization state
	  
	  echo '<tr><th>State</th>';
	  
	  if (strcasecmp($result10['org_state'], $result9['org_state']) == 0) {
		echo '<td colspan="3"><input type="hidden" name="orgstateradio" value="existing">' . $result10['org_state'] . '</td></tr>';  
	  }
	  else {
		echo '<td><input type="radio" name="orgstateradio" value="existing">' . $result10['org_state'] . '</td><td><input type="radio" name="orgstateradio" value="new">' . $result9['org_state'] . '</td><td><input type="radio" name="orgstateradio" value="custom">' . '<input type="text" name="orgstatecustom" value="' . $result9['org_state'] . '"></td></tr>';
		  
	  }

	  // compare organization zip
	  
	  echo '<tr><th>ZIP</th>';
	  
	  if (strcasecmp($result10['org_zip'], $result9['org_zip']) == 0) {
		echo '<td colspan="3"><input type="hidden" name="orgzipradio" value="existing">' . $result10['org_zip'] . '</td></tr>';  
	  }
	  else {
		echo '<td><input type="radio" name="orgzipradio" value="existing">' . $result10['org_zip'] . '</td><td><input type="radio" name="orgzipradio" value="new">' . $result9['org_zip'] . '</td><td><input type="radio" name="orgzipradio" value="custom">' . '<input type="text" name="orgzipcustom" value="' . $result9['org_zip'] . '"></td></tr>';
		  
	  }
	  
  	  // compare organization phone
	  
	  echo '<tr><th>Phone</th>';
 
	  if (strcasecmp(preg_replace("/\D/","",($result10['org_phone'])), preg_replace("/\D/","",($result9['org_phone']))) == 0) {
		echo '<td colspan="3"><input type="hidden" name="orgphoneradio" value="existing">' . $result10['org_phone'] . '</td></tr>';  
	  }
	  else {
		echo '<td><input type="radio" name="orgphoneradio" value="existing">' . $result10['org_phone'] . '</td><td><input type="radio" name="orgphoneradio" value="new">' . $result9['org_phone'] . '</td><td><input type="radio" name="orgphoneradio" value="custom">' . '<input type="text" name="orgphonecustom" value="' . $result9['org_phone'] . ' ' . $result10['org_phone'] . '"></td></tr>';
		  
	  }
	  
 	  // compare organization e-mail
	  
	  echo '<tr><th>E-mail</th>';
	  
	  if (strcasecmp($result10['org_email'], $result9['org_email']) == 0) {
		echo '<td colspan="3"><input type="hidden" name="orgemailradio" value="existing">' . $result10['org_email'] . '</td></tr>';  
	  }
	  else {
		echo '<td><input type="radio" name="orgemailradio" value="existing">' . $result10['org_email'] . '</td><td><input type="radio" name="orgemailradio" value="new">' . $result9['org_email'] . '</td><td><input type="radio" name="orgemailradio" value="custom">' . '<input type="text" name="orgemailcustom" value="' . $result9['org_email'];
		if ($result10['org_email'] != NULL) {
			
		echo ', ' . $result10['org_email']; 
		}
		
		echo '"></td></tr>';
		  
	  }

  	  // compare organization contact
	  
	  echo '<tr><th>Contact Person</th>';
	  
	  if (strcasecmp($result10['org_contact'], $result9['org_contact']) == 0) {
		echo '<td colspan="3"><input type="hidden" name="orgcontactradio" value="existing">' . $result10['org_contact'] . '</td></tr>';  
	  }
	  else {
		echo '<td><input type="radio" name="orgcontactradio" value="existing">' . $result10['org_contact'] . '</td><td><input type="radio" name="orgcontactradio" value="new">' . $result9['org_contact'] . '</td><td><input type="radio" name="orgcontactradio" value="custom">' . '<input type="text" name="orgcontactcustom" value="' . $result9['org_contact'];
		
		if ($result10['org_contact'] != NULL) {
			
		echo ', ' . $result10['org_contact'];
		}
		
		echo '"></td></tr>';
	 
		  
	  }	  
	  
	   // compare organization notes
	  
	  echo '<tr><th>Notes</th>';
	  
      echo '<td><input type="radio" name="orgnotesradio" value="existing">' . nl2br($result10['org_notes']) . '</td><td><input type="radio" name="orgnotesradio" value="new">' . nl2br($result9['org_notes']) . '</td><td><input type="radio" name="orgnotesradio" value="custom">' . '<textarea name="orgnotescustom">' . nl2br($result9['org_notes']) . "\n" . nl2br($result10['org_notes']) . '</textarea>';
		
	  echo '</td></tr>';
	  	  
	  echo '</table>';
	  
	  echo '<button name="action" value="matched" type="submit" formmethod="post" formaction="importbridge.php">Update Existing Organization</button><input type="reset"></form>';
	  
}


if  ($completion > 0) {

// fetch results of data

$result3 = $result2->fetch_array(MYSQLI_ASSOC);

// assign each field from the array to a variable

$orgid = $result3['org_id'];
$orgname = $result3['org_name'];
$orgstreet = $result3['org_street'];
$orgcity = $result3['org_city'];
$orgstate = $result3['org_state'];
$orgzip = $result3['org_zip'];
$orgphone = $result3['org_phone'];
$orgemail = $result3['org_email'];
$orgcontact = $result3['org_contact'];
$orgcategory = $result3['org_category'];
$orgnotes = $result3['org_notes'];

// convert $orgcategory (which returns the id of the category in table orgcategory) to the associated name

$sql3 = "SELECT orgcat_name FROM orgcategory WHERE orgcat_id=$orgcategory";

$result4 = $connect->query($sql3) or die($connect->error);

$result5 = $result4->fetch_array(MYSQLI_ASSOC);

// print record just entered

echo '<h1>';
if ($_POST['action'] == 'new')  {
	echo 'New Organization created!';
}

else {
	echo 'Organization successfully updated!';	
}

echo '</h1>';
echo $orgid . ' - ' . $orgname . "<br>";
echo 'Address: ' . $orgstreet . "<br>";
echo $orgcity . ', ' . $orgstate . ' ' . $orgzip . "<br>";
echo 'Phone number: ' . $orgphone . "<br>";
echo "E-mail: " . $orgemail . "<br>";
echo 'Contact person: ' . $orgcontact . "<br>";
echo 'Category: ' . $result5['orgcat_name'] . "<br>";
echo nl2br('Notes:<br>' . $orgnotes);

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="orgid" value="' . $orgid . '">';
echo '<input type="hidden" name="startdate" value="' . $_POST['startdate'] . '">';
echo '<input type="hidden" name="enddate" value="' . $_POST['enddate'] . '">';

echo '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Create Food Drive for ' . $orgname . '</button>';
echo '</form>';

echo '<form>';
echo '<input type="hidden" name="orgid" value="' . $orgid . '">';
echo '<button type="submit" formmethod="post" formaction="orgadd.php">Edit Info for ' . $orgname . '</button>';
echo '</form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="orgdisplay.php">View Organization List</button>';
echo '</form>';
	
}

$connect->close();

?>

</body>
</html>