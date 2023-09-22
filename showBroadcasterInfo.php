<?php
session_name('showBroadcasterInfo');
session_start();

$config = "";

include ('config.php');
include ('inc/classes/twitchapi.php');
include ('inc/classes/twitchforms.php');
include ('inc/classes/mysql_db.php');

$config_name = 'showBroadcasterInfo';

$stream = new twitchapi($config_name);
$auth_token = $stream->getToken();

if ($_GET['name']) {
    $broadcaster_name = $_GET['name'];
} else {
    $broadcaster_name = 'skeevetv';
}

$streamInfos 		= new twitchforms($config_name);

$broadcasterInfo  	= $stream->getBroadcaster($broadcaster_name, $auth_token);
$channelInfo 		= $stream->getChannelInfos($broadcasterInfo['broadcaster_id'], $auth_token, $config[$config_name]['client_id']);
$streamInfo         = $stream->getStreamInfo($broadcasterInfo['broadcaster_id'], $auth_token, $config[$config_name]['client_id']);
$followerCount      = $stream->getFollowerCount($broadcasterInfo['broadcaster_id'], $auth_token, $config[$config_name]['client_id']);

# alle Arrays aus den einzelnen API Calls werden vermischt
$Infos 				= array_merge($broadcasterInfo, $channelInfo, $streamInfo, $followerCount);
$streamInfosForm	= $streamInfos->showStreamInfos($Infos);

$form 				= new twitchforms($config_name);
echo $form->design($streamInfosForm);

?>
