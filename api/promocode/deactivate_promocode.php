<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/promocode.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare promocode object
$promocode = new Promocode($db);
 
// get promocode  to be edited
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(
    !empty($data->promocode)  
){
// set promocode property  to be deactivated
$promocode->promocode = $data->promocode;

// deactivate the promocode
if($promocode->deactivate()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "promocode successfully deactivated."));
}
 
// if unable to deactivate the promocode, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to deactivate promocode."));
    
   }
}
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create promocode. Data is incomplete."));
}


?>