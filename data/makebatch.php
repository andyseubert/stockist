<?php
// 
// this is called when a batch is "made"
// 
/*
from a recipe should get the recipe details. Copy all of the current recipe item data
into batch items in order to preserve the actual ingredients of each batch because over time
the vendors will sell different things. costs,name,etc could change for a given item_id

item amounts have not been multiplied before arriving here
THIS IS WHAT THE APP WILL SEND US
'recipe':{
	[recipe_id] => 34
    [name] => A recipe
    [notes] => text
}

'recipe_items':
{[
	{
		[item_id] => 9
        [name] => glass spray bottle
        [description] => 15 ml
        [type] => container
        [unit] => each
        [quantity] => 48
        [vendor] => nemat
        [vendorpartno] => GBSpryElg15mlgl
        [origin] => 
        [cost] => 1.250
        [recipe_id] => 34
        [amount] => 1
        [stockqty] => 48
	},
	{
		...
	}
]}

'multiplier':'2'

*/

require_once('connect.php');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//print_r($request);

@$recipe_id = mysqli_real_escape_string($conn,($request->recipe->recipe_id));
@$recipe_name = mysqli_real_escape_string($conn,($request->recipe->name));
@$recipe_notes = mysqli_real_escape_string($conn,($request->recipe->notes));
@$multiplier = mysqli_real_escape_string($conn,($request->multiplier));

//CREATE THE BATCH HERE TO GET THE BATCH_ID
$sql="INSERT INTO batches (batch_name,batch_notes,batch_date) values ('$recipe_name','$recipe_notes',now())";

//$conn->query($sql);
if (!$result = $conn->query($sql)){
	// TODO: RETURN THIS ERROR AND EXIT IF THIS INSERT FAILS <<<---
    echo "insert failed, error: ", $conn->error;
  }else{
  	$batch_id = $conn->insert_id;
  	echo "batch_id : " , $batch_id , "\n";
}

// INSERT INTO THE batch_items TABLE
	$insert_sql = array(); 
	foreach( $request->recipe_items as $item ) {
	    $insert_sql[] = '("'
	    	 .$batch_id .'","'
	    	 . mysqli_real_escape_string($conn,$item->item_id).'","'
	    	 . mysqli_real_escape_string($conn,$item->amount).'","'
	    	 . mysqli_real_escape_string($conn,$item->unit).'","'
	    	 . mysqli_real_escape_string($conn,$item->cost).'","'
	    	 . mysqli_real_escape_string($conn,$item->description).'","'
	    	 . mysqli_real_escape_string($conn,$item->name).'","'
	    	 . mysqli_real_escape_string($conn,$item->type).'","'
	    	 . mysqli_real_escape_string($conn,$item->origin).'","'
	    	 . mysqli_real_escape_string($conn,$item->vendor).'","'
	    	 . mysqli_real_escape_string($conn,$item->vendorpartno).'"'
	    	 .')';
	}
	// print_r('INSERT INTO batch_items (batch_id, item_id, amount, units, cost, description, name, type, origin, vendor, vendorpartno) VALUES '.implode(',', $insert_sql));

	$insert_result = $conn->query('INSERT INTO batch_items (batch_id, item_id, amount, units, cost, description, name, type, origin, vendor, vendorpartno) VALUES '.implode(',', $insert_sql));
	// echo "insert result is : " , $insert_result,"\n";


$update_cases="";
$items="";
foreach ($request->recipe_items as $item){
	$item_id = mysqli_real_escape_string($conn,$item->item_id);
/*	$name = mysqli_real_escape_string($conn,$item->name);
	$description = mysqli_real_escape_string($conn,$item->description);
	$type = mysqli_real_escape_string($conn,$item->type);
	$unit = mysqli_real_escape_string($conn,$item->unit);
	$vendor = mysqli_real_escape_string($conn,$item->vendor);
	$vendorpartno = mysqli_real_escape_string($conn,$item->vendorpartno);
	$origin = mysqli_real_escape_string($conn,$item->origin);
	$cost = mysqli_real_escape_string($conn,$item->cost);
	
*/
	$amount = mysqli_real_escape_string($conn,$item->amount);
	$amount = (float)$amount * (float)$multiplier;


	// AND DECREMENT ITEM INVENTORY FROM items TABLE
	$stockqty = (float)mysqli_real_escape_string($conn,$item->stockqty) - $amount;
	$stockqty = ($stockqty < 0 ? 0 : $stockqty);

	//$sql="update recipe_item_x set amount = '$stockqty' where item_id = $item_id ";
	//$result = $conn->query($sql);
	//$result = "just testing update here";
	$update_cases = "$update_cases WHEN $item_id THEN $stockqty\n";
	$items= ($items=="" ? "$item_id" : $items . ",$item_id");

}

$update_sql = "UPDATE items set quantity = 
CASE item_id
$update_cases 
ELSE quantity
END
 WHERE item_id IN ($items)";
   
   echo "update_sql : " , $update_sql , "\n";
  // print_r ($items);

	$update_result = $conn->query($update_sql);
	echo "update result is : " , $update_result,"\n";


/* this is all debug crap
print('recipe_id : '.$recipe_id."\n");
print('multiplier : '.$multiplier."\n");
print('name : '.$recipe_name."\n");
print('notes : '.$recipe_notes."\n");
*/

/* more debug output
	
	print("item id: ".$item_id."\n");
	print ("item name: ".$name."\n");
	print ("amount : ".$amount." ".$unit."\n");
*/
?>