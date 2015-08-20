<?php
class ModelCatalogCommission extends Model {
	public function addCommission($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "commission SET commission_name = '" . $this->db->escape($data['commission_name']) . "', commission_type = '" . (int)$this->db->escape($data['commission_type']) . "', commission = '" . $this->db->escape($data['commission']) . "', duration = '" . (int)$this->db->escape($data['duration']) . "', product_limit_id = '" . (int)$this->db->escape($data['product_limit_id']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$this->cache->delete('commission');
	}

	public function editCommission($commission_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "commission SET commission_name = '" . $this->db->escape($data['commission_name']) . "', commission_type = '" . (int)$this->db->escape($data['commission_type']) . "', commission = '" . $this->db->escape($data['commission']) . "', duration = '" . (int)$this->db->escape($data['duration']) . "', product_limit_id = '" . (int)$this->db->escape($data['product_limit_id']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE commission_id = '" . (int)$commission_id . "'");

		$this->cache->delete('commission');
	}

	public function deleteCommission($commission_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "commission WHERE commission_id = '" . (int)$commission_id . "'");
		$this->cache->delete('commission');
	}
	
	public function getCommission($commission_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission WHERE commission_id = '" . (int)$commission_id . "'");
		return $query->row;
	}
	
	public function getCommissions($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "commission";
			$sort_data = array(
				'commission_name',
				'commission_type',
				'commission',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY commission_name";	
			}
		
			
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
			
		} else {
			$commission_data = $this->cache->get('commission');
			if (!$commission_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission ORDER BY commission_id");
				$commission_data = $query->rows;
				$this->cache->set('commission', $commission_data);
			}
			return $commission_data;
		}
	}
	public function getTotalVendorsByCommissionId($commission_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendors WHERE commission_id = '" . (int)$commission_id . "'");

		return $query->row['total'];
	}

	public function getTotalCommissions($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "commission";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
}
?>