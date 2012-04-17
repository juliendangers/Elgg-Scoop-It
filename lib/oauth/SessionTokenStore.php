<?php

include("TokenStore.php");

// Store authentication tokens in the session
class SessionTokenStore implements TokenStore{
	public function __construct() {

	}
	
	// store
	public function storeRequestToken($value){
		set_plugin_setting("requestToken", $value, "scoopit");
//		$_SESSION['scoop.requestToken']=$value;
	}
	public function storeAccessToken($value){
		set_plugin_setting("accessToken", $value, "scoopit");
//		$_SESSION['scoop.accessToken']=$value;
	}
	public function storeVerifier($value){
		set_plugin_setting("verifier", $value, "scoopit");
	//	$_SESSION['scoop.verifier']=$value;
	}
	public function storeSecret($value){
		set_plugin_setting("secret", $value, "scoopit");
//		$_SESSION['scoop.secret']=$value;
	}
	
	// get
	public function getRequestToken(){
		$rq = get_plugin_setting("requestToken", "scoopit");
		return (isset($rq) && !empty($rq)) ? get_plugin_setting("requestToken", "scoopit") : null;
//		return isset($_SESSION['scoop.requestToken']) ? $_SESSION['scoop.requestToken'] : null;
	}
	public function getAccessToken(){
		$ac = get_plugin_setting("accessToken", "scoopit");
		return (isset($ac) && !empty($ac)) ? get_plugin_setting("accessToken", "scoopit") : null;
		//return isset($_SESSION['scoop.accessToken']) ? $_SESSION['scoop.accessToken'] : null;
	}
	public function getVerifier(){
		$vf = get_plugin_setting("verifier", "scoopit");
		return (isset($vf) && !empty($vf)) ? get_plugin_setting("verifier", "scoopit") : null;
		//return isset($_SESSION['scoop.verifier']) ? $_SESSION['scoop.verifier'] : null;
	}
	public function getSecret(){
		$sc = get_plugin_setting("secret", "scoopit");
		return (isset($sc) && !empty($sc)) ? get_plugin_setting("secret", "scoopit") : null;
		//return isset($_SESSION['scoop.secret']) ? $_SESSION['scoop.secret'] : null;
	}
	
	// flush
	public function flushRequestToken(){
		clear_plugin_setting("requestToken", "scoopit");
		//unset($_SESSION['scoop.requestToken']);
	}
	public function flushAccessToken(){
		clear_plugin_setting("accessToken", "scoopit");
		//unset($_SESSION['scoop.accessToken']);
	}
	public function flushVerifier(){
		clear_plugin_setting("verifier", "scoopit");
		//unset($_SESSION['scoop.verifier']);
	}
	public function flushSecret(){
		clear_plugin_setting("secret", "scoopit");
		//unset($_SESSION['scoop.secret']);
	}
}

?>