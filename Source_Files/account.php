<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php?error=notloggedin");
        exit();
    }
    session_write_close();

    include "config.php";
    include "header.php";

    $user_details = $db->prepare( "SELECT * FROM users WHERE username=?");
    $user_details->bind_param("s", $_SESSION['username']);
    $user_details->execute();

    $user_details_result = $user_details->get_result();

    $row = $user_details_result->fetch_assoc();
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col" align=center>
                    <h2 class='h2 display-2'>Account Settings</h2>
                    <form class='shadow p-3' method='post' action='include/update-account.inc.php'>
                        <div class="form-group">
                            <label>Email</label>
                            <input type='text' class='form-control w-25' name='email' value='<?php echo $row['email'] ?>' disabled>
                        </div>

                        <div class="form-group">
                            <h6>Change Password</h6>
                            <small class="form-text">
                                <?php 
                                    if(isset($_GET['result'])){
                                        switch($_GET['result']){
                                            case "collision":
                                                echo "New password can't be old password";
                                            break;
                                            case "incorrect":
                                                echo "Incorrect Password";
                                            break;
                                        }
                                    }
                                ?>
                            </small>
                            <input type='password' class='form-control w-25 m-1' name='oldpwd' placeholder='Old Password' required>
                            <input type='password' class='form-control w-25 m-1' name='newpwd' placeholder='New Password' required>
                            <input type='password' class='form-control w-25 m-1' name='pwd-repeat' placeholder='Retype New Password' required>
                            <small class="form-text"><?php if(isset($_GET['result'])){ if($_GET['result'] === "mismatch"){ echo "Doesn't match new password"; }} ?></small>
                        </div>

                        <button type='submit' class='btn btn-primary' name='submit-info'>Update</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>