<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here
// include database and object files
include_once '../config/database.php';
include_once '../objects/promocode.php';
 
// instantiate database and promocode object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$promocode = new Promocode($db);
 
///read promo codes
// query promocodes
$stmt = $promocode->read_events();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // promocodes array
    $promocodes_arr=array();
    $promocodes_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
   
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        
        extract($row);
 
        $promocode_item=array(
            "event_name" => $event_name,
            "event_location" => $event_location,
            "date" => $date,
            "start_time" => $start_time,
            "end_time" => $end_time
        );
 
        array_push($promocodes_arr["records"], $promocode_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show  data in json format
    echo json_encode($promocodes_arr);
}
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no events found
    echo json_encode(
        array("message" => "No events found.")
    );
}
 



?>