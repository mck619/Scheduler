<?php require 'include.php' ?>	
<?php 



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// retrieve dropoff information

if (isset($_GET['pickupid'])) {

  $os1 = $connect->real_escape_string($_GET["pickupid"]);

  $sql = "SELECT * FROM view_pickup WHERE pickup_id=$os1";
  $result = $connect->query($sql) or die($connect->error);
	
}

else {
  echo '<p>You must select a pickup before using this view.';
  die;
}

$result2 = $result->fetch_array(MYSQLI_ASSOC);

// assign each field from the array to a variable

$ppid = $result2['pickup_id'];
$ppfooddrive = $result2['pickup_fooddrive'];
$ppdate = $result2['pickup_date'];
$ppstaff = $result2['pickup_staff'];
$ppcomplete = $result2['pickup_complete'];

// convert dates to US format

$pickupdate = new DateTime($ppdate);

// print record just entered

echo "<h1>Pickup Detail</h1>";

echo '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' . $result2["org_street"] . ' ' . $result2["org_city"] . ' ' . $result2["org_state"] . ' ' . $result2["org_zip"] . '&output=embed"></iframe>';

echo '<div style="float:left; margin-right:25px">';

echo 'Food Drive ID: ' . $result2['fooddrive_id'] . '<br>';
echo 'Pickup ID: ' . $ppid . '<br>';
echo 'Organization:' . $result2['org_name'] . "<br>";
echo 'Address: ' . $result2['org_street'] . "<br>";
echo $result2['org_city'] . ', ' . $result2['org_state'] . ' ' . $result2['org_zip'] . "<br>";
echo 'Phone number: ' . $result2['org_phone'] . "<br>";
echo "E-mail: " . $result2['org_email'] . "<br>";
echo 'Contact person: ' . $result2['org_contact'] . "<br>";
echo nl2br('Organization notes:<br>' . $result2['org_notes']);
echo nl2br('<p>Food Drive notes:<br>' . $result2['fooddrive_notes']);
echo '<p>Pickup Date: ' . $pickupdate->format('l, F jS, Y') . '<br>';
echo 'Pickup Staff: ' . $result2['staff_name'];
echo '<p>Is Pickup complete? '; 

if ($ppcomplete == 1) { 
  echo 'Yes <br>';
  echo 'Pounds received: ' . $result2['pickup_pounds'] . '<br>';
  }

if ($ppcomplete == 0) {
  echo 'No <br>';
  }
 
echo '<p>Pounds Received: ' . $result2['pickup_pounds'];

echo '<p>Items to pick up:<br> <table><tr><th>Item Name</th><th>Item Description</th><th>Amount</th></tr>';

$sql2 = "SELECT item_name, item_desc, ppitem_amount FROM view_pickupitemexpanded WHERE ppitem_pickup=$ppid";

$result3 = $connect->query($sql2) or die($connect->error);

while ($result4 = $result3->fetch_assoc()) {
	echo '<tr><td>' . $result4['item_name'] . '</td><td>' . $result4['item_desc'] . '</td><td>' . $result4['ppitem_amount'] . '</tr>';
}

echo '</table>';

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="fooddriveorg" value="' . $result2['fooddrive_id'] . '"><input type="hidden" name="pickupid" value="' . $ppid . '">';
echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Edit This Pickup</button>';
echo '<p><button type="submit" formmethod="post" formaction="dropoffpickupcomplete.php">Complete Pickup</button><br>';
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
echo '<button type="submit" formmethod="post" formaction="pickupdisplay.php">View Pickup List</button>';
echo '</form>';


$connect->close();

?> 
