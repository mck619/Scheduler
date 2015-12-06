<?php require 'include.php' ?>

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// check if an item is in use

if (isset($_POST['itemid']) && isset($_POST['deleteitem'])) {

  if ($_POST['deleteitem'] == 1) {

	$os1 = $connect->real_escape_string($_POST["itemid"]);
  
	$sql = "SELECT COUNT(*) FROM view_dropoffitemexpanded WHERE dpitem_itemid=$os1";
	
	$result1 = $connect->query($sql) or die($connect->error);
	
	$result2 = $result1->fetch_row();
	
	$sql2 = "SELECT COUNT(*) FROM view_pickupitemexpanded WHERE ppitem_itemid=$os1";
	
	$result3 = $connect->query($sql2) or die($connect->error);
	
	$result4 = $result3->fetch_row(); 
	
	if (($result2[0] == 0) && ($result4[0] == 0)) {
	  
	  $sql3 = "DELETE FROM item WHERE item_id=$os1";
			
	  $result5 = $connect->query($sql3) or die($connect->error);
	  
	  echo '<h1>Item successfully deleted!</h1>';
	  
	  echo '<h1>Further Action</h1>';
	  
	  echo '<form><button type="submit" formmethod="post" formaction="itemdisplay.php">View Item List</button></form>';
	  
	  die;
	  
	}
	
	else {
	  
	  echo '<p>You cannot delete an item that is in use.  If you would like it to no longer appear on dropoffs or pickups, edit the item to make it inactive.';
	  
	  die;
		
	}
  }

}

elseif (isset($_POST['itemid'])) {

  $os1 = $connect->real_escape_string($_POST["itemid"]);

  $sql = "SELECT * FROM item WHERE item_id=$os1";
  
  $result1 = $connect->query($sql) or die($connect->error);
  
  $result2 = $result1->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Confirm Deletion of Item:</h1>';
  
  echo '<form><input type="hidden" name="itemid" value="' . $result2['item_id'] . '"><input type="hidden" name="deleteitem" value="1">';
  
  echo 'Name: ' . $result2["item_name"] . '<br>';

  echo 'Description: ' . $result2["item_desc"];
  
  echo '<p><button type="submit" formmethod="post" formaction="itemdelete.php">Delete Item</button></form>';
  
  echo '<h1>Further Action</h1>';
	
  echo '<form><button type="submit" formmethod="post" formaction="itemdisplay.php">View Item List</button></form>';
	
  die;

}



?>