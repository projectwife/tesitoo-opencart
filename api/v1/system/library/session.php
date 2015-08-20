<?php
class ApiSession extends Session {
	public $data = array();
			
	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->oauth = $registry->get('oauth');
		$this->request = $registry->get('request');

		if(!$this->request->isOAuthTokenRequest() && $this->oauth->isValid()) {
			session_set_save_handler(
			  	array($this, "open"),
			  	array($this, "close"),
			  	array($this, "read"),
			  	array($this, "write"),
			  	array($this, "destroy"),
			  	array($this, "gc")
			);

			if (!session_id()) {
				$accessToken = $this->oauth->getAccessToken();
				session_id($accessToken);
				
				// the following prevents unexpected effects when using objects as save handlers
				register_shutdown_function('session_write_close');

				session_start();
			}
				
			$this->data =& $_SESSION;
		}
		
	}

	public function writeToDefaultSession($session_name = 'PHPSESSID', $session_save_handler = 'files') {
        $session_id = session_id();
        
        // write and close current session
        session_write_close();
        
        // switch to files session handler
        ini_set('session.save_handler', $session_save_handler);
        
        // now we can switch the session over, capturing the old session name
        session_id($session_id);
        session_start();
        
        // write session data
        $data = $this->data;

		foreach($data as $key => $value) {
			$_SESSION[$key] = $value;
		}
	}

	public function open() {
		if($this->db) {
			return true;
		}

		return false;
	}

	public function close() {
		return true;
	}

	public function read($id) {
		$query = $this->db->query("SELECT data FROM " . DB_PREFIX . "api_session WHERE session_id = '" . $this->db->escape($id) . "'");

		if($query->row) {
			return $query->row['data'];
		}
		else {
			return '';
		}
	}

	public function write($id, $data) {
		$access = time();
		return $this->db->query("REPLACE INTO " . DB_PREFIX . "api_session VALUES ('" . $this->db->escape($id) . "', '" . $this->db->escape($access) . "', '" . $this->db->escape($data) . "')");
	}

	public function destroy($id = null) {
		if($id == null) {
			$id = session_id();
		}

		if($id != null && !empty($id)) {
			return $this->db->query("DELETE FROM " . DB_PREFIX . "api_session WHERE session_id = '" . $this->db->escape($id) . "'");
		}
		
		return false;
	}

	// Don't garbage collect since the client can use the access token later on and if he does the session could be destroyed.
	public function gc($max) {
		return true;
	//     $old = time() - $max;
	//   	return $this->db->query("DELETE * FROM " . DB_PREFIX . "api_session WHERE access < '" . $this->db->escape($old) . "'");
	}
}
?>