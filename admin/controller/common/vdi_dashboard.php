<?php
class ControllerCommonVDIDashboard extends Controller {
	public function index() {
		$this->load->language('common/vdi_dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_map'] = $this->language->get('text_map');
		$data['text_activity'] = $this->language->get('text_activity');
		$data['text_recent'] = $this->language->get('text_recent');

		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/vdi_dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/vdi_dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/vdi_header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['order'] = $this->load->controller('dashboard/vdi_order');
		$data['sale'] = $this->load->controller('dashboard/vdi_sale');
		$data['map'] = $this->load->controller('dashboard/vdi_map');
		$data['chart'] = $this->load->controller('dashboard/vdi_chart');
		$data['order_stock'] = $this->load->controller('dashboard/vdi_order_stock');
		$data['recent'] = $this->load->controller('dashboard/vdi_recent');
		$data['footer'] = $this->load->controller('common/footer');
		
		//mvds
		$data['text_total_product'] = $this->language->get('text_total_product');
		$data['text_total_shipping'] = $this->language->get('text_total_shipping');
		$data['text_total_product_approval'] = $this->language->get('text_total_product_approval');
		$data['text_total_product_pendding'] = $this->language->get('text_total_product_pendding');
		$data['text_total_vendor_approval'] = $this->language->get('text_total_vendor_approval');
		//mvde

		$this->response->setOutput($this->load->view('common/vdi_dashboard.tpl', $data));
	}
}
