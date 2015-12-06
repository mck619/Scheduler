<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Address Verification</title>
<?php require 'include.php' ?>	
</head>

<?php

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 
		
?>

<body>

<h1>Validation of Organization Addresses</h1>

<?php

$sql = "SELECT * FROM organization";

$result = $connect->query($sql) or die($connect->error);

echo '<table border="1"><tr><th>Organization ID</th><th>Organization Name</th><th>Address</th><th>Phone</th></tr>';

while ($result2 = $result->fetch_array(MYSQLI_ASSOC)) {
	
	if (isValidOrgName($result2['org_name']) && isValidAddress($result2['org_street']) && isValidString($result2['org_state']) && isValidZIPCode($result2['org_zip']) && isValidPhone($result2['org_phone'])) {
		
	}
	else {
		
	echo '<tr><td>' . $result2['org_id'] . '</td><td>' . $result2['org_name'] . '<br>' . formatValid(isValidOrgName($result2['org_name'])) . '</td><td>' . $result2['org_street'] . ", " . $result2['org_city'] . ", " . $result2['org_state'] . " " . $result2['org_zip'] . '<br> Street:' . formatValid(isValidAddress($result2['org_street'])) . '<br>City: ' . formatValid(isValidString($result2['org_state'])) . '<br>ZIP:' . formatValid(isValidZIPCode($result2['org_zip'])) . '</td><td>' . $result2['org_phone'] . '<br>' . formatValid(isValidPhone($result2['org_phone'])) . '</td>';
	
	echo '</tr>';
	}
	
}
	
/* while ($result2 = $result->fetch_array(MYSQLI_ASSOC)) {
	
	echo '<tr><td>' . $result2['org_id'] . '</td><td>' . $result2['org_name'] . '<br>' . formatValid(isValidOrgName($result2['org_name'])) . '</td><td>' . $result2['org_street'] . ", " . $result2['org_city'] . ", " . $result2['org_state'] . " " . $result2['org_zip'] . '<br> Street:' . formatValid(isValidAddress($result2['org_street'])) . '<br>City: ' . formatValid(isValidString($result2['org_state'])) . '<br>ZIP:' . formatValid(isValidZIPCode($result2['org_zip'])) . '</td><td>' . $result2['org_phone'] . '<br>' . formatValid(isValidPhone($result2['org_phone'])) . '</td>';
	
	echo '</tr>';
	
} */

echo '</table>';

?>


</body>
</html>