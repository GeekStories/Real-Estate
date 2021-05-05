<?php
    include "../config.php";

    session_start();
    if(isset($_SESSION['id'])){
        //If we reach this page and we are logged in, log out and go home. We shouldn't be here.
        session_unset();
        session_destroy();
        header("Location:../index.php");
        exit();
    }
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
    </head>

    <body>
        <div class="container-fluid">
            <div class="row" style='padding-top: 15px;'>
                <div class="col" align=center>
                    <h1>Login</h1>
                    <h4><?php if(!empty($_GET['error'])) echo $_GET['error'] ?></h4>
                    <form method=post action="include/admin-login.inc.php">
                        <div class="form-group">
                            <input type="text" class="form-control w-25" name="mailuid" placeholder="E-mail Or Username" value='<?php if(!empty($_GET['mail'])) echo $_GET['mail'] ?>' required>
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control w-25" name="pwd" placeholder="Password" required>
                        </div>

                        <button type="submit" class="btn btn-primary" name="login-submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>