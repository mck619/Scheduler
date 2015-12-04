<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Organizations</title>


<?php require 'include.php' ?>

</head>

<?php 

// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

if (isset($_GET['sort'])) {
  $os1 = $_GET['sort'];
}
else {
  $os1 = 1;
}
if (($os1 == 0) || ($os1 > 4) || ($os1 < -4)) {
  $os1 = 1;
}

if (isset($_GET['sort2'])) {
  $os2 = $_GET['sort2'];
}
else {
  $os2 = 0;
}
if (($os2 > 4) || ($os2 < -4)) {
  $os2 = 0;
}

if (isset($_GET['sort3'])) {
  $os3 = $_GET['sort3'];
}
else {
  $os3 = 0;
}
if (($os3 > 4) || ($os3 < -4)) {
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


// make a table showing list of food drives

echo '<h1>Organization List</h1>';

echo '<p>This is a list of all organizations in the database:</p>';

echo '<form action="orgdisplay.php" method="get">';

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
echo '</select>';

echo '<input type="submit">';

echo '</form>';

// build sort order

if (isset($os1)) {
	if ($os1 == -4) {
	  $sort = 'org_zip DESC';
	}
	if ($os1 == 4) {
	  $sort = 'org_zip ASC';
	}
	if ($os1 == -3) {
	  $sort = 'org_id DESC';
	}
	if ($os1 == 3) {
	  $sort = 'org_id ASC';
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
}

if (isset($os2)) {
	if ($os2 == -4) {
	  $sort2 = ', org_zip DESC';
	}
	if ($os2 == 4) {
	  $sort2 = ', org_zip ASC';
	}
	if ($os2 == -3) {
	  $sort2 = ', org_id DESC';
	}
	if ($os2 == 3) {
	  $sort2 = ', org_id ASC';
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
}

if (isset($os3)) {
	if ($os3 == -4) {
	  $sort3 = ', org_zip DESC';
	}
	if ($os3 == 4) {
	  $sort3 = ', org_zip ASC';
	}
	if ($os3 == -3) {
	  $sort3 = ', org_id DESC';
	}
	if ($os3 == 3) {
	  $sort3 = ', org_id ASC';
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
}

// build the sort URL

$os1sort = -$os1;
$os2sort = -$os2;
$os3sort = -$os3;

echo '<table border="1"><tr>';

echo '<th>';

// sort by ID

echo '<a href="orgdisplay.php';

$sorturl = '?sort=';

if (abs($os1) == 3 ) {
  $sorturl .= $os1sort;
  $sorturl .= '&sort2=';
  $sorturl .= $os2;
  $sorturl .= '&sort3=';
  $sorturl .= $os3;
  echo $sorturl;
}

elseif (abs($os2) == 3 ) {
  $sorturl .= $os1;
  $sorturl .= '&sort2=';
  $sorturl .= $os2sort;
  $sorturl .= '&sort3=';
  $sorturl .= $os3;
  echo $sorturl;
}

elseif (abs($os3) == 3 ) {
  $sorturl .= $os1;
  $sorturl .= '&sort2=';
  $sorturl .= $os2;
  $sorturl .= '&sort3=';
  $sorturl .= $os3sort;
  echo $sorturl;
}

else {
  $sorturl .= 3;
  $sorturl .= '&sort2=';
  $sorturl .= $os1;
  $sorturl .= '&sort3=';
  $sorturl .= $os2;
  echo $sorturl;

}

echo '">';

echo 'ID</a></th>';

// sort by Category

echo '<th>';

$sorturl = '?sort=';

echo '<a href="orgdisplay.php';

if (abs($os1) == 2 ) {
  $sorturl .= $os1sort;
  $sorturl .= '&sort2=';
  $sorturl .= $os2;
  $sorturl .= '&sort3=';
  $sorturl .= $os3;
  echo $sorturl;
}

elseif (abs($os2) == 2 ) {
  $sorturl .= $os1;
  $sorturl .= '&sort2=';
  $sorturl .= $os2sort;
  $sorturl .= '&sort3=';
  $sorturl .= $os3;
  echo $sorturl;
}

elseif (abs($os3) == 2 ) {
  $sorturl .= $os1;
  $sorturl .= '&sort2=';
  $sorturl .= $os2;
  $sorturl .= '&sort3=';
  $sorturl .= $os3sort;
  echo $sorturl;
}

else {
  $sorturl .= 2;
  $sorturl .= '&sort2=';
  $sorturl .= $os1;
  $sorturl .= '&sort3=';
  $sorturl .= $os2;
  echo $sorturl;

}

echo '">';

echo 'Category</a></th>';


// sort by Organization Name

echo '<th>';

$sorturl = '?sort=';

echo '<a href="orgdisplay.php';

if (abs($os1) == 1 ) {
  $sorturl .= $os1sort;
  $sorturl .= '&sort2=';
  $sorturl .= $os2;
  $sorturl .= '&sort3=';
  $sorturl .= $os3;
  echo $sorturl;
}

elseif (abs($os2) == 1 ) {
  $sorturl .= $os1;
  $sorturl .= '&sort2=';
  $sorturl .= $os2sort;
  $sorturl .= '&sort3=';
  $sorturl .= $os3;
  echo $sorturl;
}

elseif (abs($os3) == 1 ) {
  $sorturl .= $os1;
  $sorturl .= '&sort2=';
  $sorturl .= $os2;
  $sorturl .= '&sort3=';
  $sorturl .= $os3sort;
  echo $sorturl;
}

else {
  $sorturl .= 1;
  $sorturl .= '&sort2=';
  $sorturl .= $os1;
  $sorturl .= '&sort3=';
  $sorturl .= $os2;
  echo $sorturl;

}

echo '">';

echo 'Organization Name</a>';

echo ' &amp; ';

// sort by Zipcode

$sorturl = '?sort=';

echo '<a href="orgdisplay.php';

if (abs($os1) == 4 ) {
  $sorturl .= $os1sort;
  $sorturl .= '&sort2=';
  $sorturl .= $os2;
  $sorturl .= '&sort3=';
  $sorturl .= $os3;
  echo $sorturl;
}

elseif (abs($os2) == 4 ) {
  $sorturl .= $os1;
  $sorturl .= '&sort2=';
  $sorturl .= $os2sort;
  $sorturl .= '&sort3=';
  $sorturl .= $os3;
  echo $sorturl;
}

elseif (abs($os3) == 4 ) {
  $sorturl .= $os1;
  $sorturl .= '&sort2=';
  $sorturl .= $os2;
  $sorturl .= '&sort3=';
  $sorturl .= $os3sort;
  echo $sorturl;
}

else {
  $sorturl .= 4;
  $sorturl .= '&sort2=';
  $sorturl .= $os1;
  $sorturl .= '&sort3=';
  $sorturl .= $os2;
  echo $sorturl;

}

echo '">';

echo 'Address</a></th>';


echo '<th>Contact Phone & E-mail</th><th>Organization Notes</th><th>Action</th></tr>';

// retrieve the organization list view

$sortline = $sort . $sort2 . $sort3;

$sql = "SELECT * FROM view_organization ORDER BY $sortline";

$result = $connect->query($sql) or die($connect->error);


while ($result2 = $result->fetch_assoc()) {

  echo '<tr><td>' . $result2["org_id"] . '</td><td>' . $result2["orgcat_name"] . '</td><td>' . $result2["org_name"] . '<br>' . $result2["org_street"] . '<br>' . $result2["org_city"] . ', ' . $result2["org_state"] . ' ' . $result2["org_zip"] . '</td><td>' . $result2["org_contact"] . '<br>' . $result2["org_phone"] . '<br>' . '<a href="mailto:' . $result2["org_email"] . '">' . $result2["org_email"] . '</a></td><td>';

echo nl2br($result2['org_notes']) . '</td>';

echo '<td><form><input type="hidden" name="orgid" value="' . $result2['org_id'] . '">';

// Submit form to create a Food Drive

echo '<button type="submit" formmethod="post" formaction="fooddriveadd.php">Create Food Drive</button><br>';

// Submit form to change organization information

echo '<button type="submit" formmethod="post" formaction="orgadd.php">Edit Info</button>';

echo '<button type="submit" formmethod="post" formaction="orgsingle.php">View Info</button>' . '<br><button type="submit" formmethod="post" formaction="orgdelete.php">Delete Organization</button>' . '</td>';
echo '</tr>';
echo '</form>';

}

echo '</table>';


$connect->close();

?> 
