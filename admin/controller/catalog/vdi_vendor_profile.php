<?php
class ControllerCatalogVDIVendorProfile extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/vdi_vendor_profile');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vdi_vendor_profile');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vdi_vendor_profile->editVendorProfile($this->user->getId(), $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_profile');
			$this->response->redirect($this->url->link('catalog/vdi_vendor_profile', 'token=' . $this->session->data['token'], 'SSL'));

		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = $this->language->get('text_edit');
    	$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['help_email'] = $this->language->get('help_email');
		$data['help_paypal_email'] = $this->language->get('help_paypal_email');
		$data['help_image'] = $this->language->get('help_image');
		
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
	
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_paypal_email'] = $this->language->get('entry_paypal_email');
		$data['entry_company_id'] = $this->language->get('entry_company_id');
		$data['entry_iban'] = $this->language->get('entry_iban');
		$data['entry_bank_name'] = $this->language->get('entry_bank_name');
		$data['entry_bank_addr'] = $this->language->get('entry_bank_addr');
		$data['entry_swift_bic'] = $this->language->get('entry_swift_bic');
		$data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$data['entry_bank_info'] = $this->language->get('entry_bank_info');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_store_url'] = $this->language->get('entry_store_url');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_accept_paypal'] = $this->language->get('entry_accept_paypal');
		$data['entry_accept_bank_transfer'] = $this->language->get('entry_accept_bank_transfer');
		$data['entry_accept_cheques'] = $this->language->get('entry_accept_cheques');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_finance'] = $this->language->get('tab_finance');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_address'] = $this->language->get('tab_address');
		
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		
    	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
  		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['email'])) {
			$data['error_vendor_email'] = $this->error['email'];
		} else {
			$data['error_vendor_email'] = '';
		}
		
		if (isset($this->error['paypal_email'])) {
			$data['error_vendor_paypal_email'] = $this->error['paypal_email'];
		} else {
			$data['error_vendor_paypal_email'] = '';
		}
			
		if (isset($this->error['firstname'])) {
			$data['error_vendor_firstname'] = $this->error['firstname'];
		} else {
			$data['error_vendor_firstname'] = '';
		}	
		
		if (isset($this->error['lastname'])) {
			$data['error_vendor_lastname'] = $this->error['lastname'];
		} else {
			$data['error_vendor_lastname'] = '';
		}		
	
		if (isset($this->error['telephone'])) {
			$data['error_vendor_telephone'] = $this->error['telephone'];
		} else {
			$data['error_vendor_telephone'] = '';
		}
		
  		if (isset($this->error['address_1'])) {
			$data['error_vendor_address_1'] = $this->error['address_1'];
		} else {
			$data['error_vendor_address_1'] = '';
		}
   		
		if (isset($this->error['city'])) {
			$data['error_vendor_city'] = $this->error['city'];
		} else {
			$data['error_vendor_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$data['error_vendor_postcode'] = $this->error['postcode'];
		} else {
			$data['error_vendor_postcode'] = '';
		}
		
		if (isset($this->error['country'])) {
			$data['error_vendor_country'] = $this->error['country'];
		} else {
			$data['error_vendor_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_vendor_zone'] = $this->error['zone'];
		} else {
			$data['error_vendor_zone'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/vdi_dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/vdi_vendor_profile', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('catalog/vdi_vendor_profile', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];
		
		$vendors_info = $this->model_catalog_vdi_vendor_profile->getVendorProfile($this->user->getId());
		
		if ($this->config->get('mvd_store_activated')) {
			$data['mvd_store_activated'] = true;
		} else {
			$data['mvd_store_activated'] = false;
		}
		
		if (isset($this->request->post['firstname'])) {
      		$data['firstname'] = $this->request->post['firstname'];
    	} elseif (isset($vendors_info)) {
			$data['firstname'] = $vendors_info['firstname'];
		} else {	
      		$data['firstname'] = '';
    	}

		if (isset($this->request->post['lastname'])) {
      		$data['lastname'] = $this->request->post['lastname'];
    	} elseif (isset($vendors_info)) {
			$data['lastname'] = $vendors_info['lastname'];
		} else {	
      		$data['lastname'] = '';
    	}
		
		if (isset($this->request->post['telephone'])) {
      		$data['telephone'] = $this->request->post['telephone'];
    	} elseif (isset($vendors_info)) {
			$data['telephone'] = $vendors_info['telephone'];
		} else {	
      		$data['telephone'] = '';
    	}
		
		if (isset($this->request->post['fax'])) {
      		$data['fax'] = $this->request->post['fax'];
    	} elseif (isset($vendors_info)) {
			$data['fax'] = $vendors_info['fax'];
		} else {	
      		$data['fax'] = '';
    	}
		
		if (isset($this->request->post['email'])) {
      		$data['email'] = $this->request->post['email'];
    	} elseif (isset($vendors_info)) {
			$data['email'] = $vendors_info['email'];
		} else {	
      		$data['email'] = '';
    	}
		
		if (isset($this->request->post['paypal_email'])) {
      		$data['paypal_email'] = $this->request->post['paypal_email'];
    	} elseif (isset($vendors_info)) {
			$data['paypal_email'] = $vendors_info['paypal_email'];
		} else {	
      		$data['paypal_email'] = '';
    	}
		
		if (isset($this->request->post['company_id'])) {
      		$data['company_id'] = $this->request->post['company_id'];
    	} elseif (isset($vendors_info)) {
			$data['company_id'] = $vendors_info['company_id'];
		} else {	
      		$data['company_id'] = '';
    	}
		
		if (isset($this->request->post['iban'])) {
      		$data['iban'] = $this->request->post['iban'];
    	} elseif (isset($vendors_info)) {
			$data['iban'] = $vendors_info['iban'];
		} else {	
      		$data['iban'] = '';
    	}
		
		if (isset($this->request->post['bank_name'])) {
      		$data['bank_name'] = $this->request->post['bank_name'];
    	} elseif (isset($vendors_info)) {
			$data['bank_name'] = $vendors_info['bank_name'];
		} else {	
      		$data['bank_name'] = '';
    	}
		
		if (isset($this->request->post['bank_address'])) {
      		$data['bank_address'] = $this->request->post['bank_address'];
    	} elseif (isset($vendors_info)) {
			$data['bank_address'] = $vendors_info['bank_address'];
		} else {	
      		$data['bank_address'] = '';
    	}
		
		if (isset($this->request->post['swift_bic'])) {
      		$data['swift_bic'] = $this->request->post['swift_bic'];
    	} elseif (isset($vendors_info)) {
			$data['swift_bic'] = $vendors_info['swift_bic'];
		} else {	
      		$data['swift_bic'] = '';
    	}
		
		if (isset($this->request->post['accept_paypal'])) {
      		$data['accept_paypal'] = $this->request->post['accept_paypal'];
    	} elseif (isset($vendors_info)) {
			$data['accept_paypal'] = $vendors_info['accept_paypal'];
		} else {	
      		$data['accept_paypal'] = '';
    	}
		
		if (isset($this->request->post['accept_cheques'])) {
      		$data['accept_cheques'] = $this->request->post['accept_cheques'];
    	} elseif (isset($vendors_info)) {
			$data['accept_cheques'] = $vendors_info['accept_cheques'];
		} else {	
      		$data['accept_cheques'] = '';
    	}
		
		if (isset($this->request->post['accept_bank_transfer'])) {
      		$data['accept_bank_transfer'] = $this->request->post['accept_bank_transfer'];
    	} elseif (isset($vendors_info)) {
			$data['accept_bank_transfer'] = $vendors_info['accept_bank_transfer'];
		} else {	
      		$data['accept_bank_transfer'] = '';
    	}
		
		if (isset($this->request->post['tax_id'])) {
      		$data['tax_id'] = $this->request->post['tax_id'];
    	} elseif (isset($vendors_info)) {
			$data['tax_id'] = $vendors_info['tax_id'];
		} else {	
      		$data['tax_id'] = '';
    	}
		
		if (isset($this->request->post['address_1'])) {
      		$data['address_1'] = $this->request->post['address_1'];
    	} elseif (isset($vendors_info)) {
			$data['address_1'] = $vendors_info['address_1'];
		} else {	
      		$data['address_1'] = '';
    	}
		
		if (isset($this->request->post['address_2'])) {
      		$data['address_2'] = $this->request->post['address_2'];
    	} elseif (isset($vendors_info)) {
			$data['address_2'] = $vendors_info['address_2'];
		} else {	
      		$data['address_2'] = '';
    	}
		
		if (isset($this->request->post['city'])) {
      		$data['city'] = $this->request->post['city'];
    	} elseif (isset($vendors_info)) {
			$data['city'] = $vendors_info['city'];
		} else {	
      		$data['city'] = '';
    	}
		
		if (isset($this->request->post['postcode'])) {
      		$data['postcode'] = $this->request->post['postcode'];
    	} elseif (isset($vendors_info)) {
			$data['postcode'] = $vendors_info['postcode'];
		} else {	
      		$data['postcode'] = '';
    	}
		
		$this->load->model('localisation/country');
	   	$data['countries'] = $this->model_localisation_country->getCountries();
		
		if (isset($this->request->post['country_id'])) {
      		$data['country_id'] = $this->request->post['country_id'];
    	} elseif (isset($vendors_info)) {
			$data['country_id'] = $vendors_info['country_id'];
		} else {	
      		$data['country_id'] = '';
    	}

	   	if (isset($this->request->post['zone_id'])) {
      		$data['zone_id'] = $this->request->post['zone_id'];
    	} elseif (isset($vendors_info)) {
			$data['zone_id'] = $vendors_info['zone_id'];
		} else {	
      		$data['zone_id'] = '';
    	}
		
		if (isset($this->request->post['vendor_description'])) {
      		$data['vendor_description'] = $this->request->post['vendor_description'];
    	} elseif (isset($vendors_info)) {
			$data['vendor_description'] = $vendors_info['vendor_description'];
		} else {	
      		$data['vendor_description'] = '';
    	}
		
		if (isset($this->request->post['store_url'])) {
      		$data['store_url'] = $this->request->post['store_url'];
    	} elseif (isset($vendors_info)) {
			$data['store_url'] = $vendors_info['store_url'];
		} else {	
      		$data['store_url'] = '';
    	}
		
		if (isset($this->request->post['vendor_image'])) {
			$data['vendor_image'] = $this->request->post['vendor_image'];
		} elseif (isset($vendors_info)) {
			$data['vendor_image'] = $vendors_info['vendor_image'];
		} else {
			$data['vendor_image'] = '';
		}
	
		$this->load->model('tool/image');
		
		if (isset($this->request->post['vendor_image']) && is_file(DIR_IMAGE . $this->request->post['vendor_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['vendor_image'], 100, 100);
		} elseif (!empty($vendors_info) && is_file(DIR_IMAGE . $vendors_info['vendor_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($vendors_info['vendor_image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);	
		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		$data['header'] = $this->load->controller('common/vdi_header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/vdi_vendor_profile_form.tpl', $data));

  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/vdi_vendor_profile')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

 		if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_vendor_firstname');
    	}

    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_vendor_lastname');
    	}
		
		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_vendor_email');
    	}
		
		if (utf8_strlen($this->request->post['paypal_email']) > 0) {
			if ((utf8_strlen($this->request->post['paypal_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['paypal_email'])) {
				$this->error['paypal_email'] = $this->language->get('error_vendor_paypal_email');
			}
		}
		
		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_vendor_telephone');
    	}

    	if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_vendor_address_1');
    	}

    	if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
      		$this->error['city'] = $this->language->get('error_vendor_city');
    	}

		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
			$this->error['postcode'] = $this->language->get('error_vendor_postcode');
		}
		
    	if ($this->request->post['country_id'] == '') {
      		$this->error['country'] = $this->language->get('error_vendor_country');
    	}
		
		if ($this->request->post['zone_id'] == '') {
      		$this->error['zone'] = $this->language->get('error_vendor_zone');
    	}

    	if (!$this->error) {
			return TRUE;
    	} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
      		return FALSE;
    	}
  	}

  	public function zone() {
		
		$this->load->model('localisation/zone');
		
		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
        
		$output = '';
		
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
		  	$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}
	
		$this->response->setOutput($output);
  	}  

}
?>