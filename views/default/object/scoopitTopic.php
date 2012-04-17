<?php
	/**
	 * Elgg Scoop It Topic brower.
	 * 
	 * @package scoopit
	 */

	global $CONFIG;
	
	$topic = $vars['entity'];
	
	$topic_guid = $topic->getGUID();
	$tags = $topic->tags;
	$title = $topic->title;
	$desc = $topic->description;
	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
	
	if (!$title) {
		$title = elgg_echo('untitled');
	}
	
	if (get_context() == "search") { 	// Start search listing version 
		
		if (get_input('search_viewtype') == "gallery") {
			echo "<div class=\"scoopit_gallery_posts\">";
			
			echo "</div>";
			
		} else {
		
			$info = "<p> <a href=\"{$topic->getURL()}\">{$title}</a></p>";
			$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/sccopit/subject/{$topic->shortName}\">".$owner->surname. " " . $owner->name."</a> {$friendlytime}";
			//$numcomments = elgg_count_comments($file);
			//if ($numcomments)
				//$info .= ", <a href=\"{$topic->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a>";
			$info .= "</p>";
			
			// $icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'small'));
			$icon = "<a href=\"{$topic->getURL()}\"><img src=\"".$topic->image."\" alt=\"".$topic->name."\" /></a>";
			
			echo elgg_view_listing($icon, $info);
		
		}
		
	} else {							// Start main version
	
?>
	<div class="scoopit_topic">
		<div class="scoopit_topic_icon">
					<a href="<?php echo $vars['url']; ?>"><img src="<?php echo $topic->image;?>" alt="<?php echo $topic->shortName; ?>" /></a>					
		</div>
		
		<div class="scoopit_topic_title_owner_wrapper">
	
            <div class="scoopit_topic_title"><h2><a href="<?php echo $vars['url']; ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php echo $title; ?></a></h2></div>
            <?php 
			if(get_entity(get_plugin_setting("curator")) instanceof ElggUser) {
			?>
            <div class="scoopit_topic_owner">
                    <?php
    
                        echo elgg_view("profile/icon",array('entity' => get_entity(get_plugin_setting("curator")), 'size' => 'tiny'));
                    
                    ?>
                    <small><?php echo $friendlytime; ?></small></p>
            </div>
            <?php
			}
			?>
		</div>

		
		<div class="scoopit_topic_maincontent">
		
				<div class="scoopit_topic_description"><?php echo elgg_view('output/longtext', array('value' => $desc)); ?></div>
				<div class="scoopit_topic_tags">
<?php

		if (!empty($tags)) {

?>
		<div class="object_tag_string"><?php

					echo elgg_view('output/tags',array('value' => $tags));
				
				?></div>
<?php
		}

?>
				</div>
                <div class="scoopit_topic_posts_count">
                	<?php echo sprintf(elgg_echo('scoopit:posts:count'), get_posts_count_from_topic($topic->topicid)); ?>
                </div>
		
		<!--<div class="filerepo_download"><p><a href="<?php echo $vars['url']; ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php echo elgg_echo("file:download"); ?></a></p></div>-->
		
<?php

	if ($topic->canEdit()) {
?>

	<div class="filerepo_controls">
				<p>
					<a href="<?php echo $vars['url']; ?>pg/scoopit/topic/edit/<?php echo $topic->getGUID(); ?>"><?php echo elgg_echo('edit'); ?></a>&nbsp;
					<?php 
						echo elgg_view('output/confirmlink',array(
						
							'href' => $vars['url'] . "action/scoopit/delete?topic=" . $topic->getGUID(),
							'text' => elgg_echo("delete"),
							'confirm' => elgg_echo("file:delete:confirm"),
							'is_action' => true,
						
						));  
					?>
				</p>
	</div>

<?php		
	}

?>
	</div>
</div>

<?php

	if ($vars['full']) {
		
		echo elgg_view_comments($file);
		
	}

?>

<?php

	}

?>