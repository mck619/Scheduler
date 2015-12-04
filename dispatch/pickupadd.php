<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Scheduling a Pickup</title>
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
if (isset($_POST['pickupid'])) {

echo '<h1>Editing a Pickup</h1>';
}
else {
	echo '<h1>Scheduling a Pickup</h1>';
}
?>

<form action="pickup.php" method="post">

<?php

// store organization id from orgselect.php

$os1 = $connect->real_escape_string($_POST["fooddriveorg"]);

if (isset($_POST['pickupid'])) {
	$os2 = $_POST['pickupid'];
}

// insert organization id into form

echo '<input type="hidden" name="pickupfooddrive" value="' . $os1 . '">';

// retrieve food drive from view view_fooddrivelist and print 

if (isset($_POST['pickupid'])) {
	$sql = "SELECT * FROM view_dropoffpickupexpanded WHERE (dppp_id=$os2 AND type='pickup')";
	$result = $connect->query($sql) or die($connect->error);
	
	$result2 = $result->fetch_array(MYSQLI_ASSOC);
	
	// insert pickupid for existing pickup
	
	echo '<input type="hidden" name="pickupid" value="' . $os2 . '">';
	
}

else {

$sql = "SELECT * FROM view_fooddrivelist WHERE fooddrive_id=$os1";
$result = $connect->query($sql) or die($connect->error);
$result2 = $result->fetch_array(MYSQLI_ASSOC);
}

echo 'Food Drive ID: ' . $result2['fooddrive_id'] . '<br>';
echo 'Organization:' . $result2['org_name'] . "<br>";
echo 'Address: ' . $result2['org_street'] . "<br>";
echo $result2['org_city'] . ', ' . $result2['org_state'] . ' ' . $result2['org_zip'] . "<br>";

// convert dates to US format

$startdate = new DateTime($result2['fooddrive_startdate']);
$enddate = new DateTime($result2['fooddrive_enddate']);

echo '<p>Start Date: ' . $startdate->format('l, F jS, Y') . "<br>";
echo '<p>End Date: ' . $enddate->format('l, F jS, Y');

?>



<p>Pickup Date:

<?php 

if (isset($_POST['pickupid'])) {
echo '<input type="textbox" name="pickupdate" required id="pickupdate" value="' . $result2["dppp_date"] . '">';
echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#pickupdate" ).datepicker({ minDate: -1, maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";
}


else {

echo '<input type="textbox" name="pickupdate" required id="pickupdate" />';

echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#pickupdate" ).datepicker({ minDate: -1, maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";

}

?>

<p>Pickup Staff: 
<select name="pickupstaff">

<?php

// fetch staff list from table staff

$sql2 = "SELECT * FROM staff WHERE staff_active=1 ORDER BY staff_name";
$result3 = $connect->query($sql2) or die($connect->error);

// make each category a value in the dropdown box

while ($result4 = $result3->fetch_assoc()) {
  if ($result4["staff_id"] == $result2["dppp_staff"]) {
  echo '<option value="' . $result4["staff_id"] . '" selected>**' . $result2["staff_name"] . '**</option>';	  
  }
  else {
	  echo '<option value="' . $result4["staff_id"] . '">' . $result4["staff_name"] . '</option>';
  }
}

?>

</select>

<?php

// if pickupid provided, retrieve list of items

if (isset($_POST['pickupid'])) {
	
$sql6 = "SELECT * FROM view_pickupitemexpanded WHERE pickup_id=$os2 ORDER BY ppitem_itemid";
$result11 = $connect->query($sql6) or die($connect->error);

while ($result12 = $result11->fetch_assoc()) { 
	$itemstore[] = $result12;
}
// print_r($itemstore);
}

// get active items

$sql3 = "SELECT * FROM item WHERE ((item_active=1) or (item_active=3))";
$result5 = $connect->query($sql3) or die($connect->error);

// count number of active items

$sql4 = "SELECT COUNT(*) FROM item WHERE ((item_active=1) or (item_active=3))";

$result6 = $connect->query($sql4) or die($connect->error);

$result10 = $result6->fetch_row();

// j is the counter for the itemarray, while k iterates through the existing items (if needed).  k is only increased if a match is found.

$j = 0;
$k = 0;

// store count of active items in itemarray[0]

echo '<input type="hidden" name="itemarray[' . $j . ']" value="' . $result10[0] . '">';

// for each item, store the item's id in itemarray[], then each item in the list & amount selected is stored in the itemlist[] array

if (isset($_POST['pickupid'])) {

  while ($result7 = $result5->fetch_assoc()) {
	$j++;
	echo '<input type="hidden" name="itemarray[' . $j . ']" value="' . $result7["item_id"] . '">';
	echo '<p>Number of ' . $result7["item_name"] . ' (' . $result7["item_desc"] . '): ';
	echo '<select name="itemlist[' . $j . ']">';
	$i = 0;
	
	// check if item already exists in current pickup
	
	if ($itemstore[$k]["ppitem_itemid"] == $result7["item_id"]) {

	  // if item exists, highlight the selection

	  while ($i < 21 ) {
		  if ($itemstore[$k]["ppitem_amount"] == $i) {
		  echo '<option value="' . $i . '" selected>**' . $i . '**</option>';
		  $i++;
		  
		  }
		  else {
			  echo '<option value="' . $i . '">' . $i . '</option>';
			  $i++;
		  }		
		  
	  }
	  
	  $k++;
		
	}
	
	else {
		while ($i < 21 ) {
		echo '<option value="' . $i . '">' . $i . '</option>';
		$i++;
		}
	}
	
	echo '</select>';
	
  }

	
}

else {
  while ($result7 = $result5->fetch_assoc()) {
	$j++;
	echo '<input type="hidden" name="itemarray[' . $j . ']" value="' . $result7["item_id"] . '">';
	echo '<p>Number of ' . $result7["item_name"] . ' (' . $result7["item_desc"] . '): ';
	echo '<select name="itemlist[' . $result7["item_id"] . ']">';
	$i = 0;
	
	while ($i < 21 ) {
		echo '<option value="' . $i . '">' . $i . '</option>';
		$i++;
		
	}
	
	echo '</select>';
	
  }
}

// check database to see if the pickup is complete

if (isset($_POST['pickupid'])) {
	echo '<p>Is pickup complete?  <label for="yes"><input type="radio" name="pickupcomplete" id="yes" value="1" ';
	
	if ($result2['dppp_complete'] == 1) {
	
	echo 'checked';
		
	}
	
	echo '>Yes</label><label for="no"><input type="radio" name="pickupcomplete" id="no" value="0" ';
	
	if ($result2['dppp_complete'] == 0) {
	
	echo 'checked';
		
	}	
	
	echo '>No</label>';
}

else {

echo '<p>Is pickup complete?  <label for="yes"><input type="radio" name="pickupcomplete" id="yes" value="1">Yes</label><label for="no"><input type="radio" name="pickupcomplete" id="no" value="0" checked>No</label>';

}

if (isset($_POST['pickupid'])) {

$sql7 = "SELECT * FROM pickup WHERE pickup_id=$os2";
$result8 = $connect->query($sql7) or die($connect->error);
	
$result9 = $result8->fetch_array(MYSQLI_ASSOC);
$pppounds = $result9['pickup_pounds'];
	
}

else {
	$pppounds = 0;
	
}

echo '<p>Pounds received? <input type="text" name="pickuppounds" maxlength="6" value="' . $pppounds . '"></p>';

?>

<p>Food Drive Notes:<br>
<textarea name="fooddrivenotes"  cols="100"  rows="10">

<?php

echo $result2['fooddrive_notes'];

?>

</textarea> 
</p>

<p>
<input type="submit" />
<input type="reset" />
<p>
</form>

</body>
</html>
