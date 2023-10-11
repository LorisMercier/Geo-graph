<?php

function addLogEvent($event)
{
    date_default_timezone_set('Europe/Paris');

    $time = date("D, d M Y H:i:s");
    $time = "[".$time."] ";

    $event = $time.$event."\n";

    file_put_contents($_SERVER['DOCUMENT_ROOT']."/log/fichier.log", $event, FILE_APPEND);
    
}

function consoleLogObject($obj){
    $objJSON = json_encode($obj,JSON_UNESCAPED_UNICODE);
    echo "<script> var obj =".$objJSON.";"."console.log(obj);</script>";
}

?>