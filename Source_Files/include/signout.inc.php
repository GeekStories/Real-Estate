<?php

session_start();
session_unset();
session_destroy();

if(!empty($_GET['transfer'])){
    header("Location: ../login.php?result=success");
    exit();
}

header("Location: ../index.php?result=success");