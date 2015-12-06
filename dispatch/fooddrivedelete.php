<?php require 'include.php' ?>

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// check if an item is in use

if (isset($_POST['fdid']) && isset($_POST['deletefooddrive'])) {

  if ($_POST['deletefooddrive'] == 1) {

  $os1 = $connect->real_escape_string($_POST["fdid"]);

  $sql = "SELECT COUNT(*) FROM view_dropoffpickupexpanded WHERE dppp_fooddrive=$os1";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_row();
  
	if (($result2[0] == 0)) {
	  
	  $sql3 = "DELETE FROM fooddrive WHERE fooddrive_id=$os1";
			
	  $result5 = $connect->query($sql3) or die($connect->error);
	  
	  echo '<h1>Food Drive successfully deleted!</h1>';
	  
	  echo '<h1>Further Action</h1>';
	  
	  echo '<form><button type="submit" formmethod="post" formaction="fooddrivedisplay.php">View Food Drive List</button></form>';
	  
	  die;
	  
	}
  
  
  else {
	
	echo '<p>You cannot delete a food drive that has dropoffs or pickups assigned.';
	
	die;
  }
  }

}

elseif (isset($_POST['fdid'])) {

  $os1 = $connect->real_escape_string($_POST["fdid"]);

  $sql = "SELECT * FROM view_fooddrivelist WHERE fooddrive_id=$os1";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Confirm Deletion of Food Drive:</h1>';
  
  echo '<form><input type="hidden" name="fdid" value="' . $result2['fooddrive_id'] . '"><input type="hidden" name="deletefooddrive" value="1">';
  
  echo 'Name: ' . $result2["org_name"] . '<br>';

  echo 'Address: ' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"];
  
  $startdate = new DateTime($result2["fooddrive_startdate"]);
  $enddate = new DateTime($result2["fooddrive_enddate"]);
    
  echo '<p>Start Date: ' . $startdate->format('D, M jS, Y');
  echo '<br>End Date: ' . $enddate->format('D, M jS, Y');  
  
  echo '<p><button type="submit" formmethod="post" formaction="fooddrivedelete.php">Delete Food Drive</button></form>';
  
  echo '<h1>Further Action</h1>';
	
  echo '<form><button type="submit" formmethod="post" formaction="fooddrivedisplay.php">View Food Drive List</button></form>';
	
  die;

}




?>