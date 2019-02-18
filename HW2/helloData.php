<?php
$htmlOpen = "<!DOCTYPE HTML><html><head></head><body>";
$htmlClose = "</body></html>";

$param = $_GET['response'];
if ($param == 'XML'){
    header('Content-Type: text/xml');
    echo "<?xml version='1.0' encoding='UTF-8'?>\n<msg>Hello Data it's ", date("Y-m-d h:i:sa"), " </msg>";
}
else if ($param == 'JSON'){
    header('Content-Type: application/json');
    echo "{\n\t\"msg\" : \"Hello Data it's ", date("Y-m-d h:i:sa"), "\"\n}";
}
else{
    header('Content-Type: text/html');
    echo "<!DOCTYPE HTML><html><head></head><body><h1>Error: Specify response parameter</h1></body></html>";
}

?>
