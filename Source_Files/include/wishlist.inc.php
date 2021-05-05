<?php
session_start();

if(isset($_SERVER['REQUEST_METHOD']) == "POST" && isset($_SESSION['_name'])){

    require "../config.php";

    
    //Get the users wishlist
    $c_wishlist = array();
    $new_wishlist = "";

    $returnToWishlist = false;

    $result = $mysqli->query("SELECT _wishlist FROM users WHERE _id='".$_SESSION['_id']."'");
    $row = $result->fetch_assoc();

    //Explode the wishlist, turn the string into an assoc array
    $c_wishlist = explode(":", $row['_wishlist']);

    //Are we adding or removing a property?
    if(isset($_POST['RemoveFromWishlist'])){
        $c_wishlist = array_diff($c_wishlist, array($_POST['RemoveFromWishlist']));
        $returnToWishlist = true;
    }
    
    if (isset($_POST['AddToWishlist'])){
        array_push($c_wishlist, $_POST['AddToWishlist']);
    }

    //Return the new wishlist and set the users $_SESSION wishlist to the new wishlist so we don't have to continually fetch it.
    $new_wishlist = implode(":", $c_wishlist);
    $_SESSION['_wishlist'] = $new_wishlist;

    $result = $mysqli->query("UPDATE users SET _wishlist='". $new_wishlist ."' WHERE _id=".$_SESSION['_id']."");

    header("Location: ../index.php?result=success");
    exit();
}
?>