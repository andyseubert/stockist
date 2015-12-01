<?php
// 
// this is called when a batch is "made"
// 

//expects this:
/*
from a recipe should get the recipe object. Copy all of the current recipe item data
into batch items in order to preserve the actual ingredients of each batch because over time
the vendors will sell different things. costs,name,etc could change for a given item_id

recipeitem.amount should already be multiplied by the batch amount before sending here

{
	'recipe_id':recipe_id,
	'batch.multiplier':batch.multiplier,

	'recipe_items':
	[
		{
		'item_id':recipeitem.item_id,
		'recipeitem.amount':recipeitem.amount
		},
		{
		'item_id':recipeitem.item_id,
		'recipeitem.amount':recipeitem.amount
		}
	]
}
*/

require_once('connect.php');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
print_r($request);

@$item_id = mysqli_real_escape_string($conn,($request->item_id));
@$recipe_id = mysqli_real_escape_string($conn,($request->recipe_id));
@$value = mysqli_real_escape_string($conn,($request->value));


    $sql="update recipe_item_x set amount = '$value' where item_id = $item_id ";
    $conn->query($sql);


?>