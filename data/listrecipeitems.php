

<?php
require_once('connect.php');

// expecting this: {'recipe_id':'##'}
    
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
@$recipe_id = mysqli_real_escape_string($conn,($request->recipe_id));
//print_r($request);

$recipeitems = $conn->query("select * from items, recipe_item_x where recipe_item_x.recipe_id = ".$recipe_id." AND items.item_id = recipe_item_x.item_id");
if ($conn->error){
    echo $conn->error ;
}else if($recipeitems->num_rows >0 ){
            
    $rows = array();
    
    while($row = $recipeitems->fetch_assoc()){
        $rows[] = $row;
    }
    print json_encode($rows);

}