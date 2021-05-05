<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    unlink($_POST['image']);
    header("Location: ../gallery.php?property=".$_POST['property']."");
}