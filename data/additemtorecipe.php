<?php
include('connect.php');

require_once('connect.php');
//expects this:
//{'item_id':additem_id,'recipe_id':recipe_id}
// only return json encoded srtring

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//print_r($request);

@$item_id = mysqli_real_escape_string($conn,($request->item_id));
@$recipe_id = mysqli_real_escape_string($conn,($request->recipe_id));

// check if item already exists for recipe
$checksql = "SELECT * from recipe_item_x WHERE item_id='".$item_id."' and recipe_id='".$recipe_id."'  LIMIT 1";
$exists = $conn->query($checksql);
if ( $exists->num_rows > 0 ) {
    // already exists
    print_r("exists");
	// $rows = array(); 	
	// while($row = $exists->fetch_assoc()){
	// 	$rows[] = $row;
	// }
	// print json_encode($rows);
	// seems to persist after deletion...
}else{
    // retrieve the MAX position value
    $sql="SELECT count(*) FROM recipe_item_x WHERE recipe_id = ".$recipe_id;
    $conn->query($sql);
    if ($conn->error){
        print_r("error: " . $conn->error);
    }else{
        $result=$conn->query($sql);
        //print_r("position MAX result: ".$result);
        // if not null, make it 1, if has a value then add 1 to it
        $position = $result->fetch_row(); 
        //print_r("position returned: ".$position[0]."\n");  
        if ( $position[0] > 0 ) { $position = $position[0] +1; }else{ $position = 1; }
        //print_r("new position: ".$position."\n");
    }

    $addsql="INSERT INTO recipe_item_x (position,item_id,recipe_id) VALUES ('".$position."','".$item_id."','".$recipe_id."')";
    $conn->query($addsql);
    if ($conn->error){
        print_r($conn->error);
    }else{
        // now get the item by id and return it
        //print_r("added item\n");
        $exists = $conn->query($checksql);
        if ( $exists->num_rows > 0 ) {                
            $rows = array();             
            while($row = $exists->fetch_assoc()){
                $rows[] = $row;
            }
            print json_encode($rows);
        }
    }
}
?>