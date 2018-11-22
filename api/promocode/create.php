<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate promocode object
include_once '../objects/promocode.php';
 
$database = new Database();

$db = $database->getConnection();
 
$promocode = new Promocode($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->amount) &&
    !empty($data->radius) &&
    !empty($data->promocode_status) &&
    !empty($data->event)
){
    $promocode->createRandompromocode();
    
    // set promocode property values
    $promocode->promocode ;
    $promocode->amount = $data->amount;
    // $promocode->expiry = $data->expiry;
    $promocode->radius = $data->radius;
    $promocode->promocode_status = $data->promocode_status;
    $promocode->created = date('Y-m-d H:i:s');
    $promocode->event = $data->event;

   
 
    // create the promocode
    if($promocode->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "promocode was created. $promocode->promocode"));
    }
 
    // if unable to create the promocode, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create promocode."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create promocode. Data is incomplete."));
}




?>