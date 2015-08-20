<?php
class ControllerSaleMVDContractHistory extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/mvd_contract_history');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/mvd_contract_history');

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
			'href' => $this->url->link('sale/mvd_contract_history', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['histories'] = array();

		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$contracts = $this->model_sale_mvd_contract_history->getContractHistory($filter_data);
		$total_history = $this->model_sale_mvd_contract_history->getTotalContractHistory($filter_data);

    	foreach ($contracts as $contract) {
	
			if ($contract['paid_status']) {
				if ($contract['status'] == '1') {
					if ((strtotime(date($this->language->get('date_format_short2'))) <= strtotime($contract['user_date_end']))) {
						$status = $this->language->get('text_active');
					} else {
						$status = $this->language->get('text_expired');
					}
				} else {
					$status = $this->language->get('text_inactive');
				}
			} else {
				$status = $this->language->get('text_inactive');
			}
			
			$data['histories'][] = array (
				'signup_id'			=> $contract['signup_fee_id'],
				'user_id'			=> $contract['user_id'],
				'username'			=> $contract['username'],
				'vendor_name'		=> $contract['vendor_name'],
				'signup_plan'		=> $contract['signup_plan'],
				'status'			=> $status,
				'selected'   		=> isset($this->request->post['selected']) && in_array($contract['signup_fee_id'], $this->request->post['selected']),				
				'signup_fee'    	=> $this->currency->format($contract['signup_fee'], $this->config->get('config_currency')),				
				'date_start'		=> date($this->language->get('date_format_short2'), strtotime($contract['user_date_start'])),
				'date_end'			=> date($this->language->get('date_format_short2'), strtotime($contract['user_date_end'])),
				'paid_status'		=> $contract['paid_status']
				);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_completed'] = $this->language->get('text_completed');
		$data['text_pending'] = $this->language->get('text_pending');
		$data['text_active'] = $this->language->get('text_active');
		$data['text_list'] = $this->language->get('text_list');
		
		$data['column_contract_id'] = $this->language->get('column_contract_id');
		$data['column_username'] = $this->language->get('column_username');
		$data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$data['column_signup_plan'] = $this->language->get('column_signup_plan');
		$data['column_signup_duration'] = $this->language->get('column_signup_duration');
		$data['column_signup_amount'] = $this->language->get('column_signup_amount');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_paid_date'] = $this->language->get('column_paid_date');
		$data['column_paid_status'] = $this->language->get('column_paid_status');
		
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
		$pagination->total = $total_history;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/mvd_contract_history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_history) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_history - $this->config->get('config_limit_admin'))) ? $total_history : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_history, ceil($total_history / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/mvd_contract_history_list.tpl', $data));
  	}	
	
	protected function validateUpdate() {
    	if (!$this->user->hasPermission('modify', 'sale/mvd_contract_history')) {
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