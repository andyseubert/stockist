<?php
// update item values in inventory

require_once('connect.php');
//{'recipe_id':recipe_id,'name':name}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//print_r($request);

@$recipe_id = mysqli_real_escape_string($conn,($request->recipe_id));
@$new_name = mysqli_real_escape_string($conn,($request->name));

if ($recipe_id < 1000000){
//printf("update recipes set name = '$name' where recipe_id = $recipe_id ");
    $sql="update recipes set name = '$name' where recipe_id = $recipe_id";
    $conn->query($sql);

}

?>