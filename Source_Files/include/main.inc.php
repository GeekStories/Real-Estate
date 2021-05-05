<?php
    function GetCompanyName($id){
        require "config.php";
        
        $query = "SELECT company_name FROM companies WHERE id=?";

        if(!$company_name = $db->prepare($query)){
            return false;
        }

        $company_name->bind_param("i", $id);
        $company_name->execute();
        
        $result = $company_name->get_result();
        $name = $result->fetch_assoc();

        return $name['company_name'];
    }

    function GetCard($unique_id){
        require "config.php";

        $ts = strtotime("now");
        $date = new DateTime("@$ts");
        date_sub($date, date_interval_create_from_date_string("7 days"));

        if(!$get_properties = $db->prepare("SELECT * FROM properties WHERE unique_id=?")){
            ReturnError("Failed to grab properties");
        }

        $get_properties->bind_param("s", $unique_id);
        $get_properties->execute();

        $get_properties_result = $get_properties->get_result();

        while($row=$get_properties_result->fetch_assoc()){
            if(!$company_name = GetCompanyName($row['company_id'])){
                echo "Failed to get property listings...";
                exit();
            }

            $path = "companies/". $company_name . "/" . $row['unique_id']."/";
            $files = array_slice(scandir($path, ), 2);

            $desc = substr($row['description'], 0, 200);

            $facilities = json_decode($row['facilities']);

            $island = $db->prepare("SELECT name FROM island WHERE id=?");

            $island->bind_param("i", intval($row['island_id']));
            $island->execute();

            $island_result = $island->get_result();
            $island_name = $island_result->fetch_assoc();

            switch ($row['listing_type']) {
                case 'BuyNow':
                    $price_text = "$" . number_format($row['price']);
                    break;
                case 'Auction':
                    $price_text = "Up For Auction";
                    break;
                default:
                    # code...
                    break;
            }

            $obj = "<div class='col mt-1'>
                        <div class='card shadow'>
                            <img class='card-img-top' src='".$path . $files[0]."' alt='Card image cap'>
                            <div class='card-body mb-0 pb-0'>
                                <h5 class='card-title'>". $row['st_number'] . ' ' . $row['st_name'] . ', ' . $row['neighbourhood'] . ', ' . $row['city'] ."</h5>
                                <h6 class='card-subtitle mb-2 text-muted'>". $island_name['name'] . "</h6>
                                <p class='card-text'>". $desc ." ...<a href='view.php?property=". $row['id'] ."'>Read More</a></p>

                                <div class='text-center facilitiesWrapper'>
                                    <h5 class='card-text'>". $price_text ."</h5>
                                    
                                    <ul class='facilities'>
                                        <li>
                                            <p class='card-text'><img src='images/garage.png' alt='Garage Spaces Available'/>". $facilities->garage ."</p>
                                        </li>
                                        <li>
                                            <p class='card-text'><img src='images/beds.png' alt='Bedrooms Available'/>". $facilities->bedrooms ."</p>
                                        </li>
                                        <li>
                                            <p class='card-text'><img src='images/bath.png' alt='Bathrooms Available'/>". $facilities->bathrooms ."</p>
                                        </li>
                                    </ul>

                                    <p class='card-text m-0'>Parking Type: ". $facilities->parking_type ."</p>
                                </div>";
            
            if(isset($_SESSION['_name'])){ //User is login, add the wishlist options
                $form = "";

                $c_wishlist = explode(":", $_SESSION['_wishlist']);
                $val = in_array(strval($row['unique_id']), $c_wishlist);

                if($val == true) //Check if the user already has the property in their wishlist
                    $form = "<button class='btn btn-primary' name='RemoveFromWishlist' value='". $row['unique_id'] ."'>Remove from Wishlist</button>";
                else
                    $form = "<button class='btn btn-primary' name='AddToWishlist' value='". $row['unique_id'] ."'>Add To Wishlist</button>";
                
                $obj .= "<div>
                            <a href='contact.php?id=".$row['_id']."' class='btn btn-primary mr-1' style='float:left;'>Enquire</a>
                            <form action='include/wishlist.inc.php' method='post'>
                                ".$form."
                            </form>
                        </div>
                        </div>
                        <div class='card-footer text-center p-0'>
                            <p class='text-muted'>Listed: ".$row['_listed']."</p>
                        </div>
                    </div>
                </div>";
            } else { //User is not logged in, close the open div's
                $obj .= "<a class='btn btn-primary w-100 m-1' href='contact.php?id=".$row['id']."' style='float:left;'>Enquire</a>
                            <div class='card-footer text-center p-0'>
                                <p class='text-muted'>Listed: ".$row['listing_created']."</p>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            
            echo $obj;
        }
    }
?>