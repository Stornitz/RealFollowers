<?php
session_start();

require_once("function.php");

$connection = connectTwitterTokens();

$request_token = $connection->getRequestToken();

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$url = $connection->getAuthorizeURL($request_token['oauth_token'], true);

header('Location: '.$url);
?>
