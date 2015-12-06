<?php require 'include.php' ?>
<?php 



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

if (isset($_POST['fdid'])) {

  $os1 = $connect->real_escape_string($_POST["fdid"]);

  // preparing input for a new food drive 
  
  $stmt = $connect->stmt_init();
  
  $sql = "UPDATE fooddrive SET fooddrive_startdate=?, fooddrive_enddate=?, fooddrive_org=?, fooddrive_notes=? WHERE fooddrive_id=$os1";
 
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$trimnotes = trim($_POST["fooddrivenotes"]); 
	
	$stmt->bind_param('ssis', $_POST["startdate"], $_POST["enddate"], $_POST["fooddriveorg"], $trimnotes);
	$stmt->execute();
	$stmt->close();
	  
  }
	// retrieve the record just updated

	$sql2 = "SELECT * FROM fooddrive WHERE fooddrive_id=$os1";

}

else {

  // preparing input for a new food drive 

  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO fooddrive (fooddrive_startdate, fooddrive_enddate, fooddrive_org, fooddrive_notes) VALUES (?, ?, ?, ?)";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters

	$trimnotes = trim($_POST["fooddrivenotes"]); 
	
	$stmt->bind_param('ssis', $_POST["startdate"], $_POST["enddate"], $_POST["fooddriveorg"], $trimnotes);
	$stmt->execute();
	$stmt->close();
	  
  }
  
  // retrieve the id of the record just entered
  
  $result = $connect->insert_id;
  
  $sql2 = "SELECT * FROM fooddrive WHERE fooddrive_id=$result";

}

// retrieve the record just entered



$result2 = $connect->query($sql2) or die($connect->error);

// fetch results of data

$result3 = $result2->fetch_array(MYSQLI_ASSOC);

// assign each field from the array to a variable

$fdid = $result3['fooddrive_id'];
$fdstartdate = $result3['fooddrive_startdate'];
$fdenddate = $result3['fooddrive_enddate'];
$fdorg = $result3['fooddrive_org'];
$fdnotes = $result3['fooddrive_notes'];

// convert dates to US format

$startdate = new DateTime($fdstartdate);
$enddate = new DateTime($fdenddate);

// convert $fdorg (which returns the id of the category in table organization) to the associated name

$sql3 = "SELECT * FROM organization WHERE org_id=$fdorg";

$result4 = $connect->query($sql3) or die($connect->error);

$result5 = $result4->fetch_array(MYSQLI_ASSOC);

// print record just entered

if (isset($_POST['fdid'])) {
echo "<h1>Food Drive successfully edited!</h1>";	
}

else {

echo "<h1>New Food Drive created!</h1>";

}
echo 'Food Drive ID: ' . $fdid . '<br>';
echo 'Organization:' . $result5['org_name'] . "<br>";
echo 'Address: ' . $result5['org_street'] . "<br>";
echo $result5['org_city'] . ', ' . $result5['org_state'] . ' ' . $result5['org_zip'] . "<br>";
echo 'Phone number: ' . $result5['org_phone'] . "<br>";
echo "E-mail: " . $result5['org_email'] . "<br>";
echo 'Contact person: ' . $result5['org_contact'] . "<br>";
echo '<p>Start Date: ' . $startdate->format('l, F jS, Y') . '<br>';
echo 'End Date: ' . $enddate->format('l, F jS, Y') . '</p>';
echo 'Notes:<br>' . $fdnotes;

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $fdid . '">';
echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Schedule Dropoff for ' . $result5['org_name'] . '</button>';
echo '</form>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $fdid . '">';
echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Schedule Pickup for ' . $result5['org_name'] . '</button>';
echo '</form>';


echo '<form>';
echo '<input type="hidden" name="fdid" value="' . $fdid . '">';
echo '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Edit Food Drive for ' . $result5['org_name'] . '</button>';
echo '</form>';



echo '<form>';
echo '<button type="submit" formmethod="post" formaction="fooddrivedisplay.php">View Food Drive List</button>';
echo '</form>';

$connect->close();

?> 
