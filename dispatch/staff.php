<?php require 'include.php' ?>	
<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

if (isset($_POST['staffid'])) {

  $os1 = $connect->real_escape_string($_POST["staffid"]);

  $stmt = $connect->stmt_init();
  
  $sql = "UPDATE staff SET staff_name=?, staff_active=? WHERE staff_id=$os1";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('si', $_POST["staffname"], $_POST["staffactive"]);
	$stmt->execute();
	$stmt->close();
	  
  }

  // retrieve the record just updated
  
  $sql2 = "SELECT * FROM staff WHERE staff_id=$os1";

}

else {

  // prepare input
  
  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO staff (staff_name, staff_active) VALUES (?, ?)";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('si', $_POST["staffname"], $_POST["staffactive"]);
	$stmt->execute();
	$stmt->close();
	
  }
  
  // retrieve id of the record just entered
  
  $result = $connect->insert_id;
  
  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM staff WHERE staff_id=$result";

}

$result2 = $connect->query($sql2) or die($connect->error);

// fetch results

$result3 = $result2->fetch_array(MYSQLI_ASSOC);

// assign each field from the array into a variable

$staffid = $result3['staff_id'];
$staffname = $result3['staff_name'];
$staffactive = $result3['staff_active'];

// print record just entered

if (isset($_POST['staffid'])) {
  echo "<h1>Staff person " . $staffname . " edited!</h1>";	
}

else {
	echo "<h1>New staff person " . $staffname . " created!</h1>";
}

echo 'Code: ' . $staffid . '<br>';
echo 'Active:';

if ($staffactive == 1) {
   echo 'Yes';	
}
if ($staffactive == 0) {
   echo 'No';	
}

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="staffid" value="' . $staffid . '">';
echo '<button type="submit" formmethod="post" formaction="staffadd.php">Edit Staff ' . $staffname . '</button>';
echo '</form>';


echo '<form>';
echo '<button type="submit" formmethod="post" formaction="staffdisplay.php">View Staff List</button>';
echo '</form>';

$connect->close();

?> 
