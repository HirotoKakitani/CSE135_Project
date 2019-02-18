<?php
    if ($_POST["clearCookie"] == "Clear"){
        //clear cookie
        if (isset($_COOKIE["User-name"])){
            unset($_COOKIE["User-name"]);
            setcookie("User-name", null, -1, "/");
        }
    } 
    echo "<a href = '/sessionpage1.php'>To sessionpage1</a> <a href='/sessionpage2.php'>To sessionpage2</a>";
?>
