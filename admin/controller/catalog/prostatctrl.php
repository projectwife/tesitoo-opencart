<?php
class ControllerCatalogProStatCtrl extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/prostatctrl');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prostatctrl');

		$this->getList();
  	}

  	public function update() {
    	$this->load->language('catalog/prostatctrl');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/prostatctrl');
		
		if (isset($this->request->post['selected']) && $this->validateUpdate()) {
			foreach ($this->request->post['selected'] as $user_id) {				
				if ($this->request->post['user_status' . "$user_id"] == '1') {
					$this->model_catalog_prostatctrl->EnabledAllProducts($user_id);
				} else {
					$this->model_catalog_prostatctrl->DisabledAllProducts($user_id);
				}
    		}		
			$this->session->data['success'] = $this->language->get('text_success');		
		}
    	
    	$this->getList();
  	}

  	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
	
		$url = '';

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
			'href' => $this->url->link('catalog/prostatctrl', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['update'] = $this->url->link('catalog/prostatctrl/update', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$filter_data = array(
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		
		$data['users_info'] = array();
		
		$total_users = $this->model_catalog_prostatctrl->getTotalUsers($filter_data);
		$results = $this->model_catalog_prostatctrl->getUserInformation($filter_data);
		
		foreach ($results as $result) {
		
			$total_products = $this->model_catalog_prostatctrl->getTotalProductsByUserID($result['user_id']);
			
			if ($result['user_date_end'] == '0000-00-00') {
				$due_date = $this->language->get('text_nil');
			} else {
				$due_date = date('Y-m-d', strtotime($result['user_date_end']));
			}
			
			$data['users_info'][] = array(
				'user_id' 			=> $result['user_id'],
				'username' 			=> $result['username'],
				'vendor_name' 		=> $result['vendor_name'],
				'company' 			=> $result['company'],
				'flname'    		=> $result['flname'],
				'telephone'    		=> $result['telephone'],
				'email'    			=> $result['email'],
				'total_products'	=> $total_products,
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['user_id'], $this->request->post['selected']),
				'status'     		=> $result['status'],
				'due_date'     		=> $due_date
			);
    	}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['column_username'] = $this->language->get('column_username');
		$data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$data['column_company'] = $this->language->get('column_company');
    	$data['column_flname'] = $this->language->get('column_flname');
		$data['column_telephone'] = $this->language->get('column_telephone');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_due_date'] = $this->language->get('column_due_date');
		$data['column_status'] = $this->language->get('column_status');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['help_status'] = $this->language->get('help_status');
		
		$data['button_update'] = $this->language->get('button_update');

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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$pagination = new Pagination();
		$pagination->total = $total_users;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/prostatctrl', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_users) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_users - $this->config->get('config_limit_admin'))) ? $total_users : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_users, ceil($total_users / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/prostatctrl_list.tpl', $data));
  	}
	
	protected function validateUpdate() {
    	if (!$this->user->hasPermission('modify', 'catalog/prostatctrl')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
}
?>