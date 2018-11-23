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
    !empty($data->event_name) &&
    // !empty($data->expiry) &&
    !empty($data->event_location) &&
    !empty($data->start_time) &&
    !empty($data->end_time) &&
    !empty($data->date)
){
 
    // set promocode property values
    $promocode->event_name = $data->event_name;
    // $promocode->expiry = $data->expiry;
    $promocode->event_location = $data->event_location;
    $promocode->start_time = $data->start_time;
    $promocode->end_time = $data->end_time;
    $promocode->date = $data->date;
 
    // create the promocode
    if($promocode->create_event()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "event was created."));
    }
 
    // if unable to create the promocode, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create event."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create event. Data is incomplete."));
}
?>