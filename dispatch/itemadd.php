<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adding a new item</title>
<?php require 'include.php' ?>
</head>

<body>



<?php



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        } 
// check to see if an existing item is being edited, if so, retrieve item details from item table

if (isset($_POST['itemid'])) {
	$os1 = $connect->real_escape_string($_POST["itemid"]);
	$sql = "SELECT * FROM item WHERE item_id=$os1";
	
	$result = $connect->query($sql) or die($connect->error);
	
	$result2 = $result->fetch_assoc();
	echo '<h1>Editing an item</h1>';
	echo '<form action="item.php" method="post">';
	echo '<input type="hidden" name="itemid" value="' . $os1 . '">';
	echo '<p>Item Name: <input type="text" name="itemname" value="' . $result2["item_name"] . '" required maxlength="30" />';
	echo '<p>Item Description:<br> <textarea name="itemdesc"  cols="100"  rows="10">' . $result2["item_desc"] . '</textarea>';

	echo '<p>Item should be displayed for: <label for="dppp"><input type="radio" name="itemactive" id="dppp" value="1"';

	if ($result2["item_active"] == 1) {
	echo 'checked';
	}
	
	echo '>Dropoffs and Pickups</label>';
	
	echo '<label for="dp"><input type="radio" name="itemactive" id="dp" value="2"';
	if ($result2["item_active"] == 2) {
	echo 'checked';
	}
	
	echo '>Dropoffs Only</label>';
	
	echo '<label for="pp"><input type="radio" name="itemactive" id="pp" value="3"';
	if ($result2["item_active"] == 3) {
	echo 'checked';
	}
	echo '>Pickups Only</label>';
	
	echo '<label for="none"><input type="radio" name="itemactive" id="none" value="0"';
	if ($result2["item_active"] == 0) {
	echo 'checked';
	}
	echo '>None</label>';

}

// if not an existing item, display blank form

else {

echo '<h1>Adding a new item</h1>';
echo '<form action="item.php" method="post">';
echo '<p>Item Name: <input type="text" name="itemname" required maxlength="30" />';
echo '<p>Item Description:<br> <textarea name="itemdesc"  cols="100"  rows="10"></textarea> ';
echo '<p>Item should be displayed for: <label for="dppp"><input type="radio" name="itemactive" id="dppp" value="1" checked>Dropoffs and Pickups</label><label for="dp"><input type="radio" name="itemactive" id="dp" value="2">Dropoffs Only</label><label for="pp"><input type="radio" name="itemactive" id="pp" value="3">Pickups Only</label><label for="none"><input type="radio" name="itemactive" id="none" value="0" >None</label>';

}
?>
<p>
<input type="submit" />
<input type="reset" />
<p>
</form>

</body>
</html>
