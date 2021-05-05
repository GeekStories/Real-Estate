<?php 
if(isset($_POST['submit-form'])){
    require "../config.php";

    $name=$_POST['name'];
    $email=$_POST['email'];
    $topic =$_POST['topic'];
    $message = $_POST['message'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header('Location: ../contact.php?result=invalidemail');
        exit();
    }

    $result = $mysqli->query("INSERT INTO queries (_name, _email, user_message, property_id) VALUES ('". $name ."', '". $email ."', '". $message ."', '". $topic ."')");

    header('Location: ../contact.php?result=sent');
    exit();

} else {
    header('Location: ../index.php');
    exit();
}