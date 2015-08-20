<?php
class ControllerShippingMVDMFlat extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/mvdm_flat');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mvdm_flat', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));		

		}
				
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/mvdm_flat', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['action'] = $this->url->link('shipping/mvdm_flat', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('catalog/mvd_product');
		$data['couriers'] = $this->model_catalog_mvd_product->getCourier();	
		
		if (isset($this->request->post['mvdm_flat_shipping_method'])) {
			$data['mvdm_flat_shipping_method'] = $this->request->post['mvdm_flat_shipping_method'];
		} else {
			$data['mvdm_flat_shipping_method'] = $this->config->get('mvdm_flat_shipping_method');
		}

		if (isset($this->request->post['mvdm_flat_tax_class_id'])) {
			$data['mvdm_flat_tax_class_id'] = $this->request->post['mvdm_flat_tax_class_id'];
		} else {
			$data['mvdm_flat_tax_class_id'] = $this->config->get('mvdm_flat_tax_class_id');
		}

		if (isset($this->request->post['mvdm_flat_geo_zone_id'])) {
			$data['mvdm_flat_geo_zone_id'] = $this->request->post['mvdm_flat_geo_zone_id'];
		} else {
			$data['mvdm_flat_geo_zone_id'] = $this->config->get('mvdm_flat_geo_zone_id');
		}
		
		if (isset($this->request->post['mvdm_flat_status'])) {
			$data['mvdm_flat_status'] = $this->request->post['mvdm_flat_status'];
		} else {
			$data['mvdm_flat_status'] = $this->config->get('mvdm_flat_status');
		}
		
		if (isset($this->request->post['mvdm_flat_sort_order'])) {
			$data['mvdm_flat_sort_order'] = $this->request->post['mvdm_flat_sort_order'];
		} else {
			$data['mvdm_flat_sort_order'] = $this->config->get('mvdm_flat_sort_order');
		}	

		$this->load->model('localisation/tax_class');
		
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$this->load->model('catalog/mvd_product');
		$data['vendors'] = $this->model_catalog_mvd_product->getVendors();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/mvdm_flat.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/mvdm_flat')) {
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