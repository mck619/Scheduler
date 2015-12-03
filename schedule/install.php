<?php
require 'conn.php';

// Connect to MySQL

$connect = $conn2;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }  

// Table creation

// stores data from web-users

$sql = "CREATE TABLE IF NOT EXISTS organization (
  org_id smallint(5) unsigned NOT NULL auto_increment,
  org_name varchar(100) NOT NULL,
  org_street varchar(100),
  org_city varchar(60),
  org_state char(2),
  org_zip varchar(10),
  org_phone varchar(30),
  org_contact varchar(60),
  org_email varchar(100),
  org_notes text,
  org_startdate date NOT NULL default '0000-00-00',
  org_enddate date NOT NULL default '0000-00-00',
  org_import smallint(5) unsigned NOT NULL,
  PRIMARY KEY (org_id)
  )";

// Execute query

$result = $connect->query($sql) or die($connect->error);


if ($sql) {
	echo "Table organization has created<br>";
}

$connect->close();