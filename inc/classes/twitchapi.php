<?php

class twitchapi {
	private $config_name;
	private $url;
	private $client_id;
	private $client_secret;
	private $auth_token;
	private $scope;
	private $refresh_token;

	public function __construct($config_name) {
		global $config;

		$this->config_name      = $config_name;
		$this->url 	            = $config[$config_name]['url'];
        $this->client_id 		= $config[$config_name]['client_id'];
        $this->client_secret 	= $config[$config_name]['client_secret'];
        $this->auth_token		= 'Bearer ' . $_SESSION['access_token'];
        $this->scope         	= $_SESSION['scope'];
        $this->refresh_token 	= $_SESSION['refresh_token'];
    }

    public function login($reload = true) {

		$parameterValues = array(
			'client_id'		=> $this->client_id,
			'client_secret'	=> $this->client_secret,
			'grant_type'	=> 'authorization_code',
		    'redirect_uri'	=> $this->url,
		    'code'			=> $_GET['code']
		);

		$postValues = http_build_query($parameterValues, '', '&');

		$ch = curl_init();

		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER 	=> true,
			CURLOPT_URL 			=> 'https://id.twitch.tv/oauth2/token',
			CURLOPT_POST 			=> 1,
			CURLOPT_VERBOSE 		=> true,
			CURLOPT_POSTFIELDS  	=> $postValues
		));

		$response = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($response, true);

		$_SESSION['access_token']     = $json['access_token'];
		$_SESSION['scope']            = $json['scope'];
		$_SESSION['refresh_token']    = $json['refresh_token'];

		if ($reload) {
		    if ($_SESSION['access_token']) {
		        header("Location: ". $this->url);
		    }
		}
	}

	public function getToken() {

		$parameterValues = array(
	        'client_id'		=> $this->client_id,
	        'client_secret'	=> $this->client_secret,
	        'grant_type'	=> 'client_credentials'
	    );

	    $postValues = http_build_query($parameterValues, '', '&');

	    $ch = curl_init();

	    curl_setopt_array($ch, array(
	        CURLOPT_RETURNTRANSFER 	=> true,
	        CURLOPT_URL 			=> 'https://id.twitch.tv/oauth2/token',
	        CURLOPT_POST 			=> 1,
	        CURLOPT_VERBOSE 		=> true,
	        CURLOPT_POSTFIELDS  	=> $postValues
	    ));

	    $response = curl_exec($ch);
	    curl_close($ch);

	    $json = json_decode($response, true);
	    $_SESSION['access_token']     = $json['access_token'];
	    $_SESSION['scope']            = $json['scope'];
	    $_SESSION['refresh_token']    = $json['refresh_token'];

		return "Bearer " .$json['access_token'];
	}

	public function refresh_token() {

	    $parameterValues = array(
	        'client_id'		=> $this->client_id,
	        'client_secret'	=> $this->client_secret,
	        'refresh_token' => $this->refresh_token,
	        'redirect_uri'	=> $this->url,
	        'grant_type'	=> 'refresh_token'
	    );

	    $postValues = http_build_query($parameterValues, '', '&');

	    $ch = curl_init();

	    curl_setopt_array($ch, array(
	        CURLOPT_RETURNTRANSFER 	=> true,
	        CURLOPT_URL 			=> 'https://id.twitch.tv/oauth2/token',
	        CURLOPT_POST 			=> 1,
	        CURLOPT_VERBOSE 		=> true,
	        CURLOPT_POSTFIELDS  	=> $postValues
	    ));

	    $response = curl_exec($ch);
	    curl_close($ch);

	    $json = json_decode($response, true);
	    $_SESSION['access_token']     = $json['access_token'];
	    $_SESSION['scope']            = $json['scope'];
	    $_SESSION['refresh_token']    = $json['refresh_token'];

	}

	public function getBroadcasterInfos() {
		$result = $this->callAPI('https://api.twitch.tv/helix/users');
		$json = json_decode($result, true);

		# Daten sammeln und Speichern
		$streamInfo = Array();
		$streamInfo['broadcaster_id'] 		= $json['data']['0']['id'];
		$streamInfo['broadcaster_name'] 	= $json['data']['0']['display_name'];
		$streamInfo['broadcaster_type'] 	= $json['data']['0']['broadcaster_type'];
		$streamInfo['profile_image_url']	= $json['data']['0']['profile_image_url'];
		$streamInfo['offline_image_url']	= $json['data']['0']['offline_image_url'];

		return $streamInfo;
	}

	public function getBroadcaster($name, $token) {
	    $result = $this->callAPI('https://api.twitch.tv/helix/users?login='.$name, $token);
	    $json = json_decode($result, true);
	    #var_dump($json);
	    $key = array_search($name, $json);;

	    # Daten sammeln und Speichern
	    $streamInfo = Array();
	    $streamInfo['broadcaster_id'] 		= $json['data'][$key]['id'];
	    $streamInfo['broadcaster_name'] 	= $json['data'][$key]['display_name'];
	    $streamInfo['broadcaster_type'] 	= $json['data'][$key]['broadcaster_type'];
	    $streamInfo['profile_image_url']	= $json['data'][$key]['thumbnail_url'];
	    $streamInfo['view_count']	        = $json['data'][$key]['view_count'];
	    $streamInfo['profile_image_url']	= $json['data'][$key]['profile_image_url'];

	    return $streamInfo;
	}

	public function getChannelInfos($id, $token = "", $client_id = "") {
	    $result = $this->callAPI('https://api.twitch.tv/helix/channels/?broadcaster_id='.$id, $token, $client_id);
		$json = json_decode($result, true);

		# Daten sammeln und Speichern
		$channelInfo = Array();
		$channelInfo['broadcaster_language'] 	= $json['data']['0']['broadcaster_language'];
		$channelInfo['game_id'] 				= $json['data']['0']['game_id'];
		$channelInfo['game_name']				= $json['data']['0']['game_name'];
		$channelInfo['title']					= $json['data']['0']['title'];

		return $channelInfo;
	}

	public function getStreamInfo($id, $token = "", $client_id = "") {
	    $result = $this->callAPI('https://api.twitch.tv/helix/streams?user_id='.$id, $token, $client_id);
	    $json = json_decode($result, true);

	    if ($json['data']['0']['type'] == "live") {
	        $live = true;
	    } else {
	        $live = false;
	    }

	    $streamInfo = Array();
	    $streamInfo['live_status']		    = $live;
	    $streamInfo['thumbnail_url'] 		= $json['data']['0']['thumbnail_url'];
	    $streamInfo['started_at'] 	        = $json['data']['0']['started_at'];

	    return $streamInfo;

	}

	public function getFollowerCount($id, $token = "", $client_id = "") {
	    $result = $this->callAPI('https://api.twitch.tv/helix/channels/followers?broadcaster_id='.$id, $token, $client_id);
	    $json = json_decode($result, true);

	    $followerInfo = Array();
	    $followerInfo['follower_total'] = $json['total'];

	    return $followerInfo;
	}

	public function getGames($db_config, $cursor = "") {

	    if (empty($cursor)) {
            $result = $this->callAPI('https://api.twitch.tv/helix/search/categories?query=*&first=100');
	    } else {
	        $result = $this->callAPI('https://api.twitch.tv/helix/search/categories?query=*&after='.$cursor);
	    }

	    $json = json_decode($result, true);

	    # Daten sammeln und Speichern
	    foreach ($json['data'] as $key => $value) {
	        $insert_game = new mysql_db($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['base']);
	        $insert_game->query('INSERT IGNORE INTO games VALUES (?,?,?)', $value['id'], $value['name'], $value['box_art_url']);
	        $insert_game->close();
	    }

	    if (!empty($json['pagination']['cursor'])) {
	        $this->getGames($db_config, $json['pagination']['cursor']);
	    }
	}

	private function callAPI($url,  $token = "", $client_id = "", $method="", $data = "") {
		$curl = curl_init();

		$header = array();

		$header[] = 'Content-type:      application/json';
		if ($token) {
		    $header[] = 'Authorization: ' . $token;
		} else {
		    $header[] = 'Authorization: ' . $this->auth_token;
		}

		if ($client_id) {
		    $header[] = 'client-id: ' . $client_id;
		} else {
		    $header[] = 'client-id: ' . $this->client_id;
		}

		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);

		if ($method  == 'POST') {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		#echo "<pre>";
		#echo $url."<br />";
		#var_dump($header);
		#var_dump($curl);
		$result = curl_exec($curl);

		#var_dump($result);
		#echo "</pre>";
		curl_close($curl);
		return $result;
	}
}

?>
