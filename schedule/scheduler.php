<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Confirmation</title>
</head>

<?php 
require 'conn.php';

$connect = $conn2;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

$errcount = 0;
		
if (!isset($_POST['type'])) {
	echo '<h1>Verify Food Drive information</h1>';

	echo '<p><strong>Organization Name</strong>: ';
			
	if (trim($_POST['orgname']) == "") {
		echo '<strong><font color="#ff0000">Please fill in Organization Name</font></strong>';
		$errcount++;
	}
	else {
	  echo stripslashes(htmlentities($_POST['orgname']));
	}
  echo '<p><strong>Street:</strong> ';
  
	if (trim($_POST['orgstreet']) == "") {
		echo '<strong><font color="#ff0000">Please fill in Street Address</font></strong>';
		$errcount++;
	}
	else {
	  echo stripslashes(htmlentities($_POST['orgstreet']));
	}  
  echo '<p><strong>City:</strong> ';
	if (trim($_POST['orgcity']) == "") {
		echo '<strong><font color="#ff0000">Please fill in City name</font></strong>';
		$errcount++;
	}
	else {
	  echo stripslashes(htmlentities($_POST['orgcity']));
	}
 

  echo ' <strong>State:</strong> ';
  
  	if ($_POST['orgstate'] != "CA") {
		echo '<strong><font color="#ff0000">Sorry, we cannot do food drives outside of California</font></strong>';
		$errcount++;
	}
	else {
	  echo stripslashes(htmlentities($_POST['orgstate']));
	}

  echo ' <strong>ZIP:</strong> ';
  
  function isValidZIPCode($zipcode)
    {
        return (preg_match("/^[0-9]{5}(-[0-9]{4})?$/i", trim($zipcode)) > 0) ? true : false;
    } 
  
  if (isValidZIPCode($_POST['orgzip']) == false) {
	$errcount++;  
	echo '<strong><font color="#ff0000">Invalid ZIP code!</font></strong>';
  }
  
  if (isValidZIPCode($_POST['orgzip']) == true) {
  
   echo stripslashes(htmlentities($_POST['orgzip']));
  }

  function isValidPhone($phone)
  {
	$result = preg_replace("/\D/","",$phone);
	if (substr($result, 0, 1) == 1) {
		$result = substr($result, 1);	
	}
	
	if (strlen($result) < 10) {
		return false;		
	}
	
	else {
		return true;
	}
	  
  }
  
  echo '<p><strong>Phone:</strong> ';

	if (trim($_POST['orgphone']) == "") {
		echo '<strong><font color="#ff0000">Please enter a contact phone number</font></strong>';
		$errcount++;
	}
	elseif (isValidPhone($_POST['orgphone']) == false) {
		echo '<strong><font color="#ff0000">Please enter a valid phone number</font></strong>';
		$errcount++;		
	}
	else {
	  echo stripslashes(htmlentities($_POST['orgphone']));
	}  
  
  echo '<p><strong>E-mail:</strong> ';
  
  if (trim($_POST['orgemail']) != trim($_POST['orgemailconf'])) {
		echo '<strong><font color="#ff0000">Please ensure that your e-mail address is correct in both fields</font></strong>';
		$errcount++;		  
  }
  
  elseif (filter_var(trim($_POST['orgemail']), FILTER_VALIDATE_EMAIL) == FALSE) {
		echo '<strong><font color="#ff0000">Please enter a valid e-mail address</font></strong>';
		$errcount++;	  
  }
  
  else {
  echo stripslashes(htmlentities($_POST['orgemail']));
  }
  
  echo '<strong><p>Contact Person:</strong> ';

	if (trim($_POST['orgcontact']) == "") {
		echo '<strong><font color="#ff0000">' . "Please enter a contact person's name</font></strong>";
		$errcount++;
	}
	else {
	  echo stripslashes(htmlentities($_POST['orgcontact']));
	}

$startdate = new DateTime($_POST['startdate']);
$enddate = new DateTime($_POST['enddate']);
$curdate = new DateTime();
$curdate->modify('6 days');





echo '<p><strong>Start Date:</strong> ';

if ($startdate < $curdate) {
		echo '<strong><font color="#ff0000">' . "Start Date must be at least a week from today</font></strong><br>";
		$errcount++;
}

else {

echo $startdate->format('l, F jS, Y') . "<br>";

}
echo '<strong>End Date:</strong> ';

if ($enddate < $startdate) {
		echo '<strong><font color="#ff0000">' . "End Date must be after the start date</font></strong>";
		$errcount++;	  
	
}

else {
echo $enddate->format('l, F jS, Y');  
}
  echo '<strong><p>Comments, special instructions, etc:</strong><br>' . nl2br($_POST['orgnotes']);
  echo '<p>';
  
  if ($errcount == 0) {
	echo '<form method="post" action="scheduler.php">';
	echo '<input type="hidden" name="type" value="1">';
	echo '<input type="hidden" name="orgname" value="' . stripslashes(htmlentities($_POST['orgname'])) . '">';
	echo '<input type="hidden" name="orgstreet" value="' . stripslashes(htmlentities($_POST['orgstreet'])) . '">';
	echo '<input type="hidden" name="orgcity" value="' . stripslashes(htmlentities($_POST['orgcity'])) . '">';		
	echo '<input type="hidden" name="orgstate" value="' . stripslashes(htmlentities($_POST['orgstate'])) . '">';
	echo '<input type="hidden" name="orgzip" value="' . stripslashes(htmlentities($_POST['orgzip'])) . '">';	
	echo '<input type="hidden" name="orgphone" value="' . stripslashes(htmlentities($_POST['orgphone'])) . '">';	
	echo '<input type="hidden" name="orgemail" value="' . stripslashes(htmlentities($_POST['orgemail'])) . '">';	
	echo '<input type="hidden" name="orgemailconf" value="' . stripslashes(htmlentities($_POST['orgemailconf'])) . '">';	
	echo '<input type="hidden" name="orgcontact" value="' . stripslashes(htmlentities($_POST['orgcontact'])) . '">';
	echo '<input type="hidden" name="orgnotes" value="' . stripslashes(htmlentities($_POST['orgnotes'])) . '">';		
	echo '<input type="hidden" name="startdate" value="' . stripslashes(htmlentities($_POST['startdate'])) . '">';
	echo '<input type="hidden" name="enddate" value="' . stripslashes(htmlentities($_POST['enddate'])) . '">';			

  echo '<button type="submit" formmethod="post" formaction="scheduler.php">Submit Food Drive Request</button></form>';
  }

	echo '<form method="post" action="http://www.wsfb.org/fooddrive.php">';
	echo '<input type="hidden" name="type" value="1">';
	echo '<input type="hidden" name="orgname" value="' . stripslashes(htmlentities($_POST['orgname'])) . '">';
	echo '<input type="hidden" name="orgstreet" value="' . stripslashes(htmlentities($_POST['orgstreet'])) . '">';
	echo '<input type="hidden" name="orgcity" value="' . stripslashes(htmlentities($_POST['orgcity'])) . '">';		
	echo '<input type="hidden" name="orgstate" value="' . stripslashes(htmlentities($_POST['orgstate'])) . '">';
	echo '<input type="hidden" name="orgzip" value="' . stripslashes(htmlentities($_POST['orgzip'])) . '">';	
	echo '<input type="hidden" name="orgphone" value="' . stripslashes(htmlentities($_POST['orgphone'])) . '">';	
	echo '<input type="hidden" name="orgemail" value="' . stripslashes(htmlentities($_POST['orgemail'])) . '">';	
	echo '<input type="hidden" name="orgemailconf" value="' . stripslashes(htmlentities($_POST['orgemailconf'])) . '">';	
	echo '<input type="hidden" name="orgcontact" value="' . stripslashes(htmlentities($_POST['orgcontact'])) . '">';
	echo '<input type="hidden" name="orgnotes" value="' . stripslashes(htmlentities($_POST['orgnotes'])) . '">';		
	echo '<input type="hidden" name="startdate" value="' . stripslashes(htmlentities($_POST['startdate'])) . '">';
	echo '<input type="hidden" name="enddate" value="' . stripslashes(htmlentities($_POST['enddate'])) . '">';			
    echo '<button type="submit" formmethod="post" formaction="http://www.wsfb.org/fooddrive.php">Edit Food Drive Request</button>';
  
  die;
}

if ($_POST['type'] == 1) {
  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO organization (org_name, org_street, org_city, org_state, org_zip, org_phone, org_email, org_contact, org_notes, org_startdate, org_enddate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $trimnotes = trim($_POST["orgnotes"]);   
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('sssssssssss', $_POST["orgname"], $_POST["orgstreet"], $_POST["orgcity"], $_POST["orgstate"], $_POST["orgzip"], $_POST["orgphone"], $_POST["orgemail"], $_POST["orgcontact"], $trimnotes, $_POST["startdate"], $_POST["enddate"]);
	$stmt->execute();
	$stmt->close();
	  
  // retrieve the id of the record just entered
  
  $result = $connect->insert_id;
  
  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM organization WHERE org_id=$result";

}

$result2 = $connect->query($sql2) or die($connect->error);

// fetch results of data

$result3 = $result2->fetch_array(MYSQLI_ASSOC);	

echo '<h1>Your request was successfully sent!</h1>';

// temporary message about gmail problem

echo '<p>We are aware that confirmation e-mails are not being received if you have an e-mail account hosted by Google.  We are working to get this remedied as soon as possible.  In the meantime, please contact <a href="http://www.wsfb.org/index.php?option=com_contact&task=view&contact_id=5&Itemid=106">Allison Griffith</a> if you have any questions.  We apologize for the inconvenience.</p>';

echo '<p><strong>Organization Name:</strong> ' . $result3['org_name'];
echo '<p><strong>Street:</strong> ' . $result3['org_street'];
echo '<br><strong>City:</strong> ' . $result3['org_city'] . ' <strong>State:</strong> ' . $result3['org_state'] . ' <strong>ZIP:</strong> ' . $result3['org_zip'];
echo '<p><strong>Phone:</strong> ' . $result3['org_phone'];
echo '<p><strong>E-mail:</strong> ' . $result3['org_email'];
echo '<p><strong>Contact Person:</strong> ' . $result3['org_contact'];

$startdate = new DateTime($result3['org_startdate']);
$enddate = new DateTime($result3['org_enddate']);

echo '<p><strong>Start Date:</strong> ' . $startdate->format('l, F jS, Y') . "<br>";
echo '<strong>End Date:</strong> ' . $enddate->format('l, F jS, Y');

echo '<p><strong>Comments, special instructions, etc:</strong><br>';
echo nl2br($result3['org_notes']);

$emailheader = 'From: fooddrive@wsfb.org';

$emailheader2 = 'From: ' . $result3['org_email'];

$emailbody = "This is to confirm that a food drive request was submitted to Westside Food Bank.  You will be contacted within two business days for scheduling.\n\n" . 'Organization Name: ' . $result3['org_name'] . "\n" . 'Address: ' . $result3['org_street'] . ', ' . $result3['org_city'] . ', '. $result3['org_state'] . ' ' . $result3['org_zip'] . "\n" . 'Phone: ' . $result3['org_phone'] . "\n" . 'E-mail: ' . $result3['org_email'] . "\n" . 'Contact Person: ' . $result3['org_contact'] . "\n" . 'Start Date: ' . $startdate->format('l, F jS, Y') . "\n" . 'End Date: ' . $enddate->format('l, F jS, Y'). "\n" . 'Notes: ' . $result3['org_notes'];

$emailbody = wordwrap($emailbody);

mail($result3['org_email'],'Westside Food Bank Food Drive Request',$emailbody,$emailheader);

mail('fooddrive@wsfb.org, fooddrive@kamikazepenguins.com','Westside Food Bank Food Drive Request',$emailbody,$emailheader2);

}

?>



<body>
</body>
</html>