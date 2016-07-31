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

	public function editVendor($vendor) {
		if(!is_array($vendor)){
			return;
		}

        $this->db->query("UPDATE " . DB_PREFIX . "vendors SET " .
        "vendor_name = '" . $this->db->escape($vendor['vendor_name']) . "', " .
        "company = '" . $this->db->escape($vendor['company']) . "', " .
        "vendor_description = '" . $this->db->escape($vendor['vendor_description']) . "', " .
        "telephone = '" . $this->db->escape($vendor['telephone']) . "', " .
        "address_1 = '" . $this->db->escape($vendor['address_1']) . "', " .
        "address_2 = '" . $this->db->escape($vendor['address_2']) . "', " .
        "city = '" . $this->db->escape($vendor['city']) . "', " .
        "postcode = '" . $this->db->escape($vendor['postcode']) . "', " .
        "country_id = '" . (int)($vendor['country_id']) . "', " .
        "zone_id = '" . (int)($vendor['zone_id']) . "' " .
        "WHERE vendor_id = '" . (int)($vendor['vendor_id']) . "'");

        $this->db->query("UPDATE " . DB_PREFIX . "user SET " .
        "email = '" . $this->db->escape($vendor['email']) . "', " .
        "firstname = '" . $this->db->escape($vendor['firstname']) . "', " .
        "lastname = '" . $this->db->escape($vendor['lastname']) . "' " .
        "WHERE user_id = '" . (int)($vendor['user_id']) . "'");
	}
}
