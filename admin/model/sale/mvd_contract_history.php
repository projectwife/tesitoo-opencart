<?php
class ModelSaleMVDContractHistory extends Model {
	
	public function getContractHistory($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "signup_fee_history`";
		
		if ($this->user->getVP()) {
			$sql .= " WHERE user_id = '" . (int)$this->user->getId() . "'";
		}
		
		$sql .= " ORDER BY signup_fee_id DESC";
		
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
	
	public function getTotalContractHistory($data = array()) {
		$sql = "SELECT count(*) as total FROM `" . DB_PREFIX . "signup_fee_history`";
		
		if ($this->user->getVP()) {
			$sql .= " WHERE user_id = '" . (int)$this->user->getId() . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
}
?>