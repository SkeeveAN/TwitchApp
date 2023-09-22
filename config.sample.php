<?php

#error_reporting(E_ERROR | E_PARSE);
if ($_GET['debug'] == 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$config = Array (
    'changeStreamInfo' => array(
        'url'               => 'https://twitchapp.skeeve-live.de/changeStreamInfo.php',
        'client_id' 		=> '*****************************',
        'client_secret' 	=> '*****************************'
    ),
    'showBroadcasterInfo' => Array(
        'url'               => 'https://twitchapp.skeeve-live.de/showBroadcasterInfo.php',
        'client_id' 		=> '*****************************',
        'client_secret' 	=> '*****************************',
    ),
    'cron_games' => Array(
        'url'               => 'https://twitchapp.skeeve-live.de/cron_games.php',
        'client_id' 		=> '*****************************',
        'client_secret' 	=> '*****************************'
    )
);

$db_config = Array (
    'host' => 'localhost',
    'user' => 'twitch_change_info',
    'pass' => 'TwitchStreamInfo',
    'base' => 'twitch_change_info'
);


?>
