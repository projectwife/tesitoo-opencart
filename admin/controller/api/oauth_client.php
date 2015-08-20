<?php
class ControllerApiOAuthClient extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('api/oauth_client');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('api/oauth');

		$this->getList();
	}

	public function add() {
		$this->load->language('api/oauth_client');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('api/oauth');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_api_oauth->addOAuthClient($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('api/oauth_client', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('api/oauth_client');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('api/oauth');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_api_oauth->editOAuthClient($this->request->get['oauth_client_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('api/oauth_client', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() { 
		$this->load->language('api/oauth_client');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('api/oauth');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $oauth_client_id) {
				$this->model_api_oauth->deleteOAuthClient($oauth_client_id);	
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('api/oauth_client', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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
			'href' => $this->url->link('api/oauth_client', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['insert'] = $this->url->link('api/oauth_client/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('api/oauth_client/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$data['oauth_clients'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$oauth_client_total = $this->model_api_oauth->getTotalOAuthClients();

		$results = $this->model_api_oauth->getOAuthClients($filter_data);

		foreach ($results as $result) {
			$data['oauth_clients'][] = array(
				'oauth_client_id' => $result['oauth_client_id'],
				'name'            => $result['name'],
				'edit'       	  => $this->url->link('api/oauth_client/edit', 'token=' . $this->session->data['token'] . '&oauth_client_id=' . $result['oauth_client_id'] . $url, 'SSL')
			);
		}	

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list'); 
 		$data['text_no_results'] = $this->language->get('text_no_results'); 
 		$data['text_confirm'] = $this->language->get('text_confirm'); 

		$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit'); 
		$data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->post['selected'])) { 
 			$data['selected'] = (array)$this->request->post['selected']; 
 		} else { 
 			$data['selected'] = array(); 
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

		$data['sort_name'] = $this->url->link('api/oauth_client', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $oauth_client_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('api/oauth_client', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($oauth_client_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($oauth_client_total - $this->config->get('config_limit_admin'))) ? $oauth_client_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $oauth_client_total, ceil($oauth_client_total / $this->config->get('config_limit_admin'))); 				

		$data['sort'] = $sort; 
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer'); 

		$this->response->setOutput($this->load->view('api/oauth_client_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['oauth_client_id']) ? $this->language->get('text_add') : $this->language->get('text_edit'); 

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_client_id'] = $this->language->get('entry_client_id');
		$data['entry_client_secret'] = $this->language->get('entry_client_secret');

		$data['help_name'] = $this->language->get('help_name');
		$data['help_client_id'] = $this->language->get('help_client_id');
		$data['help_client_secret'] = $this->language->get('help_client_secret');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['client_id'])) {
			$data['error_client_id'] = $this->error['client_id'];
		} else {
			$data['error_client_id'] = '';
		}

		if (isset($this->error['client_secret'])) {
			$data['error_client_secret'] = $this->error['client_secret'];
		} else {
			$data['error_client_secret'] = '';
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
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('api/oauth_client', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['oauth_client_id'])) {
			$data['action'] = $this->url->link('api/oauth_client/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('api/oauth_client/edit', 'token=' . $this->session->data['token'] . '&oauth_client_id=' . $this->request->get['oauth_client_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('api/oauth_client', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['oauth_client_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$oauth_client_info = $this->model_api_oauth->getOAuthClient($this->request->get['oauth_client_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($oauth_client_info)) {
			$data['name'] = $oauth_client_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['client_id'])) {
			$data['client_id'] = $this->request->post['client_id'];
		} elseif (!empty($oauth_client_info)) {
			$data['client_id'] = $oauth_client_info['client_id'];
		} else {
			$data['client_id'] = '';
		}

		if (isset($this->request->post['client_secret'])) {
			$data['client_secret'] = $this->request->post['client_secret'];
		} elseif (!empty($oauth_client_info)) {
			$data['client_secret'] = $oauth_client_info['client_secret'];
		} else {
			$data['client_secret'] = '';
		}

		$data['header'] = $this->load->controller('common/header'); 
 		$data['column_left'] = $this->load->controller('common/column_left'); 
 		$data['footer'] = $this->load->controller('common/footer'); 

		$this->response->setOutput($this->load->view('api/oauth_client_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'api/oauth_client')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('api/oauth');
		if ($this->model_api_oauth->getTotalOAuthClientsByClientIdAndClientSecret($this->request->post['client_id'], $this->request->post['client_secret'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['client_id']) < 3) || (utf8_strlen($this->request->post['client_id']) > 40)) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}
		else if(!ctype_alnum($this->request->post['client_id'])) {
			$this->error['client_id'] = $this->language->get('error_client_id_invalid_chars');
		}

		if ((utf8_strlen($this->request->post['client_secret']) < 20) || (utf8_strlen($this->request->post['client_secret']) > 40)) {
			$this->error['client_secret'] = $this->language->get('error_client_secret');
		}
		else  if(!ctype_alnum($this->request->post['client_secret'])) {
			$this->error['client_secret'] = $this->language->get('error_client_secret_invalid_chars');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'api/oauth_client')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

}
?>