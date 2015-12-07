<?php
require 'conn.php';

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }  

// Table creation

// orgcategory - defines organizations that are a group
  
$sql = "CREATE TABLE IF NOT EXISTS orgcategory (
  orgcat_id tinyint(3) unsigned NOT NULL auto_increment,
  orgcat_name varchar(20) NOT NULL,
  orgcat_notes text,
  PRIMARY KEY (orgcat_id)
  )ENGINE = MyISAM";
  
// organization - stores data about organization.  Each location should be entered separately - use org_category to group together related organizations

$sql2 = "CREATE TABLE IF NOT EXISTS organization (
  org_id smallint(5) unsigned NOT NULL auto_increment,
  org_name varchar(100) NOT NULL,
  org_street varchar(100),
  org_city varchar(60),
  org_state char(2),
  org_zip varchar(10),
  org_phone varchar(30),
  org_contact varchar(60),
  org_email varchar(100),
  org_category tinyint(3) unsigned NOT NULL,
  org_notes text,
  PRIMARY KEY (org_id),
  FOREIGN KEY (org_category) REFERENCES orgcategory(orgcat_id),
  FULLTEXT(org_name, org_street)
  ) ENGINE = MyISAM";

// fooddrive - stores food drive data
  
$sql3 = "CREATE TABLE IF NOT EXISTS fooddrive (
  fooddrive_id mediumint(8) unsigned NOT NULL auto_increment,
  fooddrive_startdate date NOT NULL default '0000-00-00',
  fooddrive_enddate date NOT NULL default '0000-00-00',
  fooddrive_org smallint(5) unsigned NOT NULL,
  fooddrive_notes text,
  PRIMARY KEY (fooddrive_id),
  FOREIGN KEY (fooddrive_org) REFERENCES organization(org_id)
  )ENGINE = MyISAM";

// staff - stores staff names to be assigned to pickup or dropoff

$sql4 = "CREATE TABLE IF NOT EXISTS staff (
  staff_id tinyint(3) unsigned NOT NULL auto_increment,
  staff_name varchar(30) NOT NULL,
  staff_active tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (staff_id)
  )ENGINE = MyISAM";

// dropoff - stores dropoff data

$sql5 = "CREATE TABLE IF NOT EXISTS dropoff (
  dropoff_id mediumint(8) unsigned NOT NULL auto_increment,
  dropoff_fooddrive mediumint(8) unsigned NOT NULL,
  dropoff_date date NOT NULL default '0000-00-00',
  dropoff_staff tinyint(3) unsigned NOT NULL,
  dropoff_complete tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (dropoff_id),
  FOREIGN KEY (dropoff_fooddrive) REFERENCES fooddrive(fooddrive_id),
  FOREIGN KEY (dropoff_staff) REFERENCES staff(staff_id)
  )ENGINE = MyISAM";
  
// pickup - stores pickup data

$sql6 = "CREATE TABLE IF NOT EXISTS pickup (
  pickup_id mediumint(8) unsigned NOT NULL auto_increment,
  pickup_fooddrive mediumint(8) unsigned NOT NULL,
  pickup_date date NOT NULL default '0000-00-00',
  pickup_staff tinyint(3) unsigned NOT NULL,
  pickup_pounds decimal(8,2) unsigned NOT NULL,
  pickup_complete tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (pickup_id),
  FOREIGN KEY (pickup_fooddrive) REFERENCES fooddrive(fooddrive_id),
  FOREIGN KEY (pickup_staff) REFERENCES staff(staff_id)
  )ENGINE = MyISAM";

// item - stores details of items that are to be dropped off/picked up

$sql7 = "CREATE TABLE IF NOT EXISTS item (
  item_id tinyint(3) unsigned NOT NULL auto_increment,
  item_name varchar(30) NOT NULL,
  item_active tinyint(3) unsigned NOT NULL,
  item_desc text,
  PRIMARY KEY (item_id)
  )ENGINE = MyISAM";
  
// dropoffitem - stores items that are to be dropped off along with the id of the associated dropoff
  
$sql8 = "CREATE TABLE IF NOT EXISTS dropoffitem (
  dpitem_id mediumint(8) unsigned NOT NULL auto_increment,
  dpitem_dropoff mediumint(8) unsigned NOT NULL,
  dpitem_itemid tinyint(3) unsigned NOT NULL,
  dpitem_amount tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (dpitem_id),
  FOREIGN KEY (dpitem_dropoff) REFERENCES dropoff(dropoff_id),
  FOREIGN KEY (dpitem_itemid) REFERENCES item(item_id)
  )ENGINE = MyISAM";
  
// pickupitem - stores items that are to be picked up along with the id of the associated pickup
  
$sql9 = "CREATE TABLE IF NOT EXISTS pickupitem (
  ppitem_id mediumint(8) unsigned NOT NULL auto_increment,
  ppitem_pickup mediumint(8) unsigned NOT NULL,
  ppitem_itemid tinyint(3) unsigned NOT NULL,
  ppitem_amount tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (ppitem_id),
  FOREIGN KEY (ppitem_pickup) REFERENCES pickup(pickup_id),
  FOREIGN KEY (ppitem_itemid) REFERENCES item(item_id)
  )ENGINE = MyISAM";

// Execute query

$result = $connect->query($sql) or die($connect->error);
$result2 = $connect->query($sql2) or die($connect->error);
$result3 = $connect->query($sql3) or die($connect->error);
$result4 = $connect->query($sql4) or die($connect->error);
$result5 = $connect->query($sql5) or die($connect->error);
$result6 = $connect->query($sql6) or die($connect->error);
$result7 = $connect->query($sql7) or die($connect->error);
$result8 = $connect->query($sql8) or die($connect->error);
$result9 = $connect->query($sql9) or die($connect->error);

if ($sql) {
	echo "Table orgcategory has created<br>";
}

if ($sql2) {
	echo "Table organization has created<br>";
}

if ($sql3) {
	echo "Table fooddrive has created<br>";
}

if ($sql4) {
	echo "Table staff has created<br>";
}

if ($sql5) {
	echo "Table dropoff has created<br>";
}

if ($sql6) {
	echo "Table pickup has created<br>";
}

if ($sql7) {
	echo "Table item has created<br>";
}

if ($sql8) {
	echo "Table dropoffitem has created<br>";
}

if ($sql9) {
	echo "Table pickupitem has created<br>";
}

$connect->close();