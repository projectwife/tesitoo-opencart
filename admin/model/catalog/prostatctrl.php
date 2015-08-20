<?php
class ModelCatalogProStatCtrl extends Model {

	public function getUserInformation($data = array()) {
		if ($data) {
			$sql = "SELECT *, CONCAT(vds.firstname, ' ', vds.lastname) AS flname FROM " . DB_PREFIX . "user u LEFT JOIN " . DB_PREFIX . "vendors vds ON (u.user_id = vds.user_id) WHERE u.user_group_id = '50' ORDER BY u.user_id DESC";

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
	}
	
	public function getTotalProductsByUserID($user_id) {
		$query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "vendor v LEFT JOIN " . DB_PREFIX . "vendors vds ON (v.vendor = vds.vendor_id) LEFT JOIN " . DB_PREFIX . "user u ON (vds.user_id = u.user_id) WHERE u.user_id  = '" . (int)$user_id . "' AND u.user_group_id = '50'");
		return $query->row['total'];
	}
	
	public function getTotalUsers($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user WHERE user_group_id = '50'");
		return $query->row['total'];
	}
	
	Public function DisabledAllProducts($user_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vds ON (v.vendor = vds.vendor_id) LEFT JOIN " . DB_PREFIX . "user u ON (vds.user_id = u.user_id) SET p.status = '0' WHERE u.user_id = '" . (int)$this->db->escape($user_id) . "' AND p.status = '1'");
		$this->db->query("UPDATE " . DB_PREFIX . "user set status = '0' WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");		
	}
			
	Public function EnabledAllProducts($user_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vds ON (v.vendor = vds.vendor_id) LEFT JOIN " . DB_PREFIX . "user u ON (vds.user_id = u.user_id) SET p.status = '1' WHERE u.user_id = '" . (int)$this->db->escape($user_id) . "' AND p.status = '0'");
		$this->db->query("UPDATE " . DB_PREFIX . "user set status = '1' WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");
	}	
}
?>