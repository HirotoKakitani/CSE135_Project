<?php
header("Content-Type", "text/html");
$content = '<!DOCTYPE><html><head></head><body><form action = "/sessionpage1.php" method="post"><label>User Name: <input type="text" name="userName"></label><input type="submit" value="Submit"></form><a href="/sessionpage2.php">Click here for sessionpage2</a></body></html>';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    setcookie('User-name', $_POST['userName']);
}

echo $content;

?>
