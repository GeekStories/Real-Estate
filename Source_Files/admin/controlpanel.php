<?php
    session_start();
    require "../config.php";

    //If we don't have admin login permissions, then move away
    if($_SESSION['role_id'] !== 3){
        header("Location: ../index.php");
        exit();
    }

    function GetIslandName($island_id){
        require "../config.php";

        $query = "SELECT name FROM island WHERE id=?";
        $island = $db->prepare($query);
        $island->bind_param("i", $island_id);
        $island->execute();

        $island_id_result = $island->get_result();
        $name = $island_id_result->fetch_assoc();

        return $name['name'];
    }
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <style>
        form{margin:auto;}
        li {align-items: center;}
        ul{list-style-type:none;}
    </style>
    
    </head>

    <body>
        <div class="container-fluid">
            <nav class='nav-bar navbar-expand-lg navbar-light bg-light'>
                <ul class='navbar-nav'>
                    <li class='nav-item'>
                        <a class='btn btn-light' role='button' href='support.php'>Check Support</a>
                    </li>
                    <li class='nav-item mr-5'>
                        <form action='../include/signout.inc.php' method='post'>
                            <button type=submit class='btn btn-light' name='logout-submit'>Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
            <div class="row">
                <div class="col text-center">
                    <h1>Welcome to the control panel, <?php echo $_SESSION['username'] ?>!</h1>
                    <h5 class='h5'>These are the currently listed properties</h5>
                    <table class='table table-striped table-hover'>
                        <thead class='thead-light'>
                            <tr>
                                <th scope="col">Street Number</th>
                                <th scope="col">Street Name</th>
                                <th scope="col">Neighbourhood</th>
                                <th scope="col">City</th>
                                <th scope="col">Island</th>
                                <th scope="col">Listed</th>
                                <th scope="col">Price</th>
                                <th scope="col">Description</th>
                                <th scope="col">Facilities</th>
                                <th scope="col">Options</th>
                            </tr>                       
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-0" colspan="10">
                                    <button type="button" class="btn btn-block p-0" style="height:50px;" data-toggle="modal" data-target="#AddProperty">
                                        <img width="40px;" src="../images/plus_icon.png" alt="Add New Property">
                                    </button>

                                    <div class="modal fade" id="AddProperty" tabindex="-1" role="dialog" aria-labelledby="AddPropertyLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="AddPropertyLabel">New Property Listing</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action='include/add-property.inc.php' method='post' enctype='multipart/form-data' style='border:1px solid black;border-radius:5%;padding:0.75rem;'>
                                                        <div class="form-group m-0">
                                                            <label>Address Details</label>
                                                        </div>
                                                        <div class='form-row'>
                                                            <div class='form-group col-md-3'>
                                                                <input class='form-control' type='number' name='st_number' placeholder='Street Number' required>
                                                            </div>

                                                            <div class='form-group col-md-9'>
                                                                <input class='form-control' type='text' name='st_name' placeholder='Street Name' required>
                                                            </div>
                                                        </div>

                                                        <div class='form-group'>
                                                            <input class='form-control' type='text' name='neighbourhood' placeholder='Neighbourhood' required>
                                                        </div>
                                                            
                                                        <div class="form-row">
                                                            <div class='form-group col-md-8'>
                                                                <input class='form-control' type='text' name='city' placeholder='City' required>
                                                            </div>
                                                            
                                                            <div class='form-group col-md-4'>
                                                                <select class='form-control' name='island'>
                                                                    <?php
                                                                        $grab_islands = $db->query("SELECT * FROM island");
                                                                        while($islands = $grab_islands->fetch_assoc()){
                                                                    ?>
                                                                        <option value="<?php echo $islands['id']?>"><?php echo $islands['name']; ?></option>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group m-0">
                                                            <label>Extra Details</label>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class='form-group col-md-9'>
                                                                <input class='form-control' type='number' name='price' placeholder='Price' step="0.01" required>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <select class='form-control listing_type' name="listing_type">
                                                                    <option value="BuyNow" selected>Buy Now</option>
                                                                    <option value="Auction">Auction</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-12 auction_close_time" style="display:none;">
                                                            <label style="font-size:small;margin:0rem;">Close Date / Time</label>
                                                            <input type="datetime-local" class="form-control" name="close_date">
                                                        </div>

                                                        <div class="form-group">
                                                            <textarea class='form-control' type='text' name='description' placeholder='Description' required></textarea>
                                                        </div>

                                                        <div class="form-row">
                                                            <div class="form-group col-md-3"><input class='form-control' type='number' name='garage' placeholder='# Of Garage Spaces' required></div>
                                                            <div class="form-group col-md-3"><input class='form-control' type='number' name='beds' placeholder='# Of Bedrooms' required></div>
                                                            <div class="form-group col-md-3"><input class='form-control' type='number' name='bath' placeholder='# Of Bathrooms' required></div>
                                                            <div class="form-group col-md-3">
                                                                <select class='form-control' name='parking_type'>
                                                                    <option value="none" hidden>Parking Type</option>
                                                                    <option value='Off Street'>Off Street</option>
                                                                    <option value='On Street'>On Street</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class='form-group text-center' style='border-top:1px solid black;'>
                                                            Select images to upload: (MAX 20)
                                                            <input class="form-control m-auto p-1 fileinput" type="file" name="upload[]" id="fileToUpload" accept=".png, .jpg, .jpeg" multiple>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="preview text-center"></div>
                                                        </div>

                                                        <div class="form-group m-0">
                                                            <button type='submit' class='btn btn-primary btn-block' name='sbt_property'>Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                $company_listings = $db->prepare("SELECT * FROM properties WHERE company_id=?");
                                $company_listings->bind_param("i", $_SESSION['company_id']);
                                $company_listings->execute();

                                $company_listings_results = $company_listings->get_result();
                                
                                while($row = $company_listings_results->fetch_assoc()){
                                    $facilities=json_decode($row['facilities']);
                                    $desc = substr($row['description'], 0, 150);

                                    $island = GetIslandName($row['island_id']);

                                    $obj = "<tr>
                                            <td scope='row'>".$row['st_number']."</td>
                                            <td>".$row['st_name']."</td>
                                            <td>".$row['neighbourhood']."</td>
                                            <td>".$row['city']."</td>
                                            <td>".$island."</td>
                                            <td>".$row['listing_created']."</td>
                                            <td>$".number_format($row['price'])."</td>
                                            <td class='w-25'>". $desc ."...</td>
                                            <td>
                                                <div class='text-center' id='facilities'>
                                                        <img src='../images/garage.png' alt='Garage'/>". $facilities->garage ."
                                                        <img src='../images/beds.png' alt='Bedrooms'/>". $facilities->bedrooms ."
                                                        <img src='../images/bath.png' alt='Bathrooms'/>". $facilities->bathrooms ."
                                                </div>

                                                Parking Type: ". $facilities->parking_type ."
                                            </td>";

                                    if($_SESSION['role_id'] === 3){
                                        $obj .= "<td>
                                                <form action='include/remove-property.inc.php' method='post'>
                                                    <button class='btn btn-light btn-sm' name='property' value='".$row['unique_id']."'>Remove</button>
                                                </form>
                                                <form action='edit-property.php' method='post'>
                                                    <button class='btn btn-light btn-sm' name='property' value='".$row['unique_id']."'>Edit</button>
                                                </form>
                                                <form action='gallery.php' method='get'>
                                                    <button class='btn btn-light btn-sm' name='property' value='".$row['unique_id']."'>Gallery</button>
                                                </form>
                                            </td>
                                            </tr>";
                                    } else {
                                        $obj .= "</tr>";
                                    }

                                    echo $obj;          
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <script>
            const listing_type = document.querySelector(".listing_type");
            const auction_dates = document.querySelector(".auction_close_time");

            const input = document.querySelector(".fileinput");
            const preview = document.querySelector(".preview");

            input.addEventListener('change', updateImageDisplay);
            listing_type.addEventListener('change', updateAuctionDisplay)

            function updateImageDisplay(){
                while(preview.firstChild) {
                    preview.removeChild(preview.firstChild);
                }

                if(this.files.length > 20){
                    alert('Max. 20 images');
                } else {
                    const list = document.createElement('ul');
                    preview.appendChild(list);

                    const curFiles = input.files;

                    var i = 0;
                    for(const file of curFiles) {
                        const listItem = document.createElement('li');
                        listItem.style.display = "inline-block";
                        
                        const image = document.createElement('img');
                        image.src = URL.createObjectURL(file);
                        image.width = 150;

                        listItem.appendChild(image);                        
                        list.appendChild(listItem);
                    }
                }
            }

            function updateAuctionDisplay(){
                if(listing_type.value == 'BuyNow'){
                    auction_dates.style.display ='none';
                } else {
                    auction_dates.style.display = 'block';
                }
            }

            // Prevent submission if limit is exceeded.
            $('form').submit(function(){
                if(this.files.length>20){
                    alert('Max. 20 images');
                    return false;
                } else if(this.files.length === 0){
                    alert("No files selected!");
                    return false;
                }
            });
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>