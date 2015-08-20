<?php
class ControllerDashboardVDIRecent extends Controller {
	public function index() {
		$this->load->language('dashboard/vdi_recent');

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		// Last 5 Orders
		$data['orders'] = array();

		$filter_data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);
		
		$results = $this->model_sale_vdi_order->getOrders($filter_data);

		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'status'        => $this->model_sale_vdi_order->getOrderStatusName($this->model_sale_vdi_order->getVendorOrderStatus($result['order_id'])),
				'total'         => $this->currency->format($result['total'] + $result['shipping_cost'] - $result['coupon_amount'] + ($this->config->get('tax_status') ? ($result['tax'] + $result['shipping_tax'] - $result['coupon_tax'] ) : 0), $result['currency_code'], $result['currency_value']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'       => $this->url->link('sale/vdi_order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
			);
		}

		return $this->load->view('dashboard/vdi_recent.tpl', $data);
	}
}
