<?php
// delete item

require_once('connect.php');
//{'item_id':item_id}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//print_r($request);

@$item_id = mysqli_real_escape_string($conn,($request->item_id));


    $sql="DELETE FROM items WHERE item_id = $item_id ";
    $conn->query($sql);
   if ($conn->error){ printf($conn->error); }


?>