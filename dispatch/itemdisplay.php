<head>
<?php require 'include.php' ?>
</head>
<?php 



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// retrieve the item table

$sql = "SELECT * FROM item ORDER BY item_name";

$result = $connect->query($sql) or die($connect->error);

// make a table showing list of items

echo '<h1>Item List</h1>';

echo '<p>This is a list of all items in the system:</p>';

echo '<table border="1"><tr><th>ID</th><th>Item Name</th><th>Description</th><th>Status</th><th>Action</th></tr>';

while ($result2 = $result->fetch_assoc()) {

echo '<tr><td>' . $result2["item_id"] . '</td><td>' . $result2["item_name"] . '</td><td>' . $result2["item_desc"] . '</td><td>';

if ($result2["item_active"] == 1) {
echo 'Dropoffs and Pickups';
}

if ($result2["item_active"] == 2) {
echo 'Dropoffs Only';
}

if ($result2["item_active"] == 3) {
echo 'Pickups Only';
}

if ($result2["item_active"] == 0) {
echo 'Inactive';
}

echo '</td><td>';


echo '<form><input type="hidden" name="itemid" value="' . $result2['item_id'] . '">';

echo '<button type="submit" formmethod="post" formaction="itemadd.php">Edit Item</button>' . '<br><button type="submit" formmethod="post" formaction="itemdelete.php">Delete Item</button>';

echo '</form></td></tr>';

}

echo '</table>';


$connect->close();

?> 
