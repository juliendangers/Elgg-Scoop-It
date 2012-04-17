<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 * 
	 * 
	 * TODO: File icons, download & mime types
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
			
	$area2 = elgg_view_title(elgg_echo('scoopit:timeline'));
		
	// Get objects
	set_context('search');
	$offset = (int)get_input('offset', 0);
	$content = elgg_list_entities(array('types' => 'object', 'subtypes' => 'scoopit', 'limit' => 10, 'offset' => $offset, 'full_view' => FALSE));
	if (!$content) {
		$content = elgg_view('page_elements/contentwrapper',array('body' => elgg_echo("file:none")));
	}
	$area2 .= $content;

	set_context('scoopit');

	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	page_draw($title, $body);
?>