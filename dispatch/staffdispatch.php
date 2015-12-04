<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dispatch List</title>
<?php require 'include.php' ?>	</head>
<?php 


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// retrieve dropoff information

if (isset($_POST['staffid'])) {

  $os1 = $connect->real_escape_string($_POST["staffid"]);
  $sql2 = "SELECT staff_name FROM staff WHERE staff_id=$os1";
  $result5 = $connect->query($sql2) or die($connect->error);
  $result6 = $result5->fetch_array(MYSQLI_ASSOC);

}

else {
  echo '<p>You must select a staff member before using this view.';
  die;
}



// make a table showing list of dropoffs

if (isset($_POST['startdate'])) {

  $os2 = $connect->real_escape_string($_POST["startdate"]);
  
  if (isset($_POST['enddate'])) {
	
	$os3 = $connect->real_escape_string($_POST["enddate"]);
	  
  }
  
  else {
	
	$os3 = $connect->real_escape_string($_POST["startdate"]);
	  
  }
  
  $sql = "SELECT * FROM view_dropoffpickupexpanded WHERE (dppp_date BETWEEN '$os2' AND '$os3' AND staff_id=$os1) ORDER BY dppp_date";
  
  $result = $connect->query($sql) or die($connect->error);

  $reportdate = new DateTime($os2);
  $reportdateend = new DateTime($os3);
    
  echo '<h1>All Dropoffs and Pickups for ' . $result6["staff_name"] . ' from '. $reportdate->format('D, M jS, Y') . ' to ' . $reportdateend->format('D, M jS, Y') . '</h1>';
	
}


else {

  $sql = "SELECT * FROM view_dropoffpickupexpanded WHERE staff_id=$os1 AND dppp_complete=0 ORDER BY dppp_date";
  $result = $connect->query($sql) or die($connect->error);


echo '<h1>Current Dropoffs and Pickups for ' . $result6["staff_name"] . '</h1>';

}

echo '<form>';
echo '<input type="hidden" name="staffid" value="' . $os1 . '">';
echo 'Start Date: <input type="textbox" name="startdate" id="startdate" required>';
echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#startdate" ).datepicker({ minDate: "-1Y", maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";

echo 'End Date: <input type="textbox" name="enddate" id="enddate">';
echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#enddate" ).datepicker({ minDate: "-1Y", maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";


echo '<button type="submit" formmethod="post" formaction="staffdispatch.php">Set Date Range</button></form>';

if (isset($_POST['startdate'])) {
	echo '<p>This is a list of all dropoffs and pickups in the selected date range, sorted by date:</p>';
	
}

else {
	echo '<p>This is a list of all dropoffs and pickups that have not yet been completed, sorted by date:</p>';
}

echo '<table border="1"><tr><th>ID</th><th>Category</th><th>Organization Name &amp; Address</th><th>Contact Phone & E-mail</th><th>Organization &amp; Food Drive Notes</th><th>Dropoff or Pickup Date</th><th>Items Required</th><th>Completed?</th><th>Action</th></tr>';



// run through each dropoff

$curdate = new DateTime();

while ($result2 = $result->fetch_assoc()) {

  echo '<tr><td>' . $result2["dppp_id"] . '</td><td>' . $result2["orgcat_name"] . '</td><td>' . $result2["org_name"] . '<br>' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"] . '</td><td>' . $result2["org_contact"] . '<br>' . $result2["org_phone"] . '<br>' . '<a href="mailto:' . $result2["org_email"] . '">' . $result2["org_email"] . '</a></td><td>';

  echo nl2br($result2['org_notes']);
  echo '<p>';  
  echo nl2br($result2['fooddrive_notes']);
  
  $startdate = new DateTime($result2["dppp_date"]);
  
  if ($startdate <= $curdate) {
	
	echo '</td><td><span class="expired">' . $result2["type"] . '<br>' . $startdate->format('D, M jS, Y') . '</span></td>';
	  
  }
  
  else {
  
	echo '</td><td>' . $result2["type"] . '<br>' . $startdate->format('D, M jS, Y') . '</td>';
  
  }

 
  echo '<td>';

  // create list of items for each dropoff

if ($result2["type"] == 'dropoff') {
  
  $sql2 = "SELECT * from view_dropoffitemexpanded WHERE dropoff_id=$result2[dppp_id]";

}

if ($result2["type"] == 'pickup') {
	
  $sql2 = "SELECT * from view_pickupitemexpanded WHERE pickup_id=$result2[dppp_id]";

}
  
  $result3 = $connect->query($sql2) or die($connect->error);
  
  while ($result4 = $result3->fetch_assoc()) {
	  if ($result2["type"] == 'dropoff') {
	  echo $result4["item_name"] . ': ' . $result4["dpitem_amount"] . '<br>';
	  }
	  
	  if ($result2["type"] == 'pickup') {
		  echo $result4["item_name"] . ': ' . $result4["ppitem_amount"] . '<br>';
	  }
  }
  
  echo '</td>';
  
  echo '<td>';
  
  if ($result2["dppp_complete"] == 1) {
	echo 'Yes';  
  }
  
  if ($result2["dppp_complete"] == 0) {
	echo 'No';  
  }
  
  
  echo '</td>';

if ($result2["type"] == 'dropoff') {

  echo '<td><form><input type="hidden" name="dropoffid" value="' . $result2['dppp_id'] . '">';
  echo '<input type="hidden" name="fooddriveorg" value="' . $result2['fooddrive_id'] . '">';
  echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Edit Dropoff</button><br>';
  
if ($result2["dppp_complete"] == 0) {

  echo '<button type="submit" formmethod="post" formaction="dropoffpickupcomplete.php">Complete Dropoff</button><br>';
  
}
  echo '<button type="submit" formmethod="get" formaction="dropoffsingle.php">View Detail</button><br>';

if ($result2["dppp_complete"] == 0) {

  echo '<button type="submit" formmethod="post" formaction="dropoffdelete.php">Delete Dropoff</button>';
}  
  
}

if ($result2["type"] == 'pickup') {

  echo '<td><form><input type="hidden" name="pickupid" value="' . $result2['dppp_id'] . '">';
  echo '<input type="hidden" name="fooddriveorg" value="' . $result2['fooddrive_id'] . '">';
  echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Edit Pickup</button><br>';
  
if ($result2["dppp_complete"] == 0) {
  echo '<button type="submit" formmethod="post" formaction="dropoffpickupcomplete.php">Complete Pickup</button><br>';
}

  echo '<button type="submit" formmethod="get" formaction="pickupsingle.php">View Detail</button><br>';
  
if ($result2["dppp_complete"] == 0) {

  echo '<button type="submit" formmethod="post" formaction="pickupdelete.php">Delete Pickup</button>';
}    

}
  
 
  echo '</form></td></tr>';

}

echo '</table>';


$connect->close();

?> 
