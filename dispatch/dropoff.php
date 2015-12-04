<?php require 'include.php' ?>
<?php 



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// if this is an edit of an existing dropoff, update the dropoff table

if (isset($_POST['dropoffid'])) {

  $os1 = $connect->real_escape_string($_POST["dropoffid"]);
	
  $stmt = $connect->stmt_init();

  $sql = "UPDATE dropoff SET dropoff_fooddrive=?, dropoff_date=?, dropoff_staff=?, dropoff_complete=? WHERE dropoff_id=$os1";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('isii', $_POST["dropofffooddrive"], $_POST["dropoffdate"], $_POST["dropoffstaff"], $_POST["dropoffcomplete"]);
	$stmt->execute();
	$stmt->close();
  
}
	
}

// if this is a new dropoff, add it to the dropoff table

else {

  // preparing input
  
  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO dropoff (dropoff_fooddrive, dropoff_date, dropoff_staff, dropoff_complete) VALUES (?, ?, ?, ?)";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('isii', $_POST["dropofffooddrive"], $_POST["dropoffdate"], $_POST["dropoffstaff"], $_POST["dropoffcomplete"]);
	$stmt->execute();
	$stmt->close();
	
  }

}
	
$dp6 = $_POST["itemarray"];
$dp7 = $_POST["itemlist"];

// retrieve the id of the record just entered

if (isset($_POST['dropoffid'])) {

// retrieve the record just entered

$sql2 = "SELECT * FROM dropoff WHERE dropoff_id=$os1";
	
}

else {

$result = $connect->insert_id;

// retrieve the record just entered

$sql2 = "SELECT * FROM dropoff WHERE dropoff_id=$result";

}

$result2 = $connect->query($sql2) or die($connect->error);

// fetch results of data

$result3 = $result2->fetch_array(MYSQLI_ASSOC);

// assign each field from the array to a variable

$dpid = $result3['dropoff_id'];
$dpfooddrive = $result3['dropoff_fooddrive'];
$dpdate = $result3['dropoff_date'];
$dpstaff = $result3['dropoff_staff'];
$dpcomplete = $result3['dropoff_complete'];

// convert dates to US format

$dropoffdate = new DateTime($dpdate);

// convert $fdorg (which returns the id of the category in table organization) to the associated name

$sql3 = "SELECT fooddrive_id, org_name, org_street, org_city, org_state, org_zip, org_phone, org_email, org_contact FROM view_fooddrivelist WHERE fooddrive_id=$dpfooddrive";

$result4 = $connect->query($sql3) or die($connect->error);

$result5 = $result4->fetch_array(MYSQLI_ASSOC);

// retrieve staff name

$sql4 = "SELECT staff_name FROM staff WHERE staff_id=$dpstaff";

$result6 = $connect->query($sql4) or die($connect->error);

$result7 = $result6->fetch_array(MYSQLI_ASSOC);

// update in the food drive notes

$trimnotes = trim($_POST["fooddrivenotes"]); 

$stmt2 = $connect->stmt_init();

$sql5 = "UPDATE fooddrive SET fooddrive_notes=? WHERE fooddrive_id=$dpfooddrive";

if ($stmt2->prepare($sql5)) {

  // bind parameters
  
  $stmt2->bind_param('s', $trimnotes);
  $stmt2->execute();
  $stmt2->close();
  
}

/*

$sql5 = "UPDATE fooddrive SET fooddrive_notes='$dp5' WHERE fooddrive_id=$dp1";
$connect->query($sql5) or die($connect->error);

*/

// retrieve the record just entered

$sql6 = "SELECT fooddrive_notes FROM fooddrive WHERE fooddrive_id=$dpfooddrive";

$result8 = $connect->query($sql6) or die($connect->error);

if (isset($_POST['dropoffid'])) {

  // write list of dropoff items to dropoffitem
  
  $itemcount = $dp6[0];
  
  $i = 1;
  
  // check to see if item is already used for that dropoff
  
  $sql7 = "SELECT * FROM view_dropoffitemexpanded WHERE dropoff_id=$os1 ORDER BY dpitem_itemid";
  $result12 = $connect->query($sql7) or die($connect->error);

  while ($result13 = $result12->fetch_assoc()) { 
	  $itemstore[] = $result13;
  }


  // k iterates through the items already listed for the dropoff
  
  $k = 0;
	
  while ($i <= $itemcount) {
		
		if ($itemstore[$k]["dpitem_itemid"] == $dp6[$i]) {
						
		  if ($dp7[$i] != 0) {
			  
			$dp8 = $itemstore[$k]["dpitem_id"];

			$sql7 = "UPDATE dropoffitem SET dpitem_dropoff=$dpid, dpitem_itemid=$dp6[$i], dpitem_amount=$dp7[$i]		 WHERE dpitem_id=$dp8";
			$connect->query($sql7) or die($connect->error);
		  
		  }
		
		  if ($dp7[$i] == 0) {
			$dp8 = $itemstore[$k]["dpitem_id"];
			$sql7 = "DELETE FROM dropoffitem WHERE dpitem_id=$dp8";
			$connect->query($sql7) or die($connect->error);
			  
		  }
		  
		$k++;
		$i++;	
		
		}
		
		else {
		if ($dp7[$i] != 0) {
		$sql7 = "INSERT INTO dropoffitem (dpitem_dropoff, dpitem_itemid, dpitem_amount) VALUES ('$dpid', '$dp6[$i]', '$dp7[$i]')";
		$connect->query($sql7) or die($connect->error);
		}
		$i++;
		}
	  }

}

else {

// write list of dropoff items to dropoffitem

$itemcount = $dp6[0];

$i = 1;

  while ($i <= $itemcount) {
	  if ($dp7[$i] != 0) {
	  $sql7 = "INSERT INTO dropoffitem (dpitem_dropoff, dpitem_itemid, dpitem_amount) VALUES ('$dpid', '$dp6[$i]', '$dp7[$i]')";
	  $connect->query($sql7) or die($connect->error);
	  $i++;
	  }
	  else {
		  $i++;
	  }
  }

}

// fetch results of data

$result9 = $result8->fetch_array(MYSQLI_ASSOC);

// print record just entered

echo "<h1>New Dropoff scheduled!</h1>";
echo 'Food Drive ID: ' . $result5['fooddrive_id'] . '<br>';
echo 'Dropoff ID: ' . $dpid . '<br>';
echo 'Organization:' . $result5['org_name'] . "<br>";
echo 'Address: ' . $result5['org_street'] . "<br>";
echo $result5['org_city'] . ', ' . $result5['org_state'] . ' ' . $result5['org_zip'] . "<br>";
echo 'Phone number: ' . $result5['org_phone'] . "<br>";
echo "E-mail: " . $result5['org_email'] . "<br>";
echo 'Contact person: ' . $result5['org_contact'] . "<br>";
echo nl2br('Food Drive notes:<br>' . $result9['fooddrive_notes']);
echo '<p>Dropoff Date: ' . $dropoffdate->format('l, F jS, Y') . '<br>';
echo 'Dropoff Staff: ' . $result7['staff_name'];
echo '<p>Is Dropoff complete? '; 

if ($dpcomplete == 1) { 
  echo 'Yes <br>';
  }

if ($dpcomplete == 0) {
  echo 'No <br>';
  }

echo '<p>Items to drop off:<br> <table><tr><th>Item Name</th><th>Item Description</th><th>Amount</th></tr>';

$sql8 = "SELECT item_name, item_desc, dpitem_amount FROM view_dropoffitemexpanded WHERE dpitem_dropoff=$dpid";

$result10 = $connect->query($sql8) or die($connect->error);

while ($result11 = $result10->fetch_assoc()) {
	echo '<tr><td>' . $result11['item_name'] . '</td><td>' . $result11['item_desc'] . '</td><td>' . $result11['dpitem_amount'] . '</tr>';
}

echo '</table>';

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $result5['fooddrive_id'] . '"><input type="hidden" name="dropoffid" value="' . $dpid . '">';
echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Edit This Dropoff</button>';
echo '</form>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $result5['fooddrive_id'] . '">';
echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Schedule Pickup for ' . $result5['org_name'] . '</button>';
echo '</form>';


echo '<form>';
echo '<input type="hidden" name="fdid" value="' . $result5['fooddrive_id'] . '">';
echo '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Edit Food Drive for ' . $result5['org_name'] . '</button>';
echo '</form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="dropoffdisplay.php">View Dropoff List</button>';
echo '</form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="fooddrivedisplay.php">View Food Drive List</button>';
echo '</form>';

$connect->close();

?> 
