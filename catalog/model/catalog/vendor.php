<?php
class ModelCatalogVendor extends Model {
	public function getVendor($vendor_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors v LEFT JOIN " . DB_PREFIX . "user u ON (v.user_id = u.user_id) WHERE vendor_id = '" . (int)$vendor_id . "' AND u.status = 1");

		if ($query->row) {
			return $query->row;
		} else {
			false;
		}
	}

	public function getVendors($data = array()) {

		if(!is_array($data)){
			return;
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "vendors v LEFT JOIN " . DB_PREFIX . "user u ON (v.user_id = u.user_id) WHERE u.status = 1";

		$sort_data = array(
			'vendor_name',
			'commission',
			'status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY vendor_name";
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
	}
}