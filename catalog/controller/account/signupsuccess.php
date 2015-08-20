<?php 
class ControllerAccountSignUpSuccess extends Controller {  
	public function index() {
    	$this->language->load('account/signup');
  
    	$this->document->setTitle($this->language->get('heading_title_signup'));

		$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	); 

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/signup', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_success'),
			'href'      => $this->url->link('account/success'),
        	'separator' => $this->language->get('text_separator')
      	);

    	$data['heading_title'] = $this->language->get('heading_title');
		
		if (!$this->config->get('mvd_signup_auto_approval')) {
			$data['text_message'] = sprintf($this->language->get('text_approval_required'), $this->config->get('config_name'), $this->url->link('common/home'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), HTTP_SERVER . 'admin');
		}
		
    	$data['button_continue'] = $this->language->get('button_continue');
		
		$data['continue'] = $this->url->link('common/home');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}	
  	}
}
?>