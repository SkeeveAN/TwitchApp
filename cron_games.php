<?php
session_name('cron_games');
session_start();

include ('config.php');
include ('inc/classes/twitchapi.php');
include ('inc/classes/twitchforms.php');
include ('inc/classes/mysql_db.php');

$config_name = 'cron_games';

function index() {
    global $config_name, $db_config;

    $stream = new twitchapi($config_name);
    $stream->refresh_token();

    if (!empty($_SESSION['access_token'])) {
        $stream->getGames($db_config);
    } else {
        $stream->login();

        $login = new twitchforms($config_name);
        $login->hiddenLoginForm();
    }
}

function readDB($query) {
    global $config_name, $db_config;

    $games_json = Array();

    $read_game = new mysql_db($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['base']);
    $games = $read_game->query("SELECT game_id, game_name, game_pic FROM games WHERE game_name like '".$query."%'ORDER BY game_name")->fetchAll();

    foreach ($games as $key => $value) {
        $games_json[$key] = Array(
            'id' => $value['game_id'],
            'name' => $value['game_name'],
            'img' => $value['game_pic']
        );
    }

    $read_game->close();

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($games_json);
}

switch ($_GET['open']) {

    case 'readDB':
        readdb($_GET['term']);
        break;

    default:
        index();
        break;
}

?>
