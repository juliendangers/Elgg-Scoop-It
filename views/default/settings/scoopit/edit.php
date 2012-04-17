<?php
	global $CONFIG;
	$consumerKey = "";
	$consumerSecret = "";
	$curator = "";
	
	if ($vars['entity']->consumerKey)
		$consumerKey = $vars['entity']->consumerKey;
	if ($vars['entity']->consumerSecret)
		$consumerSecret = $vars['entity']->consumerSecret;
	if ($vars['entity']->topics)
		$topics = $vars['entity']->topics;
	if ($vars['entity']->curator)
		$curator = $vars['entity']->curator;
?>
<p>
	<?php echo elgg_echo('scoopit:params:key'); ?>
	
	<?php echo elgg_view('input/text', array('internalname' => 'params[consumerKey]', 'value' => $consumerKey)); ?>
</p>
<p>
	<?php echo elgg_echo('scoopit:params:secret');?>

	<?php echo elgg_view('input/text', array('internalname' => 'params[consumerSecret]','class' => ' ', 'value' => $consumerSecret)); ?>
</p>
<p>
	<?php echo elgg_echo('scoopit:params:topics');?>

	<?php echo elgg_view('input/text', array('internalname' => 'params[topics]','class' => ' ', 'value' => $topics)); ?>
</p>
<p>
	<?php echo elgg_echo('scoopit:params:curator');?>

	<?php echo elgg_view('input/text', array('internalname' => 'params[curator]','class' => ' ', 'value' => $curator)); ?>
</p>
<?php
	echo elgg_view('input/hidden', array('internalname' => 'params[localurl]', 'value' => $CONFIG->site->url."pg/scoopit/validate"));
?> 