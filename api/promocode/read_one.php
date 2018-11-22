<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/promocode.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare promocode object
$promocode = new Promocode($db);
 
// set ID property of record to read
$promocode->promocode = isset($_GET['promocode']) ? $_GET['promocode'] : die();
 
// read the details of promocode to be edited
$promocode->read_one();
 
if($promocode->amount!=null){
    // create array
    $promocode_arr = array(
        "promocode" =>  $promocode->promocode,
        "amount" => $promocode->amount,
        "radius" => $promocode->radius,
        "promocode_status" => $promocode->promocode_status,
        "event" => $promocode->event,
        "created" => $promocode->created
 
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($promocode_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user promocode does not exist
    echo json_encode(array("message" => "promocode does not exist."));
}
?>