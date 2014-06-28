<?php
/* --- Config --- */

require_once('config.php');

/* --- Twitter --- */

require_once('twitteroauth/twitteroauth.php');

function connectTwitterTokens()
{
	return $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
}

function connectTwitter($oauth_token, $oauth_token_secret)
{
	return $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
}

function postTwitter($api, $url, $params)
{
	return $result = $api->post($url, $params);
}

function getTwitter($api, $url, $params)
{
	return $result = $api->get($url, $params);
}
?>
