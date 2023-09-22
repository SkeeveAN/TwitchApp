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
        'client_id' 		=> '31bcppakc7fxjr6gavd67a831y7qmq',
        'client_secret' 	=> 'pgljg35jxmdy7onmwb5t916f29kx9x'
    ),
    'showBroadcasterInfo' => Array(
        'url'               => 'https://twitchapp.skeeve-live.de/showBroadcasterInfo.php',
        'client_id' 		=> '31bcppakc7fxjr6gavd67a831y7qmq',
        'client_secret' 	=> 'pgljg35jxmdy7onmwb5t916f29kx9x',
    ),
    'cron_games' => Array(
        'url'               => 'https://twitchapp.skeeve-live.de/cron_games.php',
        'client_id' 		=> 'pgljg35jxmdy7onmwb5t916f29kx9x',
        'client_secret' 	=> 'scwpymw5wv0gageth2zcjzvkvzvjmn'
    )
);

$db_config = Array (
    'host' => 'localhost',
    'user' => 'twitch_change_info',
    'pass' => 'TwitchStreamInfo',
    'base' => 'twitch_change_info'
);


?>
