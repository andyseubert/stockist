<?php
require_once('connect.php');
$alltypessql="SELECT DISTINCT ProperCase(type) as name FROM `items` ORDER BY type";
$alltypes = $conn->query($alltypessql);

$rows = array();

while($row = $alltypes->fetch_assoc()){
    $rows[] = $row;
}
print json_encode($rows);

?>
