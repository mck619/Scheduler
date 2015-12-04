<?php require 'include.php' ?>

<?php 


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

  $trimnotes = trim($_POST["orgnotes"]); 

// preparing input for update

if (isset($_POST['orgid'])) {
  $os1 = $connect->real_escape_string($_POST["orgid"]);

  $stmt = $connect->stmt_init();
  
  $sql = "UPDATE organization SET org_name=?, org_street=?, org_city=?, org_state=?, org_zip=?, org_phone=?, org_email=?, org_contact=?, org_category=?, org_notes=? WHERE org_id=$os1";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
		
	$stmt->bind_param('ssssssssis', $_POST["orgname"], $_POST["orgstreet"], $_POST["orgcity"], $_POST["orgstate"], $_POST["orgzip"], $_POST["orgphone"], $_POST["orgemail"], $_POST["orgcontact"], $_POST["orgcategory"], $trimnotes);
	$stmt->execute();
	$stmt->close();
	  
  }

  // retrieve the record just updated
  
  $sql2 = "SELECT * FROM organization WHERE org_id=$os1";
}

// preparing input for a new record

else {

  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO organization (org_name, org_street, org_city, org_state, org_zip, org_phone, org_email, org_contact, org_category, org_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('ssssssssis', $_POST["orgname"], $_POST["orgstreet"], $_POST["orgcity"], $_POST["orgstate"], $_POST["orgzip"], $_POST["orgphone"], $_POST["orgemail"], $_POST["orgcontact"], $_POST["orgcategory"], $trimnotes);
	$stmt->execute();
	$stmt->close();
	  
  }



  // retrieve the id of the record just entered
  
  $result = $connect->insert_id;
  
  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM organization WHERE org_id=$result";

}

$result2 = $connect->query($sql2) or die($connect->error);

// fetch results of data

$result3 = $result2->fetch_array(MYSQLI_ASSOC);

// assign each field from the array to a variable

$orgid = $result3['org_id'];
$orgname = $result3['org_name'];
$orgstreet = $result3['org_street'];
$orgcity = $result3['org_city'];
$orgstate = $result3['org_state'];
$orgzip = $result3['org_zip'];
$orgphone = $result3['org_phone'];
$orgemail = $result3['org_email'];
$orgcontact = $result3['org_contact'];
$orgcategory = $result3['org_category'];
$orgnotes = $result3['org_notes'];

// convert $orgcategory (which returns the id of the category in table orgcategory) to the associated name

$sql3 = "SELECT orgcat_name FROM orgcategory WHERE orgcat_id=$orgcategory";

$result4 = $connect->query($sql3) or die($connect->error);

$result5 = $result4->fetch_array(MYSQLI_ASSOC);

// print record just entered

echo '<h1>';
if (isset($_POST['orgid'])) {
	echo 'Organization edited!';
}
else {
	echo 'New Organization created!';
	}
echo '</h1>';
echo $orgid . ' - ' . $orgname . "<br>";
echo 'Address: ' . $orgstreet . "<br>";
echo $orgcity . ', ' . $orgstate . ' ' . $orgzip . "<br>";
echo 'Phone number: ' . $orgphone . "<br>";
echo "E-mail: " . $orgemail . "<br>";
echo 'Contact person: ' . $orgcontact . "<br>";
echo 'Category: ' . $result5['orgcat_name'] . "<br>";
echo nl2br('Notes:<br>' . $orgnotes);

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
