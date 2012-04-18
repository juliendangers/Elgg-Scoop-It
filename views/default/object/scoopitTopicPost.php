<?php
	/**
	 * Elgg Scoop It Topic brower.
	 * 
	 * @package scoopit
	 */

	global $CONFIG;
	
	$post = $vars['entity'];
	
	$post_guid = $post->getGUID();
	$tags = $post->tags;
	$title = $post->title;
	$desc = $post->smallDescription;
	$owner = $vars['entity']->getOwnerEntity();
	$friendlytime = elgg_view_friendly_time($vars['entity']->created);
	
	if (!$title) {
		$title = elgg_echo('untitled');
	}
	
	if (get_context() == "search") { 	// Start search listing version 
		
		if (get_input('search_viewtype') == "gallery") {
			echo "<div class=\"scoopit_gallery_post\">";
			
			echo "</div>";
			
		} else {
		
			$info = "<p> <a href=\"{$post->getURL()}\">{$title}</a></p>";
			$info .= "<p>".$post->smallDescription."</p>";
			$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/sccopit/post/{$post_guid}\">Scoopt It</a> {$friendlytime}";
			//$numcomments = elgg_count_comments($file);
			//if ($numcomments)
				//$info .= ", <a href=\"{$post->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a>";
			$info .= "</p>";
			
			// $icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'small'));
			$icon = "<a href=\"{$post->getURL()}\"><img src=\"".$post->smallImg."\" alt=\"".$post->title."\" /></a>";
			
			echo elgg_view_listing($icon, $info);
		
		}
		
	} else {							// Start main version
	
?>
	<div class="scoopit_post" style="float:left">
		<div class="scoopit_post_icon" style="float:left;margin-right:20px;">
        <?php if( $post->imageUrl) { ?>
					<a href="<?php echo $post->bigImage; ?>"><img src="<?php echo $post->imageUrl;?>" alt="<?php echo $post->title; ?>" /></a>	
        <?php } ?>				
		</div>
		
		<div class="scoopit_post_title_owner_wrapper">
	
            <div class="scoopit_post_title" style="padding:20px"><h2><a href="<?php echo $post->originalUrl; ?>"><?php echo $title; ?></a></h2></div>
            <?php 
			if(get_entity(get_plugin_setting("curator")) instanceof ElggUser) {
			?>
            <div class="scoopit_post_owner">
                    <?php
    
                        echo elgg_view("profile/icon",array('entity' => get_entity(get_plugin_setting("curator")), 'size' => 'tiny'));
                    
                    ?>
                    <small><?php echo $friendlytime; ?></small></p>
            </div>
            <?php
			}
			?>
		</div>

		
		<div class="scoopit_post_maincontent">
		
				<div class="scoopit_post_description"><?php echo elgg_view('output/longtext', array('value' => $desc)); ?></div>
				<div class="scoopit_post_tags">
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
                <div class="source_scoopit">
                	<?php echo elgg_echo('via'). " " . "<a href=\"{$post->scoopUrl}\"><img src=\"".$CONFIG->site->url."mod/scoopit/graphics/logo.png"."\" alt=\"{$post->title}\" /></a>"; ?>
                </div>
                <div class="visit_scoopit">
                	<a href="<?php echo $post->originalUrl; ?>" title="<?php echo $title; ?>"><?php echo elgg_echo('scoopit:visit'); ?></a>
                </div>
		
		<!--<div class="filerepo_download"><p><a href="<?php echo $vars['url']; ?>mod/file/download.php?file_guid=<?php echo $file_guid; ?>"><?php echo elgg_echo("file:download"); ?></a></p></div>-->

	</div>
</div>

<?php

	if ($vars['full']) {
		
		echo elgg_view_comments($post);
		
	}

?>

<?php

	}

?>