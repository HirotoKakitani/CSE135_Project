<?php
    header("Content-Type", "text/html");
    $content = null;
    echo "<!DOCTYPE HTML><html><head></head><body>";
    if ($_COOKIE['User-name'] == null){
        echo "<p>Howdy stranger. Please tell me your name on page 1!</p>";
    }
    else {
        echo "<p>Hi ";
        echo $_COOKIE["User-name"];
        echo ", nice to meet you!</p>";
    }
    echo '<form action = "/clearCookie.php" method= "post"><input type="submit" name="clearCookie" value="Clear"></form></body></html>';
?>
