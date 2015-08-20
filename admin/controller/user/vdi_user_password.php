<?php  
class ControllerUserVDIUserPassword extends Controller {  
	private $error = array();
   
  	public function index() {
    	$this->load->language('user/vdi_user_password');

    	$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('user/vdi_user_password');
		
		$this->getForm();
		
  	}
	
	public function update() {

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		
			if ($this->user->getId()) {
				$this->load->model('user/vdi_user_password');
				$this->model_user_vdi_user_password->editPassword($this->user->getId(), $this->request->post['password']);
				$this->load->language('user/vdi_user_password');
				$this->session->data['success'] = $this->language->get('text_change_password');
				
			}
			
			$this->response->redirect($this->url->link('user/vdi_user_password', 'token=' . $this->session->data['token'], 'SSL'));
    	}
		
		$this->load->language('user/vdi_user_password');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('user/vdi_user_password');
		$this->getForm();
	}
	
	protected function getForm() {
	
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = $this->language->get('heading_title');
		$data['entry_password'] = $this->language->get('entry_password');
    	$data['entry_confirm'] = $this->language->get('entry_confirm');
       	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/vdi_dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('user/vdi_user_password', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['action'] = $this->url->link('user/vdi_user_password/update', 'token=' . $this->session->data['token'], 'SSL');
		
    	$data['cancel'] = $this->url->link('common/vdi_dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		if ($this->user->getId()) {
			$user_info = $this->model_user_vdi_user_password->getUser($this->user->getId());
    	}

		if (isset($this->request->post['username'])) {
      		$data['username'] = $this->request->post['username'];
    	} elseif (!empty($user_info)) {
			$data['username'] = $user_info['username'];
		} else {
      		$data['username'] = '';
    	}
		
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
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['header'] = $this->load->controller('common/vdi_header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('user/vdi_user_password.tpl', $data));
	}
     	
  	private function validateForm() {
		$this->load->language('user/vdi_user_password');
		$this->load->model('user/vdi_user_password');
		
    	if (!$this->user->hasPermission('modify', 'user/vdi_user_password')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		if (isset($this->request->post['username'])) {
			$user_info = $this->model_user_vdi_user_password->getUserByUsername($this->request->post['username']);
			
			if ($user_info['user_id'] != $this->user->getId()) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		
       	if ($this->request->post['password'] || ($this->user->getId())) {
      		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}
	
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}

 }
?>