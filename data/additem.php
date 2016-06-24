<?php
require_once('connect.php');
//create an item and return its item_id

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//print_r($request);
/*
[name] => test
[quantity] => 12
[vendor] => testVendor
[vendorpartno] => 123
[cost] => .5
[unit] => ea
[size] => 2.21
[size_unit] => oz
[type] => type
[origin] => origin
[description] => descr
*/
@$name = mysqli_real_escape_string($conn,($request->name));
@$quantity = mysqli_real_escape_string($conn,($request->quantity));
@$vendor = mysqli_real_escape_string($conn,($request->vendor));
@$vendorpartno = mysqli_real_escape_string($conn,($request->vendorpartno));
@$cost = mysqli_real_escape_string($conn,($request->cost));
@$unit = mysqli_real_escape_string($conn,($request->unit));
@$size = mysqli_real_escape_string($conn,($request->size));
@$size_unit = mysqli_real_escape_string($conn,($request->size_unit));
@$type = mysqli_real_escape_string($conn,($request->type));
@$origin = mysqli_real_escape_string($conn,($request->origin));
@$description = mysqli_real_escape_string($conn,($request->description));

$sql = "INSERT INTO items
( name,quantity,vendor,vendorpartno,cost,unit,size,size_unit,type,origin,description )
VALUES ( '$name','$quantity','$vendor','$vendorpartno','$cost','$unit','$size','$size_unit','$type','$origin','$description' ) ";

$newItem = $conn->query($sql);
   if ($conn->error){ 
	printf($conn->error); 
}else{
   // return the item_id
   // in json
   //$new_item[]=array('item_id'=>$conn->insert_id);
   
	//print json_encode($new_item);
	// return just the new item ID
	echo $conn->insert_id;
}

?>