<?php
class ControllerDashboardVDIOrderStock extends Controller {
	public function index() {
		$this->load->language('dashboard/vdi_order_stock');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['token'] = $this->session->data['token'];

		$data['activities'] = array();

		$this->load->model('report/vdi_order_stock');

		$results = $this->model_report_vdi_order_stock->getOrderAndStock();

		foreach ($results as $result) {
			if ($result['key'] == 'new_order_product') {
				$comment = $result['name'] . sprintf($this->language->get('text_' . $result['key']),$result['order_id'],$result['order_id']);
			} else {
				$comment = sprintf($this->language->get('text_' . $result['key']),$result['product_id'],$result['name'],$result['quantity']);
			}
			
			$find = array(
				'product_id=',
				'order_id='
			);

			$replace = array(
				$this->url->link('catalog/vdi_product/edit', 'token=' . $this->session->data['token'] . '&product_id=', 'SSL'),
				$this->url->link('sale/vdi_order/info', 'token=' . $this->session->data['token'] . '&order_id=', 'SSL')
			);

			$data['activities'][] = array(
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		return $this->load->view('dashboard/vdi_order_stock.tpl', $data);
	}
}
