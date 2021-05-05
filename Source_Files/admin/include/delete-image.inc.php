<?php
$filename=$_GET['path'];
$recycle="../../images/Recycle/";
chmod($filename, 0777);

if(move_uploaded_file(realpath($filename), $recycle))
    header('Location: ../controlpanel.php?result=success');

header("Locaton: ../controlpanel.php?result=error");