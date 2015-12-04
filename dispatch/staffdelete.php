<?php require 'include.php' ?>	

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// check if an item is in use

if (isset($_POST['staffid']) && isset($_POST['deletestaff'])) {

  if ($_POST['deletestaff'] == 1) {

	$os1 = $connect->real_escape_string($_POST["staffid"]);
  
	$sql = "SELECT COUNT(*) FROM view_dropoffpickupexpanded WHERE dppp_staff=$os1";
	
	$result1 = $connect->query($sql) or die($connect->error);
	
	$result2 = $result1->fetch_row();
  
	if ($result2[0] == 0) {
	
	  $sql2 = "DELETE FROM staff WHERE staff_id=$os1";
			
	  $result3 = $connect->query($sql2) or die($connect->error);
	  
	  echo '<h1>Staff Member successfully deleted!</h1>';
	  
	  echo '<h1>Further Action</h1>';
	  
	  echo '<form><button type="submit" formmethod="post" formaction="staffdisplay.php">View Staff List</button></form>';
	  
	  die;
	  
	}
	
	else {
	  
	  echo '<p>You cannot delete a staff member who has been assigned to a pickup or dropoff.';
	  
	  echo '<h1>Further Action</h1>';
  
	  echo '<form><button type="submit" formmethod="post" formaction="staffdisplay.php">View Staff List</button></form>';
	  
	  die;
		
	}
  }

}

elseif (isset($_POST['staffid'])) {

  $os1 = $connect->real_escape_string($_POST["staffid"]);

  $sql = "SELECT * FROM staff WHERE staff_id=$os1";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Confirm Deletion of Staff Member:</h1>';
  
  echo '<form><input type="hidden" name="staffid" value="' . $result2['staff_id'] . '"><input type="hidden" name="deletestaff" value="1">';
  
  echo 'Name: ' . $result2["staff_name"] . '<br>';
  
  echo '<p><button type="submit" formmethod="post" formaction="staffdelete.php">Delete Staff</button></form>';
  
  echo '<h1>Further Action</h1>';
	
  echo '<form><button type="submit" formmethod="post" formaction="staffdisplay.php">View Staff List</button></form>';
	
  die;

}



?>