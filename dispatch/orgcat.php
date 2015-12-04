<?php require 'include.php' ?>

<?php


// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

// preparing input for update

if (isset($_POST['orgcatid'])) {
  $os1 = $connect->real_escape_string($_POST["orgcatid"]);

  $stmt = $connect->stmt_init();
  
  $sql = "UPDATE orgcategory SET orgcat_name=?, orgcat_notes=? WHERE orgcat_id=$os1";
  
  $trimnotes = trim($_POST["orgcatnotes"]); 
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
		
	$stmt->bind_param('ss', $_POST["orgcatname"], $trimnotes);
	$stmt->execute();
	$stmt->close();
	  
  }

  // retrieve the record just updated
  
  $sql2 = "SELECT * FROM orgcategory WHERE orgcat_id=$os1";
}

else {

  // prepare input
  
  $stmt = $connect->stmt_init();
  
  $sql = "INSERT INTO orgcategory (orgcat_name, orgcat_notes) VALUES (?, ?)";
  
  if ($stmt->prepare($sql)) {
  
	// bind parameters
	
	$stmt->bind_param('ss', $_POST["orgcatname"], $_POST["orgcatnotes"]);
	$stmt->execute();
	$stmt->close();
	  
  }
  
  // retrieve id of the record just entered
  
  $result = $connect->insert_id;
  
  // retrieve the record just entered
  
  $sql2 = "SELECT * FROM orgcategory WHERE orgcat_id=$result";

}

$result2 = $connect->query($sql2) or die($connect->error);

// fetch results

$result3 = $result2->fetch_array(MYSQLI_ASSOC);

// assign each field from the array into a variable

$orgcatid = $result3['orgcat_id'];
$orgcatname = $result3['orgcat_name'];
$orgcatnotes = $result3['orgcat_notes'];

// print record just entered

echo '<h1>';
if (isset($_POST['orgcatid'])) {
	echo 'Organization Category ' . $orgcatname . ' edited!';
}
else {
	echo 'New Organization Category ' . $orgcatname . ' created!';
	}
echo '</h1>';
echo '<p>Code: ' . $orgcatid . '<br>';
echo 'Notes:' . $orgcatnotes . '</p>';

echo '<h1>Further Action:</h1>';

echo '<form>';
echo '<input type="hidden" name="orgcatid" value="' . $orgcatid . '">';
echo '<button type="submit" formmethod="post" formaction="orgcatadd.php">Edit Category ' . $orgcatname . '</button>';
echo '</form>';

echo '<form>';
echo '<button type="submit" formmethod="post" formaction="orgcatdisplay.php">View Organization Category List</button>';
echo '</form>';

$connect->close();

?> 
