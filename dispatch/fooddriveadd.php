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


<?php 
if (isset($_POST['fdid'])) {

echo '<h1>Editing a food drive</h1>';
}
else {
	echo '<h1>Adding a new food drive</h1>';
}
?>
<form action="fooddrive.php" method="post">
<p>Organization Name: 


<?php

$sql = "SELECT org_id, org_name, org_street, org_city, org_state, org_zip FROM organization ORDER BY org_name";
$result = $connect->query($sql) or die($connect->error);

// check if a food drive id was sent, if so, set $os1 to that organization's id

if (isset($_POST['fdid'])) {
	$fdid = $connect->real_escape_string($_POST["fdid"]);
	$sql3 = "SELECT * FROM view_fooddrivelist WHERE fooddrive_id=$fdid";
	$result5 = $connect->query($sql3) or die($connect->error);
	$result6 = $result5->fetch_assoc();
	$os1 = $result6["org_id"];
	echo '<input type="hidden" name="fdid" value="' . $_POST['fdid'] . '">';
}

// check if an organization id was sent

if (isset($_POST['orgid'])) {
	$os1 = $connect->real_escape_string($_POST["orgid"]);
}

// retrieve organization names from table organization

if (isset($os1) == TRUE) {
  
	$sql2 = "SELECT org_id, org_name, org_street, org_city, org_state, org_zip FROM organization WHERE org_id=$os1";
	
	$result3 = $connect->query($sql2) or die($connect->error);
	
	$result4 = $result3->fetch_assoc();
	echo '<input type="hidden" name="fooddriveorg" value="' . $os1 . '">';
	echo $result4["org_name"] . '<br>' . 'Address: ' . $result4["org_street"] . '<br>' . $result4["org_city"] . ', ' . $result4["org_state"] . " " . $result4["org_zip"];
	  
  }

else {
  echo '<select name="fooddriveorg">';
  
  // make each category a value in the dropdown box
  
  while ($result2 = $result->fetch_assoc()) {
	echo '<option value="' . $result2["org_id"] . '">' . $result2["org_name"] . ' - ' . $result2["org_street"] . ', ' .  $result2["org_city"] . ' ' . $result2["org_zip"] . '</option>';
  }
  
  echo '</select>';
  echo '<p><a href="orgadd.php">Add new organization</a></p>';
}

?>

<p>Start Date:</p>
<?php 

if (isset($_POST['fdid'])) {
echo '<input type="textbox" name="startdate" id="startdate" value="' . $result6["fooddrive_startdate"] . '">';

echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#startdate" ).datepicker({ minDate: -1, maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";
}

elseif (isset($_POST['startdate'])) {
echo '<input type="textbox" name="startdate" id="startdate" value="' . $_POST["startdate"] . '">';

echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#startdate" ).datepicker({ minDate: -1, maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";
}

else {
echo '<input type="textbox" name="startdate" id="startdate" />';

echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#startdate" ).datepicker({ minDate: -1, maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";

}
    
echo '<p>End Date:</p>';

if (isset($_POST['fdid'])) {
echo '<input type="textbox" name="enddate" id="enddate" value="' . $result6["fooddrive_enddate"] . '">';

echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#enddate" ).datepicker({ minDate: -1, maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";	
}

elseif (isset($_POST['enddate'])) {
echo '<input type="textbox" name="enddate" id="enddate" value="' . $_POST["enddate"] . '">';

echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#enddate" ).datepicker({ minDate: -1, maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";	
}

else {

echo '<input type="textbox" name="enddate" id="enddate" />';


echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$("#enddate").datepicker({ minDate: -1, maxDate: "+1Y", showOn: ';
echo "'button', ";
echo 'buttonText: "Click to select date", dateFormat: ';
echo " 'yy-mm-dd' });
  		});
</script>";
}

if (isset($_POST['fdid'])) {

echo '<p>Notes: <br />';
echo '<textarea name="fooddrivenotes"  cols="100"  rows="10">' . $result6["fooddrive_notes"] . '</textarea></p>';

}

else {
echo '<p>Notes: <br />';
echo '<textarea name="fooddrivenotes"  cols="100"  rows="10"></textarea></p>';
	
}

?>

<p>
<input type="submit" />
<input type="reset" />
<p>
</form>

</body>
</html>
