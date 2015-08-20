<?php
class ModelCatalogVDIVendorProfile extends Model {
	
	public function editVendorProfile($user_id, $data) {
		$sql = "UPDATE " . DB_PREFIX . "vendors SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', paypal_email = '" . $this->db->escape($data['paypal_email']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', iban = '" . $this->db->escape($data['iban']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_address = '" . $this->db->escape($data['bank_address']) . "', swift_bic = '" . $this->db->escape($data['swift_bic']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "',";
		
		if ($this->config->get('mvd_store_activated')) {
		$sql .= " accept_paypal = '" . $this->db->escape($data['accept_paypal']) . "', accept_cheques = '" . $this->db->escape($data['accept_cheques']) . "', accept_bank_transfer = '" . $this->db->escape($data['accept_bank_transfer']) . "',";
		}
		
		$sql .= " vendor_description = '" . $this->db->escape($data['vendor_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', store_url = '" . $this->db->escape($data['store_url']) . "' WHERE user_id = '" . (int)$user_id . "'";
		$query = $this->db->query($sql);
		
		if (isset($data['vendor_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "vendors SET vendor_image = '" . $this->db->escape($data['vendor_image']) . "' WHERE user_id = '" . (int)$user_id . "'");
		}

		$this->cache->delete('vendor');
	}
	
	public function getVendorProfile($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE user_id = '" . (int)$user_id . "'");
		return $query->row; 
	}
}
?>