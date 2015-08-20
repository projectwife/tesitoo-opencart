<?php
class ModelCatalogVendor extends Model {
	public function addVendor($data) {
		if ($data['username1'] && empty($data['user_id'])) {
			$sql = "INSERT INTO " . DB_PREFIX . "vendors SET vendor_name = '" . $this->db->escape($data['vendor_name']) . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', paypal_email = '" . $this->db->escape($data['paypal_email']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', iban = '" . $this->db->escape($data['iban']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_address = '" . $this->db->escape($data['bank_address']) . "', swift_bic = '" . $this->db->escape($data['swift_bic']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "',";	
			
			if ($this->config->get('mvd_store_activated')) {
			$sql .= " accept_paypal = '" . $this->db->escape($data['accept_paypal']) . "', accept_cheques = '" . $this->db->escape($data['accept_cheques']) . "', accept_bank_transfer = '" . $this->db->escape($data['accept_bank_transfer']) . "',";
			}	
			
			$sql .= " vendor_description = '" . $this->db->escape($data['vendor_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', commission_id = '" . (int)$data['commission'] . "', product_limit_id = '" . (int)$data['product_limit'] . "', store_url = '" . $this->db->escape($data['store_url']) . "', sort_order = '" . (int)$data['sort_order'] . "'";		
			$query = $this->db->query($sql);
			
			$vendor_id = $this->db->getLastId();

			if (isset($data['vendor_image'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "vendors SET vendor_image = '" . $this->db->escape($data['vendor_image']) . "' WHERE vendor_id = '" . (int)$vendor_id . "'");
			}
			
			$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username1']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['vendor_image']) . "', folder = '" . (isset($this->request->post['generate_path']) ? $this->db->escape($data['username1']) : '') . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
			
			$user_id = $this->db->getLastId();
			
			$this->db->query("UPDATE " . DB_PREFIX . "vendors SET user_id = '" . (int)$user_id . "' WHERE vendor_id = '" . (int)$vendor_id . "'");
		} elseif (empty($data['username1']) && !empty($data['user_id'])) {
		
			$sql = "INSERT INTO " . DB_PREFIX . "vendors SET user_id = '" . (int)$this->db->escape($data['user_id']) . "', vendor_name = '" . $this->db->escape($data['vendor_name']) . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', paypal_email = '" . $this->db->escape($data['paypal_email']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', iban = '" . $this->db->escape($data['iban']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_address = '" . $this->db->escape($data['bank_address']) . "', swift_bic = '" . $this->db->escape($data['swift_bic']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "',";	
			
			if ($this->config->get('mvd_store_activated')) {
			$sql .= " accept_paypal = '" . $this->db->escape($data['accept_paypal']) . "', accept_cheques = '" . $this->db->escape($data['accept_cheques']) . "', accept_bank_transfer = '" . $this->db->escape($data['accept_bank_transfer']) . "',";
			}	
			
			$sql .= " vendor_description = '" . $this->db->escape($data['vendor_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', commission_id = '" . (int)$data['commission'] . "', product_limit_id = '" . (int)$data['product_limit'] . "', store_url = '" . $this->db->escape($data['store_url']) . "', sort_order = '" . (int)$data['sort_order'] . "'";
			$query = $this->db->query($sql);
			
			$vendor_id = $this->db->getLastId();

			if (isset($data['vendor_image'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "vendors SET vendor_image = '" . $this->db->escape($data['vendor_image']) . "' WHERE vendor_id = '" . (int)$vendor_id . "'");
			}
			
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET user_group_id = '" . (int)$data['user_group_id'] . "', folder = '" . (isset($this->request->post['generate_path']) ? $this->db->escape($data['username']) : '') . "', vendor_permission = '" . (int)$vendor_id . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$this->db->escape($data['user_id']) . "'");
			
			if ($data['password']) {
				$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$this->db->escape($data['user_id']) . "'");
			}
		}
		
		if ($data['email']) {
			$this->language->load('mail/signup');
			$mdata['title'] = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			
			$mdata['text_message'] = sprintf($this->language->get('text_dear'), $data['firstname'] . ' ' . $data['lastname']) . ",<br /><br />";			
			$mdata['text_message'] .= sprintf($this->language->get('text_welcome1'), $this->config->get('config_name')) . "<br /><br />";
			$mdata['text_message'] .= $this->language->get('text_login') . "<br /><br />";
			
			if ($data['password']) {
				$mdata['text_message'] .= $this->language->get('text_info') . "<br />";
				
				if ($data['username']) {
					$username = $data['username'];
				} else {
					$username = $data['username1'];
				}
				
				$mdata['text_message'] .= $this->language->get('text_username') . $username . "<br />";
				$mdata['text_message'] .= $this->language->get('text_password') . $data['password'] . "<br />";
			}
			
			$mdata['text_message'] .= $this->language->get('text_url') . HTTP_SERVER . "<br /><br />";
			$mdata['text_message'] .= $this->language->get('text_services') . "<br /><br />";

			$mdata['text_message'] .= $this->language->get('text_thanks') . "<br />";
			$mdata['text_message'] .= $this->config->get('config_name');
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
							
			$mail->setTo($data['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($mdata['title'], ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($this->load->view('mail/signup_notification.tpl', $mdata));
			$mail->send();
		}
	}

	public function editVendor($vendor_id, $data) {
		$sql = "UPDATE " . DB_PREFIX . "vendors SET user_id = '" . (int)$this->db->escape($data['user_id']) . "', vendor_name = '" . $this->db->escape($data['vendor_name']) . "', company = '" . $this->db->escape($data['company']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', email = '" . $this->db->escape($data['email']) . "', paypal_email = '" . $this->db->escape($data['paypal_email']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', iban = '" . $this->db->escape($data['iban']) . "', bank_name = '" . $this->db->escape($data['bank_name']) . "', bank_address = '" . $this->db->escape($data['bank_address']) . "', swift_bic = '" . $this->db->escape($data['swift_bic']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "',";
		
		if ($this->config->get('mvd_store_activated')) {
		$sql .= " accept_paypal = '" . $this->db->escape($data['accept_paypal']) . "', accept_cheques = '" . $this->db->escape($data['accept_cheques']) . "', accept_bank_transfer = '" . $this->db->escape($data['accept_bank_transfer']) . "',";
		}
		
		$sql .= " vendor_description = '" . $this->db->escape($data['vendor_description']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', commission_id = '" . (int)$data['commission'] . "', product_limit_id = '" . (int)$data['product_limit'] . "', store_url = '" . $this->db->escape($data['store_url']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE vendor_id = '" . (int)$vendor_id . "'";
		$query = $this->db->query($sql);
		
		if (isset($data['vendor_image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "vendors SET vendor_image = '" . $this->db->escape($data['vendor_image']) . "' WHERE vendor_id = '" . (int)$vendor_id . "'");
		}
		
		//user account start
		if (isset($this->request->post['generate_path'])) {
		  $this->db->query("UPDATE `" . DB_PREFIX . "user` SET user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['vendor_image']) . "', status = '" . (int)$data['status'] . "', folder = '" . (isset($this->request->post['generate_path']) ? $this->db->escape($data['username']) : '') . "', vendor_permission = '" . (int)$data['vendor_product'] . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "' WHERE user_id = '" . (int)$this->db->escape($data['user_id']) . "'");
		} elseif (isset($this->request->post['remove_path'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['vendor_image']) . "', status = '" . (int)$data['status'] . "', folder = '" . (isset($this->request->post['remove_path']) ? '' : $this->db->escape($data['username'])) . "', vendor_permission = '" . (int)$data['vendor_product'] . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "' WHERE user_id = '" . (int)$this->db->escape($data['user_id']) . "'");		
		} else {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['vendor_image']) . "', status = '" . (int)$data['status'] . "', vendor_permission = '" . (int)$data['vendor_product'] . "', cat_permission = '" . (isset($data['vendor_category']) ? serialize($data['vendor_category']) : '') . "', store_permission = '" . (isset($data['product_store']) ? serialize($data['product_store']) : '') . "', user_date_start = '" . $this->db->escape($data['user_date_start']) . "', user_date_end = '" . $this->db->escape($data['user_date_end']) . "' WHERE user_id = '" . (int)$this->db->escape($data['user_id']) . "'");
		}
		
		if (($data['status'] == '1') && ($data['pending_status'] == '5')) {
			$this->language->load('mail/signup');
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
					
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_login') . "\n";
					
			$message .= HTTP_SERVER . "\n\n";
			$message .= $this->language->get('text_services') . "\n\n";
			$message .= $this->language->get('text_thanks') . "\n";
			$message .= $this->config->get('config_name');
	
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
							
			$mail->setTo($data['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
		
		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$this->db->escape($data['user_id']) . "'");
		}
		//user account end

		$this->cache->delete('vendor');
	}
	
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
 
	public function deleteVendor($vendor_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "vendors WHERE vendor_id = '" . (int)$vendor_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "user WHERE vendor_permission = '" . (int)$vendor_id . "'");
	}
	
	public function getCommissionLimits() {
		$commission_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission c LEFT JOIN " . DB_PREFIX . "product_limit pc ON (c.product_limit_id = pc.product_limit_id)");
		if ($query->rows) {
			$commission_text = '';
			foreach ($query->rows as $commission) {		
				if ($commission['commission_type'] == '0') {
					$commission_text = $commission['commission_name'] . ' (' . $commission['commission'] . '%) - ' . $commission['product_limit'];
				} elseif ($commission['commission_type'] == '1') { 
					$commission_text = $commission['commission_name'] . ' (' . $this->currency->format($commission['commission'], $this->config->get('config_currency')) . ') - ' . $commission['product_limit'];
				} elseif ($commission['commission_type'] == '2') {
					$dc = explode(':',$commission['commission']);
					$commission_text = $commission['commission_name'] . ' (' . $dc[0] . '% + ' . $dc[1] . ') - ' . $commission['product_limit'];
				} elseif ($commission['commission_type'] == '3') {
					$dc = explode(':',$commission['commission']);
					$commission_text = $commission['commission_name'] . ' (' . $dc[0] . ' + ' . $dc[1] . '%) - ' . $commission['product_limit'];
				} elseif ($commission['commission_type'] == '4') {
					$commission_text = $commission['commission_name'] . ' (' . $this->currency->format($commission['commission'], $this->config->get('config_currency')) . ') - ' . $commission['product_limit'];
				} elseif ($commission['commission_type'] == '5') { 
					$commission_text = $commission['commission_name'] . ' (' . $this->currency->format($commission['commission'], $this->config->get('config_currency')) . ') - ' . $commission['product_limit'];
				}
				
				$commission_data[] = array (
					'commission_id'		=>	$commission['commission_id'],
					'product_limit_id'	=>	$commission['product_limit_id'],
					'commission_name'	=>	$commission['commission_name'],
					'commission_type'	=>	$commission['commission_type'],
					'commission'		=>	$commission['commission'],
					'duration'			=>	$commission['duration'],
					'package_name'		=>	$commission['package_name'],
					'product_limit'		=>	$commission['product_limit'],
					'commission_text'	=>  $commission_text,
					'sort_order'		=>	$commission['sort_order'],
					'date_add'			=>	$commission['date_add']
				);
			}
			return $commission_data;
		} else {
			return false; 
		}
	}
	
	public function getVendor($vendor_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE vendor_id = '" . (int)$vendor_id . "'");
		if ($query->row) {
			return $query->row;
		} else {
			false;
		}
	}
	
	public function getVendorProfile($user_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendors WHERE user_id = '" . (int)$user_id . "'");
		return $query->row; 
	}
	
	public function getVendors($data = array()) {
		
		if ($data) {
			$sql = "SELECT *,v.commission_id AS commission_id, c.commission AS commission,v.sort_order as vsort_order FROM " . DB_PREFIX . "vendors v LEFT JOIN " . DB_PREFIX . "commission c ON (v.commission_id = c.commission_id)";
			$sql .= " LEFT JOIN " . DB_PREFIX . "user u ON (v.user_id = u.user_id)";
			
			if (isset($this->request->get['filter_status']) && !is_null($this->request->get['filter_status'])) {
				$sql .= " WHERE u.status = '" . (int)$this->request->get['filter_status'] . "'";
			}
			
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
		} else {
			
			if (!$vendors_data) {
				$query = $this->db->query("SELECT *,v.commission_id AS commission_id, c.commission AS commission,v.sort_order as sort_order FROM " . DB_PREFIX . "vendors v LEFT JOIN " . DB_PREFIX . "commission c ON (v.commission_id = c.commission_id) LEFT JOIN " . DB_PREFIX . "user u ON (v.user_id = u.user_id)");
			
				$vendors_data = $query->rows;

				$this->cache->set('vendor', $vendors_data);
			}

			return $vendors_data;
		}
	}
	public function getTotalVendorsByVendorId($vendors) {
      	$query = $this->db->query("SELECT COUNT(vendor) AS total FROM " . DB_PREFIX . "vendor WHERE vendor = '" . (int)$vendors . "'");
		return $query->row['total'];
	}

	public function getTotalVendors($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendors";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getByCountryZone($country_id,$zone_id) {
		$query = $this->db->query("SELECT zone FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND zone_id = '" . (int)$zone_id . "'");
		
		return $query->row;
	}
	
	public function getVendorsList($data = array()) {
		$sql = "SELECT vendor_id,vendor_name as name FROM " . DB_PREFIX . "vendors ORDER BY vendor_id" ;					
		$query = $this->db->query($sql);				
		return $query->rows;
	}
			
	Public function getUserAwaitingApproval($data = array()) {
		$query = $this->db->query("SELECT count(*) AS total FROM " . DB_PREFIX . "user u WHERE u.status = '5'");				
		return $query->row['total'];
	}
			
	Public function DisabledAllProducts($user_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vds ON (v.vendor = vds.vendor_id) LEFT JOIN " . DB_PREFIX . "user u ON (vds.user_id = u.user_id) SET p.status = '0' WHERE u.user_id = '" . (int)$this->db->escape($user_id) . "' AND p.status = '1'");
	}
			
	Public function EnabledAllProducts($user_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) LEFT JOIN " . DB_PREFIX . "vendors vds ON (v.vendor = vds.vendor_id) LEFT JOIN " . DB_PREFIX . "user u ON (vds.user_id = u.user_id) SET p.status = '1' WHERE u.user_id = '" . (int)$this->db->escape($user_id) . "' AND p.status = '0'");
	}
	
	public function getUsers($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user`";
		
		//mvds
		if (isset($this->request->get['filter_status']) && !is_null($this->request->get['filter_status'])) {
			$sql .= " WHERE status = '" . (int)$this->request->get['filter_status'] . "'";
		}
		//mvde
		
		$sort_data = array(
			'username',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY username";
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

	public function getTotalUsers() {
		//$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");
		//mvds
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`";
		if (isset($this->request->get['filter_status']) && !is_null($this->request->get['filter_status'])) {
			$sql .= " WHERE status = '" . (int)$this->request->get['filter_status'] . "'";
		}
		$query = $this->db->query($sql);
		//mvde
		return $query->row['total'];
	}
	
	public function ValidateUserMapping($user_id) {		
		$query = $this->db->query("SELECT count(*) AS total FROM " . DB_PREFIX . "vendors WHERE user_id = '" . (int)$user_id . "'");				
		return $query->row['total'];
	}
}
?>