<?php

include ('template.php');

class twitchforms {
	
    public function __construct($config_name) {
        global $config;
        
        $this->url 	            = $config[$config_name]['url'];
        $this->client_id 		= $config[$config_name]['client_id'];
        $this->client_secret 	= $config[$config_name]['client_secret'];
        $this->auth_token		= $_SESSION['access_token'] ;
        $this->scope         	= $_SESSION['scope']  ;
        $this->refresh_token 	= $_SESSION['refresh_token'];
    }
    
	public function showLoginForm() {
		$template_form = new template('inc/template/template_login.html');
		$template_form->replace('client_id',	$this->client_id);
		$template_form->replace('own_url',		urlencode($this->url));
		return $template_form->echo_template();
	}
	
	public function hiddenLoginForm() {    
	    header ('Location: https://id.twitch.tv/oauth2/authorize?response_type=code&client_id=' . $this->client_id . '&redirect_uri='.urlencode($this->url).'&scope=channel_editor');
	}
	
	public function showStreamInfos($data) {
	    $live = "";
	    $notlive = "";
	    
	    if ($data['live_status']) {
	        $live = "inline";
	        $notlive = "none";
	    } else {
	        $live = "none";
	        $notlive = "inline";
	    }
	    
	    $template_StreamInfos = new template('inc/template/template_showBroadcaster.html');
	    
	    $template_StreamInfos->replace('broadcaster_id',		$data['broadcaster_id']);
	    $template_StreamInfos->replace('game_id',				$data['game_id']);
	    
	    $template_StreamInfos->replace('display_name',			$data['broadcaster_name']);
	    $template_StreamInfos->replace('broadcaster_language',	$data['broadcaster_language']);
	    $template_StreamInfos->replace('live_display',		    $live);
	    $template_StreamInfos->replace('notlive_display',		$notlive);
	    $template_StreamInfos->replace('follower_total',		$data['follower_total']);
	    
	    $template_StreamInfos->replace('title',					$data['title']);
	    $template_StreamInfos->replace('game_name',				$data['game_name']);
	    
	    $template_StreamInfos->replace('profile_image_url',		$data['profile_image_url']);
	    
	    return $template_StreamInfos->echo_template();
	}
	
	public function editStreamInfos($data) {
	    $live = "";
	    $notlive = "";
	    
	    if ($data['live_status']) {
	        $live = "inline";
	        $notlive = "none";
	    } else {
	        $live = "none";
	        $notlive = "inline";
	    }
	    
	    $template_StreamInfos = new template('inc/template/template_formular.html');
	    
	    $template_StreamInfos->replace('broadcaster_id',		$data['broadcaster_id']);
	    $template_StreamInfos->replace('game_id',				$data['game_id']);
	    
	    $template_StreamInfos->replace('display_name',			$data['broadcaster_name']);
	    $template_StreamInfos->replace('broadcaster_language',	$data['broadcaster_language']);
	    $template_StreamInfos->replace('live_display',		    $live);
	    $template_StreamInfos->replace('notlive_display',		$notlive);
	    $template_StreamInfos->replace('follower_total',		$data['follower_total']);
	    
	    $template_StreamInfos->replace('title',					$data['title']);
	    $template_StreamInfos->replace('game_name',				$data['game_name']);
	    
	    $template_StreamInfos->replace('profile_image_url',		$data['profile_image_url']);
	    
	    return $template_StreamInfos->echo_template();
	}
	
	public function design($content) {
		$template_design = new template('inc/template/template_design.html');
		
		if ($_GET['iframe'] == "true") {
		    $template_design->replace('css_iframe', '<link rel="stylesheet" href="inc/css/stylesheet_iframe.css" type="text/css" />');
		} else {
		    $template_design->replace('css_iframe', '');
		}
		
		$template_design->replace('content', $content);
		return $template_design->echo_template();
	}
}
	
?>