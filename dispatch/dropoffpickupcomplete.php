<?php require 'include.php' ?>
<?php 



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

if (isset($_POST['dropoffid'])) {
	
  $os1 = $connect->real_escape_string($_POST["dropoffid"]);
  
  $sql = "UPDATE dropoff SET dropoff_complete=1 WHERE dropoff_id=$os1";

  $result = $connect->query($sql) or die($connect->error);
  
  $sql2 = "SELECT * from view_dropoffpickupexpanded WHERE (dppp_id=$os1 AND type='dropoff')";
  
  $result2 = $connect->query($sql2) or die($connect->error);

  $result3 = $result2->fetch_array(MYSQLI_ASSOC);
  
  echo "<h1>Dropoff Completed!</h1>";
  echo 'Dropoff ID: ' . $result3["dppp_id"] . '<br>';
  echo 'Organization:' . $result3['org_name'] . "<br>";
  echo 'Address: ' . $result3['org_street'] . "<br>";
  echo $result3['org_city'] . ', ' . $result3['org_state'] . ' ' . $result3['org_zip'] . "<br>";
  echo 'Phone number: ' . $result3['org_phone'] . "<br>";
  echo "E-mail: " . $result3['org_email'] . "<br>";
  echo 'Contact person: ' . $result3['org_contact'] . "<br>";
  echo nl2br('Food Drive notes:<br>' . $result3['fooddrive_notes']);
  echo '<p>Is Dropoff complete? '; 
  
  if ($result3['dppp_complete'] == 1) { 
	echo 'Yes <br>';
	}
  
  if ($result3['dppp_complete'] == 0) {
	echo 'No <br>';
	}

  echo '<h1>Further Action:</h1>';
  echo '<form>';
  echo '<input type="hidden" name="fooddriveorg" value="' . $result3['fooddrive_id'] . '"><input type="hidden" name="dropoffid" value="' . $result3["dppp_id"] . '">';
  echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Edit This Dropoff</button>';
  echo '</form>';

  echo '<form>';
  echo '<button type="submit" formmethod="post" formaction="dropoffdisplay.php">View Dropoff List</button>';
  echo '</form>';
	
  die;

}

if (isset($_POST['pickupid']) && isset($_POST['pickuppounds'])) {
  $os1 = $connect->real_escape_string($_POST["pickupid"]);
  
  $stmt = $connect->stmt_init();

  $sql = "UPDATE pickup SET pickup_pounds=?, pickup_complete=1 WHERE pickup_id=$os1";
 
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('i', $_POST["pickuppounds"]);
	$stmt->execute();
	$stmt->close();
  }

  $sql2 = "SELECT * from view_pickup WHERE pickup_id=$os1";

  $result2 = $connect->query($sql2) or die($connect->error);

  $result3 = $result2->fetch_array(MYSQLI_ASSOC);

  echo "<h1>Pickup Completed!</h1>";
  echo 'Pickup ID: ' . $result3["pickup_id"] . '<br>';
  echo 'Organization:' . $result3['org_name'] . "<br>";
  echo 'Address: ' . $result3['org_street'] . "<br>";
  echo $result3['org_city'] . ', ' . $result3['org_state'] . ' ' . $result3['org_zip'] . "<br>";
  echo 'Phone number: ' . $result3['org_phone'] . "<br>";
  echo "E-mail: " . $result3['org_email'] . "<br>";
  echo 'Contact person: ' . $result3['org_contact'] . "<br>";
  echo nl2br('Food Drive notes:<br>' . $result3['fooddrive_notes']);
  echo '<p>Is Pickup complete? '; 
  
  if ($result3['pickup_complete'] == 1) { 
	echo 'Yes <br>';
	}
  
  if ($result3['pickup_complete'] == 0) {
	echo 'No <br>';
	}
	
  echo 'Pounds received: ' . $result3["pickup_pounds"];

  echo '<h1>Further Action:</h1>';
  echo '<form>';
  echo '<input type="hidden" name="fooddriveorg" value="' . $result3['fooddrive_id'] . '"><input type="hidden" name="pickupid" value="' . $result3["pickup_id"] . '">';
  echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Edit This Pickup</button>';
  echo '</form>';

  echo '<form>';
  echo '<button type="submit" formmethod="post" formaction="pickupdisplay.php">View Pickup List</button>';
  echo '</form>';
	
  die;

}

if (isset($_POST['pickupid'])) {
  $os1 = $connect->real_escape_string($_POST["pickupid"]);

  $sql2 = "SELECT * from view_dropoffpickupexpanded WHERE (dppp_id=$os1 AND type='pickup')";

  $result2 = $connect->query($sql2) or die($connect->error);
  
  $result3 = $result2->fetch_array(MYSQLI_ASSOC); 

  echo '<h1>Completing pickup for ' . $result3["org_name"];
  echo '<form action="dropoffpickupcomplete.php" method="post"><input type="hidden" name="pickupid" value="' . $os1 . '">';  

  echo '<p>Pounds received?</p><input type="text" name="pickuppounds" maxlength="6">';
  
  echo '<input type="submit"><input type="reset"></form>';
  
  die;
		
}

else {
  echo '<p>Dropoff or pickup must be specified</p>.';
  die;
	
}

?>