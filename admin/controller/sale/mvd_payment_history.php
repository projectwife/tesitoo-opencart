<?php
class ControllerSaleMVDPaymentHistory extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/mvd_payment_history');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/mvd_payment_history');

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
			'href' => $this->url->link('sale/mvd_payment_history', 'token=' . $this->session->data['token'], 'SSL')
		);
	
		$data['histories'] = array();

		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$payment_histories = $this->model_sale_mvd_payment_history->getPaymentHistory($filter_data);
		$order_total = $this->model_sale_mvd_payment_history->getTotalPaymentHistory($filter_data);

    	foreach ($payment_histories as $payment_history) {
			$data['histories'][] = array (
				'payment_id'	=> $payment_history['payment_id'],
				'name'			=> $payment_history['name'],
				'details'		=> unserialize(trim($payment_history['details'])),
				'amount'		=> $this->currency->format($payment_history['payment_amount'], $this->config->get('config_currency')),
				'date'			=> date($this->language->get('date_format_short2'), strtotime($payment_history['payment_date']))				
				);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_list'] = $this->language->get('text_list');
		$data['config_currency'] = $this->config->get('config_currency');
		
		$data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$data['column_order_product'] = $this->language->get('column_order_product');
		$data['column_payment_amount'] = $this->language->get('column_payment_amount');
		$data['column_payment_date'] = $this->language->get('column_payment_date');

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
		
		$json['success'] = $this->language->get('text_success');
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/mvd_payment_history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/mvd_payment_history_list.tpl', $data));
  	}	
}
?>