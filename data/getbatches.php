<?php
require_once('connect.php');

$batches = $conn->query("select batch_id, batch_name, batch_notes, batch_date from batches ORDER BY batch_name");
    
$rows = array();

while($row = $batches->fetch_assoc()){
    $rows[] = $row;
}
print json_encode($rows);

?>
