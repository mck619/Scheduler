<?php require 'include.php' ?>	

<?php

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// check if an item is in use

if (isset($_POST['pickupid']) && isset($_POST['deletepickup'])) {

  if ($_POST['deletepickup'] == 1) {

  $os1 = $connect->real_escape_string($_POST["pickupid"]);

  $sql = "SELECT dppp_complete FROM view_dropoffpickupexpanded WHERE dppp_id=$os1 AND type='pickup'";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_row();
  
	if (($result2[0] == 0)) {

	  $sql4 = "DELETE FROM pickupitem WHERE ppitem_pickup=$os1";
	  
	  $sql3 = "DELETE FROM pickup WHERE pickup_id=$os1";
			
	  $result5 = $connect->query($sql4) or die($connect->error);
	  
	  $result6 = $connect->query($sql3) or die($connect->error);	  
	  
	  echo '<h1>Pickup successfully deleted!</h1>';
	  
	  echo '<h1>Further Action</h1>';
	  
	  echo '<form><button type="submit" formmethod="post" formaction="pickupdisplay.php">View Pickup List</button></form>';
	  
	  die;
	  
	}
  }
  
  else {
	
	echo '<p>You cannot delete a pickup that has been completed.';
	
	die;
	  
  }

}

elseif (isset($_POST['pickupid'])) {

  $os1 = $connect->real_escape_string($_POST["pickupid"]);

  $sql = "SELECT * FROM view_dropoffpickupexpanded WHERE dppp_id=$os1 AND type='pickup'";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Confirm Deletion of Pickup:</h1>';
  
  echo '<form><input type="hidden" name="pickupid" value="' . $result2['dppp_id'] . '"><input type="hidden" name="deletepickup" value="1">';
  
  echo 'Name: ' . $result2["org_name"] . '<br>';

  echo 'Address: ' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"];
  
  $dpppdate = new DateTime($result2["dppp_date"]);
    
  echo '<p>Pickup Date: ' . $dpppdate->format('D, M jS, Y');
  
  echo '<p><button type="submit" formmethod="post" formaction="pickupdelete.php">Delete Pickup</button></form>';
  
  echo '<h1>Further Action</h1>';

  echo '<form>';
  echo '<input type="hidden" name="fdid" value="' . $result2["fooddrive_id"] . '">';
  echo '<button type="submit" formmethod="post" formaction="fooddrivesingle.php">View Food Drive for ' . $result2["org_name"] . '</button>';
  echo '</form>';
	
  echo '<form><button type="submit" formmethod="post" formaction="pickupdisplay.php">View Pickup List</button></form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="fooddrivedisplay.php">View Food Drive List</button>';
echo '</form>';
	
  die;

}




?>