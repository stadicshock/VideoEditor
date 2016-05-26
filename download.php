<?php
    header('Content-type:' .$_GET["type"].'/'.$_GET["format"]);
    header('Content-Disposition: attachment;filename="output.'.$_GET["format"].'"');
    echo file_get_contents("Assets/".$_GET["id"]."/output.".$_GET["format"]);
?>