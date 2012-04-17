<?php

	/*
  	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Michal Zacher [michal.zacher@gmail.com]
	 * @remarks In case you need any help with this plugin, geocoding or reverse geocoding, please contact me and I may be able to help.
	*/
	
	function scoopit_init() {
		//extend css and metatags
		extend_view('css','scoopit/css');
		extend_view('metatags','scoopit/metatags',1);

		register_page_handler('scoopit','scoopit_page_handler');
		
		//register_entity_type('object','scoopitTopic');
		
		add_subtype('object', 'scoopitTopic');
		add_subtype('object', 'scoopitTopicPost');
		
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
		
	}
	
	function update_scoopit($hook, $entity_type, $returnvalue, $params){
		global $CONFIG;
		include("lib/scoopit.php");
		include("model/model.php");
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

	function scoopit_page_handler($page) {
		
		global $CONFIG;
		
		if (isset($page[0])) 
		{
			switch($page[0]) 
			{
				case "validate": 
					require_once('pages/validate');
					break;
				case "all":
					require_once('pages/all');
					break;
			}
		}
	}
			
	register_elgg_event_handler('init','system','scoopit_init');
?>