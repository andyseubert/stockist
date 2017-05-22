<?php 

require_once('connect.php');
$allitemssql="select * from items ORDER BY name";
$allitems = $conn->query($allitemssql);

$data = "name,quantity,vendor,vendorpartno,cost,unit,size,type,origin,description\n";
while($row = $allitems->fetch_assoc()) {
  $data .= $row['name'].",".$row['quantity'].",".$row['vendor'].",".$row['vendorpartno'].",".$row['cost'].",".$row['unit'].",".$row['size'].",".$row['type'].",".$row['origin'].",".$row['description']."\n";
}

header('Content-Type: application/csv');
header('Content-Disposition: attachement; filename="items.csv"');
echo $data; exit();
?>