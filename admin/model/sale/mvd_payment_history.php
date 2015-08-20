<?php
class ModelSaleMVDPaymentHistory extends Model {
	
	public function getPaymentHistory($data = array()) {
		$sql = "SELECT v.vendor_name AS name, vp.payment_id AS payment_id, vp.payment_info AS details, vp.payment_amount, vp.payment_date FROM `" . DB_PREFIX . "vendor_payment` vp LEFT JOIN `" . DB_PREFIX . "vendors` v ON (vp.vendor_id = v.vendor_id) ORDER BY vp.payment_date DESC ";
		
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
	
	public function getTotalPaymentHistory($data = array()) {
		$query = $this->db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "vendor_payment` vp LEFT JOIN `" . DB_PREFIX . "vendors` v ON (vp.vendor_id = v.vendor_id)");
		
		return $query->row['total'];
	}
}
?>