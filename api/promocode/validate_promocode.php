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
    !empty( $data->promocode ) &&
    !empty( $data->origin_address) &&
    !empty($data->destination_address)

){
 
    // set promocode property values
    $origin_address = $data->origin_address;
    $destination_address = $data->destination_address;
    $promocode->promocode=$data->promocode;

    // $origin_address = "legends rugby grounds";
    // $destination_address = "village mall bugolobi";
    // $promocode->promocode= 4;
    
 
// get the promo code details
    $promocode->read_one();

//    $promocode = $promocode->promocode;
   $amount = $promocode->amount;
   $radius = $promocode->radius; 
   $status = $promocode->promocode_status;
   $event = $promocode->event;


   $promocode->event_name = $event;
   //get the event details
   $promocode->read_event();

   $event_name = $promocode->event_name;
   $event_location = $promocode->event_location;
   $start_time = $promocode->start_time;
   $end_time = $promocode->end_time;
   $date = $promocode->date;
    
    // function validate_promocode($status,$radius,$amount,$event_location,$origin_address,$destination_address,$date,$start_time,$end_time){


        $todays_date =date('Y-m-d');
        $time =date('H:m');
   
        //origin address
        $origin = str_replace(' ','+',$origin_address);
        

        //destination address
        $destination = str_replace(' ','+',$destination_address);

        //event location
        
        $event_location_str = str_replace(' ','+',$event_location);
        
 
        //use google maps api to get distance between origin address and event location
        if (! $geocode_origin=file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$origin.'&destinations='.$event_location_str.'&key=AIzaSyDuLWYi-plvGR53V814ABRhQuNHqP8EZGM')) {
            $error = error_get_last();
            echo json_encode(array("message" => "HTTP request failed. Error was on origin : " . $error['message']));
           
      } else{
        $geocode_origin_output= json_decode($geocode_origin,true);
       $origin_distance = $geocode_origin_output['rows'][0]['elements'][0]['distance']['value'];
      }
        //use google maps api to get distance between destinatin address and event location
        if (!$geocode_destination=file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$destination.'&destinations='.$event_location_str.'&key=AIzaSyDuLWYi-plvGR53V814ABRhQuNHqP8EZGM')) {
            $error = error_get_last();
            echo json_encode(array("message" => "HTTP request failed. Error was  on destination : " . $error['message']));
           
      } else{
        $geocode_destination_output= json_decode($geocode_destination,true);
        $destination_distance = $geocode_destination_output['rows'][0]['elements'][0]['distance']['value'];
      }
            // set response code - 201 created
            http_response_code(201);


            if ($status ==='false') {
                echo json_encode(array("message" => "promocode is not active"));
            }

           
            elseif ($todays_date != $date ) {

                echo json_encode(array("message" => "The promo code expired or the event is not today"));
            }
             // elseif (($origin_distance > $radius) || ($destination_distance > $radius)) {
            elseif ($radius > $origin_distance || $radius > $destination_distance ) {

                //get latitude and longtitude cordinates for the destination
                $destination_latlong=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$destination.'&key=AIzaSyDuLWYi-plvGR53V814ABRhQuNHqP8EZGM');
                $destination_latlong_output= json_decode($destination_latlong,true);
                $destination_cordinates =  $destination_latlong_output['results'][0]['geometry']['location'];

                //get latitude and longitude for the origin address
                $origin_latlong=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$origin.'&key=AIzaSyDuLWYi-plvGR53V814ABRhQuNHqP8EZGM');
                $origin_latlong_output= json_decode($origin_latlong,true);
                $origin_cordinates =  $origin_latlong_output['results'][0]['geometry']['location'];

                $promocode_arr = array(
                    "promocode" => $promocode->promocode ,
                    "amount" => $amount,
                    "radius" => $radius,
                    "promocode_status" => $status,
                    "event" => $event,
                    "destination_cordinates" => $destination_cordinates,
                    "origin_cordinates" => $origin_cordinates
                );
                echo json_encode($promocode_arr);
                
                }
            else{
               
               

                echo json_encode(array("message" => "Please make sure you are going to the event you are not with in the radius"));
            }
    }

 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to validate promocode. Data is incomplete."));
}
?>