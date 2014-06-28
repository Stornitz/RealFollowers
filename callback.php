<?php
session_start();
require_once('function.php');

if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token'])
{
	$_SESSION['oauth_status'] = 'oldtoken';
}

$api = connectTwitter($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

$access_token = $api->getAccessToken($_REQUEST['oauth_verifier']);

$_SESSION['access_token'] = $access_token;

if ($api->http_code == 200)
{
	// Application autorisée
	$accesstoken = $_SESSION['access_token']['oauth_token'];
	$accesstoken_secret = $_SESSION['access_token']['oauth_token_secret'];
	$get = $api->get('account/verify_credentials');
	$pseudo = $get->screen_name;

	$followersId = $api->get('followers/ids', array('screen_name' => $pseudo))->ids;

	$length = count($followersId);
	$i = 0;
	$nb = 0;
	while($i < $length) {
		$ids = array_slice($followersId, $i, 100);

		$usersId = implode(',', $ids);
		$users = $api->post('users/lookup', array('user_id' => $usersId));
		
		foreach($users as $user) {
			$followings = $user->friends_count;
			$timestamp = strtotime($user->status->created_at);
			if($followings > 5 && $followings < 1000 && $timestamp > time()-60*60*24*30) {
				$nb++;
			}
		}


		$i += 100;
	}

	$pr = floor(($nb/$length)*100);
	echo 'Vous avez '.$nb.' sur '.$length.' followers rééls<small>*</small> ('.$pr.'%).';
	?>
	<br>
	<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://stornitz.fr/TwitterAPIs/RealFollowers" data-text="J'ai <?=$pr?>% de followers réels ! Toi aussi découvre ton pourcentage de vrais followers! :D" data-via="Stornitz" data-hashtags="RealFollowers">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	<br>
	<br>
	<small>* : Compte ayant entre 5 et 1000 followings et ayant tweeté il y a moins d\'un mois.</small>
	<?php
}
else 
{
	// Application refusée
	echo "Error ".$api->http_code;
}
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);
?>
