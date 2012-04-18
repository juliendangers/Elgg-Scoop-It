<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	$topic_guid = get_input('topic', 0);

	if($topic_guid) {
		$topic = get_entity($topic_guid);
		
		if($topic instanceof ElggObject)
			$title = $topic->title;
		else
			$title = elgg_echo('scoopit:topics:all');
		// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
		
		
		// Get objects
		$area2 = elgg_view_title($title);
		//$area1 = get_filetype_cloud(); // the filter
		set_context('search');
		
		//$topic = get_topic_by_name($topic);
		$area2 .= elgg_list_entities_from_metadata(
					array(
						'types' => 'object', 
						'subtypes' => 'scoopitTopicPost', 
						'limit' => $limit, 
						'offset' => $offset, 
						'full_view' => FALSE, 
						"metadata_names" => "topicid",
						"metadata_values"=> $topic->topicid
						)
					);
		
		set_context('scoopit');
			
		$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
	
		page_draw($title, $body);
	} else {
		include("veille.php");
	}
?>