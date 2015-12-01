<?php
require_once('connect.php');
$allitemssql="select * from items";
$allitems = $conn->query($allitemssql);

$rows = array();

while($row = $allitems->fetch_assoc()){
    $rows[] = $row;
}
print json_encode($rows);

?>
