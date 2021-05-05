<?php
    include "header.php";
    include "include/main.inc.php";
    require "config.php";
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel='stylesheet' href='stylesheets/style.css'>
        <script src="libs/jquery-3.5.1.min.js"></script>
        <script>
            $(document).ready(function() {

                var priceOptions = [
                    "<option value='1'>$100k</option>",
                    "<option value='2'>$200k</option>",
                    "<option value='3'>$300k</option>",
                    "<option value='4'>$400k</option>",
                    "<option value='5'>$500k</option>",
                    "<option value='6'>$600k</option>",
                    "<option value='7'>$700k</option>",
                    "<option value='8'>$800k</option>",
                    "<option value='9'>$900k</option>",
                    "<option value='100'>$1m+</option>"
                ];

                $("#type").change(function() {
                    if($(this).val() == 1){
                        $("#cities").html(options[0]);
                    } else if($(this).val() == 2){
                        $("#cities").html(options[1]);
                    } else {
                        $("#cities").html(options[0] + options[1]);
                    }                
                });

                $("#min").change(function() {
                    $("#max").html("<option value='0' selected disabled hidden>Price Max.</option>");

                    for(var x=$(this).val();x<10;x++){
                        $("#max").append(priceOptions[x]);
                    }

                    if($(this).attr("value") != 0){
                        $("#max").removeAttr("hidden");
                    }
                });
            });
        </script>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h1 class='text-center'>Search for a property!</h1> 
                </div>
            </div>
            <div class="row-fluid">
                <form class="shadow" action='homes.php' method='get'>
                    <div class="form-row">
                        <div class="form-group w-100 p-2">
                            <select class='form-control m-1' name='island' id='type'>
                                <option value='0' selected>All New Zealand</option>
                                <option value='2'>North</option>
                                <option value='1'>South</option>
                            </select>

                            <select class='form-control m-1' name='priceMin' id='min'>
                                <option selected disabled hidden>Price Min.</option>
                                <option value='1'>$100k</option>
                                <option value='2'>$200k</option>
                                <option value='3'>$300k</option>
                                <option value='4'>$400k</option>
                                <option value='5'>$500k</option>
                                <option value='6'>$600k</option>
                                <option value='7'>$700k</option>
                                <option value='8'>$800k</option>
                                <option value='9'>$900k</option>
                                <option value='100'>$1m+</option>
                            </select>

                            <select class='form-control m-1 ' name='priceMax' id='max' hidden>
                                <option selected disabled hidden>Price Max.</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-center">
                        <button type='submit' class='btn btn-info btn-block w-75'>Search!</button>  
                    </div>
                </form>
            </div>

            <div class="row-fluid">
                <div class="col">
                    <h1 class="display-3 text-center" style='color:black;'>Recently Added</h1>
                    <div class="card-columns">
                        <?php         
                            $ts = strtotime("now");
                            $date = new DateTime("@$ts");
                            date_sub($date, date_interval_create_from_date_string("7 days"));
                    
                            $result = $db->query("SELECT unique_id FROM properties WHERE listing_created>'". date_format($date, 'Y-m-d') ."' ORDER BY RAND() LIMIT 6");

                            while($row=$result->fetch_assoc()){
                                GetCard($row['unique_id']);
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>