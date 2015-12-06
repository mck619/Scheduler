<head>
<?php require 'include.php' ?>
</head>

<?php 

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// set date variables to NULL

$osd2 = NULL;
$osd3 = NULL;

// parse the sorting		
		
if (isset($_GET['sort'])) {
  $os1 = $_GET['sort'];
}
else {
  $os1 = 1;
}
if (($os1 == 0) || ($os1 > 6) || ($os1 < -6)) {
  $os1 = 1;
}

if (isset($_GET['sort2'])) {
  $os2 = $_GET['sort2'];
}
else {
  $os2 = 0;
}
if (($os2 > 6) || ($os2 < -6)) {
  $os2 = 0;
}

if (isset($_GET['sort3'])) {
  $os3 = $_GET['sort3'];
}
else {
  $os3 = 0;
}
if (($os3 > 6) || ($os3 < -6)) {
  $os3 = 0;
}

// error checking

// make sure 3rd sort order is not the same type as either the first or second

if ((abs($os3) == abs($os2)) || (abs($os3) == abs($os1))) {
  $os3 = 0;
}

// make sure 2nd sort order is not the same type as the first

if (abs($os2) == abs($os1)) {
  $os2 = 0;
}

if ($os2 == 0) {
  $sort2 = NULL;
}

if ($os3 == 0) {
  $sort3 = NULL;
}

// build sort order

if (isset($os1)) {
	if ($os1 == -4) {
	  $sort = 'org_zip DESC';
	}
	if ($os1 == 4) {
	  $sort = 'org_zip ASC';
	}
	if ($os1 == -3) {
	  $sort = 'fooddrive_id DESC';
	}
	if ($os1 == 3) {
	  $sort = 'fooddrive_id ASC';
	}
	if ($os1 == -2) {
	  $sort = 'org_category DESC';
	}
	if ($os1 == 2) {
	  $sort = 'org_category ASC';
	}
	if ($os1 == -1) {
	  $sort = 'org_name DESC';
	}
	if ($os1 == 1) {
	  $sort = 'org_name ASC';
	}
	if ($os1 == -5) {
	  $sort = 'fooddrive_startdate DESC';
	}
	if ($os1 == 5) {
	  $sort = 'fooddrive_startdate ASC';
	}	
	if ($os1 == -6) {
	  $sort = 'fooddrive_enddate DESC';
	}
	if ($os1 == 6) {
	  $sort = 'fooddrive_enddate ASC';
	}	
}

if (isset($os2)) {
	if ($os2 == -4) {
	  $sort2 = ', org_zip DESC';
	}
	if ($os2 == 4) {
	  $sort2 = ', org_zip ASC';
	}
	if ($os2 == -3) {
	  $sort2 = ', fooddrive_id DESC';
	}
	if ($os2 == 3) {
	  $sort2 = ', fooddrive_id ASC';
	}
	if ($os2 == -2) {
	  $sort2 = ', org_category DESC';
	}
	if ($os2 == 2) {
	  $sort2 = ', org_category ASC';
	}
	if ($os2 == -1) {
	  $sort2 = ', org_name DESC';
	}
	if ($os2 == 1) {
	  $sort2 = ', org_name ASC';
	}
	if ($os2 == -5) {
	  $sort = 'fooddrive_startdate DESC';
	}
	if ($os2 == 5) {
	  $sort = 'fooddrive_startdate ASC';
	}	
	if ($os2 == -6) {
	  $sort = 'fooddrive_enddate DESC';
	}
	if ($os2 == 6) {
	  $sort = 'fooddrive_enddate ASC';
	}		
}

if (isset($os3)) {
	if ($os3 == -4) {
	  $sort3 = ', org_zip DESC';
	}
	if ($os3 == 4) {
	  $sort3 = ', org_zip ASC';
	}
	if ($os3 == -3) {
	  $sort3 = ', fooddrive_id DESC';
	}
	if ($os3 == 3) {
	  $sort3 = ', fooddrive_id ASC';
	}
	if ($os3 == -2) {
	  $sort3 = ', org_category DESC';
	}
	if ($os3 == 2) {
	  $sort3 = ', org_category ASC';
	}
	if ($os3 == -1) {
	  $sort3 = ', org_name DESC';
	}
	if ($os3 == 1) {
	  $sort3 = ', org_name ASC';
	}
	if ($os3 == -5) {
	  $sort = 'fooddrive_startdate DESC';
	}
	if ($os3 == 5) {
	  $sort = 'fooddrive_startdate ASC';
	}	
	if ($os3 == -6) {
	  $sort = 'fooddrive_enddate DESC';
	}
	if ($os3 == 6) {
	  $sort = 'fooddrive_enddate ASC';
	}	
}

// build the sort URL

$os1sort = -$os1;
$os2sort = -$os2;
$os3sort = -$os3;

$sortline = $sort . $sort2 . $sort3;

if (isset($_GET['startdate'])) {

  $osd2 = $connect->real_escape_string($_GET["startdate"]);
  
  if (isset($_GET['enddate'])) {
	
	$osd3 = $connect->real_escape_string($_GET["enddate"]);
	  
  }
  
  else {
	
	$osd3 = $connect->real_escape_string($_GET["startdate"]);
	  
  }
}


  if ($osd2 == NULL) {
	$sql = "SELECT * FROM view_fooddrivelist WHERE fooddrive_enddate >= (CURDATE() - INTERVAL 14 DAY) ORDER BY $sortline";
  }

  else {

  $sql = "SELECT * FROM view_fooddrivelist WHERE fooddrive_startdate BETWEEN '$osd2' AND '$osd3' OR fooddrive_enddate BETWEEN '$osd2' AND '$osd3'  OR (fooddrive_startdate <= '$osd2' AND fooddrive_enddate >= '$osd3') ORDER BY $sortline"; 
  
  }

  $result = $connect->query($sql) or die($connect->error);

  if ($osd2 != NULL) {

  $reportdate = new DateTime($osd2);
  $reportdateend = new DateTime($osd3);
  echo '<h1>All Food Drives from '. $reportdate->format('D, M jS, Y') . ' to ' . $reportdateend->format('D, M jS, Y') . '</h1>';
  }
  
  else {

  echo '<h1>Current Food Drives</h1>';
  
  echo '<p>This is a list of all food drives which have not ended, or food drives that have ended in the past 2 weeks:</p>';

	  

}

// retrieve the food drive list view

/*

else {

  $sql = "SELECT * FROM view_fooddrivelist WHERE fooddrive_enddate >= CURDATE() ORDER BY $sortline";

  $result = $connect->query($sql) or die($connect->error);
  
  // make a table showing list of food drives
  
  echo '<h1>Current Food Drives</h1>';
  
  echo '<p>This is a list of all food drives which have not ended</p>';

}

*/

echo '<form>';

echo '<p>Sort by...<select name="sort"></p>';
echo '<option value="1" ';
if ($os1 == 1) {
  echo 'selected';	
}
echo '>Organization Name</option>';
echo '<option value="-1" ';
if ($os1 == -1) {
  echo 'selected';	
}
echo '>Organization Name - reverse</option>';
echo '<option value="2" ';
if ($os1 == 2) {
  echo 'selected';	
}
echo '>Category</option>';
echo '<option value="-2" ';
if ($os1 == -2) {
  echo 'selected';	
}
echo '>Category - reverse</option>';
echo '<option value="3" ';
if ($os1 == 3) {
  echo 'selected';	
}
echo '>ID number</option>';
echo '<option value="-3" ';
if ($os1 == -3) {
  echo 'selected';	
}
echo '>ID number - reverse</option>';
echo '<option value="4" ';
if ($os1 == 4) {
  echo 'selected';	
}
echo '>Zipcode</option>';
echo '<option value="-4" ';
if ($os1 == -4) {
  echo 'selected';	
}
echo '>Zipcode - reverse</option>';
echo '<option value="5" ';
if ($os1 == 5) {
  echo 'selected';	
}
echo '>Start Date</option>';
echo '<option value="-5" ';
if ($os1 == -5) {
  echo 'selected';	
}
echo '>Start Date - reverse</option>';
echo '<option value="6" ';
if ($os1 == 6) {
  echo 'selected';	
}
echo '>End Date</option>';
echo '<option value="-6" ';
if ($os1 == -6) {
  echo 'selected';	
}
echo '>End Date - reverse</option>';
echo '</select>';

// second selector

echo ' Then by...<select name="sort2"></p>';

echo '<option value="0" ';
if ($os2 == 0) {
  echo 'selected';	
}
echo '>None</option>';
echo '<option value="1" ';
if ($os2 == 1) {
  echo 'selected';	
}
echo '>Organization Name</option>';
echo '<option value="-1" ';
if ($os2 == -1) {
  echo 'selected';	
}
echo '>Organization Name - reverse</option>';
echo '<option value="2" ';
if ($os2 == 2) {
  echo 'selected';	
}
echo '>Category</option>';
echo '<option value="-2" ';
if ($os2 == -2) {
  echo 'selected';	
}
echo '>Category - reverse</option>';
echo '<option value="3" ';
if ($os2 == 3) {
  echo 'selected';	
}
echo '>ID number</option>';
echo '<option value="-3" ';
if ($os2 == -3) {
  echo 'selected';	
}
echo '>ID number - reverse</option>';
echo '<option value="4" ';
if ($os2 == 4) {
  echo 'selected';	
}
echo '>Zipcode</option>';
echo '<option value="-4" ';
if ($os2 == -4) {
  echo 'selected';	
}
echo '>Zipcode - reverse</option>';
echo '<option value="5" ';
if ($os2 == 5) {
  echo 'selected';	
}
echo '>Start Date</option>';
echo '<option value="-5" ';
if ($os2 == -5) {
  echo 'selected';	
}
echo '>Start Date - reverse</option>';
echo '<option value="6" ';
if ($os2 == 6) {
  echo 'selected';	
}
echo '>End Date</option>';
echo '<option value="-6" ';
if ($os2 == -6) {
  echo 'selected';	
}
echo '>End Date - reverse</option>';
echo '</select>';

// third selector

echo ' Then by...<select name="sort3"></p>';
echo '<option value="0" ';
if ($os3 == 0) {
  echo 'selected';	
}
echo '>None</option>';
echo '<option value="1" ';
if ($os3 == 1) {
  echo 'selected';	
}
echo '>Organization Name</option>';
echo '<option value="-1" ';
if ($os3 == -1) {
  echo 'selected';	
}
echo '>Organization Name - reverse</option>';
echo '<option value="2" ';
if ($os3 == 2) {
  echo 'selected';	
}
echo '>Category</option>';
echo '<option value="-2" ';
if ($os3 == -2) {
  echo 'selected';	
}
echo '>Category - reverse</option>';
echo '<option value="3" ';
if ($os3 == 3) {
  echo 'selected';	
}
echo '>ID number</option>';
echo '<option value="-3" ';
if ($os3 == -3) {
  echo 'selected';	
}
echo '>ID number - reverse</option>';
echo '<option value="4" ';
if ($os3 == 4) {
  echo 'selected';	
}
echo '>Zipcode</option>';
echo '<option value="-4" ';
if ($os3 == -4) {
  echo 'selected';	
}
echo '>Zipcode - reverse</option>';
echo '<option value="5" ';
if ($os3 == 5) {
  echo 'selected';	
}
echo '>Start Date</option>';
echo '<option value="-5" ';
if ($os3 == -5) {
  echo 'selected';	
}
echo '>Start Date - reverse</option>';
echo '<option value="6" ';
if ($os3 == 6) {
  echo 'selected';	
}
echo '>End Date</option>';
echo '<option value="-6" ';
if ($os3 == -6) {
  echo 'selected';	
}
echo '>End Date - reverse</option>';


echo '</select>';

echo '<p>Start Date: <input type="textbox" name="startdate" id="startdate">';
echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#startdate" ).datepicker({ minDate: "-1Y", maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";

echo 'End Date: <input type="textbox" name="enddate" id="enddate">';
echo '<script type="text/javascript">';
echo '		$(document).ready(function(){';
echo '    			$( "#enddate" ).datepicker({ minDate: "-1Y", maxDate: "+1Y", dateFormat:';
echo " 'yy-mm-dd' });
  		});
</script>";


echo '<button type="submit" formmethod="get" formaction="fooddrivedisplay.php">Set Date Range and Sort</button></form></p>';

echo '<table border="1"><tr><th>ID</th><th>Category</th><th>Organization Name &amp; Address</th><th>Contact Phone & E-mail</th><th>Organization &amp; Food Drive Notes</th><th>Start Date</th><th>End Date</th><th>Dropoff Status</th><th>Pickup Status</th><th>Action</th></tr>';

while ($result2 = $result->fetch_assoc()) {

  // count dropoffs
  
  $sql2 = "SELECT COUNT(*) FROM view_dropoff WHERE fooddrive_id=$result2[fooddrive_id]";
  
  $result3 = $connect->query($sql2) or die($connect->error);
  
  $result4 = $result3->fetch_array(MYSQLI_NUM);
  
  // count pickups
  
  $sql3 = "SELECT COUNT(*) FROM view_pickup WHERE fooddrive_id=$result2[fooddrive_id]";
  
  $result5 = $connect->query($sql3) or die($connect->error);
  
  $result6 = $result5->fetch_array(MYSQLI_NUM);
  
  // count completed dropoffs
  
  $sql4 = "SELECT COUNT(*) FROM view_dropoff WHERE fooddrive_id=$result2[fooddrive_id] AND dropoff_complete=1";
  
  $result7 = $connect->query($sql4) or die($connect->error);
  
  $result8 = $result7->fetch_array(MYSQLI_NUM);
  
  
  // count completed pickups
  
  $sql5 = "SELECT COUNT(*) FROM view_pickup WHERE fooddrive_id=$result2[fooddrive_id] AND pickup_complete=1";
  
  $result9 = $connect->query($sql5) or die($connect->error);
  
  $result10 = $result9->fetch_array(MYSQLI_NUM);
  
  echo '<tr><td>' . $result2["fooddrive_id"] . '</td><td>' . $result2["orgcat_name"] . '</td><td>' . $result2["org_name"] . '<br>' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"] . '</td><td>' . $result2["org_contact"] . '<br>' . $result2["org_phone"] . '<br>' . '<a href="mailto:' . $result2["org_email"] . '">' . $result2["org_email"] . '</a></td><td>';
  
  echo nl2br($result2['org_notes']);
  echo '<p>';  
  echo nl2br($result2['fooddrive_notes']);
  
  // convert dates to US format
  
/*  $startdate = new DateTime($result2["fooddrive_startdate"]);
  $enddate = new DateTime($result2["fooddrive_enddate"]);
  
  echo '</td><td>' . $startdate->format('D, M jS, Y') . '</td><td>' . $enddate->format('D, M jS, Y') . '</td>'; */

  
  echo '</td><td>' . dateDefault($result2["fooddrive_startdate"]) . '</td><td>' . dateDefault($result2["fooddrive_enddate"]) . '</td>';
  
  echo '<form><input type="hidden" name="fooddriveorg" value="' . $result2['fooddrive_id'] . '">';
  
  // show number of scheduled dropoffs, number of completed dropoffs
  
  echo '<td>';

  if ($result4[0] == 0) {
  echo '<span class="unscheduled">';
}
  
  echo 'Scheduled: ' . $result4[0] . '<br>Completed: ' . $result8[0] . '<br>';
  echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Schedule Dropoff</button>';

  if ($result4[0] == 0) {
	echo '</span>';	
  }
  
  echo '</td>';
  
  // show number of scheduled pickups, number of completed pickups

  echo '<td>';

  if ($result6[0] == 0) {
  echo '<span class="unscheduled">';
}
  
  echo 'Scheduled: ' . $result6[0] . '<br>Completed: ' . $result10[0] . '<br>';
  echo '<button type="submit" formmethod="post" formaction="pickupadd.php">Schedule Pickup</button>';

  if ($result4[0] == 0) {
	echo '</span>';	
  }  
	
  echo '</td>';
  echo '</form>';
  echo '<td><form><input type="hidden" name="fdid" value="' . $result2['fooddrive_id'] . '">' . '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Edit Food Drive</button><br><button type="submit" formmethod="post" formaction="fooddrivesingle.php">View Food Drive</button><br><button type="submit" formmethod="post" formaction="fooddrivedelete.php">Delete Food Drive</button> </form></td></tr>';

}

echo '</table>';


$connect->close();

?> 
