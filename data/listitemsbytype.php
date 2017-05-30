
<?php
require_once('connect.php');

// expecting this: {'type':'string'}
    
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
@$type = mysqli_real_escape_string($conn,($request->type));
//print_r($request);
//print_r("select ProperCase(name) AS name from items WHERE ProperCase(type) = \'".$type."\';\n"  );
$items = $conn->query("select *,ProperCase(name) AS name from items WHERE type = '".$type."';" );

if ($conn->error){
    echo $conn->error ;
}else if($items->num_rows >0 ){
            
    $rows = array();
    
    while($row = $items->fetch_assoc()){
        $rows[] = $row;
    }
    print json_encode($rows);

}