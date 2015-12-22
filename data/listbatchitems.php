

<?php
require_once('connect.php');

// expecting this: {'batch_id':'##'}
    
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
@$batch_id = mysqli_real_escape_string($conn,($request->batch_id));
//print_r($request);

$batchitems = $conn->query("select * from batch_items where batch_id = ".$batch_id);
if ($conn->error){
    echo $conn->error ;
}else if($batchitems->num_rows >0 ){
            
    $rows = array();
    
    while($row = $batchitems->fetch_assoc()){
        $rows[] = $row;
    }
    print json_encode($rows);

}