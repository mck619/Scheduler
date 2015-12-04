<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Food Drive Pickup Totals</title>
<?php require 'include.php' ?>
</head>

<body>
<h1>Completed Food Drives</h1>

<table border="1">
<tr><th>ID</th><th>Category</th><th>Organization Name &amp; Address</th><th>Contact Phone & E-mail</th><th>Start Date</th><th>End Date</th><th>Total Pounds</th><th>Action</th></tr>




<?php

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// get list of pickups with pounds

$sql = "SELECT fooddrive_id, org_name, orgcat_name, org_street, org_city, org_state, org_zip, org_contact, org_phone, org_email, fooddrive_startdate, fooddrive_enddate, SUM(pickup_pounds) FROM view_pickup WHERE pickup_complete=1 GROUP BY fooddrive_id";

$result = $connect->query($sql) or die($connect->error);

// create the table

while ($result2 = $result->fetch_assoc()) {

  echo '<tr><td>' . $result2["fooddrive_id"] . '</td><td>' . $result2["orgcat_name"] . '</td><td>' . $result2["org_name"] . '<br>' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"] . '</td><td>' . $result2["org_contact"] . '<br>' . $result2["org_phone"] . '<br>' . '<a href="mailto:' . $result2["org_email"] . '">' . $result2["org_email"] . '</a></td><td>' . dateDefault($result2["fooddrive_startdate"]) . '</td><td>' . dateDefault($result2["fooddrive_enddate"]) . '</td><td>' . $result2["SUM(pickup_pounds)"] . '</td><td>';

 echo '<form><input type="hidden" name="fdid" value="' . $result2['fooddrive_id'] . '">' . '<button type="submit" formmethod="post" formaction="fooddrivesingle.php">View Food Drive</button><br></form>';  
  
  echo '</td></tr>';
  		
}


?>

</table>
</body>
</html>