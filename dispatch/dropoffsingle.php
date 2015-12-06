<?php require 'include.php' ?>
<?php 



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// retrieve dropoff information

if (isset($_GET['dropoffid'])) {

  $os1 = $connect->real_escape_string($_GET["dropoffid"]);

  $sql = "SELECT * FROM view_dropoff WHERE dropoff_id=$os1";
  $result = $connect->query($sql) or die($connect->error);
	
}

else {
  echo '<p>You must select a dropoff before using this view.';
  die;
}

$result2 = $result->fetch_array(MYSQLI_ASSOC);

// assign each field from the array to a variable

$dpid = $result2['dropoff_id'];
$dpfooddrive = $result2['dropoff_fooddrive'];
$dpdate = $result2['dropoff_date'];
$dpstaff = $result2['dropoff_staff'];
$dpcomplete = $result2['dropoff_complete'];

// convert dates to US format

$dropoffdate = new DateTime($dpdate);

// print record just entered

echo "<h1>Dropoff Detail</h1>";

echo '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' . $result2["org_street"] . ' ' . $result2["org_city"] . ' ' . $result2["org_state"] . ' ' . $result2["org_zip"] . '&output=embed"></iframe>';

echo '<div style="float:left; margin-right:25px">';

echo 'Food Drive ID: ' . $result2['fooddrive_id'] . '<br>';
echo 'Dropoff ID: ' . $dpid . '<br>';
echo 'Organization:' . $result2['org_name'] . "<br>";
echo 'Address: ' . $result2['org_street'] . "<br>";
echo $result2['org_city'] . ', ' . $result2['org_state'] . ' ' . $result2['org_zip'] . "<br>";
echo 'Phone number: ' . $result2['org_phone'] . "<br>";
echo "E-mail: " . $result2['org_email'] . "<br>";
echo 'Contact person: ' . $result2['org_contact'] . "<br>";
echo nl2br('Organization notes:<br>' . $result2['org_notes']);
echo nl2br('<p>Food Drive notes:<br>' . $result2['fooddrive_notes']);
echo '<p>Dropoff Date: ' . $dropoffdate->format('l, F jS, Y') . '<br>';
echo 'Dropoff Staff: ' . $result2['staff_name'];
echo '<p>Is Dropoff complete? '; 

if ($dpcomplete == 1) { 
  echo 'Yes <br>';
  }

if ($dpcomplete == 0) {
  echo 'No <br>';
  }
  
echo '</div>';  

echo '<p>Items to drop off:<br> <table><tr><th>Item Name</th><th>Item Description</th><th>Amount</th></tr>';

$sql2 = "SELECT item_name, item_desc, dpitem_amount FROM view_dropoffitemexpanded WHERE dpitem_dropoff=$dpid";

$result3 = $connect->query($sql2) or die($connect->error);

while ($result4 = $result3->fetch_assoc()) {
	echo '<tr><td>' . $result4['item_name'] . '</td><td>' . $result4['item_desc'] . '</td><td>' . $result4['dpitem_amount'] . '</tr>';
}

echo '</table>';

echo '<h1>Further Action:</h1>';

if ($dpcomplete == 0) {

  echo '<form><input type="hidden" name="dropoffid" value="' . $result2['dropoff_id'] . '">';
  echo '<input type="hidden" name="fooddriveorg" value="' . $result2['fooddrive_id'] . '">';	
  echo '<button type="submit" formmethod="post" formaction="dropoffpickupcomplete.php">Complete Dropoff</button><p>';
}

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $result2['fooddrive_id'] . '"><input type="hidden" name="dropoffid" value="' . $dpid . '">';
echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Edit This Dropoff</button>';
echo '</form>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $result2['fooddrive_id'] . '">';
echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Schedule Pickup for ' . $result2['org_name'] . '</button>';
echo '</form>';


echo '<form>';
echo '<input type="hidden" name="fdid" value="' . $result2['fooddrive_id'] . '">';
echo '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Edit Food Drive for ' . $result2['org_name'] . '</button>';
echo '</form>';

echo '<form>';
echo '<input type="hidden" name="orgid" value="' . $result2['org_id'] . '">';
echo '<button type="submit" formmethod="post" formaction="orgadd.php">Edit Organization Information for ' . $result2['org_name'] . '</button>';
echo '</form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="dropoffdisplay.php">View Dropoff List</button>';
echo '</form>';


$connect->close();

?> 
