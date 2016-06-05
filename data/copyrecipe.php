<?php
// receive a recipe id  and new name and copy it to a new recipe with the new name
// add to the notes "copied from recipe id on date"


include('connect.php');

require_once('connect.php');
//expects this:
//{'recipe_id':$scope.recipe.recipe_id , 'old_recipe_name':$scope.recipe.name,'new_recipe_name':result,'recipe_notes':$scope.recipe.notes}
// only return json encoded srtring

	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	//print_r($request);
	@$old_recipe_name = mysqli_real_escape_string($conn,($request->old_recipe_name));
	@$new_recipe_name = mysqli_real_escape_string($conn,($request->new_recipe_name));
	@$from_recipe_id = mysqli_real_escape_string($conn,($request->recipe_id));
	@$recipe_notes = "coped from \'$old_recipe_name\' with recipe_id : $from_recipe_id\n".date("Y-m-d H:i:s")." \n" . mysqli_real_escape_string($conn,($request->recipe_notes));
	//print_r ($request);

// add recipe here (from addrecipe.php)
$insertsql="INSERT INTO recipes (name,notes) VALUES ( '".$new_recipe_name."' , '".$recipe_notes."')";
$conn->query($insertsql);
//get new recipe_id
$new_recipe_id =  $conn->insert_id;

if ($conn->error) { // fail
	printf ("error %s",$conn->error); 
}else{
	// copy recipe items with new recipe_id
	// insert into recipe_item_x (recipe_id, item_id, amount)
    // SELECT 85,item_id,amount FROM `recipe_item_x` where recipe_id = 34
	$insertsql="insert into recipe_item_x (recipe_id, item_id, amount)
    			SELECT ".$new_recipe_id." ,item_id,amount FROM recipe_item_x where recipe_id = ".$from_recipe_id;
	$conn->query($insertsql);
	if ($conn->error) { // fail
		printf ("error %s",$conn->error); 
	}else{
	print "new recipe name : " . $new_recipe_name."\n";
	//echo "old recipe_id : " . $from_recipe_id."\n";
	echo "new recipe_id : " . $new_recipe_id."\n";
	//echo "notes : " . $recipe_notes."\n";

	}
}

?>