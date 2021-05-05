<?php
session_start();

// Function to remove folders and files 
function rmrf($dir) {
    foreach (glob($dir) as $file) {
        if (is_dir($file)) { 
            rmrf("$file/*");
            rmdir($file);
        } else {
            unlink($file);
        }
    }
}

if($_SERVER['REQUEST_METHOD'] === "POST"){
    require "../../config.php";

    $unique_id = $_POST['property'];

    $query = "DELETE FROM properties WHERE unique_id=?";
    if($stmt = $db->prepare($query)){

        $stmt->bind_param("s", $unique_id);
        $stmt->execute();

        $dir="../../companies/". $_SESSION['company_name'] ."/".$unique_id;

        rmrf($dir); //Delete the properties directory and photos

        header("Location: ../controlpanel.php?result=houseremoved");
        exit();
    } else { 
        header("Location: ../controlpanel.php?error=".$mysqli->error."");
        exit();
    }
} else {
    header("Location: ../../index.php");
    exit();
}