<?php
header('Content-Type: text/html');
echo "<!DOCTYPE HTML><html><head></head><body style='background-color:";
if ($_GET["first"] || $_GET["last"] || $_GET["color"]){
    echo $_GET["color"], "'>","Hello ",$_GET["first"], $_GET["last"], " from a Web app written in PHP on ", date("Y-m-d h:i:sa"), "</body></html>";
}
else if ($_POST["first"] || $_POST["last"] || $_POST["color"]){
    echo $_POST["color"], "'>","Hello ",$_POST["first"], $_POST["last"], " from a Web app written in PHP on ", date("Y-m-d h:i:sa"), "</body></html>";
}
?>
