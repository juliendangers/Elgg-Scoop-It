<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	$post_guid = get_input('post');
	
	$post = get_entity($post_guid);
	
	if($post instanceof ElggObject)
		$title = $post->title;
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
	
	$area2 .= elgg_view("object/scoopitTopicPost", array("entity"=>$post, 'full' => TRUE));
	/*
	set_context('search');
	
	//$post = get_topic_by_name($post);
	$area2 .= elgg_list_entities_from_metadata(
				array(
					'types' => 'object', 
					'subtypes' => 'scoopitTopicPost', 
					'limit' => $limit, 
					'offset' => $offset, 
					'full_view' => FALSE, 
					"metadata_names" => "topicid",
					"metadata_values"=> $post->topicid
					)
				);
	
	set_context('scoopit');
		*/
	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);

	page_draw($title, $body);

?>