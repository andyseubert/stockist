<?php
// update item values in inventory

require_once('connect.php');
//{'item_id':item_id,'column':item_column,'value':new_value}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//print_r($request);

@$item_id = mysqli_real_escape_string($conn,($request->item_id));
@$column = mysqli_real_escape_string($conn,($request->column));
@$value = mysqli_real_escape_string($conn,($request->value));

if ($item_id < 1000000){
printf("update items set $column = '$value' where item_id = $item_id ");
    $sql="update items set $column = '$value' where item_id = $item_id ";
    $conn->query($sql);

}

?>