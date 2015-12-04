<?php require 'include.php' ?>

<?php

require '../schedule/conn.php';

// Connect to MySQL

$connect = $conn;
$connect2 = $conn2;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

if($connect2->connect_error){
    die('Connection error: ('.$connect2->connect_errno.'): '.$connect2->connect_error);
        } 

// check if an item is in use

if (isset($_POST['orgid']) && isset($_POST['deleteformentry'])) {

  if ($_POST['deleteformentry'] == 1) {

  $os1 = $connect2->real_escape_string($_POST["orgid"]);
 
  $sql = "UPDATE organization SET org_import=2 WHERE org_id=$os1";
			
  $result = $connect2->query($sql) or die($connect2->error);
	  
	  echo '<h1>Form Entry successfully deleted!</h1>';
	  
	  echo '<h1>Further Action</h1>';
	  
	  echo '<form><button type="submit" formmethod="post" formaction="importform.php">View Import from Form List</button></form>';
	  
	  die;
	  
  }

}

elseif (isset($_POST['orgid'])) {

  $os1 = $connect2->real_escape_string($_POST["orgid"]);

  $sql = "SELECT * FROM organization WHERE org_id=$os1";
  
  $result1 = $connect2->query($sql) or die($connect2->error);
  
  $result2 = $result1->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Confirm Deletion of Food Drive:</h1>';
  
  echo '<form><input type="hidden" name="orgid" value="' . $result2['org_id'] . '"><input type="hidden" name="deleteformentry" value="1">';
  
  echo 'Name: ' . $result2["org_name"] . '<br>';

  echo 'Address: ' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"];
  
  $startdate = new DateTime($result2["org_startdate"]);
  $enddate = new DateTime($result2["org_enddate"]);
    
  echo '<p>Start Date: ' . $startdate->format('D, M jS, Y');
  echo '<br>End Date: ' . $enddate->format('D, M jS, Y');  
  
  echo '<p><button type="submit" formmethod="post" formaction="importdelete.php">Delete Import Form Entry</button></form>';
  
  echo '<h1>Further Action</h1>';
	
  echo '<form><button type="submit" formmethod="post" formaction="importform.php">View Import from Form List</button></form>';
	
  die;

}




?>