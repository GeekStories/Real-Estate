<?php
session_start();
require_once("../../vendor/autoload.php");

function ReturnError($errorMessage){
    header("Location: ../controlpanel.php?error=" . $errorMessage); exit();
}

function CheckPropertyExists($unique_id ){
    require "../../config.php";

    $query = "SELECT id FROM properties WHERE unique_id=?";

    if(!$check_property = $db->prepare($query))
        ReturnError("Failed to check if property Exists");

    $check_property->bind_param("s", $unique_id );
    $check_property->execute();

    $result = $check_property->get_result();        

    return $result->num_rows === 0;
}

function ValidateInput(){
    if(!is_int(intval($_POST['st_number'])))
        ReturnError("Street Number Must Only Contain 0-9");
    
    if(!is_string($_POST['st_name']))
        ReturnError("Street Name Must Only Contain a-Z");

    if(!is_string($_POST['city']))
        ReturnError("City Must Only Contain a-Z");

    if(!is_string($_POST['neighbourhood']))
        ReturnError("Neighbourhood Must Only Contain a-Z");

    if(!is_float(floatval($_POST['price'])))
        ReturnError("Price Must Only Contain 0-9, .");

    return true;
}

if(isset($_POST['sbt_property'])){
    require "../../config.php";

    if(ValidateInput()){
        $st_number = $_POST['st_number'];
        $st_name = ucfirst(strtolower($_POST['st_name']));

        $neighbourhood = ucfirst(strtolower($_POST['neighbourhood']));
        $city = ucfirst(strtolower($_POST['city']));

        $unique_id = hash("ripemd160", ($st_number . $st_name . $neighbourhood . $city));

        if(CheckPropertyExists($unique_id)){
            $price = floatval($_POST['price']);
            $island_id=intval($_POST['island']);

            $listing_type = $_POST['listing_type'];

            switch($listing_type){
                case "Auction":
                    $listing_close_date_input = $_POST['close_date'];
                    $listing_close_date = strtotime("Y-m-d H:i", $listing_close_date_input);
                    break;
                default:
                    $listing_close_date = date("Y-m-d H:i");
                    break;
            }

            $description = ucfirst(htmlspecialchars($_POST['description'])); //Description
                
            $temp = array("garage" => $_POST['garage'], "bedrooms" => $_POST['beds'], "bathrooms" => $_POST['bath'], "parking_type" => $_POST['parking_type']);
            $facilities=json_encode($temp);
            
            $listing_created = date("Y-m-d H:i:s");

            $company_name = $_SESSION['company_name'];
            $company_id = $_SESSION['company_id'];
            $agent_id = $_SESSION['user_id'];

            $files = array_filter($_FILES['upload']['name']); //something like that to be used before processing files.
            // Count # of uploaded files in array
            $total_files_uploaded = count($_FILES['upload']['name']);
    
            if($total_files_uploaded>0){                
                $path="../../companies/". $company_name ."/". $unique_id . "/";

                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
        
                // Loop through each file
                $x=0;
                foreach( $_FILES['upload']['tmp_name'] as $file ){
                    $new_name_image = 'img_' . $x . ".jpg";
                    $quality = 60;
                    $pngQuality = 9;
            
                    $image_compress = new Eihror\Compress\Compress($file, $new_name_image, $quality, $pngQuality, $path);
                    $image_compress->compress_image();
                    $x++;
                }
            } else {
                ReturnError("No Images!");
            }

            $query = "INSERT INTO properties (`st_name`, `st_number`, `neighbourhood`, `city`, `island_id`, `listing_type`, 
                                              `description`, `price`, `facilities`, `agent_id`, `company_id`, `listing_created`,
                                              `listing_close_date`, `unique_id`) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if(!$stmt = $db->prepare($query))
                ReturnError("Failed to prepare query");

            $stmt->bind_param("sississdsiisss", $st_name, $st_number, $neighbourhood, $city, $island_id, 
                                                  $listing_type, $description, $price, 
                                                  $facilities, $agent_id, $company_id, $listing_created, 
                                                  $listing_close_date, 
                                                  $unique_id);                                      

            $stmt->execute();
            header("Location: ../controlpanel.php?result=Successfully Added Property!"); exit();
        } else {
            ReturnError("Property Exists");
        }
    }  else {
        ReturnError("Invalid Input");
    }
}else{
    header("Location: ../../index.php"); exit();
}

