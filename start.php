<?php

	/*
  	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Julien Crestin [jcrestin@human-connect.com]s
	*/
	
	function scoopit_init() {
		//extend css and metatags
		extend_view('css','scoopit/css');
		extend_view('metatags','scoopit/metatags',1);

		register_page_handler('scoopit','scoopit_page_handler');
		
		//register_entity_type('object','scoopitTopic');
		
		add_subtype('object', 'scoopitTopic');
		add_subtype('object', 'scoopitTopicPost');
		
		// Register a URL handler for Scoop It topics
		register_entity_url_handler('scoopit_topic_url','object','scoopitTopic');
	
		// Register a URL handler for Scoop It posts
		register_entity_url_handler('scoopit_post_url','object','scoopitTopicPost');
		
		extend_view('css','scoopit/css');
		
		$period = get_plugin_setting('period','profile_manager');
		switch ($period)
		{
			case 'hourly':
			case 'daily':
			case 'weekly':
			case 'monthly' :
			case 'yearly' :
			break;
			default: $period = 'hourly';
		}
		register_plugin_hook('cron', $period, 'update_scoopit');
		
		include("lib/scoopit.php");
		include("model/model.php");
	}
	
	function update_scoopit($hook, $entity_type, $returnvalue, $params){
		global $CONFIG;
		$consumerKey = get_plugin_setting("consumerKey");
		$consumerSecret = get_plugin_setting("consumerSecret");
		$localurl = get_plugin_setting("localurl");
		
		$scoop = new ScoopIt(new SessionTokenStore(), $localUrl, $consumerKey, $consumerSecret);
		
		if($topics = get_topics()){
			foreach($topics as $ntopic){
				$id = $scoop->resolve("Topic",$ntopic);
				if($id) {
					$since = 0;
					if(topic_exist($id->id)) {
						$tp = get_topic_by_id($id->id);
						$since = $tp->since;	
					}
					$topic = $scoop->topic($id->id, 30, 0, 0, $since);
					register_topic($topic, $id->id);
					//set_plugin_setting("topic".$ntopic, microtime(), "scoopit");
				} else {
					//system_message 
					//delete the topic from the list	
					delete_topic($ntopic, $topics);
					register_error(sprintf(elgg_echo("scoopit:error:unavailabletopic"), $ntopic));
				}
			}
		}
		echo "update Scoop It topics : OK ";
	}

	/**
	 * Populates the ->getUrl() method for scoopitTopicPost objects
	 *
	 * @param ElggEntity $post scoopitTopicPost entity
	 * @return string poll post URL
	 */
	function scoopit_post_url($post) {
		
		global $CONFIG;
		$title = $post->title;
		$title = friendly_title($title);
		return $CONFIG->url . "pg/scoopit/post/" . $post->getGUID() . "/" . $title;
		
	}

	/**
	 * Populates the ->getUrl() method for scoopitTopic object
	 *
	 * @param ElggEntity $topic scoopitTopic entity
	 * @return string poll post URL
	 */
	function scoopit_topic_url($topic) {
		
		global $CONFIG;
		$title = $topic->title;
		$title = friendly_title($title);
		return $CONFIG->url . "pg/scoopit/topic/" . $topic->getGUID() . "/" . $title;
		
	}	
	
	function scoopit_page_handler($page) {
		
		global $CONFIG;
		
		if (isset($page[0])) 
		{
			switch($page[0]) 
			{
				case "validate": 
					require_once('pages/validate');
					break;
				case "veille":
					require_once('pages/veille.php');
					break;
				case "topic":
					set_input("topic", $page[1]);
					require_once('pages/topic.php');
					break;
				case "post":
					set_input("post", $page[1]);
					require_once('pages/post.php');
					break;
			}
		}
	}
	
	function scoopit_pagesetup(){
		
		global $CONFIG;
	
		$page_owner = page_owner_entity();
	
		//add submenu options
			if (get_context() == "scoopit") {
				add_submenu_item(elgg_echo('scoopit:topics:all'),$CONFIG->wwwroot."pg/scoopit/topic/");
				$tops = elgg_get_entities(
					array(
						"types"=>"object",
						"subtypes"=>"scoopitTopic",
						"limit"=>0
					)
				);
				foreach($tops as $top){
					add_submenu_item(sprintf(elgg_echo('scoopit:topic:subject'),$top->title),$CONFIG->site->url."pg/scoopit/topic/".$top->getGUID()."/".friendly_title($top->title));
				}
			}
		
	}
	
	register_elgg_event_handler('init','system','scoopit_init');
	register_elgg_event_handler('pagesetup','system','scoopit_pagesetup');
?>