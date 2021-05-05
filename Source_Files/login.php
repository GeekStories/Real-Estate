<?php
    include "config.php";
    include "header.php";

    $color = 'red';

    if(!empty($_GET['c']) !== FALSE){
        switch($_GET['c']){
            case "r":
                $color = 'red';
                break;
            case "g":
                $color = 'green';
                break;
        }
    }
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
    </head>

    <body>
        <div class="container">
            <div class="row-auto">
                <div class="col-5 text-center m-auto">
                    <h1 class="m-auto" style="font-weight:100;">Login</h1> <br />
                    <h4 class="error_message m-auto" style="color:<?php echo $color ?>;font-size:1em;font-weight:200;">
                        <?php 
                            if(!empty($_GET['result'])){
                                echo $_GET['result'];
                            } 
                        ?>
                    </h4> <br />

                    <form class='m-auto' method="post" action="include/login.inc.php">
                        <div class="form-group"><input type="text" class="form-control" name="mailuid" placeholder="E-mail Or Username" required></div>
                        <div class="form-group"><input type="password" class="form-control" name="password" placeholder="Password" required></div>
                        <button type="submit" class="btn btn-primary w-50" name="login-submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>