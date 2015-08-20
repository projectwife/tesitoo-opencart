<?php

class ControllerAccountAccountBaseAPI extends ApiController {
	
	private $defaultParameters = array(
		'fax' => ''
	);

	public function index($args = array()) {
		if ($this->request->isPutRequest()) {
			$this->put();
		}
		else if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
		
	}

	public function redirect($url, $status = 302) {
		switch($url) {
			case 'account/login': // User not logged in
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN));
				break;

			case 'account/account': // Success
				break;
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		$data = parent::getInternalRouteData('account/edit');
		
		$account = array('account' => $this->getAccount($data));
		$this->response->setOutput($account);
	}

	public function put() {
		$this->request->setDefaultParameters($this->defaultParameters);

		$this->request->server['REQUEST_METHOD'] = 'POST';
		$data = parent::getInternalRouteData('account/edit');

		ApiException::evaluateErrors($data);

		$account = array('account' => $this->getAccount($data));
		$this->response->setOutput($account);
	}

	/**
	 * Helper methods
	 */
	
	protected function getAccount($data) {
		$account['customer_id'] = (int)$this->customer->getId();
		$account['firstname'] = $data['firstname'];
		$account['lastname'] = $data['lastname'];
		$account['email'] = $data['email'];
		$account['telephone'] = $data['telephone'];
		$account['fax'] = $data['fax'];
		$account['custom_fields'] = $this->processCustomFields($data['custom_fields'], $data['account_custom_field']);
		$account['newsletter'] = $this->customer->getNewsletter() == '1' ? true : false;
		$rewardPoints = $this->customer->getRewardPoints();
		$account['reward_points'] = $rewardPoints == null ? 0 : $rewardPoints;
		$account['balance'] = $this->currency->format($this->customer->getBalance());

		$this->load->model('account/customer_group');
		$customerGroup = $this->model_account_customer_group->getCustomerGroup($this->customer->getGroupId());
		$account['customer_group'] = $this->processCustomerGroup($customerGroup);

		return $account;
	}

	protected function processCustomerGroup($customerGroup) {
		$customerGroup['customer_group_id'] = (int)$customerGroup['customer_group_id'];

		unset($customerGroup['approval']);
		unset($customerGroup['sort_order']);
		unset($customerGroup['language_id']);

		return $customerGroup;
	}

	protected function processCustomFields($customFields, $accountCustomFields) {
		foreach($customFields as $key => &$customField) {
			if($customField['location'] != 'account') {
				// Can remove this item since it's not an account custom field.
				unset($customFields[$key]);
			}
			else {
				$customField['custom_field_id'] = (int)$customField['custom_field_id'];
				unset($customField['location']);
				unset($customField['sort_order']);
				
				if(isset($accountCustomFields[$customField['custom_field_id']])) {
					// Override the value when there the customer already filled in these fields earlier.
					$customField['value'] = $accountCustomFields[$customField['custom_field_id']];
				}

				if(isset($customField['custom_field_value'])) {
					foreach($customField['custom_field_value'] as &$customFieldValue) {
						$customFieldValue['custom_field_value_id'] = (int)$customFieldValue['custom_field_value_id'];
					}
				}
			}
		}

		return array_values($customFields);
	}
 
}

?>