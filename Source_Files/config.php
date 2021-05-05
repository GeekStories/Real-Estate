<?php 
date_default_timezone_set("Pacific/Auckland");

$db = new mysqli("localhost", "root", "", "realestate");
if($db->connect_errno){
    printf("Connect failed: $s\n", $mysqli->connect_error);
    exit();
}