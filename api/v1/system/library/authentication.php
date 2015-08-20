<?php

class Authentication {

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->config = $registry->get('config');
	}

	public function generateAccessToken($oldAccessToken = NULL) {
		$headers = $this->getAuthorizationHeader();

		if(isset($headers['PHP_AUTH_USER'])) {
			$consumerKey = $headers['PHP_AUTH_USER'];
		}
		else {
			return false;
		}

		if(isset($headers['PHP_AUTH_PW'])) {
			$consumerSecret = $headers['PHP_AUTH_PW'];
		}
		else {
			return false;
		}

		$client = $this->db->query("SELECT * FROM " . DB_PREFIX . "api_oauth_client WHERE client_id = '" . $this->db->escape($consumerKey) . "' AND client_secret = '" . $this->db->escape($consumerSecret) . "'");
		
		if(!empty($client->row)) {
			$accessToken = $this->makeAccessToken();
			$accessTokenTtl = $this->config->get('api_access_token_ttl') <= 0 ? 0 : time() + $this->config->get('api_access_token_ttl');

			$this->db->query("INSERT INTO " . DB_PREFIX . "api_oauth_session_access_token SET client_id = '" . $this->db->escape($consumerKey) . "', access_token = '" . $this->db->escape($accessToken) . "', access_token_expires = '" . $accessTokenTtl . "'");

			// Restore data
			if($oldAccessToken != NULL) {
				$sessionData = $this->db->query("SELECT data FROM " . DB_PREFIX . "api_session WHERE session_id = '".$this->db->escape($oldAccessToken)."'");
				if(!empty($sessionData->row)) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "api_session SET session_id = '" . $this->db->escape($accessToken) . "', access = '".time()."', data = '" . $sessionData->row['data'] . "'");

					// Clean up old session
					$this->db->query("DELETE FROM " . DB_PREFIX . "api_session WHERE session_id = '".$this->db->escape($oldAccessToken)."'");
				}

				$this->db->query("DELETE FROM " . DB_PREFIX . "api_oauth_session_access_token WHERE access_token = '".$this->db->escape($oldAccessToken)."'");
			}

			return $accessToken;
		}
		else {
			return false;
		}
	}

	public function isValid() {
		$accessToken = $this->getAccessToken();

		if($accessToken !== '') {
			$session = $this->db->query("SELECT * FROM " . DB_PREFIX . "api_oauth_session_access_token WHERE access_token = '" . $this->db->escape($accessToken) . "' AND (access_token_expires = 0 OR access_token_expires > '".time()."')");

			if(!empty($session->row)) {
				return true;
			}
		}
		
		return false;
	}

	public function getAccessToken() {
		$accessToken = '';

		// Search for the access token in the header
		if(isset($this->request->headers['Authorization']) && !empty($this->request->headers['Authorization'])) {
			$header = $this->request->headers['Authorization'];

            if (strpos($header, ',') !== false) {
                $headerPart = explode(',', $header);
                $accessToken = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $headerPart[0]));
            } else {
                $accessToken = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $header));
            }
            $accessToken = ($accessToken === 'Bearer') ? '' : $accessToken;
        }
        else if(isset($this->request->get['access_token'])) { // Search for the access token in the get parameters
        	$accessToken = $this->request->get['access_token'];
        }

        return $accessToken;
	}

	private function makeAccessToken($length = 40) {
		$bytes = openssl_random_pseudo_bytes($length * 2);
		$base64 = base64_encode($bytes);
		return substr(str_replace(array('/', '+', '='), '', $base64), 0, $length);
	}

	private function getAuthorizationHeader() {
		$headers = array();

        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $headers['PHP_AUTH_USER'] = $_SERVER['PHP_AUTH_USER'];
            $headers['PHP_AUTH_PW'] = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
        } else {
            // php-cgi under Apache does not pass HTTP Basic user/pass to PHP by default
            $authorizationHeader = null;

            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $authorizationHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = (array) apache_request_headers();
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                if (isset($requestHeaders['Authorization'])) {
                    $authorizationHeader = trim($requestHeaders['Authorization']);
                }
            }
            if (null !== $authorizationHeader) {
                $headers['AUTHORIZATION'] = $authorizationHeader;
                // Decode AUTHORIZATION header into PHP_AUTH_USER and PHP_AUTH_PW when authorization header is basic
                if (0 === stripos($authorizationHeader, 'basic')) {
                    $exploded = explode(':', base64_decode(substr($authorizationHeader, 6)));
                    if (count($exploded) == 2) {
                        list($headers['PHP_AUTH_USER'], $headers['PHP_AUTH_PW']) = $exploded;
                    }
                }
            }
        }

        // PHP_AUTH_USER/PHP_AUTH_PW
        if (isset($headers['PHP_AUTH_USER'])) {
            $headers['AUTHORIZATION'] = 'Basic '.base64_encode($headers['PHP_AUTH_USER'].':'.$headers['PHP_AUTH_PW']);
        }

        return $headers;
	}
}

?>