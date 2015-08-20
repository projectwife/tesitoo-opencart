<?php
class ModelApiOAuth extends Model {
	public function getOAuthClients($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "api_oauth_client";

		$sql .= " ORDER BY name";	

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getOAuthClient($oauth_client_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "api_oauth_client WHERE oauth_client_id = '" . (int)$oauth_client_id . "'");

		$oauth_client = array(
			'name'       	=> $query->row['name'],
			'client_id'  	=> $query->row['client_id'],
			'client_secret' => $query->row['client_secret']
		);

		return $oauth_client;
	}

	public function addOAuthClient($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "api_oauth_client SET name = '" . $this->db->escape($data['name']) . "', client_id = '" . $this->db->escape($data['client_id']) . "', client_secret = '" . $this->db->escape($data['client_secret']) . "'");
	}

	public function editOAuthClient($oauth_client_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "api_oauth_client SET name = '" . $this->db->escape($data['name']) . "', client_id = '" . $this->db->escape($data['client_id']) . "', client_secret = '" . $this->db->escape($data['client_secret']) . "' WHERE oauth_client_id = '" . (int)$oauth_client_id . "'");
	}

	public function deleteOAuthClient($oauth_client_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "api_oauth_client WHERE oauth_client_id = '" . (int)$oauth_client_id . "'");
	}

	public function getTotalOAuthClients() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "api_oauth_client");

		return $query->row['total'];
	}

	public function getTotalOAuthClientsByClientIdAndClientSecret($client_id, $client_secret) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "api_oauth_client WHERE client_id = '" . $this->db->escape($client_id) . "' AND client_secret = '" . $this->db->escape($client_secret) . "'");

		return $query->row['total'];
	}
}

?>