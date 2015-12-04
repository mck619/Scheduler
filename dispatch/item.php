<?php require 'include.php' ?>

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// check if an item is being edited

if (isset($_POST['itemid'])) {

  $os1 = $connect->real_escape_string($_POST["itemid"]);

  $stmt = $connect->stmt_init();
  
  $sql = "UPDATE item SET item_name=?, item_active=?, item_desc=? WHERE item_id=$os1";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('sis', $_POST["itemname"], $_POST["itemactive"], $_POST["itemdesc"]);
	$stmt->execute();
	$stmt->close();
	  
  }

  // retrieve the record just updated
  
  $sql2 = "SELECT * FROM item WHERE item_id=$os1";

}

// otherwise, create new item

else {

// prepare input

  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO item (item_name, item_active, item_desc) VALUES (?, ?, ?)";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('sis', $_POST["itemname"], $_POST["itemactive"], $_POST["itemdesc"]);
	$stmt->execute();
	$stmt->close();
	  
  }
  
  // retrieve id of the record just entered
  
  $result = $connect->insert_id;
  
  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM item WHERE item_id=$result";

}

$result2 = $connect->query($sql2) or die($connect->error);

// fetch results

$result3 = $result2->fetch_array(MYSQLI_ASSOC);

// assign each field from the array into a variable

$itemid = $result3['item_id'];
$itemname = $result3['item_name'];
$itemactive = $result3['item_active'];
$itemdesc = $result3['item_desc'];


// print record just entered

echo '<h1>';
if (isset($_POST['itemid'])) {
	echo 'Item ' . $itemname . ' edited!';
}
else {
	echo 'New item ' . $itemname . ' created!';
	}

echo '</h1>';
echo '<p>Code: ' . $itemid . '<br>';
echo 'Item is active for: ';

if ($itemactive == 1) { 
  echo 'Dropoffs and Pickups <br>';
  }

if ($itemactive == 2) { 
  echo 'Dropoffs Only<br>';
  }

if ($itemactive == 3) { 
  echo 'Pickups Only<br>';
  }


if ($itemactive == 0) {
  echo 'None <br>';
  }


echo 'Description:' . $itemdesc . '</p>';

$connect->close();

?> 
