<?php
    include "header.php";

    function FillHouses(){
        require "config.php";
        require "include/main.inc.php";

        (isset($_GET['priceMin'])) ? $priceMin = $_GET['priceMin'] : $priceMin = 1;
        (isset($_GET['priceMax'])) ? $priceMax = $_GET['priceMax'] : $priceMax = 100;
        (isset($_GET['island'])) ? $island = $_GET['island'] : $island = 0;

        if($_GET['island'] !== "0")
            $query = "SELECT * FROM properties WHERE price>=".($priceMin*100000)." AND price<=".($priceMax * 100000)." AND island_id=".$island.";";
        else
            $query = "SELECT * FROM properties WHERE price>=".($priceMin*100000)." AND price<=".($priceMax * 100000).";";

        $result = $db->query($query);

        if($result->num_rows === 0){
            echo "<h1>No houses found</h1>";
            return;
        }

        while($row = $result->fetch_assoc()){
            if(isset($_GET['cities'])){
                if(in_array($row['_city'], $_GET['cities']))
                    echo GetCard($row['unique_id']);
            } else {
                echo GetCard($row['unique_id']);
            }
        }
    }
    
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
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
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col">
                    <h1>Results</h1>
                    <div class="card-columns">
                        <?php FillHouses() ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>