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
$data = new SocialConect('vkontakte'); //change 'vkontakte' to another provider to catch the data
echo "<pre>";
var_dump($data); // for Tests only
unset($data); //unsetting data

class SocialConect {
	private $autoload;
	private $provider;
	private $config;
	private $callback;
	private $auth;
	private $errorMessage;

	public $data = array();

	function __construct($provider = null) {

		/*Save the Hybridauth Library Path*/
		/*change library way address*/
		$this->autoload = dirname(__FILE__) . '/hybridauth/autoload.php';

		/*Set Social Driver Callback Way*/
		/*change callback way address*/
		$this->callback = 'http://' . $_SERVER['HTTP_HOST'] . '/SocialConnectClass/auth.php';

		/*Set Provide Name*/
		$this->provider = $provider;

		/*Check Provider*/
		$this->providerCheck();

		/*Check for errors*/
		if ($this->errorMessage) {return $this->errorMessage;}

		/*Return Catched Data*/
		return $this->data;
	}

	private function providerCheck() {
		/*Checking Matching $provider Name*/
		if ($this->provider == 'vkontakte') {
			$this->config = [
				'callback' => $this->callback,
				'keys' => ['id' => '6357516', 'secret' => '6HzVAG78rT3huBeYE3OZ'],
			];
			$this->vkontakteAuth();
		}
		if ($this->provider == 'facebook') {
			$this->config = [
				'callback' => $this->callback,
				'keys' => ['id' => '1814018862224028', 'secret' => '9312fd7b71ac2674d6429e1ece68c942'],
			];
			$this->facebookAuth();
		}
		/*If No Matching - Return False*/
		$this->errorMessage = 'ERROR: Provider [' . $this->provider . '] Does Not Found!';
	}

	private function vkontakteAuth() {
		/*Require the Hybridauth Library*/
		require $this->autoload;

		/*Create new Hybridauth Class*/
		$this->auth = new Hybridauth\Provider\Vkontakte($this->config);

		/*Connect to Provider*/
		$this->connect();
	}

	private function facebookAuth() {
		/*Require the Hybridauth Library*/
		require $this->autoload;

		/*Create new Hybridauth Class*/
		$this->auth = new Hybridauth\Provider\Facebook($this->config);

		/*Connect to Provider*/
		$this->connect();
	}

	private function connect() {
		/*Do Hybridauth Autentification*/
		$this->auth->authenticate();

		/*Get User Profile*/
		$this->data['userProfile'] = $this->auth->getUserProfile();

		/*Get Provider API Response*/
		$this->data['apiResponse'] = $this->auth->apiRequest('gists');

		/*Get Provider Access Token Key*/
		$this->data['accessToken'] = $auth->getAccessToken();

		/*Get Provider Responce Body*/
		//$this->data['ResponseBody']->getHttpClient()->getResponseBody();

		/*Disconnect From Provider*/
		$this->auth->disconnect();

		/*Unset Variables*/
		unset($this->auth, $this->config);

		/*Object Data to Array Data*/
		$this->data = json_decode(json_encode($this->data), true);
	}
}