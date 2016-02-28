<?php
class User {
	private $user_id;
	private $username;
	private $permission = array();

			private $store_permission;
			private $cat_permission;
			private $vendor_permission;
			private $folder_path;
			private $account_expired;
			private $user_expire_date;
			

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['user_id'])) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['user_id'];
				$this->username = $user_query->row['username'];

			$this->store_permission = $user_query->row['store_permission'];
			$this->cat_permission = $user_query->row['cat_permission'];
			$this->vendor_permission = $user_query->row['vendor_permission'];
			$this->folder_path = $user_query->row['folder'];
			$this->user_expire_date = $user_query->row['user_date_end'];
				
			if((strtotime(date('Y-m-d')) >= strtotime($user_query->row['user_date_start'])) && (strtotime(date('Y-m-d')) <= strtotime($user_query->row['user_date_end'])) || (($user_query->row['user_date_start'] == '0000-00-00') && ($user_query->row['user_date_end'] == '0000-00-00'))) {
				$this->account_expired = false;
			} else {
				$this->account_expired = true;
			}
			
				$this->user_group_id = $user_query->row['user_group_id'];

				$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

				$permissions = json_decode($user_group_query->row['permission'], true);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($username, $password) {
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'");

		if ($user_query->num_rows) {
			$this->session->data['user_id'] = $user_query->row['user_id'];

			$this->user_id = $user_query->row['user_id'];
			$this->username = $user_query->row['username'];

			$this->store_permission = $user_query->row['store_permission'];
			$this->cat_permission = $user_query->row['cat_permission'];
			$this->vendor_permission = $user_query->row['vendor_permission'];
			$this->folder_path = $user_query->row['folder'];
			$this->user_expire_date = $user_query->row['user_date_end'];
				
			if((strtotime(date('Y-m-d')) >= strtotime($user_query->row['user_date_start'])) && (strtotime(date('Y-m-d')) <= strtotime($user_query->row['user_date_end'])) || (($user_query->row['user_date_start'] == '0000-00-00') && ($user_query->row['user_date_end'] == '0000-00-00'))) {
				$this->account_expired = false;
			} else {
				$this->account_expired = true;
			}
			
			$this->user_group_id = $user_query->row['user_group_id'];

			$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

			$permissions = json_decode($user_group_query->row['permission'], true);

			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}

			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['user_id']);

		$this->user_id = '';
		$this->username = '';

			$this->store_permission = '';
			$this->cat_permission = '';
			$this->vendor_permission = '';
			$this->folder_path = '';
			$this->user_expire_date = '';
			$this->account_expired = false;
			
	}

	public function hasPermission($key, $value) {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}

			public function getSP() {
				return $this->store_permission;
			}
					
			public function getCP() {
				return $this->cat_permission;
			}
					
			public function getVP() {
				return $this->vendor_permission;
			}
					
			public function getStorePath() {
				return $this->folder_path;
			}
					
			public function getExpireDate() {
				return $this->user_expire_date;
			}
					
			public function IsAccountExpired() {
				return $this->account_expired;
			}
			

	public function getGroupId() {
		return $this->user_group_id;
	}
}