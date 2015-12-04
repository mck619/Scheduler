<?php require 'include.php' ?>

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// check if an item is in use

if (isset($_POST['dropoffid']) && isset($_POST['deletedropoff'])) {

  if ($_POST['deletedropoff'] == 1) {

  $os1 = $connect->real_escape_string($_POST["dropoffid"]);

  $sql = "SELECT dppp_complete FROM view_dropoffpickupexpanded WHERE dppp_id=$os1 AND type='dropoff'";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_row();
  
	if (($result2[0] == 0)) {

	  $sql4 = "DELETE FROM dropoffitem WHERE dpitem_dropoff=$os1";
	  
	  $sql3 = "DELETE FROM dropoff WHERE dropoff_id=$os1";
			
	  $result5 = $connect->query($sql4) or die($connect->error);
	  
	  $result6 = $connect->query($sql3) or die($connect->error);	  
	  
	  echo '<h1>Dropoff successfully deleted!</h1>';
	  
	  echo '<h1>Further Action</h1>';
	  
	  echo '<form><button type="submit" formmethod="post" formaction="dropoffdisplay.php">View Dropoff List</button></form>';
	  
	  die;
	  
	}
  }
  
  else {
	
	echo '<p>You cannot delete a dropoff that has been completed.';
	
	die;
	  
  }

}

elseif (isset($_POST['dropoffid'])) {

  $os1 = $connect->real_escape_string($_POST["dropoffid"]);

  $sql = "SELECT * FROM view_dropoffpickupexpanded WHERE dppp_id=$os1 AND type='dropoff'";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Confirm Deletion of Dropoff:</h1>';
  
  echo '<form><input type="hidden" name="dropoffid" value="' . $result2['dppp_id'] . '"><input type="hidden" name="deletedropoff" value="1">';
  
  echo 'Name: ' . $result2["org_name"] . '<br>';

  echo 'Address: ' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"];
  
  $dpppdate = new DateTime($result2["dppp_date"]);
    
  echo '<p>Dropoff Date: ' . $dpppdate->format('D, M jS, Y');
  
  echo '<p><button type="submit" formmethod="post" formaction="dropoffdelete.php">Delete Dropoff</button></form>';
  
  echo '<h1>Further Action</h1>';

  echo '<form>';
  echo '<input type="hidden" name="fdid" value="' . $result2["fooddrive_id"] . '">';
  echo '<button type="submit" formmethod="post" formaction="fooddrivesingle.php">View Food Drive for ' . $result2["org_name"] . '</button>';
  echo '</form>';
	
  echo '<form><button type="submit" formmethod="post" formaction="dropoffdisplay.php">View Dropoff List</button></form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="fooddrivedisplay.php">View Food Drive List</button>';
echo '</form>';
	
  die;

}




?>