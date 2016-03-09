<?php

class ApiRequest extends Request {
	protected $registry;
	public $headers = array();

	public function __construct($registry) {
		parent::__construct();

		$this->registry = $registry;

		if(function_exists('getallheaders')) {
			$this->headers = $this->clean(getallheaders());
		}
		else {
			$this->headers = $this->clean($this->getallheaders());
		}

		// Treat the PUT variables as POST variables.
		if($this->server['REQUEST_METHOD'] == 'PUT') {
			parse_str(file_get_contents("php://input"), $this->post);
		}
	}

	public function isOAuthTokenRequest() {
		if(isset($this->get['route'])) {
			return $this->get['route'] == 'oauth2/token';
		}

		return false;
	}

	public function isGetRequest() {
		return $this->server['REQUEST_METHOD'] == 'GET';
	}

	public function isPostRequest() {
		return $this->server['REQUEST_METHOD'] == 'POST';
	}

	public function isPutRequest() {
		return $this->server['REQUEST_METHOD'] == 'PUT';
	}

	public function isDeleteRequest() {
		return $this->server['REQUEST_METHOD'] == 'DELETE';
	}

	public function setDefaultParameters($defaultParameters) {
		$data = NULL;

		if($this->isGetRequest()) {
			$data = &$this->get;
		}
		else if($this->isPostRequest() || $this->isPutRequest()) {
			$data = &$this->post;
		}

		foreach($defaultParameters as $key => $value) {
			if(!isset($data[$key])) {
				$data[$key] = $value;
			}
		}
	}

	/**
	 * This method is used to simulate the way a checkbox works. If
	 * the value for the given key is not true we unset this parameter
	 * just like would happen when a checkbox is unchecked and submitted.
	 */
	public function convertBoolToCheckbox($key) {
		if($this->isPostRequest() || $this->isPutRequest()) {
			$requestParams = &$this->post;
		}
		else if($this->isGetRequest()) {
			$requestParams = &$this->get;
		}

		if(isset($requestParams) && isset($requestParams[$key])) {
			if($requestParams[$key] !== 'true') {
				unset($requestParams[$key]);
			}
			else {
				$requestParams[$key] = '1';
			}
		}
	}

	public function convertBoolToYesNoRadioValue($key) {
		if($this->isPostRequest() || $this->isPutRequest()) {
			$requestParams = &$this->post;
		}
		else if($this->isGetRequest()) {
			$requestParams = &$this->get;
		}

		if(isset($requestParams) && isset($requestParams[$key])) {
			if($requestParams[$key] !== 'true') {
				$requestParams[$key] = '0';
			}
			else {
				$requestParams[$key] = '1';
			}
		}
	}

	private function getallheaders() 
    { 
      	$headers = ''; 
	  	foreach ($_SERVER as $name => $value) 
	   	{ 
	       	if (substr($name, 0, 5) == 'HTTP_') 
	       	{ 
	           	$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
	       	} 
	   	} 
	   	return $headers; 
    }
	
}

?>