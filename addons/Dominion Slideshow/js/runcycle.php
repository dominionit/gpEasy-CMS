<?php
header('content-type:application/x-javascript');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the pas

$cycle = isset($_GET['c'])?$_GET['c']:2000; //verstek 2000
$effek = isset($_GET['e'])?$_GET['e']:'fadeZoom'; //verstek fadeZoom
$idName =  isset($_GET['i'])?$_GET['i']:'slideshow'; //verstek fadeZoom

echo "$(function() {
    $('#$idName').cycle({
        fx:       '$effek',
        timeout:   $cycle,
        after:     function() {
            $('#caption').html(this.alt);
        }
    });
});";
?>
