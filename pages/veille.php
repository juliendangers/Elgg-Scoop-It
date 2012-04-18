<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
	
	$title = elgg_echo('scoopit:topics:all');
	
	// Get objects
	$area2 = elgg_view_title($title);
	//$area1 = get_filetype_cloud(); // the filter
	set_context('search');
	$topics = get_topics();
	$area2 .= elgg_list_entities(
				array(
					'types' => 'object', 
					'subtypes' => 'scoopitTopic', 
					'limit' => $limit, 
					'offset' => $offset, 
					'full_view' => FALSE
					)
				);
	$area2 .= "<div class=\"api-agreement\"><a href=\"http://www.scoop.it/\"><img src=\"".$CONFIG->site->url."mod/scoopit/graphics/poweredbyscoopit.png\" /></a></div>";
	set_context('scoopit');
		
	$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);

	page_draw($title, $body);

?>