<?php
class ControllerCatalogVendor extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/vendor');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/vendor');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendor->addVendor($this->request->post);
			
			if (!empty($this->request->post['username'])) {
				$username = $this->request->post['username'];
			} else {
				$username =  $this->request->post['username1'];
			}
			
			if (!file_exists(rtrim(DIR_IMAGE . 'catalog/', '/') . '/' . str_replace('../', '', $username)) && (isset($this->request->post['generate_path']))) {
				mkdir(rtrim(DIR_IMAGE . 'catalog/', '/') . '/' . str_replace('../', '', $username), 0777);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/vendor');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_vendor->editVendor($this->request->get['vendor_id'], $this->request->post);

			if (file_exists(rtrim(DIR_IMAGE . 'catalog/', '/') . '/' . str_replace('../', '', $this->request->post['username'])) && (isset($this->request->post['remove_path']))) {
				$this->recursiveDelete(rtrim(DIR_IMAGE . 'catalog/', '/') . '/' . str_replace('../', '', $this->request->post['username']));
			} elseif (!file_exists(rtrim(DIR_IMAGE . 'catalog/', '/') . '/' . str_replace('../', '', $this->request->post['username'])) && (isset($this->request->post['generate_path']))) {
				mkdir(rtrim(DIR_IMAGE . 'catalog/', '/') . '/' . str_replace('../', '', $this->request->post['username']), 0777);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/vendor');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/vendor');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $vendor_id) {
				$this->model_catalog_vendor->deleteVendor($vendor_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'v.vendor_name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/vendor/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/vendor/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['vendors'] = array();

		$filter_data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');
		
		$vendors_total = $this->model_catalog_vendor->getTotalVendors($filter_data);  //count vendor per page
		$results = $this->model_catalog_vendor->getVendors($filter_data); //get total vendor name
		
		$this->load->model('catalog/commission');
		$data['commissions'] = $this->model_catalog_commission->getCommissions();
		
		foreach ($results as $result) {
			
			if ($result['vendor_image'] && file_exists(DIR_IMAGE . $result['vendor_image'])) {
				$image = $this->model_tool_image->resize($result['vendor_image'], 120, 45);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 120, 45);
			}
		
			$total_products = $this->model_catalog_vendor->getTotalVendorsByVendorId($result['vendor_id']);
			
			switch ($result['commission_type']) {
				case '0': 
					$commission = $this->language->get('text_percentage') . ' (' . $result['commission'] . '%)';
					break;
				case '1':
					$commission = $this->language->get('text_fixed_rate')  . ' (' . $result['commission'] . ')';
					break;
				case '2':
					$commission_type = $this->language->get('text_pf');
					if (!strpos($result['commission'], ':') === false) {
						$dc = explode(':',$result['commission']);
						$commission = $this->language->get('text_pf') . ' (' . $dc[0] . '% + ' . $dc[1] . ')'; 
					} else {
						$commission = $this->language->get('text_pf') . '(' . $result['commission'] . '%)';
					}
					break;
				case '3':
					if (!strpos($result['commission'], ':') === false) {
						$dc = explode(':',$result['commission']);
						$commission = $this->language->get('text_fp') . ' (' . $dc[0] . ' + ' . $dc[1] . '%)'; 
					} else {
						$commission = $this->language->get('text_fp') . '(' . $result['commission'] . ')';
					}
					break;
				case '4':
					$commission = $result['commission_name']  . ' (' . $this->currency->format($result['commission'], $this->config->get('config_currency')) . ')';
					break;
				case '5':
					$commission = $result['commission_name'] . ' (' . $this->currency->format($result['commission'], $this->config->get('config_currency')) . ')';
					break;
				default: 
					$commission = '';
					break;
			}
			
			if ($result['status'] == 5) {
				$status = $this->language->get('txt_pending_approval');
			} elseif ($result['status'] == 1) {
				$status = $this->language->get('text_enabled');
			} else {
				$status = $this->language->get('txt_disabled_approval');
			}
			
			$data['vendors'][] = array(
				'vendor_id' 		=> $result['vendor_id'],
				'vendor_name'    	=> $result['vendor_name'],
				'commission_id'    	=> $result['commission_id'],
				'commission'    	=> $commission,
				'image'      		=> $image,
				'status'			=> $status,
				'total_products'	=> $total_products,
				'sort_order'    	=> $result['vsort_order'],
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['vendor_id'], $this->request->post['selected']),
				'view'				=> $this->url->link('catalog/mvd_product', 'token=' . $this->session->data['token'] . '&filter_vendor=' . $result['vendor_id'] . $url, 'SSL'),
				'edit'     			=> $this->url->link('catalog/vendor/update', 'token=' . $this->session->data['token'] . '&vendor_id=' . $result['vendor_id'] . $url, 'SSL')
			);
    	}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$data['text_percentage'] = $this->language->get('text_percentage');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		$data['text_subs_fee'] = $this->language->get('text_subs_fee');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_view_vendor_products'] = $this->language->get('text_view_vendor_products');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$data['column_vendor_commission'] = $this->language->get('column_vendor_commission');
    	$data['column_total_products'] = $this->language->get('column_total_products');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_edit'] = $this->language->get('button_edit');

 		$data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->request->get['filter_vendor_name'])) {
			$filter_vendor_name = $this->request->get['filter_vendor_name'];
		} else {
			$filter_vendor_name = NULL;
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$filter_sort_order = $this->request->get['filter_sort_order'];
		} else {
			$filter_sort_order = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_vendor_name'])) {
			$url .= '&filter_vendor_name=' . $this->request->get['filter_vendor_name'];
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$url .= '&filter_sort_order=' . $this->request->get['filter_sort_order'];
		} 

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_vendor_name'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=vendor_name' . $url, 'SSL');
		$data['sort_commission'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=commission' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $vendors_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($vendors_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($vendors_total - $this->config->get('config_limit_admin'))) ? $vendors_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $vendors_total, ceil($vendors_total / $this->config->get('config_limit_admin')));

		$data['filter_vendor_name'] = $filter_vendor_name;
			
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/vendor_list.tpl', $data));
  	}

  	private function getForm() {
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['vendor_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
    	$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
    	$data['text_default'] = $this->language->get('text_default');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$data['text_percentage'] = $this->language->get('text_percentage');
		$data['text_pf'] = $this->language->get('text_pf');
		$data['text_fp'] = $this->language->get('text_fp');
		$data['text_products'] = $this->language->get('text_products');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_remove'] = $this->language->get('text_remove');
		$data['txt_pending_approval'] = $this->language->get('txt_pending_approval');
		$data['txt_expired_date'] = $this->language->get('txt_expired_date');
		$data['txt_start_date'] = $this->language->get('txt_start_date');
		$data['txt_end_date'] = $this->language->get('txt_end_date');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		
		$data['entry_username1'] = $this->language->get('entry_username1');
		$data['entry_vendor_name'] = $this->language->get('entry_vendor_name');
		$data['entry_user_account'] = $this->language->get('entry_user_account');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_company_id'] = $this->language->get('entry_company_id');
		$data['entry_iban'] = $this->language->get('entry_iban');
		$data['entry_bank_name'] = $this->language->get('entry_bank_name');
		$data['entry_bank_addr'] = $this->language->get('entry_bank_addr');
		$data['entry_swift_bic'] = $this->language->get('entry_swift_bic');
		$data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$data['entry_bank_info'] = $this->language->get('entry_bank_info');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_paypal_email'] = $this->language->get('entry_paypal_email');
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
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_folder_path'] = $this->language->get('entry_folder_path');
		$data['entry_folder_path_remove'] = $this->language->get('entry_folder_path_remove');
		$data['entry_vendor'] = $this->language->get('entry_vendor');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_expired_date'] = $this->language->get('entry_expired_date');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_user_group'] = $this->language->get('entry_user_group');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_confirm'] = $this->language->get('entry_confirm');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_finance'] = $this->language->get('tab_finance');
		$data['tab_commission'] = $this->language->get('tab_commission');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_address'] = $this->language->get('tab_address');
		$data['tab_setting'] = $this->language->get('tab_setting');
		
		$data['help_user_account'] = $this->language->get('help_user_account');
		$data['help_username1'] = $this->language->get('help_username1');
		$data['help_commission'] = $this->language->get('help_commission');
		$data['help_limit'] = $this->language->get('help_limit');
		$data['help_email'] = $this->language->get('help_email');
		$data['help_paypal_email'] = $this->language->get('help_paypal_email');
		$data['help_image'] = $this->language->get('help_image');
		$data['help_folder_path'] = $this->language->get('help_folder_path');
		$data['help_folder_delete'] = $this->language->get('help_folder_delete');
		$data['help_folder_path_remove'] = $this->language->get('help_folder_path_remove');	
		$data['help_map_vendor_profile'] = $this->language->get('help_map_vendor_profile');

    	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['add_profile'] = false;
		
		if (!isset($this->request->get['vendor_id'])) {
			$data['add_profile'] = true;
		}
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['username1'])) {
			$data['error_username1'] = $this->error['username1'];
		} else {
			$data['error_username1'] = '';
		}
		
		if (isset($this->error['vendor_name'])) {
			$data['error_vendor_name'] = $this->error['vendor_name'];
		} else {
			$data['error_vendor_name'] = '';
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
		
		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}
		
		if (isset($this->error['link_account'])) {
			$data['error_link_account'] = $this->error['link_account'];
		} else {
			$data['error_link_account'] = '';
		}
		
		if (isset($this->error['account_validate'])) {
			$data['error_account_validate'] = $this->error['account_validate'];
		} else {
			$data['error_account_validate'] = '';
		}

   		if (isset($this->error['sort_order'])) {
			$data['error_sort_order'] = $this->error['sort_order'];
		} else {
			$data['error_sort_order'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_vendor_name'])) {
			$url .= '&filter_vendor_name=' . $this->request->get['filter_vendor_name'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['vendor_id'])) {
			$data['action'] = $this->url->link('catalog/vendor/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/vendor/update', 'token=' . $this->session->data['token'] . '&vendor_id=' . $this->request->get['vendor_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/vendor', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['token'] = $this->session->data['token'];
		
		$this->load->model('user/user');
		
		if (isset($this->request->get['vendor_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$vendors_info = $this->model_catalog_vendor->getVendor($this->request->get['vendor_id']);
			$user_info = $this->model_user_user->getUser($vendors_info['user_id']);
    	}
		
		$data['user_accounts'] = $this->model_user_user->getUsers();
		
		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}
		
		if (isset($this->request->post['user_id'])) {
      		$data['user_id'] = $this->request->post['user_id'];
    	} elseif (isset($vendors_info)) {
			$data['user_id'] = $vendors_info['user_id'];
		} else {	
      		$data['user_id'] = '';
    	}
		
		if (isset($this->request->post['user_id_1'])) {
      		$data['user_id_1'] = $this->request->post['user_id_1'];
		} elseif (isset($vendors_info)) {
			$data['user_id_1'] = $vendors_info['user_id'];
		} else {	
      		$data['user_id_1'] = '';
    	}
		
		if (isset($this->request->post['vendor_name'])) {
      		$data['vendor_name'] = $this->request->post['vendor_name'];
    	} elseif (isset($vendors_info)) {
			$data['vendor_name'] = $vendors_info['vendor_name'];
		} else {	
      		$data['vendor_name'] = '';
    	}
		
		if (isset($this->request->post['company'])) {
      		$data['company'] = $this->request->post['company'];
    	} elseif (isset($vendors_info)) {
			$data['company'] = $vendors_info['company'];
		} else {	
      		$data['company'] = '';
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
		
		if (isset($this->request->post['commission'])) {
      		$data['commission'] = $this->request->post['commission'];
    	} elseif (isset($vendors_info)) {
			$data['commission'] = $vendors_info['commission_id'];
		} else {	
      		$data['commission'] = '';
    	}
		/*add*/
		if (isset($this->request->post['product_limit'])) {
      		$data['product_limit'] = $this->request->post['product_limit'];
    	} elseif (isset($vendors_info)) {
			$data['product_limit'] = $vendors_info['product_limit_id'];
		} else {	
      		$data['product_limit'] = '';
    	}
		/*add*/
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
		
		if (isset($this->request->post['tax_id'])) {
      		$data['tax_id'] = $this->request->post['tax_id'];
    	} elseif (isset($vendors_info)) {
			$data['tax_id'] = $vendors_info['tax_id'];
		} else {	
      		$data['tax_id'] = '';
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
	
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($vendors_info)) {
			$data['sort_order'] = $vendors_info['sort_order'];
		} else {	
      		$data['sort_order'] = '';
    	}
		
		if ($this->config->get('mvd_store_activated')) {
			$data['mvd_store_activated'] = true;
		} else {
			$data['mvd_store_activated'] = false;
		}
		
		$data['commissions'] = $this->model_catalog_vendor->getCommissionLimits();
		
		$this->load->model('catalog/prolimit');
		$data['prolimits'] = $this->model_catalog_prolimit->getLimits();
		
		//user setting start
		$data['vendor_List'] = $this->model_catalog_vendor->getVendorsList();
			
		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(0);
			
		$this->load->model('setting/store');		
		$data['stores'] = $this->model_setting_store->getStores();
			
		if (isset($this->request->post['vendor_product'])) {
			$data['vendor_product'] = $this->request->post['vendor_product'];
		} elseif (!empty($user_info)) {
			$data['vendor_product'] = $user_info['vendor_permission'];
		} else { 
			$data['vendor_product'] = '';
		}
			
		if (isset($user_info['cat_permission'])) {
			$cat_permission = unserialize($user_info['cat_permission']);
		} else {
			$cat_permission = '';
		}		
			
		if (isset($this->request->post['vendor_category'])) {
			$data['vendor_category'] = $this->request->post['vendor_category'];
		} elseif (isset($cat_permission)) {
			$data['vendor_category'] = $cat_permission;
		} else { 
			$data['vendor_category'] = array();
		}
			
		if (isset($user_info['store_permission'])) {
			$store_permission = unserialize($user_info['store_permission']);
		} else {
			$store_permission = '';
		}
			
		if (isset($this->request->post['product_store'])) {
			$data['product_store'] = $this->request->post['product_store'];
		} elseif (isset($store_permission)) {
			$data['product_store'] = $store_permission;
		} else {
			$data['product_store'] = array();
		}
			
		if (isset($user_info['folder']) && !empty($user_info['folder'])) {
			$data['folder_path'] = $user_info['folder'];
		} else {
			$data['folder_path'] = false;
		}
			
		if (isset($this->request->post['user_date_start'])) {
			$data['user_date_start'] = $this->request->post['user_date_start'];
		} elseif (!empty($user_info['user_date_start'])) {
			$data['user_date_start'] = ($user_info['user_date_start'] != '0000-00-00' ? $user_info['user_date_start'] : '');
		} else {
			$data['user_date_start'] = '';
		}
			
		if (isset($this->request->post['user_date_end'])) {
			$data['user_date_end'] = $this->request->post['user_date_end'];
		} elseif (!empty($user_info['user_date_end'])) {
			$data['user_date_end'] = ($user_info['user_date_end'] != '0000-00-00' ? $user_info['user_date_end'] : '');
		} else {
			$data['user_date_end'] = '';
		}
		
		$this->load->model('user/user_group');
		$data['user_groups'] = $this->model_user_user_group->getUserGroups();
		
		if (isset($this->request->post['user_group_id'])) {
			$data['user_group_id'] = $this->request->post['user_group_id'];
		} elseif (!empty($user_info)) {
			$data['user_group_id'] = $user_info['user_group_id'];
		} else {
			$data['user_group_id'] = '50';
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($user_info)) {
			$data['status'] = $user_info['status'];
		} else {
			$data['status'] = 0;
		}
		//user setting end

		
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
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/vendor_form.tpl', $data));
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/vendor')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((utf8_strlen($this->request->post['vendor_name']) < 3) || (utf8_strlen($this->request->post['vendor_name']) > 64)) {
      		$this->error['vendor_name'] = $this->language->get('error_vendor_name');
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

		if (!isset($this->request->post['user_id']) || $this->request->post['user_id'] == 1) {
			$this->error['link_account'] = $this->language->get('error_link_account2');
			
			if ($this->request->post['user_id'] == 1) {
				$this->error['link_account'] = $this->language->get('error_link_account');
			}
		}
		
		if ($this->request->post['password']) {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}
		
		//add profile start	
		if (empty($this->request->post['user_id']) && (utf8_strlen($this->request->post['username1']) > 1)) {			
			if ((utf8_strlen($this->request->post['username1']) < 3) || (utf8_strlen($this->request->post['username1']) > 20)) {
				$this->error['warning'] = $this->language->get('error_username1');
			}
			
			$this->load->model('user/user');
			$user_info = $this->model_user_user->getUserByUsername($this->request->post['username1']);

			if ($user_info) {
				$this->error['warning'] = $this->language->get('error_username_exists');
			}
			
				
			if ((utf8_strlen($this->request->post['password']) < 1) || (utf8_strlen($this->request->post['confirm']) < 1)) {
				$this->error['password'] = $this->language->get('error_password');
			} else {
				if ($this->request->post['password']) {
					if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
						$this->error['password'] = $this->language->get('error_password');
					}

					if ($this->request->post['password'] != $this->request->post['confirm']) {
						$this->error['confirm'] = $this->language->get('error_confirm');
					}
				}
			}
			
		} elseif (!empty($this->request->post['user_id']) && (utf8_strlen($this->request->post['username1']) < 1)) {	
			$this->load->model('catalog/vendor');
			$mapping_info = $this->model_catalog_vendor->ValidateUserMapping($this->request->post['user_id']);
			
			if ($this->request->post['user_id_1'] != $this->request->post['user_id']) {
				if ($mapping_info > 0) {
					$this->error['warning'] = $this->language->get('error_mapping_validation');
				}
			}
			
		} else {
			$this->error['warning'] = $this->language->get('error_account_validate');
		}
		//add profile end

    	if (!$this->error) {
			return TRUE;
    	} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
      		return FALSE;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/vendor')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/vendor');
		
		foreach ($this->request->post['selected'] as $vendor_id) {
			
  			$vendors_total = $this->model_catalog_vendor->getTotalVendorsByVendorId($vendor_id);
    
			if ($vendors_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_vendor'), $vendors_total);	
			}	
	  	} 
		
		if (!$this->error) {
	  		return TRUE;
		} else {
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

	Private function recursiveDelete($directory) {
		if (is_dir($directory)) {
			$handle = opendir($directory);
		}
				
		if (!$handle) {
			return false;
		}
				
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				if (!is_dir($directory . '/' . $file)) {
					unlink($directory . '/' . $file);
				} else {
					$this->recursiveDelete($directory . '/' . $file);
				}
			}
		}
				
		closedir($handle);
				
		rmdir($directory);
				
		return true;
	}
}
?>