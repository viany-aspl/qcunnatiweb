<?php
include ('../system/library/Barcode39.php');

$bc = new Barcode39($_GET['inv']); 
header('Content-type: image/gif');
$bc->draw();
?>