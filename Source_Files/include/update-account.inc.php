<?php

if(isset($_POST['submit-info'])){ //We have reached this page correctly
    require "../config.php";
    session_start();

    $Oldpwd = htmlspecialchars($_POST['oldpwd']);
    $Newpwd = htmlspecialchars($_POST['newpwd']);
    $Repwd = htmlspecialchars($_POST['pwd-repeat']);
    $userId = htmlspecialchars($_SESSION['_id']);
    
    if($Newpwd !== $Repwd){
        header("Location: ../account.php?result=mismatch");
        exit();
    }

    if($Newpwd === $Oldpwd){
        header("Location: ../account.php?result=collision");
        exit();
    }

    $result = $mysqli->query("SELECT _password FROM users WHERE _id='".$userId."'");

    if($row = $result->fetch_assoc()){
        if(password_verify($Oldpwd, $row['_password'])){
            //Update the password
            $hashedPwd = password_hash($Newpwd, PASSWORD_DEFAULT);

            $query = "UPDATE users SET _password=? WHERE _id=?";

            if(!$stmt = $mysqli->prepare($query)){
                header("Location: ../account.php?result=SQLERROR");
                exit();
            } else{
                $stmt->bind_param("ss", $hashedPwd, $userId);
                $stmt->execute();

                $stmt->close();

                header("Location: signout.inc.php?transfer=login");
                exit();
            }
        } else {
            header("Location: ../account.php?result=incorrect");
            exit();
        }   
    } else {
        header("Location: ../account.php?result=SQLERROR");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}