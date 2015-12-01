<?php
require_once('connect.php');

$recipes = $conn->query("select recipe_id, name, notes from recipes ORDER BY name");
    
$rows = array();

while($row = $recipes->fetch_assoc()){
    $rows[] = $row;
}
print json_encode($rows);

?>
