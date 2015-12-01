<?php
// remove item from a recipe
include('connect.php');

require_once('connect.php');
//expects this:
//{'item_id':item_id,'recipe_id':recipe_id}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//print_r($request);

@$item_id = mysqli_real_escape_string($conn,($request->item_id));
@$recipe_id = mysqli_real_escape_string($conn,($request->recipe_id));


    $sql="DELETE FROM recipe_item_x WHERE item_id='".$item_id."' AND recipe_id='".$recipe_id."'";
    $conn->query($sql);
    if ($conn->error){
        print_r($conn->error);
    }
// now get the item by id and return it


?>