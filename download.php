<?php
$download="upload/".$_GET['download'];
$rr = $_GET['download'];
header('Content-type: pdf'); 
header('Content-disposition: attachment; filename='.$rr);                             
readfile('upload/'.$_GET['download']);

?>