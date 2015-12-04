<?php require 'include.php' ?>
<?php 



// Connect to MySQL

$connect = $conn;

if($connect->connect_error){
    die('Connection error: ('.$connect->connect_errno.'): '.$connect->connect_error);
        }

// retrieve the record just entered

$sql = "SELECT * FROM orgcategory ORDER BY orgcat_name";

$result = $connect->query($sql) or die($connect->error);

// make a table showing categories and their associated note

echo '<h1>Food Drive Categories</h1>';

echo '<p>This is a list of all food drive categories and their descriptions.</p>';

echo '<table border="1"><tr><th>Code</th><th>Category Name</th><th>Category Notes</th><th>Action</th></tr>';

while ($result2 = $result->fetch_assoc()) {
  echo '<tr><td>' . $result2["orgcat_id"] . '</td><td>' . $result2["orgcat_name"] . '</td><td>' . $result2["orgcat_notes"] . '</td><td><form><input type="hidden" name="orgcatid" value="' . $result2['orgcat_id'] . '">';
  echo '<button type="submit" formmethod="post" formaction="orgcatadd.php">Edit Category</button><br><button type="submit" formmethod="post" formaction="orgcatdelete.php">Delete Category</button></form></td></tr>';
}

echo '</table>';


$connect->close();

?> 
