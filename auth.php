<?php

### Vladimir S. Udartsev
### udartsev.ru

### Based on [Hybridauth v 3.0.0] https://hybridauth.github.io/

/*
PHP SocialConnectClass - This simple example class illustrate how to authenticate users with Facebook and Vkontakte.

Based on Hybridauth Library and returns normally PHP Array output instead object array.

Most other providers (which includes in Hybridauth/Provider) work pretty much the same just replace Vkontakte/Facebook provider names to another provider name.
 */

/**
 * SocialConnectClass
 */
$data = new SocialConect('facebook'); //change 'vkontakte' to another provider to catch the data
echo "<pre>";
var_dump($data); // for Tests only
unset($data); //unsetting data

class SocialConect {
	private $autoload;
	private $provider;
	private $config;
	private $callback;

	public $data = array();

	function __construct($provider = null) {

		/*Save the Hybridauth Library Path*/
		$this->autoload = dirname(__FILE__) . '/hybridauth/autoload.php';

		/*Set Social Driver Callback Way*/
		/*change callback way address*/
		$this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/SocialConnectClass/auth.php';

		/*Set Provide Name*/
		$this->provider = $provider;

		/*Checking Matching $provider Name*/
		if ($provider == 'vkontakte') {
			$this->config = [
				'callback' => $this->callback,
				'keys' => ['id' => '', 'secret' => ''],
			];
			$this->connect();
		}
		if ($provider == 'facebook') {
			$this->config = [
				'callback' => $this->callback,
				'keys' => ['id' => '', 'secret' => ''],
			];
			$this->connect();
		}

		/*If No Matching - Return False*/
		return false;
	}

	private function connect() {

		/*Require the Hybridauth Library*/
		require $this->autoload;

		/*Create new Hybridauth Class*/
		$auth = new Hybridauth\Provider\Vkontakte($this->config);

		/*Do Hybridauth Autentification*/
		$auth->authenticate();

		/*Get User Profile*/
		$this->data['userProfile'] = $auth->getUserProfile();

		/*Get Provider API Response*/
		$this->data['apiResponse'] = $auth->apiRequest('gists');

		/*Get Provider Access Token Key*/
		$this->data['accessToken'] = $auth->getAccessToken();

		/*Disconnect From Provider*/
		$auth->disconnect();

		/*Unset Variables*/
		unset($auth);

		/*Object Data to Array Data*/
		$this->data = json_decode(json_encode($this->data), true);

		/*Return Catched Data*/
		return $this->data;
	}
}