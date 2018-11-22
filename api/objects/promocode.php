<?php
class Promocode{
 
    // database connection and table amount
    private $conn;
    private $table_name = "promo_codes";

    private $event_table = "event";
 
    // object properties
    public $promocode;
    public $amount;
    public $radius;
    public $promocode_status;
    public $created;
    public $origin_address;
    public $destination_address;
 
    // constructor with $db as database connection
    public function __construct($db=null){
        $this->conn = $db;
    }


    // read proomcodes
function read(){
 
    // select all query
    $query = "SELECT * FROM `promo_codes`";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}


//create promocode string
function createRandompromocode() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $promocode_string = array(); // declare $promocode as an array
    $alphaLength = strlen($alphabet) - 1; // length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $promocode_string[] = $alphabet[$n];
    }
    $promocode_str = implode($promocode_string);//turn the array into a string
    
      $this->promocode =$promocode_str;
}

// create promocode
function create(){
 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                amount=:amount,  radius=:radius, promocode_status=:promocode_status, created=:created, event=:event, promocode=:promocode";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->amount=htmlspecialchars(strip_tags($this->amount));
    // $this->expiry=htmlspecialchars(strip_tags($this->expiry));
    $this->radius=htmlspecialchars(strip_tags($this->radius));
    $this->promocode_status=htmlspecialchars(strip_tags($this->promocode_status));
    $this->created=htmlspecialchars(strip_tags($this->created));
    $this->event=htmlspecialchars(strip_tags($this->event));
    $this->promocode=htmlspecialchars(strip_tags($this->promocode));
 
    // bind values
    $stmt->bindParam(":amount", $this->amount);
    // $stmt->bindParam(":expiry", $this->expiry);
    $stmt->bindParam(":radius", $this->radius);
    $stmt->bindParam(":promocode_status", $this->promocode_status);
    $stmt->bindParam(":created", $this->created);
    $stmt->bindParam(":event", $this->event);
    $stmt->bindParam(":promocode", $this->promocode);
 
    // execute query
    if($stmt->execute()){

        return true;
    }
 
    return false;
     
}


// used when filling up the update promocode form
function read_one(){
 
    // query to read single record
    $query = "SELECT  * FROM " . $this->table_name . "  WHERE promocode = ? LIMIT 0,1";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind promocode of promocode to be updated
    $stmt->bindParam(1, $this->promocode);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->amount = $row['amount'];
    $this->radius = $row['radius'];
    $this->promocode_status = $row['promocode_status'];
    $this->event = $row['event'];
    $this->created = $row['created'];
}


// configure_radius the promocode
function configure_radius(){
 
    // configure_radius query
    $query = "UPDATE " . $this->table_name . " SET    radius = :radius WHERE promocode = :promocode";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->radius=htmlspecialchars(strip_tags($this->radius));
    $this->promocode=htmlspecialchars(strip_tags($this->promocode));
 
    // bind new values
   
    $stmt->bindParam(':radius', $this->radius);
    $stmt->bindParam(':promocode', $this->promocode);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

// create create event
function create_event(){
 
    // query to insert record
    $query = "INSERT INTO
                " . $this->event_table . "
            SET
            event_name=:event_name,  event_location=:event_location, start_time=:start_time, end_time=:end_time, date=:date";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->event_name=htmlspecialchars(strip_tags($this->event_name));
    $this->event_location=htmlspecialchars(strip_tags($this->event_location));
    $this->start_time=htmlspecialchars(strip_tags($this->start_time));
    $this->end_time=htmlspecialchars(strip_tags($this->end_time));
    $this->date=htmlspecialchars(strip_tags($this->date));
 
    // bind values
    $stmt->bindParam(":event_name", $this->event_name);
    $stmt->bindParam(":event_location", $this->event_location);
    $stmt->bindParam(":start_time", $this->start_time);
    $stmt->bindParam(":end_time", $this->end_time);
    $stmt->bindParam(":date", $this->date);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}

// / used when filling up the update promocode form
function read_event(){
 
    // query to read single record of the event table
    $query = "SELECT  * FROM " . $this->event_table . "  WHERE event_name = ? LIMIT 0,1";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind name of event to query
    $stmt->bindParam(1, $this->event_name);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->event_name = $row['event_name'];
    $this->event_location = $row['event_location'];
    $this->start_time = $row['start_time'];
    $this->end_time = $row['end_time'];
    $this->date = $row['date'];
}



//deactivate promocodes
function deactivate(){
 
    // deactivate query
    $query = "UPDATE " . $this->table_name . " SET    promocode_status = :promocode_status WHERE promocode = :promocode";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    
    $this->radius=htmlspecialchars(strip_tags($this->radius));
    $this->promocode=htmlspecialchars(strip_tags($this->promocode));
 
    // bind new values
   $status ="false";
    $stmt->bindParam(':promocode_status', $status);
    $stmt->bindParam(':promocode', $this->promocode);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}


//read active promocodes
function read_active_promocodes(){
 
    // select all query
    $query = "SELECT * FROM `promo_codes` WHERE promocode_status='TRUE'";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

//read all events
    // read proomcodes
    function read_events(){
 
        // select all query
        $query = "SELECT * FROM `event`";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

}