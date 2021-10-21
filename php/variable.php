<?php
session_start();
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];

define("INCLUDE_DIR", "{$uri}/football/");
define("ROOT_PATH",$_SERVER['DOCUMENT_ROOT']."/football/");
?>