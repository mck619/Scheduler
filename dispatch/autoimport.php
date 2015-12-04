<?php
require 'conn.php';

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 

$sql = "SELECT * FROM import";

$result = $connect->query($sql) or die($connect->error);

print_r($result);

while ($result2 = $result->fetch_assoc()) {
  $stmt = $connect->stmt_init();
  
  $sql2 = "INSERT INTO organization (org_name, org_street, org_city, org_state, org_zip, org_phone, org_email, org_contact, org_notes, org_category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
  
  if ($stmt->prepare($sql2)) {
  
	// bind parameters
	
	$stmt->bind_param('sssssssss', $result2["name"], $result2["street"], $result2["city"], $result2["state"], $result2["zip"], $result2["phone"], $result2["email"], $result2["contact"], $result2["notes"]);
	$stmt->execute();
	$stmt->close();

  }
  /*
  
  $sql2 = "INSERT INTO organization (org_name, org_street, org_city, org_state, org_zip, org_phone, org_email, org_contact, org_notes, org_category) VALUES ('$result2[name]', '$result2[street]', '$result2[city]', '$result2[state]', '$result2[zip]', '$result2[phone]', '$result2[email]', '$result2[contact]', '$result2[notes]', 1)"; 

  $result3 = $connect->query($sql2) or die($connect->error); */

  // retrieve the id of the record just entered
  
  $result4 = $connect->insert_id;
  $sql3 = "SELECT * FROM organization WHERE org_id=$result4";
  $result5 = $connect->query($sql3) or die($connect->error);

  // fetch results of data
  
  $result6 = $result5->fetch_array(MYSQLI_ASSOC);
  
  $orgname = $result6['org_name'];
  
  echo $orgname . '<br>';
    
}
	  
?>
		

