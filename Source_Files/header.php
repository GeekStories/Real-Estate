<?php
    session_start();

    function GetNumOfProperties(){
        require "config.php";

        $result=$db->query("SELECT * FROM properties");
        $rows=$result->num_rows;

       echo ($rows>10) ? "Over " . $rows-10 : $rows;
    }
?>

<html>
<head>
    <title>Real Estate</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="Stylesheets/header.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row" style="margin-top:15px">
            <div class="col-4">
            <!-- HEADER CONTENT: Nav, Wishlist, Login or Account/Logout option -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                    <div id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link btn btn-light" role="button" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" role="button" href="about.php">About Us</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle btn btn-light" role='button' href="#" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Our Homes
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <form class='m-0 p-0' action='homes.php' method='get'>
                                        <button type='submit' class="dropdown-item" name='island' value='1'>North Island</button>
                                        <button type='submit' class="dropdown-item" name='island' value='2'>South Island</button>
                                        <button type='submit' class="dropdown-item" name='island' value='0'>All New Zealand</button>
                                    </form>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="contact.php">Custom Build? Contact Us!</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-light" role="button" href="contact.php">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <br />
                <!-- HEADER CONTENT: Nav, Wishlist, Login or Account/Logout option -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
                    <div id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <?php
                                echo (isset($_SESSION['username'])) ? 
                                        "<li class='nav-item'><a class='nav-link btn btn-light' role='button' href='account.php'>".$_SESSION['username']."</a></li>" 
                                            :
                                        "<li class='nav-item'><a class='nav-link btn btn-light' role='button' href='signup.php'>Account</a></li>";
                            ?>
                            <li class='nav-item'>
                                <a class="nav-link btn btn-light" role="button" href="wishlist.php">Wishlist</a>
                            </li>
                            <?php
                                echo(!isset($_SESSION['username'])) ?
                                     "<li class='nav-item'><a class='nav-link btn btn-light' role='button' href='login.php'>Login</a></li>
                                      <li class='nav-item'><a class='nav-link btn btn-light' role='button' href='signup.php'>Create Account</a></li>"
                                    :
                                    "<li class='nav-item'>
                                        <form style='margin:0px' method='post' action='include/signout.inc.php'>
                                            <button class='nav-link btn btn-light' name='logout-submit'>Logout</button>
                                        </form>
                                     </li>";
                            ?>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="col-8" style=text-align:center>
                <h1>Real-Estate</h1>
                <p class='display-3'><?php GetNumOfProperties(); ?> listed houses!</p>
            </div>
        </div>
    </div>
    <hr class="w-75">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>