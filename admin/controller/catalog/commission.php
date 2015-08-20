<?php
class ControllerCatalogCommission extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/commission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_commission->addCommission($this->request->post);

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
			$this->response->redirect($this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_commission->editCommission($this->request->get['commission_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $commission_id) {
				$this->model_catalog_commission->deleteCommission($commission_id);
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

			$this->response->redirect($this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	protected function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'commission_type';
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
			'href' => $this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['insert'] = $this->url->link('catalog/commission/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/commission/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['commissions'] = array();

		$filter_data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		
		$commissions_total = $this->model_catalog_commission->getTotalCommissions($filter_data);  //count commission per page
		$results = $this->model_catalog_commission->getCommissions($filter_data); //get total commission name

		foreach ($results as $result) {
			$total_vendors = $this->model_catalog_commission->getTotalVendorsByCommissionId($result['commission_id']);
			
			if ($result['commission_type'] == '0') { 
				$commission_type = $this->language->get('text_percentage');
				$commission = $result['commission'];
			 } elseif ($result['commission_type'] == '1') { 
				$commission_type = $this->language->get('text_fixed_rate');
				$commission = $result['commission'];
			} elseif ($result['commission_type'] == '2') {  
				$commission_type = $this->language->get('text_pf');
				if (!strpos($result['commission'], ':') === false) {
					$dc = explode(':',$result['commission']);
					$commission = $dc[0] . '% + ' . $dc[1]; 
				} else {
					$commission = $result['commission'];
				}
			} elseif ($result['commission_type'] == '3') { 
				$commission_type = $this->language->get('text_fp');
				if (!strpos($result['commission'], ':') === false) {
					$dc = explode(':',$result['commission']);
					$commission = $dc[0] . ' + ' . $dc[1] . '%'; 
				} else {
					$commission = $result['commission'];
				}
			} elseif ($result['commission_type'] == '4') { 
				$commission_type = $this->language->get('text_month');
				$commission = $this->language->get('text_subs_fee') . $this->currency->format($result['commission'], $this->config->get('config_currency'));
			} elseif ($result['commission_type'] == '5') { 
				$commission_type = $this->language->get('text_year');
				$commission = $this->language->get('text_subs_fee') . $this->currency->format($result['commission'], $this->config->get('config_currency'));
			}					
			
			$data['commissions'][] = array(
				'commission_id' 	=> $result['commission_id'],
				'commission_name' 	=> $result['commission_name'],
				'commission_type'   => $commission_type,
				'commission'    	=> $commission,
				'total_vendors'		=> $total_vendors,
				'sort_order'    	=> $result['sort_order'],
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['commission_id'], $this->request->post['selected']),
				'edit'     			=> $this->url->link('catalog/commission/update', 'token=' . $this->session->data['token'] . '&commission_id=' . $result['commission_id'] . $url, 'SSL')
			);
    	}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_commission'] = $this->language->get('column_commission');
    	$data['column_total_vendors'] = $this->language->get('column_total_vendors');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$data['text_percentage'] = $this->language->get('text_percentage');
		$data['text_pf'] = $this->language->get('text_pf');
		$data['text_fp'] = $this->language->get('text_fp');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['button_insert'] = $this->language->get('button_insert');
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
		
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_commission_name'] = $this->url->link('catalog/commission&token=' . $this->session->data['token'] . '&sort=commission_name' . $url, 'SSL');
		$data['sort_commission_type'] = $this->url->link('catalog/commission&token=' . $this->session->data['token'] . '&sort=commission_type' . $url, 'SSL');
		$data['sort_commission'] = $this->url->link('catalog/commission&token=' . $this->session->data['token'] . '&sort=commission' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/commission&token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $commissions_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($commissions_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($commissions_total - $this->config->get('config_limit_admin'))) ? $commissions_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $commissions_total, ceil($commissions_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/commission_list.tpl', $data));
  	}

  	private function getForm() {
	
    	$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = !isset($this->request->get['commission_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_subscription'] = $this->language->get('entry_subscription');
		$data['entry_duration'] = $this->language->get('entry_duration');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['help_duration'] = $this->language->get('help_duration');
		$data['help_subscription'] = $this->language->get('help_subscription');
		$data['help_commission'] = $this->language->get('help_commission');
		
		$data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$data['text_percentage'] = $this->language->get('text_percentage');
		$data['text_pf'] = $this->language->get('text_pf');
		$data['text_fp'] = $this->language->get('text_fp');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		
    	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
		
    	
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['commission_name'])) {
			$data['error_commission_name'] = $this->error['commission_name'];
		} else {
			$data['error_commission_name'] = '';
		}
		
		if (isset($this->error['commission'])) {
			$data['error_commission'] = $this->error['commission'];
		} else {
			$data['error_commission'] = '';
		}

   		if (isset($this->error['sort_order'])) {
			$data['error_sort_order'] = $this->error['sort_order'];
		} else {
			$data['error_sort_order'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_commission_type'])) {
			$url .= '&filter_commission_type=' . $this->request->get['filter_commission_type'];
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
			'href' => $this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['commission_id'])) {
			$data['action'] = $this->url->link('catalog/commission/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/commission/update', 'token=' . $this->session->data['token'] . '&commission_id=' . $this->request->get['commission_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/commission', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['commission_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$commissions_info = $this->model_catalog_commission->getCommission($this->request->get['commission_id']);
    	}
		
		if (isset($this->request->post['commission_name'])) {
      		$data['commission_name'] = $this->request->post['commission_name'];
    	} elseif (isset($commissions_info)) {
			$data['commission_name'] = $commissions_info['commission_name'];
		} else {	
      		$data['commission_name'] = '';
    	}
		
		if (isset($this->request->post['commission_type'])) {
      		$data['commission_type'] = $this->request->post['commission_type'];
    	} elseif (isset($commissions_info)) {
			$data['commission_type'] = $commissions_info['commission_type'];
		} else {	
      		$data['commission_type'] = '';
    	}
		
		if (isset($this->request->post['duration'])) {
      		$data['duration'] = $this->request->post['duration'];
    	} elseif (isset($commissions_info)) {
			$data['duration'] = $commissions_info['duration'];
		} else {	
      		$data['duration'] = '';
    	}
		
		if (isset($this->request->post['commission'])) {
			$data['commission'] = $this->request->post['commission'];
		} elseif (isset($commissions_info)) {
			$data['commission'] = $commissions_info['commission'];
		} else {
			$data['commission'] = '';
		}
		
		$this->load->model('catalog/prolimit');
		$data['product_limits'] = $this->model_catalog_prolimit->getLimits();

		if (isset($this->request->post['product_limit_id'])) {
			$data['product_limit_id'] = $this->request->post['product_limit_id'];
		} elseif (isset($commissions_info)) {
			$data['product_limit_id'] = $commissions_info['product_limit_id'];
		} else {
			$data['product_limit_id'] = '1';
		}
	
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($commissions_info)) {
			$data['sort_order'] = $commissions_info['sort_order'];
		} else {	
      		$data['sort_order'] = '';
    	}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/commission_form.tpl', $data));
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/commission')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['commission_name'])) < 1) || (strlen(utf8_decode($this->request->post['commission_name'])) > 64)) {
      		$this->error['commission_name'] = $this->language->get('error_commission_name');
    	}
		
		if ((strlen(utf8_decode($this->request->post['commission'])) < 1)) {
      		$this->error['commission'] = $this->language->get('error_commission');
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

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/commission')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/commission');

		foreach ($this->request->post['selected'] as $commission_id) {
  			$commissions_total = $this->model_catalog_commission->getTotalVendorsByCommissionId($commission_id);
    		if ($commissions_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_delete'), $commissions_total);	
			}	
	  	} 
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

}
?>