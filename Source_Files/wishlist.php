<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: signup.php?error=Not logged in!");
        exit();
    }
    session_write_close();

    include "header.php";
?>

<html>
    <head>
        <title>Real-Estate</title>
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
        <div class="row">
                <div class="col">
                <h1>Your saved properties</h1>
                    <div class="card-columns">
                        <?php 
                            include "include/main.inc.php";
                            include "config.php";

                            $get_user_wishlist = $db->prepare("SELECT wishlist FROM users WHERE username=?");
                            $get_user_wishlist->bind_param("s", $_SESSION['username']);
                            $get_user_wishlist->execute();

                            $get_user_wishlist_result = $get_user_wishlist->get_result();
                            $user_wishlist = $get_user_wishlist_result->fetch_assoc();

                            $c_wishlist = explode(":", $user_wishlist['wishlist']);

                            for($i=0;$i<count($c_wishlist);$i++){
                                GetCard($c_wishlist[$i]);
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>