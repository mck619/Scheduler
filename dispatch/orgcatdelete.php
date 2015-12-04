<?php require 'include.php' ?>

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// check if an item is in use

if (isset($_POST['orgcatid']) && isset($_POST['deleteorgcat'])) {

  if ($_POST['deleteorgcat'] == 1) {

	$os1 = $connect->real_escape_string($_POST["orgcatid"]);
  
	$sql = "SELECT COUNT(*) FROM organization WHERE org_category=$os1";
	
	$result1 = $connect->query($sql) or die($connect->error);
	
	$result2 = $result1->fetch_row();
	
	if (($result2[0] == 0)) {
	  
	  $sql3 = "DELETE FROM orgcategory WHERE orgcat_id=$os1";
			
	  $result5 = $connect->query($sql3) or die($connect->error);
	  
	  echo '<h1>Organization Category successfully deleted!</h1>';
	  
	  echo '<h1>Further Action</h1>';
	  
	  echo '<form><button type="submit" formmethod="post" formaction="orgcatdisplay.php">View Organization Category List</button></form>';
	  
	  die;
	  
	}
	
	else {
	  
	  echo '<p>You cannot delete an organization category which has organizations assigned to it.';
	  
	  die;
		
	}
  }
}

elseif (isset($_POST['orgcatid'])) {

  $os1 = $connect->real_escape_string($_POST["orgcatid"]);

  $sql = "SELECT * FROM orgcategory WHERE orgcat_id=$os1";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Confirm Deletion of Organization Category:</h1>';
  
  echo '<form><input type="hidden" name="orgcatid" value="' . $result2['orgcat_id'] . '"><input type="hidden" name="deleteorgcat" value="1">';
  
  echo 'Name: ' . $result2["orgcat_name"] . '<br>';

  echo 'Notes: ' . $result2["orgcat_notes"];
  
  echo '<p><button type="submit" formmethod="post" formaction="orgcatdelete.php">Delete Organization Category</button></form>';
  
  echo '<h1>Further Action</h1>';
	
  echo '<form><button type="submit" formmethod="post" formaction="orgcatdisplay.php">View Organization Category List</button></form>';
	
  die;

}

?>