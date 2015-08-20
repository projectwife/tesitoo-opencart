<?php
class ControllerSaleVDIContractHistory extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/vdi_contract_history');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/vdi_contract_history');

    	$this->getList();
  	}
	
	public function renew() {
    	$this->load->language('sale/vdi_contract_history');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/vdi_contract_history');

    	if (isset($this->request->post['selected']) && $this->validateUpdate()) {
			foreach ($this->request->post['selected'] as $signup_fee_id) {				
				$this->model_sale_signup_history->DeleteSignUPHistory($signup_fee_id);
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
			'href' => $this->url->link('common/vdi_dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/vdi_contract_history', 'token=' . $this->session->data['token'], 'SSL')
		);

		$old_contract = $this->model_sale_vdi_contract_history->getRenewContract();
		$getSignupRate = $this->model_sale_vdi_contract_history->getCommissionRate($old_contract['commission_id']);
		
		if ($getSignupRate) {
			if ($getSignupRate['commission_type'] == '4') {
				$data['my_type'] = True;
				$data['my_duration'] = (int)$getSignupRate['duration'];
				$data['month_year'] = $this->language->get('text_month') . $this->language->get('text_s');
				$data['due_date'] = date($this->language->get('date_format_short2'), strtotime("+" . (int)$getSignupRate['duration'] . $this->language->get('text_month'), strtotime($old_contract['user_date_end'])));
			} else {
				$data['my_type'] = False;
				$data['month_year'] = $this->language->get('text_year') . $this->language->get('text_s');
				$data['due_date'] = date($this->language->get('date_format_short2'), strtotime("+" . '1' . $this->language->get('text_year'), strtotime($old_contract['user_date_end'])));
			}
			$data['old_date'] = date($this->language->get('date_format_short2'),strtotime($old_contract['user_date_end']));
			$data['commission_type'] = $getSignupRate['commission_type'];
			$data['renew_plan'] = $getSignupRate['commission_name'];
			$data['renew_period'] = $getSignupRate['duration'];
			$data['renew_rate'] = $this->currency->format($getSignupRate['commission'], $this->config->get('config_currency'));
		}
		
		$data['renew'] = $this->url->link('sale/vdi_contract_history/renew', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['histories'] = array();

		$filter_data = array(
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$contracts = $this->model_sale_vdi_contract_history->getContractHistory($filter_data);
		$total_history = $this->model_sale_vdi_contract_history->getTotalContractHistory($filter_data);

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
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_completed'] = $this->language->get('text_completed');
		$data['text_pending'] = $this->language->get('text_pending');
		$data['text_active'] = $this->language->get('text_active');
		$data['text_products'] = $this->language->get('text_products');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['button_renew'] = $this->language->get('button_renew');
		
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
		
		$data['contract_history'] = $this->url->link('sale/vdi_contract_history/renew', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['token'] = $this->session->data['token'];
		
		$data['this_user_getVP'] = $this->user->getVP();
		$data['currency'] = $this->config->get('config_currency');
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
		$pagination->url = $this->url->link('sale/vdi_contract_history', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_history) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_history - $this->config->get('config_limit_admin'))) ? $total_history : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_history, ceil($total_history / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/vdi_header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/vdi_contract_history_list.tpl', $data));
  	}	
	
	public function ajaxrate() {
		
		$this->load->language('sale/vdi_contract_history');
		$this->load->model('sale/vdi_contract_history');
		
		$json = array();
		
    	$old_contract = $this->model_sale_vdi_contract_history->getRenewContract();
		$getSignupRate = $this->model_sale_vdi_contract_history->getCommissionRate($old_contract['commission_id']);
		
		if ($getSignupRate) {
			if ($getSignupRate['commission_type'] == '4') {
				$data['my_type'] = True;
				$data['my_duration'] = (int)$getSignupRate['duration'];
				$month_year = $this->language->get('text_month');
				$getRate = (float)$getSignupRate['commission']/$getSignupRate['duration'];
			} else {
				$data['my_type'] = False;
				$month_year = $this->language->get('text_year');
				$getRate = (float)$getSignupRate['commission'];
			}
			
			$json = array(
				'renew_commission_id' 	=> $getSignupRate['commission_id'],
				'renew_plan'    		=> $getSignupRate['commission_name'],
				'renew_cycle'  			=> $getSignupRate['duration'],
				'renew_rate'    		=> $getRate*$this->request->get['renewcycle'],
				'renew_lrate'    		=> $this->currency->format($getRate*$this->request->get['renewcycle'], $this->config->get('config_currency')),
				'renew_due_date'    	=> date($this->language->get('date_format_short2'), strtotime("+" . $this->request->get['renewcycle'] . $month_year, strtotime($old_contract['user_date_end'])))
			);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
  	}
	
	public function paynow() {
	
		$this->load->language('sale/vdi_contract_history');
		$this->load->model('sale/vdi_contract_history');
		
		$old_contract = $this->model_sale_vdi_contract_history->getRenewContract();
		
		$data = array();

		$data = array(
			'user_id'			=> $this->user->getId(),
			'signup_fee'		=> $this->request->get['renew_rate'],
			'username'			=> $old_contract['username'],
			'vendor_name'		=> $old_contract['vendor_name'],
			'commission_type'	=> $this->request->get['commission_type'],			
			'signup_plan'		=> $this->request->get['renew_plan'],			
			'renew_period'		=> $this->request->get['renew_period'],			
			'user_date_start'	=> date($this->language->get('date_format_short2'), strtotime($this->request->get['old_date'])),
			'user_date_end'		=> date($this->language->get('date_format_short2'), strtotime($this->request->get['next_due_date']))
		);

		$this->model_sale_vdi_contract_history->addRenewContract($data);
		
		if ($this->config->get('mvd_signup_paypal_email')) {
			$custom_id = $this->user->getId() . ':' . $this->request->get['next_due_date'];
			$request = 'cmd=_xclick';		
			$request .= '&business=' . $this->config->get('mvd_signup_paypal_email');
			$request .= '&item_name=' . html_entity_decode($this->language->get('text_signup_plan') . $this->request->get['renew_plan'], ENT_QUOTES, 'UTF-8');			
			$request .= '&notify_url=' . HTTPS_CATALOG . 'index.php?route=account/signup_callback/renew_callback';
			$request .= '&cancel_return=' . HTTPS_CATALOG . 'index.php?route=account/signup';
			$request .= '&return=' . HTTPS_CATALOG . 'index.php?route=account/renewalsuccess';
			$request .= '&currency_code=' . $this->config->get('config_currency');
			$request .= '&amount=' . $this->request->get['renew_rate'];
			$request .= '&custom=' . $custom_id;
			
			if ($this->config->get('mvd_signup_paypal_sandbox')) {
				$json['success'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr?' . $request;
			} else {
				$json['success'] = 'https://www.paypal.com/cgi-bin/webscr?' . $request;
			}
		} else {
			$json['error'] = $this->language->get('text_paypal_error');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	protected function validateUpdate() {
    	if (!$this->user->hasPermission('modify', 'sale/vdi_contract_history')) {
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