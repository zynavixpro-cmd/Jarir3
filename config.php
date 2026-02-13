<?php
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$bots = ["googlebot", "bingbot", "ahrefs", "msnbot", "yandex", "crawler", "scanner"];
foreach($bots as $bot) { if(strpos($ua, $bot) !== false) { header("Location: https://www.google.com"); exit; } }
?>
