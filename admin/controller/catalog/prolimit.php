<?php
class ControllerCatalogProLimit extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/prolimit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prolimit');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/prolimit');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prolimit');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_prolimit->addLimit($this->request->post);

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
			$this->response->redirect($this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/prolimit');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prolimit');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_prolimit->editLimit($this->request->get['product_limit_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/prolimit');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prolimit');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_limit_id) {
				$this->model_catalog_prolimit->deleteLimit($product_limit_id);
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

			$this->response->redirect($this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	protected function getList() {
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['delete'] = $this->url->link('catalog/prolimit/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['insert'] = $this->url->link('catalog/prolimit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['product_limits'] = array();
		$filter_data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		
		$prolimit_total = $this->model_catalog_prolimit->getTotalLimits($filter_data);  //count prolimit per page
		$results = $this->model_catalog_prolimit->getLimits($filter_data); //get total prolimit name

		foreach ($results as $result) {	
			$total_vendors = $this->model_catalog_prolimit->getTotalVendorsByLimitId($result['product_limit_id']);			
			$data['product_limits'][] = array(
				'product_limit_id' 	=> $result['product_limit_id'],
				'package_name' 		=> $result['package_name'],
				'product_limit'    	=> $result['product_limit'],
				'total_vendors'		=> $total_vendors,
				'sort_order'    	=> $result['sort_order'],
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['product_limit_id'], $this->request->post['selected']),
				'edit'     			=> $this->url->link('catalog/prolimit/update', 'token=' . $this->session->data['token'] . '&product_limit_id=' . $result['product_limit_id'] . $url, 'SSL')
			);
    	}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_quantity'] = $this->language->get('column_quantity');
    	$data['column_total_vendors'] = $this->language->get('column_total_vendors');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
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
		
		$data['sort_package_name'] = $this->url->link('catalog/prolimit&token=' . $this->session->data['token'] . '&sort=package_name' . $url, 'SSL');
		$data['sort_product_limit'] = $this->url->link('catalog/prolimit&token=' . $this->session->data['token'] . '&sort=product_limit' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/prolimit&token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $prolimit_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($prolimit_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($prolimit_total - $this->config->get('config_limit_admin'))) ? $prolimit_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $prolimit_total, ceil($prolimit_total / $this->config->get('config_limit_admin')));
			
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/prolimit_list.tpl', $data));
  	}

  	private function getForm() {
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['product_limit_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['help_quantity'] = $this->language->get('help_quantity');

    	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
    	
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['package_name'])) {
			$data['error_package_name'] = $this->error['package_name'];
		} else {
			$data['error_package_name'] = '';
		}
		
		if (isset($this->error['product_limit'])) {
			$data['error_product_limit'] = $this->error['product_limit'];
		} else {
			$data['error_product_limit'] = '';
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
			'href' => $this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['product_limit_id'])) {
			$data['action'] = $this->url->link('catalog/prolimit/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/prolimit/update', 'token=' . $this->session->data['token'] . '&product_limit_id=' . $this->request->get['product_limit_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/prolimit', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['product_limit_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$prolimit_info = $this->model_catalog_prolimit->getLimit($this->request->get['product_limit_id']);
    	}
		
		if (isset($this->request->post['package_name'])) {
      		$data['package_name'] = $this->request->post['package_name'];
    	} elseif (isset($prolimit_info)) {
			$data['package_name'] = $prolimit_info['package_name'];
		} else {	
      		$data['package_name'] = '';
    	}
			
		if (isset($this->request->post['product_limit'])) {
			$data['product_limit'] = $this->request->post['product_limit'];
		} elseif (isset($prolimit_info)) {
			$data['product_limit'] = $prolimit_info['product_limit'];
		} else {
			$data['product_limit'] = '';
		}
	
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($prolimit_info)) {
			$data['sort_order'] = $prolimit_info['sort_order'];
		} else {	
      		$data['sort_order'] = '';
    	}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/prolimit_form.tpl', $data));
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/prolimit')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['package_name'])) < 1) || (strlen(utf8_decode($this->request->post['package_name'])) > 64)) {
      		$this->error['package_name'] = $this->language->get('error_package_name');
    	}
		
		if ((strlen(utf8_decode($this->request->post['product_limit'])) < 1) || (!is_numeric($this->request->post['product_limit']))) {
      		$this->error['product_limit'] = $this->language->get('error_product_limit');
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
    	if (!$this->user->hasPermission('modify', 'catalog/prolimit')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/prolimit');

		foreach ($this->request->post['selected'] as $product_limit_id) {
  			$prolimit_total = $this->model_catalog_prolimit->getTotalVendorsByLimitId($product_limit_id);
    		if ($prolimit_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_delete'), $prolimit_total);	
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