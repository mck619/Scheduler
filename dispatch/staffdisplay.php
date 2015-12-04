<head>
<?php require 'include.php' ?>	
</head>

<?php 



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// retrieve the staff table

$sql = "SELECT * FROM staff ORDER BY staff_name";

$result = $connect->query($sql) or die($connect->error);

// make a table showing list of items

echo '<h1>Staff List</h1>';

echo '<p>This is a list of all staff in the system:</p>';

echo '<table border="1"><tr><th>ID</th><th>Staff Name</th><th>Active</th><th>Action</th></tr>';

$i = 1;

while ($result2 = $result->fetch_assoc()) {

echo '<tr><td>' . $result2["staff_id"] . '</td><td>' . $result2["staff_name"] . '</td>';

echo '<td>';

if ($result2['staff_active'] == 0) {
  echo 'No';	
}
if ($result2['staff_active'] == 1) {
  echo 'Yes';	
}

echo '</td>';

echo '<form><input type="hidden" name="staffid" value="' . $result2['staff_id'] . '">';

echo '<td><button type="submit" formmethod="post" formaction="staffadd.php">Edit Staff Member</button><br>' . '<button type="submit" formmethod="post" formaction="staffdelete.php">Delete Staff Member</button><br>' . '<button type="submit" formmethod="post" formaction="staffdispatch.php">View Dropoffs and Pickups</button><br><button type="submit" formmethod="get" formaction="calendar.php">View Calendar</button><br></form>';
echo '<form><input type="hidden" name="staffid" value="' . $result2['staff_id'] . '">Date: <input type="textbox" name="startdate" id="startdate' . $i . '" required>';
echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#startdate' . $i . '" ).datepicker({ minDate: "-1Y", maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";
echo '<button type="submit" formmethod="post" formaction="staffprint.php">View Dispatch for Date Selected</button></form></td></tr>';

$i++;

}

echo '</table>';


$connect->close();

?> 
