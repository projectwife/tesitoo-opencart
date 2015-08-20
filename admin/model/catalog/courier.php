<?php
class ModelCatalogCourier extends Model {
	public function addCourier($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "courier SET courier_name = '" . $this->db->escape($data['courier_name']) . "', description = '" . $this->db->escape($data['description']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$courier_id = $this->db->getLastId();

		if (isset($data['courier_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "courier SET courier_image = '" . $this->db->escape($data['courier_image']) . "' WHERE courier_id = '" . (int)$courier_id . "'");
		}
		
		$this->cache->delete('courier');
	}

	public function editCourier($courier_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "courier SET courier_name = '" . $this->db->escape($data['courier_name']) . "', description = '" . $this->db->escape($data['description']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE courier_id = '" . (int)$courier_id . "'");

		if (isset($data['courier_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "courier SET courier_image = '" . $this->db->escape($data['courier_image']) . "' WHERE courier_id = '" . (int)$courier_id . "'");
		}

		$this->cache->delete('courier');
	}

	public function deleteCourier($courier_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "courier WHERE courier_id = '" . (int)$courier_id . "'");
		$this->cache->delete('courier');
	}
	
	public function getCourier($courier_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier WHERE courier_id = '" . (int)$courier_id . "'");
		return $query->row;
	}
	
	public function getCouriers($data = array()) {
		if ($data) {
			
			$sql = "SELECT * FROM " . DB_PREFIX . "courier";
			
			$sort_data = array(
				'courier_name',
				'description',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY courier_name";	
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
			
			if (!$couriers_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier ORDER BY courier_name");

				$couriers_data = $query->rows;

				$this->cache->set('courier', $couriers_data);
			}

			return $couriers_data;
		}
	}
	public function getTotalCouriersByCourierId($courier) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendor WHERE shipping_method = '" . (int)$courier . "' OR prefered_shipping = '" . (int)$courier . "'");

		return $query->row['total'];
	}

	public function getTotalCouriers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "courier";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
}
?>