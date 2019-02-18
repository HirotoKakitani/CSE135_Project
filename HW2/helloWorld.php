<?php
header('Content-Type: text/html');
$htmlOpen = "<!DOCTYPE HTML><html><head></head><body style='background-color:";
$htmlClose = "</body></html>";
$rand = rand(1,3);
if ($rand == 1){
    $color = "blue";
}
else if($rand==2){
    $color = "yellow";
}
else{
    $color = "white";
}
echo "$htmlOpen","$color","'><p>Hello Web World from Language PHP on ", date("Y-m-d h:i:sa") ," enjoy my ","$color", " page!</p>","$htmlClose";
?>
