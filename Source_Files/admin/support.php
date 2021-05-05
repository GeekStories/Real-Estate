<?php 
session_start();

if(isset($_SESSION['_name'])){
    require "../config.php";

    //If we don't have admin login permissions, then move away
    if($_SESSION['role_id'] !== 3){
        header("Location: controlpanel.php");
        exit();
    }
}

function GetSupportQueries(){
    include "../config.php";

    $result = $db->query("SELECT * FROM queries");

    if($result->num_rows == 0){
        echo "<li><h1 class='dispay-1' align='center'>No support queries here!</h1></li>";
        return;
    }

    while($row = $result->fetch_assoc()){
        $obje = "";

        $obje = "<li class='list-item shadow-lg p-2 m-5'>
                    <h4 class='h4'>".$row['property_id']."</h4>
                    <h6 class='h6 lead'>Asked by: ".$row['name'].", ".$row['email']."</h6>
                    <p style='border: 1px solid black;border-radius:5px;'>".$row['message']."</p>
                        <div class='form-group'>
                            <form action='#' method='post'>
                                <input class='form-control mb-1' type='text' name='reply' placeholder='Type your response here'/>
                            </form>
                        </div>

                        <div class='form-group'>
                            <form action='include/modify-support.inc.php' method='post'>
                                <!-- <button  type='submit' class='btn btn-primary' name='submit-respond' value='".$row['id']."' disabled>Respond</button> -->
                                <button type='submit' class='btn btn-primary' name='submit-delete' value='".$row['id']."'>Delete Request</button>
                            </form>
                        </div>
                </li>";

        echo $obje;
    }
}
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
        <style>
            li{
                list-style:none;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="col" align=center>
                    <a class='btn btn-light' role='button' href='controlpanel.php'>Back</a>
                </div>
            </div>

            <div class="row-fluid">
                <div class="col">
                    <div class="ul">
                        <?php GetSupportQueries() ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>