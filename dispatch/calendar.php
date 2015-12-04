<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<?php require 'include.php' ?>	
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='fullcalendar/fullcalendar.min.js'></script>
<?php

require 'conn.php';

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

if (isset($_GET["staffid"])) {

  $os1 = $connect->real_escape_string($_GET["staffid"]);
  
  $sql = "SELECT * FROM staff WHERE staff_id=$os1";

  $result = $connect->query($sql) or die($connect->error);
  
  $result2 = $result->fetch_array(MYSQLI_ASSOC);
  
  echo '<h1>Calendar for ' . $result2["staff_name"] . '</h1>';

}

elseif (isset($_GET["dropoff"])) { 
  echo '<h1>Dropoff Calendar</h1>';
}
elseif (isset($_GET["pickup"])) { 			
  echo '<h1>Pickup Calendar</h1>';
}
else {  
  echo '<h1>Full Calendar</h1>';
}
?>

<script type='text/javascript'>

	$(document).ready(function() {
	
		$('#calendar').fullCalendar({
			header: {
				left: 'title',
				center: '',
				right: 'today prev,next,month,agendaWeek'
			},
		
			editable: false,
			
			events: "calendarfetch.php<?php 
			if (isset($_GET["staffid"])) { 
			  echo '?staffid=' . $os1;
			} 
			elseif (isset($_GET["dropoff"])) { 
			  echo '?dropoff=1'; 
			}
			elseif (isset($_GET["pickup"])) { 
			  echo '?pickup=1'; 
			}

			?>",
			
			eventDrop: function(event, delta) {
				alert(event.title + ' was moved ' + delta + ' days\n' +
					'(should probably update your database)');
			},
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
			
		});
		
	});

</script>

<style type='text/css'>

	/*body {
		margin-top: 10px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}*/
		
	#loading {
		position: absolute;
		top: 5px;
		right: 5px;
		}

	#calendar {
		width: 900px;
		margin: 0 auto;
		}

</style>
</head>
<body>
<div id='loading' style='display:none'>loading...</div>
<div id='calendar'></div>
</body>
</html>
