<?php
class ModelCatalogVendor extends Model {
	public function getVendor($vendor_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors v LEFT JOIN " . DB_PREFIX . "user u ON (v.user_id = u.user_id) WHERE vendor_id = '" . (int)$vendor_id . "' AND u.status = 1");

        if ($query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$query->row['country_id'] . "'");

			if ($country_query->num_rows) {
                $country_name = $country_query->row['name'];
			} else {
                $country_name = "";
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone_name = $zone_query->row['name'];
			} else {
				$zone_name = "";
			}
        }

		$results = $query->row;

		if ($results) {
            $results['country_name'] = $country_name;
            $results['zone_name'] = $zone_name;
			return $results;
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