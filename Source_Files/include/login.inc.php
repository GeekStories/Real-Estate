<?php

if(isset($_POST['login-submit'])){ //We have reached this page correctly
    require "../config.php";

    $id = $_POST['mailuid'];
    $password = $_POST['password'];

    //Using a prepared statement for security
    $query = "SELECT * FROM users WHERE username=? OR email=?";

    //Prepare the statement
    if(!$stmt = $db->prepare($query)){
        header("Location: ../login.php?result=Problem loging in!&c=r");
        exit();
    }

    $stmt->bind_param("ss", $id, $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row=$result->fetch_assoc();
        $result->free(); //Clear the data from result, we only need it in row

        if(password_verify($password, $row['password']) === TRUE){
            session_start();

            $_SESSION['username'] = $row['username'];
            $_SESSION['role_id'] = $row['role_id'];

            $_SESSION['wishlist'] = array();
            $_SESSION['wishlist'] = explode($row['wishlist']);

            //Check through and see if properties in the wishlist still exist on the database (compare Unique ID's)
            $result = $db->query("SELECT id FROM properties");
            $row = $result->fetch_array();

            $c_wishlist = explode(":", $_SESSION['wishlist']);

            for($x=0;$x<count($c_wishlist);$x++){
                if(!in_array($c_wishlist[$x], $row))
                    $c_wishlist = array_diff($c_wishlist, $row[$x]);
            }

            header("Location: ../index.php?result=Successfully Logged In&c=g");
            exit();
        }else{
            header("Location: ../login.php?result=Incorrect Password&c=r");
            exit();
        }
    } else {
        header("Location: ../login.php?result=Username or Email not found!&c=r");
        exit();
    }

} else { //We shouldn't be here, take us back to safety
    header("Location: ../index.php");
    exit();
}