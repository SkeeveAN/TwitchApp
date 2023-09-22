<?php
session_name('changeStreamInfo');
session_start();

include ('config.php');
include ('inc/classes/twitchapi.php');
include ('inc/classes/twitchforms.php');
include ('inc/classes/mysql_db.php');

$config_name = 'changeStreamInfo';

$stream = new twitchapi($config_name);
$stream->refresh_token($config_name);

if (!empty($_SESSION['access_token'])) {
	#echo "<br />Debug - login abgeschlossen <br />";    
    $streamInfos 		= new twitchforms($config_name);
	
	$broadcasterInfo  	= $stream->getBroadcasterInfos();
	$channelInfo 		= $stream->getChannelInfos($broadcasterInfo['broadcaster_id']);
	$streamInfo         = $stream->getStreamInfo($broadcasterInfo['broadcaster_id']);
	$followerCount      = $stream->getFollowerCount($broadcasterInfo['broadcaster_id']);
	
	# alle Arrays aus den einzelnen API Calls werden vermischt
	$Infos 				= array_merge($broadcasterInfo, $channelInfo, $streamInfo, $followerCount);
	$streamInfosForm	= $streamInfos->editStreamInfos($Infos);
	
	$form 				= new twitchforms($config_name);
	echo $form->design($streamInfosForm);
	
} else {
	#echo "<br />Debug - login benoetigt <br />";
	
    $login 			= new twitchforms($config_name);
	$loginForm 		= $login->showLoginForm();
	
	$form 			= new twitchforms($config_name);
	echo $form->design($loginForm);
	
	$stream->login();
}

?>