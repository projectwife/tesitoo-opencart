<?php
class ModelSaleVDIContractHistory extends Model {
	
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
		
	public function getTotalPaymentHistory($data = array()) {
		$query = $this->db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "vendor_payment` vp LEFT JOIN `" . DB_PREFIX . "vendors` v ON (vp.vendor_id = v.vendor_id)");
		
		return $query->row['total'];
	}
	
	public function UpdatePaidStatus($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "signup_fee_history SET paid_status = '" . (int)$this->db->escape($data['status']) . "', status = '" . (int)$this->db->escape($data['status']) . "' WHERE signup_fee_id = '" . (int)$this->db->escape($data['signup_fee_id']) . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '" . (int)$this->db->escape($data['status']) . "' WHERE user_id = '" . (int)$this->db->escape($data['user_id']) . "'");
	}
	
	public function DeleteSignUPHistory($signup_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "signup_fee_history WHERE signup_fee_id = '" . (int)$this->db->escape($signup_id) . "'");
	}
	
	public function getRenewContract() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors vds LEFT JOIN " . DB_PREFIX . "user u ON (vds.user_id = u.user_id) WHERE vds.user_id = '" . (int)$this->user->getId(). "'");
		if ($query->row) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function getCommissionRate($commission_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission WHERE commission_id = '" . (int)$commission_id. "'");
		if ($query->row) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function addRenewContract($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "signup_fee_history` SET user_id = '" . (int)$this->db->escape($data['user_id']) . "', signup_fee = '" . (float)$this->db->escape($data['signup_fee']) . "', signup_plan = '" . $this->db->escape($data['signup_plan']) . "', vendor_name = '" . $this->db->escape($data['vendor_name']) . "', username = '" . $this->db->escape($data['username']) . "', commission_type = '" . (int)$this->db->escape($data['commission_type']) . "', status = '5', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "', date_added = NOW()");		
	}
}
?>