<?php 
if(isset($_POST['submit-property'])){
    require "../../config.php";

    $number = $_POST['number'];
    $name = ucfirst(strtolower($_POST['name']));
    $area = ucfirst(strtolower($_POST['area']));
    $city = ucfirst(strtolower($_POST['city']));
    $price = $_POST['price'];
    $desc = $_POST['description'];

    //Set Island Variables
    switch($_POST['island']){
        case "North":
            $island_id = 1;
        break;
        case "South":
            $island_id = 2;
        break;
    }

    session_start();
    $id = $_SESSION['house_id'];

    $temp = array($_POST['garage'], $_POST['beds'], $_POST['bath'], $_POST['toilets']);

    $facilities = implode(":", $temp);

    $query = "UPDATE properties SET st_number=?, st_name=?, _area=?, _city=?, island_id=?, _price=?, _description=?, _facilities=? WHERE _id=?";
    if(!$stmt = $mysqli->prepare($query)){
        header("Location: ../controlpanel.php?error=sqlerror");
        exit();
    } else {
        $stmt->bind_param("isssiissi", $number, $name, $area, $city, $island_id, $price, $desc, $facilities, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: ../controlpanel.php?update=success");
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../../index.php");
    exit();
}