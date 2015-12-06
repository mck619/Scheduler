<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adding a Food Drive</title>

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


<h1>Select an Organization</h1>
<form> 
<p>Organization Name: 

<select name="fooddriveorg">
<?php

// retrieve food drives from view view_fooddrivelist

$sql = "SELECT fooddrive_id, org_name, org_street, org_city, org_state, org_zip FROM view_fooddrivelist ORDER BY org_name";
$result = $connect->query($sql) or die($connect->error);

// make each category a value in the dropdown box

while ($result2 = $result->fetch_assoc()) {
  echo '<option value="' . $result2["fooddrive_id"] . '">' . $result2["org_name"] . ' - ' . $result2["org_street"] . ', ' .  $result2["org_city"] . ' ' . $result2["org_zip"] . '</option>';
}

?>
</select>

<p>

<button type="submit" formmethod="post" formaction="dropoffadd.php">Schedule Dropoff</button>
<button type="submit" formmethod="post" formaction="pickupadd.php">Schedule Pickup</button>
<input type="reset" />


<p>
</form>

</body>
</html>
