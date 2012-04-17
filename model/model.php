<?php
	function register_topic($topic, $id){
		if($topic->id != $id)
			return false;
			
		if(topic_exist($id)){
			register_posts($topic->curatedPosts, $id);
		} else {
			$scoopit = new ElggObject();
			
			$scoopit->subtype = "scoopitTopic";
			
			$scoopit->name = $topic->name;
			$scoopit->description = $topic->description;
			$scoopit->url = $topic->url;
			$scoopit->topicid = $topic->id;
			$scoopit->lImage = $topic->largeImageUrl;
			$scoopit->image = $topic->image;
			$scoopit->tags = implode(',', $topic->tags);
			$scoopit->shortName = $topic->shortName;
			$scoopit->access_id = 1;
			
			$guid = $scoopit->save();
			
			register_posts($topic->curatedPosts, $id, $scoopit);
		}
	}
	
	function register_posts($posts, $id, $topic = NULL){
		if(!is_array($posts)) return;
		//print_r($posts);
		foreach($posts as $post){
			$p = new ElggObject();
			$p->subtype = "scoopitTopicPost";
			$p->postid = $post->id;
			$p->title = $post->title;
			$p->topicid = $id;
			$p->description = $post->htmlContent;
			$p->smallDescription = $post->content;
			$p->smallImg = $post->smallImageUrl;
			$p->created = (int)($post->curationDate/1000);
			$p->access_id = 1;
			$guid = $p->save();
			
		}
		if(count($posts) > 0) {
			update_topic($id);	
		}
		
	}
	
	function update_topic($id){
		if($topic = get_topic_by_id($id)) {
			list($u, $s) = explode(' ',microtime()); 
    		$last = (int)bcadd((int)($u*1000), ($s*1000), 7);
			$topic->since = $last;
			$topic->save();
		} else {
			return false;	
		}
	}
	
	function delete_topic($t, $topics){
		foreach($topics as $k=>$topic){
			if($topic == $t)
				unset($topics[$k]);	
		}
		$save = implode(",", $topics);
		set_plugin_setting("localurl",$save,"scoopit");
	}
	
	function get_topics(){
		$topics = get_plugin_setting("topics");
		if(isset($topics)){
			$list = explode(',', $topics);
			return $list;
		}
		return false;
	}
	
	function get_posts_from_topic($id, $limit = 10){
		if(topic_exist($id)) {
			$posts = elgg_get_entities_from_metadata(
				array(
					"types"=>"object",
					"subtypes"=>"scoopitTopicPost",
					"metadata_names" => "topicid",
					"metadata_values"=> $id,
					"limit"=> $limit
				)
			);
			return $posts;
		} else {
			return null;	
		}
	}
	
	function get_posts_count_from_topic($id){
		if(topic_exist($id)) {
			$topic = elgg_get_entities_from_metadata(
				array(
					"types"=>"object",
					"subtypes"=>"scoopitTopicPost",
					"metadata_names" => "topicid",
					"metadata_values"=> $id,
					"count"=>true
				)
			);
			return $topic;
		} else {
			return null;	
		}
	}
	
	/***
	*	Check if a topic already exists in database
	*	Returns:
	*	true, false if it doesn't
	***/
	function topic_exist($topic){
		$last = elgg_get_entities_from_metadata(
			array(
				"types"=>"object",
				"subtypes"=>"scoopitTopic",
				"metadata_names" => "topicid",
				"metadata_values"=> $topic,
				"count"=>true
			)
		);
		//make sure there isn't more than 1 topic with the same id, else there's a problem
		if((int)$last == 1){
			return true;
		} else {
			return false;	
		}
	}
	
	function get_topic_by_id($id){
		if(topic_exist($id)) {
			$topic = elgg_get_entities_from_metadata(
				array(
					"types"=>"object",
					"subtypes"=>"scoopitTopic",
					"metadata_names" => "topicid",
					"metadata_values"=> $id
				)
			);
			return $topic[0];
		} else {
			return false;	
		}
	}
	
	/***
	* returns last check of a topic
	* Returns:
	* timestamp. false on error
	***/
	function get_last_check($id){
		if(topic_exist($id)){
			$last = elgg_get_entities_from_metadata(
				array(
					"types"=>"object",
					"subtypes"=>"scoopitTopic",
					"metadata_names"=>"topicid",
					"metadata_values"=>$id
				)
			);
			return $last[0]->since;
		} else
			return false;
	}
?>