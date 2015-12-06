<?php
require 'conn.php';

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }  

// View creation

// view_fooddrivelist - displays all food drives along with organization information
  
$sql1 = "CREATE OR REPLACE VIEW view_fooddrivelist AS SELECT * FROM fooddrive INNER JOIN organization ON fooddrive.fooddrive_org=organization.org_id INNER JOIN orgcategory ON organization.org_category=orgcategory.orgcat_id";

// view_dropoffitemexpanded

$sql2 = "CREATE OR REPLACE VIEW view_dropoffitemexpanded AS SELECT * FROM dropoffitem INNER JOIN dropoff ON dropoffitem.dpitem_dropoff=dropoff.dropoff_id INNER JOIN item ON dropoffitem.dpitem_itemid=item.item_id";
  
// view_pickupitemexpanded

$sql3 = "CREATE OR REPLACE VIEW view_pickupitemexpanded AS SELECT * FROM pickupitem INNER JOIN pickup ON pickupitem.ppitem_pickup=pickup.pickup_id INNER JOIN item ON pickupitem.ppitem_itemid=item.item_id";

// view_dropoffpickup

$sql4 = "CREATE OR REPLACE VIEW view_dropoff AS SELECT * FROM dropoff INNER JOIN fooddrive ON dropoff.dropoff_fooddrive=fooddrive.fooddrive_id INNER JOIN organization ON fooddrive.fooddrive_org=organization.org_id INNER JOIN orgcategory ON organization.org_category=orgcategory.orgcat_id INNER JOIN staff ON dropoff.dropoff_staff=staff.staff_id";

$sql5 = "CREATE OR REPLACE VIEW view_pickup AS SELECT * FROM pickup INNER JOIN fooddrive ON pickup.pickup_fooddrive=fooddrive.fooddrive_id INNER JOIN organization ON fooddrive.fooddrive_org=organization.org_id INNER JOIN orgcategory ON organization.org_category=orgcategory.orgcat_id INNER JOIN staff ON pickup.pickup_staff=staff.staff_id";

$sql6 = "CREATE OR REPLACE VIEW view_organization AS SELECT * FROM organization INNER JOIN orgcategory ON organization.org_category=orgcategory.orgcat_id";

$sql7 = "CREATE OR REPLACE VIEW view_dropoffpickup AS SELECT 'dropoff' AS type, dropoff_id AS dppp_id, dropoff_fooddrive AS dppp_fooddrive, dropoff_date AS dppp_date, dropoff_staff AS dppp_staff, dropoff_complete AS dppp_complete FROM dropoff UNION ALL SELECT 'pickup' AS type, pickup_id AS dppp_id, pickup_fooddrive AS dppp_fooddrive, pickup_date AS dppp_date, pickup_staff AS dppp_staff, pickup_complete AS dppp_complete FROM pickup";

$sql8 = "CREATE OR REPLACE VIEW view_dropoffpickupexpanded AS SELECT * FROM view_dropoffpickup INNER JOIN fooddrive ON view_dropoffpickup.dppp_fooddrive=fooddrive.fooddrive_id INNER JOIN organization ON fooddrive.fooddrive_org=organization.org_id INNER JOIN orgcategory ON organization.org_category=orgcategory.orgcat_id INNER JOIN staff ON view_dropoffpickup.dppp_staff=staff.staff_id";

/*
 
// dropoffitem - stores items that are to be dropped off along with the id of the associated dropoff
  
$sql8 = "CREATE TABLE IF NOT EXISTS dropoffitem (
  dpitem_id mediumint(8) unsigned NOT NULL auto_increment,
  dpitem_dropoff mediumint(8) unsigned NOT NULL,
  dpitem_itemid tinyint(3) unsigned NOT NULL,
  dpitem_amount tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (dpitem_id),
  FOREIGN KEY (dpitem_dropoff) REFERENCES dropoff(dropoff_id),
  FOREIGN KEY (dpitem_itemid) REFERENCES item(item_id)
  )";
  
// pickupitem - stores items that are to be picked up along with the id of the associated pickup
  
$sql9 = "CREATE TABLE IF NOT EXISTS pickupitem (
  ppitem_id mediumint(8) unsigned NOT NULL auto_increment,
  ppitem_pickup mediumint(8) unsigned NOT NULL,
  ppitem_itemid tinyint(3) unsigned NOT NULL,
  ppitem_amount tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (ppitem_id),
  FOREIGN KEY (ppitem_pickup) REFERENCES pickup(pickup_id),
  FOREIGN KEY (ppitem_itemid) REFERENCES item(item_id)
  )";
  
*/

// Execute query

$result = $connect->query($sql1) or die($connect->error);
$result2 = $connect->query($sql2) or die($connect->error);
$result3 = $connect->query($sql3) or die($connect->error);
$result4 = $connect->query($sql4) or die($connect->error);
$result5 = $connect->query($sql5) or die($connect->error);
$result6 = $connect->query($sql6) or die($connect->error);
$result7 = $connect->query($sql7) or die($connect->error);
$result8 = $connect->query($sql8) or die($connect->error);

/*

$result9 = $connect->query($sql9) or die($connect->error);

*/

if ($sql1) {
	echo "View view_fooddrivelist has created<br>";
}

if ($sql2) {
	echo "View view_dropoffitemexpanded has created<br>";
}

if ($sql3) {
	echo "View view_pickupitemexpanded has created<br>";
}

if ($sql4) {
	echo "View view_dropoff has created<br>";
}

if ($sql5) {
	echo "View view_pickup has created<br>";
}

if ($sql6) {
	echo "View view_organization has created<br>";
}

if ($sql7) {
	echo "View view_dropoffpickup has created<br>";
}

if ($sql8) {
	echo "View view_dropoffpickupexpanded has been created<br>";
}

/*

if ($sql9) {
	echo "Table pickupitem has created<br>";
}

*/

$connect->close();