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
$stmt = $promocode->read();
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
            "amount" => $amount,
            "promocode" => $promocode,
            "radius" => $radius,
            "promocode_status" => $promocode_status,
            "created" => $created,
            "event"  => $event
        );
 
        array_push($promocodes_arr["records"], $promocode_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show promoc$promocodes data in json format
    echo json_encode($promocodes_arr);
}
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no promocodes found
    echo json_encode(
        array("message" => "No promocodes found.")
    );
}
 



?>