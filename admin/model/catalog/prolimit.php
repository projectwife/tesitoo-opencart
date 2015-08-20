<?php
class ModelCatalogProLimit extends Model {
	public function addLimit($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_limit SET package_name = '" . $this->db->escape($data['package_name']) . "', product_limit = '" . (int)$data['product_limit'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$this->cache->delete('prolimit');
	}

	public function editLimit($product_limit_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product_limit SET package_name = '" . $this->db->escape($data['package_name']) . "', product_limit = '" . (int)$data['product_limit'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE product_limit_id = '" . (int)$product_limit_id . "'");

		$this->cache->delete('prolimit');
	}

	public function deleteLimit($product_limit_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_limit WHERE product_limit_id = '" . (int)$product_limit_id . "'");
		$this->cache->delete('prolimit');
	}
	
	public function getLimit($product_limit_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_limit WHERE product_limit_id = '" . (int)$product_limit_id . "'");
		return $query->row;
	}
	
	public function getLimits($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "product_limit";
			$sort_data = array(
				'package_name',
				'product_limit',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sort_order";	
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
			$prolimit_data = $this->cache->get('prolimit');
			if (!$prolimit_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_limit ORDER BY product_limit_id");
				$prolimit_data = $query->rows;
				$this->cache->set('prolimit', $prolimit_data);
			}
			return $prolimit_data;
		}
	}
	
	public function getTotalVendorsByLimitId($product_limit_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendors WHERE product_limit_id = '" . (int)$product_limit_id . "'");

		return $query->row['total'];
	}

	public function getTotalLimits($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_limit";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getTotalProducts() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) WHERE v.vendor = '" . $this->user->getVP() . "'";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getAssignLimit() {
		$sql = "SELECT pl.product_limit as total FROM " . DB_PREFIX . "product_limit pl LEFT JOIN " . DB_PREFIX . "vendors vd ON (pl.product_limit_id = vd.product_limit_id) WHERE vd.vendor_id = '" . $this->user->getVP() . "'";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
}
?>