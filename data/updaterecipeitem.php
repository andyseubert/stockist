<?php
// update item amount in recipe

require_once('connect.php');
//expects this:
//{'recipe_id':recipe_id,'item_id':recipeitem.item_id,'value':recipeitem.amount}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
print_r($request);

@$item_id = mysqli_real_escape_string($conn,($request->item_id));
@$recipe_id = mysqli_real_escape_string($conn,($request->recipe_id));
@$value = mysqli_real_escape_string($conn,($request->value));


    $sql="update recipe_item_x set amount = '$value' where item_id = $item_id ";
    $conn->query($sql);


?>