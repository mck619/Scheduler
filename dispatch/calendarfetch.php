<?php

	$year = date('Y');
	$month = date('m');

	require 'conn.php';
	
	$connect = $conn;

	if($connect->connect_error){
		die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
			} 

// calendar for specific staff member
			
if (isset($_GET['staffid'])) {

  $os1 = $connect->real_escape_string($_GET["staffid"]);

  $sql = "SELECT * FROM view_dropoffpickupexpanded WHERE staff_id=$os1 ORDER BY dppp_date";	

  $result = $connect->query($sql) or die($connect->error);	
  
}

elseif (isset($_GET['dropoff'])) {

  $sql = "SELECT * FROM view_dropoffpickupexpanded WHERE type='dropoff' ORDER BY dppp_date";	

  $result = $connect->query($sql) or die($connect->error);	
  
}

elseif (isset($_GET['pickup'])) {

  $sql = "SELECT * FROM view_dropoffpickupexpanded WHERE type='pickup' ORDER BY dppp_date";	

  $result = $connect->query($sql) or die($connect->error);	
  
}

else {

	$sql = "SELECT * FROM view_dropoffpickupexpanded ORDER BY dppp_date";	
	$result = $connect->query($sql) or die($connect->error);	
}
	
	$dropoffpickup = array();
	
	while ($result2 = $result->fetch_assoc()) {	
	
	$convertdate = new DateTime($result2['dppp_date']);
	
	if ($result2['type'] == 'dropoff') {

	  $dropoffpickup[] = array(
		  'id' => $result2['dppp_id'],
		  'title' => $result2['type'] . ': ' . $result2['org_name'],		
		  'start' => $convertdate->format('Y-m-d'),
		  'end' => $convertdate->format('Y-m-d'),
		  'url' => "dropoffsingle.php?dropoffid=" . $result2['dppp_id'],
		  'color' => '#008000'
		  );
		  
	  }
	
	if ($result2['type'] == 'pickup') {
	
	  $dropoffpickup[] = array(
		  'id' => $result2['dppp_id'],
		  'title' => $result2['type'] . ': ' . $result2['org_name'],		
		  'start' => $convertdate->format('Y-m-d'),
		  'end' => $convertdate->format('Y-m-d'),
		  'url' => "pickupsingle.php?pickupid=" . $result2['dppp_id'],
		  'color' => '#000080'
		  );
		  
	  }
	
	}

	echo json_encode($dropoffpickup);
			
	/*array(
		
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => "$year-$month-10",
			'url' => "http://yahoo.com/"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => "$year-$month-20",
			'end' => "$year-$month-22",
			'url' => "http://yahoo.com/"
		)
	
	)  ); */

?>
