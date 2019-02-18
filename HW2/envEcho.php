<?php
$vars = apache_request_headers();
header('Content-Type: text/html');
echo "<!DOCTYPE HTML><html><head></head><body>";
foreach ($vars as $header => $value){
    echo "<p>$header: $value </p>";
}
echo "</body></html>";
?>
