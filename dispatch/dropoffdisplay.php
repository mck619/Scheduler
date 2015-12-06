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
  $os1 = 5;
}
if (($os1 == 0) || ($os1 > 5) || ($os1 < -5)) {
  $os1 = 5;
}

if (isset($_GET['sort2'])) {
  $os2 = $_GET['sort2'];
}
else {
  $os2 = 0;
}
if (($os2 > 5) || ($os2 < -5)) {
  $os2 = 0;
}

if (isset($_GET['sort3'])) {
  $os3 = $_GET['sort3'];
}
else {
  $os3 = 0;
}
if (($os3 > 5) || ($os3 < -5)) {
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
	  $sort = 'dropoff_id DESC';
	}
	if ($os1 == 3) {
	  $sort = 'dropoff_id ASC';
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
	  $sort = 'dropoff_date DESC';
	}
	if ($os1 == 5) {
	  $sort = 'dropoff_date ASC';
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
	  $sort2 = ', dropoff_id DESC';
	}
	if ($os2 == 3) {
	  $sort2 = ', dropoff_id ASC';
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
	  $sort = 'dropoff_date DESC';
	}
	if ($os2 == 5) {
	  $sort = 'dropoff_date ASC';
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
	  $sort3 = ', dropoff_id DESC';
	}
	if ($os3 == 3) {
	  $sort3 = ', dropoff_id ASC';
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
	  $sort = 'dropoff_date DESC';
	}
	if ($os3 == 5) {
	  $sort = 'dropoff_date ASC';
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

  $sql = "SELECT * FROM view_dropoff WHERE dropoff_complete=0 ORDER BY $sortline";
	 
 }

else {

  $sql = "SELECT * FROM view_dropoff WHERE dropoff_date BETWEEN '$osd2' AND '$osd3' ORDER BY $sortline"; 

}

  $result = $connect->query($sql) or die($connect->error);

  if ($osd2 != NULL) {

  $reportdate = new DateTime($osd2);
  $reportdateend = new DateTime($osd3);
    
  echo '<h1>All Dropoffs from '. $reportdate->format('D, M jS, Y') . ' to ' . $reportdateend->format('D, M jS, Y') . '</h1>';
  
}

else {

// make a table showing list of dropoffs

echo '<h1>Current Dropoffs</h1>';

echo '<p>This is a list of all dropoffs that have not yet been completed</p>';

}

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
echo '>Date</option>';
echo '<option value="-5" ';
if ($os1 == -5) {
  echo 'selected';	
}
echo '>Date - reverse</option>';
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
echo '>Date</option>';
echo '<option value="-5" ';
if ($os2 == -5) {
  echo 'selected';	
}
echo '>Date - reverse</option>';

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
echo '>Date</option>';
echo '<option value="-5" ';
if ($os3 == -5) {
  echo 'selected';	
}
echo '>Date - reverse</option>';

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


echo '<button type="submit" formmethod="get" formaction="dropoffdisplay.php">Set Date Range</button></form>';

echo '<table border="1"><tr><th>ID</th><th>Category</th><th>Organization Name &amp; Address</th><th>Contact Phone & E-mail</th><th>Organization &amp; Food Drive Notes</th><th>Dropoff Date</th><th>Staff</th><th>Items Required</th><th>Action</th></tr>';

// run through each dropoff

$curdate = new DateTime();

while ($result2 = $result->fetch_assoc()) {

  echo '<tr><td>' . $result2["dropoff_id"] . '</td><td>' . $result2["orgcat_name"] . '</td><td>' . $result2["org_name"] . '<br>' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"] . '</td><td>' . $result2["org_contact"] . '<br>' . $result2["org_phone"] . '<br>' . '<a href="mailto:' . $result2["org_email"] . '">' . $result2["org_email"] . '</a></td><td>';

  echo nl2br($result2['org_notes']);
  echo '<p>';  
  echo nl2br($result2['fooddrive_notes']);
  
  $startdate = new DateTime($result2["dropoff_date"]);
  
  if ($startdate <= $curdate) {
	
	echo '</td><td><span class="expired">' . $startdate->format('D, M jS, Y') . '</span>';
	  
  }
  
  else {
  
	echo '</td><td>' . $startdate->format('D, M jS, Y');
  
  }

  if ($result2['dropoff_complete'] == 1) {
	  
	  echo '<p><strong>Completed</strong>';
	  
  }

  echo '</td>';
  
  echo '<td>' . $result2["staff_name"] . '</td>';
  
  echo '<td>';

  // create list of items for each dropoff
  
  $sql2 = "SELECT * from view_dropoffitemexpanded WHERE dropoff_id=$result2[dropoff_id]";
  
  $result3 = $connect->query($sql2) or die($connect->error);
  
  while ($result4 = $result3->fetch_assoc()) {
	  echo $result4["item_name"] . ': ' . $result4["dpitem_amount"] . '<br>';
  }
  
  echo '</td>';
  
  echo '<td><form><input type="hidden" name="dropoffid" value="' . $result2['dropoff_id'] . '">';
  echo '<input type="hidden" name="fooddriveorg" value="' . $result2['fooddrive_id'] . '">';
  echo '<button type="submit" formmethod="post" formaction="dropoffadd.php">Edit Dropoff</button><br>';

if ($result2["dropoff_complete"] == 0) {

  echo '<button type="submit" formmethod="post" formaction="dropoffpickupcomplete.php">Complete Dropoff</button><br>';
  
}

  echo '<button type="submit" formmethod="get" formaction="dropoffsingle.php">View Detail</button><br>';

if ($result2["dropoff_complete"] == 0) {

  echo '<button type="submit" formmethod="post" formaction="dropoffdelete.php">Delete Dropoff</button>';
}
  echo '</form></td></tr>';


}

echo '</table>';


$connect->close();

?> 
