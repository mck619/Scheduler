<?php require 'include.php' ?>	
<?php 


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }
		
// retrieve food drive information

if (isset($_POST['orgid'])) {

  $os1 = $connect->real_escape_string($_POST["orgid"]);

  $sql = "SELECT * FROM organization WHERE org_id=$os1";
  $result = $connect->query($sql) or die($connect->error);
	
}

else {
  echo '<p>You must select an organization before using this view.';
  die;
}		

$result2 = $result->fetch_array(MYSQLI_ASSOC);

// process the organization results

$orgid = $result2['org_id'];
$orgname = $result2['org_name'];
$orgnotes = $result2['org_notes'];

// display food drive information

echo '<h1>Organization Information for ' . $orgname . '</h1>';

echo 'Organization ID: ' . $orgid . '<br>';
echo 'Organization:' . $orgname . "<br>";
echo 'Address: ' . $result2['org_street'] . "<br>";
echo $result2['org_city'] . ', ' . $result2['org_state'] . ' ' . $result2['org_zip'] . "<br>";
echo 'Phone number: ' . $result2['org_phone'] . "<br>";
echo "E-mail: " . $result2['org_email'] . "<br>";
echo 'Contact person: ' . $result2['org_contact'] . "<br>";
echo 'Notes:<br>' . $orgnotes;

// retrieve dropoffs and pickups for this food drive

$sql2 = "SELECT * FROM view_dropoffpickupexpanded WHERE org_id=$result2[org_id]";

$result3 = $connect->query($sql2) or die($connect->error);

// create table displaying food drives

echo '<h1>Food Drives for ' . $orgname . '</h1>';

$sql3 = "SELECT * FROM view_fooddrivelist WHERE org_id=$result2[org_id]";

$result5 = $connect->query($sql3) or die($connect->error);

echo '<table border="1"><tr><th>Start Date</th><th>End Date</th><th>Notes</th><th>Action</th></tr>';

while ($result6 = $result5->fetch_assoc()) {
	$startdate = new DateTime($result6["fooddrive_startdate"]);
	$enddate = new DateTime($result6["fooddrive_enddate"]);
	
	echo '<tr><td>' . $startdate->format('D, M jS, Y') . '</td><td>';
	echo $enddate->format('D, M jS, Y') . '</td><td>';
	echo $result6["fooddrive_notes"] . '</td><td>';
	
	echo '<form><input type="hidden" name="fdid" value="' . $result6['fooddrive_id'] . '">' . '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Edit Food Drive</button><br><button type="submit" formmethod="post" formaction="fooddrivesingle.php">View Food Drive</button><br><button type="submit" formmethod="post" formaction="fooddrivedelete.php">Delete Food Drive</button> </form></td></tr>';	

}

echo '</table>';


// create table displaying the dropoffs and pickups

echo '<h1>Scheduled Dropoffs and Pickups for ' . $orgname . '</h1>';

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
  
  echo '</td><td>';

  echo '<form>';
  echo '<input type="hidden" name="fooddriveorg" value="' . $result4['fooddrive_id'] . '">';
  
  if ($result4["type"] == 'dropoff') {
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
  }

  if ($result4["type"] == 'pickup') {
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

}

  echo '</form>';
  
  echo '</td></tr>';

}
echo '</table>';

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="orgid" value="' . $orgid . '">';
echo '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Create Food Drive for ' . $orgname . '</button>';
echo '</form>';

echo '<form>';
echo '<input type="hidden" name="orgid" value="' . $orgid . '">';
echo '<button type="submit" formmethod="post" formaction="orgadd.php">Edit Info for ' . $orgname . '</button>';
echo '</form>';


echo '<form>';
echo '<button type="submit" formmethod="post" formaction="orgdisplay.php">View Organization List</button>';
echo '</form>';

$connect->close();

		
?>