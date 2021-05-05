<?php
    if(!isset($_GET['property'])){
        header("Location: index.php?error=noproperty");
        exit();
    } else {
        include 'header.php';
    }

    function GenerateCard(){
        require "config.php";

        $id=htmlspecialchars($_GET['property']);

        $result = $mysqli->query("SELECT * FROM properties WHERE _id=". $id ."");
        $row = $result->fetch_assoc();

        $path = "properties/". $row['unique_id']."/";
        $files = array_slice(scandir($path, ), 2);

        $facilities = explode(":", $row['_facilities']);

        $items = "";

        for($i=0;$i<count($files);$i++){
            $active='';

            if($i==0){$active='active';}

            $items .= "<div class='carousel-item ". $active ."'>
                        <img class='d-block w-100' src='". $path . $files[$i] ."' alt='Image #".$i."' />
                        </div> ";
        }

        $islandResult = $mysqli->query("SELECT _name FROM islands WHERE _id=".$row['island_id']."");
        $islandRow = $islandResult->fetch_assoc();

        $obje = "<div class='card shadow'>
                    <div id='carouselExampleControls' class='carousel slide' data-ride='carousel'>
                        <div class='carousel-inner'>
                            ".$items."
                        </div>
                        <a class='carousel-control-prev' href='#carouselExampleControls' role='button' data-slide='prev'>
                            <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                            <span class='sr-only'>Previous</span>
                        </a>
                        <a class='carousel-control-next' href='#carouselExampleControls' role='button' data-slide='next'>
                            <span class='carousel-control-next-icon' aria-hidden='true'></span>
                            <span class='sr-only'>Next</span>
                        </a>
                    </div>
                    <div class='card-body mb-0 pb-0'>
                        <h5 class='card-title'>". $row['st_number'] . ' ' . $row['st_name'] . ', ' . $row['_area'] . ', ' . $row['_city'] ."</h5>
                        <h6 class='card-subtitle mb-2 text-muted'>". $islandRow['_name'] . " Island</h6>
                        <p class='card-text'>". $row['_description'] ."</p>
                        <h5 class='card-text'>$". number_format($row['_price']) ."</h5>
                        <div>
                            <ul id='facilities'>
                                <li>
                                    <p class='card-text'><img src='images/garage.png' alt='Garage'/>". $facilities[0] ."</p>
                                </li>
                                <li>
                                    <p class='card-text'><img src='images/beds.png' alt='Garage'/>". $facilities[1] ."</p>
                                </li>
                                <li>
                                    <p class='card-text'><img src='images/bath.png' alt='Garage'/>". $facilities[2] ."</p>
                                </li>
                                <li>
                                    <p class='card-text'><img src='images/toilet.png' alt='Garage'/>". $facilities[3] ."</p>
                                </li>
                            </ul>
                        </div>";
    
        //User is login, add the wishlist options
        if(isset($_SESSION['_name'])){
            $form = "";

            $c_wishlist = explode(":", $_SESSION['_wishlist']);
            $val = in_array(strval($row['unique_id']), $c_wishlist);

            if($val == true) //Check if the user already has the property in their wishlist
                $form = "<button class='btn btn-primary' name='RemoveFromWishlist' value='". $row['unique_id'] ."'>Remove from Wishlist</button>";
            else
                $form = "<button class='btn btn-primary' name='AddToWishlist' value='". $row['unique_id'] ."'>Add To Wishlist</button>";

            $obje .= "<div>
                        <form class='w-100' action='include/wishlist.inc.php' method='post'>
                            <a href='contact.php?id=".$row['_id']."' class='btn btn-primary mr-1 w-50' style='float:left;'>Enquire</a>

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
            $obje .= "<a href='contact.php?id=".$row['_id']."' class='btn btn-primary w-100' style='float:left;'>Enquire</a>
                        <div class='card-footer text-center p-0'>
                            <p class='text-muted'>Listed: ".$row['_listed']."</p>
                        </div>
                    </div>
                </div>
            </div>";
        }
    
        echo $obje;
    }
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
        <link rel=stylesheet href=view_style.css>

        <style>
            #facilities {
                list-style-type: none;
                padding: 0;
                overflow: hidden;
                background-color: #bdbdbd;
            }

            #facilities ul,li {
                float:left;
            }
        </style>
    </head>

    <body>
        <div class="container" style='background-color:white; border-radius:15px;'>
            <div class="row">
                <div class="col">
                    <?php GenerateCard(); ?>
                </div>
            </div>
        </div>
    </body>
</html>