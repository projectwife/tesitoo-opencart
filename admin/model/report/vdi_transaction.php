<?php
class ModelReportVDITransaction extends Model {
	public function getVendorOrders($data = array()) {
		$sql = "SELECT op.product_id AS product_id, o.date_added AS date, o.order_id AS order_id, op.order_status_id AS order_status, op.name AS product_name, op.price AS price,op.quantity AS quantity, op.commission AS commission,op.vendor_total AS amount, op.order_product_id as order_product_id, op.total AS total, op.vendor_id as vendor_id, op.vendor_paid_status AS paid_status,op.tax as tax, (op.tax*op.quantity) as total_tax, op.store_tax as store_tax, op.vendor_tax as vendor_tax,op.title as title FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE op.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE op.order_status_id > '0'";
		}

		$sql .= " AND op.vendor_id = '" . (int)$this->user->getVP() . "'";

		if (!empty($data['filter_paid_status'])) {
			$sql .= " AND op.vendor_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} elseif (!is_null($data['filter_paid_status']) && $data['filter_vendor_group']) {
			$sql .= " AND op.vendor_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} else {
			$sql .= " AND op.vendor_paid_status = '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= " ORDER BY o.order_id DESC";
		
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
	
	public function getVendorTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE op.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE op.order_status_id > '0'";
		}

		$sql .= " AND op.vendor_id = '" . (int)$this->user->getVP() . "'";

		if (!empty($data['filter_paid_status'])) {
			$sql .= " AND op.vendor_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} elseif (!is_null($data['filter_paid_status']) && $data['filter_vendor_group']) {
			$sql .= " AND op.vendor_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} else {
			$sql .= " AND op.vendor_paid_status = '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	public function getVendorTotalAmount($data = array()) {
		$sql = "SELECT SUM(op.vendor_total) AS vendor_amount, SUM(op.commission) AS commission, SUM(op.total) AS gross_amount,SUM(op.tax*op.quantity) as total_tax, SUM(op.store_tax) as total_store_tax, SUM(op.vendor_tax) as total_vendor_tax, op.vendor_id AS vendor_id, vds.company AS company, vds.paypal_email AS paypal_email FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) LEFT JOIN `" . DB_PREFIX . "vendors` vds ON (op.vendor_id = vds.vendor_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE op.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE op.order_status_id > '0'";
		}

		$sql .= " AND op.vendor_id = '" . (int)$this->user->getVP() . "'";
		
		if (!empty($data['filter_paid_status'])) {
			$sql .= " AND op.vendor_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} elseif (!is_null($data['filter_paid_status']) && $data['filter_vendor_group']) {
			$sql .= " AND op.vendor_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} else {
			$sql .= " AND op.vendor_paid_status = '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getShippingChargedTotal($data = array()) {
		$sql = "SELECT SUM(os.cost) AS shipping_charged, SUM(os.tax) as shipping_tax FROM `" . DB_PREFIX . "order_shipping` os LEFT JOIN `" . DB_PREFIX . "order` o ON (os.order_id = o.order_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}		
		
		$sql .= " AND os.vendor_id > '0' AND os.vendor_id = '" . (int)$this->user->getVP() . "'";
		
		if (!empty($data['filter_paid_status'])) {
			$sql .= " AND os.shipping_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} elseif (!is_null($data['filter_paid_status']) && $data['filter_vendor_group']) {
			$sql .= " AND os.shipping_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} else {
			$sql .= " AND os.shipping_paid_status = '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getCouponTotal($data = array()) {
		$sql = "SELECT SUM(vdsc.amount) AS discount_amount, SUM(vdsc.tax) as discount_tax FROM `" . DB_PREFIX . "vendor_discount` vdsc LEFT JOIN `" . DB_PREFIX . "order` o ON (vdsc.order_id = o.order_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		$sql .= " AND vdsc.vendor_id = '" . (int)$this->user->getVP() . "'";
		
		if (!empty($data['filter_paid_status'])) {
			$sql .= " AND vdsc.coupon_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} elseif (!is_null($data['filter_paid_status']) && $data['filter_vendor_group']) {
			$sql .= " AND vdsc.coupon_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} else {
			$sql .= " AND vdsc.coupon_paid_status = '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getHomeShipping($data = array()) {
		$query = $this->db->query("SELECT (SUM(os.cost)+SUM(os.tax)) AS total FROM `" . DB_PREFIX . "order_shipping` os LEFT JOIN `" . DB_PREFIX . "order` o ON (os.order_id = o.order_id) WHERE os.vendor_id = '" . (int)$this->user->getVP() . "'");
			
		return $query->row['total'];
	}

	public function getHomeShippingByYear($year) {
      	$query = $this->db->query("SELECT (SUM(os.cost)+SUM(os.tax)) AS total FROM `" . DB_PREFIX . "order_shipping` os LEFT JOIN `" . DB_PREFIX . "order` o ON (os.order_id = o.order_id) WHERE os.vendor_id = '" . (int)$this->user->getVP() . "' AND YEAR(o.date_added) = '" . (int)$year . "'");

		return $query->row['total'];
	}
	
	public function getVendorsName($data = array()) {
		$sql = "SELECT vendor_id AS vendor_id, vendor_name AS name FROM `" . DB_PREFIX . "vendors` v WHERE v.vendor_id = '" . (int)$this->user->getVP() . "'";

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getPaymentHistory($data = array()) {
		$sql = "SELECT v.vendor_name AS name, vp.payment_id AS payment_id, vp.payment_info AS details, vp.payment_type as payment_type, vp.payment_amount, vp.payment_date FROM `" . DB_PREFIX . "vendor_payment` vp LEFT JOIN `" . DB_PREFIX . "vendors` v ON (vp.vendor_id = v.vendor_id) ";

		if  ($this->user->getVP()) {
			$sql .= " WHERE v.vendor_id = '" . (int)$this->user->getVP() . "'";
		}
		
		$sql .= " ORDER BY vp.payment_date DESC LIMIT 10";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getCommissionData($data = array()) {
		$query = $this->db->query("SELECT vds.vendor_id, cm.commission_name, cm.commission, cm.commission_type FROM " . DB_PREFIX . "vendors vds LEFT JOIN " . DB_PREFIX . "commission cm ON (vds.commission_id = cm.commission_id)");
		return $query->rows;
	}
	
	public function getVendorPaypalEmail($vendor_id) {
		$query = $this->db->query("SELECT paypal_email FROM " . DB_PREFIX . "vendors WHERE vendor_id = '" . (int)$vendor_id . "'");
		if (isset($query->row['paypal_email'])) {
			return $query->row['paypal_email'];
		} else {
			return false;
		}
	}
	
	public function getVendorName($vendor_id) {
		$query = $this->db->query("SELECT vendor_name FROM `" . DB_PREFIX . "vendors` WHERE vendor_id = '" . (int)$vendor_id . "'");
		if (isset($query->row['vendor_name'])) {
			return $query->row['vendor_name'];
		} else {
			return false;
		}		
	}
}
?>