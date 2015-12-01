<?php

require_once('connect.php');

if (!$conn){die ('no connection');}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
//print_r($request);

@$recipe_name = mysqli_real_escape_string($conn,($request->recipe_name));

$insertsql="INSERT INTO recipes (name) VALUES ( \"".$recipe_name."\")";

$conn->query($insertsql);

if ($conn->error) { printf ("error %s",$conn->error); }
//return recipt_id
echo $conn->insert_id;

// return recipe_id



?>