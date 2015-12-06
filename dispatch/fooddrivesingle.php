<?php require 'include.php' ?>
<?php 


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }
		
// retrieve food drive information

if (isset($_POST['fdid'])) {

  $os1 = $connect->real_escape_string($_POST["fdid"]);

  $sql = "SELECT * FROM view_fooddrivelist WHERE fooddrive_id=$os1";
  $result = $connect->query($sql) or die($connect->error);
	
}

else {
  echo '<p>You must select a food drive before using this view.';
  die;
}		

$result2 = $result->fetch_array(MYSQLI_ASSOC);

// process the food drive results

$fdid = $result2['fooddrive_id'];
$fdstartdate = $result2['fooddrive_startdate'];
$fdenddate = $result2['fooddrive_enddate'];
$fdorg = $result2['org_name'];
$fdnotes = $result2['fooddrive_notes'];
$startdate = new DateTime($result2["fooddrive_startdate"]);
$enddate = new DateTime($result2["fooddrive_enddate"]);

// display food drive information

echo '<h1>Food Drive for ' . $fdorg . '</h1>';

echo 'Food Drive ID: ' . $fdid . '<br>';
echo 'Organization:' . $fdorg . "<br>";
echo 'Address: ' . $result2['org_street'] . "<br>";
echo $result2['org_city'] . ', ' . $result2['org_state'] . ' ' . $result2['org_zip'] . "<br>";
echo 'Phone number: ' . $result2['org_phone'] . "<br>";
echo "E-mail: " . $result2['org_email'] . "<br>";
echo 'Contact person: ' . $result2['org_contact'] . "<br>";
echo '<p>Start Date: ' . $startdate->format('l, F jS, Y') . '<br>';
echo 'End Date: ' . $enddate->format('l, F jS, Y') . '</p>';
echo 'Notes:<br>';
echo nl2br($fdnotes);

// retrieve dropoffs and pickups for this food drive

$sql2 = "SELECT * FROM view_dropoffpickupexpanded WHERE fooddrive_id=$result2[fooddrive_id]";

$result3 = $connect->query($sql2) or die($connect->error);

// create table displaying the dropoffs and pickups

echo '<h1>Scheduled Dropoffs and Pickups for ' . $fdorg . '</h1>';

echo '<table border="1"><tr><th>Date</th><th>Type</th><th>Staff</th><th>Complete?</th><th>Action</th></tr>';

while ($result4 = $result3->fetch_assoc()) {

  $dpppdate = new DateTime($result4["dppp_date"]);
  
  $curdate = new DateTime();
  
  if (($dpppdate <= $curdate) && ($result4["dppp_complete"] == 0)) {
	
	echo '<tr><td><span class="expired">' . $dpppdate->format('D, M jS, Y') . '</span></td>';
	  
  }
  
  else {
  
	echo '<tr><td>' . $dpppdate->format('D, M jS, Y') . '</td>';
  
  }

  echo '<td>' . $result4["type"] . '</td><td>' . $result4["staff_name"] . '</td><td>';
  
  if ($result4["dppp_complete"] == 1) {
	echo 'Yes';  
  }
  
  if ($result4["dppp_complete"] == 0) {
	echo 'No';  
  }
  
  echo '</td>';
  
  if ($result4["type"] == 'dropoff') {
  
	echo '<td><form>';
	echo '<input type="hidden" name="dropoffid" value="' . $result4['dppp_id'] . '">';
	echo '<input type="hidden" name="fooddriveorg" value="' . $result4['fooddrive_id'] . '">';
	echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Edit Dropoff</button><br>';
	
  if ($result4["dppp_complete"] == 0) {
  
	echo '<button type="submit" formmethod="post" formaction="dropoffpickupcomplete.php">Complete Dropoff</button><br>';
	
  }
	echo '<button type="submit" formmethod="get" formaction="dropoffsingle.php">View Detail</button><br>';
  
  if ($result4["dppp_complete"] == 0) {
  
	echo '<button type="submit" formmethod="post" formaction="dropoffdelete.php">Delete Dropoff</button>';
  } 

	echo '</form></td></tr>';
	
  }
  
  if ($result4["type"] == 'pickup') {

	echo '<td><form>';  
  echo '<input type="hidden" name="pickupid" value="' . $result4['dppp_id'] . '">';
  echo '<input type="hidden" name="fooddriveorg" value="' . $result4['fooddrive_id'] . '">';
  echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Edit Pickup</button><br>';
  
if ($result4["dppp_complete"] == 0) {
  echo '<button type="submit" formmethod="post" formaction="dropoffpickupcomplete.php">Complete Pickup</button><br>';
}

  echo '<button type="submit" formmethod="get" formaction="pickupsingle.php">View Detail</button><br>';
  
if ($result4["dppp_complete"] == 0) {

  echo '<button type="submit" formmethod="post" formaction="pickupdelete.php">Delete Pickup</button>';
}    
	echo '</form></td></tr>';  
  }

}
echo '</table>';

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $fdid . '">';
echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Schedule Dropoff for ' . $result2['org_name'] . '</button>';
echo '</form>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $fdid . '">';
echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Schedule Pickup for ' . $result2['org_name'] . '</button>';
echo '</form>';


echo '<form>';
echo '<input type="hidden" name="fdid" value="' . $fdid . '">';
echo '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Edit Food Drive for ' . $result2['org_name'] . '</button>';
echo '</form>';



echo '<form>';
echo '<button type="submit" formmethod="post" formaction="fooddrivedisplay.php">View Food Drive List</button>';
echo '</form>';

$connect->close();

		
?>