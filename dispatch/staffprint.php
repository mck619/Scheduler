<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dispatch List</title>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
</head>
<?php 

require 'conn.php';

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
  
  $sql = "SELECT * FROM view_dropoffpickupexpanded WHERE dppp_date='$os2' AND staff_id=$os1 AND dppp_complete=0 ORDER BY dppp_date";
  
  $result = $connect->query($sql) or die($connect->error);

  $reportdate = new DateTime($os2);
    
  echo '<h1>All Dropoffs and Pickups for ' . $result6["staff_name"] . ' for '. $reportdate->format('D, M jS, Y') . '</h1>';
	
}


else {

  echo '<p>You must select a date before using this view.';
  die;

}

if (isset($_POST['startdate'])) {
	echo '<p>This is a list of all dropoffs and pickups in the selected date range, sorted by date:</p>';
	
}

echo '<table border="1"><tr><th>ID</th><th>Category</th><th>Organization Contact Information<th>Organization &amp; Food Drive Notes</th><th>Items Required for...</th><th>Completed / Pounds Received</th></tr>';

// run through each dropoff

$curdate = new DateTime();

while ($result2 = $result->fetch_assoc()) {

  echo '<tr><td>' . $result2["dppp_id"] . '</td><td>' . $result2["orgcat_name"] . '</td><td>' . $result2["org_name"] . '<br>' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"] . '<p>'. $result2["org_contact"] . '<br>' . $result2["org_phone"] . '<br>' . '<a href="mailto:' . $result2["org_email"] . '">' . $result2["org_email"] . '</a></td><td>';

  echo nl2br($result2['org_notes']);
  echo '<p>';  
  echo nl2br($result2['fooddrive_notes']);
  
  echo '</td><td>' . $result2['type'] . '<p>';

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
  
  echo '<td>&nbsp;';

  
 
  echo '</td></tr>';

}

echo '</table>';


$connect->close();

?> 
