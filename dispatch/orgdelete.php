<?php require 'include.php' ?>

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// check if an item is in use

if (isset($_POST['orgid']) && isset($_POST['deleteorg'])) {

  if ($_POST['deleteorg'] == 1) {

	$os1 = $connect->real_escape_string($_POST["orgid"]);
  
	$sql = "SELECT COUNT(*) FROM view_fooddrivelist WHERE org_id=$os1";
	
	$result1 = $connect->query($sql) or die($connect->error);
	
	$result2 = $result1->fetch_row();
	
	if (($result2[0] == 0)) {
	  
	  $sql3 = "DELETE FROM organization WHERE org_id=$os1";
			
	  $result5 = $connect->query($sql3) or die($connect->error);
	  
	  echo '<h1>Organization successfully deleted!</h1>';
	  
	  echo '<h1>Further Action</h1>';
	  
	  echo '<form><button type="submit" formmethod="post" formaction="orgdisplay.php">View Organization List</button></form>';
	  
	  die;
	  }
	
	else {
  
	  echo '<p>You cannot delete an organization which has had a food drive assigned.';
	  
	  die;
	
	}
  
  }
}
  
elseif (isset($_POST['orgid'])) {

  $os1 = $connect->real_escape_string($_POST["orgid"]);

  $sql = "SELECT * FROM organization WHERE org_id=$os1";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Confirm Deletion of Organization:</h1>';
  
  echo '<form><input type="hidden" name="orgid" value="' . $result2['org_id'] . '"><input type="hidden" name="deleteorg" value="1">';
  
  echo 'Name: ' . $result2["org_name"] . '<br>';

  echo 'Address: ' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"];
  
  echo '<p><button type="submit" formmethod="post" formaction="orgdelete.php">Delete Organization</button></form>';
  
  echo '<h1>Further Action</h1>';
	
  echo '<form><button type="submit" formmethod="post" formaction="orgdisplay.php">View Organization List</button></form>';
	
  die;

}



?>