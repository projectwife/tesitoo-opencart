<?php
class ModelSaleContStatCtrl extends Model {
	
	public function getSignUpHistory($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "signup_fee_history` ORDER BY date_added DESC";
		
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
	
	public function getTotalSignUpHistory($data = array()) {
		$query = $this->db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "signup_fee_history`");		
		return $query->row['total'];
	}
	
	public function getTotalPaymentHistory($data = array()) {
		$query = $this->db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "vendor_payment` vp LEFT JOIN `" . DB_PREFIX . "vendors` v ON (vp.vendor_id = v.vendor_id)");
		
		return $query->row['total'];
	}
	
	public function UpdatePaidStatus($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "signup_fee_history SET paid_status = '" . (int)$this->db->escape($data['status']) . "', status = '" . (int)$this->db->escape($data['status']) . "' WHERE signup_fee_id = '" . (int)$this->db->escape($data['signup_fee_id']) . "'");
		$this->db->query("UPDATE " . DB_PREFIX . "user SET status = '" . (int)$this->db->escape($data['status']) . "', user_date_end = '" . $this->db->escape($data['date_end']) . "' WHERE user_id = '" . (int)$this->db->escape($data['user_id']) . "'");
	
	}
	
	public function DeleteSignUPHistory($signup_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "signup_fee_history WHERE signup_fee_id = '" . (int)$this->db->escape($signup_id) . "'");
	}
	
	public function getVendorData($user_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "vendors` WHERE user_id = '" . (int)$this->db->escape($user_id) . "'");		
		return $query->row;
	}
}
?>