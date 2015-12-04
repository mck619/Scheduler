<?php require 'include.php' ?>	
<?php 


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// if this is an edit of an existing pickup, update the pickup table

if (isset($_POST['pickupid'])) {

  $os1 = $connect->real_escape_string($_POST["pickupid"]);
	
  $stmt = $connect->stmt_init();

  $sql = "UPDATE pickup SET pickup_fooddrive=?, pickup_date=?, pickup_staff=?, pickup_complete=?, pickup_pounds=? WHERE pickup_id=$os1";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('isiii', $_POST["pickupfooddrive"], $_POST["pickupdate"], $_POST["pickupstaff"], $_POST["pickupcomplete"], $_POST["pickuppounds"]);
	$stmt->execute();
	$stmt->close();
  
	}
	
}

// preparing input

else {

  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO pickup (pickup_fooddrive, pickup_date, pickup_staff, pickup_complete, pickup_pounds) VALUES (?, ?, ?, ?, ?)";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('isiii', $_POST["pickupfooddrive"], $_POST["pickupdate"], $_POST["pickupstaff"], $_POST["pickupcomplete"], $_POST["pickuppounds"]);
	$stmt->execute();
	$stmt->close();
	
  }
}
	
$pp6 = $_POST["itemarray"];
$pp7 = $_POST["itemlist"];

// retrieve the id of the record just entered

if (isset($_POST['pickupid'])) {

  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM pickup WHERE pickup_id=$os1";
	
}

else {

  $result = $connect->insert_id;
  
  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM pickup WHERE pickup_id=$result";

}

$result2 = $connect->query($sql2) or die($connect->error);

// fetch results of data

$result3 = $result2->fetch_array(MYSQLI_ASSOC);

// assign each field from the array to a variable

$ppid = $result3['pickup_id'];
$ppfooddrive = $result3['pickup_fooddrive'];
$ppdate = $result3['pickup_date'];
$ppstaff = $result3['pickup_staff'];
$ppcomplete = $result3['pickup_complete'];

// convert dates to US format

$pickupdate = new DateTime($ppdate);

// convert $fdorg (which returns the id of the category in table organization) to the associated name

$sql3 = "SELECT fooddrive_id, org_name, org_street, org_city, org_state, org_zip, org_phone, org_email, org_contact FROM view_fooddrivelist WHERE fooddrive_id=$ppfooddrive";

$result4 = $connect->query($sql3) or die($connect->error);

$result5 = $result4->fetch_array(MYSQLI_ASSOC);

// retrieve staff name

$sql4 = "SELECT staff_name FROM staff WHERE staff_id=$ppstaff";

$result6 = $connect->query($sql4) or die($connect->error);

$result7 = $result6->fetch_array(MYSQLI_ASSOC);

// update in the food drive notes

$stmt2 = $connect->stmt_init();

$trimnotes = trim($_POST["fooddrivenotes"]); 

$sql5 = "UPDATE fooddrive SET fooddrive_notes=? WHERE fooddrive_id=$ppfooddrive";

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

$sql6 = "SELECT fooddrive_notes FROM fooddrive WHERE fooddrive_id=$ppfooddrive";

$result8 = $connect->query($sql6) or die($connect->error);

if (isset($_POST['pickupid'])) {

  // write list of pickup items to dropoffitem
  
  $itemcount = $pp6[0];
  
  $i = 1;
  
  // check to see if item is already used for that dropoff
  
  $sql7 = "SELECT * FROM view_pickupitemexpanded WHERE pickup_id=$os1 ORDER BY ppitem_itemid";
  $result12 = $connect->query($sql7) or die($connect->error);

  while ($result13 = $result12->fetch_assoc()) { 
	  $itemstore[] = $result13;
  }

/*   print_r($pp6);
  print_r($pp7); */
  
  // k iterates through the items already listed for the dropoff
  
  $k = 0;
	
  while ($i <= $itemcount) {
		
		if ($itemstore[$k]["ppitem_itemid"] == $pp6[$i]) {
					
		  if ($pp7[$i] != 0) {
			  
			$pp8 = $itemstore[$k]["ppitem_id"];

			$sql7 = "UPDATE pickupitem SET ppitem_pickup=$ppid, ppitem_itemid=$pp6[$i], ppitem_amount=$pp7[$i]		 WHERE ppitem_id=$pp8";
			$connect->query($sql7) or die($connect->error);
		  
		  }
		
		  if ($pp7[$i] == 0) {
			$pp8 = $itemstore[$k]["ppitem_id"];
			$sql7 = "DELETE FROM pickupitem WHERE ppitem_id=$pp8";
			$connect->query($sql7) or die($connect->error);
			  
		  }
		  
		$k++;
		$i++;	
		
		}
		
		else {
		  if ($pp7[$i] != 0) {
			$sql7 = "INSERT INTO pickupitem (ppitem_pickup, ppitem_itemid, ppitem_amount) VALUES ('$ppid', '$pp6[$i]', '$pp7[$i]')";
			$connect->query($sql7) or die($connect->error);
		  }
		$i++;
		}
	  }

}

else {

  // write list of pickup items to pickupitem
  
  $itemcount = $pp6[0];
  
  $i = 1;
  
  while ($i <= $itemcount) {
	  if ($pp7[$i] != 0) {
	  $sql7 = "INSERT INTO pickupitem (ppitem_pickup, ppitem_itemid, ppitem_amount) VALUES ('$ppid', '$pp6[$i]', '$pp7[$i]')";
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

echo "<h1>New Pickup scheduled!</h1>";
echo 'Food Drive ID: ' . $result5['fooddrive_id'] . '<br>';
echo 'Pickup ID: ' . $ppid . '<br>';
echo 'Organization:' . $result5['org_name'] . "<br>";
echo 'Address: ' . $result5['org_street'] . "<br>";
echo $result5['org_city'] . ', ' . $result5['org_state'] . ' ' . $result5['org_zip'] . "<br>";
echo 'Phone number: ' . $result5['org_phone'] . "<br>";
echo "E-mail: " . $result5['org_email'] . "<br>";
echo 'Contact person: ' . $result5['org_contact'] . "<br>";
echo nl2br('Food Drive notes:<br>' . $result9['fooddrive_notes']);
echo '<p>Pickup Date: ' . $pickupdate->format('l, F jS, Y') . '<br>';
echo 'Pickup Staff: ' . $result7['staff_name'];
echo '<p>Is Pickup complete? '; 

if ($ppcomplete == 1) { 
  echo 'Yes <br>';
  }

if ($ppcomplete == 0) {
  echo 'No <br>';
  }

echo '<p>Pounds received: ' . $result3['pickup_pounds'];

echo '<p>Items to pickup:<br> <table><tr><th>Item Name</th><th>Item Description</th><th>Amount</th></tr>';

$sql8 = "SELECT item_name, item_desc, ppitem_amount FROM view_pickupitemexpanded WHERE ppitem_pickup=$ppid";

$result10 = $connect->query($sql8) or die($connect->error);

while ($result11 = $result10->fetch_assoc()) {
	echo '<tr><td>' . $result11['item_name'] . '</td><td>' . $result11['item_desc'] . '</td><td>' . $result11['ppitem_amount'] . '</tr>';
}

echo '</table>';

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $result5['fooddrive_id'] . '"><input type="hidden" name="pickupid" value="' . $ppid . '">';
echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Edit This Pickup</button>';
echo '</form>';

echo '<form>';
echo '<input type="hidden" name="fdid" value="' . $result5['fooddrive_id'] . '">';
echo '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Edit Food Drive for ' . $result5['org_name'] . '</button>';
echo '</form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="pickupdisplay.php">View Pickup List</button>';
echo '</form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="fooddrivedisplay.php">View Food Drive List</button>';
echo '</form>';

$connect->close();

?> 
