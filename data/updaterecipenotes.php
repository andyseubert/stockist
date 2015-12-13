<?php
// update the notes for a recipe

require_once('connect.php');
//expects this:
//{'recipe_id':recipe_id,'notes':recipeitem.notes}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
print_r($request);


@$recipe_id = mysqli_real_escape_string($conn,($request->recipe_id));
@$value = mysqli_real_escape_string($conn,($request->notes));


    $sql="update recipes set notes = '$value' where recipe_id = $recipe_id ";
    $conn->query($sql);


?>