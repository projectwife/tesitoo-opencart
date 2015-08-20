<?php 
class ControllerAccountSignUpCallBack extends Controller {
	private $error = array(); 	
	
	public function signup_callback() {
		if (isset($this->request->post['custom'])) {
			$user_id = (int)$this->request->post['custom'];
		} else {
			$user_id = 0;
		}
	
		$this->load->model('account/signup');
		$user_info = $this->model_account_signup->ValidateUserID($user_id);	
		
		if ($user_info) {
			$request = 'cmd=_notify-validate';
		
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}
			
			if (!$this->config->get('signup_paypal_sandbox')) {
				$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
			} else {
				$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}
			
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					
			$response = curl_exec($curl);
	
			if (!$response) {
				$this->log->write('PP_SIGNUP :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}
			
			$this->log->write('PP_SIGNUP :: IPN REQUEST: ' . $request);
			$this->log->write('PP_SIGNUP :: IPN RESPONSE: ' . $response);
			
			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
				
				switch($this->request->post['payment_status']) {
					case 'Completed':
					$user_status = '1';
					break;
					
					default:
					$user_status = '5';
					break;
				}		
				
				if ($user_status == '1') {
					$this->model_account_signup->UpdateUserStatus($user_id);
				}
			}
			curl_close($curl);
		}
	}
	
	public function renew_callback() {		
		if (isset($this->request->post['custom'])) {
			$renew_id = explode(':',$this->request->post['custom']);
			$user_id = $renew_id[0];
		} else {
			$user_id = 0;
		}
		
		$this->load->model('account/signup');
		$user_info = $this->model_account_signup->ValidateUserID($user_id);	
		
		if ($user_info) {
			$request = 'cmd=_notify-validate';
		
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}
			
			if (!$this->config->get('signup_paypal_sandbox')) {
				$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
			} else {
				$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}
			
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					
			$response = curl_exec($curl);
	
			if (!$response) {
				$this->log->write('PP_SIGNUP :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}
			
			$this->log->write('PP_RENEW :: IPN REQUEST: ' . $request);
			$this->log->write('PP_RENEW :: IPN RESPONSE: ' . $response);
			
			if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0) && isset($this->request->post['payment_status'])) {
				
				switch($this->request->post['payment_status']) {
					case 'Completed':
					$user_status = '1';
					break;
					
					default:
					$user_status = '5';
					break;
				}		
				
				if ($user_status == '1') {
					$this->model_account_signup->RenewUserStatus($user_id,$renew_id[1]);
					$this->renewNotification($user_id);
				}
			}
			curl_close($curl);
		}	
	}
	
	public function renewNotification($user_id) {

    	$this->language->load('mail/signup');
		$this->load->model('account/signup');
		
		$vendor_data = $this->model_account_signup->getVendorData($user_id);
		$subject = $this->language->get('text_renew_subject');
					
		$text = sprintf($this->language->get('text_to'), $vendor_data['firstname'] . ' ' . $vendor_data['lastname']) . "<br><br>";				
		$text .= $this->language->get('text_renew_message') . "<br><br>";
		$text .= $this->language->get('text_thanks') . "<br>";
		$text .= $this->config->get('config_name') . "<br><br>";
		$text .= $this->language->get('text_system');
		
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
							
		$mail->setTo($vendor_data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
		$mail->send();
  	}
}
?>