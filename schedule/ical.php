<?php
require '../dispatch/conn.php';

$connect = $conn;

if($connect->connect_error){
	die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
	} 

// insert header information

//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');

$ical = "BEGIN:VCALENDAR\r
VERSION:2.0\r
METHOD:PUBLISH\r
PRODID:-//Westside Food Bank//Food Drive Scheduler//EN\r
X-WR-TIMEZONE:America/Los_Angeles\r
";

// retrieve events from database

$sql = "SELECT * FROM view_dropoffpickupexpanded ORDER BY dppp_date";	
$result = $connect->query($sql) or die($connect->error);	

while ($result2 = $result->fetch_assoc()) {

$dpdate = new DateTime($result2['dppp_date']);

$ical .= "BEGIN:VEVENT\r\n" .
"UID: " . $result2['dppp_id'] . "\r\n"; 
$summary = "SUMMARY:" . ucfirst($result2['type']) . ": " . str_replace(",", "\,", $result2['org_name']) . "\r\n";
$location = "LOCATION:" . str_replace(",", "\,", $result2['org_street']) . "\, " . str_replace(",", "\,", $result2['org_city']) . "\, " . $result2['org_state'] . ' ' . $result2['org_zip'] . "\r\n";
$ical .= $summary . $location . 
'DESCRIPTION: http://test.wsfb.org/dispatch/' . $result2['type'] . 'single.php?' . $result2['type'] . 'id=' . $result2['dppp_id'] . "\r\n" .
"DTSTART;VALUE=DATE:" . $dpdate->format('Ymd') ."\r\n";
$dpdate->modify('+1 day');
$ical .= "DTEND;VALUE=DATE:" . $dpdate->format('Ymd') . "\r\n" .
"DTSTAMP: " . gmdate('Ymd'). 'T'. gmdate('His') . "Z\r\n" .
"END:VEVENT\r\n"; 


}

// insert footer information

$ical .= "END:VCALENDAR";

echo $ical;

?>